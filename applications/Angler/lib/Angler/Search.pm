package Angler::Search;

use Moo;
use MooX::Types::MooseLike::Base qw/ArrayRef/;

extends 'Interchange::Search::Solr';

use Data::Page;

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
            total_entries => $self->num_found,
            entries_per_page => $self->page_size,
        );
    },
);

has words => (
    is => 'rw',
#    isa => 'ArrayRef',
    default => sub {[]},
);

sub results {
    my $self = shift;
    my @matches;

	for my $doc ( $self->response->docs ) {
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

1;
