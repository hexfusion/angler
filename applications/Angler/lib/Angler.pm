package Angler;
use Dancer ':syntax';
use Dancer::Plugin::Nitesi;
use Dancer::Plugin::Nitesi::Routes;

our $VERSION = '0.1';

hook 'before_layout_render' => sub {
    my $tokens = shift;

    # display cart count
    $tokens->{cart_count} = cart->count;

    # navigation elements
    $tokens->{navigation} = shop_navigation->search(where => {parent => 0});
};

hook 'before_product_display' => sub {
    my ($product) = @_;
    
    debug "Before product display: ", $product->sku;
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
