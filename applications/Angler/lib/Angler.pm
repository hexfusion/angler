package Angler;
use Dancer ':syntax';
use Dancer::Plugin::Form;
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Interchange6::Routes;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Auth::Extensible qw(
logged_in_user authenticate_user user_has_role require_role
require_login require_any_role
);

use Angler::Routes::About;
use Angler::Routes::Account;
use Angler::Routes::Checkout;
use Angler::Routes::Contact;
use Angler::Routes::Review;
use Angler::Routes::Search;
use Angler::Cart;

use Data::Transpose::Iterator::Scalar;

our $VERSION = '0.1';

# connect DBIC session engine to our schema
set session_options => {schema => schema};

hook 'before_layout_render' => sub {
    my $tokens = shift;
    my $action ='';
    my $scope = '';

    # make cart details available
    $tokens->{cart} = cart->products;
    $tokens->{cart_count} = cart->quantity;
    $tokens->{cart_total} = cart->total;

    # logo
    $tokens->{logo_uri} = uri_for('/');

    # create menu iterators
    my $nav = schema->resultset('Navigation')->search(
         {
          type => 'menu',
         },
         {
          order_by => { -asc => 'priority'},
         }
    );
    while (my $record = $nav->next) {
         push @{$tokens->{'menu_' . $record->scope}}, $record;
    };

# login/logout button
    if (! logged_in_user){
        $action = 'login';
        $scope = 'top-login';
    } else {
        $action ='logout';
        $scope = 'top-logout'
};


   my $auth = schema->resultset('Navigation')->search(
         {
          scope => $scope,
         },
    );
    while (my $record = $auth->next) {
       if ( $record->type eq 'nav' ) {
         push @{$tokens->{'auth-' . $scope}}, $record;
     } else {
         push @{$tokens->{'fb-' . $scope}}, $record;
   }
    };



    # navigation elements
#    $tokens->{navigation} = shop_navigation->search(where => {parent => 0});
};

hook 'before_navigation_display' => sub {
    my $nav_tokens = shift;

    # load list of brands
    my $brands = shop_navigation->search({type => 'manufacturer',
                                          active => 1});

    $nav_tokens->{brands} = [$brands->all];

    my @products;
    my $product;

    while ($product = $nav_tokens->{products}->next) {
        my $product_href = {$product->get_inflated_columns};

        # retrieve picture and add it to the results
        my $image = $product->media_by_type('image')->first;
        if ($image) {
            $product_href->{image} = uri_for($image->display_uri('image_120x120'));
        }

        push @products, $product_href;
    }

    $nav_tokens->{products} = \@products;
};

hook 'before_product_display' => sub {
    my ($tokens) = @_;
    my $product = $tokens->{product};
    my @related_products;
    my @reviews;

    # find related products
    my $prod_rs = shop_product($product->sku)->search_related(
        'merchandising_products',
        {
            type => 'related',
        },
        {
            rows => config->{flypage}->{related_products}->{qty},
        },
    );

    while (my $prod = $prod_rs->next) {
        my $related = shop_product($prod->sku_related);
        my $image = $related->media_by_type('image')->first;
        push @related_products, {
                            sku => $related->sku,
                            name => $related->name,
                            price => $related->price,
                            image => $image,
        };
    }

    debug "Related Products: ", \@related_products;
    $tokens->{related_products} = \@related_products;

    # cart
    debug "Cart Products: ", cart->products;
    $tokens->{cart} = cart->products;
    $tokens->{cart_count} = cart->quantity;
    $tokens->{cart_total} = cart->total;

    # reviews
    my $review_rs = shop_product($product->sku)->reviews;

    $tokens->{review_count} =  $review_rs->count;
    $tokens->{review_link} = '/review/' . $product->sku;

    $tokens->{review_avg} = Angler::Routes::Review->average_rating($product->sku);

    debug "review avg: ", $tokens->{review_avg};

    while (my $review = $review_rs->next) {
       push @reviews, {
                    content => $review->content,
                    rating => $review->rating,
                    recommend => $review->recommend,
#FIXME we need to manage if user is anonymous
#                    author => $review->author->first_name  . ' ' . $review->author->last_name
        };    
    };

    $tokens->{reviews} = \@reviews;
    debug "Review: ", $tokens->{reviews};

    # order quantity
    my $qmin = 1;
    my $qmax = 10;
    my $qiter = Data::Transpose::Iterator::Scalar->new([$qmin..$qmax]);

    $tokens->{quantity} = $qiter;

    # free shipping
    my $free_shipping_amount = config->{free_shipping}->{amount};
    
    if ($product->price > $free_shipping_amount) {
    $tokens->{free_shipping} = 1;
    }

    # add image. There could be more, so we just pick the first
    my $image = $product->media_by_type('image')->first;

    if ($image) {
        $tokens->{image_src} = uri_for($image->display_uri('image_325x325'));
        $tokens->{image_thumb} = uri_for($image->display_uri('image_50x50'));
    }

    my $video = $product->media_by_type('video')->first;

    if ($video) {
        $tokens->{video_src} = $video->uri;
    }
};

hook 'before_cart_display' => sub {
    my ($values) = @_;
    my $cart = cart;
    my $subtotal = cart->subtotal;
    my $free_shipping_amount = config->{free_shipping}->{amount};
    my $free_shipping_gap;

    # determine whether shipping is free or determine missing amount
    if ($free_shipping_amount > $subtotal) {
        $values->{free_shipping_gap} = $free_shipping_amount - $subtotal;
    }
    else {
        $values->{free_shipping} = 1;
    }

    $values->{countries} = countries();

    my $form = form('cart');
    my $form_values;

    if (request->method eq 'GET') {
        $form_values = $form->values('session');
    }
    else {
        $form_values = $form->values;
    }

    my $angler_cart = Angler::Cart->new(
        schema => shop_schema,
        cart => $cart,
        shipping_methods_id => $form_values->{shipping_method},
        country => $form_values->{country},
        postal_code => $form_values->{postal_code},
        user_id => session('logged_in_users_id'),
    );

    $angler_cart->update_costs;

    $form_values->{country} = $angler_cart->country;

    $values->{cart_shipping} = $angler_cart->shipping_cost;
    $values->{cart_tax} = $angler_cart->tax;
    $values->{cart_total} = $cart->total;

    $values->{shipping_methods} = $angler_cart->shipping_methods;

    unless (@{$values->{shipping_methods}}) {
        $values->{shipping_warning} = 'No shipping methods for this country/zip';
    }

    $form->fill($form_values);
    $values->{form} = $form;
};

sub countries {
    return [shop_country->search(
        {active => 1},
        {order_by => 'name'},
    )];
}

get '/' => sub {
    # get all manufacturers
    my $mf = shop_navigation->search({type => 'manufacturer'});

    template 'home', {manufacturer => [$mf->all]};

};

get '/shipping' => sub {
    template 'shipping';

};

get '/privacy-policy' => sub {
    template 'privacy-policy';

};

get '/return-policy' => sub {
    template 'return-policy';

};

shop_setup_routes;


true;
