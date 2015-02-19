package Angler::Routes::Review;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::FlashNote;
use Dancer::Plugin::Form;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Auth::Extensible qw(
logged_in_user authenticate_user user_has_role require_role
require_login require_any_role
);
use Data::Transpose::Validator;
use Dancer::Plugin::Email;
use Try::Tiny;
use Data::UUID;

get '/review/:sku' => sub {
    my $self = shift;

    my $sku     = param "sku";
    my $form    = form('review');
    my $product = shop_product($sku);

    warning "review get pristine: ", $form->pristine;

    return forward 404 unless $product;

    if (logged_in_user) {
        $form->fill(
            { nickname => shop_user( session('logged_in_user_id') )->nickname }
        );
    }

    template 'product/review/content',
      {
        image   => $product->image_325x325,
        product => $product,
        review_count =>
          $product->reviews->search( { public => 1, approved => 1 } )->count,
        review_avg      => $product->average_rating,
        form            => $form,
        "canonical-url" => uri_for( "/review/" . $product->sku ),
      };
};

post '/review/:sku' => sub {
    my $tokens;
    my $form = form('review');
    warning "review post pristine: ", $form->pristine;
    my $values = $form->values;

    my $sku = param 'sku';
    my $product = shop_product($sku);

    return forward 404 unless $product;

    $tokens->{'form'} = $form;
    $tokens->{errors} = validate_review($values);

    if ( $tokens->{errors} ) {
        debug "server-side errors in post review: ", $tokens->{errors};
        return template 'product/review/content', $tokens;
    }

    my $review_data = {
        rating    => $values->{rating},
        title     => $values->{title},
        content   => $values->{content},
        recommend => $values->{recommend},
    };

    my ( $user, $review );
    if (logged_in_user) {
        $user = shop_user( session('logged_in_user_id') );
    }
    elsif ( $user = shop_user->find({ nickname => $values->{nickname} }) ) {
        debug "found anon user in add review with nickname: " . $user->nickname;
    }
    else {
        # create dummy user with unique username
        my $ug = Data::UUID->new;
        $user = shop_user->create(
            {
                username => lc($ug->create_str),
                active   => 0,
            }
        );
        # change username to anonymous12345
        $user->update({username => "anonymous" . $user->id});
    }

    if ( !$user->nickname || $user->nickname ne $values->{nickname} ) {
        try {
            $user->update( { nickname => $values->{nickname} } );
        }
        catch {
            # delete anonymous user and return error
            $user->delete unless logged_in_user;
            $tokens->{errors} =
              { nickname =>
                  "Nickname already in use. Please choose a different one." };
            debug "server-side errors in post review: ", $tokens->{errors};
            return template 'product/review/content', $tokens;
        }
    }

    try {
        $review = $product->add_to_reviews($review_data);
    }
    catch {
        error "problem inserting review: ", $review_data;
        $review_data->{error} = $_;
    }
    finally {
        debug "send email review for id: ", $review->id;
        $review_data->{author_users_id} = $user->id;
        $review_data->{sku} = $product->sku;
        $review_data->{reviews_id} = $review->id;
        $review_data->{nickname} = $user->nickname;
        $review_data->{username} = $user->username;
        review_email($review_data);
    };

    flash success => "Thank you for reviewing this product. Our shop staff have been notified and your review will appear on the site as soon as possible.";

    $form->reset;
    return redirect "/" . $product->uri;
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
        email ({
            from    => 'noreply@westbranchangler.com',
            to      => config->{emails}->{admin_email},
            subject => 'New Product Review Has Been Posted!',
            type    => 'html',
            body => $message,
        });
 };

1;
