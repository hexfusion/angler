package Angler::Routes::Search;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;

use Angler::Search;
use Angler::Paging;
use Angler::SearchResults;

get qr{/search(/(.*))?} => sub {
    my $params = params;
    my $q;
    my ($slash, $current_uri) = splat;
    my %tokens;

    $current_uri ||= '';
    debug "Current search uri: ", $current_uri;

    my %query = params('query');

    my $navigation = Angler::SearchResults->new(
        routes_config => config->{plugin}->{'Interchange6::Routes'} || {},
        tokens => \%tokens,
        query => \%query,
    );

    # create search object
    my $search = Angler::Search->new(
        solr_url => config->{solr_url},
        facets => [ 'navigation_ids', @{config->{facet_fields}->{attributes}} ],
        rows => $tokens{per_page},
        sorting_direction => $navigation->current_sorting_direction,
        sorting => $navigation->sorting_for_solr,
        global_conditions => { active => 1 },
    );
    debug $search->version;


    if (exists $params->{q}) {
        $q = $params->{q};
        my @words =  split(/\s+/, param('q'));
        my $new_uri = $search->add_terms_to_url($current_uri, @words);
        debug "Redirect to new search uri: ", $new_uri;
        redirect "/search/$new_uri";
    }

    $search->search_from_url($current_uri);

    my $response = $search->response;
    my $results = $search->results;
    my $count = $search->num_found;

    # we need an equivalent of DBIC Product's allow_cart_add otherwise 
    # we won't get 'Add to Cart' buttons in the view

    foreach my $product (@$results) {
        if (   $product->{availability}
            && $product->{availability} eq 'Out of Stock' )
        {
            $product->{allow_cart_add} = 0;
        }
        else {
            $product->{allow_cart_add} = 1;
        }
    }

    # transform facets
    my @facets;
    my $schema = shop_schema;

    my %facet_map;

    for my $name (@{$search->facets}) {
        my @facets_found = @{$search->facets_found->{$name} || []};
        next unless @facets_found;

        my ($is_navigation, $attribute, $facet_title);
        # retrieve corresponding attribute
        if ($name eq 'navigation_ids') {
            $is_navigation = 1;
            $facet_title = 'Categories';
        }
        elsif ($attribute = $schema->resultset('Attribute')->find({
                                                                   type => 'variant',
                                                                   name => $name,
                                                                  })) {
            $facet_title  = $attribute->title;
        }
        else {
            warning "Attribute not found for facets: $name.";
            next;
        }

        my @values;

        for my $facet_found (@{$search->facets_found->{$name}}) {
            # retrieve attribute title
            my %value;
            if ($is_navigation) {
                if (my $navigation = $schema->resultset('Navigation')->find($facet_found->{name})) {
                    %value = (
                              title => $navigation->name,
                              priority => 0, # $navigation->priority,
                             );
                }
                else {
                    warning "Attribute value not found for facets: $name - $facet_found->{name}";
                    next;
                }
            }
            elsif (my $value = $attribute->attribute_values
                   ->find({value => $facet_found->{name}})) {
                %value = (
                          title => $value->title,
                          priority => $value->priority,
                         );
            }
            else {
                warning "Attribute value not found for facets: $name - $facet_found->{name}";
                next;
            }
            $facet_map{$name}{$facet_found->{name}} = $value{title};
            $facet_found->{title} = $value{title};
            $facet_found->{name} = $name;
            $facet_found->{checked} = $facet_found->{active};
            $facet_found->{unchecked} = ! $facet_found->{active};
            $facet_found->{priority} = $value{priority};
            push @values, $facet_found;
        }
        push @facets, {title => $facet_title,
                       values => sort_attributes(\@values),
                   };
    }

# debug "Facets: ", \@facets;

    my $paging = Angler::Paging->new(
        pager => $response->pager,
        uri => '/search/' .  $search->current_search_to_url(hide_page => 1) . '/page',
        query => \%query,
    );

    # load list of brands
    my $brands = shop_navigation->search({type => 'manufacturer',
                                          active => 1});

    if ( var 'breadcrumbs' ) {
        # we were forwared here from elsewhere
        $tokens{breadcrumbs} = var 'breadcrumbs';
    }
    else {
        my @breadcrumbs = (
            {
                uri  => 'search',
                name => 'Products',
            }
        );

        # debug to_dumper(\%facet_map);
        foreach my $breadcrumb ( $search->breadcrumbs ) {
            my $bc = {
                uri  => 'search/' . $breadcrumb->{uri},
                name => ucfirst( $breadcrumb->{label} ),
            };
            if ( my $bc_facet = $breadcrumb->{facet} ) {
                if ( my $bc_title =
                    $facet_map{$bc_facet}{ $breadcrumb->{label} } )
                {
                    $bc->{name} = $bc_title;
                }
                else {
                    warning
                      "no $bc_facet $breadcrumb->{label} found in facet map";
                }
            }
            push @breadcrumbs, $bc;
        }
        $tokens{breadcrumbs} = \@breadcrumbs;
    }
    my @removewords;
    foreach my $remove_word ($search->remove_word_links) {
        push @removewords, {
                            uri => 'search/' . $remove_word->{uri},
                            name => ucfirst($remove_word->{label}),
                           };
    }

    $tokens{products} = $results;
    $tokens{pager} = $response->pager;
    $tokens{pagination} = $paging->page_list;
    $tokens{pagination_previous} = $paging->previous_uri;
    $tokens{pagination_next} = $paging->next_uri;
    if (@removewords) {
        $tokens{clear_words} = 'search/' . $search->clear_words_link;
        $tokens{remove_words} = \@removewords;
        # debug to_dumper(\@removewords);
        # debug $tokens{clear_words};
    }
    $tokens{facets} = \@facets;
    $tokens{count} = $count;
    $tokens{brands} = [$brands->all];
    $tokens{"extra-js-file"} = 'product-listing.js';

    template 'product/grid/content', \%tokens;
};

