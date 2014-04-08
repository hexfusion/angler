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
use Angler::Shipping;

use Data::Transpose::Iterator::Scalar;

our $VERSION = '0.1';

# connect DBIC session engine to our schema
set session_options => {schema => schema};

hook 'before_layout_render' => sub {
    my $tokens = shift;
    my $action ='';
    my $scope = '';
    # display cart count
    $tokens->{cart_count} = cart->count;

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
};

hook 'before_product_display' => sub {
    my ($tokens) = @_;
    my $product = $tokens->{product};
    my $user = logged_in_user;
    my $user_id = session('logged_in_user_id');
    my $sku = $product->sku;

    my $status = logged_in_user;

    my $path = $product->path('menu');

    my $current_nav = pop @$path;

    # order quantity
    my $qmin = 1;
    my $qmax = 10;
    my $qiter = Data::Transpose::Iterator::Scalar->new([$qmin..$qmax]);

    $tokens->{quantity} = $qiter;

    my @other_products;
    my @review_list;

    # set form review defaults
    my $form = form('review');

    $form->{values}->{rating} //= '0';
    my $values = $form->{values};
    # debug "Errors: ", $form->{errors};
    $form->fill($values);
    $form->action('/review/' . $product->sku);
    $tokens->{form} = $form;

    if ($user) {
        $tokens->{review_link} = '#open';
        $tokens->{review_nickname} = shop_user($user_id)->nickname;
    }
    else {
        $tokens->{review_link} = '/login';
    }

    # create review iderators
    my $review_rs = shop_review->search({sku => $sku, approved => '1', public => '1'});

    while (my $review = $review_rs->next) {

        my $user = shop_user($review->users_id);
        debug "Review user_id: ", $user->id;
        my $nickname = $user->nickname;
        my $first_name = $user->first_name;
        my $last_name = substr($user->last_name, 0, 1);
        my $avatar = $user->find_attribute_value('user_avatar_thumb') || '/img/img_default_user_icon_50x50.jpg';
        debug "Avatar: ", $avatar;
        my $reviewer;

        if (!$nickname) {
            $reviewer = "- $first_name $last_name.";
        }
        else {
            $reviewer = "- $nickname";
            $reviewer =~ s/([\w']+)/\u\L$1/g;
        }

        push @review_list, {'title' => $review->title,
                            'content' => $review->content,
                            'rating' => $review->rating,
                            'recommend' => $review->recommend,
                            'avatar' => $avatar,
                            'reviewer' => $reviewer,
                        };
        $tokens->{review_list} = \@review_list;
    };

    if ($current_nav) {
        my $other_records = config->{records}->{other_products};

        my $same_category = $current_nav->search_related(
            'NavigationProduct')->search_related(
                'Product', {
                    'Product.active' => 1,
                    'Product.sku' => {'!=' => $product->sku},
                },
                {
                    rows => $other_records,
                    page => 1,
                },
                );

        while (my $product = $same_category->next) {
            push @other_products, $product;
        }

        if ($same_category->pager->last_page > 1) {
            $tokens->{category_more} = $current_nav->uri;
        }

        $tokens->{category_name} = 'Other ' . $current_nav->name;
    }

    $tokens->{category_products} = \@other_products;

    # add image. There could be more, so we just pick the first
    my $image = $product->media_by_type('image')->first;
    if ($image) {
        $tokens->{image_src} = uri_for($image->display_uri('image_325x325'));
        $tokens->{image_thumb} = uri_for($image->display_uri('image_50x50'));
    }

    return;

        # determine category for product
    my $categories = query->select(join => [qw/navigation code=navigation navigation_products/],
                                   where => {sku => $product->sku});

    if (@$categories) {
        $product->{category_name} = $categories->[0]->{name};

        # get other products for this category
        my $category_products = query->select(join => [qw/navigation_products sku=sku products/],
                                   fields => [qw/products.sku products.description products.price/],
                                   where => {navigation => $categories->[0]->{code}});

        $product->{category_products} = $category_products;
    }

    return;

    # determine variants for product
    my $variants = query->select(table => 'product_attributes',
                                 where => {original_sku => $product->sku},
                                 );

    my %variant_types;

    for (@$variants) {
        $variant_types{$_->{name}}->{$_->{value}} = 1;
    }

    # create iterators
    while (my ($name, $ref) = each %variant_types) {
        for my $value (keys %$ref){
            push (@{$product->{"attribute_$name"}}, {value => $value});
        }
    }

};

hook 'before_cart_display' => sub {
    my ($values) = @_;
    my $subtotal = cart->subtotal;
    my $free_shipping_amount = config->{free_shipping}->{amount};
    my $free_shipping_gap;

    if ($free_shipping_amount > $subtotal) {
	$values->{free_shipping_gap} = $free_shipping_amount - $subtotal;
    }
    else {
	$values->{free_shipping} = 1;
    }

    $values->{countries} = countries();
    $values->{country} = param('country') || 'US';
    $values->{shipping_method} = param('shipping_method');
    $values->{shipping_methods} =
      Angler::Shipping::shipment_methods_iterator_by_iso_country(schema, $values->{country});
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
