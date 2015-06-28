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
use Angler::Populate::Inventory;
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
use DateTime qw();

my $now    = DateTime->now;

set logger => 'console';
set log    => 'info';

my ( $sync, $help, $manufacturer );


GetOptions(
    "help"           => \$help,
    "manufacturer=s" => \$manufacturer,
    "sync"           => \$sync,
);

pod2usage(1) if $help;

my $config =
  LoadFile( File::Spec->catfile( config->{appdir}, "importer.yml" ) );

unless ( defined $config->{manufacturers}->{$manufacturer} ) {
    print "ERROR: manufacturer not found. Valid manufacturers are\n";
    print( join( " ", keys %{ $config->{manufacturers} } ), "\n" );
    exit 1;
}

unless ( defined $config->{manufacturers}->{$manufacturer}->{type} ) {
    print "ERROR: type not defined for manufacturer $manufacturer in config\n";
    exit 1;
}

my ($inventory_file, $inv);
$config = $config->{manufacturers}->{$manufacturer};
    # check for inventory file
    if ( $config->{inventory_file} ) {
        $inventory_file = 
            File::Spec->catfile( [ File::Spec->splitpath($0) ]->[1],
            '..', 'shared', 'data',  $config->{inventory_file} );
        $inv = Angler::Populate::Inventory->new(
            {
                file => $inventory_file,
                importer_config => $config,
                schema => schema
            }
        );
        print "Intiate inventory sync \n";
        my $total = $inv->sync;
}


__END__

=head1 NAME

inventory.pl - Update current inventory for manufacturer based on inventory file

=head1 SYNOPSIS

inventory.pl [options]

 Options:
  -m | --manufacturer       manufacturer name
  -h | --help               help message
  -s | --sync               sync inventory

=cut
