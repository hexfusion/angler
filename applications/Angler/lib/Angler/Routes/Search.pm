package Angler::Routes::Search;

use Dancer ':syntax';

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

    debug "Results: ", $results;
    
    template 'product-listing', {products => $results,
                                 count => $count};
};
  
1;

