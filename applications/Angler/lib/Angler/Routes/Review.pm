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

get '/review_thank-you' => sub {
    my $form = form('review');
    template 'review_thank-you', {form => $form };
};

post '/review/:sku' => require_login sub {
    my $form = form('review');
    my $values = $form->values;
    my $user = logged_in_user;
    my $users_id = $user->users_id;
    my $sku = params->{sku};

    #validate input
    my ($error_string) = validate_review($values);

    unless ($error_string) {
        my $review_data = { rating => $values->{rating},
                            title => $values->{title},
                            sku => $sku,
                            content => $values->{content},
                            recommend => $values->{recommend},
                            users_id => $users_id,
        };

        # check if review for product already exist for this user
        my $review_exist = rset('Review')->find({sku => $sku, users_id => $users_id});

        if ($review_exist) {
            die "A review for $sku already exists for this user!";
        }
        else {
            my $review = rset('Review')->create( $review_data );
            $form->reset;
            $form->to_session;
            return redirect '/review_thank-you';
        }
    }
    else {
        $form->errors($error_string);
        $form->to_session;

        return redirect "/$sku#review";
    }
};

sub validate_review {
    my ($form, $values) = @_;
    my ($clean, $error_string);
    # validate form input
    my $validator = Data::Transpose::Validator->new(requireall => 1);

    $validator->field('rating' => "String");
    $validator->field('title' => "String");
    $validator->field('content' => "String");

    $clean = $validator->transpose($values);
    if (!$clean || $validator->errors) {
        $error_string = $validator->errors_as_hashref;
    }
    return ($error_string);
};


1;
