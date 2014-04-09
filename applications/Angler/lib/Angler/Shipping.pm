package Angler::Shipping;

use strict;
use warnings;

use Dancer ':syntax';

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
    my ($schema, $country, $postal_code) = @_;

    my @zone_ids;
    my $country_rs = $schema->resultset('Country')->find($country);
    if ($country_rs and $country eq 'US' and $postal_code) {
        if (my $state = find_state($schema, $postal_code)) {
            foreach my $zone ($state->zones) {
                push @zone_ids, $zone->zones_id;
            }
        }
    }
    elsif ($country_rs) {
        foreach my $zone ($country_rs->zones) {
            push @zone_ids, $zone->zones_id;
        }
    }
    return $schema->resultset('ShipmentDestination')
      ->search({
                zones_id => { -in => \@zone_ids },
                active => '1'
               });
}

sub shipment_methods_iterator_by_iso_country {
    my ($schema, $country, $postal_code) = @_;
    my @iterator;
    foreach my $shipping (shipment_methods($schema, $country, $postal_code)) {
        next unless $shipping->ShipmentMethod->active;
        my $shipment_rate =  $shipping->ShipmentMethod
          ->find_related('ShipmentRate',
            {
                shipment_methods_id => $shipping->ShipmentMethod->id
            });
        push @iterator, {
                         name => $shipping->ShipmentMethod->id,
                         title => $shipping->ShipmentMethod->title .' $ '. $shipment_rate->price,
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
    return unless $zone_rs;
    # the following looks like a missing relationship
    my $state_rs = $zone_rs->find_related('ZoneState', { zones_id => $zone_rs->id});
    my $state = $schema->resultset("State")->find( { country_iso_code => $country, states_id => $state_rs->states_id } );

    return $state;
}

sub shipping_rate {
    my ($schema, $id ) = @_;
    unless ($id) {
        return 0;
    }
    my $shipment_rate = $schema->resultset("ShipmentRate")->find({ shipment_methods_id => $id });

    return $shipment_rate->price;
    }

# from state object provide 0|1
sub free_shipping_destination {
    my ($schema, $state ) = @_;
    my $lower48_rs = $schema->resultset("Zone")->find({ zone => 'US lower 48'});
    my $lower48 = $lower48_rs->find_related('ZoneState', { states_id => $state->id });

    unless ($lower48) {
        return 0;
    }
    return 1;
}

# from cart object provide 0|1
sub free_shipping_cart {
    my ($schema, $cart) = @_;
    unless ($cart >= '100.00') {
        return 0;
    }
    return 1;
}

1;
