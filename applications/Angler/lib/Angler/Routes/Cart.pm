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
    if ( !param('get_quote') && !param('select_quote') ) {
        debug "getting shipping-quote from session";
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

=head2 shipping_quote( $form_values, $tokens, $use_easypost )

Handle shipping quote form for cart get/post and ajax shipping-quote adding
appropriate tokens.

=cut

sub shipping_quote {
    my ( $form_values, $tokens, $use_easypost ) = @_;

    debug "params: ", params;

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

    debug "Getting quote for cart with weight: ", $cart->weight;
    debug "shipment_rates_id: ", $form_values->{shipping_rate};

    eval {
        my $angler_cart = Angler::Cart->new(
            schema            => shop_schema,
            cart              => $cart,
            shipment_rates_id => $form_values->{shipping_rate},
            country           => $form_values->{country},
            postal_code       => $form_values->{postal_code},
            use_easypost      => $use_easypost,
        );

        if ( $angler_cart->shipment_rates ) {
            $tokens->{show_shipping_rates} = 1;
            $tokens->{shipping_rates}      = $angler_cart->shipment_rates;
            debug "Found shipping rates: ", $angler_cart->shipment_rates;
        }

        $tokens->{cart_shipping} = $angler_cart->shipping_cost;
        $tokens->{cart_tax}      = $angler_cart->tax;
    };
    if ( $@ ) {
        warning "KABOOM! shipping_quote failure: ", $@;
    }

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

=head2 ajax /cart/shipping-quote

=cut

ajax '/cart/shipping-quote' => sub {
    debug "in /cart/shipping-quote";
    my %ret;
    my $tokens = {};
    my $params = params;
    &shipping_quote( $params, $tokens, 1 );
    if ( $tokens->{shipping_rates} ) {
        %ret = (
            type     => "success",
            rates    => $tokens->{shipping_rates},
            tax      => $tokens->{cart_tax},
            shipping => $tokens->{cart_shipping},
            total    => $tokens->{cart_total},
        );
    }
    else {
        $ret{type} = "fail";
    }
    return to_json \%ret;
};

ajax '/cart/select-shipping' => sub {
    my %ret = ( type => "fail" );
    if ( my $rates_id = param('rates_id') ) {
        if ( my $shipment_rate =
            shop_schema->resultset('ShipmentRate')->find($rates_id) )
        {
            my $price = $shipment_rate->price;
            my $cart = cart;
            $cart->apply_cost(name => 'shipping', amount => $price );
            %ret = (
                type     => "success",
                shipping => $price,
                total    => $cart->total,
            );
        }
    }
    return to_json \%ret;
};


true;
