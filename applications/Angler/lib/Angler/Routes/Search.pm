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

    $current_uri ||= '';
    debug "Current search uri: ", $current_uri;

    # create search object
    my $search = Angler::Search->new(
        solr_url => config->{solr_url},
        facets => config->{facet_fields}->{attributes},
    );

    if (exists $params->{q}) {
        $q = $params->{q};
        my @words =  split(/\s+/, param('q'));
        my $new_uri = $search->add_terms_to_url($current_uri, @words);
        debug "Redirect to new search uri: ", $new_uri;
        redirect "/search/$new_uri";
    }
    else {
        $q = '';
    }

    if ($current_uri) {
        $search->search_from_url($current_uri);
    }
    else {
        $search->search($q);
    }

    my $response = $search->response;
    my $results = $search->results;
    my $count = $search->num_found;

#    debug "Results: ", $search->results;
    debug "Facets found: ", $search->facets_found;

    # transform facets
    my @facets;
    my $schema = shop_schema;

    for my $name (@{$search->facets}) {
        my @facets_found = @{$search->facets_found->{$name}};
        next unless @facets_found;

        # retrieve corresponding attribute
        my $attribute = $schema->resultset('Attribute')->find({
            type => 'variant',
            name => $name,
        });

        if (! $attribute) {
            warning "Attribute not found for facets: $name.";
            next;
        }

        my @values;

        for my $facet_found (@{$search->facets_found->{$name}}) {
            # retrieve attribute title
            my $value = $attribute->attribute_values
                ->find({value => $facet_found->{name}});

            if (! $value) {
                warning "Attribute value not found for facets: $name - $facet_found->{name}";
                next;
            }

            $facet_found->{title} = $value->title;
            $facet_found->{name} = $name;
            $facet_found->{checked} = $facet_found->{active};

            push @values, $facet_found;
        }

        push @facets, {title => $attribute->title,
                       values => \@values,
                   };
    }

    my %query = params('query');

    my $paging = Angler::Paging->new(
        pager => $response->pager,
        uri => '/search/' .  $search->current_search_to_url(hide_page => 1) . '/page',
        query => \%query,
    );

    # load list of brands
    my $brands = shop_navigation->search({type => 'manufacturer',
                                          active => 1});

    my %tokens = (
        products => $results,
        pager => $response->pager,
        pagination => $paging->page_list,
        pagination_previous => $paging->previous_uri,
        pagination_next => $paging->next_uri,
        breadcrumbs => [],
        facets => \@facets,
        count => $count,
        brands => [$brands->all],
        "extra-js-file" => 'product-listing.js',
    );

    my $navigation = Angler::SearchResults->new(
        routes_config => config->{plugin}->{'Interchange6::Routes'} || {},
        tokens => \%tokens,
    );

    # select view
    $navigation->select_view(%query);

    # add different views to template tokens
    $tokens{views} = $navigation->views;

    template 'product/grid/content', \%tokens;
};

1;

