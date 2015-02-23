package Angler::Populate::ProductVariant;

use strict;
use warnings;

use Moo;
use Dancer ':script';

set logger => 'console';
set log => 'info';

=head1 NAME

Angler::Populate::ProductVariant

=head1 DESCRIPTION

assists in population Product variants

=head1 SYNOPSIS

my $product = shop_product->find({ sku => 'WBA2002'});
my $attributes = {'size' => 's', 'color' => 'red'};

my $variant = Angler::Populate::ProductVariant->new(

            schema => shop_schema,
            product => $product,
            sku => 'WBA2003',
            name => 'Test Product Variant',
            price => '20.00',
            uri => 'simms_glove_small_red',
            weight => '3.5',
            gtin => '8908765555555599',
            active => '1',
            manufacturer_sku => 'SF-23444',
            inventory_exempt => '0',
            priority => '0',
            attributes => $attributes
);

$variant->add;

=head1 ASSESSORS

=cut

=head2 product

Returns the Interchange6::Schema::Result::Product object

=cut

has product => (
    is => 'ro',
    required => '1',
);

=head2 sku

Returns sku of product

=cut

has sku => (
    is => 'ro',
    required => 1,
);

=head2 name

Returns name of product

=cut

has name => (
    is => 'ro',
    required => 1,
);

=head2 price

Returns price of product

=cut

has price => (
    is => 'ro',
    required => 1,
);

=head2 uri

Returns uri of product

=cut

has uri => (
    is => 'ro',
    required => 1,
);

=head2 weight

Returns weight of product

=cut

has weight => (
    is => 'ro',
    default => 1,
);

=head2 gtin

Returns gtin of product

=cut

has gtin => (
    is => 'ro',
);

=head2 active

Returns active 0/1 of product

=cut

has active => (
    is => 'ro',
    default => undef
);

=head2 manufacturer_sku

Returns manufacturer_sku of product

=cut

has manufacturer_sku => (
    is => 'ro',
);

=head2 inventory_exempt

Returns inventory_exempt status of product

=cut

has inventory_exempt => (
    is => 'ro',
    default => '1'
);

=head2 priority

Returns priority of product

=cut

has priority => (
    is => 'ro',
);

=head2 attributes

Returns a hashref of attributes

=cut

has attributes => (
    is => 'ro',
    required => '1'
);

=head1 METHODS

=cut

sub add {
    my ($self) = @_;

    $self->product->add_variants(
    {
        sku => $self->sku,
        name => $self->name,
        price => $self->price,
        uri => $self->uri,
        weight => $self->weight,
        gtin => $self->gtin,
        active => $self->active,
        manufacturer_sku => $self->manufacturer_sku,
        inventory_exempt => $self->inventory_exempt,
        priority => $self->priority,
            attributes => $self->attributes,
     });
}

1;
