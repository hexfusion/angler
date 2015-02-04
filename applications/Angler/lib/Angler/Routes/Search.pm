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

    my $results = $search->solr_query;
    my $count = $search->count;

    # load list of brands
    my $brands = shop_navigation->search({type => 'manufacturer',
                                          active => 1});

    template 'product/grid/content', {products => $results,
                                 count => $count,
				 brands => [$brands->all],
    };
};

1;

