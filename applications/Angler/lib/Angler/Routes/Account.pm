package Angler::Routes::Account;

use Dancer ':syntax';
use Dancer::Plugin::Nitesi;
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
    template 'registration', {layout_noleft => 1,
        layout_noright => 1};
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
    $acct = rset('User')->create( $user_data );

    # add user role
    $role = rset('UserRole')->create( { users_id => $acct->id, roles_id => $user_role_id  } );

    # add user attribute
    $attr = rset('UserAttribute')->create( { users_id => $acct->id, name => 'facebook', value => '0' } );
#    $attr = rset('UserAttribute')->create( { users_id => $acct->id, name => 'fb_token', value => '' } );

    #debug("Register result: ", $acct || 'N/A');
    return redirect '/login';
    }

    template 'registration', {form => $form,
                  errors => $error_ref,
                  layout_noleft => 1,
                  layout_noright => 1};

};

sub post_login_route {
   return redirect '/' if logged_in_user;

   my $login_route = '/login';

   my $user = rset('User')->search( { username => params->{username} } )->single;
   if ( !$user ) {
        var login_failed => 1;
        return forward $login_route, { return_url => params->{return_url} }, { method => 'get' };
  }
    my ($success, $realm) = authenticate_user( params->{username}, params->{password} );

    if ($success) {
        session logged_in_user => params->{username};
        session logged_in_user_realm => $realm;
        return redirect '/';
    } else {
    #something
}
};

post '/login' => \&post_login_route;

get '/logout' => sub {
    template 'login', {layout_noleft => 1,
        layout_noright => 1};
    session->destroy;
    return redirect '/';
};

get '/login/denied' => sub {
    template 'login_denied';
};

get '/login' => sub {
    template 'login';
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
    my ( $attr, $avail,  $user, $user_email, $fb, $token_response_object, $authorization_code, $role, $acct, $user_role_id, $pass, $secret, $user_data);

    $authorization_code = params->{code};
    $fb                 = Facebook::Graph->new( config->{facebook} );
    $user_role_id = '3';
    $pass = new String::Random;
    # create password for facebook user
    $secret = $pass->randpattern("CCcn!Ccn");
    $token_response_object = $fb->request_access_token($authorization_code);

    # get fb user information
    $user = $fb->fetch('me');
    $user_email = $user->{email}; 
    $avail = rset('User')->find({ email => $user_email });

    # if the customer is not already registered with this email then DO IT.
    if ( !$avail ) {

    $user_data = { username => $user->{email},
                   first_name => $user->{first_name},
                   last_name => $user->{last_name},
                   email    => $user_email,
                   password => $secret,
                   created => $now
    };

    # create new user
    $acct = rset('User')->create( $user_data );

    # add user role
    $role = rset('UserRole')->create( { users_id => $acct->id, roles_id => $user_role_id  } );

    # add user attribute
    $attr = rset('UserAttribute')->create( { users_id => $acct->id, name => 'facebook', value => '1' } );
    $attr = rset('UserAttribute')->create( { users_id => $acct->id, name => 'fb_token', value => $token_response_object->token } );
    $attr = rset('UserAttribute')->create( { users_id => $acct->id, name => 'fb_token_exp', value => $token_response_object->expires } );
    $attr = rset('UserAttribute')->create( { users_id => $acct->id, name => 'fb_id', value => $user->{id} } );
    $attr = rset('UserAttribute')->create( { users_id => $acct->id, name => 'fb_password', value => $secret } );
}
    # log person in with DPAE
    session logged_in_user => $user->{email};
    session logged_in_user_realm => 'users';

    # TODO lets email the user now with login information

    redirect '/';

};
1;