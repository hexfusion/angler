package Angler::Routes::About;

use Dancer ':syntax';
use Dancer::Plugin::Form;

get '/about' => sub {
    my $form = form('contact');

    template 'about/us/content', {form => $form};
};
