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
    my $sku = params->{sku};
    my $product = shop_product($sku);
    my $form = form('review');
    my ($image_src, $review_count, $review_avg);

    # add image. There could be more, so we just pick the first
    my $image = $product->media_by_type('image')->first;

    if ($image) {
        $image_src = uri_for($image->display_uri('image_325x325'));
    }

    template 'product-review', { product => $product,
                                 image_src => $image_src,
                                 review_count => $product->reviews->count,
                                 review_avg => $product->average_rating,
                                 form => $form,
    };
};

#post '/review/:sku' => require_login sub {
post '/review/:sku' => sub {
    my $form = form('review');
    my $values = $form->values;
    my $users_id;
    my $sku = params->{sku};
    my $product = shop_product($sku);

    #validate input
    my $error_hash = validate_review($values);

#    unless ($error_hash) {
        my $review_data = { rating => $values->{rating},
                            title => $values->{title},
                            content => $values->{content},
                            recommend => $values->{recommend},
                            author => $users_id,
        };

        debug "error hash: ", $error_hash;
         debug "form values: ", $review_data;
          my $review = $product->add_to_reviews( $review_data );
            review_email($review_data);
            $form->reset;
            return redirect "/review/:sku";
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
            to      => 'sam@westbranchresort.com',
            subject => 'New Product Review Has Been Posted!',
            type    => 'html',
            body => $message,
        });
 };

1;
