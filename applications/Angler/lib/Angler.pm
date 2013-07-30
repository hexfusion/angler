package Angler;
use Dancer ':syntax';
use Dancer::Plugin::Nitesi;
use Dancer::Plugin::Nitesi::Routes;

our $VERSION = '0.1';

get '/' => sub {
    template 'home';
};

shop_setup_routes;

true;