get qr{/(?<uri>fly-fishing-gear/(?<species>.+))} => sub {
    my $captures = captures;
    my $uri      = $captures->{uri};
    my $species  = $captures->{species};

    my $nav = shop_navigation->search(
        {
            'me.active'   => 1,
            'me.uri'      => $uri,
            'parents.uri' => 'fly-fishing-gear/species',
        },
        {
            join => 'parents',
        }
    )->rows(1)->single;

    pass unless $nav;

    my @encestors = $nav->ancestors;
    var breadcrumbs => [ reverse(@encestors), $nav ];

    $species =~ s/\-permit//;
    $species =~ s/striped\-//;
    forward "/search/words/$species";
};

get qr{/XXXXXXXX_not_yet_in_use_(?<uri>clothing/\w+)/?(?<facets>.*)$} => sub {
    my $captures = captures;
    my %tokens;
    my %query = params('query');
    my $schema = shop_schema;

    my $uri = lc( $captures->{uri} );

    my $brand_nav =
      shop_navigation->search( { uri => $uri, scope => 'clothing-brand' },
        { rows => 1 } )->single;

    pass unless $brand_nav;

    $tokens{title} = $brand_nav->description;

    my $navigation = Angler::SearchResults->new(
        routes_config => config->{plugin}->{'Interchange6::Routes'} || {},
        tokens        => \%tokens,
        query         => \%query,
    );

    my $search = Angler::Search->new(
        solr_url => config->{solr_url},
        facets => [ 'navigation_ids', @{config->{facet_fields}->{attributes}} ],
        rows => $tokens{per_page},
        sorting_direction => $navigation->current_sorting_direction,
        sorting => $navigation->sorting_for_solr,
        global_conditions => { active => 1 },
    );

    # find all navigation_ids for navs that are children of the nav that
    # has use 'clothing' which are active and have products

    my $rset = shop_navigation->search(
        {
            'parents.uri' => 'clothing',
            'me.active' => 1,
            'navigation_products.navigation_id' => { '!=' => undef },
        },
        {
            columns => [ 'me.navigation_id' ],
            join => [ 'parents', 'navigation_products' ],
            group_by => 'me.navigation_id',
        }
    );
    my @navigation_ids = $rset->get_column('navigation_id')->all;

    my $search_uri = join( '/', 'words',
        split( /\s+/, $brand_nav->name ),
        'navigation_ids', @navigation_ids, $captures->{facets} );

    debug "Search uri: $search_uri";

    $search->search_from_url($search_uri);

    my $response = $search->response;
    my $results = $search->results;
    my $count = $search->num_found;

    debug $response->raw_response->request->content;
    debug "Facets found: ", $search->facets_found;
    debug "Count: $count";

    my @facets;
    my %facet_map;

    for my $name ( @{ $search->facets } ) {
        my @facets_found = @{ $search->facets_found->{$name} || [] };
        next unless @facets_found;

        my ( $is_navigation, $attribute, $facet_title, %value_map, $unchecked );

        # retrieve corresponding attribute
        if ( $name eq 'navigation_ids' ) {
            $is_navigation = 1;
            $facet_title   = 'Categories';

            # if no navigation facets in URI we need to force unchecked
            $unchecked = 1
              if !grep { $_ eq 'navigation_ids' }
              split( /\//, $captures->{facets} );
        }
        else {
            # prefetch with cache so only one query per facet name
            my $rset = $schema->resultset('Attribute')->search(
                {
                    type => 'variant',
                    name => $name,
                },
                {
                    prefetch => 'attribute_values',
                    cache    => 1,
                }
            );

            my $attribute = $rset->next;

            if ( not $attribute) {
                warning "Attribute not found for facets: $name.";
                next;
            }

            $facet_title = $attribute->name;

            # build %value_map for use later

            my $avs = $attribute->attribute_values;
            while ( my $av = $avs->next ) {
                $value_map{ $av->value } =
                  { title => $av->title, priority => $av->priority };
            }
        }

        my @values;

        for my $facet_found ( @facets_found ) {

            # retrieve attribute title
            my %value;
            if ($is_navigation) {
                if ( my $navigation =
                    $schema->resultset('Navigation')
                    ->find( $facet_found->{name} ) )
                {
                    %value = (
                        title    => $navigation->name,
                        priority => 0,                  # $navigation->priority,
                    );
                }
                else {
                    warning "Attribute value not found for facets: "
                      . "$name - $facet_found->{name}";
                    next;
                }
            }
            elsif ( $value_map{ $facet_found->{name} } ) {
                %value = %{ $value_map{ $facet_found->{name} } };
            }
            else {
                warning "Attribute value not found for facets: "
                  . "$name - $facet_found->{name}";
                next;
            }
            $facet_map{$name}{ $facet_found->{name} } = $value{title};
            $facet_found->{title}                     = $value{title};
            $facet_found->{name}                      = $name;
            $facet_found->{unchecked} = $unchecked || !$facet_found->{active};
            $facet_found->{checked}   = !$facet_found->{unchecked};
            $facet_found->{priority}  = $value{priority};
            push @values, $facet_found;
        }
        push @facets,
          {
            title  => $facet_title,
            values => sort_attributes( \@values ),
          };
    }

    my $paging = Angler::Paging->new(
        pager => $response->pager,
        uri   => '/search/'
          . $search->current_search_to_url( hide_page => 1 ) . '/page',
        query => \%query,
    );

    # breadcrumb is pretty simple

    $tokens{breadcrumbs} = [
        {
            name => "Clothing",
            uri  => "clothing"
        },
        {
            name => $brand_nav->name,
            uri  => $uri,
        }
    ];

    $tokens{products} = $results;
    $tokens{pager} = $response->pager,
    $tokens{pagination} = $paging->page_list,
    $tokens{pagination_previous} = $paging->previous_uri,
    $tokens{pagination_next} = $paging->next_uri,
    $tokens{facets} = \@facets;
    $tokens{count} = $count;
    $tokens{"extra-js-file"} = 'product-listing.js';

    template 'product/grid/content', \%tokens;
};

get '/ajax/search' => sub {
    my $q = params->{'q'} || '';
    my $search = Angler::Search->new(
                                     solr_url => config->{solr_url},
                                     rows => 10,
                                     search_fields => [qw/sku name uri/],
                                     facets => [], # disable facets
                                     return_fields => [qw/sku name uri active/],
                                     global_conditions => { active => 1 },
                                    );
    my $res = $search->execute_query(q[(_query_:"{!edismax qf='sku name uri description'}] . $q . '*")');
    # debug to_dumper($res);
    my $results = $search->results;
    # debug to_dumper($results);
    # debug $search->search_string;
    debug scalar(@$results) . " solr results for " . $q;
    foreach my $item (@$results) {
        $item->{category} = ''; # no category for search results
    }
    # debug to_dumper($results);

    if (length($q) > 2) {
        my $schema = shop_schema;
#         $schema->storage->debugcb(sub {
#                                       my ($op, $info) = @_;
#                                       debug "$info";
#                                   });

        my $like = $q;
        $like =~ s/[^\w]/ /g; # remove punctuation and such
        my @words = grep { $_ } split(/\s+/, $like);
        my @conds;
        foreach my $word (@words) {
            my @likeness;
            foreach my $field (qw/name/) {
                push @likeness, { $field => { -like => '%' . $word . '%' } };
            }
            push @conds, \@likeness;
        }
        if (@conds) {
            my %search = (active => 1,
                          -and =>  \@conds );
            my $cats = $schema->resultset('Navigation')
              ->search(\%search,
                       {
                        order_by => [qw/type name/],
                        # rows => 20, # unclear
                       });
            while (my $cat = $cats->next) {
                my %details = (
                               name => $cat->name,
                               category => $cat->type,
                               uri => $cat->uri,
                              );
                if ($details{category} eq 'menu') {
                    $details{category} = 'Category';
                }
                else {
                    $details{category} = ucfirst($details{category});
                }
                push @$results, \%details;
            }
        }
    }
    content_type('application/json');
	return to_json({ docs => $results });
};

sub sort_attributes {
    my $values = shift;
    my (@priority, @name_no_number, @numbers);
    my @old = @$values;
    foreach my $value (@old) {
        push @priority, $value->{priority};
        my $copy = $value->{title};
        if ($copy =~ /\A(\d+(\.\d+)?)/) {
            push @numbers, $1;
            $copy =~ s/\A\d+(\.\d+)?//;
            push @name_no_number, $copy;
        }
        elsif ($copy =~ /(\d+(\.\d+)?)\z/) {
            push @numbers, $1;
            $copy =~ s/\d+(\.\d+)?\z//;
            push @name_no_number, $copy;
        }
        else {
            push @name_no_number, $copy;
            push @numbers, 0;
        }
    }
    # debug to_dumper([\@numbers, \@name_no_number, \@priority]);

    my @sorted = @old[
                      sort {
                          $priority[$b] <=> $priority[$a]
                            ||
                            $name_no_number[$a] cmp $name_no_number[$b]
                            ||
                            $numbers[$a] <=> $numbers[$b]
                      } 0..$#old
                     ];
    return \@sorted;
}

1;

