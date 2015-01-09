package Angler::Routes::Checkout;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Form;
use Dancer::Plugin::Auth::Extensible;
use Dancer::Plugin::Email;

use Angler::Cart;
use Angler::Forms::Checkout;
use File::Spec;

use DateTime;
use Business::PayPal::API::ExpressCheckout;

use Angler::Cart;
use Angler::Forms::Checkout;
use File::Spec;

get '/checkout' => sub {
    my $cart_form;

    my $form = form('checkout');
    $form->valid(0);

    if ($form->pristine && logged_in_user) {
        debug "GET Prefill form with addresses.";
    }

    debug "Get form checkout " , $form;

    template 'checkout/content', checkout_tokens($form);
};

post '/checkout' => sub {
    my $cart_form;
    my $form = form('checkout');
    my $values = $form->values;

    debug "checkout form values ", $values;
    debug "Params: ", params;

    if (param('shipping_method')) {
        $cart_form = form('cart')->values;
    }
    else {
        $cart_form = form('cart')->values('session');
    }

    # take values from cart_form or use default
    $values->{postal_code}     ||= $cart_form->{postal_code};
    $values->{country}         ||= $cart_form->{country} || 'US';
    $values->{billing_country} ||= $cart_form->{billing_country} || 'US';
    $values->{shipping_method} ||= $cart_form->{shipping_method};

    # if user logs in on checkout page lets make sure they get back
    if (param('login')) {
        forward '/login', {return_url => 'checkout'}, {method => 'get'},
    }

    if ($form->pristine and logged_in_user) {
        # search for exisitng addresses
        my $form_values = user_address();
        if ($form_values) {
            $form->fill($form_values);
        }
    }

    # before we do any payment stuff lets make sure we have what we need
    my $error_hash = validate_checkout($values);

    debug "Fill form with: ", $values;
    $form->fill($values);

    # if we have errors back to the form we go
    if (!$error_hash) {
        debug "Error Hash Return", $error_hash;
        return template 'checkout/content', checkout_tokens($form, $error_hash, $values);
    }

    # paypal payment    
    if ($values->{payment_method} and $values->{payment_method} eq 'paypal') {
        # manually insert the paymant data
        my %payment_data = (
                            payment_mode => 'PayPal',
                            payment_action => 'charge',
                            status => 'request',
                            sessions_id => session->id,
                            amount => cart->total,
                            users_id => session('logged_in_user_id'),
                            );

        debug "Populating the payment order";
        my $payment_order = 
          shop_schema->resultset('PaymentOrder')->create(\%payment_data);

        session payment_order_id => $payment_order->payment_orders_id;

        my %response = paypal_request($values);
    
        # check the response
        if ($response{Ack} eq 'Success' and $response{Token}) {

            # handle the sandbox
            my $base = 'https://www.sandbox.paypal.com';

            if (config->{paypal}->{production}) {
                $base = 'https://www.paypal.com';
            }

            my $uri = URI->new($base . '/cgi-bin/webscr');
            $uri->query_form(cmd => '_express-checkout',
                                 useraction => 'commit',
                                 token => $response{Token});
            debug "Redirecting to " . $uri->as_string;

            # store the token and redirect
            session (paypal_token => $response{Token});
            return redirect $uri->as_string;
        }
        else {
            $error_hash = { paypal => "Couldn't perform a request to paypal" };
        }
    }
    elsif ($values->{payment_method} and $values->{payment_method} eq 'creditcard') {
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

            my $tokens = checkout_tokens($form, {}, $values);
            my $order = generate_order($tokens, $tx->payment_order);

            debug("Order complete.");

            $tokens->{order} = $order;

            return template 'cart_receipt', $tokens;
        }
        else {
            debug "Payment failed: ", $tx->error_message;
        }
    }

    template 'checkout/content', checkout_tokens($form, $error_hash, $values);
};

=head2 checkout_tokens

populate all checkout iterators for flute returns tokens.

=cut

