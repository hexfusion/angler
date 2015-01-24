package Angler::Data::DataGen;

use strict;
use warnings;

use Interchange6::Schema;
use parent 'DBIx::Class::ResultSet';

__PACKAGE__->load_components('Helper::ResultSet::Random');

use Dancer ':script';
use Dancer::Plugin::Interchange6;

#Random data generators
use Data::Faker;
use Data::Generate qw{parse};
use Acme::MetaSyntactic;

use utf8;

my $fake = Data::Faker->new;
my $meta = Acme::MetaSyntactic->new;
my $shop_schema = shop_schema;

sub users {
    my $name     = $fake->first_name;
    my $lastname = $fake->last_name;
    my $domain   = $fake->domain_name;
    my $password = parse(q{VC(10) [A-Z][1-14].[a-z][2579]{4}[A-Z][14]{2}})
      ->get_unique_data(1);

    my $rset_countries = $shop_schema->resultset('Country');
    my $country        = $rset_countries->search(
        undef,
        {
            rows => 1,
            offset => int( rand( $rset_countries->count ) ),
        }
    )->single;

    my $states_id;
    if ( $country->show_states ) {
        my $rset_states = $shop_schema->resultset('State')->search(
            {
                country_iso_code => $country->country_iso_code,
            }
        );
        $states_id = $rset_states->search(
            undef,
            {
                rows => 1,
                offset => int( rand( $rset_states->count ) ),
            }
        )->single->states_id;
    }

    my $user = {
        username => lc($name) . "@" . $domain,
        email    => lc($name) . "@" . $domain,
        password => $password,
        first_name => $name,
        last_name => $lastname,
        addresses => [
            {
                type             => 'shipping',
                first_name       => $name,
                last_name        => $lastname,
                address          => $fake->street_address,
                postal_code      => $fake->us_zip_code,
                city             => $fake->city,
                country_iso_code => $country->country_iso_code,
                states_id        => $states_id,
                phone            => $fake->phone_number,
            },
            {
                type             => 'billing',
                first_name       => $fake->first_name,
                last_name        => $fake->last_name,
                address          => $fake->street_address,
                postal_code      => $fake->us_zip_code,
                city             => $fake->city,
                country_iso_code => $country->country_iso_code,
                states_id        => $states_id,
                phone            => $fake->phone_number,
            }
        ]
    };
    return $user;
}

sub products {
    my $sku = shift;

    #generate product parents
    my ( $name, $uri, $short_description, $description ) = data($sku);
    my $product = {
        sku               => $sku,
        name              => $name,
        short_description => $short_description,
        description       => $description,
        price             => price(),
        uri               => $uri,
        canonical_sku     => undef,
        weight            => weight()
    };
    return $product;
}

