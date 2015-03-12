package Angler::Routes::Cart;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
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
    my ($values) = @_;
    my $cart = cart;
    my $subtotal = $cart->subtotal;
    my $free_shipping_amount = config->{free_shipping}->{amount};
    my $free_shipping_gap;

    $values->{title} = "Cart";

    # related_products
    my @canonical_skus =
      map { $_->canonical_sku || $_->sku } $cart->products_array;

    if ( @canonical_skus ) {
        my $related_products =
          shop_schema->resultset('MerchandisingProduct')->search(
            {
                'me.sku'  => { -in => \@canonical_skus },
                'me.type' => 'related',
            },
            {
                join => { product => { canonical => 'variants' }},
            }
          )->related_resultset('product_related')->rand->search(
            {
                'product_related.active' => 1,
                'product_related.sku'    => { -not_in => \@canonical_skus },
            },
            {
                rows => config->{cart}->{related_product}->{qty} || 3,
            }
          )->listing( { users_id => session('logged_in_user_id') } );

        $values->{related_products} = $related_products
          if $related_products->count;
    }

    # determine whether shipping is free or determine missing amount
    if ($free_shipping_amount > $subtotal) {
        $values->{free_shipping_gap} = $free_shipping_amount - $subtotal;
    }
    else {
        $values->{free_shipping} = 1;
    }

    $values->{countries} = Angler::Shipping::deliverable_countries(shop_schema);

    my $form = form('shipping-quote');
    my $form_values = $form->values('session');

    # set country if we don't already have it
    $form_values->{country} ||= 'US';

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

            debug "user postal code ", $form_values->{postal_code};
            debug "user country ", $form_values->{country};
        }
    }

    my $angler_cart = Angler::Cart->new(
        schema => shop_schema,
        cart => $cart,
        shipping_methods_id => session('shipping_method') || 0,
        country => $form_values->{country},
        postal_code => $form_values->{postal_code},
        user_id => session('logged_in_users_id'),
    );

    my $rates = Angler::Shipping::show_rates($angler_cart, !param('get_quote'));
    $values->{shipping_methods} = [];
    if ($rates && @$rates) {
        my @shipping_rates;
        foreach my $rate (@$rates) {
            my $iref = {
                value => $rate->{carrier_service},
                label => "$rate->{service} $rate->{rate}\$",
            };
            if ($rate->{carrier_service} == $angler_cart->shipping_methods_id) {
                $iref->{checked} = 'checked';
            }

            push @shipping_rates, $iref;
        }
        $values->{shipping_methods} = \@shipping_rates;
        if (@shipping_rates) {
            $values->{show_shipping_methods} = 1;
        }
        debug "shipping methods are", $values->{shipping_methods};
    }
    else {
        debug "No rates found for: ", $form_values;
    }

    if ($form_values->{shipping_method}) {
        $values->{shipping_method} = $form_values->{shipping_method};
        debug "Shipping method is " . $form_values->{shipping_method};
    }
    $angler_cart->update_costs;

    $form_values->{country} = $angler_cart->country;
    $form_values->{postal_code} = $angler_cart->postal_code;

    $values->{cart_shipping} = $angler_cart->shipping_cost;
    $values->{cart_tax} = $angler_cart->tax;
    $values->{cart_total} = $cart->total;

    # $values->{shipping_methods} = $angler_cart->shipping_methods;

    # unless (@{$values->{shipping_methods}}) {
    # $values->{shipping_warning} = 'No shipping methods for this country/zip';
    # }

    # filling cart form
    $form->fill($form_values);
    $values->{form} = $form;

    $values->{"extra-js-file"} = 'cart.js';
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
                  schema->resultset('Product')->find($sku)->search_related(
                    'navigation_products',
                    {
                        'attribute.name' => 'weight',
                        'attribute.type' => 'navigation',
                    },
                    {
                        columns    => [],
                        '+columns' => { weight => 'attribute_value.value' },
                        join => {
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
                        rows => 1,
                    }
                  )->single->get_column('weight');

                $cart_product->set_weight( $weight );
            };
            if ( $@ ) {
                warning "No navigation weight found for sku: ",
                  $cart_product->sku;
            }
        }
    }
};

=head1 ROUTES

=head2 post /shipping-quote

=cut

post '/shipping-quote' => sub {
    my $form = form('shipping-quote');

    my $tokens;

    # force update of form values
    my $values = $form->values;

    $tokens->{'form'} = $form;

    # save to session so cart route picks it up
    $form->to_session;

    if ( param('get_quote') && param('country') && param('postal_code') ) {

        my $zone = shop_schema->resultset('Zone')
          ->find( { zone => "Deliverable Countries" } );

        # TODO: fail here if the zone is missing instead of going back to cart
        return template('/cart', $tokens) unless $zone;

        my @countries = $zone->zone_countries->get_column('country_iso_code');

        my $dtv = Data::Transpose::Validator->new(requireall => 1);

        $dtv->field( country     => 'String' );
        $dtv->field( postal_code => 'String' );

        my $clean = $dtv->transpose($values);

        if (!$clean || $dtv->errors) {
            $tokens->{errors} = $dtv->errors_hash;
            return template '/cart', $tokens;
        }

        unless (
            Angler::Validator::country_and_postal_code(
                $values->{country}, $values->{postal_code}
            )
          )
        {
            # TODO: set appropriate errors.
            # TODO: are these errors handles by the template?
            return template '/cart', $tokens;
        }

        my $angler_cart = Angler::Cart->new(
            cart        => shop_cart,
            schema      => shop_schema,
            country     => param('country'),
            postal_code => param('postal_code')
        );
        $angler_cart->update_costs;

        $tokens->{shipping_methods} = $angler_cart->shipping_methods;

        template "cart", $tokens;
    }
    elsif (param('select_quote')) {
        # selecting quotes

        my $ship_method_id = param('shipping_method');

        if ($ship_method_id) {
            debug "Selecting shipping method $ship_method_id in SQ route.";
            session shipping_method => $ship_method_id;
        }
    }

    redirect '/cart';
};

true;
