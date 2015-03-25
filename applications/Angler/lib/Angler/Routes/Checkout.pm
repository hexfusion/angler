package Angler::Routes::Checkout;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Form;
use Dancer::Plugin::Auth::Extensible;
use Dancer::Plugin::Ajax;
use Dancer::Plugin::Email;

use Angler::Plugin::History;
use Angler::Cart;
use Angler::Data::Tokens;
use Angler::Forms::Checkout;
use Angler::Shipping;

use File::Spec;
use DateTime;
use Business::PayPal::API::ExpressCheckout;

=head1 ROUTES

=head2 get/post /checkout

=cut

get '/checkout' => sub {
    my $form = form('checkout');
    debug "one";
    $form = set_form_values($form);
    debug "two";
    return template 'checkout/content', checkout_tokens($form);
};

post '/checkout' => sub {

    # if user logs in on checkout page lets make sure they get back
    # FIXME: login modal should post to /login with return_url set
    if (param('login')) {
        return forward '/login', {return_url => '/checkout'}, {method => 'get'},
    }

    my ($user, $order, $error_hash);

    my $form   = form('checkout');
    my $values = $form->{values};

    debug "validate checkout values", $values;

    # before we do anything lets make sure we have what we need
    $error_hash = validate_checkout($values);

    # if we have errors back to the form we go
    if ($error_hash) {
        debug "oops you have form errors return to checkout :", $error_hash;
        return template 'checkout/content',
          checkout_tokens( $form, $error_hash );
    }

    debug "create user";

    # form is clean lets create the order/user now
    $user = shop_user->find_or_create(
        {
            email      => $values->{email},
            username   => $values->{email},
            first_name => $values->{first_name},
            last_name  => $values->{last_name},
        },
        {
            key => 'username'
        }
    );

    # add user_id to session for DPIC6
    session( logged_in_user_id => $user->id );

    debug "generating order";

    # generate order now get payment later
    $order = generate_order( $form, $user );

    # payment
    # paypal

    if ( $values->{payment_method} and $values->{payment_method} eq 'paypal' ) {

        # manually insert the paymant data
        my %payment_data = (
            payment_mode   => 'PayPal',
            payment_action => 'charge',
            status         => 'request',
            sessions_id    => session->id,
            amount         => cart->total,
            users_id       => $user->id,
        );

        debug "Populating the payment order";
        my $payment_order =
          shop_schema->resultset('PaymentOrder')->create( \%payment_data );

        session payment_order_id => $payment_order->payment_orders_id;

        my %response = paypal_request($values);

        # check the response
        if ( $response{Ack} eq 'Success' and $response{Token} ) {

            # handle the sandbox
            my $base = 'https://www.sandbox.paypal.com';

            if ( config->{paypal}->{production} ) {
                $base = 'https://www.paypal.com';
            }

            my $uri = URI->new( $base . '/cgi-bin/webscr' );
            $uri->query_form(
                cmd        => '_express-checkout',
                useraction => 'commit',
                token      => $response{Token}
            );
            debug "Redirecting to " . $uri->as_string;

            # store the order_id to retrieve later
            session( paypal_order_id => $order->id );

            # store the token and redirect
            session( paypal_token => $response{Token} );
            return redirect $uri->as_string;
        }
        else {
            $error_hash = { paypal => "Couldn't perform a request to paypal" };
        }
    }
    elsif ( $values->{payment_method}
        and $values->{payment_method} eq 'creditcard' )
    {
        # input data complete, charge amount
        my $expiration =
          sprintf( "%02d/%02d", $values->{card_month}, $values->{card_year} );

        my %payment_data = (
            amount      => cart->total,
            first_name  => $values->{first_name},
            last_name   => $values->{last_name},
            card_number => $values->{card_number},
            expiration  => $expiration,
            cvc         => $values->{card_cvc}
        );

        $payment_data{action} = 'Normal Authorization';

        debug( "Payment_data: ", \%payment_data );

        my $tx = shop_charge(%payment_data);

        if ( $tx->is_success ) {
            debug "Payment successful: ", $tx->authorization;

            # append payment tokens
            my $tokens = generate_payment( $order, $tx->payment_order );

            finalize_order( $tokens, $form );
            debug("Order complete.");
            session logged_in_user_id => undef;
            return template 'checkout/receipt/content', $tokens;
        }
        else {
            debug "Payment failed: ", $tx->error_message;
        }
    }
    template 'checkout/content', checkout_tokens($form, $error_hash);
};

