#!/usr/bin/env perl

use warnings;
use strict;

package Product;

use Moo;


package main;

use Dancer ':script';
use Angler::Schema;
use Dancer::Plugin::Interchange6;
use Getopt::Long;
use HTML::Entities;
use List::Util qw/all/;
use Pod::Usage;
use Text::Unidecode;
use Try::Tiny;
use URI::Escape;
use XML::Twig;
use YAML;
use Data::Dumper::Concise;

set logger => 'console';
set log => 'info';

my $config = config->{importer};

my $schema = shop_schema;

my ( $file, $help, $manufacturer );

GetOptions(
    "file=s" => \$file,
    "help" => \$help,
    "manufacturer=s" => \$manufacturer,
);
pod2usage(1) if $help;

# initial checks

unless ($file && $manufacturer) {
    print "ERROR: file and manufacturer must be supplied as options.\n";
    pod2usage(1);
}

unless (-r $file) {
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

    my $twig = XML::Twig->new(
        twig_handlers => $twig_handlers
    );

    $twig->parsefile($file);
}
else {

    # unknown

    die "ERROR: unexpected type $type found in config\n";
}

=head2 short_description($description)

Tries to shorten a description to fit in short_description varchar(500) column

=cut

sub short_description {
    my $description = shift;

    my @desc_sentences = split(/\./, $description);
    my $short_description = '';
    foreach my $sentence ( @desc_sentences ) {
        if ( length($short_description) + length($sentence) + 1 < 500 ) {
            $short_description .= ".$sentence";
        }
        else {
            last;
        }
    }
    if ( $short_description ) {

        # append final full stop
        $short_description .= ".";
    }
    else {

        # all sentences were too long so get a substr
        $short_description = substr($description, 500);
    }
    return $short_description;
}

=head2 process_orvis_product

twig handler for Orvis xml Product

=cut