#generate product variants
sub variants {
    my ( $product, $no_colors ) = @_;
    my $max_children_no = $no_colors || rand_int( 0, $#{ colors() } - 1 );
    my @variants;
    my $colors = unique_colors($max_children_no);
    foreach ( @{$colors} ) {
        my $color = $_;
        my $sizes = size();
        for my $size ( @{$sizes} ) {
            my $size_letter = lc( substr( $size->{'title'}, 0, 1 ) );
            my $sku =
              join( "-", $product->{'sku'}, $color->title, $size_letter );
            my $variant = {
                sku   => $sku,
                color => $color->value,
                size  => $size->{'value'},
                name  => join( " ",
                    $color->title, $size->{'title'}, $product->{'name'} ),
                uri =>
                  join( "-", $product->{'uri'}, $size_letter, $color->value ),
            };
            push( @variants, $variant );
        }
    }
    return \@variants;
}

sub navigation {
    my @navigation = (
{ navigation_id => '10', priority => '10', uri => 'fly-fishing-gear', type => 'menu', scope => 'root', description => 'Gear for fly fishing', name => 'Fly Fishing Gear' },
{ navigation_id => '20', priority => '20', uri => 'fly-fishing-gear/species', type => 'menu', scope => 'root', name => 'Species' , description => 'Fly Fishing Gear By Species'},
{ navigation_id => '30', priority => '30', uri => 'clothing', type => 'menu', scope => 'root', description => 'Clothing for fly fishing', name => 'Fly Fishing Clothing', },
{ navigation_id => '31', priority => '31', uri => 'clothing/brand', type => 'menu', scope => 'root', description => 'Clothing By Brand', name => 'Fly Fishing Clothing By Brand', },
{ navigation_id => '40', priority => '40', uri => 'fly-tying', type => 'menu', scope => 'root', description => 'Materials for fly tying', name => 'Fly Tying Materials and Supplies'},
{ navigation_id => '50', priority => '50', uri => 'flies', type => 'menu', scope => 'root', description => 'Flies for fishing', name => 'Fly Fishing Flies'},
{ navigation_id => '60', priority => '60', uri => 'gifts', type => 'menu', scope => 'root', description => 'Gifts for fishing', name => 'Fly Fishing Gifts'},
{ navigation_id => '70', priority => '70', uri => 'sale', type => 'menu', scope => 'root', description => 'Fly Fishing Gear on Sale!', name => 'Fly Fishing Gear on Sale!'},
# sub cats neg priority to hide from menus
{ navigation_id => '90', priority => '-1', uri => 'clothing/mens', type => 'menu', scope => 'header', description => 'Clothing for fly fishing', name => "Men's Clothing", parent_id =>'30' },
# gear
{ navigation_id => '100', priority => '200', uri => 'fly-fishing-gear/fly-rods', type => 'menu', scope => 'gear', description => 'Fly Rods', name => 'Fly Rods', parent_id =>'10' },
{ navigation_id => '101', priority => '300', uri => 'fly-fishing-gear/fly-reels', type => 'menu', scope => 'gear', description => 'Fly Reels', name => 'Fly Reels', parent_id =>'10' },
{ navigation_id => '102', priority => '400', uri => 'fly-fishing-gear/fly-line', type => 'menu', scope => 'gear', description => 'Fly Line & Backing', name => 'Fly Line & Backing', parent_id =>'10' },
{ navigation_id => '103', priority => '500', uri => 'fly-fishing-gear/waders', type => 'menu', scope => 'gear', description => 'Waders', name => 'Waders', parent_id =>'10' },
{ navigation_id => '104', priority => '600', uri => 'fly-fishing-gear/wading-boots', type => 'menu', scope => 'gear', description => 'Wading Boots', name => 'Wading Boots', parent_id =>'10' },
{ navigation_id => '105', priority => '610', uri => 'fly-fishing-gear/sunglasses', type => 'menu', scope => 'gear', description => 'Sunglasses', name => 'Sunglasses', parent_id =>'10' },
{ navigation_id => '106', priority => '900', uri => 'fly-fishing-gear/fly-boxes', type => 'menu', scope => 'gear', description => 'Fly Boxes', name => 'Fly Boxes', parent_id =>'10' },
{ navigation_id => '107', priority => '1000', uri => 'fly-fishing-gear/packs-vests', type => 'menu', scope => 'gear', description => 'Packs & Vests', name => 'Packs & Vests', parent_id =>'10' },
{ navigation_id => '108', priority => '1200', uri => 'fly-fishing-gear/leader-tippet', type => 'menu', scope => 'gear', description => 'Leader & Tippet', name => 'Leader & Tippet', parent_id =>'10' },
{ navigation_id => '109', priority => '1300', uri => 'fly-fishing-gear/tools-gadgets', type => 'menu', scope => 'gear', description => 'Tools & Gadgets', name => 'Tools & Gadgets', parent_id =>'10' },
{ navigation_id => '110', priority => '700', uri => 'fly-fishing-gear/bags-luggage', type => 'menu', scope => 'gear', description => 'Bags & Luggage', name => 'Bags & Luggage', parent_id =>'10' },
{ navigation_id => '111', priority => '1500', uri => 'fly-fishing-gear/nets', type => 'menu', scope => 'gear', description => 'Landing Nets', name => 'Landing Nets', parent_id =>'10' },
{ navigation_id => '112', priority => '800', uri => 'fly-fishing-gear/watercraft-boats', type => 'menu', scope => 'gear', description => 'Watercraft & Boats', name => 'Watercraft & Boats', parent_id =>'10' },
{ navigation_id => '113', priority => '1400', uri => 'fly-fishing-gear/books-dvds', type => 'menu', scope => 'gear', description => 'Books & DVDs', name => 'Books & DVDs', parent_id =>'10' },
{ navigation_id => '114', priority => '1100', uri => 'fly-fishing-gear/fly-rod-combos', type => 'menu', scope => 'gear', description => 'Fly Rod Combos', name => 'Fly Rod Combos', parent_id =>'10' },
{ navigation_id => '115', priority => '100', uri => 'fly-fishing-gear/new', type => 'menu', scope => 'gear', name => 'New Products!' , description => 'New Fly Fishing Gear!', parent_id =>'10'},
# clothing
{ navigation_id => '200', priority => '200', uri => 'clothing/mens/jackets', type => 'menu', scope => 'clothing', name => 'Jackets', description => 'Mens Jackets and Outerwear for Fly Fishing', parent_id =>'30' },
{ navigation_id => '201', priority => '300', uri => 'clothing/mens/fleece', type => 'menu', scope => 'clothing', name => 'Fleece', description => 'Mens Fleece Layering for Fly Fishing', parent_id =>'30' },
{ navigation_id => '202', priority => '400', uri => 'clothing/mens/layering', type => 'menu', scope => 'clothing', name => 'Layering', description => 'Mens Base Layerting for Fly Fishing', parent_id =>'30' },
{ navigation_id => '203', priority => '500', uri => 'clothing/mens/shirts', type => 'menu', scope => 'clothing', name => 'Shirts', description => 'Mens Shirts for Fly Fishing', parent_id =>'30' },
{ navigation_id => '204', priority => '600', uri => 'clothing/mens/t-shirts', type => 'menu', scope => 'clothing', name => 'T-Shirts', description => 'Mens Fly Fishing T-Shirts', parent_id =>'30' },
{ navigation_id => '205', priority => '700', uri => 'clothing/mens/pants', type => 'menu', scope => 'clothing', name => 'Pants', description => 'Mens Fly Fishing Pants', parent_id =>'30' },
{ navigation_id => '206', priority => '800', uri => 'clothing/mens/underwear', type => 'menu', scope => 'clothing', name => 'Underwear', description => 'Mens Fly Fishing Underwear', parent_id =>'30' },
{ navigation_id => '207', priority => '900', uri => 'clothing/mens/hats', type => 'menu', scope => 'clothing', name => 'Hats', description => 'Mens Fly Fishing Hats', parent_id =>'30' },
{ navigation_id => '208', priority => '1000', uri => 'clothing/mens/wba-logo', type => 'menu', scope => 'clothing', name => 'WBA Logo Gear', description => 'WBA Logo Gear', parent_id =>'30' },
{ navigation_id => '209', priority => '1100', uri => 'clothing/mens/shorts', type => 'menu', scope => 'clothing', name => 'Shorts', description => 'Mens Fly Fishing Shorts', parent_id =>'30' },
{ navigation_id => '210', priority => '1200', uri => 'clothing/mens/socks', type => 'menu', scope => 'clothing', name => 'Socks', description => 'Mens Fly Fishing Socks', parent_id =>'30' },
# clothing by brand
{ navigation_id => '300', priority => '100', uri => 'clothing/patagonia', type => 'menu', scope => 'clothing-brand', name => 'Patagonia', description => 'Patagonia Clothing', parent_id =>'31' },
{ navigation_id => '301', priority => '200', uri => 'clothing/simms', type => 'menu', scope => 'clothing-brand', name => 'Simms', description => 'Simms Clothing', parent_id =>'31' },
{ navigation_id => '302', priority => '300', uri => 'clothing/orvis', type => 'menu', scope => 'clothing-brand', name => 'Orvis', description => 'Orvis Clothing', parent_id =>'31' },
{ navigation_id => '303', priority => '400', uri => 'clothing/wba', type => 'menu', scope => 'clothing-brand', name => 'West Branch Angler', description => 'WBA Clothing', parent_id =>'31' },
# tying
{ navigation_id => '400', priority => '100', uri => 'fly-tying/tools', type => 'menu', scope => 'tying', name => 'Fly Tying Tools', description => 'Fly Tying Tools', parent_id =>'40' },
{ navigation_id => '401', priority => '200', uri => 'fly-tying/dubbing', type => 'menu', scope => 'tying', name => 'Fly Tying Dubbing', description => 'Fly Tying Dubbing', parent_id =>'40' },
{ navigation_id => '402', priority => '300', uri => 'fly-tying/hooks', type => 'menu', scope => 'tying', name => 'Fly Tying Hooks', description => 'Fly Tying Hooks', parent_id =>'40' },
# flies
{ navigation_id => '500', priority => '200', uri => 'flies/dry-flies', type => 'menu', scope => 'flies', name => 'Dry Flies', description => 'Dry Flies', parent_id =>'50' },
{ navigation_id => '501', priority => '300', uri => 'flies/nymphs', type => 'menu', scope => 'flies', name => 'Nymphs', description => 'Nymphs', parent_id =>'50' },
{ navigation_id => '502', priority => '400', uri => 'flies/streamers', type => 'menu', scope => 'flies', name => 'Streamers', description => 'Streamers', parent_id =>'50' },
# species
{ navigation_id => '600', priority => '100', uri => 'fly-fishing-gear/trout', type => 'menu', scope => 'species', name => 'Trout' , description => 'Fly Fishing Gear for Trout', parent_id =>'20'},
{ navigation_id => '601',priority => '200', uri => 'fly-fishing-gear/steelhead', type => 'menu', scope => 'species', name => 'Steelhead' , description => 'Fly Fishing Gear for Steelhead', parent_id =>'20'},
{ navigation_id => '602', priority => '300', uri => 'fly-fishing-gear/salmon', type => 'menu', scope => 'species', name => 'Salmon' , description => 'Fly Fishing Gear for Salmon', parent_id =>'20'},
{ navigation_id => '603', priority => '400', uri => 'fly-fishing-gear/bonefish-permit', type => 'menu', scope => 'species', name => 'Bonefish - Permit' , description => 'Fly Fishing Gear for Bonefish and Permit', parent_id =>'20'},
{ navigation_id => '604', priority => '500', uri => 'fly-fishing-gear/tarpon', type => 'menu', scope => 'species', name => 'Tarpon' , description => 'Fly Fishing Gear for Tarpon', parent_id =>'20'},
{ navigation_id => '605', priority => '600', uri => 'fly-fishing-gear/striped-bass', type => 'menu', scope => 'species', name => 'Stripers' , description => 'Fly Fishing Gear for Striped Bass', parent_id =>'20'},
# manufactures
{ uri => 'simms', scope => 'manf', type => 'manufacturer', name => 'Simms Fishing Products', description => 'Simms', priority => '1' },
{ uri => 'patagonia', scope => 'manf', type => 'manufacturer', name => 'Patagonia', description => 'Patagonia', priority => '2'},
{ uri => 'orvis', scope => 'manf', type => 'manufacturer', name => 'The Orvis Company', description => 'Orvis', priority => '3' },
{ uri => 'sage', scope => 'manf', type => 'manufacturer', name => 'Sage', description => 'Sage', priority => '4' },
{ uri => 'scott', scope => 'manf', type => 'manufacturer', name => 'Scott', description => 'Scott', priority => '5' },
{ uri => 'loomis', scope => 'manf', type => 'manufacturer', name => 'Loomis', description => 'Loomis', priority => '6' },
{ uri => 'hatch', scope => 'manf', type => 'manufacturer', name => 'Hatch Outdoors', description => 'Hatch Outdoors', priority => '7' },
#nav
{ uri => 'contact', type => 'menu', scope => 'nav-top', description => 'Contact', name => 'Contact', priority => '1' },
{ uri => 'about-us', type => 'menu', scope => 'nav-top', description => 'About Us', name => 'About Us', priority => '1' },
{ uri => 'shipping', type => 'menu', scope => 'nav-top', description => 'Shipping', name => 'Shipping', priority => '1' },
{ uri => 'login', type => 'nav', scope => 'top-login', description => 'Login', name => 'Login', priority => '1' },
{ uri => 'registration', type => 'nav', scope => 'top-login', description => 'Sign Up', name => 'Sign Up', priority => '1' },
{ priority => '1300', uri => 'logout', type => 'nav', scope => 'top-logout', name => 'Logout', description => 'Logout' },
{ priority => '1400', uri => 'user/account', type => 'nav', scope => 'top-logout', name => 'My Account', description => 'My Account' },
]);
 

    return \@navigation;
}

