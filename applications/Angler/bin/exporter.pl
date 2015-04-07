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
use Spreadsheet::WriteExcel;
use DateTime qw();
use Data::Dumper;

my $now    = DateTime->now;
my $manufacturer = 'orvis';
my $exp_dir = config->{appdir} . '/export';

my $navigation_rs = shop_product->search({ sku => { like => 'WB-OR-%' } });

print "Prduct Count", $navigation_rs->count;

# Create a new Excel workbook
my $workbook = Spreadsheet::WriteExcel->new($exp_dir . '/'. $manufacturer . $now .  '.xls');
my $worksheet = $workbook->add_worksheet();
my @excel_export = (
        ['canonical_desc', 'variant_desc', 'alu', 'upc', 'department_code', 'income_account', 'cogs_account',
        'attribute', 'size', 'vendor_code', 'vendor_name', 'avg_cost', 'order_cost', 'reg_price', 'msrp', 'item_type',
        'asset_account', 'custom_field_1']
);


while (my $product = $navigation_rs->next) {
    #print "sku ", $product->sku , "\n";

   my ($sku, $canonical_name);

    if ($product->canonical_sku) {
        $sku = $product->canonical_sku;
        $canonical_name = $product->canonical->name;
    }
    else {
        $sku = $product->sku;
        $canonical_name = $product->name;
    }

   my $code = shop_schema->resultset('NavigationProduct')->search_related('navigation', { sku => $sku, 'navigation.type' => 'menu'})->first->code;

   # if we don't have a price skip 
   if ($product->price > 0) {
        my @data =  ([
            $product->name ,  #variant_desc
            $canonical_name ,  #variant_desc
            $product->manufacturer_sku, #alu
            $product->gtin, #upc
            $code, #department_code
            'Retail Sales', #income_account
            'COGS-Retail', #cogs_account
            $product->find_attribute_value('color') || undef, #attribute
            $product->find_attribute_value('size') || undef, #size
            #$self->importer_config->{vendor_code}, #vendor_code
            'Orv', #vendor_code
            # $self->importer_config->{erp_name}, #vendor_name
            "Orvis Services, Inc.", #vendor_name
            $product->price/2, #avg_cost
            $product->price/2 ,#order_cost
            $product->price, #reg_price
            $product->price, #msrp
            'Inventory', #item_type
            'Inventory Asset', #asset_account
            $product->sku #custom_field_1
        ]);
        push @excel_export, @data;
    }
}

$worksheet->write_col('A1', \@excel_export);

1;
