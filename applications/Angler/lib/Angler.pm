package Angler;
use Dancer ':syntax';
use Dancer::Plugin::Nitesi;
use Dancer::Plugin::Nitesi::Routes;

our $VERSION = '0.1';

hook 'before_layout_render' => sub {
    my $tokens = shift;

    # display cart count
    $tokens->{cart_count} = cart->count;

    # create menu iterators
	my $menu = query->select(table => 'navigation',
                             where => {type => 'menu',
                                      },
                             order => 'priority'
                            );

    for my $record (@$menu) {
		push @{$tokens->{"menu_$record->{scope}"}}, $record;
    };

    # navigation elements
    $tokens->{navigation} = shop_navigation->search(where => {parent => 0});
};

hook 'before_product_display' => sub {
    my ($product) = @_;
    
    debug "Before product display: ", $product->sku;

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
