package Angler::Populate::Size;

use strict;
use warnings;

use Moo;
use Angler::Interchange6::Schema;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Interchange6;
use Angler::Populate::Attribute;

=head1 NAME

Angler::Populate::Size

=head1 DESCRIPTION

=head1 SYNOPSIS

=cut

my %size;

$size{clothing} = (
    # basic clothing size
    { value => 'xxs', title => 'Extra Extra Small', priority => '1'},
    { value => 'xs', title => 'Extra Small', priority => '2'},
    { value => 's', title => 'Small', priority => '3'},
    { value => 'm', title => 'Medium', priority => '4'},
    { value => 'l', title => 'Large', priority => '5'},
    { value => 'xl', title => 'Extra Large', priority => '6'},
    { value => 'xxl', title => 'Extra Extra Large', priority => '7'},
    { value => 'xxxl', title => '3XL', priority => '8'},
    { value => 'xxxxl', title => '4XL', priority => '9'},
    { value => 'xxxxxl', title => '5XL', priority => '10'},
)

    # clothing range
    { value => 'xs/s', title => 'XS/S', priority => '1'},
    { value => 's/m', title => 'S/M', priority => '2'},
    { value => 'm/l', title => 'M/L', priority => '3'},
    { value => 'l/xl', title => 'L/XL', priority => '4'},
    { value => 'xl/xxl', title => 'XL/XXL', priority => '5'},
    { value => 'xxxl/xxxxl', title => '3XL/4XL', priority => '6'},

    # tippet
    { value => '0x', title => '0X', priority => '1'},
    { value => '1x', title => '1X', priority => '2'},
    { value => '2x', title => '2X', priority => '3'},
    { value => '3x', title => '3X', priority => '4'},
    { value => '4x', title => '4X', priority => '5'},
    { value => '4.5x', title => '4.5X', priority => '6'},
    { value => '5x', title => '5X', priority => '7'},
    { value => '5.5x', title => '5.5X', priority => '8'},
    { value => '6x', title => '6X', priority => '9'},
    { value => '6.5x', title => '6.5X', priority => '10'},
    { value => '7x', title => '7X', priority => '11'},
    { value => '8x', title => '8X', priority => '12'},
    { value => '9x', title => '9X', priority => '13'},

    # footwear
    { value => '0', title => '0', priority => '1'},
    { value => '0.5', title => '0 1/2', priority => '2'},
    { value => '1', title => '1', priority => '3'},
    { value => '1.5', title => '1 1/2', priority => '4'},
    { value => '2', title => '2', priority => '5'},
    { value => '2.5', title => '2 1/2', priority => '6'},
    { value => '3', title => '3', priority => '7'},
    { value => '3.5', title => '3 1/2', priority => '8'},
    { value => '4', title => '4', priority => '9'},
    { value => '4.5', title => '4 1/2', priority => '10'},
    { value => '5', title => '5', priority => '11'},
    { value => '5.5', title => '5 1/2', priority => '12'},
    { value => '6', title => '6', priority => '13'},
    { value => '6.5', title => '6 1/2', priority => '14'},
    { value => '7', title => '7', priority => '15'},
    { value => '7.5', title => '1 1/2', priority => '16'},
    { value => '8', title => '8', priority => '17'},
    { value => '8.5', title => '8 1/2', priority => '18'},
    { value => '9', title => '9', priority => '19'},
    { value => '9.5', title => '9 1/2', priority => '20'},
    { value => '10', title => '10', priority => '21'},
    { value => '10.5', title => '10 1/2', priority => '22'},
    { value => '11', title => '11', priority => '23'},
    { value => '12.5', title => '12 1/2', priority => '24'},
    { value => '13', title => '13', priority => '25'},
    { value => '14.5', title => '14 1/2', priority => '26'},
    { value => '15', title => '15', priority => '27'},
    { value => '15.5', title => '15 1/2', priority => '29'},
    { value => '16', title => '16', priority => '30'},
    { value => '16.5', title => '16 1/2', priority => '31'},
);

sub add {
    # add attributes
    my $attributes = Angler::Populate::Attribute->new(
        schema => shop_schema,
        name   => 'size',
        title  => 'Choose Size',
        values =>  \@sizes
    );
    $attributes->add;
}

1;
