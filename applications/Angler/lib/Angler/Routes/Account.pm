package Angler::Routes::Account;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Form;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Auth::Extensible qw(
logged_in_user authenticate_user user_has_role require_role
require_login require_any_role
);
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

my $now = DateTime->now;

# add default admin user for testing
#my $admin_user = shop_user->create({ username => 'admin', 
#                                      password => 'admin', 
#                                      email => 'admin@localhost',
#                                      created => $now
# });

get '/registration' => sub {
    my $form = form('registration');

    template 'account/register/content', {form => $form};
};

post '/registration' => sub {
    my ($form, $values, $validator, $error_ref, $acct, $attr, $user, $role, $user_data, $user_role_id );

    $form = form('registration');

    $values = $form->values;
    # id of user role
    $user_role_id = '3';
    $user_data = { username => $values->{email},
                      email    => $values->{email},
                      password => $values->{password},
                      created => $now
        };

    debug "User data: ", $user_data;

    # validate form input
    $validator = Data::Transpose::Validator->new(requireall => 1);
    $validator->prepare(email => {validator => 'EmailValid'},
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
    my $errors;

    if (!$clean || $validator->errors) {
        $errors = $validator->errors_hash;
         debug("Register errors: ", $errors);
        $form->fill($values);
    }
    else {
    # create new user and role
    my $user = add_user($user_data);

    # add user attribute
    $user->add_attribute('facebook','0');

    #debug("Register result: ", $acct || 'N/A');
    return redirect '/auth/login/content';
    }

    template 'account/register/content', {form => $form,
                  errors => $errors};

};

get '/login/denied' => sub {
    template 'login_denied';
};

get '/forum' => require_login sub {
    template 'forum';
     };

get '/user/account' => require_role user => sub {
    my %tokens;
    my $form = form('account');

    # read information for this account
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

    my $form_values = {};
    
    if ($ship_adr) {
        debug "Shipping address found: ", $ship_adr->id;

        $form_values = Angler::Forms::Checkout->new(
            address => $ship_adr,
        )->transpose;

        $form_values->{shipping_id} = $ship_adr->id;
    }
    else {
        $form_values->{country} = 'US';
    }

    if ($bill_adr) {
        $form_values->{billing_enabled} = 1;
        $form_values->{billing_id} = $bill_adr->id;
    }
    else {
        $form_values->{billing_country} = 'US';
    }

    $form->fill($form_values);

    %tokens = (form => $form,
               countries => [shop_country->search(
                   {active => 1},
                   {order_by => 'name'},
               )],
               );

    template 'account_my-account', \%tokens;
};

post '/user/account' => require_role user => sub {
    # check user input
    my $form = form('account');

    my $values = $form->values;

    # check incoming shipping/billing id
    my $ship_adr;

    if ($values->{shipping_id}) {
        if ($ship_adr = shop_address->find($values->{shipping_id})) {
            if ($ship_adr->users_id ne session('logged_in_user_id')) {
                return status '403';
            }
        }
        else {
            return status '403';
        }
    }

    my $error_hash;

    if ($error_hash = validate_account($values)) {
        debug "Fill account form with: ", $values;
        $form->fill($values);
    }
    else {
        # update db
        debug "Account change ok: ", $values;

        # read information for this account
        $ship_adr->update({
            first_name => $values->{first_name},
            last_name => $values->{last_name},
            address_2 => $values->{address},
            phone => $values->{phone}
        });

        return "OK";
    }

    my %tokens = (form => $form,
                  countries => [shop_country->search(
                      {active => 1},
                      {order_by => 'name'},
                  )],
                  errors => $error_hash);

    template 'account_my-account', \%tokens;
};
    
get '/orders' => require_role user => sub {
    my $orders = shop_user->find(session('logged_in_user_id'))->search_related('Order');

    template 'account_your-orders', {orders => [$orders->all]};
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
    my ($data) = @_;
    my $message = template('email/reg_conf_email', $data, {layout => undef});
        email ({
            from    => 'ic6test@westbranchangler.com',
            to      => 'sam@westbranchresort.com',
            subject => 'You have created an account with West Branch Angler.',
            type    => 'html',
            body => $message,
        });
 };


sub add_user {
    my ($user_data) = @_;
    my ($user, $role);
    my $user_role_id = config->{user_role_id};

     debug("Create user data: ", $user_data);
    # create account
    $user = shop_user->create( $user_data );

    # add user role
    $role = $user->create_related('user_roles', { users_id => $user->id, roles_id => $user_role_id  } );

    # email confirmation
    reg_conf_email();

    return ($user);
};

1;
