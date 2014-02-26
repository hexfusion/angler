package Angler::Routes::Checkout;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Form;
use Dancer::Plugin::Auth::Extensible;
use DateTime;

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
        # input data complete, charge amount
        my $expiration = sprintf(
            "%02d/%02d",
            $values->{card_month}, $values->{card_year});

        my %payment_data = (amount => cart->total,
                     first_name => $values->{first_name},
                     last_name => $values->{last_name},
                     card_number => $values->{card_number},
                     expiration => $expiration,
                     cvc => $values->{card_cvc});

        $payment_data{action} = 'Normal Authorization';
        
        debug("Payment_data: ", \%payment_data);
        
	    my $tx = shop_charge(%payment_data);

        if ($tx->is_success) {
            debug "Payment successful: ", $tx->authorization;
        }
        else {
            debug "Payment failed: ", $tx->error_message;
        }

        
        # ...

        debug("Order complete.");
    }

    $form->fill($values);

    template 'cart_checkout', checkout_tokens($form, $error_hash);
};

sub validate_checkout {
    my ($values) = @_;

    # validate form input
    my $validator = Data::Transpose::Validator->new(requireall => 1);

    # email for guest users
    if (! logged_in_user) {
        $validator->field('email' => 'EmailValid');
    }

    # shipping address
    $validator->field('first_name' => "String");
    $validator->field('last_name' => "String");
    $validator->field('address' => "String");
    $validator->field('postal_code' => "String");
    $validator->field('city' => 'String');

    # credit card data
    $validator->field('card_number' => 'CreditCard');
    $validator->field('card_month' =>
                          {validator => 'NumericRange',
                           options => {
                               min => 1,
                               max => 12,
                           }
                       });
    $validator->field('card_cvc' =>
                          {validator => 'NumericRange',
                           options => {
                               min => 1,
                               max => 999,
                           }
                       });

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

    # iterators for credit card expiration
    $tokens->{card_months} = card_months();
    $tokens->{card_years} = card_years();

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

# iterators for credit card expiration
sub card_months {
    my @months;

    for my $i (1..12) {
        push @months, {value => $i, label => $i};
    }

    return \@months;
}

sub card_years {
    my @years;
    my $cur_year = DateTime->now->year;

    for my $i ($cur_year..$cur_year+20) {
        push @years, {value => substr($i,0,2), label => $i};
    }

    return \@years;
}

1;
