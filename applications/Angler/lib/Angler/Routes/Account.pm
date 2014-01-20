package Angler::Routes::Account;

use Dancer ':syntax';
use Dancer::Plugin::Form;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Auth::Extensible qw(
logged_in_user authenticate_user user_has_role require_role
require_login require_any_role
);
use String::Random;
use Facebook::Graph;
use Input::Validator;
use DateTime qw();
use DateTime::Duration qw();

my $now = DateTime->now;

# add default admin user for testing
#my $admin_user =
#        rset('User')
#        ->create(
#        { username => 'admin', password => 'admin', email => 'admin@localhost', created => $now } );

get '/registration' => sub {
    my $form = form('registration');

    template 'registration', {layout_noleft => 1,
        layout_noright => 1,
        form => $form};
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
    # validate form input
    $validator = new Input::Validator;

    $validator->field('email')->required(1)->email();
    $validator->field('password')->required(1);
    $validator->field('verify')->required(1);

    $validator->validate($values);

    if ($validator->has_errors) {
    $error_ref = $validator->errors;
    debug("Register errors: ", $error_ref);
    $form->errors($error_ref);
    $form->fill($values);
    }
    else {
    # create account
    #debug("Register account: $values->{email}.");
    $user = rset('User')->create( $user_data );

    # add user role
    $role = rset('UserRole')->create( { users_id => $user->id, roles_id => $user_role_id  } );

    # add user attribute
    $user->add_attribute('facebook','0');
#    $attr = rset('UserAttribute')->create( { users_id => $acct->id, name => 'fb_token', value => '' } );

    #debug("Register result: ", $acct || 'N/A');
    return redirect '/login';
    }

    template 'registration', {form => $form,
                  errors => $error_ref,
                  layout_noleft => 1,
                  layout_noright => 1};

};

get '/login/denied' => sub {
    template 'login_denied';
};

get '/forum' => require_login sub {
    template 'forum';
     };

get '/account' => require_role users => sub {
    template 'account_my-account';
};

get '/facebook/login' => sub {
    my $fb = Facebook::Graph->new( config->{facebook} );
    #redirect $fb->authorize->uri_as_string;
        redirect $fb
        ->authorize
        ->extend_permissions( qw(offline_access email publish_stream) )
        ->uri_as_string;
};
get '/facebook/postback/' => sub {
    my ( $attr, $avail,  $user, $fb_user, $user_email, $fb, $token_response_object, $authorization_code, $role, $acct, $user_role_id, $pass, $secret, $user_data);

    $authorization_code = params->{code};
    $fb = Facebook::Graph->new( config->{facebook} );
    $user_role_id = '3';
    $pass = new String::Random;
    # create password for facebook user
    $secret = $pass->randpattern("CCcn!Ccn");
    $token_response_object = $fb->request_access_token($authorization_code);

    # get fb user information
    $fb_user = $fb->fetch('me');
    $user_email = $user->{email}; 
    $avail = rset('User')->find({ email => $user_email });

    # if the customer is not already registered with this email then DO IT.
    if ( !$avail ) {

    $user_data = { username => $fb_user->{email},
                   first_name => $fb_user->{first_name},
                   last_name => $fb_user->{last_name},
                   email    => $user_email,
                   password => $secret,
                   created => $now
    };

    # create new user
    $user = rset('User')->create( $user_data );

    # add user role
    $role = rset('UserRole')->create( { users_id => $acct->id, roles_id => $user_role_id  } );

    # add user attribute
    $user->add_attribute('facebook','1');
    $user->add_attribute('fb_token', $token_response_object->token);
    $user->add_attribute('fb_token_exp', $token_response_object->expires);
    $user->add_attribute('fb_id', $fb_user->{id});
    $user->add_attribute('fb_password', $secret);
}
    # log person in with DPAE
    session logged_in_user => $fb_user->{email};
    session logged_in_user_realm => 'users';

    # TODO lets email the user now with login information

    redirect '/';

};
1;