ajax '/checkout/update_tax' => sub {
    my %params = params;
    my %return;

    forward 400 unless $params{shipping_country};

    my $tax_rate = 0;

    if ( $params{shipping_country} eq 'US' && $params{shipping_state} ) {
        my $state = shop_state( $params{shipping_state} );
        if ( $state && $state->state_iso_code eq 'NY' ) {
            $tax_rate = 0.08;
        }
    }

    if (   $params{billing_country}
        && $params{billing_country} eq 'US'
        && $params{billing_state} )
    {
        my $state = shop_state( $params{billing_state} );
        if ( $state && $state->state_iso_code eq 'NY' ) {
            $tax_rate = 0.08;
        }
    }

    my $cart = shop_cart;
    if ( $tax_rate ) {
        $cart->apply_cost( amount => $tax_rate, name => 'tax', relative => 1 );
        $return{tax} = $cart->cost('tax');
    }
    else {
        $return{tax} = '0.00';
    }

    $return{subtotal} = $cart->subtotal;
    $return{total}    = $cart->total;
    $return{type}     = 'success';

    content_type('application/json');
    to_json( \%return );
};

get '/paypal-checkout' => sub {
    my $pp = pp_obj();

    my $token = param('token');
    my $session_token = session('paypal_token');
    my $order_id = session('paypal_order_id');
    my $poid = session('payment_order_id');

    my $order = shop_order($order_id);

    ## sanity check. this should not happen
    # check if the token match the session one
    if ($token ne $session_token) {
        return report_pp_error("Token mismatch, transaction aborted");
    }

    # check the payment order in the session
    elsif (!$poid) {
        return report_pp_error("Missing order id, transaction aborted");
    }

    # check that the order still exists if not something bad happened
    elsif (!$order) {
        return report_pp_error("Missing Order, transaction aborted");
    }

    my $po = shop_schema->resultset('PaymentOrder')->find($poid);

    unless ($po) {
        return report_pp_error("Missing order id, transaction aborted");
    }

    my %details = $pp->GetExpressCheckoutDetails($session_token);
    debug to_dumper(\%details);

    unless ($details{Ack}
            and $details{Ack} eq 'Success'
            and $details{Token}) {
        return report_pp_payment_error($po, \%details);
    }

    my %payinfo = $pp->DoExpressCheckoutPayment( Token => $details{Token},
                                                 PaymentAction => 'Sale',
                                                 PayerID => $details{PayerID},
                                                 OrderTotal => cart->total );

    if ($payinfo{Ack} eq 'Success') {
        # update the payment order
        debug to_dumper(\%payinfo);
        $po->auth_code($payinfo{TransactionID});
        $po->status("success");
        $po->payment_sessions_id($payinfo{Token});

        # this should not happen
        if ($payinfo{GrossAmount} < cart->total) {
            warning "$payinfo{GrossAmount} doesn't match the cart total!";
            $po->payment_error_message("Gross amount is $payinfo{GrossAmount}");
        }
        $po->update;
    }
    else {
        return report_pp_payment_error($po, \%payinfo);
    }

    # at this point we have everything and can pass the data
    my $form = form('checkout');
    debug("Generating payment");
    my $tokens = generate_payment($order, $po);
     debug("Finalizing order");
    finalize_order($tokens, $form);
    debug("Order complete.");

    # clear the session from stale data
    session paypal_order_id => undef;
    session paypal_token => undef;
    session payment_order_id => undef;
    session logged_in_user_id => undef;
    return template 'checkout/receipt/content', $tokens;
};

=head2 checkout_tokens

tokens used to display form cart and errors in the checkout view

=cut

