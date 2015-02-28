package Angler::Populate::ProductVariant;

use strict;
use warnings;

use Moo;
use Angler::Interchange6::Schema;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Interchange6;
use Text::Unidecode;
use YAML qw/LoadFile/;
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

            code => '23444',
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

=head2 importer_config

Returns importer config

=cut

has importer_config => (
    is => 'lazy'
);

sub _build_importer_config {
    my ($self) =@_;
    my $file = LoadFile( File::Spec->catfile( config->{appdir}, "importer.yml" ) );
    return $file->{manufacturers}->{$self->manufacturer};
}

=head2 code 

Returns the Interchange6::Schema::Result::Product object

=cut

has code => (
    is => 'ro',
    required => '1',
);

=head2 sku

Returns sku of product

=cut

has sku => (
    is => 'lazy',
);

sub _build_sku {
    my ($self) = @_;
    my $prefix = $self->importer_config->{prefix};
    my $sku = "WB-" . $prefix . "-" . $self->manufacturer_sku;
    return $sku;
}

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
    is => 'lazy',
);

sub _build_uri {
    my ($self) = @_;
    my $uri = clean_uri(lc( unidecode($self->name . '-' . $self->attribute_values . '-' .  $self->manufacturer_sku)));
    return $uri;
}

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
    default => '0'
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
    default => '0'
);

=head2 priority

Returns priority of product

=cut

has priority => (
    is => 'ro',
    default => '0'
);

=head2 attributes

experimental only atm

=cut

has attributes => (
    is => 'ro',
    required => '1'
);

=head2 attribute_values

Returns a list of the attribute values space delimited.

=cut

has attribute_values => (
    is => 'lazy',
);

sub _build_attribute_values {
    my ($self) = @_;
    my @a = @{$self->attributes};
    my @v;
    foreach (@a) {
         push @v, $_->{value};
    }
    return join(' ', @v);;
}

=head2 variants

returns attribute/value pair used for add_varaints method

=cut

has variants => (
    is => 'lazy'
);

sub _build_variants {
    my ($self) = @_;
    my @a = @{$self->attributes};
    my $variants;
    foreach (@a) {
         $variants->{$_->{name}} = &clean_attribute_value($_->{value});
    }
    return $variants;
}

=head2 manufacturer

=cut

has manufacturer => (
    is => 'ro',
    required => 1,
);

=head1 METHODS

=cut

sub add {
    my ($self) = @_;

    info "variants", $self->variants;

    shop_product->find({manufacturer_sku => $self->code})->add_variants(
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
            attributes => $self->variants,
     });
}

=head2 clean_uri($uri)

Removes junk from potential uri including RFC3986 reserved characters

=cut

sub clean_uri {
    my $uri = shift;
    $uri =~ s^\!\*\'\(\)\;\:\@\&\=\+\$\,/\?\#\[\]^-^g;
    $uri =~ s/\s+/-/g;
    $uri =~ s/\-+/-/g;
    return $uri;
}

=head2 clean_attribute_value($value)

return a cleaned up value for AttributeValue value column

=cut

sub clean_attribute_value {
    my $value = lc(shift);
    $value =~ s/\s+/_/g;
    return $value;
}

1;
