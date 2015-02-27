#!/usr/bin/env perl

use warnings;
use strict;
use v5.14;

package Product;

use Moo;

package main;

use Dancer ':script';
use Angler::Interchange6::Schema;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Interchange6;
#use Angler::Interchange6::Schema::Populate::Media;
use Angler::Populate::Product;
use Angler::Populate::Attribute;
use Angler::Populate::ParseExcel;
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
use Text::Unidecode;
use Time::HiRes qw/sleep/;
use Try::Tiny;
use URI::Escape;
use XML::Twig;
use YAML qw/LoadFile/;
use List::MoreUtils qw(uniq);
use Data::Dumper::Concise;

set logger => 'console';
set log    => 'info';

my $count = 0;

my $config =
  LoadFile( File::Spec->catfile( config->{appdir}, "importer.yml" ) );

my $schema = shop_schema;
my $drone_schema = schema('drone');

my $img_dir = "/home/camp/angler/rsync/htdocs/assetstore/site/images/items";

my @img_sizes = qw/35 75 100 110 200 325 975/;

my ( $active, $file, $help, $manufacturer, %nav_lookup );


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

# parse by type

my $type = $config->{manufacturers}->{$manufacturer}->{type};

if ( $type =~ /^xls/ ) {
    $config = $config->{manufacturers}->{$manufacturer};

    my $data = Angler::Populate::ParseExcel->new(
                                file => $file,
                                importer_config => $config
    );

    #workflow

    # parse excel file
    my @data = $data->parse;

    # append excel data with drone if exists
#    my $drone = Angler::Populate::Drone->new(
#    );

    # create attributes and attribute_values
    my @values;
    foreach my $attribute (@{$config->{attributes}}) {
        foreach (@data) {
            # make sure value exists
            if ($_->{$attribute}) {
                push @values, $_->{$attribute};
            }
        }
        # make array unique
        @values = uniq @values;
        # add
        my $attributes = Angler::Populate::Attribute->new(
            schema => shop_schema,
            name   => $attribute,
            title  => ucfirst $attribute,
            values =>  \@values
        );
        $attributes->add;
    }


    # add canonical products
    my %seen;
    my $product;
    my @canonical = grep { ! $seen{$_->{code}}++ } @data;

    foreach (@canonical) {
    $product = Angler::Populate::Product->new($_);
        $product->add;
    }

#    my @variants = grep { $seen{$_->{code}}++ } @data;

    }
    else {
        die "xls processor for $manufacturer does not exist";
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
