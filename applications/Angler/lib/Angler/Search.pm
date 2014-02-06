package Angler::Search;

use Moo;
use MooX::Types::MooseLike::Base qw/ArrayRef/;

use WebService::Solr;

has solr_url => (
    is => 'ro',
    required => 1,
);

has solr_object => (
    is => 'ro',
    lazy => 1,
    builder => '_get_object',
);

has count => (
    is => 'rwp',
    lazy => 1,
    default => sub {0},
);

has words => (
    is => 'rw',
#    isa => 'ArrayRef',
    default => sub {[]},
);

sub solr_query {
    my ( $self ) = @_;
    my (@filters);

    my @spec_words = map {"$_*"} @{$self->words};

    if (@spec_words) {
        push @filters, q/_query_:"{!edismax qf='sku name description'}/
            . join(' AND ', @spec_words)
                . '"';
    }

    my $query;

    # only search for active products
    if (! @filters) {
        @filters = '*:*';
    }

    unshift @filters, q{canonical_sku:['' TO *]};

    $query = join( " AND ", map { "($_)" } @filters );
     
    Dancer::Logger::debug("Query: ", $query);
    
    my $solr = $self->solr_object;

	my $response = $solr->search($query);
	my @matches;

    my $count = $response->pager->total_entries;

    # save count in our object
    $self->_set_count($count);

	for my $doc ( $response->docs ) {
		my (%record, $name);

        for my $fld ($doc->fields) {
            $name = $fld->name;
            next if $name =~ /^_/;

            $record{$name} = $fld->value;
        }

        push  @matches, \%record;
	}

    return \@matches;
}

sub _get_object {
    my ($self) = @_;

    my $solr = WebService::Solr->new( $self->solr_url );

    return $solr;
}

1;