sub process_orvis_product {
    my( $t, $xml )= @_;

    my $manuf_sku = $xml->first_child('PF_ID')->text;

    # should we skip this item due to freight charges?

    if (   $xml->first_child('Freight_From_Price_Flag')->text * 1 > 0
        || $xml->first_child('Min_Freight')->text * 1 > 0
        || $xml->first_child('Max_Freight')->text * 1 > 0 )
    {
        debug "skipping $manuf_sku due to freight charge";
        return;
    }

    # grab Product info

    my $description =
      decode_entities( $xml->first_child('PF_Description')->text );

    my $short_description = &short_description($description);

    my $keywords = decode_entities( $xml->first_child('Keywords')->text );

    my $name = "Orvis " . decode_entities( $xml->first_child('PF_Name')->text );

    # freight charge in name
    return if $name =~ /\(\+\$\d+\)/;

    my $sku = "WB-OR-" . $manuf_sku;

    my $uri = lc($name);
    $uri =~ s/\s+/-/g;
    $uri = encode_entities($uri);
    $uri =~ s/\//-/g;

    my $product = shop_product->find_or_create(
        {
            sku               => $sku,
            manufacturer_sku  => $manuf_sku,
            name              => $name,
            short_description => $short_description,
            description       => $description,
            uri               => $uri,
            active            => 0,
        },
        {
            key => 'primary'
        }
    );

    my @variants;

    my @items = $xml->children('Item');

    if ( scalar @items == 1 ) {

        # simple situation with just one Item entry for this product

        my $item = $items[0];

        my @skus = $item->children('Sku');

        if ( scalar @skus == 1 ) {

            # a simple canonical product with no variants

            if ( $product->variants->has_rows ) {

                # but we have variants in the DB so remove them

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

            my @variants;

            my @options = $item->children('Option');

            # make sure we have all variant attributes in DB and collect
            # attribute names along the way for use later

            my @attr_names;
            foreach my $option ( @options ) {
                my $name = $option->first_child('OptionName')->text;
                push @attr_names, $name;
                my @values =
                  split( /,/, $option->first_child('OptionValue')->text );

                my $attribute =
                  $schema->resultset('Attribute')->find_or_new(
                    {
                        name  => $name,
                        title => ucfirst($name),
                        type  => 'variant',
                    },
                    {
                        key => 'attributes_name_type'
                    }
                  );

                if ( $attribute->in_storage ) {

                    # we have to do things the slow way

                    foreach my $value (@values) {

                        my $av = $attribute->search_related(
                            'attribute_values',
                            {
                                value => $value,
                            },
                            {
                                rows => 1,
                            }
                        );

                        unless ($av) {

                            # create new record

                            $attribute->create_related(
                                'attribute_values',
                                {
                                    value => $value,
                                    title => ucfirst($value),
                                }
                            );
                        }
                    }

                }
                else {

                    # a new attribute so insert it along with attr_vals

                    $attribute->insert;
                    foreach my $value ( @values ) {
                        $attribute->create_related(
                            'attribute_values',
                            {
                                value => $value,
                                title => ucfirst($value),
                            }
                        );
                    }
                }
            }

            # process each variant

            foreach my $sku ( $item->children('Sku') ) {

                my @attr_values =
                  split( /,/, $sku->first_child('Option_String')->text );

                my $manuf_sku = $sku->first_child('Item_Code')->text;

                my $sku_name = "Orvis "
                  . decode_entities( $sku->first_child('Sku_Name')->text );

                # freight charge in name
                next if $sku_name =~ /\(\+\$\d+\)/;

                my $variant_sku = "WB-OR-" . $manuf_sku;

                my $price       = $sku->first_child('Regular_Price')->text;

                my $uri = lc($sku_name);
                $uri =~ s/\s+/-/g;
                $uri = encode_entities($uri);
                $uri =~ s/\//-/g;

                my $variant = shop_product->find(
                    {
                        sku => $variant_sku,
                        canonical_sku => $sku,
                    }
                );

                if ( $variant ) {

                    # we already have this variant so just update price
                    $variant->price($price);

                    debug "update price for variant $variant_sku\n";
                }
                else {

                    # a new variant

                    my %attributes = (
                        sku => $variant_sku,
                        manufacturer_sku => $manuf_sku,
                        name  => $variant_sku,
                        price => $price,
                        uri   => $uri,
                    );

                    push @variants, \%attributes;
                }
            }

            if ( scalar @variants > 0 ) {
                debug "add variants to sku $sku\n";
                try {
                    $product->add_variants(@variants);
                }
                catch {
                    warning $_;
                };
            }
        }
    }
    else {

        # multiple items can mean multiple canonical products or one
        # canonical product with multiple variants


        foreach my $item ( @items ) {
            info unidecode("$manuf_sku :: $name :: " . $item->first_child('Item_Name')->text) . "\n";

        }

        my @size = ('small', 'medium', 'large');

        my @item_names =
          map { decode_entities( $_->first_child('Item_Name')->text ) } @items;

        if ( all { /^(\d+)(\s+|-)pack$/ } @item_names ) {

            # variants based on number of items in pack

            info "variant name => pack";
        }
        elsif ( all { my $a = $_ && grep { $_ eq $a } @size } @item_names ) {

            info "variant name => size";
        }
    }

    foreach my $item ( $xml->children('Item') ) {

        # <Item PF_ID="0009"

        my @attr_names =
          map { $_->first_child('OptionName')->text } $item->children('Option');

        foreach my $sku ( $item->children('Sku') ) {;

            # <Sku PF_ID="0009" ProdGroupID="39736">

            my @attr_values =
              split( /,/, $sku->first_child('Option_String')->text );

            my $sku_name = $sku->first_child('Sku_Name')->text;

            #print "bugger $sku_name" if $name eq $sku_name;

            my %attributes = (
                sku => "WB-OR-" . $sku->first_child('Item_Code')->text,
                name => "Orvis $sku_name",
            );

            foreach my $i ( 0..$#attr_names ) {
                $attributes{$attr_names[$i]} = $attr_values[$i];
            }

            push @variants, \%attributes;
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
  -f | --file               file to import
  -m | --manufacturer       manufacturer name
  -h | --help               help message

=cut
