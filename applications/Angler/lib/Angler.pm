package Angler;
use Dancer ':syntax';
use Dancer::Plugin::Ajax;
use Dancer::Plugin::Auth::Extensible;

$ENV{EASYPOST_API_KEY} = config->{easypost}->{development};

use Dancer::Plugin::Form;
use Angler::Schema; # load before Dancer::Plugin::Interchange6
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Interchange6::Routes;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Auth::Extensible qw(
logged_in_user authenticate_user user_has_role require_role
require_login require_any_role user_roles
);
use Dancer::Plugin::FlashNote;

use Angler::Plugin::History;
use Angler::Routes::About;
use Angler::Routes::Blog;
use Angler::Routes::Account;
use Angler::Routes::Checkout;
use Angler::Routes::Contact;
use Angler::Routes::Review;
use Angler::Routes::Search;
use Angler::Cart;
use Angler::SearchResults;

use Data::Transpose::Iterator::Scalar;
use Template::Flute::Iterator::JSON;
use List::Util qw(first max);
use POSIX qw/ceil/;
use URL::Encode qw/url_decode_utf8/;
use DateTime;

use Angler::Shipping;

our $VERSION = '0.1';

# debug dbic 
# schema->storage->debugfh(IO::File->new('/tmp/trace.out', 'w'));

# connect DBIC session engine to our schema
set session_options => {schema => schema};

=head1 COMMON TOKENS

This is a list of common tokens, which should be used exclusively.

=over 4

=item cart_subtotal

Cart subtotal.

=item cart_shipping

Shipping cost.

=item cart_tax

Tax amount.

=item cart_total

Total amount of order.

=back

=head1 HOOKS

We make use of the following hooks.

=head2 before_template_render

=cut

hook 'before_template_render' => sub {
    my $tokens = shift;

    # make cart details available
    my $cart = cart;
    $tokens->{cart} = $cart->products;
    $tokens->{cart_count} = $cart->quantity;
    $tokens->{cart_subtotal} = $cart->subtotal;
    $tokens->{cart_total} = $cart->total;

    # add images into 'extra' attribute
    foreach my $product ( @{$tokens->{cart}} ) {
        my $image = shop_product( $product->{sku} )->image_75x75;
        $product->set_extra( image => $image ) if $image;
    }
};

=head2 before_layout_render

=cut

