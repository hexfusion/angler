package Angler::Routes::Checkout;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Form;
use Dancer::Plugin::Auth::Extensible;
use DateTime;
use Business::PayPal::API::ExpressCheckout;
use Angler::Forms::Checkout;

get '/checkout' => sub {
    my $form;

    $form = form('checkout');
    $form->valid(0);

    if ($form->pristine && logged_in_user) {
        debug "GET Prefill form with addresses.";
    }

    template 'cart_checkout', checkout_tokens($form);
};

post '/checkout' => sub {
    my $form;
    my $error_hash;

    $form = form('checkout');

    my $values = $form->values;

    debug "Checkout form values: ", to_dumper($values);

    if ($form->pristine) {
        if (logged_in_user) {
            debug "POST Prefill form with addresses.";

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

                my $form_values = Angler::Forms::Checkout->new(
                    address => $ship_adr,
                )->transpose;

                $form->fill($form_values);
            }
        }
    }
    else {
        # validate input
        if ($error_hash = validate_checkout($values)) {
            debug "Error hash: ", $error_hash;
        }
        elsif ($values->{use_paypal}) {
            # manually insert the paymant data
            my %payment_data = (
                                payment_mode => 'PayPal',
                                status => 'request',
                                sessions_id => session->id,
                                payment_action => 'charge',
                                amount => cart->total,
                                users_id => session('logged_in_user_id'),
                               );
            debug "Populating the payment order";
            my $payment_order = 
              shop_schema->resultset('PaymentOrder')->create(\%payment_data);
            session payment_order_id => $payment_order->payment_orders_id;
            
            # get the token and redirect
            my $pp = pp_obj();
            my %request = (
                           OrderTotal => cart->total,
                           currencyID => 'USD',
                          );

            # force the stringification of urls or SOAP will trip out
            my $cancel_url = uri_for('/checkout');
            my $return_url = uri_for('/paypal-checkout');
            $request{ReturnURL} = "$return_url";
            $request{CancelURL} = "$cancel_url";
            debug "setting the express checkout: " . to_dumper(\%request);

            # request the token
            my %response = $pp->SetExpressCheckout(%request);
            debug "response is: " . to_dumper(\%response);

            # check the response
            if ($response{Ack} eq 'Success' and $response{Token}) {


                # handle the sandbox
                my $base = 'https://www.sandbox.paypal.com';
                if (config->{paypal}->{production}) {
                    $base = 'https://www.paypal.com';
                }
                my $uri = URI->new($base . '/cgi-bin/webscr');
                $uri->query_form(cmd => '_express-checkout',
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

                generate_order($form, $tx->payment_order);

                debug("Order complete.");

                return template 'cart_receipt', checkout_tokens($form);
            }
            else {
                debug "Payment failed: ", $tx->error_message;
            }
        }
    }

    debug "Fill form with: ", $values;

    $form->fill($values);
    debug to_dumper($form);

    template 'cart_checkout', checkout_tokens($form, $error_hash);
};

get '/paypal-checkout' => sub {
    my $pp = pp_obj();

    my $token = param('token');
    my $session_token = session('paypal_token');

    # check if the token match the session one
    if ($token ne $session_token) {
        error "param token $token is not equal to the session one $session_token";
        return send_error("Bad token", 403);
    };
    my $poid = session('payment_order_id');
    unless ($poid) {
        error "Couldn't retrieve payment_order_id from session";
        return send_error("Couldn't retrieve the order id from the session!", 403);
    }

    my $po = shop_schema->resultset('PaymentOrder')->find($poid);

    my %details = $pp->GetExpressCheckoutDetails($session_token);
    debug to_dumper(\%details);
    my %payinfo = $pp->DoExpressCheckoutPayment( Token => $details{Token},
                                                 PaymentAction => 'Sale',
                                                 PayerID => $details{PayerID},
                                                 OrderTotal => cart->total );
    debug to_dumper(\%payinfo);

    # update the payment order
    # at this point we have everything and can pass the data
    my $form = form('checkout');
    generate_order($form, $po);
    debug("Order complete.");
    return template cart_receipt => checkout_tokens($form);
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

    # credit card data, only used if use_paypal is not set
    if (!$values->{use_paypal}) {
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
    }

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
    $tokens->{countries} = [ shop_country->search(
        {active => 1},
        {order_by => 'priority DESC, name'},
    )];

    # iterators for credit card expiration
    $tokens->{card_months} = card_months();
    $tokens->{card_years} = card_years();

    if ($errors) {
        $tokens->{errors} = $errors;
        $tokens->{country} = $form->values->{country};
    }
    else {
        # default values for form
        $tokens->{country} = 'US';
        $tokens->{billing_country} = 'US';
    }
    debug to_dumper($tokens->{form});
    return $tokens;
};

sub generate_order {
    my ($form, $payment_order) = @_;
    my ($ship_address, $bill_address, $ship_obj, $bill_obj);

    my $users_id = session('logged_in_user_id');

    # create delivery address from gift info form
    my $addr_form = $form;
    my $addr_values = $addr_form->values('session');

    while (my ($name, $value) = each %$addr_values) {
        if ($name =~ s/^(billing_)//) {
            $bill_address->{$name} = $value;
        }
        elsif ($name =~ /^card_/) {
            # skip credit card data
        }
        elsif ($name eq 'use_paypal') {
            # ignore this token too
        }
        else {
            $ship_address->{$name} = $value;
        }
    }

    if (! $users_id) {
        # create user
        my $user = shop_user->create({email => $ship_address->{email},
                                      username => $ship_address->{email},
                                      first_name => $ship_address->{first_name},
                                      last_name => $ship_address->{last_name},
                                      });
        $users_id = $user->id;
    }

    $ship_address->{users_id} = $users_id;
    delete $ship_address->{email};
    $ship_address->{country_iso_code} = delete $ship_address->{country};
    delete $ship_address->{state_iso_code};
    delete $ship_address->{state};
    $ship_address->{type} = 'shipping';

    $ship_address->{type} = 'billing';

    debug("Delivery address values: ", $ship_address);

    $ship_obj = shop_address->create($ship_address);

    if ($addr_values->{billing_enabled}) {
        # create billing address
        $bill_obj = shop_address->create($addr_values);
    }
    else {
        $bill_obj = $ship_obj;
    }

    # order date
    my $order_date = DateTime->now->iso8601;

    # create orderlines
    my @orderlines;
    my $position = 1;
    my $cart_items = cart->products;

    for my $item (@$cart_items) {
        debug "Items: ", $item;
        my $ol_prod = shop_product($item->{sku});
        my %orderline_product = (
            sku => $ol_prod->sku,
            order_position => $position++,
            name => $ol_prod->name,
            short_description => $ol_prod->short_description,
            description => $ol_prod->description,
            weight => $ol_prod->weight,
            quantity => $item->{quantity},
            price => $ol_prod->price,
            subtotal => $ol_prod->price * $item->{quantity},
        );

        push @orderlines, \%orderline_product;
    }

    # create transaction
    my %order_info = (users_id => $users_id,
                      billing_addresses_id => $bill_obj->id,
                      shipping_addresses_id => $ship_obj->id,
                      subtotal => cart->subtotal,
                      total_cost => cart->total,
                      order_date => $order_date,
                      order_number => $order_date,
                      Orderline => \@orderlines);

    my $order = shop_order->create(\%order_info);

    # update payment info
    $payment_order->update({orders_id => $order->id});

    cart->clear;

    return $order;
}

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

sub paypal_set_request {
    
}

1;
