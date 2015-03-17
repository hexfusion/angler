package Angler::Populate::ProductVariant;

use strict;
use warnings;

use Moo;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Interchange6;
use Text::Unidecode;
use HTML::Entities;
use HTML::Obliterate qw/strip_html/;
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
    my $uri = clean_uri(lc( unidecode($self->name . '-' . &attribute_value_titles . '-' .  $self->manufacturer_sku)));
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
    return join(' ', @v);
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
    my $title = &attribute_value_titles;
    my $name = $self->name . ' ' . $title;

    my $product = shop_product->find({manufacturer_sku => $self->code});
    my $variant = $product->find_variant($self->variants);

    if ($variant) {
        info "variant $name exists skipping";
    }
    else {
        info "creating product " . $self->name  . " variant " . $name;

        $product->add_variants(
            {
                sku => $self->sku,
                name => $self->name . ' ' . $title,
                price => $self->price,
                uri => &clean_uri($self->uri),
                weight => $self->weight,
                gtin => $self->gtin,
                active => $self->active,
                manufacturer_sku => $self->manufacturer_sku,
                inventory_exempt => $self->inventory_exempt,
                attributes => $self->variants,
            });
    }
}

=head2 clean_uri($uri)

Removes junk from potential uri including RFC3986 reserved characters

=cut

sub clean_uri {
    my $uri = shift;
    $uri =~ s/[\$#@~`'=+!&*()\[\];.,:?^ `\\\/]+/-/g;
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

=head2 attribute_value_titles

=cut

sub attribute_value_titles {
    my ($self) = @_;
    my @data;
    my $v = $self->variants;

    foreach my $a ( keys %$v ) {
        my $av_rs = shop_schema->resultset('Attribute')->find(
            { name => $a });

        my $av = $av_rs->find_related(
            'attribute_values',
                {
                    value => $v->{$a}
                }
        );

        push @data, { title => $av->title, priority => $av_rs->priority };
    }


    my @sorted =  sort { $a->{priority} <=> $b->{priority} } @data;

    my @attr_titles;

    #FIXME what a hack...
    foreach my $key (@sorted) {
        push @attr_titles, $key->{title};
    }

    return join(' ', @attr_titles)
}

=head2 clean_name($name)

Decodes HTML entities and purges any html tags from arg.
Also removes freight charge from name, e.g.: (+$30).

=cut

sub clean_name {
    my $name = strip_html( decode_entities(shift) );

    # freight charge in name
    $name =~ s/\(\+\$\s*\d+\)//;
    return $name;
}

1;
