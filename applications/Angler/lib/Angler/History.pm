package Angler::History;

use 5.010001;
use strict;
use warnings;

use Dancer ':syntax';
use Moo;
use MooX::Types::MooseLike::Base qw(ArrayRef HashRef Int);

has max_items => (
    is      => 'ro',
    isa     => Int,
    default => 20,
);

has pages => (
    is  => 'rw',
    isa => HashRef [ ArrayRef [HashRef] ],
);

has current_page => (
    is => 'lazy',
    isa => HashRef,
);

sub _build_current_page {
    my $self = shift;
    if ( defined $self->pages->{all} ) {
        return $self->pages->{all}->[0];
    }
    return undef;
}

has previous_page => (
    is => 'lazy',
    isa => HashRef,
);

sub _build_previous_page {
    my $self = shift;
    if ( defined $self->pages->{all} ) {
        return $self->pages->{all}->[1];
    }
    return undef;
}

sub add {
    my $self = shift;
}

sub navigation {
    my $self = shift;
    return $self->pages->{navigation};
}

sub product {
    my $self = shift;
    return $self->pages->{product};
}


1;