sub colors {
    my $color_names = [$meta->name('colours', 0)];
    debug "Colors: ", $color_names;
    my @colors;
    my $rset = $shop_schema->resultset('AttributeValue');
    foreach ( @{$color_names} ) {
        next if $rset->search({value=>$_})->count;
        my $color->{value} = $_;
        ($color->{title} = $_) =~ s/_/ /g;
        push( @colors, $color );
    }
    @colors = sort { $a->{'value'} cmp $b->{'value'} } @colors;
    return \@colors;
}

sub size {
    my $size = [
        { value => 'small',  title => 'Small',  priority => 2 },
        { value => 'medium', title => 'Medium', priority => 1 },
        { value => 'large',  title => 'Large',  priority => 0 },
    ];
    return $size;
}

sub orders {
    my $userid           = shift;
    my $shipping_address = shop_address->search(
        {
            'users_id' => $userid,
            'type'     => 'shipping',
        }
    )->first;

    my $billing_address = shop_address->search(
        {
            'users_id' => $userid,
            'type'     => 'billing',
        }
    )->first;

    unless ( $shipping_address && $billing_address ) {
        return 1;
    }

    my $products = $shop_schema->resultset('Product')->search(
        {
            'canonical_sku' => undef,
        }
    );

    my ( @orderlines, $count );
    my ( $weight,     $subtotal );
    while ( my $product = $products->next ) {
        my $rand_int = rand_int( 1, $products->count - 1 );
        $count++;
        if ( $count == $rand_int ) {
            my $rand = rand_int( 3, 20 );
            $weight   = $product->weight;
            $subtotal = $product->price * $rand;
            push @orderlines,
              {
                sku               => $product->sku,
                order_position    => $_,
                name              => $product->name,
                short_description => $product->short_description,
                description       => $product->description,
                weight            => $product->weight,
                quantity          => $rand,
                price             => $product->price,
                subtotal          => $subtotal,
              };
        }
        $subtotal += $subtotal || 0;
        $weight   += $weight   || 0;
    }
    my $date = $fake->date_this_year;
    $date =~ s/T/ /g;
    my $order_data = shop_order->create(
        {
            order_date            => $date,
            users_id              => $userid,
            billing_addresses_id  => $billing_address->id,
            shipping_addresses_id => $shipping_address->id,
            weight                => $weight,
            subtotal              => $subtotal,
            Orderline             => \@orderlines,
        }
    );

    $date =~ s/\D//g;
    $order_data->update(
        {
                order_number => 'UID'
              . $userid
              . $date . 'OID'
              . $order_data->orders_id,
        }
    );
}