sub checkout_tokens {
    my ($form, $errors, $values) = @_;
    my $tokens;

    $tokens->{form} = $form;
    $tokens->{cart} = cart;

    debug "checkout_tokens form ", $form;
    debug "checkout tokens cart ", cart;

    # update cart
    my $angler_cart = Angler::Cart->new(schema => shop_schema,
                                        cart => $tokens->{cart},
                                        postal_code => $values->{postal_code},
                                        country => $values->{country},
                                        shipping_methods_id => $values->{shipping_method},
                                        user_id => session('logged_in_users_id'),);

    $values ||= {};

    $angler_cart->update_costs($values);

    $tokens->{cart_tax} = $angler_cart->tax;
    $tokens->{cart_shipping} = $angler_cart->shipping_cost;

    # countries
    $tokens->{countries} = [ shop_country->search(
        {active => 1},
        {order_by => { -asc => [qw/priority name/]}},
    )];

    my @payment_errors;
    # report the paypal failures too
    if (my $pp_exception = session('paypal_exception')) {
        push @payment_errors, $pp_exception;
        session paypal_exception => undef;
    }

    if ($errors) {
        my %map = (
                   card_cvc => 'CVC',
                   card_month => 'CC expiration',
                   card_number => 'Credit card number',
                  );
        foreach my $cc_error (keys %map) {
            if (exists $errors->{$cc_error}) {
                push @payment_errors, $map{$cc_error} . ': ' . $errors->{$cc_error};
            }
        }
    }

    if (@payment_errors) {
        $tokens->{paypal_exception} = join(', ', @payment_errors);
    }


    if ($errors) {
        $tokens->{errors} = $errors;
        $tokens->{country} = $tokens->{form}->values->{country};
    }
    else {
        # default values for form
        $tokens->{country} = 'US';
        $tokens->{billing_country} = 'US';
    }

    # iterator for states
    $tokens->{states} = states($tokens->{country});
    #$tokens->{state} = $angler_cart->state;

    # credit card expiration
    $tokens->{card_months} = card_months();
    $tokens->{card_years} = card_years();

    # form
    $tokens->{form} = $form;

    # cart
    #$tokens->{cart} = cart;

    debug "tokens form ",  $form , " tokens errors ", $errors , " tokens values " , $values;
    #debug "tokens cart ", $tokens->{cart};

    return $tokens;

};

=head2 states

input country_iso_code and return a state object

=cut

sub states {
    my ($country) = @_;
    my $states;

    $states = [shop_schema->resultset('State')->search(
        {country_iso_code => $country,
         active => 1,
     },
        {order_by => 'name'},
    )];

    return $states;
};

=head2 card_months

iterators for credit card expiration months

=cut

sub card_months {
    my @months;

    for my $i (1..12) {
        push @months, {value => $i, label => $i};
    }

    return \@months;
}

=head2 card_years

iterators for credit card years

=cut

sub card_years {
    my @years;
    my $cur_year = DateTime->now->year;

    for my $i ($cur_year..$cur_year+20) {
        push @years, {value => substr($i,0,2), label => $i};
    }

    return \@years;
}

=head2 validate_checkout

input form values and pass data through Data::Transpose::Validator
return errors.

=cut

sub validate_checkout {
    my ($values) = @_;

    # validate form input
    my $validator = Data::Transpose::Validator->new(requireall => 1);

    # email for guest users
    if (! logged_in_user) {
        $validator->field('email' => 'EmailValid');
    }

    # billing address
    $validator->field('billing_first_name' => "String");
    $validator->field('billing_last_name' => "String");
    $validator->field('billing_address' => "String");
    $validator->field('billing_postal_code' => "String");
    $validator->field('billing_city' => 'String');
    $validator->field('billing_phone' => 'String');

    # payment method
    $validator->field('payment_method' => 'String');

    # credit card data, only used payment_method is not paypal
    if ($values->{payment_method} and $values->{payment_method} ne 'paypal') {
        $validator->field('card_name' => 'String');
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
                               max => 9999,
                           }
                       });
    }

    if (! $validator->transpose($values)) {
        my ($v_hash, %errors);

        $v_hash = $validator->errors_hash;

        while (my ($key, $value) = each %$v_hash) {
            $errors{$key} = $value->[0]->{value};
            # flag the field with error using has-error class
            $errors{$key . '_input' } = 'has-error';
        }
        return \%errors;
    }
}

