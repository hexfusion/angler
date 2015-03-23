#!/usr/bin/env perl

use warnings;
use strict;

use Test::More;
use Test::Exception;

use Dancer qw/:script !pass/;
use Dancer::Plugin::DBIC;
use Data::Dumper::Concise;

use WWW::Mechanize::PhantomJS;
use HTML::TreeBuilder;

my $camp_number = `/home/camp/bin/camp-info --number -q`;
chomp($camp_number);
die "Cannot determine camp number" unless $camp_number =~ /^\d+$/;

my $port = 9000 + $camp_number; # web server port

my $base_url = "http://angler:Testing123\@localhost:$port/";

my ( $products, $form, $table, @nodes );

# start by visiting /cart and /checkout with shiny clean browsers

{
    my $mech = WWW::Mechanize::PhantomJS->new(
        port => 8800 + $camp_number,
        log  => 'OFF',
    );

    lives_ok { $mech->get( $base_url . "cart" ) } "get /cart";

    ok( $mech->success, "success" );

    my $tree = HTML::TreeBuilder->new;

    lives_ok { $tree->parse( $mech->content ) } "parse content";

    cmp_ok( $tree->look_down( _tag => 'span', class => 'cart-count' )->as_text,
        '==', 0, "Item count 0 is good" );

    my $session_id = $mech->driver->get_all_cookies->[0]->{value};

    lives_ok { schema->resultset('Session')->find($session_id)->delete }
    "cleanup session";
}

{
    my $mech = WWW::Mechanize::PhantomJS->new(
        port => 8800 + $camp_number,
        log  => 'OFF',
    );

    lives_ok { $mech->get( $base_url . "checkout" ) } "get /checkout";

    ok( $mech->success, "success" );

    my $tree = HTML::TreeBuilder->new;

    lives_ok { $tree->parse( $mech->content ) } "parse content";

    cmp_ok( $tree->look_down( _tag => 'span', class => 'cart-count' )->as_text,
        '==', 0, "Item count 0 is good" );

    my $session_id = $mech->driver->get_all_cookies->[0]->{value};

    lives_ok { schema->resultset('Session')->find($session_id)->delete }
    "cleanup session";
}

# get a new mech for the rest of the tests

my $mech = WWW::Mechanize::PhantomJS->new(
    port => 8800 + $camp_number,
    log  => 'OFF',
);

# get / so we can retrieve session cookie

lives_ok { $mech->get( $base_url ) } "get /";

ok( $mech->success, "success" );

my $session_id = $mech->driver->get_all_cookies->[0]->{value};

# grab 5 random canonical products

lives_ok(
    sub {
        $products = schema->resultset('Product')->search(
            {
                "me.canonical_sku" => undef,
                "variants.sku" => undef,
            },
            {
                join => 'variants',
            }
        )->rand->rows(5)->listing;
    },
    "find 5 canonical products for testing"
);

cmp_ok( $products->count, '==', 5, "Check we have 5 products" );

my $i = 0;
my $items = 0;
my @products;

# visit each product page and add random (1 to 10) qty of product to cart

while ( my $product = $products->next ) {

    $i++;
    push @products, $product;

    my $uri = $product->uri;

    lives_ok { $mech->get( $base_url . $uri ) } "get /$uri";

    ok( $mech->success, "success" );

    lives_ok { $mech->form_name('product') } "find product form";

    my $qty = int( rand(9) ) + 1;
    lives_ok { $mech->field( quantity => $qty ) } "set qty to $qty";

    $items += $qty;

    lives_ok { $mech->submit } "submit form";

    ok( $mech->success, "success" );

    like ( $mech->base, qr(/cart$), "we have the cart" );

    my $tree = HTML::TreeBuilder->new;

    lives_ok { $tree->parse( $mech->content ) } "parse content";

    cmp_ok( $tree->look_down( _tag => 'span', class => 'cart-count' )->as_text,
        '==', $items, "Item count $items is good" );

    lives_ok { $form = $tree->look_down( _tag => 'form', id => 'form-cart' ) }
    "find cart form";

    lives_ok {
        $table =
          $form->look_down( _tag => 'table', id => 'shopping-cart-table' )
    }
    "find shopping cart table";

    lives_ok {
        @nodes = $table->look_down( _tag => 'tr', class => qr/cartitem/ )
    }
    "find cart items in main cart table";

    cmp_ok( @nodes, '==', $i, "$i cart items in main cart display" );

    foreach my $j ( 0..$#nodes ) {
        cmp_ok(
            $nodes[$j]->look_down( _tag => 'h2', class => 'product-name' )
              ->as_trimmed_text,
            'eq', $products[$j]->name, "product name is: " . $products[$j]->name
        );
    }

    $mech->render_content(
        format => 'jpg',
        filename => 'content.jpg',
        width => 2048,
        height => 2048,
    );
}

# remove an item from the cart

lives_ok { $mech->get( $base_url . 'cart' ) } "get /cart";

ok( $mech->success, "success" );

my $tree = HTML::TreeBuilder->new;

lives_ok { $tree->parse( $mech->content ) } "parse content";

lives_ok { $form = $tree->look_down( _tag => 'form', id => 'form-cart' ) }
"find cart form";

lives_ok {
    $table = $form->look_down( _tag => 'table', id => 'shopping-cart-table' )
}
"find shopping cart table";

lives_ok {
    @nodes = $table->look_down( _tag => 'tr', class => qr/cartitem/ )
}
"find cart items in main cart table";

cmp_ok( @nodes, '==', 5, "5 products in cart" );

my $sku = $products[2]->sku;

# reduce our expected qty via data in DB
$items -=
  schema->resultset('Cart')->search( { sessions_id => $session_id } )
  ->search_related( 'cart_products', { sku => $sku } )->rows(1)
  ->single->get_column('quantity');

$mech->confirm(
    "Are you sure you would like to remove this item from the shopping cart?");

lives_ok { $mech->get( $base_url . 'cart?remove=' . $sku ) }
"get /cart?remove=$sku";

ok( $mech->success, "success" );

like ( $mech->base, qr(/cart$), "we have the cart" );

$tree = HTML::TreeBuilder->new;

lives_ok { $tree->parse( $mech->content ) } "parse content";

cmp_ok( $tree->look_down( _tag => 'span', class => 'cart-count' )->as_text,
    '==', $items, "Item count $items is good" );

lives_ok { $form = $tree->look_down( _tag => 'form', id => 'form-cart' ) }
"find cart form";

lives_ok {
    $table = $form->look_down( _tag => 'table', id => 'shopping-cart-table' )
}
"find shopping cart table";

lives_ok {
    @nodes = $table->look_down( _tag => 'tr', class => qr/cartitem/ )
}
"find cart items in main cart table";

cmp_ok( @nodes, '==', 4, "4 products in cart" );

lives_ok { $mech->form_name( "shipping-quote" ) } "set form to shipping-quote";

lives_ok {
    $mech->set_fields(
        country     => 'US',
        postal_code => '12345',
      )
}
"set fields";

lives_ok { $mech->click_button( name => "get_quote" ) } "click get_quote";

ok( $mech->success, "success" );

# cleanup
#lives_ok { schema->resultset('Session')->find($session_id)->delete }
#"cleanup session";

done_testing;
