package Angler::Populate::ParseExcel;

=head1 NAME

Angler::Populate::ParseExcel

=head1 DESCRIPTION

This module provides population capabilities for the Simms manufacturer

=cut

use 5.010001;
use strict;
use warnings;

use Dancer ':syntax';
use Moo;
use MooX::Types::MooseLike::Base qw(ArrayRef HashRef Int);
use Spreadsheet::ParseExcel;
use Spreadsheet::ParseXLSX;
use Data::Dumper;
use YAML qw/LoadFile/;
use File::Spec;;

set logger => 'console';
set log    => 'info';

=head2 data

Returns direct datafeed from importer

=cut

has file => (
    is => 'ro',
    required => 1,
);

=head2 config

Returns importer config

=cut

has importer_config => (
    is => 'ro',
);

sub parse {
    my ($self) = @_;

    my $manufacturer = 'simms';

    my $config =
      LoadFile( File::Spec->catfile( config->{appdir}, "importer.yml" ) );

    $config = $config->{manufacturers}->{$manufacturer};

    my $parser =
      $config->{type} eq 'xlsx'
      ? Spreadsheet::ParseXLSX->new
      : Spreadsheet::ParseExcel->new;

    # parse the file

    my $workbook = $parser->parse($self->{file});
    if ( !defined $workbook ) {
        die $parser->error(), ".\n";
    }

    # we need at least one worksheet

    die "no worksheets found" unless $workbook->worksheet_count;

    my $worksheet = $workbook->worksheet( $config->{worksheet} );

    die "worksheet not found" unless $worksheet;

    my ( $col_min, $col_max ) = $worksheet->col_range();
    my ( $row_min, $row_max ) = $worksheet->row_range();

    info "col min $col_min $col_max";

    # grab headers
    my %headers;
    foreach my $col ( $col_min .. $col_max ) {
        $headers{$col} =
           $worksheet->get_cell( $config->{header_row}, $col )->value;
    }

    my @map = (
        { source => 'Product Group Code', action => 'map', result => 'code'},
        { source => 'SKU', action => 'map', result => 'manufacturer_sku'},
        { source => 'Product Name - Display', action => 'map', result => 'name'},
        { source => 'SRP', action => 'map', result => 'price'},
        { source => 'Color', action => 'map', result => 'color'},
        { source => 'Size', action => 'map', result => 'size'},
        { source => 'UPC', action => 'map', result => 'gtin'},
        { source => 'Item Category Code', action => 'map', result =>'navigation'},
        { source => 'Season', action => 'map', result => 'season'},
        { source => 'Base', action => 'delete' },
        { source => 'Product Name - Description', action=> 'delete' }
    );

    my @values;

   foreach my $row ( 2 .. $row_max ) {
        my %cells =
          map {
            $headers{$_} => $worksheet->get_cell( $row, $_ )->value 
          } $col_min .. $col_max;


    foreach (@map) {
        if ($_->{action} eq 'delete') {
            delete $cells{$_->{source}};
        }
        elsif ($_->{action} eq 'map'){
            $cells{ $_->{result} } = $cells{$_->{source}};
            delete $cells{$_->{source}};
        }
    }
    push @values, \%cells;
    }
    #print Dumper($cells);
print Dumper(\@values);

}

sub trim {
    my $text = shift;
    $text =~ s/^\s+|\s+$//g;
    return $text;
}

1;
