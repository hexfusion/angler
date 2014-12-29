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

#use Angler::Routes::About;
use Angler::Routes::Account;
use Angler::Routes::Checkout;
use Angler::Routes::Contact;
use Angler::Routes::Review;
use Angler::Routes::Search;
use Angler::Cart;

use Data::Transpose::Iterator::Scalar;
use Template::Flute::Iterator::JSON;

our $VERSION = '0.1';

# connect DBIC session engine to our schema
set session_options => {schema => schema};

hook 'before_layout_render' => sub {
    my $tokens = shift;
    my $action ='';
    my $scope = '';
    my $record;

    # make cart details available
    $tokens->{cart} = cart->products;
    $tokens->{cart_count} = cart->quantity;
    $tokens->{cart_total} = cart->total;

    # logo
    $tokens->{logo_uri} = uri_for('/');

    # create menu iterators
    my $nav = shop_navigation;
    my $menu_rs = $nav->search({ type => 'menu' });
    my $section=0;

    while ($record = $menu_rs->next ) { 
        $section++;
        my $menu = $nav->search({
                type => 'menu',
                parent_id => $record->navigation_id,
            },
            {
                order_by => { -asc => 'priority'},
            }
        );

        my $total = $menu->count;

        # FIXME this total and others here should come from a config not hardcode
        if ( $total >= '16' ) {
            debug "Too many records $total to display in nav menui max is 16";
        };

        my $row = $menu->search({}, { rows => 8 });

        my $n = 2;
        my $column=0;
        for my $i (1..$n) {
            $column ++;

            my $nav_menu = $row->page($i);
            while (my $record = $nav_menu->next ) {
                push @{$tokens->{'menu_s' . $section . '_c' . $column}}, $record;
            };
        };
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

    # adjust image name.
    

    # navigation elements
#    $tokens->{navigation} = shop_navigation->search(where => {parent => 0});
};

=head2 before_navigation_search

This hooks replaces the standard L<Dancer::Plugin::Interchange6::Routes>
navigation route to enable us to alter product listing items per page on 
the fly and sort order.

=cut

hook 'before_navigation_search' => sub {
    my $tokens = shift;
    my $routes_config = config->{plugin}->{'Interchange6::Routes'};

    # rows (products per page) 
    my $rows = $routes_config->{navigation}->{records} || 10;

    my $products =
      $tokens->{navigation}->navigation_products->search_related('product')
      ->active->limited_page( $tokens->{page}, $rows );

    # FIXME I believe this only goes 1 level of children

    # if this is the root category then show all the childrens products.
    if ( $tokens->{navigation}->is_root == 1 ) {
        $products =
            $tokens->{navigation}->children->search_related('navigation_products')->search_related('product')
            ->active->limited_page( $tokens->{page}, $rows );
    }
    else {
        $products =
            $tokens->{navigation}->navigation_products->search_related('product')
            ->active->limited_page( $tokens->{page}, $rows );
    }

    my $pager = $products->pager;

    $tokens->{pager} = $pager;

    # paging

    my $current = $pager->current_page;
    my $n = int( ($pager->total_entries / $pager->entries_per_page) + .999);
    my $first_page = 1;
    my $last_page  = $pager->last_page;

    if ( $pager->last_page > 5 ) {
   # more than 5 pages so we might need to start later than page 1
        if ( $pager->current_page <= 3 ) {
            $last_page = 5;
        }
        elsif (
            $pager->last_page - $pager->current_page <
             3 )
            {
                $first_page = $pager->last_page - 4;
            }
        else {
            $first_page = $pager->current_page - 2;
            $last_page = $pager->current_page + 2;
        }
    }

    my @pages = map {
       +{
           page => $_,
           uri  => $_ == $pager->current_page
           ? undef
           : uri_for( $tokens->{navigation}->uri . '/' . $_ ),
           active => $_ == $pager->current_page ? " active" : undef,
         }
    } $first_page .. $last_page;

    # debug "Paging", \@pages;

    $tokens->{pagination} = \@pages;

    unless ($current == '1') {
        $tokens->{pagination_previous} =
         uri_for( $tokens->{navigation}->uri . '/' . ($current - 1));
    }

    unless ($current == $n) {
        $tokens->{pagination_next} =
         uri_for( $tokens->{navigation}->uri . '/' . ($current + 1));
    }

    my @products;

    # FIXME this should come from config.
    my $default_image = schema->resultset('Media')->find({ uri => 'default.jpg' });

    while (my $record = $products->next) {

        my $product_href = {$record->get_inflated_columns};

        # retrieve picture and add it to the results
        my $image = $record->media_by_type('image')->first;
        unless ($image) {
            $image = $default_image;
        }
        # FIXME this should be a new folder 200x200
        $product_href->{image} = uri_for($image->display_uri('image_120x120'));
        push @products, $product_href;
    };

    # FIXME load list of brands for testing should be only for this search
    my $brands = shop_navigation->search({type => 'manufacturer',
                                          active => 1});

    $tokens->{brands} = [$brands->all];

    # breadcrumbs
    my $rs = shop_navigation->find(
            { navigation_id => $tokens->{navigation}->navigation_id },
    );

    my @anc = $rs->ancestors;

    # parents are actually childern in Navigation so we need to reverse this order
    my @crumbs = ((reverse @anc), $rs);

    $tokens->{breadcrumbs} = \@crumbs;

    $tokens->{products} = \@products;

    Dancer::Continuation::Route::Templated->new(
        return_value => template( $tokens->{template}, $tokens ) )->throw;
};

hook 'before_product_display' => sub {
    my ($tokens) = @_;
    my $product = $tokens->{product};
    my @related_products;
    my @reviews;

    # breadcrumbs
    my $path = $product->path;
    $tokens->{breadcrumbs} = $path;

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
    my $components = Template::Flute::Iterator::JSON->new(file => '/home/sam/camp10/applications/Angler/views/home/components.json');
    my $mf = shop_navigation->search({type => 'manufacturer'});
        debug "json components", $components;

    template 'home/content', {manufacturer => [$mf->all], component => $components };

};

get '/about-us' => sub {
    template 'about/us/content';
};

get '/about-pros' => sub {
    template 'about/pros/content';
};

get '/about-me' => sub {
    template 'about/me/content';
};

get '/blog' => sub {
    template 'blog/content';
};

get '/learning-center' => sub {
    template 'learning/content';
};

get '/learning-video' => sub {
    template 'learning/video/content';
};

get '/shipping' => sub {
    template 'shipping/content';
};

get '/privacy-policy' => sub {
    template 'policy/privacy/content';
};

get '/return-policy' => sub {
    template 'policy/return/content';
};

get '/login' => sub {
    template 'auth/login/content';
};

get '/test-upload' => sub {
    my $product = shop_product;
    template 'test/uploader/content', { products => $product };
};

post '/upload' => sub {
    my $upload_dir = "/home/sam/camp10/applications/Angler/uploads";
    my $upload = request->upload('filename');
    my $filename = $upload->filename;
    $upload->copy_to("$upload_dir/$filename");
 
};


shop_setup_routes;


true;
