package Angler::Interchange6::Schema::ResultSet::Product;

use warnings;
use strict;

=head1 NAME

Angler::Interchange6::Schema::ResultSet::Product

=cut

package Interchange6::Schema::ResultSet::Product;
use Interchange6::Schema::ResultSet::Product;
use Class::Method::Modifiers;

=head1 METHODS

=head2 with_manufacturer_inventory

Add C<lead_time_min_days>, C<lead_time_max_days> and C<manufacturer_quantity>
from L<Angler::Interchange6::Schema::Result::Inventory> to query using
correlated subqueries.

=cut

sub with_manufacturer_inventory {
    my $self = shift;

    return $self->search(
        undef,
        {
            '+select' => [
                {
                    coalesce => [

                        $self->correlate('variants')
                          ->related_resultset('inventory')
                          ->get_column('lead_time_min_days')->min_rs->as_query,

                        $self->correlate('inventory')
                          ->get_column('lead_time_min_days')->as_query,

                    ],
                    -as => 'lead_time_min_days',
                },
                {
                    coalesce => [

                        $self->correlate('variants')
                          ->related_resultset('inventory')
                          ->get_column('lead_time_max_days')->max_rs->as_query,

                        $self->correlate('inventory')
                          ->get_column('lead_time_max_days')->as_query,

                    ],
                    -as => 'lead_time_max_days',
                },
                {
                    coalesce => [

                        $self->correlate('variants')
                          ->related_resultset('inventory')
                          ->get_column('manufacturer_quantity')
                          ->sum_rs->as_query,

                        $self->correlate('inventory')
                          ->get_column('manufacturer_quantity')->as_query,

                    ],
                    -as => 'manufacturer_quantity',
                },
            ],
            '+as' => [
                'lead_time_min_days',
                'lead_time_max_days',
                'manufacturer_quantity'
            ],
        }
    );
}

=head2 listing

Add L</with_manufacturer_inventory> to
L<Interchange6::Schema::Result::Inventory/listing>.

=cut

around 'listing' => sub {
    my $orig = shift;
    my $new = $orig->(@_);
    return $new->with_manufacturer_inventory;
};

1;
