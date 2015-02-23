package Angler::Populate::Product;

use strict;
use warnings;

use Moo;
use Dancer ':script';

set logger => 'console';
set log => 'info';

=head1 NAME

Angler::Populate::Product

=head1 DESCRIPTION

=head1 SYNOPSIS

=cut

sub add {
    my ($self) = @_;
    my $schema = $self->schema;

    my $product = $schema->resultset('Product')->find_or_new(
        {
            sku => $self->sku,
            manufacturer_sku => $self->manufacturer_sku,
            name => $self->name,
            short_description => $self->short_description,
            description => $self->description,
            price => $self->price,
            uri => $self->uri,
            weight => $self->weight,
            gtin => $self->gtin,
            canonical_sku => $self->canonical_sku,
            active => $self->active,
            inventory_exempt => $self->inventory_exempt,
            priority => $self->priority
        }
    );
    unless ($product->in_storage) {
        info "Product sku: $product->sku not found and added"
        $product->insert;
    }
}
