package Angler;
use Dancer ':syntax';
use Dancer::Plugin::Nitesi;
use Dancer::Plugin::Nitesi::Routes;

our $VERSION = '0.1';

get '/' => sub {
    template 'index';
};

shop_setup_routes;

true;
