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

    # select view
    $navigation->select_view;
    $navigation->select_sorting;

    # add different views to template tokens
    $tokens{views} = $navigation->views;

    $navigation->select_rows;

    # create search object
    my $search = Angler::Search->new(
        solr_url => config->{solr_url},
        facets => config->{facet_fields}->{attributes},
        rows => $tokens{per_page},
        sorting_direction => $navigation->current_sorting_direction,
        sorting => $navigation->sorting_for_solr,
    );
    debug $search->version;


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
    debug $response->raw_response->request->content;
#    debug "Results: ", $search->results;
    debug "Facets found: ", $search->facets_found;
    # debug to_dumper($search);
    # transform facets
    my @facets;
    my $schema = shop_schema;

    for my $name (@{$search->facets}) {
        my @facets_found = @{$search->facets_found->{$name} || []};
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



    my $paging = Angler::Paging->new(
        pager => $response->pager,
        uri => '/search/' .  $search->current_search_to_url(hide_page => 1) . '/page',
        query => \%query,
    );

    # load list of brands
    my $brands = shop_navigation->search({type => 'manufacturer',
                                          active => 1});

    $tokens{products} = $results;
    $tokens{pager} = $response->pager,
    $tokens{pagination} = $paging->page_list,
    $tokens{pagination_previous} = $paging->previous_uri,
    $tokens{pagination_next} = $paging->next_uri,
    $tokens{breadcrumbs} = [];
    $tokens{facets} = \@facets;
    $tokens{count} = $count;
    $tokens{brands} = [$brands->all];
    $tokens{"extra-js-file"} = 'product-listing.js';

    template 'product/grid/content', \%tokens;
};

1;

