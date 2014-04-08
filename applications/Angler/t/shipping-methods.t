#!/usr/bin/env perl

use strict;
use warnings;

use Test::More import => [ "!pass" ];

use Dancer qw/:script/;
use Dancer::Plugin::DBIC;
use Angler::Shipping;

my $code = 'US';
my $test_country;
eval {
    $test_country = schema->resultset('Country')->find($code);
};

if ($test_country) {
    plan tests => 24;
}
else {
    plan skip_all => "DB not populated!";
    exit;
}

my $schema = schema;


ok($schema);

my $country = schema->resultset('Country')->find($code);

ok($country);

ok($country->name, "Found " . $country->name);

foreach my $method ($country->zones({ zone => 'US lower 48'})
                    ->first->shipment_methods) {
    ok($method->name) and diag $method->name;
}

my $postal_code = '13783'; # Hancock, New York

# return methods with country and postal
foreach my $dest (Angler::Shipping::shipment_methods($schema, 'US', $postal_code)) {
    my $method = $dest->ShipmentMethod;
    ok($method->name, "name ok:" . $method->name);
    ok($method->title, "title ok:" . $method->title);
}

is Angler::Shipping::shipment_methods($schema, 'US')->count, 3, "3 methods";

# return methods with only country
foreach my $dest (Angler::Shipping::shipment_methods($schema, 'US')) {
    my $method = $dest->ShipmentMethod;
    ok($method->name, "name ok:" . $method->name);
    ok($method->title, "title ok:" . $method->title);
}

is Angler::Shipping::shipment_methods($schema, 'US')->count, 3, "3 methods";

#is_deeply
#  Angler::Shipping::shipment_methods_iterator_by_iso_country($schema, 'US'),
#  [{ title => 'Ground Residential',
#     name => 'GNDRES' },
#   { title => 'Next Day Air',
#     name => '1DA' },
#   { title => '3 Day Select',
#     name => '3DS' }
#];

my $state =  Angler::Shipping::find_state($schema, $postal_code, 'US');

ok($state->state_iso_code eq 'NY', "Testing find_state method.")
    || diag "State returned. " . $state->name;

# check if state qulifies for free shipping
my $free_ship =  Angler::Shipping::free_shipping_destination($schema, $state);


ok($free_ship eq '1', "Testing free shipping state.")
    || diag "Valid state 0|1. " . $free_ship;

# show state that doesn't qualify
$postal_code = '99504'; # Anchorage, Alaska
$state =  Angler::Shipping::find_state($schema, $postal_code, 'US');

ok($state->state_iso_code eq 'AK', "Testing find_state method.")
    || diag "State returned. " . $state->name;

# check if state qulifies for free shipping
$free_ship =  Angler::Shipping::free_shipping_destination($schema, $state);

ok($free_ship eq '0', "Testing free shipping state.")
    || diag "Valid state 0|1. " . $free_ship;
