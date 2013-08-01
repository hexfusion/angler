package Angler;
use Dancer ':syntax';
use Dancer::Plugin::Nitesi;
use Dancer::Plugin::Nitesi::Routes;

our $VERSION = '0.1';

hook 'before_layout_render' => sub {
    my $tokens = shift;

    $tokens->{cart_count} = cart->count;
};

get '/' => sub {
    template 'home';
};

shop_setup_routes;

true;
