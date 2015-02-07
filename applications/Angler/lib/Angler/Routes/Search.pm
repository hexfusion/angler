package Angler::Routes::Search;

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;

use Angler::Search;

get '/search' => sub {
    debug "Searching for : ", param('q');

    my @words = split(/\s+/, param('q'));

    # create search object
    my $search = Angler::Search->new(
        solr_url => config->{solr_url},
        words => \@words);

    $search->search(param('q'));

    my $response = $search->response;
    my $results = $search->results;
    my $count = $search->num_found;

    debug "Count for ", \@words, ": ", $search->num_found;
    debug "Total entries: ", $response->pager->total_entries;
    debug "Matches:", $results;

    my @pages = ({page => 1, uri => "bla", active => "active"});

    # load list of brands
    my $brands = shop_navigation->search({type => 'manufacturer',
                                          active => 1});

    my %tokens = (
        products => $results,
        pager => $response->pager,
        pagination => \@pages,
        breadcrumbs => [],
        facets => [],
        views => [],
        count => $count,
        brands => [$brands->all],
    );

    template 'product/grid/content', \%tokens;
};

1;

