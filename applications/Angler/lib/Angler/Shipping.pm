package Angler::Shipping;

use strict;
use warnings;

use Net::Easypost;
use Net::Easypost::Address;
use Net::Easypost::Parcel;
use Dancer qw/debug error warning/;
use Safe::Isa;

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
        if (my $state = find_state($schema, $postal_code, $country)) {
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

    unless ($zone) {
        die "you need to populate the 'Deliverable Countries' zone.";
    }

    return [
        $zone->countries->search(
            {
                active => '1'
            },
            { order_by => 'name' }
        )
    ];
}

=head2 find_state

This method uses the data from angler6-populate-postal-zones.  With the input of a standard 5 digit US zipcode
the a state object is returned.

=cut

# from postal code get State
sub find_state {
    my ($schema, $postal_code, $country ) = @_;
    return unless $postal_code;
    return unless $country eq 'US';

    # use 1st 3 digits
    my $postal_zone = substr($postal_code, 0, 3);

    my $zone = $schema->resultset('Zone')->find(
        {
            zone => "US postal $postal_zone"
        }
    );

    return unless $zone;

    my $state = $zone->states->first;

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

=head2 show_rates( $angler_cart, $from_db_flag )

First argument is an instance of Angler::Cart. Second optional argument should
be true if only rates from the database are required.

Always tries to get rates from database before making call to easypost.

No rates will be returned if $angler_cart->cart->weight is undef or zero.

=cut

sub show_rates {
    my ($cart, $from_db) = @_;

    die "Shipping show_rates arg is not an Angler::Cart object"
      unless $cart->$_isa('Angler::Cart');

    # zero weight is valid for things such as gift tokens or services

    return unless $cart->cart->weight;

    # cannot do anything if we don't have a postal code yet

    return unless $cart->postal_code;

    my @rates = get_database_rates(
        $cart->schema,      $cart->country,
        $cart->postal_code, $cart->cart->weight
    );

    if ( !@rates && !$from_db ) {

        @rates = easy_post_get_rates(
            $cart->schema,      $cart->country,
            $cart->postal_code, $cart->cart->weight
        );
    }

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

=head2 shipment_zone( $schema, $country_iso_code, $zip )

Returns the zone for ShipmentRate queries.

=cut

sub shipment_zone {
    my ( $schema, $country, $zip ) = @_;

    my $zone;
    my $zone_rs = $schema->resultset('Zone');

    if ( $country eq 'US' ) {

        # search the zip

        my $postal_zone = substr( $zip, 0, 3 );
        $zone = $zone_rs->find( { zone => "US postal $postal_zone" } );
    }
    else {

        # match zone by country name

        $zone = $zone_rs->search(
            {
                'zone_countries.country_iso_code' => $country,
                'zone.zone' => { -ident => 'country.name' },
            },
            {
                join => { zone_countries => 'country' },
                rows => 1,
            }
        )->single;
    }

    if ( !$zone ) {
        error "Shipping shipment_zone no zone found for $country and $zip";
        return;
    }

    return $zone;
}

=head2 get_database_rates( $schema, $country_iso_code, $zip, $weight )

Get rates from the database. Returns an empty array reference if no rates are
available.

=cut

sub get_database_rates {
    my ( $schema, $country_iso_code, $zip, $weight ) = @_;

    my $zone = &shipment_zone( $schema, $country_iso_code, $zip );
    return unless $zone;

    my $now = $schema->format_datetime(DateTime->now);
    my @rates = $schema->resultset('ShipmentRate')->search(
        {
            'me.zones_id'   => $zone->id,
            'me.valid_from' => { '<=' => $now },
            'me.valid_to'   => { '>=' => $now },
            'me.value_type' => 'weight',
            'me.value_unit' => 'lb',
            'me.min_value'  => { '<=' => $weight },
            'me.max_value'  => { '>=' => $weight },
        },
        {
            prefetch => 'shipment_method',
            order_by => 'me.price',
        },
    )->all;

    return @rates;
}

=head2 easy_post_get_rates( $schema, $country_iso_code, $zip, $weight )

Get rates from easypost, store them in the database and return them.

=cut

sub easy_post_get_rates {
    my ($schema, $country, $zip, $weight) = @_;
    die "Missing schema" unless $schema;
    return unless $country;
    return unless $weight;
    return unless $zip;

    my ( $rates, @out );

    my $zone = &shipment_zone( $schema, $country, $zip );
    return unless $zone;

    my $ounces = $weight * 16;
    eval {
        my $easypost = Net::Easypost->new;
        my $from     = Net::Easypost::Address->new(
            name    => 'West Branch Resort',
            street1 => '150 Faulkener Rd',
            city    => 'Hancock',
            state   => 'NY',
            zip     => '13783',
            country => 'US',
        );
        my $to = Net::Easypost::Address->new(
            zip     => $zip,
            country => $country,
        );
        my $parcel = Net::Easypost::Parcel->new( weight => $ounces, );
        $rates = $easypost->get_rates(
            { to => $to, from => $from, parcel => $parcel } );
    };
    if ($@) {
        error "Easy post call failed: $@";
        return;
    }

    if ( $rates && ref($rates) eq 'ARRAY' ) {
        foreach my $rate (@$rates) {

            # stuff them in the db
            # first the carrier

            my $carrier = $schema->resultset('ShipmentCarrier')->find_or_create(
                {
                    name  => $rate->carrier,
                    title => $rate->carrier
                }
            );

            my $method_name = $rate->carrier . ' ' . $rate->service;

            my $shipment_method = $carrier->search_related(
                'shipment_methods',
                {
                    name => $method_name,
                },
                {
                    rows => 1,
                }
            )->single;

            if ( not $shipment_method ) {
                $shipment_method = $carrier->create_related(
                    'shipment_methods',
                    {
                        name  => $method_name,
                        title => $method_name,
                    }
                );
            }

            # now we have a method and a zone, so update/create the rate

            my $now        = DateTime->now;
            my $valid_from = $schema->format_datetime($now);
            my $valid_to   = $schema->format_datetime( $now->add( days => 7 ) );

            my $db_ship_rate = $shipment_method->search_related(
                'shipment_rates',
                {
                    zones_id   => $zone->zones_id,
                    value_type => 'weight',
                    value_unit => 'lb',
                    min_value  => $weight,
                    max_value  => $weight,
                },
                {
                    rows => 1,
                }
            )->single;

            if ($db_ship_rate) {

                # update existing rate

                $db_ship_rate->update(
                    {
                        valid_from => $valid_from,
                        valid_to   => $valid_to,
                        price      => $rate->price
                    }
                );
            }
            else {

                # create new rate

                $db_ship_rate = $shipment_method->create_related(
                    'shipment_rates',
                    {
                        zones_id   => $zone->zones_id,
                        value_type => 'weight',
                        value_unit => 'lb',
                        min_value  => $weight,
                        max_value  => $weight,
                        valid_from => $valid_from,
                        valid_to   => $valid_to,
                        price      => $rate->rate,
                    }
                );
            }
            push @out, $db_ship_rate;
        }
    }
    return sort { $a->price <=> $b->price } @out;
}

1;
