#!/usr/bin/env perl

use warnings;
use strict;

package Product;

use Moo;

package main;

use Dancer ':script';
use Angler::Schema;
use Dancer::Plugin::Interchange6;
use File::Path qw/make_path/;
use File::Spec;
use Getopt::Long;
use HTML::Entities;
use HTML::Obliterate qw/strip_html/;
use HTTP::Tiny;
use Imager;
use Pod::Usage;
use Text::Unidecode;
use Try::Tiny;
use URI::Escape;
use XML::Twig;
use YAML;
use Data::Dumper::Concise;

set logger => 'console';
set log    => 'info';

my $config = config->{importer};

my $schema = shop_schema;

#my $img_dir = "/home/camp/angler/rsync/htdocs/assetstore/site/images/items";
my $img_dir = "/home/syspete/camp11/images";

my ( $active, $file, $help, $manufacturer );

GetOptions(
    "active"         => \$active,
    "file=s"         => \$file,
    "help"           => \$help,
    "manufacturer=s" => \$manufacturer,
);
pod2usage(1) if $help;

# initial checks

unless ( $file && $manufacturer ) {
    print "ERROR: file and manufacturer must be supplied as options.\n";
    pod2usage(1);
}

unless ( -r $file ) {
    print "ERROR: unable to read file";
    exit 1;
}

unless ( defined $config->{manufacturers}->{$manufacturer} ) {
    print "ERROR: manufacturer not found. Valid manufacturers are\n";
    print( join( " ", keys %{ $config->{manufacturers} } ), "\n" );
    exit 1;
}

unless ( defined $config->{manufacturers}->{$manufacturer}->{type} ) {
    print "ERROR: type not defined for manufacturer $manufacturer in config\n";
    exit 1;
}

$active = 0 unless $active;

my @product_columns = shop_product->result_source->columns;
my $mediatype_image =
  $schema->resultset('MediaType')->find( { type => 'image' } );


# parse by type

my $type = $config->{manufacturers}->{$manufacturer}->{type};
if ( $type eq 'xls' ) {

    # spreadsheet

    die "xls processing not working yet\n";
}
elsif ( $type eq 'xml' ) {

    # XML

    my $twig_handlers;

    if ( $manufacturer eq 'orvis' ) {
        $twig_handlers = { Product => \&process_orvis_product };
    }
    else {
        die "xml processor for $manufacturer does not exist";
    }

    my $twig = XML::Twig->new( twig_handlers => $twig_handlers );

    $twig->parsefile($file);
}
else {

    # unknown

    die "ERROR: unexpected type $type found in config\n";
}

=head2 add_attribute_values( $name, $title, @values );

=cut

sub add_attribute_values {
    my ( $name, $title, @values ) = @_;

    my $attribute = $schema->resultset('Attribute')->find_or_create(
        {
            name  => &clean_attribute_value($name),
            title => $title,
            type  => 'variant',
        },
        {
            key => 'attributes_name_type'
        }
    );

    foreach my $value (@values) {
        $schema->resultset('AttributeValue')->find_or_create(
            {
                attributes_id => $attribute->attributes_id,
                value         => &clean_attribute_value($value),
                title         => $value,
            },
            {
                key => 'attribute_values_attributes_id_value'
            }
        );
    }
}

=head2 clean_attribute_value($value)

return a cleaned up value for AttributeValue value column

=cut

sub clean_attribute_value {
    my $value = lc(shift);
    $value =~ s/\s+/_/g;
    while ( grep { $value eq $_ } @product_columns ) {
        $value = "X$value";
    }
    return $value;
}

=head2 clean_name($name)

Decodes HTML entities and purges any html tags from arg.

Also removes freight charge from name, e.g.: (+$30).

=cut

sub clean_name {
    my $name = strip_html(decode_entities( shift ));

    # freight charge in name
    $name =~ s/\(\+\$\s*\d+\)//;

    return $name;
}

=head2 short_description($description)

Tries to shorten a description to fit in short_description varchar(500) column

=cut

sub short_description {
    my $description = shift;

    my @desc_sentences = split( /\./, $description );
    my $short_description = '';
    foreach my $sentence (@desc_sentences) {
        if ( length($short_description) + length($sentence) + 1 < 500 ) {
            $short_description .= ".$sentence";
        }
        else {
            last;
        }
    }
    if ($short_description) {

        # append final full stop
        $short_description .= ".";
    }
    else {

        # all sentences were too long so get a substr
        $short_description = substr( $description, 500 );
    }
    return $short_description;
}

=head2 process_orvis_product

twig handler for Orvis xml Product

=cut

