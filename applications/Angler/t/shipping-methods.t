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
    plan tests => 10;
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


foreach my $dest (Angler::Shipping::shipment_methods($schema, 'US')) {
    my $method = $dest->ShipmentMethod;
    ok($method->name, "name ok:" . $method->name);
    ok($method->title, "title ok:" . $method->title);
}

is Angler::Shipping::shipment_methods($schema, 'US')->count, 2, "2 methods";
