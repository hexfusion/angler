package Angler;
use Dancer ':syntax';
use Dancer::Plugin::Ajax;
use Dancer::Plugin::Auth::Extensible;

$ENV{EASYPOST_API_KEY} = config->{easypost}->{development};

use Dancer::Plugin::Form;
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
use Angler::Routes::Account;
use Angler::Routes::Blog;
use Angler::Routes::Cart;
use Angler::Routes::Checkout;
use Angler::Routes::Contact;
use Angler::Routes::Review;
use Angler::Routes::Search;
use Angler::Routes::Validator;

use Angler::Cart;
use Angler::SearchResults;
use Angler::Shipping;

use Data::Transpose::Iterator::Scalar;
use Template::Flute::Iterator::JSON;
use List::Util qw(first max);
use POSIX qw/ceil/;
use URL::Encode qw/url_decode_utf8/;
use DateTime;


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

    # add various things into cart product 'extra' attribute
    foreach my $cart_product ( @{$tokens->{cart}} ) {
        my $product = shop_product( $cart_product->{sku} );
        my $image = $product->image_75x75;

        # check if canonical has image
        unless ($image) {
            if ($product->canonical){
                $image = $product->canonical->image_75x75;
            }
        }
        # set default image
        $image = shop_media->find(uri => config->{default_image}->{uri})->image_75x75 unless $image;
        $cart_product->set_extra( image => $image ) if $image;

        # availability
        $cart_product->set_extra( availability => $product->availability );
        $cart_product->set_extra( lead_time_min_days => $product->lead_time_min_days );
        $cart_product->set_extra( lead_time_max_days => $product->lead_time_max_days );
        $cart_product->set_extra( manufacturer_quantity => $product->manufacturer_quantity );
    }

    if ( var('login_failed') && request->referer !~ /login$/ ) {
        flash error => "Login failed. Username or password incorrect.";
    }

    # return_url for redirect after successful login
    if ( !logged_in_user && request->uri !~ /login$/ ) {
        my $url = uri_for( request->uri, params('query') );
        session return_url => "$url";
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

        # fly fishing gear by species has no navigation_products since it
        # is handled via solr search
        my $menu = $nav->search(
            {
                'me.type' => 'menu',
                'me.scope' => $record->scope,
                -or => [
                    { 'navigation_products.navigation_id' => { '!=', undef } },
                    { 'parents.uri' => 'fly-fishing-gear/species' },
                ]
            },
            {
                join => [ 'navigation_products', 'parents' ],
                group_by => 'me.uri',
            }
        );

        my $total = $menu->count;

        # FIXME this total and others here should come from a config not hardcode
        #if ( $total > '16' ) {
            # NOTE: this number is now ignored since we calculate number
            # of rows dynamically below
            #debug "Too many records $total to display in nav menui max is 16";
        #};

        my $row = $menu;
        if ( $section == 1 || $section == 3 ) {
            # two columns for these
            $row = $menu->search( {}, { rows => int( ( $total + 1 ) / 2 ) } );
        }

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

    $tokens->{title} = $tokens->{navigation}->name;

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

    if ( !$products->has_rows ) {

        # maybe there are sub-menus that have products

        $products =
          $tokens->{navigation}->children->search_related('navigation_products')
          ->search_related('product')->active;
    }

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
    $results_handler->select_sorting;
    my $order     = $results_handler->current_sorting;
    my $direction = $results_handler->current_sorting_direction;
    # we need to prepend alias to most columns but not all
    unless ( $order =~ /^(average_rating|selling_price)$/ ) {
        $order = $products->me($order);
    }
    #    debug join(' ', $tokens->{reverse_order}, "($order)", $tokens->{order_by},
    #               to_dumper($tokens->{order_by_iterator}),
    #               to_dumper($results_handler->query));

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
        uri   => "/" . $tokens->{navigation}->uri,
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
    my $related_products = $canonical_product->search_related(
        'merchandising_products',
        {
            'me.type' => 'related',
        },
      )->related_resultset('product_related')->rand->search(
        {
            'product_related.active' => 1,
        },
        {
            rows => config->{flypage}->{related_product}->{qty} || 3,
        },
      )->listing( { users_id => session('logged_in_user_id') } );

    $tokens->{related_products} = $related_products
      if $related_products->has_rows;

    # reviews
    my $review_rs = shop_product( $product->sku )
      ->reviews->search( { public => 1, approved => 1 } );

    $tokens->{review_count} =  $review_rs->count;
    $tokens->{review_link} = '/review/' . $product->sku;

    $tokens->{review_avg} = $product->average_rating;

    debug "review avg: ", $tokens->{review_avg};

    my ( $author, $label_type, $label );

    while (my $review = $review_rs->next) {

        if ($review->author) {

            $author = $review->author->nickname;

            if ( $review->author->username =~ /^anonymous/ ) {
                $label_type = 'label-standard';
                $label = 'Unregistered User';
            }
            else {
                $label_type = 'label-primary';
                $label      = 'WBA User';

                # is the user part of the pro role?
                if ( $review->author->roles->find( { name => 'pro' } ) ) {
                    $label_type = 'label-success';
                    $label      = 'Pro Reviewer';
                }
            }
        }
        # anon
        else {
            $author = "Anonymous";
            $label_type = 'label-default';
            $label = 'Unregistered User';
        }

        push @reviews, {
                    content => $review->content,
                    rating => $review->rating,
                    recommend => $review->recommend,
                    title => $review->title,
                    label => { type => $label_type, name => $label },
                    author => $author,
                    date => $review->created->strftime("Created on %B %d %Y")
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

=head1 METHODS

=cut

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
    my $mf = shop_navigation->search({type => 'manufacturer'});

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

get '/account/orders/print' => sub {
    template 'account/orders/print', {}, { layout => undef};
};

get '/account/address/edit' => sub {
    template 'account/address/edit';
};

get '/account/address/new' => sub {
    template 'account/address/new';
};

any [ 'get', 'post' ] => '/cart' => sub {

    pass if ( param('remove') || param('update') );

    # TODO: this also needs to check whether the variant is valid otherwise
    # we get the flash message even when a valid variant is added to the cart

    pass; # until fixed^^

    if ( my $sku = param('sku') ) {
        my $product = shop_product($sku);
        pass unless $product;

        if ( !$product->canonical_sku && $product->has_variants ) {

            # this will cause a redirect to product instead of adding to cart
            # so add a flash message before handing on control
            flash info => "This product has several variants. Please choose the options you would like and then add it to the cart.";
        }
    }
    pass;
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

            # if default image for variant check product for an actual image
            if($variant->image_325x325 ne 
              '/products/images/325x325/default.jpg' ) {
                $response{src} = $variant->image_325x325;
            }

                $response{availability} = $variant->availability;
                $response{name}         = $variant->name unless $is_canonical;
                $response{price}        = $variant->price;
                $response{selling}      = $variant->selling_price
                  if $variant->price > $variant->selling_price;
            }

            $response{name}  = $product->name          unless $response{name};
            $response{price} = $product->price         unless $response{price};
            $response{src}   = $product->image_325x325 unless $response{src};
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

=head2 ajax /states_for_country

Called with single param 'code' which should be a valid country iso code.
Returns a hash reference of states_id => name in values if found.

=cut

ajax '/states_for_country' => sub {
    my $country_iso_code = param 'code';
    my %response;
    if ($country_iso_code) {

        my %states =
          map { $_->{states_id} => $_->{name} } shop_state->search(
            { country_iso_code => $country_iso_code, active => 1 },
            { order_by => "name", columns => [qw/states_id name/] }
          )->hri->all;

        if (%states) {
            $response{type}   = "success";
            $response{values} = \%states;
        }
    }
    content_type('application/json');
    to_json(\%response);
};

=head2 ajax /states_id_for_zipcode

Called with a single param 'zipcode' which should be a valid US zipcode
searches on the first three digits for a zone named "US postal $zip3".
Returns the states_id if found.

=cut

ajax '/states_id_for_zipcode' => sub {
    my $zip = param 'zipcode';
    my %return = ( type => "fail" );

    if ( $zip && $zip =~ /^(\d{3})/ ) {
        if ( my $zone =
            shop_schema->resultset('Zone')->find( { zone => "US postal $1" } ) )
        {
            if ( my $state = $zone->states->single ) {
                %return = (
                    type      => "success",
                    states_id => $state->states_id
                );
            }
        }
    }

    content_type('application/json');
    to_json(\%return);
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
