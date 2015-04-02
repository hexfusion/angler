package Angler::Routes::Account;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Form;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Auth::Extensible qw(
logged_in_user authenticate_user user_has_role require_role
require_login require_any_role
);
use Dancer::Plugin::FlashNote;
use String::Random;
use Facebook::Graph;
use Data::Transpose::Validator;
use Dancer::Plugin::Email;
use Try::Tiny;
use DateTime qw();
use DateTime::Duration qw();
use URI::Escape qw(uri_escape);
use Digest::MD5 qw(md5_hex);

use Angler::Forms::Checkout;
use Angler::Data::Alert;

my $now = DateTime->now;

# registration
get '/registration' => sub {
    my $tokens;
    my $form = form('registration');

    $tokens->{'form'} = $form;

    template 'account/register/content', $tokens;
};

post '/registration' => sub {
    my $tokens;
    my $form = form('registration');
    my $values = $form->values;
    my $username = $values->{email};

    $tokens->{'form'} = $form;

    debug "form ", $tokens;

    # user exists?
    my $user = shop_user->find({ username => $username });

    if ($user) {
        $tokens->{'alerts'} = Angler::Data::Alert->username_exists;
        $form->fill($values);
        debug "return tokens", $tokens;
        return template 'account/register/content', $tokens;
    };

    $tokens->{errors} = validate_registration($values);

    # if we have errors back to the form we go
    if ($tokens->{errors}) {
        debug "Form passed on clientside but not on serverside ", $tokens->{errors};
        return template 'account/register/content', $tokens;
    }

    my $user_data = { username => $values->{email},
                      email    => $values->{email},
                      first_name => $values->{first_name},
                      last_name => $values->{last_name},
                      password => $values->{password},
                      created => $now
    };
  
    # so far so good add user
    $user = &add_user($user_data);

    # email new user
    &reg_conf_email($user);

    $tokens->{'alerts'} = Angler::Data::Alert->registration_success;

    return template 'auth/login/content', $tokens;
};    

=head2 validate_registration

input form values and will return errors

=cut

