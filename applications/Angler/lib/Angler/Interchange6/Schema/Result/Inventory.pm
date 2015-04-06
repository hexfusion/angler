package Angler::Interchange6::Schema::Result::Inventory;

use strict;
use warnings;

use base 'DBIx::Class::Schema';

use Interchange6::Schema::Result::Inventory;
package Interchange6::Schema::Result::Inventory;

=head1 NAME

Angler::Interchange6::Schema::Result::Inventory

=head1 DESCRIPTION

Adds extra columns and methods to L<Interchange6::Schema::Result::Inventory>.

=head1 ACCESSORS

=head2 lead_time_min_days 

numeric value representing minimum lead time for delivery of product

=cut

=head2 lead_time_max_days

numeric value representing maximum lead time for delivery of product

=cut

=head2 manufacturer_quantity

numeric value representing number of products available from manufacturer

=cut

__PACKAGE__->add_columns(
    lead_time_min_days => {
        data_type      => "integer",
        default_value  => 0
    },
    lead_time_max_days => {
        data_type      => "integer",
        default_value  => 0
    },
    manufacturer_quantity  => {
        data_type      => "integer",
        default_value  => 0
    },
);

1;

