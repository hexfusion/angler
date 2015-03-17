package Angler::Populate::ParseExcel;

=head1 NAME

Angler::Populate::ParseExcel

=head1 DESCRIPTION

This module parses and maps excel data file based on importer.yml configs.

=cut

use 5.010001;
use strict;
use warnings;

use Dancer ':syntax';
use Moo;
use Spreadsheet::ParseExcel;
use Spreadsheet::ParseXLSX;
use Data::Dumper;

set logger => 'console';
set log    => 'info';

=head2 data

Returns direct datafeed from importer

=cut

has file => (
    is => 'ro',
    required => 1,
);

=head2 importer_config

Returns importer config

=cut

has importer_config => (
    is => 'ro',
);

sub parse {
    my ($self) = @_;

    my $config = $self->importer_config;

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

   # check headers
        # grab headers
        my %headers;
        foreach my $col ( $col_min .. $col_max ) {
            $headers{$col} = &trim(@{$config->{headers}}[$col]->{map}); 
        }

    # define manufacturer prefix
    my $prefix = $config->{prefix};

    my @data;
    foreach my $row ( 2 .. $row_max ) {
        my %cells =
          map {
            $headers{$_} => &trim( $worksheet->get_cell( $row, $_ )->value )
          } $col_min .. $col_max;
            # remove if map = delete
            delete $cells{'remove_field'};
            $cells{'manufacturer'} = lc $config->{short_name};
            push @data, \%cells;

     }
    return @data;
}

sub trim {
    my $text = shift;
    $text =~ s/^\s+|\s+$//g;
    return $text;
}

1;