sub validate_registration {
    my ($values) = @_;

    # validate form input
    my $validator = Data::Transpose::Validator->new(requireall => 1);

    $validator->prepare(first_name => { validator => 'String'},
                        last_name => { validator => 'String'},
                        email => {validator => 'EmailValid'},
                        password => {
                            validator => {
                                class => 'PasswordPolicy',
                                options => {
                                    username => $values->{email},
                                    minlength => 8,
                                    maxlength => 50,
                                    patternlength => 4,
                                    mindiffchars => 5,
                                    disabled => {
                                        digits => 1,
                                        mixed => 1,
                                        specials => 1,
                                    }
                                }
                            }
                        },
                        confirm_password => {
                            validator => 'String',
                        },
                        passwords_matching => {
                            validator => 'Group',
                            fields => [ "password", "confirm_password" ],
                        },

    );

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



get '/login/denied' => sub {
    template 'login_denied';
};

=head2 state_name

returns state name with states_id input

=cut

sub state_name {
    my ($states_id) = @_;
    my $name;

    if ($states_id) {
        $name = shop_schema->resultset('State')->find($states_id)->name;
    }
    return $name;
};

=head2 country_name {

returns country with country_iso_code input

=cut

sub country_name {
    my ($country_iso_code) = @_;

    my $name = shop_schema->resultset('Country')->find($country_iso_code)->name;

    return $name;
};

sub countries {
    return [shop_country->search(
        {active => 1},
        {order_by => 'name'},
    )];
}

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


# my account
get '/account' => require_role user => sub {
    my $user = shop_user(session('logged_in_user_id'));

    # this should never happen with DPAE
    unless ($user) {
        debug "Non logged in user got past DPAE";
        die "Access Denied";
    }

    # read information for this account
    my $ship_adr = shop_address->search(
        {
            users_id => $user->id,
            type => 'shipping',
        },
        {
            order_by => { -desc => 'last_modified' },
            rows => 1,
        },
    )->single;

       my $bill_adr = shop_address->search(
        {
            users_id => $user->id,
            type => 'billing',
        },
        {
            order_by => { -desc => 'last_modified' },
            rows => 1,
        },
    )->single;

    my $tokens;

    $tokens->{orders} = $user->orders;
    $tokens->{user} = $user;

    $tokens->{billing_address} = $bill_adr;
    if ($bill_adr) {
        $tokens->{billing_address_state} = state_name($bill_adr->states_id);
        $tokens->{billing_address_country} = country_name($bill_adr->country_iso_code);
    }

    $tokens->{shipping_address} = $ship_adr;
    if ($ship_adr) {
        $tokens->{shipping_address_state} = state_name($ship_adr->states_id);
        $tokens->{shipping_address_country} = country_name($ship_adr->country_iso_code);
    }
    template 'account/content', $tokens;
};

get '/account/edit' => require_role user => sub {
    my $form = form('edit');
    my $user = shop_user->find(session('logged_in_user_id'));
    $form->fill({   email => $user->email,
                    first_name => $user->first_name,
                    last_name => $user->last_name
     });
    template 'account/edit', {form => $form};
};

post '/account/edit'  => require_role user => sub {
    my $form = form('edit');
    my $values = $form->values;
    my $email = $values->{email};
    my %tokens;

    debug "account/edit post values", $values;

    $tokens{form} = $form;

    # username already exist with this email address?
    my $user = shop_user->find({ username => $email });

    if (defined($user) && $user->id ne session('logged_in_user_id')) {
        $tokens{user_exists} = "The username you entered is already in use by another user.";
        return  template 'account/edit', \%tokens;
   }

    my $user_data;

    if (defined($values) && $values->{change_password}) {
        $user_data = {  email    => $values->{email},
                        first_name => $values->{first_name},
                        last_name => $values->{last_name},
                        password => $values->{password},
        };

        my $validator = Data::Transpose::Validator->new(requireall => 1);

        $validator->prepare(first_name => { validator => 'String'},
                        last_name => { validator => 'String'},
                        email => {validator => 'EmailValid'},
                        password => {
                            validator => {
                                class => 'PasswordPolicy',
                                options => {
                                    username => $values->{email},
                                    minlength => 8,
                                    maxlength => 50,
                                    patternlength => 4,
                                    mindiffchars => 5,
                                    disabled => {
                                        digits => 1,
                                        mixed => 1,
                                        specials => 1,
                                    }
                                }
                            }
                        },
                        confirm_password => {
                            validator => 'String',
                        },
                        passwords_matching => {
                            validator => 'Group',
                            fields => [ "password", "confirm_password" ],
                        },

        );
        my $clean = $validator->transpose($values);

        # clear checkbox FIXME
        $form->fill({   email => $values->{email},
                    first_name => $values->{first_name},
                    last_name => $values->{last_name},
                    change_password => undef
        });

        unless($clean) {
            $tokens{errors} = $validator->errors_as_hashref_for_humans;
            debug "form errors ", $tokens{errors};
            return  template 'account/edit', \%tokens;
        }
    }
    else {
       $user_data = {   email    => $values->{email},
                        first_name => $values->{first_name},
                        last_name => $values->{last_name},
        }
    }

    unless ($user) {
        $user = shop_user->find(session('logged_in_user_id'));
    }

    # clear checkbox FIXME
    $form->fill({   email => $values->{email},
                    first_name => $values->{first_name},
                    last_name => $values->{last_name},
                    change_password => undef
     });

    # update user
    $user = $user->update($user_data);

    # flag success msg
    $tokens{success} = '1';

    template 'account/edit', \%tokens;
};

# my address
get '/account/address' => require_role user => sub {
    my $user = shop_user(session('logged_in_user_id'));

    # this should never happen with DPAE
    unless ($user) {
        debug "Non logged in user got past DPAE";
        die "Access Denied";
    }

    # read information for this account
    my $ship_adr = shop_address->search(
        {
            users_id => $user->id,
            type => 'shipping',
        },
        {
            order_by => { -desc => 'last_modified' },
            rows => 1,
        },
    )->single;

    my $bill_adr = shop_address->search(
        {
            users_id => $user->id,
            type => 'billing',
        },
        {
            order_by => { -desc => 'last_modified' },
            rows => 1,
        },
    )->single;


    my %tokens;
    my @address_id;

    $tokens{user} = $user;

    $tokens{billing_address} = $bill_adr;
    if ($bill_adr) {
        $tokens{billing_address_state} = state_name($bill_adr->states_id);
        $tokens{billing_address_country} = country_name($bill_adr->country_iso_code);
        push @address_id, $bill_adr->id;
    }

    $tokens{shipping_address} = $ship_adr;
    if ($ship_adr) {
        $tokens{shipping_address_state} = state_name($ship_adr->states_id);
        $tokens{shipping_address_country} = country_name($ship_adr->country_iso_code);
        push @address_id, $ship_adr->id
    }

    my $extra_adr = shop_address->search(
        {
            users_id => $user->id,
            addresses_id => {-not_in => \@address_id}
        },
        {
            order_by => { -desc => 'last_modified' },
        },
    );

    $tokens{extra_address} = $extra_adr;

    debug "extra :", $extra_adr->count;

    template 'account/address/content', \%tokens;

};

get '/account/address/edit/:addresses_id' => sub {
    my %tokens;
    my $form = form('edit');
    my $user = shop_user(session('logged_in_user_id'));
    my $addresses_id = params->{addresses_id};

    my $address = $user->addresses->find({addresses_id => $addresses_id});

    # this is very bad go back and think about what you just did
    unless ($address) {
        $tokens{errors}{form_error} = ({
                value => 'Address does not exist for this user.'
        });

    debug "oops ";
    return template 'account/content', \%tokens;
    }

    $tokens{states} = states($address->country_iso_code);

    my $address_data = { type => $address->type,
                         first_name => $address->first_name,
                        last_name => $address->last_name,
                        company => $address->company,
                        phone => $address->phone,
                        address => $address->address,
                        address_2 => $address->address_2,
                        city => $address->city,
                        states_id => $address->states_id,
                        postal_code => $address->postal_code,
                        country_iso_code => $address->country_iso_code,
    };

    debug "get address " , $address_data;

    $form->fill($address_data);
    $form->action('/account/address/edit/' . $addresses_id);

    $tokens{form} = $form;

    $tokens{default_state_id} = ({ value => $address->states_id});

    debug "form ", $form;

    template 'account/address/edit', \%tokens;
  };

post '/account/address/edit/:addresses_id'  => require_role user => sub {
    my %tokens;
    my $form = form('edit');
    my $values = $form->values;
    my $user = shop_user(session('logged_in_user_id'));
    my $addresses_id = params->{addresses_id};

    # lets check that the addresses_id is associated with user
    my $address = $user->addresses->find({addresses_id => $addresses_id});

    debug "post address " , $address->address;

    $tokens{form} = $form;

    # this is very bad go back and think about what you just did
    unless ($address) {
        $tokens{errors}{form_error} = ({
                value => 'Address does not exist for this user.'
        });
        return template 'account/content', \%tokens;
    
    }

    my $address_data = { type => $values->{type},
                         first_name => $values->{first_name},
                        last_name => $values->{last_name},
                        company => $values->{company},
                        phone => $values->{phone} || '',
                        address => $values->{address},
                        address_2 => $values->{address_2},
                        city => $values->{city},
                        states_id => $values->{states_id},
                        postal_code => $values->{postal_code},
                        country_iso_code => $values->{country_iso_code} || 'US',
    };

    debug "address_data", $address_data;

    # FIXME lets send cusotmer an email that this has been done.
    my $update = $address->update($address_data);

#    if ($update->is_changed) {
        $tokens{form_success} = '1';
#    }

    $tokens{states} = states($address->country_iso_code);

    template 'account/address/edit', \%tokens;
};

# my orders
get '/account/orders' => sub {
    my $tokens;
    my $user = shop_user->find(session('logged_in_user_id'));
    $tokens->{orders} = $user->orders;

    template 'account/orders/content', $tokens;
};

get '/account/orders/view/:orders_id' => require_role user => sub {
    my $tokens;
    my $user = shop_user->find(session('logged_in_user_id'));
    $tokens->{order} = $user->orders->find({orders_id => param('orders_id')});

    unless ($tokens->{order}) {
        redirect '/account';
    }

    template 'account/orders/view', $tokens;
};

get '/user/orders/:order_number' => require_role user => sub {
    # find order number with restraint on current user
    my $order = shop_user->find(session('logged_in_user_id'))->find_related('Order', {order_number => param('order_number')});

    unless ($order) {
        status 403;
        return;
    }

    template 'email/order-receipt', {order => $order}, {layout => undef};
};

get '/facebook/login' => sub {
    my $fb = Facebook::Graph->new( config->{facebook} );
    #redirect $fb->authorize->uri_as_string;
        redirect $fb
        ->authorize
        ->extend_permissions( qw(email) )
        ->uri_as_string;
};
get '/facebook/postback/' => sub {
    my $user = facebook_auth();
    redirect '/';
};

get '/confirmation/conf_id:' => sub {
    my $conf_id = params->{conf_id};
};

sub validate_account {
    my ($values) = @_;

    # validate form input
    my $validator = Data::Transpose::Validator->new(requireall => 1);

    # shipping address
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
};

sub facebook_auth {
    my ($attr, $avail, $user, $username, $fb_user, $fb_user_id, $fb_user_img_thumb, $fb_user_email, $user_exist, 
        $fb, $fb_user_img_large, $token_response_object, $authorization_code, $user_id, $pass, $secret, $user_data,
        $facebook_attr);

    $authorization_code = params->{code};
    $fb = Facebook::Graph->new( config->{facebook} );
    $pass = new String::Random;
    # create password for facebook user
    $secret = $pass->randpattern("CCcn!Ccn");
    $token_response_object = $fb->request_access_token($authorization_code);

    # get fb user information
    $fb_user = $fb->fetch('me');
    $fb_user_id = $fb_user->{id};
    $fb_user_img_thumb = $fb->picture($fb_user_id)->get_square->uri_as_string;
    $fb_user_img_large = $fb->picture($fb_user_id)->get_large->uri_as_string;
    $fb_user_email = $fb_user->{email};
    $user = shop_user->find({ email => $fb_user_email });
    $facebook_attr = $user->find_attribute_value('facebook');
    #debug 'facebook b4 elsif value', $facebook;

    # if the customer is not already registered with this email then DO IT.
    if ( !$user ) {
        $user_data = { username => $fb_user_email,
                       first_name => $fb_user->{first_name},
                       last_name => $fb_user->{last_name},
                       email    => $fb_user_email,
                       password => $secret,
                       created => $now
        };
        #debug 'facebook users data', $user_data;
        # create new user and role
        $user = add_user($user_data);
        $user_id = $user->id;
        $username = $user->username;
        $user->add_attribute('facebook','1');
    }
    elsif ( $facebook_attr == 0 ) {
        # add user attribute
        $user->update_attribute('facebook','1');
        $user->add_attribute('fb_id', $fb_user->{id});
        $user->add_attribute('fb_token', $token_response_object->token);
        $user->add_attribute('fb_token_exp', $token_response_object->expires);
        $user->add_attribute('user_avatar_thumb', $fb_user_img_thumb);
        $user->add_attribute('user_avatar', $fb_user_img_large);
    }
    else {
        # update facebook token data
        $user->update_attribute_value('fb_token', $token_response_object->token);
        $user->update_attribute_value('fb_token_exp', $token_response_object->expires);
    }
    
    $user_id = $user->id;
    $username = $user->username;

    # set session login for DPAE 
    session logged_in_user_id => $user_id;
    session logged_in_user => $username;
    session logged_in_user_realm => 'users';

    return ($user);
};

sub reg_conf_email {
    my ($user) = @_;
    my $message = template('email/reg_conf_email', $user, {layout => undef});
        email ({
            from    => config->{emails}->{service_email},
            to      => $user->email,
            subject => 'You have created an account with West Branch Angler.',
            type    => 'html',
            body => $message,
        });
 };


sub add_user {
    my ($user_data) = @_;

    debug("Create user data: ", $user_data);
    # create account
    my $user = shop_user->create( $user_data );

    # email confirmation
    #reg_conf_email();

    return ($user);
};

get "/resetpassword" => sub {
    my $tokens;
    my $form = form('resetrequest');

    $tokens->{'form'} = $form;

    template 'account/resetpassword/request', $tokens;
};

post "/resetpassword" => sub {
    my $tokens;
    my $form = form('resetrequest');
    my $values = $form->values;
    my $email = $values->{email};

    $tokens->{'form'} = $form;

    my $validator = Data::Transpose::Validator->new(requireall => 1);
    $validator->prepare(email => {validator => 'EmailValid'});

    if (! $validator->transpose($values)) {
        $tokens->{error} = "The email address does not look valid."
          . " Please make sure you have entered it correctly";
        return template 'account/resetpassword/request', $tokens;
    }

    # we look for valid user but respond with "email sent..." even if user
    # does not exist

    my $user = shop_user->find({ username => $email });

    if ( $user ) {

        $tokens->{link} =
          uri_for( "resetpassword/" . $user->reset_token_generate );

        $tokens->{year} = DateTime->now->year;

        my $message = template "email/resetpassword", $tokens,
          { layout => undef };

        email(
            {
                from    => 'ic6test@westbranchangler.com',
                to      => $email,
                subject => "Password reset request for West Branch Angler",
                type    => 'html',
                body    => $message,
            }
        );
    }

    # stash form values so they survive the redirect
    $form->to_session;
    redirect "/resetpassword/sent";
};

get "/resetpassword/sent" => sub {
    my $form  = form('resetrequest');
    my $values = $form->values('session');
    $form->reset;
    return template 'account/resetpassword/email_sent', $values;
};

get "/resetpassword/:token" => sub {
    my $token = param 'token';
    my $user;

    try {
        $user = shop_user->find_user_with_reset_token($token);
    }
    catch {
        warning "resetpassword confirm failed: ", $_;
    };

    if ( $user ) {
        template 'account/resetpassword/confirm',
          { form => form('resetconfirm'), token => $token };
    }
    else {
        template "account/resetpassword/bad_token";
    }
};

post "/resetpassword/:token" => sub {
    my $token = param 'token';

    my ( $tokens, $user );
    my $form = form('resetconfirm');
    my $values = $form->values;

    try {
        $user = shop_user->find_user_with_reset_token( $token );
    }
    catch {
        warning "resetpassword confirm failed: ", $_;
    };

    if ( !$user ) {

        # we shouldn't normally get here but just in case do something sane

        flash warning => "We're really sorry but something went wrong when trying to reset your password. Our staff have been informed and will investigate. In the meantime please enter your email address again and we will send you a new link for you to try.";
        return redirect "/resetpassword";
    }

    my $validator = Data::Transpose::Validator->new( requireall => 1 );
    $validator->prepare(
        password => {
            validator => {
                class   => 'PasswordPolicy',
                options => {
                    username      => $user->username,
                    minlength     => 8,
                    maxlength     => 50,
                    patternlength => 4,
                    mindiffchars  => 5,
                    disabled      => {
                        digits   => 1,
                        mixed    => 1,
                        specials => 1,
                    }
                }
            }
        },
        confirm_password => {
            validator => 'String',
        },
        passwords => {
            validator => 'Group',
            fields    => [ "password", "confirm_password" ],
        },
    );

    if ($validator->transpose($values)) {

        # all good so change user password and head back somewhere nice

        $user->password( $values->{password} );
        $user->update;

        flash success =>
          "Your password has been changed. Welcome back to West Branch Angler, "
          . $user->first_name;

        # should use history here...
        return redirect "/";
    }
    else {

        # we have a problem

        my ($v_hash, %errors);
        $v_hash = $validator->errors_hash;
        debug "password reset error hash: ", $v_hash;
        while (my ($key, $value) = each %$v_hash) {
            $errors{$key} = $value->[0]->{value};
            # flag the field with error using has-error class
            $errors{$key . '_input' } = 'has-error';
        }
        $tokens->{'form'} = $form;
        $tokens->{errors} = \%errors;
        debug "errors token: ", $tokens->{errors};
        template 'account/resetpassword/confirm', $tokens;
    }
};

1;
