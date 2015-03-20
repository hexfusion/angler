#!/usr/bin/env perl

use warnings;
use strict;

use Test::More;
use Test::Exception;

use Dancer qw/:script !pass/;
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::DBIC;

use Angler::Cart;

my ( $ac, %args, $rset, $product );

my $schema = shop_schema;

set session_options => {schema => $schema};
set logger => 'console';
set log => 'debug';

my $environment = setting 'environment';
die unless $environment eq 'development';
$ENV{EASYPOST_API_KEY} = config->{easypost}->{$environment};

my $cart = shop_cart;
my $dbcart;

lives_ok(
    sub {
        $rset = shop_product->search(
            { canonical_sku => { '!=' => undef }, price => { '>' => 25 }, }
            )->rand->rows(4);
    },
    "find 4 random products to be added to cart with total value > 100"
);

while ( my $product = $rset->next ) {
    my $sku = $product->sku;
    lives_ok( sub { $cart->add($sku) }, "add $sku to cart" );
}

cmp_ok( $cart->count, '==', 4, "4 products in cart" );
cmp_ok( $cart->weight, '>', 0, "cart weight > 0" );

lives_ok( sub { $dbcart = schema->resultset('Cart')->find( $cart->id ) },
    "look for cart in DB" );
isa_ok( $dbcart, "Interchange6::Schema::Result::Cart", "cart" );
cmp_ok( $dbcart->cart_products->count, '==', 4, "4 products in DB cart" );

throws_ok(
    sub { $ac = Angler::Cart->new() },
    qr/Missing required arguments/,
    "new Angler::Cart with no args"
);

# /cart shipping and tax estimate

%args = (
    cart => $cart,
    schema => $schema,
    country => 'US',
    postal_code => '10001',
);

lives_ok(
    sub { $ac = Angler::Cart->new(%args) },
    "new Angler::Cart for /cart shipping+tax quote"
);

cmp_ok( $ac->state->state_iso_code, 'eq', 'NY', 'state is NY' );

note "cart subtotal: ", $cart->subtotal;
cmp_ok(
    $ac->tax, '==',
    sprintf( "%.2f", $cart->subtotal * 0.08 ),
    "tax is: " . $ac->tax
);

$args{postal_code} = '40401';

lives_ok(
    sub { $ac = Angler::Cart->new(%args) },
    "new Angler::Cart for /cart shipping+tax quote"
);

cmp_ok( $ac->state->state_iso_code, 'eq', 'KY', 'state is KY' );
cmp_ok( $ac->tax, '==', 0, "no tax" );

$args{billing_country} = 'US';
$args{billing_postal_code} = '10001';

lives_ok(
    sub { $ac = Angler::Cart->new(%args) },
    "get cart will billing in NY and shipping KY"
);
cmp_ok(
    $ac->tax, '==',
    sprintf( "%.2f", $cart->subtotal * 0.08 ),
    "tax is: " . $ac->tax
);

ok(!defined $ac->shipment_rates_id, "No shipment_rates_id yet");
cmp_ok( $ac->shipping_cost, '==', 0, "shipping_cost is zero" );

my $rates = $ac->shipment_rates;
ok( ref($rates) eq 'ARRAY' && @$rates > 1, "More than one rate found" );

my $rate = shift @$rates;
cmp_ok( $rate->{rate}, '==', 0, "first rate found is free shipping" );

my $i = 0;
foreach my $rate ( @$rates ) {
    cmp_ok( $rate->{rate}, '>=', $i, "next rate is higher: " . $rate->{rate} );
    $i = $rate->{rate};
}

$rates = $ac->shipment_rates;
my $count = @$rates;
lives_ok( sub { $rate = $rates->[ int( rand(@$rates -1) ) + 1 ] },
    "pick a rate at random" );

lives_ok( sub { $ac->set_shipment_rates_id( $rate->{carrier_service} ) },
    "set_shipment_rates_id in angler cart" );

cmp_ok( $ac->shipping_cost, '==', $rate->{rate},
    "angler cart shipping_cost is: " . $ac->shipping_cost );

lives_ok( sub { $cart->clear }, "clear cart" );

cmp_ok( $ac->cart->count, '==', 0, "no items in cart" );

lives_ok(
    sub {
        $rset = shop_product->search(
            { canonical_sku => { '!=' => undef }, price => { '<' => 25 }, }
            )->rand->rows(4);
    },
    "find 4 random products to be added to cart with total value < 100"
);

while ( my $product = $rset->next ) {
    my $sku = $product->sku;
    lives_ok( sub { $cart->add($sku) }, "add $sku to cart" );
}

cmp_ok( $cart->count, '==', 4, "4 products in cart" );
cmp_ok( $cart->weight, '>', 0, "cart weight > 0" );

$rates = $ac->shipment_rates;
ok( ref($rates) eq 'ARRAY' && @$rates > 0, "At least one rate found" );

$rate = shift @$rates;
cmp_ok( $rate->{rate}, '>', 0, "first rate found is NOT free shipping" );

# cleanup

lives_ok(
    sub {
        $schema->resultset('Cart')->find( { sessions_id => session->id } )
          ->delete;
    },
    "delete cart from db"
);

lives_ok(
    sub {
        $schema->resultset('Session')->find( { sessions_id => session->id } )
          ->delete;
    },
    "delete session from db"
);

done_testing;