=head2 user_address

Input user_id and a get addresses
TODO this should return a full list not just ->single

=cut

sub user_address {
    my $form_values;
    debug "POST Prefill form with addresses.";

    # find existing billing address for user
    #TODO this should be a search and a dropdown to select if multiple
    my $bill_adr = shop_address->search(
        {
            users_id => session('logged_in_user_id'),
            type => 'billing',
        },
        {
            order_by => 'last_modified DESC',
            rows => 1,
        },
    )->single;

    if ($bill_adr) {
        debug "Billing address found: ", $bill_adr->id;

        $form_values = Angler::Forms::Checkout->new(
            billing_address => $bill_adr,
        )->transpose;
    }

    #TODO this should be a search and a dropdown to select if multiple
    my $ship_adr = shop_address->search(
        {
            users_id => session('logged_in_user_id'),
            type => 'shipping',
        },
        {
            order_by => 'last_modified DESC',
            rows => 1,
        },
    )->single;

    if ($ship_adr) {
        debug "Shipping address found: ", $ship_adr->id;

        $form_values->{shipping_enabled} = 1;
        $form_values->{shipping_id} = $ship_adr->id;
    }
        debug "Filling checkout form with: ", $form_values;
  
    return $form_values
};

=head2 paypal_request

creates inital paypal request and returns response

=cut

sub paypal_request {
    my ($values) = @_;

    # get the token and redirect
    my $pp = pp_obj();
    my %request = (
                    OrderTotal => sprintf('%0.2f', cart->total),
                    currencyID => 'USD',
                    BuyerEmail => $values->{email},
                    OrderDescription => "Angler",
                    PaymentAction => 'Sale',
                   );

    # force the stringification of urls or SOAP will trip out
    my $cancel_url = uri_for('/paypal-cancel');
    my $return_url = uri_for('/paypal-checkout');
    $request{ReturnURL} = "$return_url";
    $request{CancelURL} = "$cancel_url";

    debug "paypal checkout request: " . to_dumper(\%request);

    # request the token
    my %response = $pp->SetExpressCheckout(%request);
    debug "paypal response is: " . to_dumper(\%response);

    return %response;

};

=head2 pp_obj

extract the paypal config data and return pp object

=cut

sub pp_obj {
    my %credentials = (
                       Username => config->{paypal}->{id} || die,
                       Password => config->{paypal}->{password} || die,
                       Signature => config->{paypal}->{signature} || die,
                       sandbox => 1,
                      );
    if (config->{paypal}->{production}) {
        $credentials{sandbox} = 0;
    }
    debug "creating Business::PayPal::API::ExpressCheckout with ".
      to_dumper(\%credentials);
    my $pp = Business::PayPal::API::ExpressCheckout->new(%credentials);
    return $pp;
}

=head2 report_pp_error

if we get an error from paypal lets make sure to save it.

=cut

sub report_pp_error {
    my $error = shift;
    session paypal_exception => $error;
    return redirect '/checkout';
}

=head2 report_pp_payment_error

paypal errors

=cut

sub report_pp_payment_error {
    my ($po, $response) = @_;
    my %details = %$response;
    debug to_dumper($response);
    
    # report the errors
    my @errors;
    my @error_codes;
    if (my $errs = $details{Errors}) {
        foreach my $err (@$errs) {
            push @errors, $err->{LongMessage};
            push @error_codes, $err->{ErrorCode};
        }
    } else {
        push @errors, "Transaction failed!";
    }

    # update the PaymentOrder
    $po->payment_error_message(join(' ', @errors));
    $po->payment_error_code(join(',', @error_codes));
    $po->status("failure");
    $po->update;
    return report_pp_error(join("<br>", @errors));
}

1;
