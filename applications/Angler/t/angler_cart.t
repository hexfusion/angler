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
            { canonical_sku => { '!=' => undef }, price => { '>' => 5 }, }
            )->rand->rows(4);
    },
    "find 4 random products to be added to cart"
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

diag explain $ac->cart->weight;
diag explain $ac->shipment_rates_id;
diag explain $ac->shipping_cost;
diag explain $ac->shipment_rates;
diag explain $ac->tax;

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
