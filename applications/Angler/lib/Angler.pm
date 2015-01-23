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

use Angler::Routes::About;
use Angler::Routes::Blog;
use Angler::Routes::Account;
use Angler::Routes::Checkout;
use Angler::Routes::Contact;
use Angler::Routes::Review;
use Angler::Routes::Search;
use Angler::Cart;

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


=head1 HOOKS

We make use of the following hooks.

=head2 before_template_render

Maintain page history for interesting pages and add 'recent_history' token
containing uri?query of most recent interesting page in history.

The history list is a hash reference of arrays of hash references.

The hash key is set using the add_to_history var in a route. In a product
route we might do the following:

    var add_to_history =>
        { type => 'product', name => 'Interesting Product', sku => 'IP00001' };

Assuming the URI plus query string is:

  /my-interesting-product

and session history already contains:

    {
        all => [
            { name => 'Hardware', uri  => '/hardware?f.color=red' }
        ],
        navigation => [
            { name => 'Hardware', uri => '/hardware?f.color=red' }
        ],
    }

then the new history hash reference will become:

    {
        all => [
            {
                name => 'Interesting Product',
                uri  => '/my-interesting-product',
                sku  => 'IP00001',
            },
            { name => 'Hardware', uri  => '/hardware?f.color=red' }
        ],
        product => [
            {
                name => 'Interesting Product',
                uri  => '/my-interesting-product',
                sku  => 'IP00001'
            }
        ]
        navigation => [
            { name => 'Hardware', uri => '/hardware?f.color=red' }
        ],
    }

Note the special C<all> array which all history items are added to. If an
item should only be added to C<all> then simply set that as the key
for C<add_to_history>:

    var add_to_history => { type => 'all', name => 'Blog page' };

A short form using just the history type is possible thus:

    var add_to_history => 'all';

Though in this case only the URI will be stored in the history list with no
additional data such as name.

=cut

hook 'before_template_render' => sub {
    my $tokens = shift;

    # make cart details available
    my $cart = cart;
    $tokens->{cart} = $cart->products;
    $tokens->{cart_count} = $cart->quantity;
    $tokens->{cart_subtotal} = $cart->subtotal;
    $tokens->{cart_total} = $cart->total;

    my $default_image =
      schema->resultset('Media')->find( { uri => 'default.jpg' } );

    my $image_type =
      schema->resultset('MediaType')->find( { type => 'image' } );

    # add images into 'extra' attribute
    foreach my $product ( @{$tokens->{cart}} ) {
        my $image =
          shop_product( $product->{sku} )
            ->media->search( { media_types_id => $image_type->media_types_id },
              { rows => 1 } )->single;

        # set default
        $image = $default_image unless $image;

        my $display = $image_type->media_displays->find({name => 'cart'});

        $product->set_extra( image => $display->path . '/' . $image->uri );
    }

    my %history;
    my $session_history = session('history');
    if ( ref($session_history) eq 'HASH' ) {
        %history = %$session_history;
    }

    # maintain history lists

    my $var = var('add_to_history');

    if ( defined $var ) {

        my ( $key, %values );

        if ( ref($var) eq '' ) {
            $key = $var;
        }
        elsif ( ref($var) eq 'HASH' ) {
            $key = delete $var->{type};
            %values = %$var;
        }

        if ( defined $key ) {

            # all OK so add history
            $values{uri} =
              uri_for( request->path, [ params('query') ] )->path_query;

            unshift @{ $history{$key} }, \%values unless $key eq 'all';
            unshift @{ $history{all} }, \%values;

            # keep max 20 items in each history list and put back in session
            foreach my $key ( keys %history ) {
                pop @{ $history{$key} } if scalar @{ $history{$key} } > 20;
            }
            session history => \%history;
        }
    }

    # add token with most recent history entry

    $tokens->{recent_history} = $history{all}[0];
};

=head2 before_layout_render

=cut

