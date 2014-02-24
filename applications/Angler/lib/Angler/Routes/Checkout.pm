package Angler::Routes::Checkout;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Form;

get '/checkout' => sub {
    my $form;

    $form = form('checkout');
    $form->valid(0);

    template 'cart_checkout', checkout_tokens($form);
};

post '/checkout' => sub {
    my $form;

    $form = form('checkout');

    my $values = $form->values;

    debug "Checkout form values: ", $values;

    $form->fill($values);

    template 'cart_checkout', checkout_tokens($form, {});
};

sub checkout_tokens {
    my ($form, $errors) = @_;
    my $tokens ||= {};

    $tokens->{form} = $form;

    # iterator for countries
    $tokens->{countries} = [shop_country->search({active => 1})];

    if ($errors) {
    }
    else {
        # default values for form
        $tokens->{country} = 'US';
        $tokens->{billing_country} = 'US';
    }

    return $tokens;
};

1;
