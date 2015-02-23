package Angler::Populate::Variant;

use strict;
use warnings;

use Moo;
use Dancer ':script';

set logger => 'console';
set log => 'info';

=head1 NAME

Angler::Populate::Variant

=head1 DESCRIPTION

assists in population of the Product variants class

=head1 SYNOPSIS

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

=head2 canonical_sku

Returns canonical_sku of product

=cut

has canonical_sku => (
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
    is => 'ro'
    isa => 'Hashref'
);

=head1 METHODS

=cut

sub add {
    my ($self) = @_;
    my $schema = $self->schema;

    $self->product->add_variants(
    {
        sku => $self->sku,$
        name => $self->name,$
        price => $self->price,$
        uri => $self->uri,$
        weight => $self->weight,$
        gtin => $self->gtin,$
        canonical_sku => $self->canonical_sku,$
        active => $self->active,$
        manufacturer_sku => $self->manufacturer_sku,$
        inventory_exempt => $self->inventory_exempt,$
        priority => $self->priority$
            attributes => $self->attributes,
     });
}

1;
