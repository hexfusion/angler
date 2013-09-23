package Angler;
use Dancer ':syntax';
use Dancer::Plugin::Nitesi;
use Dancer::Plugin::Nitesi::Routes;

our $VERSION = '0.1';

hook 'before_layout_render' => sub {
    my $tokens = shift;
	 my ($new_results,$encoded) = shift;

    # display cart count
    $tokens->{cart_count} = cart->count;

    # menus
	
	#top-menu
    $tokens->{menu_top} = query->select(table => 'navigation',
                                        where => {type => 'menu',
                                                  scope => 'nav-top',
												   });
  
    #top-menu-user
    $tokens->{menu_top_user} = query->select(table => 'navigation',
                                        where => {type => 'menu',
                                                  scope => 'nav-user',
												   });
												   
    #drop_cat
    $tokens->{nav_gear_left} = query->select(table => 'navigation',
                                        where => {type => 'menu',
                                                  scope => 'cat-gear',
												   });
	#drop_cat
    $tokens->{nav_gear_right} = query->select(table => 'navigation',
                                        where => {type => 'menu',
                                                  scope => 'cat-gear-r',
												   });											   
												   

    # navigation elements
    $tokens->{navigation} = shop_navigation->search(where => {parent => 0});
};

hook 'before_product_display' => sub {
    my ($product) = @_;
    
    debug "Before product display: ", $product->sku;

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
};

hook 'before_cart_display' => sub {
    my ($values) = @_;

    $values->{countries} = countries();
    $values->{country} = 'US';
};

hook 'before_checkout_display' => sub {
    my ($values) = @_;

    $values->{countries} = countries();
    $values->{country} = 'US';
};

sub countries {
    return query->select(
                join => "country",
                fields => [
                        "selector AS value", "name AS label"
                ],
                order => "name"
        );
}

get '/' => sub {
    template 'home';
};

shop_setup_routes;

true;