sub checkout_tokens {
    my ($form, $errors) = @_;
    my $values = $form->{values};

    # set tokens {billing|shipping}_states, countries, card_months, card_years
    my $tokens = Angler::Data::Tokens->new(
        billing_country => $values->{billing_country},
        country         => $values->{country}, # shipping country
        schema          => shop_schema,
        form            => $form
    )->checkout;

    $tokens->{form} = $form;
    $tokens->{cart} = cart;

    # update cart
    my $angler_cart = Angler::Cart->new(
        schema              => shop_schema,
        cart                => $tokens->{cart},
        postal_code         => $values->{postal_code},
        country             => $values->{country},
        billing_postal_code => $values->{billing_postal_code},
        billing_country     => $values->{billing_country},
        shipment_rates_id   => $values->{shipping_rate},
        use_easypost        => 1,
        rates_display_type  => 'select',
    );

    my $rates = $angler_cart->shipment_rates;

    if ( $rates && ref($rates) eq 'ARRAY' && @$rates ) {
        $angler_cart->set_shipment_rates_id( $rates->[0]->{rate} )
          unless $angler_cart->shipment_rates_id;
    }

    $tokens->{cart_tax}       = $angler_cart->tax;
    $tokens->{cart_shipping}  = $angler_cart->shipping_cost;
    $tokens->{shipping_rates} = $angler_cart->shipment_rates;

    if (   $values->{country}
        && $values->{country} eq 'US'
        && $values->{postal_code}
        && !$values->{state} )
    {
        $values->{state} = $angler_cart->state->states_id;
    }

    if (   $values->{billing_country}
        && $values->{billing_country} eq 'US'
        && $values->{billing_postal_code}
        && !$values->{billing_state} )
    {
        $values->{billing_state} = $angler_cart->billing_state->states_id;
    }

    $form->fill( $values );

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

    $tokens->{errors} = $errors;

    return $tokens;
};

=head2 order_address_tokens

method to generate billing and shipping address tokens.
used during order creation.

=cut

sub order_address_tokens {
    my ($values, $tokens, $user) = @_;

    debug "order_address_tokens values ", $values;

    # create order address tokens
    $tokens->{shipping_address} = (
        {
            users_id => $user->id,
            type => 'shipping',
            first_name => $values->{first_name},
            last_name => $values->{last_name},
            company => $values->{company},
            address => $values->{address},
            address_2 => $values->{address_2},
            postal_code => $values->{postal_code},
            city => $values->{city},
            states_id => $values->{state},
            country_iso_code => $values->{country},
            phone => $values->{phone}
        }
    );

    if ($values->{billing_enabled} and $values->{billing_enabled} == 1) {
        # create billing address
        $tokens->{billing_address} = (
            {
                users_id => $user->id,
                type => 'billing',
                first_name => $values->{billing_first_name},
                last_name => $values->{billing_last_name},
                company => $values->{billing_company},
                address => $values->{billing_address},
                address_2 => $values->{billing_address_2},
                postal_code => $values->{billing_postal_code},
                city => $values->{billing_city},
                states_id => $values->{billing_state},
                country_iso_code => $values->{billing_country},
                phone => $values->{billing_phone}
            }
        );
    }
    else {
        # billing and shipping are the same
        $tokens->{billing_address} = $tokens->{shipping_address}
    }

    return $tokens;

};

=head2 validate_checkout

input form values and pass data through Data::Transpose::Validator
return errors.

=cut

sub validate_checkout {
    my ($values) = @_;

    debug "validate_checkout values ", $values;

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
    $validator->field('phone' => 'String');

    # billing address is differnt
    if ($values->{billing_enabled} and $values->{billing_enabled} == 1) {
        $validator->field('billing_first_name' => "String");
        $validator->field('billing_last_name' => "String");
        $validator->field('billing_address' => "String");
        $validator->field('billing_postal_code' => "String");
        $validator->field('billing_city' => 'String');
    }

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
};

=head2 set_form_values

define the defaults used by the checkout form. returns checkout $form

=cut

sub set_form_values {
    my $form = shift;
    my $values;
    my $quote_values = form('shipping-quote')->values('session');

    debug "shipping-quote from session: ", $quote_values;

    if (logged_in_user) {
        # search for existing addresses
        debug "search for exiting address";
        $values = user_address();
    }
    $values->{country} ||= $quote_values->{country} || 'US';
    $values->{postal_code}         ||= $quote_values->{postal_code};
    $values->{billing_country}     ||= $values->{country};
    $values->{billing_postal_code} ||= $values->{postal_code};
    $values->{shipping_rate} = $quote_values->{shipping_rate};

    # save changes to form
    $form->fill($values);

    return $form;
};