sub height {
    my $height = [
        { value => '10', title => '10cm' },
        { value => '20', title => '20cm' },
        { value => '30', title => '30cm' },
        { value => '40', title => '40cm' },
        { value => '50', title => '50cm' },
    ];
    return $height;
}

sub data {
    my $sku = shift;
    my ( $name, $uri, $short_description, $description );
    $name = join( " ", map $fake->meta, 1..3 );
    $name =~ s/_/ /g;
    $uri = join( "-", $sku, $name );
    $uri =~ s/ /-/g;
    $name = ucfirst($name);
    $short_description = join( " ", $name, map $fake->meta, 1..20 );
    $short_description =~ s/_/ /g;
    $description = join(" ", $meta->name('loremipsum', 0));
    return ( $name, $uri, $short_description, $description );
}

sub price {
    my ($price);
    $price = parse(q{ FLOAT (9) [0-9]{3} . [1-9]{2}})->get_unique_data(1);
    return sprintf( "%.2f", $price->[0] );
}

sub weight {
    my ($weight);
    $weight = parse(q{INT [1-99]})->get_unique_data(1);
    return $weight->[0];
}

sub rand_int {
    my ( $x, $y ) = @_;
    my $rand = int( rand( $y - $x + 1 ) ) + $x;
    return $rand;
}

sub unique_colors {
    my $array_size = shift;
    my @colors     = $shop_schema->resultset('Attribute')->search(
        {
            'name' => 'color',
        },
    )->search_related('attribute_values');

    print STDERR "\n" . scalar @colors . "\n";
    my $rand =
      parse( "INT [0-" . ( $#colors - 1 ) . "]" )->get_unique_data($array_size);
    my @unique_colors;
    foreach ( @{$rand} ) {
        push( @unique_colors, $colors[$_] );
    }
    @unique_colors = sort { $a->value cmp $b->value } @unique_colors;
    return \@unique_colors;
}

sub uniqe_varchar {
    my $count = shift;

    #generate uniqe varchar list
    my $data    = parse(q{VC(10) [A-Z][1-14][a-z][2579]{4}[A-Z][14]{2}});
    my $freedom = $data->get_degrees_of_freedom();
    if ( $freedom < $count ) {
        die "Max unique value count exceeded. Please set value below $freedom.";
    }
    my $varchars = $data->get_unique_data($count);
    return $varchars;
}
1;

