package Angler::Populate::Inventory;

=head1 NAME

Angler::Populate::Inventory

=head1 DESCRIPTION

This module provides population capabilities for the Inventory class.

=cut

use strict;
use warnings;

use Moo;

use File::Slurp;
use Dancer ':script';
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Interchange6;
use File::Spec;
use Spreadsheet::ParseExcel;
use Data::Dumper::Concise;

=head2 file

Returns inventory file

=cut

has file => (
    is => 'ro',
);

=head2 importer_config

Returns importer config

=cut

has importer_config => (
    is => 'ro',
);

sub sync {
    my $self = shift;
    my ($min, $max); 
    my $parser = Spreadsheet::ParseExcel->new;

    # parse the file
    my $workbook = $parser->parse($self->file);
    if ( !defined $workbook ) {
        die $parser->error(), ".\n";
    }

    # we need at least one worksheet
    die "no worksheets found" unless $workbook->worksheet_count;
    my $worksheet = $workbook->worksheet(0);
    die "worksheet not found" unless $worksheet;

    # col/row ranges in use
    my ( $row_min, $row_max ) = $worksheet->row_range();
    my ( $col_min, $col_max ) = $worksheet->col_range();

    # find columns we are interested in
    my ( $item_col, $inv_col, $color_code_col, $size_col );

    foreach my $col ( $col_min .. $col_max ) {
        my $value = $worksheet->get_cell( $row_min, $col )->value;
        if ( $value eq 'Item' ) {
            $item_col = $col;
        }
        if ( $value eq 'Color Code' ) {
            $color_code_col = $col;
        }
        if ( $value eq 'Size' ) {
            $size_col = $col;
        } 
        elsif ( $value eq 'Inventory' ) {
            $inv_col = $col;
        }
    }

    die "Cannot find required columns in ", $self->file
    unless ( $item_col && $inv_col && $color_code_col && $size_col );

    my $inventory;

    foreach my $row ( $row_min + 1 .. $row_max ) {
        my $sku =
            $self->importer_config->{prefix}
            . $worksheet->get_cell( $row, $item_col )->value . '-'
            . $worksheet->get_cell( $row, $color_code_col )->value . '-'
            . $worksheet->get_cell( $row, $size_col )->value;
        $sku =~ s/^\s+|\s+$//g;
        my $qty = $worksheet->get_cell( $row, $inv_col )->value;
        my $product = shop_product->find({ sku => $sku });  

        if ( $product ) {
             print "Inventory sync for sku ", $sku, "\n";
            # manufacturer has stock so nice short lead time
            $min = 3;
            $max = 5;
            if ($product->inventory) {
                if ( $product->inventory->quantity == 0 && $max == 0 ) {
                    $product->inventory->delete;
                    $product->update({ active => 0 });
                    return;
                }
                $product->inventory->update(
                    {
                        lead_time_min_days    => $min,
                        lead_time_max_days    => $max,
                        manufacturer_quantity => $qty,
                    }
                );
            }
            else {
                $product->create_related(
                    'inventory',
                        {
                            quantity              => 0,
                            lead_time_min_days    => $min,
                            lead_time_max_days    => $max,
                            manufacturer_quantity => $qty,
                        }
                );
            }
        }
    }
}

1;
