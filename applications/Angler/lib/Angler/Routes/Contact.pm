package Angler::Routes::Contact;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Form;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Auth::Extensible qw(
logged_in_user authenticate_user user_has_role require_role
require_login require_any_role
);

get '/contact' => sub {
    my $form = form('contact');

    template 'contact', {layout_noleft => 1,
        layout_noright => 1,
        form => $form};
};
