#!/usr/bin/env perl

use warnings;
use strict;

use Test::More;
use Test::Exception;

BEGIN { $ENV{EMAIL_SENDER_TRANSPORT} = 'Test' }

use Dancer qw/:script !pass/;

use Angler::Schema;
use Dancer::Plugin::DBIC;
use Angler;

use Test::WWW::Mechanize::Dancer;
use WWW::Mechanize::TreeBuilder;

setting logger => "console";
setting log => "error";

my $email = Email::Sender::Simple->default_transport;

my $mech = Test::WWW::Mechanize::Dancer->new->mech;
WWW::Mechanize::TreeBuilder->meta->apply($mech);

my $schema = schema;

my ( $product, $review_count, $user );

my $review_rset = $schema->resultset('Message')->search(
    {
        'message_type.name' => 'product_review',
    },
    {
        join => 'message_type',
    }
);

lives_ok(
    sub {
        $product = $schema->resultset('Product')->search(
            {
                canonical_sku => undef,
                '_product_reviews.sku' => undef,
            },
            {
                join => '_product_reviews',
                rows => 1,
            }
        )->single;
    },
    "grab a product with no reviews"
);

isa_ok( $product, "Interchange6::Schema::Result::Product",
    "we have a product" );

note "Product sku and name: ", $product->sku, " ", $product->name;

lives_ok( sub { $review_count = $product->reviews->count },
    "record review count" );

cmp_ok( $review_count, '==', 0, "product has no reviews" );

$mech->get_ok( "/" . $product->uri, "get: /" . $product->uri );

ok( !$mech->look_down( "class", "product-reviews-summary" ),
    "No product-reviews-summary" );

ok( !$mech->look_down( class => "product-review-list" ),
    "No product-review-list" );

$mech->follow_link_ok( { class => "add-review" }, "follow add review link" );

$mech->base_is( "http://localhost/review/" . $product->sku,
    "review page URL is correct" );

$mech->submit_form_ok(
    {
        form_name => "review",
        fields    => {
            rating    => 4.5,
            nickname  => "Dave Bean",
            title     => "Brilliant",
            content   => "The best ever",
            recommend => 1,
        },
    },
    "Submit review"
);

cmp_ok( $email->delivery_count, '==', 1, "1 email sent" );
# maybe we could check email content here?
$email->clear_deliveries;

cmp_ok( $product->reviews->count,
    '==', ++$review_count, "product has $review_count reviews" );

$mech->base_is( "http://localhost/" . $product->uri, "back on product page" );

ok( !$mech->look_down( "class", "product-reviews-summary" ),
    "No product-reviews-summary" );

ok( !$mech->look_down( class => "product-review-list" ),
    "No product-review-list" );

lives_ok( sub { $product->reviews->update( { public => 1, approved => 1 } ) },
    "set all reviews to public and approved" );

# re-get page now all reviews are public
$mech->get_ok( "/" . $product->uri, "get: /" . $product->uri );

ok( $mech->look_down( "class", "product-reviews-summary" ),
    "Has product-reviews-summary" );

ok( $mech->look_down( class => "product-review-list" ),
    "Has product-review-list" );

lives_ok( sub { $product->reviews->delete }, "delete product reviews" );

$review_count = 0;

lives_ok(
    sub {
        $schema->resultset('User')->find( { nickname => "Dave Bean" } )
          ->delete;
    },
    "delete anonymous user Dave Bean"
);

# authenticated user

lives_ok(
    sub {
        $user = $schema->resultset('User')->create(
            {
                username => 'fred@example.com',
                nickname => "old nick",
                password => "taexuJ1miGha7xo"
            }
        );
    },
    'create user fred@example.com'
);

ok( $mech->look_down( class => "auth-login last" ), "we have login link");
ok( !$mech->look_down( class => "auth-logout last" ), "no logout link");

$mech->submit_form_ok(
    {
        form_id => "login-form",
        fields    => {
            username => 'fred@example.com',
            password => "taexuJ1miGha7xo"
        },
    },
    "Login"
);

ok( !$mech->look_down( class => "auth-login last" ), "no login link");
ok( $mech->look_down( class => "auth-logout last" ), "we have logout link");

$mech->get_ok( "/" . $product->uri, "get: /" . $product->uri );

ok( !$mech->look_down( "class", "product-reviews-summary" ),
    "No product-reviews-summary" );

ok( !$mech->look_down( class => "product-review-list" ),
    "No product-review-list" );

$mech->follow_link_ok( { class => "add-review" }, "follow add review link" );

$mech->base_is( "http://localhost/review/" . $product->sku,
    "review page URL is correct" );

$mech->submit_form_ok(
    {
        form_name => "review",
        fields    => {
            rating    => 4.5,
            nickname  => "Fred Bloggs",
            title     => "Brilliant",
            content   => "The best ever",
            recommend => 1,
        },
    },
    "Submit review"
);

cmp_ok( $email->delivery_count, '==', 1, "1 email sent" );
# maybe we could check email content here?
$email->clear_deliveries;

cmp_ok( $product->reviews->count,
    '==', ++$review_count, "product has $review_count reviews" );

$mech->base_is( "http://localhost/" . $product->uri, "back on product page" );

ok( !$mech->look_down( "class", "product-reviews-summary" ),
    "No product-reviews-summary" );

ok( !$mech->look_down( class => "product-review-list" ),
    "No product-review-list" );

lives_ok( sub { $product->reviews->update( { public => 1, approved => 1 } ) },
    "set all reviews to public and approved" );

# re-get page now all reviews are public
$mech->get_ok( "/" . $product->uri, "get: /" . $product->uri );

ok( $mech->look_down( "class", "product-reviews-summary" ),
    "Has product-reviews-summary" );

ok( $mech->look_down( class => "product-review-list" ),
    "Has product-review-list" );

lives_ok( sub { $product->reviews->delete }, "delete product reviews" );

lives_ok( sub { $user->delete; }, 'delete user fred@example.com' );

done_testing;
