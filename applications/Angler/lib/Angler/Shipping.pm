package Angler::Shipping;

use strict;
use warnings;

=head2 shipment_methods($country_iso_code)

Return a ShipmentDestination resultset. In list context, you can get
the ShipmentMethod object calling ShipmentMethod on the result row.

  foreach my $dest (shipment_methods($schema, 'US')) {
    my $method_name = $dest->ShipmentMethod->name;
  }

=cut

sub shipment_methods {
    my ($schema, $country) = @_;

    my @zone_ids;
    # if we have a logic for the zip, we can refine the zone result.
    foreach my $zone ($schema->resultset('Country')->find($country)->zones) {
        push @zone_ids, $zone->zones_id;
    }

    return $schema->resultset('ShipmentDestination')
      ->search({
                zones_id => { -in => \@zone_ids },
                active => '1'
               });
}


1;
