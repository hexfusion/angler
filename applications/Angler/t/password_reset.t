#!/usr/bin/env perl

use warnings;
use strict;

use Test::More;
use Test::Exception;

BEGIN { $ENV{EMAIL_SENDER_TRANSPORT} = 'Test' }

use Dancer qw/:script !pass/;

use Angler::Interchange6::Schema;
use Dancer::Plugin::DBIC;
use Angler;

use Test::WWW::Mechanize::Dancer;
use WWW::Mechanize::TreeBuilder;
use HTML::TreeBuilder;

setting logger => "console";
setting log => "error";

my $email = Email::Sender::Simple->default_transport;

my $mech = Test::WWW::Mechanize::Dancer->new->mech;
WWW::Mechanize::TreeBuilder->meta->apply($mech);

my $tree = HTML::TreeBuilder->new;

my $schema = schema;

my ( $message, $user );

lives_ok(
    sub {
        $user = $schema->resultset('User')->find_or_create(
            {
                username => 'fred@example.com',
                nickname => "old nick",
                password => "taexuJ1miGha7xo",
                first_name => "Fred",
            }
        );
    },
    'create user fred@example.com'
);

$mech->get_ok( "/" );

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

$mech->follow_link_ok( { url => '/logout' }, "Logout" );

ok( $mech->look_down( class => "auth-login last" ), "we have login link");
ok( !$mech->look_down( class => "auth-logout last" ), "no logout link");

$mech->follow_link_ok( { url => '/resetpassword' },
    "follow /resetpassword link" );

$mech->base_like( qr(/resetpassword$), "URI is good" );

$mech->submit_form_ok(
    {
        form_name => "resetrequest",
        fields => {
            email => 'nosuchuser@example.com'
        }
    },
    "submit reset request for unknown user"
);

$mech->base_like( qr(/resetpassword/sent$), "URI is good" );

cmp_ok( $email->delivery_count, '==', 0, "no email sent" );

$mech->follow_link_ok( { url => '/resetpassword' },
    "follow /resetpassword link" );

$mech->submit_form_ok(
    {
        form_name => "resetrequest",
        fields => {
            email => 'fred@example.com'
        }
    },
    "submit reset request for good user"
);

$mech->base_like( qr(/resetpassword/sent$), "URI is good" );

cmp_ok( $email->delivery_count, '==', 1, "1 email sent" );

lives_ok( sub { $message = $email->shift_deliveries->{email}->get_body },
    "get email body for checking" );

lives_ok( sub { $tree->parse($message) }, "parse email body with TreeBuilder" );

my $link;
lives_ok(
    sub {
        $link = $tree->look_down( _tag => 'a', title => "password reset link" )
          ->attr('href');
    },
    "Find reset link in email"
);

like( $link, qr(/resetpassword/\w+), "link looks good" );

$mech->get_ok( $link, "Follow the link" );

$mech->base_is( $link, "we seem to be in the right place" );

$mech->submit_form_ok(
    {
        form_name => "resetconfirm",
        fields => {
            password => '1234567',
            confirm_password => '1234567',
        }
    },
    "submit short password"
);

$mech->base_is( $link, "still on reset page" );

$mech->text_contains( "it should be long at least 8 characters" );

$mech->submit_form_ok(
    {
        form_name => "resetconfirm",
        fields => {
            password => '123456789',
            confirm_password => '123456789',
        }
    },
    "submit password 123456789"
);

$mech->base_is( $link, "still on reset page" );

$mech->text_contains( "No letters in the password" );

$mech->submit_form_ok(
    {
        form_name => "resetconfirm",
        fields => {
            password => 'laiReoph5n',
            confirm_password => '123456789',
        }
    },
    "submit passwords don't match"
);

$mech->base_is( $link, "still on reset page" );

$mech->text_contains( "Passwords differ" );

$mech->submit_form_ok(
    {
        form_name => "resetconfirm",
        fields => {
            password => 'laiReoph5n',
            confirm_password => 'laiReoph5n',
        }
    },
    "submit good matching password"
);

$mech->base_is( 'http://localhost/', "back on home page" );

$mech->text_like( qr/Your password has been changed.+Fred/,
    "got welcome back message" );

#cleanup
lives_ok( sub { $user->delete; }, 'delete user fred@example.com' );

done_testing;
