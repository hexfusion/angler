#!/usr/bin/env perl

use warnings;
use strict;
use v5.14;

package Product;

use Moo;

package main;

use Dancer ':script';
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Interchange6;
#use Angler::Interchange6::Schema::Populate::Media;
use Angler::Populate::Product;
use Angler::Populate::ProductVariant;
use Angler::Populate::Attribute;
use Angler::Populate::ParseExcel;
use Angler::Populate::Image;
#use Angler::Populate::Size;
use File::Basename;
use File::Path qw/make_path/;
use File::Copy;
use File::Spec;
use Getopt::Long;
use HTML::Entities;
use HTML::Obliterate qw/strip_html/;
use HTTP::Tiny;
use Imager;
use List::Util qw/all/;
use Pod::Usage;
use Spreadsheet::ParseExcel;
use Spreadsheet::ParseXLSX;
use Spreadsheet::WriteExcel;
use Text::Unidecode;
use Time::HiRes qw/sleep/;
use Try::Tiny;
use URI::Escape;
use XML::Twig;
use YAML qw/LoadFile/;
use List::MoreUtils qw(uniq);
use Data::Dumper::Concise;
use DateTime qw();

my $now    = DateTime->now;

set logger => 'console';
set log    => 'info';

my $count = 0;

my $config =
  LoadFile( File::Spec->catfile( config->{appdir}, "importer.yml" ) );

my $schema = shop_schema;
my $drone_schema = schema('drone');

my $img_dir = "/home/camp/angler/rsync/htdocs/assetstore/site/images/items";

my $exp_dir = config->{appdir} . '/export';

my @img_sizes = qw/35 75 100 110 200 325 975/;

my ( $active, $export, $file, $help, $manufacturer, %nav_lookup );


GetOptions(
    "active"         => \$active,
    "file=s"         => \$file,
    "help"           => \$help,
    "manufacturer=s" => \$manufacturer,
    "export"         => \$export,
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
$export = 0 unless $export;
# parse by type

my $type = $config->{manufacturers}->{$manufacturer}->{type};

if ( $type =~ /^xls/ ) {
    $config = $config->{manufacturers}->{$manufacturer};

    my $data = Angler::Populate::ParseExcel->new(
                                file => $file,
                                importer_config => $config
    );

    # parse excel file
    my @data = $data->parse;
    my $merge_cell = $config->{merge_cell}->{name};
    my %nav_map;

    # define nav mapping from config
    my $nav_map = $config->{navigation};

    # do some more work on data
    # FIXME this should be part of a ExcelParse class.
    foreach my $field (@data) {
        # perform navigation mapping
        if (exists $nav_map->{$field->{navigation}}) {
           $field->{navigation} = $nav_map->{$field->{navigation}};
        }
        if ($active) {
            $field->{active} = '1';
        }
    }


    my ($drone_data, $drone_rs);
    my $drone_class = $config->{drone}->{class};

    # check if drone has any data
    if ($drone_class) {
        $drone_rs = $drone_schema->resultset($drone_class);
        $drone_data = $drone_rs->count;
    }

    my %seen;
    my $product;
    my $drone_product;

    # Create a new Excel workbook
    my $workbook = Spreadsheet::WriteExcel->new($exp_dir . '/'. $manufacturer . $now .  '.xls');
    my $worksheet = $workbook->add_worksheet();
    my @excel_export = (
        ['canonical_desc', 'variant_desc', 'alu', 'upc', 'department_code', 'income_account', 'cogs_account',
        'attribute', 'size', 'vendor_code', 'vendor_name', 'avg_cost', 'order_cost', 'reg_price', 'msrp', 'item_type',
        'asset_account', 'custom_field_1']
    );

        # define caonical products in @data
    my @canonical = grep { ! $seen{$_->{code}}++ } @data;
    foreach (@canonical) {
        if ($drone_class) {
            # define drone link
            $drone_product = $drone_schema->resultset($drone_class)->find({ sku => $_->{code} });
        }
        # check if canonical product has any drone data;
        if($drone_product) {
            foreach my $dtf ( @{$config->{drone}->{fields}->{text}}) {
                $drone_data = $drone_product->$dtf;
                if ($drone_data){
                    $_->{$dtf} = $drone_data;
                }
             }
        }

        # remove sku from drone.
        delete $_->{sku} if $_->{sku};

        # add product
        my $pop_product = Angler::Populate::Product->new($_);
        $product = $pop_product->add;

        if ($product) {
            # add default navigation routes and weight;
            $pop_product->add_defaults($product);;
        }

        # export to excel
        if ($export) {
            push @excel_export, $pop_product->export;
        }
        # lets check the drone for an image

        if (defined($drone_product) and $drone_product->img) {

            my $image = Angler::Populate::Image->new(
                schema => shop_schema,
                url   => $drone_product->img,
                manufacturer  => $manufacturer,
                product =>  $product,
                img_dir => $img_dir
            );
            $image->download;
            $image->process;
        }
    }

    # reset
    %seen = ();
    my $variant;
    my @variants = grep { $seen{$_->{code}}++ } @data;       

    my @values;
    my @attributes;
    my $i = '-1';

    foreach my $attribute (@{$config->{attributes}}) {
        $i++;
        foreach (@variants) {
            # make sure value exists
            if ($_->{$attribute}) {
                push @values, $_->{$attribute};
                push @attributes, $_->{'attributes'}[$i] = {
                    name => $attribute,
                    title => ucfirst($attribute),
                    value => $_->{$attribute}},
                    $_->{'manufacturer'} = $manufacturer;
            }
        }
        # make array values unique
        @values = uniq @values;

        # add attributes
        my $attributes = Angler::Populate::Attribute->new(
            schema => shop_schema,
            name   => $attribute,
            title  => ucfirst $attribute,
            values =>  \@values
        );
        $attributes->add;
    }

    # add variants
    foreach my $record (@variants) {
        # check config for cells to merge
        if ($merge_cell) {
            $record->{$merge_cell} = join("-",map {$record->{$_}} @{$config->{merge_cell}->{fields}});
        }

        $variant = Angler::Populate::ProductVariant->new($record);
        $variant->add;
        if ($export) {
            my $remove = $variant->clean;
            # if product is a canonical product with variants delete the product record for erp export.
            if ($remove) {
                @excel_export = grep { $_->[2] ne $remove  } @excel_export;
            }
            push @excel_export, $variant->export;
        }
    }

    # write to file
    $worksheet->write_col('A1', \@excel_export)

    }
    else {
        die "xls processor for $manufacturer does not exist";
    }


__END__

=head1 NAME

importer.pl - Import manufacturer products lists into Angler

=head1 SYNOPSIS

moo-importer.pl [options]

 Options:
  -a | --active             set active to 't' for all items (defaults to 'f')
  -e | --export             set erp file export to 't' (defaults to 'f')
  -f | --file               file to import
  -m | --manufacturer       manufacturer name
  -h | --help               help message

=cut
