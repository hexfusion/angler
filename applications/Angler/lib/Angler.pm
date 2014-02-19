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
use Facebook::Graph;

use Angler::Routes::Account;
#use Angler::Routes::User;
use Angler::Routes::Review;
use Angler::Routes::Search;

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

#     debug "Info: ", $nav_tokens->{navigation}->uri;

#     debug "Products: ", scalar @{$nav_tokens->{products}};
#     for my $product (@{$nav_tokens->{products}}) {
#         debug "Price: ", $product->price;
#     }
};

hook 'before_product_display' => sub {
    my ($tokens) = @_;
    my $product = $tokens->{product};
    my $user = logged_in_user;
    my $user_id = session('logged_in_user_id');

    debug "Before product display: ", $product->sku;
    my $status = logged_in_user;
    my $path = $product->path;
    my $current_nav = pop @$path;
    my @other_products;

    # set form review defaults
    my $form = form('review');

    $form->{values}->{rating} //= '0';
    my $values = $form->{values};
#    debug "Errors: ", $form->{errors};
    $form->fill($values);
    $form->action('/review/' . $product->sku);
    $tokens->{form} = $form;
    debug "Form: ", \$tokens->{form};

    if ($user) {
        $tokens->{review_link} = '#open';
        $tokens->{review_nickname} = shop_user($user_id)->nickname;
    }
    else {
        $tokens->{review_link} = '/login';
    }

    if ($current_nav) {
        my $same_category = $current_nav->search_related('NavigationProduct')->search_related('Product', {'Product.active' => 1, 'Product.sku' => {'!=' => $product->sku}});

        while (my $product = $same_category->next) {
            debug "Found other: ", $product->sku, " with price: ", $product->price;
            push @other_products, $product;
        }
    }

    $tokens->{category_products} = \@other_products;
debug "Attributes: ", $product->attribute_iterator;
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

    # add image
    $product->{image_src} = 'http://www.westbranchangler.com/site/images/items/325x325/' . $product->image;

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

    $values->{countries} = countries();

debug "Country: ", ref($values->{countries}->[0]);
    $values->{country} = 'US';
};

hook 'before_checkout_display' => sub {
    my ($values) = @_;

    $values->{countries} = countries();
    $values->{country} = 'US';
};

sub countries {
    return [shop_country->search({active => 1})];
}

get '/' => sub {
session foo => 'bar';
    template 'home';
};

shop_setup_routes;


true;
