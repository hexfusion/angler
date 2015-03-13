package Angler::Routes::Cart;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Ajax;
use Dancer::Plugin::Form;
use Dancer::Plugin::Auth::Extensible;
use Class::Method::Modifiers 'install_modifier';

use Angler::Cart;
use Angler::Data::Tokens;
use Angler::Forms::Checkout;
use Angler::Plugin::History;
use Angler::Shipping;

=head1 HOOKS

=head2 before_cart_display

=cut

hook 'before_cart_display' => sub {
    my $tokens = shift;

    $tokens->{title} = "Cart";

    # related_products

    my @canonical_skus =
      map { $_->canonical_sku || $_->sku } cart->products_array;

    if ( @canonical_skus ) {
        my $related_products =
          shop_schema->resultset('MerchandisingProduct')->search(
            {
                'me.sku'  => { -in => \@canonical_skus },
                'me.type' => 'related',
            },
          )->related_resultset('product_related')->rand->search(
            {
                'product_related.active' => 1,
                'product_related.sku'    => { -not_in => \@canonical_skus },
            },
            {
                rows => config->{cart}->{related_product}->{qty} || 3,
            }
          )->listing( { users_id => session('logged_in_user_id') } );

        $tokens->{related_products} = $related_products
          if $related_products->first;
    }

    $tokens->{countries} = Angler::Shipping::deliverable_countries(shop_schema);

    # shipping quote form

    my $form = form('shipping-quote');

    my $form_scope;
    if ( !param('get_quote') ) {
        $form_scope = 'session';
    }
    my $form_values = $form->values($form_scope);

    if (logged_in_user) {
        # retrieve shipping address
        #TODO this should be a search and a dropdown to select if multiple
        my $ship_adr = shop_address->search(
            {
                users_id => session('logged_in_user_id'),
                type => 'shipping',
            },
            {
                order_by => {-desc => 'last_modified'},
                rows => 1,
            },
        )->single;

        if ($ship_adr) {
            debug "user_address: Shipping address found: ", $ship_adr->id;

            $form_values->{postal_code} ||= $ship_adr->postal_code;
            $form_values->{country} ||= $ship_adr->country_iso_code;
        }
    }

    &shipping_quote( $form_values, $tokens );

    $tokens->{"extra-js-file"} = 'cart.js';
};

=head1 METHODS

=head2 Dancer::Plugin::Interchange6::Cart after BUILD (modifier)

=cut

install_modifier "Dancer::Plugin::Interchange6::Cart", "after", "BUILD", sub {
    my $self = shift;

    foreach my $cart_product ( $self->products_array ) {


        if ( !defined $cart_product->weight ) {

            my $sku =
                $cart_product->canonical_sku
              ? $cart_product->canonical_sku
              : $cart_product->sku;

            eval {
                my $weight =
                  shop_schema->resultset('Product')->find($sku)
                  ->search_related(
                    'navigation_products',
                    {
                        'attribute.name' => 'weight',
                        'attribute.type' => 'navigation',
                    },
                    {
                        columns    => [],
                        '+columns' => { weight => 'attribute_value.value' },
                        join       => {
                            navigation => {
                                navigation_attributes => [
                                    'attribute',
                                    {
                                        navigation_attribute_values =>
                                          'attribute_value'
                                    }
                                ]
                            }
                        },
                        order_by => { -desc => 'navigation.priority' },
                        rows     => 1,
                    }
                  )->single->get_column('weight');

                if ( defined $weight ) {
                    $cart_product->set_weight($weight);
                }
                else {
                    warning "No navigation weight found for $sku";
                }
            };
            if ( $@ ) {
                warning "kaboom in Cart after BUILD: ", $@;
            }
        }
    }
};

=head2 shipping_quote( $form_values, $tokens )

Handle shipping quote form for cart get/post and ajax shipping-quote adding
appropriate tokens.

=cut

sub shipping_quote {
    my ( $form_values, $tokens ) = @_;

    my $cart = cart;

    my $subtotal = $cart->subtotal;
    my $free_shipping_amount = config->{free_shipping}->{amount};
    my $free_shipping_gap;

    # determine whether shipping is free or determine missing amount
    # FIXME: free shipping not yet implemented
    if ($free_shipping_amount > $subtotal) {
        $tokens->{free_shipping_gap} = $free_shipping_amount - $subtotal;
    }
    else {
        $tokens->{free_shipping} = 1;
    }

    $tokens->{countries} = Angler::Shipping::deliverable_countries(shop_schema);

    # shipping quote form

    my $form = form('shipping-quote');

    # set country if we don't already have it
    $form_values->{country} ||= 'US';

    eval {
        my $angler_cart = Angler::Cart->new(
            schema            => shop_schema,
            cart              => $cart,
            shipment_rates_id => $form_values->{shipping_rate},
            country           => $form_values->{country},
            postal_code       => $form_values->{postal_code},
        );

        if ( $angler_cart->shipment_rates ) {
            $tokens->{show_shipping_rates} = 1;
            $tokens->{shipping_rates}      = $angler_cart->shipment_rates;
        }

        $tokens->{cart_shipping} = $angler_cart->shipping_cost;
        $tokens->{cart_tax}      = $angler_cart->tax;
    };

    $tokens->{cart_total} = $cart->total;

    # fill shipping-quote form & stash back into session
    $form->fill($form_values);
    $form->to_session;
    $tokens->{form} = $form;
};

=head2 select_quote

=cut

sub select_quote {
    my $tokens = shift;

    my $ship_method_id = param('shipping_method');

    if ($ship_method_id) {
        debug "Selecting shipping method $ship_method_id in SQ route.";
        session shipping_method => $ship_method_id;
    }
}

=head1 ROUTES

=head2 ajax /shipping-quote

=cut

ajax '/shipping-quote' => sub {
    my %ret;
    my $tokens = {};
    &shipping_quote( params, $tokens );
    if ( $tokens->{shipping_rates} ) {
        %ret = (
            type  => "success",
            rates => $tokens->{shipping_rates},
            tax   => $tokens->{cart_tax},
            total => $tokens->{cart_total},
        );
    }
    else {
        $ret{type} = "fail";
    }
    return to_json \(%ret, %$tokens);
};

true;
