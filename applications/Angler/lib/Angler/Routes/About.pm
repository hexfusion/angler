package Angler::Routes::About;

use Dancer ':syntax';
use Dancer::Plugin::Form;

get '/about-us' => sub {
    my $form = form('contact');

    template 'about-us', {layout_noleft => 1,
        layout_noright => 1,
        form => $form};
};