hook 'before_layout_render' => sub {
    my $tokens = shift;
    my $action ='';
    my $scope = '';
    my $record;

    my $flash_flush = flash_flush;
    $tokens->{flash} = {};
    foreach my $flash (@$flash_flush) {
        push @{ $tokens->{flash}->{ $flash->[0] } }, { message => $flash->[1] };
    }

    $tokens->{"canonical-url"} = uri_for( request->path )
      unless $tokens->{"canonical-url"};

    # logo
    $tokens->{logo_uri} = '/';

    # logged in user?
    $tokens->{logged_in_user} = session('logged_in_user_id');

    # setup basic history for testing
    # we ignore 404 because if you have a missing file in
    # template it throws a 404..
    unless (request->path eq '404') {
        debug "pass ";
        session last_page => session('current_page') || 'none';
        session current_page => request->path;
    }

    $tokens->{'current_page'} = session('current_page');
    $tokens->{'last_page'} = session('last_page');

    my $nav = shop_navigation;

    # build menu sections for mega-drop

    # select the unique menu scopes and order by parents priority
    my $menus = $nav->search(
        {
            'me.type' => 'menu',
            # null parent_id means record is a header
            'me.parent_id' => { '!=', undef },
            # we use negative numbers for sub categories we don't want in menu..
            'me.priority' => {'>=' => '0'}
        },
        {
            join => 'parents',
            # we don't need all these fields but to debug they are useful
            select => [ 'me.scope', 'me.navigation_id', 'me.name', 'me.parent_id', 'parents.priority' ],
            as => [ 'scope', 'navigation_id', 'name', 'parent_id', 'priority'],
            group_by => [ qw/scope priority/ ],
            order_by => { -asc => 'priority' }
        }
    );

    my $section=0;

    while ($record = $menus->next ) {
       $section++;

        my $menu = $nav->search(
            {
                'type' => 'menu',
                'scope' => $record->scope
            },
        );

        my $total = $menu->count;

        # FIXME this total and others here should come from a config not hardcode
        if ( $total > '16' ) {
            debug "Too many records $total to display in nav menui max is 16";
        };

        # set number of records per row to return
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
};

=head2 before_navigation_search

This hooks replaces the standard L<Dancer::Plugin::Interchange6::Routes>
navigation route to enable us to alter product listing items per page on 
the fly and sort order.

=cut

hook 'before_navigation_search' => sub {
    my $tokens = shift;
    my $routes_config = config->{plugin}->{'Interchange6::Routes'};
    my $products;

    # an interesting page
    add_to_history(
        type  => 'navigation',
        title => $tokens->{navigation}->name
    );

    my %query = params('query');

    my $schema = shop_schema;

    # FIXME I believe this only goes 1 level of children

    # 2015-02-07
    # SysPete: this makes a LOT of bad assumptions about how we create and
    # manage nav. For now use something simpler.
    #
    # if this is the root category then show all the childrens products.
    #if ( $tokens->{navigation}->is_root == 1 ) {
    #    $products =
    #      $tokens->{navigation}->children->search_related('navigation_products')
    #      ->search_related('product')->active;
    #}
    #else {
    #    $products =
    #      $tokens->{navigation}->navigation_products->search_related('product')
    #      ->active;
    #}

    $products =
      $tokens->{navigation}->navigation_products->search_related('product')
      ->active;

    # find facets in query params

    my %query_facets = map {
        $_ =~ s/^f\.//
          && url_decode_utf8($_) =>
              [ split( /\!/, url_decode_utf8( $query{"f.$_"} ) ) ]
    } grep { /^f\./ } keys %query;

    # setup search results handler
    my $results_handler = Angler::SearchResults->new(
        routes_config => config->{plugin}->{'Interchange6::Routes'} || {},
        tokens => $tokens,
        query => \%query,
    );

    # select view
    $results_handler->select_view;

    # add different views to template tokens
    $tokens->{views} = $results_handler->views;

    # rows (products per page)
    my $view = $results_handler->current_view;
    my $rows = $query{rows};
    if ( !defined $rows || $rows !~ /^\d+$/ ) {
        $rows = $routes_config->{navigation}->{records} || 10;
    }

    my @rows_iterator;
    if ( $view eq 'grid' ) {
        $rows = ceil($rows/3)*3;
        $tokens->{per_page_iterator} = [ map { +{ value => 12 * $_ } } 1 .. 4 ];
    }
    else {
        $tokens->{per_page_iterator} = [ map { +{ value => 10 * $_ } } 1 .. 4 ];
    }
    $tokens->{per_page} = $rows;

    # order

    my @order_by_iterator = (
        { value => 'priority',       label => 'Position' },
        { value => 'average_rating', label => 'Rating' },
        { value => 'selling_price',  label => 'Price' },
        { value => 'name',           label => 'Name' },
        { value => 'sku',            label => 'SKU' },
    );
    $tokens->{order_by_iterator} = \@order_by_iterator;

    my $order     = $query{order};
    my $direction = $query{dir};

    # maybe set default order(_by)
    if (   !defined $order
        || !grep { $_ eq $order } map { $_->{value} } @order_by_iterator )
    {
        $order = 'priority';
    }
    $tokens->{order_by} = $order;

    # maybe set default direction
    if ( !defined $direction || $direction !~ /^(asc|desc)/ ) {
        if ( $order =~ /^(average_rating|priority)$/ ) {
            $direction = 'desc';
        }
        else {
            $direction = 'asc';
        }
    }

    # we need to prepend alias to most columns but not all
    unless ( $order =~ /^(average_rating|selling_price)$/ ) {
        $order = $products->me($order);
    }

    # asc/desc arrow
    if ( $direction eq 'asc' ) {
        $tokens->{reverse_order} = 'desc';
        $tokens->{order_by_glyph} =
          q(<span class="fa fa-long-arrow-up"></span>);
    }
    else {
        $tokens->{reverse_order} = 'asc';
        $tokens->{order_by_glyph} =
          q(<span class="fa fa-long-arrow-down"></span>);
    }

    # Filter products based on facets in query params if there are any.
    # This loopy query stuff is terrible - should be a much better way
    # to do this but I haven't found one yet that is as fast.

    if ( keys %query_facets ) {

        my @skus = $products->get_column($products->me('sku'))->all;

        # attribute facets

        foreach my $key ( keys %query_facets ) {

            @skus = $schema->resultset('Product')->search(
                {
                    -and => [
                        'product.sku' => { -in => \@skus },
                        -or      => [
                            -and => [
                                'attribute.name' => $key,
                                'attribute_value.value' =>
                                  { -in => $query_facets{$key} }
                            ],
                            -and => [
                                'attribute_2.name' => $key,
                                'attribute_value_2.value' =>
                                  { -in => $query_facets{$key} }
                            ]
                        ]
                    ]
                },
                {
                    alias => 'product',
                    columns => [ 'product.sku' ],
                    join  => [
                        {
                            product_attributes => [
                                'attribute',
                                {
                                    product_attribute_values =>
                                      'attribute_value'
                                }
                            ]
                        },
                        {
                            variants => {
                                product_attributes => [
                                    'attribute',
                                    {
                                        product_attribute_values =>
                                          'attribute_value'
                                    }
                                ]
                            }
                        }
                    ],
                },
            )->get_column('product.sku')->all;
        }

        $products = $schema->resultset('Product')->search(
            {
                'product.sku' => { -in => \@skus }
            },
            {
                alias => 'product',
            }
        );
    }

    # pager needs a paged version of the products result set

    my $paged_products = $products->limited_page( $tokens->{page}, $rows );
    my $pager = $paged_products->pager;

    if ( $tokens->{page} > $pager->last_page ) {
        # we're past the last page which happens a lot if we start on a high
        # page then results are restricted via facets so reset the pager
        $tokens->{page} = $pager->last_page;
        $paged_products = $products->limited_page( $tokens->{page}, $rows );
        $pager = $paged_products->pager;
    }
    $tokens->{pager} = $pager;

    # facets

    # start with facets from attributes

    # TODO: add price and brand facets

    my $cond = {
        'attribute.name' => { '!=' => undef }
    };

    # different condition if we have any query facets
    $cond = {
        -or => [
            'attribute.name' => { -not_in => [ keys %query_facets ] },
            map {
                -and => [
                    { 'attribute.name' => $_ },
                    {
                        'attribute_value.value' => { -in => $query_facets{$_} }
                    }
                  ]
            } keys %query_facets
        ]
    } if keys %query_facets;

    # start with canonical
    my $attrs = {
        join => {
            product_attributes => [
                'attribute', { product_attribute_values => 'attribute_value' }
            ]
        },
        columns    => [ 'product.sku' ],
        '+columns' => [
            { name  => 'attribute.name' },
            { priority => 'attribute_value.priority' },
            { value => 'attribute_value.value' },
            { title => 'attribute_value.title' },
        ],
    };
    my $facet_list_rset1 = $products->search( $cond, $attrs );

    # now variants
    $attrs->{join} = {
        variants => {
            product_attributes => [
                'attribute', { product_attribute_values => 'attribute_value' }
            ]
        }
    };
    my $facet_list_rset2 = $products->search( $cond, $attrs );

    # union our two sets together and complete the query
    my @facet_list = $facet_list_rset1->union($facet_list_rset2)->search(
        undef,
        {
            '+columns' =>
              { count => { count => { distinct => 'product.sku' } } },
            order_by => [
                { -asc => 'product.name' },
                { -desc => 'product.priority' },
                { -asc  => 'product.title' },
            ],
            group_by => [
                "product.name",        "product.value",
                "product.title", "product.priority",
            ],

        }
    )->hri->all;

    # now we need the facet groups (name, title & priority)
    # this can also be rather expensive
    # TODO: maybe we can grab names from @facet_list instead of this?
    my $facet_group_rset1 = $products->search(
        { 'attribute.name' => { '!=' => undef } },
        {
            join       => { product_attributes => 'attribute' },
            columns    => [],
            '+columns' => {
                name     => 'attribute.name',
                title    => 'attribute.title',
                priority => 'attribute.priority',
            },
            distinct => 1,
        }
    );
    my $facet_group_rset2 = $products->search(
        { 'attribute.name' => { '!=' => undef } },
        {
            join       => { variants => { product_attributes => 'attribute' }},
            columns    => [],
            '+columns' => {
                name     => 'attribute.name',
                title    => 'attribute.title',
                priority => 'attribute.priority',
            },
            distinct => 1,
        }
    );

    my $facet_group_rset =
      $facet_group_rset1->union($facet_group_rset2)
      ->distinct( $products->me('name') )->order_by(
        [
            { -desc => $products->me('priority') },
            { -asc  => $products->me('title') }
        ]
      );

    # now construct facets token
    my @facets;
    my %seen;
    while ( my $facet_group = $facet_group_rset->next ) {
        # it could in theory be possible to have two attributes with the same
        # name in the facet groups list so we skip if we've seen it before
        unless ( $seen{$facet_group->name} ) {

            my $data;
            my @results = grep { $_->{name} eq $facet_group->name } @facet_list;
            $data->{title} = $facet_group->get_column('title');

            $data->{values} = [ map {
                {
                    name  => $facet_group->name,
                    value => $_->{value},
                    title => $_->{title},
                    count => $_->{count},
                    unchecked => 1, # cheaper to use param than container
                }
            } @results ];

            if ( defined $query_facets{ $facet_group->name } ) {
                foreach my $value ( @{ $data->{values} } ) {
                    if ( grep { $_ eq $value->{value} }
                      @{ $query_facets{ $facet_group->name } } ) {
                        $value->{checked} = "yes";
                        delete $value->{unchecked};
                    }
                }
            }
            push @facets, $data;
        }
    }
    $tokens->{facets} = \@facets;

    # paging

    my $paging = Angler::Paging->new(
        pager => $pager,
        uri   => $tokens->{navigation}->uri,
        query => \%query,
    );

    $tokens->{pagination} = $paging->page_list;
    $tokens->{pagination_previous} = $paging->previous_uri;
    $tokens->{pagination_next} = $paging->next_uri;

    # product listing using paged_products result set

    my $listing = [
        $paged_products->listing(
            { users_id => session('logged_in_user_id') }
          )->group_by(
            [
                map { $paged_products->me($_) } (
                    'sku',               'name',
                    'uri',               'price',
                    'short_description', 'canonical_sku'
                )
            ]
          )->order_by( { "-$direction" => [$order] } )->all
    ];

    # I don't the the following is needed for Sam's grid design...
#    if ( $view eq 'grid' ) {
#        my @grid;
#        while ( scalar @products > 0 ) {
#            push @grid, +{ row => [ splice @products, 0, 3 ] };
#        }
#        $tokens->{products} = \@grid;
#    }
#    else {
#        $tokens->{products} = \@products;
#    }

    $tokens->{products} = $listing;

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

    # add extra js

    $tokens->{"extra-js-file"} = 'product-listing.js';

    Dancer::Continuation::Route::Templated->new(
        return_value => template( $tokens->{template}, $tokens ) )->throw;
};

hook 'before_product_display' => sub {
    my ($tokens) = @_;
    my $product = $tokens->{product};
    my @related_products;
    my @reviews;

    &add_recent_products( $tokens, 3 );

    my $canonical_product =
      $product->canonical_sku ? $product->canonical : $product;

    $tokens->{"canonical-url"} = uri_for( $canonical_product->uri );

    $tokens->{title} = $product->name;

    # an interesting page
    add_to_history(
        type  => 'product',
        title => $product->name,
        sku   => $product->sku
    );

    # breadcrumbs
    my $path = $canonical_product->path;
    $tokens->{breadcrumbs} = $path;

    # find related products
    $tokens->{related_products} = $canonical_product->search_related(
        'merchandising_products',
        {
            'me.type' => 'related',
        },
      )->search_related(
        'product_related',
        {
            'product_related.active' => 1,
        },
        {
            rows => config->{flypage}->{related_product}->{qty} || 3,
        },
      )->listing( { users_id => session('logged_in_user_id') });

    # reviews
    my $review_rs = shop_product($product->sku)->reviews;

    $tokens->{review_count} =  $review_rs->count;
    $tokens->{review_link} = '/review/' . $product->sku;

    $tokens->{review_avg} = $product->average_rating;

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

    $tokens->{quantity_iterator} = $qiter;

    # free shipping
    my $free_shipping_amount = config->{free_shipping}->{amount};
    
    if ($product->price > $free_shipping_amount) {
    $tokens->{free_shipping} = 1;
    }

    my $parent_product =
      $product->canonical_sku ? $product->canonical : $product;

    # find an image for this product

    my $images_rset = $product->media_products->search_related(
        'media',
        { 'media_type.type' => 'image', },
        { join              => 'media_type', rows => 1 }
    );

    if ( $images_rset->has_rows ) {

        # we have images

        $tokens->{image_src} = $product->image_325x325;
    }
    elsif ( $product->canonical_sku ) {

        # no images and we have a variant so try the parent product

        $tokens->{image_src} = $parent_product->image_325x325;
    }

    # we collect thumbs in a hash to avoid display 2 thumbs that have the same
    # image
    my %thumbs;
    my $variants = $parent_product->variants;
    while ( my $variant = $variants->next ) {
        my $images = $variant->media_by_type('image');
        while ( my $image = $images->next ) {
            my $src = $image->display_uri('product_100x100');

            $thumbs{$src} = {
                src  => $src,
                href => $variant->uri,
                sku  => $variant->sku,
            };
        }
    }
    $tokens->{thumbs} = [ values %thumbs ];

    my $video = $product->media_by_type('video')->first;

    if ($video) {
        $tokens->{video_src} = $video->uri;
    }
    # add extra js

    $tokens->{"extra-js-file"} = 'product-page.js';

};

hook 'before_cart_display' => sub {
    my ($values) = @_;
    # we get cart in tokens
    my $cart = cart;
    my $subtotal = $cart->subtotal;
    my $free_shipping_amount = config->{free_shipping}->{amount};
    my $free_shipping_gap;

    $values->{title} = "Cart";

    # determine whether shipping is free or determine missing amount
    if ($free_shipping_amount > $subtotal) {
        $values->{free_shipping_gap} = $free_shipping_amount - $subtotal;
    }
    else {
        $values->{free_shipping} = 1;
    }

    $values->{countries} = Angler::Shipping::deliverable_countries(shop_schema);

    my $form = form('shipping-quote');
    my $form_values = $form->values('session');

    if (logged_in_user) {
        # retrieve shipping address
        #TODO this should be a search and a dropdown to select if multiple
        my $ship_adr = shop_address->search(
            {
                users_id => session('logged_in_user_id'),
                type => 'shipping',
            },
            {
                order_by => {-desc => 'last_modified'},
                rows => 1,
            },
        )->single;

        if ($ship_adr) {
            debug "user_address: Shipping address found: ", $ship_adr->id;

            $form_values->{postal_code} ||= $ship_adr->postal_code;
            $form_values->{country} ||= $ship_adr->country_iso_code;

            debug "user postal code ", $form_values->{postal_code};
            debug "user country ", $form_values->{country};
        }
    }

    my $angler_cart = Angler::Cart->new(
        schema => shop_schema,
        cart => $cart,
        shipping_methods_id => session('shipping_method') || 0,
        country => $form_values->{country},
        postal_code => $form_values->{postal_code},
        user_id => session('logged_in_users_id'),
    );

    my $rates = Angler::Shipping::show_rates($angler_cart);
    $values->{shipping_methods} = [];
    if ($rates && @$rates) {
        my @shipping_rates;
        foreach my $rate (@$rates) {
            my $iref = {
                value => $rate->{carrier_service},
                label => "$rate->{service} $rate->{rate}\$",
            };
            if ($rate->{carrier_service} == $angler_cart->shipping_methods_id) {
                $iref->{checked} = 'checked';
            }

            push @shipping_rates, $iref;
        }
        $values->{shipping_methods} = \@shipping_rates;
        if (@shipping_rates) {
            $values->{show_shipping_methods} = 1;
        }
        debug "shipping methods are", $values->{shipping_methods};
    }
    else {
        debug "No rates found for: ", $form_values;
    }

    if ($form_values->{shipping_method}) {
        $values->{shipping_method} = $form_values->{shipping_method};
        debug "Shipping method is " . $form_values->{shipping_method};
    }
    $angler_cart->update_costs;

    $form_values->{country} = $angler_cart->country;
    $form_values->{postal_code} = $angler_cart->postal_code;

    $values->{cart_shipping} = $angler_cart->shipping_cost;
    $values->{cart_tax} = $angler_cart->tax;
    $values->{cart_total} = $cart->total;

    # $values->{shipping_methods} = $angler_cart->shipping_methods;

    # unless (@{$values->{shipping_methods}}) {
    # $values->{shipping_warning} = 'No shipping methods for this country/zip';
    # }

    # filling cart form
    $form->fill($form_values);
    $values->{form} = $form;
};

=head1 METHODS

=head2 add_recent_products($tokens, $quantity)

Add recent_products token containing the most recently-viewed products.

This sub must be given the current template tokens hash reference and
quantity of results wanted.

=cut

sub add_recent_products {
    my ( $tokens, $quantity ) = @_;

    return if (!defined $tokens || !defined $quantity );

    my $product_history = history->product;

    if ( defined $product_history ) {

        my %seen;
        my @skus;
        foreach my $product ( @{ $product_history } ) {

            next if $product->{path} eq request->path;

            unless ( $seen{ $product->{sku} } ) {
                $seen{ $product->{sku} } = 1;
                push @skus, $product->{sku};
            }
            last if scalar(@skus == $quantity);
        }

        my $products = schema->resultset('Product')
          ->search( { active => 1, sku => { -in => \@skus } } );

        if ( $products->has_rows ) {

            # we have some results so set the token

            $tokens->{recent_products} = [ $products->listing(
                { users_id => session('logged_in_user_id') } )->all ];
        }
    }
}

=head1 ROUTES

=cut

get '/' => sub {
    my $tokens;

    # get all manufacturers
    my $components = Template::Flute::Iterator::JSON->new(file => '/home/sam/camp10/applications/Angler/views/home/components.json');
    my $mf = shop_navigation->search({type => 'manufacturer'});
        debug "json components", $components;

    # products for homepage grid
    my $attribute = config->{homepage}->{related_product}->{attribute} || 'homepage';
    my $attribute_value = config->{homepage}->{related_product}->{attribute_value} || 'highlighted_products';
    my $rows = config->{homepage}->{related_product}->{qty} || '8';

    my $new_products = shop_product->search(
        {
            -and => [
                        'attribute.name' => $attribute,
                        'attribute_value.value' => $attribute_value
                    ],
        },
            { rows => $rows,
              join  => [
                {
                    product_attributes => [
                        'attribute',
                            {
                                product_attribute_values =>
                                'attribute_value'
                            }
                    ]
                },
           ],
       },
   );

    $tokens->{"new_products"} = $new_products;
    $tokens->{"component"} = $components;
    $tokens->{"manufacturer"} = [$mf->all];

    template 'home/content', $tokens;

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

# my orders
get '/account/orders' => sub {
    template 'account/orders/content';
};

get '/account/orders/view' => sub {
    template 'account/orders/view';
};

get '/account/orders/print' => sub {
    template 'account/orders/print', {}, { layout => undef};
};

get '/account/address/edit' => sub {
    template 'account/address/edit';
};

get '/account/address/new' => sub {
    template 'account/address/new';
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
    my $upload_dir = "uploads";
    my $upload = request->upload('filename');
    my $filename = $upload->filename;
    $upload->copy_to("$upload_dir/$filename");
 
};

ajax '/check_variant' => sub {

    # params should be sku and variant attributes only with optional quantity

    my %params = params;
    my ( $product, %response );

    my $sku = $params{sku};
    delete $params{sku};

    my $quantity = $params{quantity};
    delete $params{quantity};
    $quantity = 1 unless defined $quantity;

    my $message = "Sorry, the current selection is not available. "
      ."Please choose a different combination.";

    if ( defined $sku ) {
        if ( $product = shop_product($sku) ) {

            my $variant;
            my $is_canonical = $product->canonical_sku ? 0 : 1;

            if ( !$is_canonical || $product->variants->has_rows ) {

                $variant = $product->find_variant( \%params );

                if ( !$variant ) {
                    debug "variant not found for sku $sku with params: "
                      . \%params;
                    $response{type}    = "error";
                    $response{message} = $message;

                    content_type('application/json');
                    return to_json( \%response );
                }

                $response{name} = $variant->name unless $is_canonical;
                $response{price}   = $variant->price;
                $response{selling} = $variant->selling_price
                    if $variant->price > $variant->selling_price;
            }

            my $images_rset = $product->media_products->search_related(
                'media',
                { 'media_type.type' => 'image', },
                { join              => 'media_type', rows => 1 }
            );

            if ( $images_rset->has_rows ) {

                # we have images

                $response{src} = $product->image_325x325;
            }
            elsif ( $product->canonical_sku ) {

                # no images and we have a variant so try the parent product

                $response{src} = $product->canonical->image_325x325;
            }

            $response{name}  = $product->name  unless $response{name};
            $response{price} = $product->price unless $response{price};
            $response{type}  = "success";

            if (  !$response{selling}
                && $product->price > $product->selling_price )
            {
                $response{selling} = $product->selling_price;
            }
        }
        else {
            debug "product not found in database for sku $sku in check_variant";
            $response{type} = "error";
            $response{message} = $message;
        }
    }
    else {
        debug "no SKU received in ajax check_varian call";
        $response{type} = "error";
        $response{message} = $message;
    }

    content_type('application/json');
    to_json(\%response);
};

ajax '/variant_attribute_values' => sub {

    # param should be sku of a variant

    my ( %response, %attributes );
    my $product = shop_product(param 'sku');

    return undef unless $product;

    my $attributes_rset = $product->search_related(
        'product_attributes',
        {
            'attribute.type' => 'variant',
        },
        {
            prefetch => [
                'attribute', { product_attribute_values => 'attribute_value' },
            ],
        }
    );

    while ( my $attribute = $attributes_rset->next ) {
        $attributes{ $attribute->attribute->name } =
          $attribute->product_attribute_values->first->attribute_value->value;
    }

    $response{type} = "success";
    $response{values} = \%attributes;

    content_type('application/json');
    to_json(\%response);
};

#get '404' => sub {
#    pass if request->path =~ m{^/(css|fonts|images|js|products)/};
#    status 'not_found';
#    template 'error_pages/404' => { title => 'Not Found' };
#};

shop_setup_routes;

true;
