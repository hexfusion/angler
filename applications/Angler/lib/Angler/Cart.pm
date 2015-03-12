package Angler::Cart;

use strict;
use warnings;

use Angler::Shipping;
use Angler::Tax;
use DateTime;

use Moo;

=head1 Angler::Cart - calculate shipping and salestax costs

=head1 SYNOPSIS

    my $angler_cart = Angler::Cart->new(
        schema => shop_schema,
        cart => shop_cart,
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
    is => 'ro',
);

=head2 cart

L<Interchange6::Cart> object.

=cut

has cart => (
    is => 'ro',
    required => 1,
);

=head2 schema

L<Interchange6::Schema> object.

=cut

has schema => (
    is => 'ro',
    required => 1,
);

=head2 country

Shipping country (required).

=cut

has country => (
    is => 'ro',
    required => 1,
);

=head2 postal_code

Shipping postal code (required).

=cut

has postal_code => (
    is => 'ro',
    required => 1,
);

=head2 state

Returns shipping state, determined from country and postal_code.

=cut

has state => (
    is => 'lazy',
);

sub _build_state {
    my $self = shift;

    return undef unless ( $self->country eq 'US' && $self->postal_code );
    
    my $state = Angler::Shipping::find_state(
        $self->schema,
        $self->postal_code,
        $self->country
    );

    return $state ? $state : undef;
}

=head2 billing_country

Billing country (optional).

=cut

has billing_country => (
    is => 'ro',
);

=head2 billing_postal_code

Billing postal code (optional).

=cut

has billing_postal_code => (
    is => 'ro',
);

=head2 billing_state

Returns billing state, determined from billing_country and billing_postal_code.

=cut

has billing_state => (
    is => 'lazy',
);

sub _build_billing_state {
    my $self = shift;

    return undef
      unless ( $self->billing_country
        && $self->billing_country eq 'US'
        && $self->billing_postal_code );

    my $state = Angler::Shipping::find_state(
        $self->schema,
        $self->billing_postal_code,
        $self->billing_country
    );

    return $state ? $state : undef;
}

=head2 shipping_methods_id

Sets the current shipping method
(L<Interchange6::Schema::Result::ShipmentMethod>).

=cut

has shipping_methods_id => (
    is => 'ro',
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

has shipping_rates => (
    is => 'rw',
);

=head2 tax

Returns salestax amount.

=cut

has tax => (
    is => 'lazy',
);

sub _build_tax {
    my $self = shift;

    # apply tax if either shipping or billing address is US/NY
    if (
        ( $self->state && $self->state->state_iso_code eq 'NY' )
        || (   $self->billing_state
            && $self->billing_state->state_iso_code eq 'NY' )
      )
    {
        my $now = $self->schema->format_datetime( DateTime->now );
        my $tax = $self->schema->resultset('Tax')->search(
            {
                tax_name   => 'nys_sales_tax',
                valid_from => [ undef, { '<=', $now } ],
                valid_to   => [ undef, { '>=', $now } ],
            },
            {
                rows => 1,
            }
        )->single;

        die "nys_sales_tax missing from database" unless $tax;

        $self->cart->apply_cost(
            amount   => $tax->percent / 100,
            name     => 'tax',
            label    => $tax->description,
            relative => 1
        );

        return $self->cart->cost('tax');
    }

    return 0;
}

=head1 METHODS

=head2 BUILD

Make sure L</country> is deliverable.

=cut

sub BUILD {
    my $self = shift;

    my $zones = $self->schema->resultset('Zone');

    my $zone = $zones->find( { zone => 'Deliverable Countries' } );
    die "Deliverable Countries zone not populated" unless $zone;

    die "Country not deliverable" unless $zone->has_country($self->country);
}

=head2 update_costs

=cut

sub update_costs {
    my ($self) = @_;
    my $schema = $self->schema;
    my $cart = $self->cart;

    # make sure tax gets set
    $self->tax;

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

    #$self->shipping_rates(Angler::Shipping::show_rates($self));
}

1;
