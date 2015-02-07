package Angler::Search;

use Moo;
use MooX::Types::MooseLike::Base qw/ArrayRef/;

use WebService::Solr;
use Data::Page;

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

has page_size => (
    is => 'rw',
    default => sub {20},
);

has pager => (
    is => 'rwp',
    lazy => 1,
    default => sub {
        my $self = shift;
        my $pager = Data::Page->new(
            total_entries => $self->count,
            entries_per_page => $self->page_size,
        );
    },
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

    # search terms from customer
    if (@spec_words) {
        push @filters, q/_query_:"{!edismax qf='sku name description'}/
            . join(' AND ', @spec_words)
                . '"';
    }
    else {
        push @filters, '*:*';
    }

    # only search for active and canonical products
    unshift @filters, q{*:* NOT canonical_sku:['' TO *]};
    unshift @filters, 'active:true';

    my $query = join( " AND ", map { "($_)" } @filters );

#    Dancer::Logger::debug("Query: ", $query);

    my $solr = $self->solr_object;

	my $response = $solr->search($query, {start => 0,
                                          rows => $self->page_size});

	my @matches;

    my $count = $response->pager->total_entries;

    # save count and pager in our object
    $self->_set_count($count);
    $self->_set_pager($response->pager);

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
