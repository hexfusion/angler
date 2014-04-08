package Angler::Shipping;

use strict;
use warnings;

=head2 shipment_methods($schema, $country_iso_code)

Return a ShipmentDestination resultset. In list context, you can get
the ShipmentMethod object calling ShipmentMethod on the result row.

  foreach my $dest (shipment_methods($schema, 'US')) {
    my $method_name = $dest->ShipmentMethod->name;
  }

=head2 shipment_methods_iterator_by_iso_country($schema, $country)

Using the above function, return an arrayref with name and title.

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

sub shipment_methods_iterator_by_iso_country {
    my ($schema, $country) = @_;
    my @iterator;
    foreach my $shipping (shipment_methods($schema, $country)) {
        next unless $shipping->ShipmentMethod->active;
        push @iterator, {
                         name => $shipping->ShipmentMethod->name,
                         title => $shipping->ShipmentMethod->title,
                        };
    }
    @iterator = sort { $a->{title} cmp $b->{title} } @iterator;
    return \@iterator;
}

# from postal code get State
sub find_state {
    my ($schema, $postal_code, $country ) = @_;

    # use 1st 3 digits
    my $postal_zone = substr($postal_code, 0, 3);

    my $zone_rs = $schema->resultset('Zone')->find({zone => 'US postal ' . $postal_zone });
    my $state_rs = $zone_rs->find_related('ZoneState', { zones_id => $zone_rs->id});
    my $state = $schema->resultset("State")->find( { country_iso_code => $country, states_id => $state_rs->states_id } );

    return $state;
}

# from state object provides 0|1
sub free_shipping_destination {
    my ($schema, $state ) = @_;
    my $lower48_rs = $schema->resultset("Zone")->find({ zone => 'US lower 48'});
    my $lower48 = $lower48_rs->find_related('ZoneState', { states_id => $state->id });

    unless ($lower48) {
        return 0;
    }
    return 1;
}

1;
