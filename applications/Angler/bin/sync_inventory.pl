#!/usr/bin/env perl

use warnings;
use strict;
use v5.14;

use Dancer ':script';
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Interchange6;
use File::Basename;
use File::Path qw/make_path/;
use File::Spec;
use List::Util qw/all/;
use Spreadsheet::ParseExcel;
use Spreadsheet::ParseXLSX;
use Try::Tiny;
use URI::Escape;
use Data::Dumper::Concise;

set logger => 'console';
set log    => 'info';

my $schema = shop_schema;

my $inventory_report = 
  File::Spec->catfile( [ File::Spec->splitpath($0) ]->[1],
  '..', 'shared', 'data', 'WBA_Inventory_Export.xls' );

my $parser = Spreadsheet::ParseExcel->new;

# parse the file

my $workbook = $parser->parse($inventory_report);

if ( !defined $workbook ) {
    print "$inventory_report";
    die $parser->error(), ".\n";
}

# we need at least one worksheet

die "no worksheets found" unless $workbook->worksheet_count;

my $worksheet = $workbook->worksheet(0);
die "worksheet not found" unless $worksheet;

my ($upc_col, $alu_col, $custom_col, $qty_col);

# col/row ranges in use
my ( $row_min, $row_max ) = $worksheet->row_range();
my ( $col_min, $col_max ) = $worksheet->col_range();



foreach my $col ( $col_min .. $col_max ) {
    my $value = $worksheet->get_cell( $row_min, $col )->value;

    print "Value" , $value, "\n";

    if ( $value eq 'UPC' ) {
        print "found UPC";
        $upc_col = $col;
    }
    elsif ( $value eq 'Alternate Lookup' ) {
        $alu_col = $col;
    }
    elsif ( $value eq 'Custom Field 1' ) {
        $custom_col = $col;
    }
    elsif ( $value eq 'Qty 1' ) {
        $qty_col = $col;
    }
}

    my ($manufacturer_sku, $gtin, $sku, $qty);

    foreach my $row ( $row_min + 1 .. $row_max ) {
        my $manufacturer_sku_col = $worksheet->get_cell( $row, $alu_col);
        my $gtin_col = $worksheet->get_cell( $row, $upc_col );
        my $sku_col = $worksheet->get_cell( $row, $custom_col );
        my $qty_col =  $worksheet->get_cell( $row, $qty_col );

        if ( defined  $manufacturer_sku_col ) {
            $manufacturer_sku =  $manufacturer_sku_col->value;
        }
        if ( defined  $gtin_col ) {
            $gtin = $gtin_col->value;
            $gtin =~ s/^0+//;
        }
        if ( defined  $sku_col ) {
            $sku =  $sku_col->value;
        }
        if ( defined  $qty_col ) {
            $qty = $qty_col->value;
        }

   # print " alu ", $manufacturer_sku, " gtin ",  $gtin, " sku ",  $sku; 

        my $product = shop_product->find(
            { manufacturer_sku => $manufacturer_sku},
            { gtin =>  $gtin },
            { sku => $sku },
           
        );

    if ($product) {
        #check if inventory record exists for item.
        my $inventory = 
            $schema->resultset('Inventory')->find({sku => $product->sku});
        if ($inventory) {
            print "Product SKU :", $product->sku, " Inventory Updated \n";
            $inventory->update({quantity => $qty});
        }
        else {
            print "Product SKU :", $product->sku, " Inventory Inventory Record Did Not Exist... CREATED \n";
            $schema->resultset('Inventory')->create({
                sku => $product->sku,
                quantity => $qty
            });
        }
    }
}

1;