sub process_orvis_product {
    my ( $t, $xml ) = @_;

    # get attribute 'option'

    my $option_attribute = $schema->resultset('Attribute')->find_or_create(
        {
            name  => 'option',
            title => 'Option',
            type  => 'variant',
        },
        {
            key => 'attributes_name_type'
        }
    );

    my $pf_id = $xml->first_child('PF_ID')->text;

    # should we skip this item due to freight charges?

    if (   $xml->first_child('Freight_From_Price_Flag')->text * 1 > 0
        || $xml->first_child('Min_Freight')->text * 1 > 0
        || $xml->first_child('Max_Freight')->text * 1 > 0 )
    {
        debug "skipping $pf_id due to freight charge";
        return;
    }

    # do we skip due to category?

    if (
        $xml->first_child('Dir_Name')->text eq 'Distinctive Home'
        || (
            $xml->first_child('Dir_Name')->text eq 'Sale Outlet'
            && (   $xml->first_child('Group_Name')->text eq 'Distinctive Home'
                || $xml->first_child('Cat_Name')->text =~ /Home/ )
        )
        || $xml->first_child('Group_Name')->text eq 'Gift Card'
        || $xml->first_child('Cat_Name')->text eq 'Gift Card'
        || $xml->first_child('Dir_Name')->text =~ /School/i
      )
    {
        debug "skipping $pf_id due to category";
        return;
    }

    # grab Product info

    my $description =
      decode_entities( $xml->first_child('PF_Description')->text );

    my $short_description = &short_description($description);

    my $keywords = decode_entities( $xml->first_child('Keywords')->text );

    my $pf_name = clean_name( $xml->first_child('PF_Name')->text );
    my $name = "Orvis $pf_name";

    my $sku = "WB-OR-" . $pf_id;

    my $uri = lc( unidecode("$name-$pf_id") );
    $uri =~ s/\s+/-/g;
    $uri =~ s/\//-/g;

    my $product = shop_product->find_or_create(
        {
            sku               => $sku,
            manufacturer_sku  => $pf_id,
            name              => $name,
            short_description => $short_description,
            description       => $description,
            uri               => $uri,
            active            => $active,
        },
        {
            key => 'primary'
        }
    );

    # download image

    my $img_dir_helios = File::Spec->catdir($img_dir, "original_files/helios");
    make_path( $img_dir_helios );

    my $http = HTTP::Tiny->new();
    TAG: foreach my $tag (qw/ LargeImageURL ImageURL /) {
        if ( my $elt = $xml->first_child('LargeImageURL') ) {
            if ( my $url = $elt->text ) {
                ( my $file = $url ) =~ s/^.+\///;
                my $path = File::Spec->catfile($img_dir_helios, $file);
                my $response = $http->mirror( $url, $path );
                if ( $response->{success} ) {
                    info "response $response->{status} for $tag $pf_id $name";
                    sleep 1;
                    last TAG;
                }
                else {
                    error "failed to get $tag for $pf_id $name";
                }
            }
        }
    }

    # add navigation for this canonical product

    my $navigator = $xml->first_child('Navigator');

    if ($navigator) {
        my $first_child = $navigator->first_child;
        my $text        = $first_child->text;
        my $uri         = lc( unidecode($text) );
        $uri =~ s/\s+/-/g;

        my $nav = $schema->resultset('Navigation')->find_or_create(
            {
                uri   => $uri,
                type  => 'nav',
                scope => 'menu-main',
                name  => $text,
            },
            {
                key => 'navigations_uri',
            }
        );

        foreach my $sibling ( $first_child->siblings ) {

            my $text = $sibling->text;
            $uri .= "/" . lc($text);
            $uri =~ s/\s+/-/g;

            $nav = $schema->resultset('Navigation')->find_or_create(
                {
                    uri       => $uri,
                    type      => 'manufacturer',
                    scope     => 'orvis',
                    name      => $text,
                    parent_id => $nav->id,
                },
                {
                    key => 'navigations_uri',
                }
            );
        }

        # add product to the final nav

        $nav->add_to_navigation_products({ sku => $product->sku });
    }
    else {
        warning unidecode("no Navigator element for $sku $name\n");
    }

    #$xml->purge;
    #return;

    # Product->Item

    my @items = $xml->children('Item');

    if ( scalar @items == 1 ) {

        # simple situation with just one Item entry for this product

        my $item = $items[0];

        # Product->Item->Sku

        my @skus = $item->children('Sku');

        if ( scalar @skus == 1 ) {

            # a simple canonical product with no variants

            if ( $product->variants->has_rows ) {

                # but we have variants in the DB so remove them

                debug unidecode("removing old variants for $sku $name\n");

                $product->variants->delete;
            }
            else {
                $product->price( $skus[0]->first_child('Regular_Price')->text );
                $product->update;
                debug unidecode("product with no variants $sku $name\n");
            }
        }
        else {

            # we have variants

            my @options = $item->children('Option');

            # make sure we have all variant attributes in DB and collect
            # attribute names along the way for use later

            my @option_names;
            foreach my $option (@options) {

                my $option_name = lc($option->first_child('OptionName')->text);

                my $title = ucfirst($option_name);

                $option_name =~ s/\s+/_/g;

                $title = "Line weight" if $option_name eq "line_weigh";
                $title = "Magnification" if $option_name eq "magnificat";

                push @option_names, $option_name;

                my @option_values =
                  split( /,/, $option->first_child('OptionValue')->text );

                &add_attribute_values( $option_name, $title, @option_values );
            }

            # process each variant

            foreach my $sku (@skus) {

                my $pf_id = $xml->first_child('PF_ID')->text;
                my $item_code = $sku->first_child('Item_Code')->text;

                my $sku_name =
                  clean_name( $sku->first_child('Sku_Name')->text );
                unless ( $sku_name =~ /^\Q$pf_name\E/ ) {
                    $sku_name = "$pf_name $sku_name";
                }
                $sku_name = "Orvis $sku_name";

                # prefix sku with WB-OR-
                my $variant_sku = "WB-OR-" . $pf_id . "-" . $item_code;

                my $regular_price = $sku->first_child('Regular_Price')->text;

                my $uri =
                  lc( unidecode("${sku_name}-${pf_id}${item_code}") );
                $uri =~ s/\s+/-/g;
                $uri =~ s/\//-/g;

                my $variant = shop_product->find(
                    {
                        sku => $variant_sku,
                    }
                );

                if ($variant) {

                    # we already have this variant so just update price
                    $variant->price($regular_price);
                    $variant->update;
                }
                else {

                    # a new variant

                    debug unidecode("new variant $variant_sku $sku_name\n");

                    my %attributes = (
                        sku              => $variant_sku,
                        manufacturer_sku => $pf_id . "-" . $item_code,
                        name             => $sku_name,
                        price            => $regular_price,
                        uri              => $uri,
                    );

                    my @option_string =
                      split( /,/, $sku->first_child('Option_String')->text );

                    foreach my $i ( 0 .. $#option_names ) {
                        $attributes{ &clean_attribute_value( $option_names[$i] )
                        } = &clean_attribute_value( $option_string[$i] );
                    }

                    # we add one variant at a time since orvis have a small
                    # number of conflicting variants (different sku but same
                    # attribute options)
                    try {
                        $product->add_variants( ( \%attributes ) );
                    }
                    catch {
                        warning $_;
                    };
                }
            }
        }
    }
    else {

        # multiple items can mean multiple canonical products or one
        # canonical product with multiple variants

        debug unidecode("product with multiple items $sku $name\n");

        foreach my $item (@items) {

            my $item_name = clean_name( $item->first_child('Item_Name')->text );

            my @options = $item->children('Option');

            my @all_option_values;
            my @option_names;
            foreach my $option (@options) {

                my $option_name =
                  lc( $option->first_child('OptionName')->text );

                my $title = ucfirst($option_name);

                $option_name =~ s/\s+/_/g;

                $title = "Line weight"   if $option_name eq "line_weigh";
                $title = "Magnification" if $option_name eq "magnificat";

                push @option_names, $option_name;

                my @option_values =
                  split( /,/, $option->first_child('OptionValue')->text );

                push @all_option_values, @option_values;

                &add_attribute_values( $option_name, $title, @option_values );
            }

            if ( !grep { lc($_) eq lc($item_name) } @all_option_values ) {

                # Item_Name is not in option_values so it needs to
                # be added as extra variant option

                push @option_names, 'option';

                &add_attribute_values( 'option', 'Option',
                    &clean_attribute_value($item_name) );
            }

            my @skus = $item->children('Sku');

            foreach my $sku ( @skus ) {

                my $item_code = $sku->first_child('Item_Code')->text;

                my $sku_name =
                  clean_name( $sku->first_child('Sku_Name')->text );
                unless ( $sku_name =~ /^\Q$pf_name\E/ ) {
                    $sku_name = "$pf_name $sku_name";
                }
                $sku_name = "Orvis $sku_name";

                my $variant_sku = "WB-OR-" . $item_code;

                my $price = $sku->first_child('Regular_Price')->text;

                my $uri =
                  lc( unidecode("${sku_name}-${pf_id}${item_code}") );
                $uri =~ s/\s+/-/g;
                $uri =~ s/\//-/g;

                my $variant = shop_product->find(
                    {
                        sku           => $variant_sku,
                    }
                );

                if ($variant) {

                    # we already have this variant so just update price
                    $variant->price($price);
                    $variant->update;
                }
                else {

                    # a new variant

                    my %attributes = (
                        sku              => $variant_sku,
                        manufacturer_sku => $item_code,
                        name             => $sku_name,
                        price            => $price,
                        uri              => $uri,
                    );

                    my @option_string =
                      split( /,/, $sku->first_child('Option_String')->text );

                    if (   @option_names
                        && $option_names[$#option_names] eq 'option' )
                    {
                        push @option_string, &clean_attribute_value($item_name);
                    }

                    foreach my $i ( 0 .. $#option_names ) {
                        $attributes{ &clean_attribute_value( $option_names[$i] )
                        } = &clean_attribute_value( $option_string[$i] );
                    }

                    try {
                        $product->add_variants( ( \%attributes ) );
                    }
                    catch {
                        warning $_;
                    };
                }
            }
        }
    }
    $xml->purge;
}

__END__

=head1 NAME

importer.pl - Import manufacturer products lists into Angler

=head1 SYNOPSIS

inporter.pl [options]

 Options:
  -a | --active             set active to 't' for all items (defaults to 'f')
  -f | --file               file to import
  -m | --manufacturer       manufacturer name
  -h | --help               help message

=cut
