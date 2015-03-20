package Angler::Cart;

use strict;
use warnings;

use Angler::Shipping;
use Angler::Tax;
use DateTime;
use Interchange6::Types;

use Moo;

=head1 Angler::Cart - calculate shipping and salestax costs

=head1 SYNOPSIS

    my $angler_cart = Angler::Cart->new(
        country     => 'US',
        postal_code => '12345',
        schema      => shop_schema,
        cart        => shop_cart,
        shipping_rates_id => $form_values->{shipping_rate},
    );

    $tax = $angler_cart->tax;
    $shipping = $angler_cart->shipping_cost;

=head1 Attributes

=head2 cart

L<Interchange6::Cart> object (required).

=cut

has cart => (
    is       => 'ro',
    isa      => InstanceOf ['Interchange6::Cart'],
    required => 1,
);

=head2 schema

L<Interchange6::Schema> object (required).

=cut

has schema => (
    is       => 'ro',
    isa      => InstanceOf ['Interchange6::Schema'],
    required => 1,
);

=head2 country

Shipping country (required).

=cut

has country => (
    is       => 'ro',
    required => 1,
);

=head2 postal_code

Shipping postal code (required).

=cut

has postal_code => (
    is       => 'ro',
    required => 1,
);

=head2 state

Returns shipping L<Interchange6::Schema::Result::State> determined from
L</country> and L</postal_code>.

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
    is  => 'ro',
);

=head2 billing_postal_code

Billing postal code (optional).

=cut

has billing_postal_code => (
    is  => 'ro',
);

=head2 billing_state

Returns billing L<Interchange6::Schema::Result::State> determined from
L</billing_country> and L</billing_postal_code>.

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

=head2 rates_display_type

What display type will be used in TF for L</shipment_rates>. Defaults to 
C<list>. Set to C<select> when label/value is required for a TF value field.

=cut

has rates_display_type => (
    is      => 'ro',
    default => 'list',
);

=head2 shipment_rates

An array reference of shipment rates lazily generated by
L<Angler::Shipping/show_rates> and converted to a format ready to be passed
in a token to the template.

=cut

has shipment_rates => (
    is  => 'lazy',
);

sub _build_shipment_rates {
    my $self = shift;

    my $rates = Angler::Shipping::show_rates( $self, $self->use_easypost );

    if ( $rates && ref($rates) eq 'ARRAY' && @$rates ) {

        # we have some rates

        if ( defined $self->shipment_rates_id ) {

            # one of them needs to be checked

            my $found = 0;

            foreach my $rate (@$rates) {
                if ( $rate->{carrier_service} == $self->shipment_rates_id ) {
                    $rate->{checked}  = 'checked';
                    $found = 1;
                    last;
                }
            }

            $self->set_shipment_rates_id(undef) unless $found;
        }
        if ( $self->rates_display_type eq 'list' ) {
            return $rates;
        }
        elsif ( $self->rates_display_type eq 'select' ) {
            my @rates;
            foreach my $rate (@$rates) {
                my $newrate = {
                    value => $rate->{carrier_service},
                    label => $rate->{service} . ' $' . $rate->{rate}
                };
                $newrate->{selected} = "selected" if $rate->{checked};
                push @rates, $newrate;
            }
            return \@rates;
        }
    }
    return undef;
}

=head2 shipment_rates_id

The selected L<Interchange6::Schema::Result::ShipmentRate/shipment_rates_id>.

=cut

has shipment_rates_id => (
    is => 'rwp',
    writer => 'set_shipment_rates_id',
);

after 'set_shipment_rates_id' => sub {
    my $self = shift;
    $self->clear_shipping_cost;
};

=head2 shipping_cost

Returns shipping_cost.

=cut

has shipping_cost => (
    is      => 'lazy',
    clearer => 1,
);

sub _build_shipping_cost {
    my $self = shift;

    return 0 unless ( $self->shipment_rates && $self->shipment_rates_id );

    my $shipping_cost =
      Angler::Shipping::shipping_rate( $self->schema,
        $self->shipment_rates_id,  );

    return 0 unless $shipping_cost;

    $self->cart->apply_cost( amount => $shipping_cost, name => 'shipping' );
    return $shipping_cost;
}

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

=head2 use_easypost

Boolean to control whether eaypost should be used when calling
L<Angler::Shipping/show_rates>. Defaults to 1 (true).

=cut

has use_easypost => (
    is      => 'ro',
    default => 1,
);

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

1;