=head2 user_address

Input user_id and a get addresses
TODO this should return a full list not just ->single

=cut

sub user_address {
    my $values;

    my $users_id = session('logged_in_user_id');

    #TODO this should be a search and a dropdown to select if multiple
    my $shipping_address = shop_address->search(
        {
            users_id => $users_id,
            type => 'shipping',
        },
        {
            order_by => {-desc => 'last_modified'},
            rows => 1,
        },
    )->single;

    if ($shipping_address) {
        debug "user_address: Shipping address found: ", $shipping_address->id;

        $values = Angler::Forms::Checkout->new(
            address => $shipping_address,
        )->transpose;

        $values->{shipping_enabled} = 1;
        $values->{shipping_id} = $shipping_address->id;
    }

    # find existing billing address for user
    #TODO this should be a search and a dropdown to select if multiple
    my $bill_adr = shop_address->search(
        {
            users_id => $users_id,
            type => 'billing',
        },
        {
            order_by => {-desc => 'last_modified'},
            rows => 1,
        },
    )->single;

    if ($bill_adr) {
        debug "Billing address found: ", $bill_adr->id;

        my $billing_form_values = Angler::Forms::Checkout->new(
            address => $bill_adr,
            prefix => 'billing_',
        )->transpose;

        # add billing data to form values
        while (my ($key, $value) = each %$billing_form_values) {
            $values->{$key} = $value;
        }

        $values->{billing_enabled} = 1;
        $values->{billing_id} = $bill_adr->id;
    }
        debug "user_address: Filling checkout form with: ", $values;

    return $values
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

=head2 generate_order

add order details to database

=cut

sub generate_order {
    my ($form, $user) = @_;
    my $values = $form->{values};

    my $tokens = checkout_tokens($form);

    #append order address tokens
    $tokens = order_address_tokens($values, $tokens, $user);

    # create address's
    my $bill_obj = shop_address->create($tokens->{billing_address});
    my $ship_obj = shop_address->create($tokens->{shipping_address});

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
    my %order_info = (users_id => $user->id,
              email => $user->email,
                      billing_addresses_id => $bill_obj->id,
                      shipping_addresses_id => $ship_obj->id,
                      subtotal => $tokens->{cart}->subtotal,
                      shipping => $tokens->{cart_shipping},
                      salestax => $tokens->{cart_tax},
                      total_cost => $tokens->{cart}->total,
                      order_date => $order_date,
                      order_number => $order_date,
                      orderlines => \@orderlines);

    my $order = shop_order->create(\%order_info);

    return $order;
};

=head2 generate_payment

input order 

=cut

sub generate_payment {
    my ($order, $payment_order) = @_;
    my $tokens;

    # update payment info
    $payment_order->update({orders_id => $order->id});

    # update order number
    $order->update({order_number => 'WBA6' . sprintf("%06s", $order->id)});

    $tokens->{order} = $order;
    $tokens->{payment} = $payment_order;

    return $tokens;
};

=head2 finalize_order

clean up everything and email

=cut

sub finalize_order {
    my ($tokens, $form) = @_;

    my $order = $tokens->{order};

    # clear cart
    cart->clear;

    # reset form
    $form->reset;

    my $cids = {};
    # send email to customer
    my $body = template 'email/order-receipt',
        {order => $order, email_cids => $cids}, {layout => undef};

    debug to_dumper($cids);
    my @attachments;
    foreach my $cid (keys %$cids) {
        my @paths = grep { length($_) } split(/\//, $cids->{$cid}->{filename});
        my $path = File::Spec->catfile(config->{public},@paths);
        if (-f $path) {
            push @attachments, { Id => $cid,
                                 Path => $path };
        }
        else {
            warning "Couldn't find $path!";
        }
    }
    debug to_dumper(\@attachments);

    email ({type => 'html',
            from => config->{order_email},
            to => $order->email,
            bcc => config->{bcc} || '',
            subject => "Your Order " . $order->order_number,
            message => $body,
            multipart => 'related',
            attach => \@attachments,
        });

    return $order;
}

1;
