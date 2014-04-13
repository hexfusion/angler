package Angler::Cart;

use strict;
use warnings;

use Angler::Shipping;
use Angler::Tax;

use Moo;

=head1 Angler::Cart - calculate shipping and salestax costs

=head1 SYNOPSIS

    my $angler_cart = Angler::Cart->new(
        schema => shop_schema,
        cart => $cart,
        shipping_methods_id => $form_values->{shipping_method},
        user_id => session('logged_in_users_id'));

    $angler_cart->update_costs;

    $tax = $angler_cart->tax;
    $shipping = $angler_cart->shipping_cost;

=head1 Attributes

=head2 user_id

user_id for authenticated users.

=cut

has user_id => (
    is => 'rw',
    required => 1,
);

=head2 cart

L<Interchange6::Cart> object.

=cut

has cart => (
    is => 'rw',
    required => 1,
);

=head2 schema

L<Interchange6::Schema> object.

=cut

has schema => (
    is => 'rw',
    required => 1,
);

=head2 country

Shipping country, defaults to C<US>.

=cut

has country => (
    is => 'rw',
    default => 'US',
);

=head2 postal_code

Postal code (required).

=cut

has postal_code => (
    is => 'rw',
    required => 1,
);

=head2 shipping_methods_id

Sets the current shipping method
(L<Interchange6::Schema::Result::ShipmentMethod>).

=cut

has shipping_methods_id => (
    is => 'rw',
);

=head2 state

Returns state, determined from country and postal_code.

=cut

has state => (
    is => 'rw',
);

=head2 shipping_cost

Returns shipping_cost.

=cut

has shipping_cost => (
    is => 'rwp',
);

=head2 shipping_methods

Returns shipping methods.

=cut

has shipping_methods => (
    is => 'rwp',
);

=head2 tax

Returns salestax amount.

=cut

has tax => (
    is => 'rwp',
);

sub update_costs {
    my ($self) = @_;
    my $schema = $self->schema;
    my $cart = $self->cart;

    if (my $user_id = $self->user_id) {
        my $user = $schema->resultset('User')->find($user_id);
        my $address = $user->Address->search(
            {
                type => 'shipping',
                active => 1
            });

        if ($address) {
            unless ($self->postal_code) {
                $self->postal_code($address->postal_code);
            }
            unless ($self->country) {
                $self->country($address->country_iso_code);
            }
        }
    }

    unless ($self->country) {
        $self->country('US');
    }

    my $shipping_methods_id = $self->shipping_methods_id;
    my $shipping_cost;

    if ($shipping_methods_id) {
        # determine and apply shipping cost
        $shipping_cost =
            Angler::Shipping::shipping_rate($schema, $shipping_methods_id);
        $cart->apply_cost( amount => $shipping_cost, name => 'shipping' );
        $shipping_cost = $cart->cost('shipping');
    }
    else {
        $shipping_cost = 0;
    }

    $self->_set_shipping_cost($shipping_cost);

    # Update shipping methods
    my $shipping_methods =  Angler::Shipping::shipment_methods_iterator_by_iso_country(
            $schema,
            $self->country,
            $self->postal_code,
            );

    $self->_set_shipping_methods($shipping_methods);

    # Determine state
    my $state = Angler::Shipping::find_state($schema,
                                            $self->postal_code,
                                            $self->country);

    $self->state($state);

    my $sales_tax = Angler::Tax::rate($schema, $state, $cart->subtotal);

    $cart->apply_cost( amount => $sales_tax, name => 'tax' );
    $self->_set_tax($sales_tax);
}

1;
