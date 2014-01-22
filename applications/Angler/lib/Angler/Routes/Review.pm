package Angler::Routes::Review;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Form;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Auth::Extensible qw(
logged_in_user authenticate_user user_has_role require_role
require_login require_any_role
);
use Data::Transpose::Validator;
use Try::Tiny;

get '/review/:sku' => require_login sub {
    my $sku = params->{sku};
    my $form = form('review');
     $form->reset;
     $form->action('/review/' . $sku);
     $form->fill({sku => $sku,});
    template 'review', {form => $form };
};
get '/review_thank-you' => sub {
    my $form = form('review');
    template 'review_thank-you', {form => $form };
};

post '/review/:sku' => require_login sub {
    my $form = form('review');
    my $values = $form->values;
    my $user = logged_in_user;
    my $users_id = $user->users_id;

    my ($form_validate, $error_string) = validate_review($form, $values, $users_id);

    unless ($error_string) {
        my $review_data = { rating => $values->{rating},
                            title => $values->{title},
                            sku => $values->{sku},
                            content => $values->{content},
                            recommend => $values->{recommend},
                            users_id => $users_id,
        };

        # add review
        my $review = rset('Review')->create( $review_data );

        # add/use display name
        if ($values->{user_alias}) {
            $user->add_attribute('user_alias',$values->{user_alias});
        }
        elsif ($values->{user_alias_visible}){
            $user->add_attribute('user_alias_visible',$values->{user_alias_visible});
        }

        return redirect '/review_thank-you';
    }
    else {
    $form->action('/review/' . $values->{sku});
    template 'review', {form => $form_validate,
                    errors => $error_string};
    }
};

sub validate_review {
    my ( $form, $values, $users_id) = @_;

    # validate form input
    my $validator = Data::Transpose::Validator->new(requireall => 1);

    $validator->field('rating' => "String");
    $validator->field('title' => "String");
    $validator->field('sku' => "String");
    $validator->field('content' => "String");

    my $clean = $validator->transpose($values);
    my $error_string;

    if (!$clean || $validator->errors) {
        my $error_ref = $validator->errors;
        debug("Register errors: ", $error_ref);
        $error_string = $validator->packed_errors;
        debug("Error string: ", $error_string);
        $form->errors($error_string);
        debug to_dumper($error_ref);
        $form->fill($values);
    }
    else { 
        my $sku = $values->{sku};
        my $review_exist = rset('Review')->find({sku => $sku, users_id => $users_id});
        if ($review_exist) {
            die "A review for $sku already exists for this user!";
            #$form->errors({sku => "A review for $sku already exists for this user!"});
            #$error_string=({sku => 'Valid sku required for review'});
        }
    }
    return ($form, $error_string);
};


1;
