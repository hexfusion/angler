package Angler::Populate::Product;

use strict;
use warnings;

use Moo;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Interchange6;
use Text::Unidecode;
use YAML qw/LoadFile/;
use Dancer ':script';

set logger => 'console';
set log => 'info';

=head1 NAME

Angler::Populate::Product

=head1 DESCRIPTION

assists in population of the product class

=head1 SYNOPSIS

my $product = Angler::Populate::Product->new(
            sku => 'WBA2002',
            code => '23444',
            name => 'Test Product',
            short_description => 'Just a short description',
            description => 'Like a short description but this is longer',
            price => '20.00',
            cost => '10.00,
            uri => 'simms_glove',
            weight => '3.5',
            gtin => '8908765555555555',
            canonical_sku => undef,
            active => '1',
            manufacturer_sku => 'SF-23444',
            inventory_exempt => '0',
            priority => '0',
            manufacturer => 'simms'
);

$product->add;

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

=head2 sku

Returns sku of product

=cut

has sku => (
    is => 'lazy',
);

sub _build_sku {
    my ($self) = @_;
    my $prefix = $self->importer_config->{prefix};
    my $sku = "WB-" . $prefix . "-" . $self->code;
    return $sku;
}

=head2 code

Returns code of product

=cut

has code => (
    is => 'ro',
#    required => 1,
);

=head2 name

Returns name of product

=cut

has name => (
    is => 'ro',
    required => 1,
);

=head2 short_description

Returns short_description of product

=cut

has short_description => (
    is => 'ro',
    default => '',
);

=head2 description

Returns description of product

=cut

has description => (
    is => 'ro',
    default => '',
);

=head2 price

Returns price of product

=cut

has price => (
    is => 'ro',
    required => 1,
);

=head2 cost

Returns cost of product

=cut

has cost => (
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
    my $uri = clean_uri(lc( unidecode($self->name . '-' . $self->code)));
    return $uri;
}

=head2 weight

Returns weight of product

=cut

has weight => (
    is => 'ro',
    default => '1'
);

=head2 gtin

Returns gtin of product

=cut

has gtin => (
    is => 'ro',
    required => '1',
);

=head2 canonical_sku

Returns canonical_sku of product should never be defined for a product
only a product variant.

=cut

has canonical_sku => (
    is => 'ro',
);

=head2 navigation

Returns the navigation code of the product

=cut

has navigation => (
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
#    required => 1
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
    my $schema = shop_schema;
    my $sku = $self->sku;

    my $product = $schema->resultset('Product')->find_or_new(
        {
            sku => $sku,
            name => $self->name,
            short_description => $self->short_description,
            description => $self->description,
            price => $self->price,
            cost => $self->cost,
            uri => $self->uri,
            weight => $self->weight,
            gtin => $self->gtin,
            canonical_sku => $self->canonical_sku,
            active => $self->active,
            manufacturer_sku => $self->code,
            inventory_exempt => $self->inventory_exempt,
            priority => $self->priority
        }
    );
    unless ($product->in_storage) {
        info "Product sku: $sku not found and added";
        $product->insert;
    }
    return $product;
}

=head2 export

This method takes product data and formats it for writing to excel
the file is used by qbpos

=cut

sub export {
    my ($self) = @_;

    my @excel_export = ([
       $self->name, #canonical_desc
       $self->name,  #variant_desc
       $self->code, #alu
       $self->gtin, #upc
       $self->navigation, #department_code
       'Retail Sales', #income_account
       'COGS-Retail', #cogs_account
       undef, #attribute
       undef, #size
       $self->importer_config->{vendor_code}, #vendor_code
       $self->importer_config->{erp_name}, #vendor_name
       $self->cost, #avg_cost
       $self->cost, #order_cost
       $self->price, #reg_price
       $self->price, #msrp
       'Inventory', #item_type
       'Inventory Asset', #asset_account
       $self->sku #custom_field_1
    ]);

    return @excel_export;
}

=head2 clean_uri($uri)

Removes junk from potential uri including RFC3986 reserved characters

=cut

sub clean_uri {
    my $uri = shift;
    $uri =~ s/\W+/-/g;  # non-word chars
    $uri =~ s/_+/-/g;   # underscores
    $uri =~ s/\-+/-/g;  # multiple dashes
    return $uri;
}

1;
