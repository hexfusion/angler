package Angler::Paging;

use 5.010001;
use strict;
use warnings;

use Moo;
use MooX::Types::MooseLike::Base qw(InstanceOf);

has pager => (
    is => 'rw',
    isa => InstanceOf('Data::Page'),
    required => 1,
);

has uri => (
    is => 'rw',
    required => 1,
);

sub page_list {
    my $self = shift;
    my $pager = $self->pager;
    my $uri = $self->uri;
    my $current = $pager->current_page;
    my $n = int( ($pager->total_entries / $pager->entries_per_page) + .999);
    my $first_page = 1;
    my $last_page  = $pager->last_page;
    my %query;

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
           : uri_for( "$uri/$_", \%query ),
           active => $_ == $pager->current_page ? " active" : undef,
         }
    } $first_page .. $last_page;

    return \@pages;
}

sub previous_uri {
    my $self = shift;
    my $pager = $self->pager;

    if ($pager->previous_page) {
        return uri_for($self->uri . '/' . $pager->previous_page);
    }

    return undef;
}

sub next_uri {
    my $self = shift;
    my $pager = $self->pager;

    if ($pager->next_page) {
        return uri_for($self->uri . '/' . $pager->next_page);
    }

    return undef;
}

sub uri_for {
    return shift;
}

1;