hook 'before_layout_render' => sub {
    my $tokens = shift;
    my $action ='';
    my $scope = '';
    my $record;

    # logo
    $tokens->{logo_uri} = '/';

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
    my $products;

    # an interesting page
    var add_to_history =>
      { type => 'navigation', name => $tokens->{navigation}->name };

    my %query = params('query');

    my $schema = shop_schema;

    # FIXME I believe this only goes 1 level of children

    # if this is the root category then show all the childrens products.
    if ( $tokens->{navigation}->is_root == 1 ) {
        $products =
          $tokens->{navigation}->children->search_related('navigation_products')
          ->search_related('product')->active;
    }
    else {
        $products =
          $tokens->{navigation}->navigation_products->search_related('product')
          ->active;
    }

    # find facets in query params

    my %query_facets = map {
        $_ =~ s/^f\.//
          && url_decode_utf8($_) =>
              [ split( /\!/, url_decode_utf8( $query{"f.$_"} ) ) ]
    } grep { /^f\./ } keys %query;

    # determine which view to display

    my @views = (
        {
            name => 'grid',
            title => 'Grid',
            icon_class => 'fa fa-th'
        },
        {
            name => 'list',
            title => 'List',
            icon_class => 'fa fa-th-list'
        },
    );
    my $view = $query{view};
    if (   !defined $view
        || !grep { $_ eq $view } map { $_->{name} } @views )
    {
        $view = $routes_config->{navigation}->{default_view} || 'grid';
    }
    $tokens->{"navigation-view-$view"} = 1;

    my $view_index = first { $views[$_]->{name} eq $view } 0..$#views;
    $views[$view_index]->{active} = 'active';
    $tokens->{views} = \@views;

    # rows (products per page) 

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

        # we're going to mess up %query_facets in this scope so localise it
        #local %query_facets;

        # navigation facets first (brand/manufacturer, etc)

if (0) {
        foreach my $id ( map { s/^n\.// && $_ } keys %query_facets ) {
            @skus = $schema->resultset('Navigation')->search(
                {
                    -and => [
                        'me.navigation_id' => $id,
                        -or => [
                            { 
                                'product.sku' => { -in => \@skus }
                            },
                            {
                                'variants.sku' => { -in => \@skus },
                            },
                            {
                                'canonical_sku.sku' => { -in => \@skus },
                            }
                        ],
                    ],
                },
                {
                    join => {
                        navigation_products => {
                            product => 'variants'
                        }
                    }
                }
            );
            delete $query_facets{$id};
        }
}
        # now attribute facets

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

if (0) { ############ price facets needs more thought
    # add price facets

    # we need the highest price 1st and we ignore price_modifiers since
    # that gives lower prices we're not interested in for now

    my $max_price = max( $products->get_column('price')->max,
        $products->related_resultset('variants')->get_column('price')->max );

    my $divisor = $max_price > 100 ? 100 : 10;

    my $dtf = $schema->storage->datetime_parser;
    my $today = $dtf->format_datetime(DateTime->today);

    my $canon_skus = $products->search(
        { 'variants.sku' => undef },
    )->get_column('product.sku')-as_query;


    my $price_variant = $products->related_resultset('variants')->search(
        undef,
        {
            select => [
                {
                    coalesce => \"
                      MIN( current_price_modifiers.price ),
                      variants.price",
                    -as => 'price'
                },
            ],
            join => { 'variants' => 'current_price_modifiers' },
            bind => [
                [ end_date => $today ],
                [ quantity => 1 ],
                [ { sqlt_datatype => "integer" } => session('logged_in_user_id') ],
                [ start_date => $today ],

            ]
        }

    );

    debug $price_variant->as_query;

    #my @price_facets = $price_canon->union($price_variant)->hri->all;
    #$tokens->{price_facets} = \@price_facets;

}######## end of excluded code

if (0) {
    # manufacturer facets derived from navigation

    my @a = $schema->resultset('Navigation')->search(
        {
            'me.type'                 => 'manufacturer',
            -or => [
                { 'navigation_products.sku' => { -in => $products->get_column('product.sku')->as_query }},
                { 'navigation_products.sku' => { -in => $products->related_resultset('variants')->get_column('variants.sku')->as_query }},
            ],
        },
        {
            columns => [
                'me.name',
                { count => { count => 'me.navigation_id' }}
            ],
            join => 'navigation_products',
        }
    )->hri->all;

    use Data::Dumper::Concise;
    debug Dumper(@a);
}

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
           : uri_for( $tokens->{navigation}->uri . '/' . $_, \%query ),
           active => $_ == $pager->current_page ? " active" : undef,
         }
    } $first_page .. $last_page;

    # debug "Paging", \@pages;

    $tokens->{pagination} = \@pages;

    unless ($current == '1') {
        $tokens->{pagination_previous} =
         uri_for( $tokens->{navigation}->uri . '/' . ($current - 1), \%query);
    }

    unless ($current == $n) {
        $tokens->{pagination_next} =
         uri_for( $tokens->{navigation}->uri . '/' . ($current + 1), \%query);
    }

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

    # add image for product (if one exists)
    # FIXME: IC6S Product listing method needs to return images if poss
    #        since otherwise we end up having to run a query for each
    #        product i the listing.

    # FIXME this should come from config.
    my $default_image =
      $schema->resultset('Media')->find( { uri => 'default.jpg' } );

    my $image_type =
      $schema->resultset('MediaType')->find( { type => 'image' } );

    foreach my $product (@$listing ) {

        # retrieve picture and add it to the results
        my $image =
          shop_product( $product->{sku} )
          ->media->search( { media_types_id => $image_type->media_types_id },
            { rows => 1 } )->single;

        $image = $default_image unless $image;

        $product->{details} = "location.href=('/" . $product->{uri} . "')";

        if ( my $uri = $image->display_uri('product_200x200') ) {
            $product->{image} = uri_for($uri);
        }
    }

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

    # an interesting page
    var add_to_history =>
      { type => 'product', name => $product->name, sku => $product->sku };

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

    $tokens->{quantity} = $qiter;

    # free shipping
    my $free_shipping_amount = config->{free_shipping}->{amount};
    
    if ($product->price > $free_shipping_amount) {
    $tokens->{free_shipping} = 1;
    }

    my $default_image =
      schema->resultset('Media')->find( { uri => 'default.jpg' } );

    # add image. There could be more, so we just pick the first
    # FIXME issue #72
    my $image = $product->media_by_type('image')->first;

    $image = $default_image unless $image;

    if ($image) {
        $tokens->{image_src} = uri_for($image->display_uri('product_325x325'));
        $tokens->{image_thumb} = uri_for($image->display_uri('product_75x75'));
    }

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

    my $rates = Angler::Shipping::show_rates($angler_cart);
    $values->{shipping_methods} = [];
    if ($rates && @$rates) {
        my @shipping_rates;
        foreach my $rate (@$rates) {
            push @shipping_rates, {
                                     value => $rate->{carrier_service},
                                     label => "$rate->{service} $rate->{rate}\$",
                                    };
        }
        $values->{shipping_methods} = \@shipping_rates;
        if (@shipping_rates) {
            $values->{show_shipping_methods} = 1;
        }
        debug to_dumper("shipping methods are" . to_dumper($values->{shipping_methods}));
    }

    if ($form_values->{shipping_method}) {
        $values->{shipping_method} = $form_values->{shipping_method};
        debug "Shipping method is " . $form_values->{shipping_method};
    }
    $angler_cart->update_costs;

    $form_values->{country} = $angler_cart->country;
    # $values->{states} = states($form_values->{country});

    $form_values->{postal_code} = $angler_cart->postal_code;

    $values->{cart_shipping} = $angler_cart->shipping_cost;
    $values->{cart_tax} = $angler_cart->tax;
    $values->{cart_total} = $cart->total;

    # $values->{shipping_methods} = $angler_cart->shipping_methods;

    # unless (@{$values->{shipping_methods}}) {
    # $values->{shipping_warning} = 'No shipping methods for this country/zip';
    # }

    $form->fill($form_values);
    $values->{form} = $form;
};

sub countries {
    return [shop_country->search(
        {active => 1},
        {order_by => 'name'},
    )];
}

sub states {
    my ($country) = @_;
    my $states;

    $states = [shop_schema->resultset('State')->search(
        {country_iso_code => $country,
         active => 1,
     },
        {order_by => 'name'},
    )];

    return $states;
};


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
            if ( $product = $product->find_variant( \%params ) ) {
                $response{type} = "success";
                $response{uri} = "/" . $product->uri;
            }
            else {
                debug "variant not found for sku $sku with params: " . \%params;
                $response{type} = "error";
                $response{message} = $message;
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

shop_setup_routes;


true;
