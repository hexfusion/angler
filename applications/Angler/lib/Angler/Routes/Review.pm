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
    my $self = shift;

    my $sku     = param "sku";
    my $form    = form('review');
    my $product = shop_product($sku);

    return forward 404 unless $product;

    template 'product/review/content',
      {
        image   => $product->image_325x325,
        product => $product,
        review_count =>
          $product->reviews->search( { public => 1, approved => 1 } )->count,
        review_avg      => $product->average_rating,
        form            => $form,
        "canonical-url" => uri_for( "/review/" . $product->sku ),
        "extra-js-file" => 'validator.min.js',
      };
};

post '/review/:sku' => sub {
    my $tokens;
    my $form = form('review');
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

    if (logged_in_user) {
        $review_data->{author_users_id } = session('logged_in_user_id');
    }
    debug "review form values: ", $review_data;
    my $review = $product->add_to_reviews($review_data);
    review_email($review_data);
    $form->reset;
    return redirect "/review/$sku";
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
#    debug 'email review data ', $review_data;
        email ({
            from    => 'ic6test@westbranchangler.com',
            #to      => 'sam@westbranchresort.com',
            to      => 'peter@sysnix.com',
            subject => 'New Product Review Has Been Posted!',
            type    => 'html',
            body => $message,
        });
 };

1;
