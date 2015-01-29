package Angler::Shipping;

use strict;
use warnings;

use Dancer ':syntax';
use Net::Easypost;
use Net::Easypost::Address;
use Net::Easypost::Parcel;

=head2 shipment_methods($schema, $country_iso_code)

Returns a ShipmentDestination resultset. In list context, you can get
the ShipmentMethod object calling shipment_method on the result row.

  foreach my $dest (shipment_methods($schema, 'US')) {
    my $method_name = $dest->shipment_method->name;
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
    foreach my $shipping_dest (shipment_methods($schema, $country, $postal_code)) {
        next unless $shipping_dest->shipment_method->active;
        my $shipment_rate =  $shipping_dest->shipment_method
          ->find_related('shipment_rates',
            {
                shipment_methods_id => $shipping_dest->shipment_method->id
            });
        next unless $shipment_rate;
        push @iterator, {
                         name => $shipping_dest->shipment_method->id,
                         title => $shipping_dest->shipment_method->title .' $ '. $shipment_rate->price,
                        };
    }
    @iterator = sort { $a->{title} cmp $b->{title} } @iterator;
    return \@iterator;
}

=head2 deliverable_countries

returns a Country resultset including countries in the 'Deliverable Countries' zone.

=cut

sub deliverable_countries {
    my ($schema) = @_;
    my $zone = $schema->resultset('Zone')->find({ zone => 'Deliverable Countries' });

    # 
    unless ($zone) {
        die "you need to populate the 'Deliverable Countries' zone.";
    }

    my @country_iso_codes;

    foreach my $country ($zone->countries) {
            push @country_iso_codes, $country->country_iso_code;
    }

    return [$schema->resultset('Country')
      ->search({
                country_iso_code => { -in => \@country_iso_codes },
                active => '1'
               })];

}

=head2 find_state

This method uses the data from angler6-populate-postal-zones.  With the input of a standard 5 digit US zipcode
the a state object is returned.

=cut

# from postal code get State
sub find_state {
    my ($schema, $postal_code, $country ) = @_;
    return unless $postal_code;
    # use 1st 3 digits
    my $postal_zone = substr($postal_code, 0, 3);

    my $zone_rs = $schema->resultset('Zone')->find({zone => 'US postal ' . $postal_zone });
    return unless $zone_rs;
    # the following looks like a missing relationship
    my $state_rs = $zone_rs->find_related('zone_states', { zones_id => $zone_rs->id});
    my $state = $schema->resultset("State")->find( { country_iso_code => $country, states_id => $state_rs->states_id } );

    return $state;
}

sub shipping_rate {
    my ($schema, $id ) = @_;
    unless ($id) {
        return 0;
    }
    my $shipment_rate = $schema->resultset("ShipmentRate")->find({ shipment_rates_id => $id });

    return $shipment_rate->price;
    }

# from state object provide 0|1
sub free_shipping_destination {
    my ($schema, $state ) = @_;
    my $lower48_rs = $schema->resultset("Zone")->find({ zone => 'US lower 48'});
    my $lower48 = $lower48_rs->find_related('zone_states', { states_id => $state->id });

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

sub show_rates {
    my ($cart) = @_;
    my $weight = 0;
    foreach my $product (@{$cart->cart->products}) {
        my $p = $cart->schema->resultset('Product')->find($product->sku);
        if ($p) {
            $weight += ($p->weight || 0);
        }
    }
    return unless $weight;
    my @rates = easy_post_get_rates($cart->schema, $cart->country,
                                    $cart->postal_code, $weight);
    my @out;
    foreach my $rate (@rates) {
        push @out, {
                    rate => $rate->price,
                    carrier_service => $rate->shipment_rates_id,
                    service => $rate->shipment_method->name,
                   };
    }
    return \@out;
}

sub easy_post_get_rates {
    my ($schema, $country, $zip, $weight) = @_;
    die "Missing schema" unless $schema;
    return unless $country;
    return unless $weight;
    return unless $zip;
    my $rates;

    my $ounces = $weight * 16;
    eval {
        my $easypost = Net::Easypost->new;
        my $from = Net::Easypost::Address->new(
                                               name => 'West Branch Resort',
                                               street1 => '150 Faulkener Rd',
                                               city => 'Hancock',
                                               state => 'NY',
                                               zip => '13783',
                                               country => 'US',
                                              );
        my $to = Net::Easypost::Address->new(
                                             zip => $zip,
                                             country => $country,
                                            );
        my $parcel = Net::Easypost::Parcel->new(
                                                weight => $ounces,
                                               );
        $rates = $easypost->get_rates({ to => $to, from => $from, parcel => $parcel });
    };

    # find the zone
    my $country_row = $schema->resultset('Country')->find({ country_iso_code => $country });
    die "Country not found" unless $country_row;
    my $zones_rs = $country_row->zones;
    my $zones_found = $zones_rs->count;
    my $zone;
    if ($zones_found == 1) {
        $zone = $zones_rs->first;
    }
    elsif ($zones_found > 1) {
        # search the zip
        my $postal_zone = substr($zip, 0, 3);
        $zone = $zones_rs->single({ zone => { -like => "% postal $postal_zone" } });
    }

    die "No zone found for $country and $zip!" unless $zone;
    # print "Zone id is " . $zone->id . " " . $zone->zone . "\n";
    my @out;
    if ($rates && @$rates) {
        foreach my $rate (@$rates) {
            # stuff them in the db
            # first the carrier
            my $carrier;
            if ($carrier = $schema->resultset('ShipmentCarrier')->find({ name => $rate->carrier })) {
            }
            else {
                $carrier = $schema->resultset('ShipmentCarrier')->create({ name => $rate->carrier,
                                                                           title => $rate->carrier });
            }
            my $method_name = $rate->carrier . ' ' . $rate->service;
            my $methods_rs = $carrier->shipment_methods;
            my %specs = (
                         name => $method_name,
                         min_weight => $weight,
                         max_weight => $weight,
                        );

            my $method = $methods_rs->find(\%specs);
            unless ($method) {
                $method = $methods_rs->create({
                                               %specs,
                                               title => $method_name,
                                              })->discard_changes;
            }
            die "This shouldn't happen" unless $method;
            # now we have a method and a zone, so create the rate

            my $db_ship_rate = $method->shipment_rates->find({
                                                     zones_id => $zone->zones_id,
                                                     shipment_methods_id => $method->shipment_methods_id,
                                                     min_weight => $weight,
                                                     max_weight => $weight,
                                                    });
            if ($db_ship_rate) {
                if ($db_ship_rate->price ne $rate->rate) {
                    warn $db_ship_rate->price . ' ne ' . $rate->rate . "For $method_name and $weight";
                    # maybe we should update here?
                }
            }
            else {
                $db_ship_rate = $method->shipment_rates->create({
                                                        zone => $zone,
                                                        shipment_method => $method,
                                                        min_weight => $weight,
                                                        max_weight => $weight,
                                                        price => $rate->rate,
                                                       });
            }
            push @out, $db_ship_rate;
        }
    }
    return sort { $a->price <=> $b->price } @out;
}



1;
