#!perl

use strict;
use warnings;

use Angler::Shipping;

use Dancer qw/:tests/;
use Dancer::Plugin::DBIC;
use Data::Dumper;
use Test::More tests => 11;

$ENV{EASYPOST_API_KEY} = config->{easypost}->{development};

die "Missing EASYPOST_API_KEY in easypost/development config"
  unless $ENV{EASYPOST_API_KEY};

my $schema = schema;

 # shipment in the same town, 5 pounds
my @rates = Angler::Shipping::easy_post_get_rates($schema, 'US', '13783', 5); # 

ok (@rates > 4);
my $lowest = $rates[0];
ok $lowest->shipment_method->name, "Found shipment method name";
ok $lowest->price, "Found price";
ok $lowest->shipment_rates_id, "Found the id";

foreach my $rate (@rates) {
    diag $rate->shipment_method->name . " => " . $rate->price . "\n";
}

my $carriers_count = $schema->resultset('ShipmentCarrier')->search({})->count;
my $rates_count = $schema->resultset('ShipmentRate')->search({})->count;
my $methods_count = $schema->resultset('ShipmentMethod')->search({})->count;

ok ($carriers_count, "Found $carriers_count carriers");
ok ($rates_count, "Found $rates_count rates");
ok ($methods_count, "Found $methods_count methods");

# do another request
@rates = Angler::Shipping::easy_post_get_rates($schema, 'US', '13783', 5); # 

is $schema->resultset('ShipmentCarrier')->search({})->count, $carriers_count, "No new carriers";
is $schema->resultset('ShipmentRate')->search({})->count,    $rates_count,    "No new rates";
is $schema->resultset('ShipmentMethod')->search({})->count,  $methods_count,  "No new methods";

@rates = Angler::Shipping::easy_post_get_rates($schema, DE => 30853 => 5);

ok(@rates);
diag "DE rates";
foreach my $rate (@rates) {
    diag $rate->shipment_method->name . " => " . $rate->price . "\n";
}

@rates = Angler::Shipping::easy_post_get_rates($schema, US => 30853 => 5);

ok(@rates);
diag "US rates";
foreach my $rate (@rates) {
    diag $rate->shipment_method->name . " => " . $rate->price . "\n";
}

