#!/usr/bin/env perl

use warnings;
use strict;

use Test::More;
use Test::Exception;

use Dancer qw/:script !pass/;
use Angler::Interchange6::Schema;
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

lives_ok(
    sub {
        $rset = shop_product->search(
            { canonical_sku => { '!=' => undef }, price => { '>' => 5 }, }
            )->rand->rows(4);
    },
    "find 4 products to be added to cart"
);

while ( my $product = $rset->next ) {
    my $sku = $product->sku;
    lives_ok( sub { $cart->add($sku) }, "add $sku to cart" );
}

cmp_ok( $cart->count, '==', 4, "4 products in cart" );

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

lives_ok( sub { $ac->update_costs }, "update_costs" );

cmp_ok( $ac->state->state_iso_code, 'eq', 'NY', 'state is NY' );

note "cart subtotal: ", $cart->subtotal;
cmp_ok(
    $ac->tax, '==',
    sprintf( "%.2f", $cart->subtotal * 0.08 ),
    "tax is: " . $ac->tax
);

diag explain $ac->shipping_methods_id;
diag explain $ac->shipping_cost;
diag explain $ac->shipping_methods;
diag explain $ac->shipping_rates;
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
