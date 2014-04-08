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
use Dancer::Plugin::Email;
use Try::Tiny;

get '/review/:sku' => sub {
    return redirect '/' . param('sku') . '#review';
};

post '/review/:sku' => require_login sub {
    my $form = form('review');
    my $values = $form->values;
    my $user = logged_in_user;
    my $users_id = $user->users_id;
    my $sku = params->{sku};

    #validate input
    my $error_hash = validate_review($values);

    unless ($error_hash) {
        my $review_data = { rating => $values->{rating},
                            title => $values->{title},
                            sku => $sku,
                            content => $values->{content},
                            recommend => $values->{recommend},
                            users_id => $users_id,
        };

         debug "clean review data: ", $review_data;

        # check if review for product already exist for this user
        my $review_exist = rset('Review')->find({sku => $sku, users_id => $users_id});
        if ($review_exist) {
            die "A review for $sku already exists for this user!";
        }
        else {
            debug "review EXIST: ", $review_data;
            my $review = rset('Review')->create( $review_data );
            debug "dirty review data: ", $review_data;
            review_email($review_data);
            $form->reset;
            $form->to_session;
            session flypage_message => 'Thank you for your review!';
            return redirect "/$sku";
        }
    }
    else {
        $form->errors($error_hash);
        $form->to_session;
        $form->fill($error_hash);
        return redirect "/$sku#review";
    }
};

sub validate_review {
    my ($values) = @_;
    my ($clean, $error_hash);
    # validate form input
    my $validator = Data::Transpose::Validator->new(requireall => 1);

    $validator->field('rating' => "String");
    $validator->field('title' => "String");
    $validator->field('content' => "String");

    $clean = $validator->transpose($values);
    if (!$clean || $validator->errors) {
        $error_hash = $validator->errors_hash;
    }
    return $error_hash;
};

sub review_email {
    my ($review_data) = @_;
    my $message = template('email/review_new', $review_data, {layout => undef});
    debug 'email review data ', $review_data;
        email ({
            from    => 'ic6test@westbranchangler.com',
            to      => 'sam@westbranchresort.com',
            subject => 'New Product Review Has Been Posted!',
            type    => 'html',
            body => $message,
        });
 };


1;
