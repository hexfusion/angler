package Angler::Routes::Contact;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Form;

get '/contact' => sub {
    my $form = form('contact');

    template 'contact/content', {form => $form};
};
