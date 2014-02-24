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
    my $error_hash;

    $form = form('checkout');

    my $values = $form->values;

    debug "Checkout form values: ", $values;

    # validate input
    if ($error_hash = validate_checkout($values)) {
        debug "Error hash: ", $error_hash;
    }
    else {
        # complete order
        # ...

        Log("Order complete.");
    }

    $form->fill($values);

    template 'cart_checkout', checkout_tokens($form, $error_hash);
};

sub validate_checkout {
    my ($values) = @_;

    # validate form input
    my $validator = Data::Transpose::Validator->new(requireall => 1);

    $validator->field('first_name' => "String");
    $validator->field('last_name' => "String");
    $validator->field('address' => "String");
    $validator->field('postal_code' => "String");
    $validator->field('city' => 'String');

    if (! $validator->transpose($values)) {
        my ($v_hash, %errors);

        $v_hash = $validator->errors_hash;

        while (my ($key, $value) = each %$v_hash) {
            $errors{$key} = $value->[0]->{value};
        }

        return \%errors;
    }
}

sub checkout_tokens {
    my ($form, $errors) = @_;
    my $tokens;

    $tokens->{form} = $form;

    # iterator for countries
    $tokens->{countries} = [shop_country->search({active => 1})];

    if ($errors) {
        $tokens->{errors} = $errors;
    }
    else {
        # default values for form
        $tokens->{country} = 'US';
        $tokens->{billing_country} = 'US';
    }

    return $tokens;
};

1;
