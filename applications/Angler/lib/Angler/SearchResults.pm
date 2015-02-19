package Angler::SearchResults;

use 5.010001;
use strict;
use warnings;

use Moo;
use MooX::Types::MooseLike::Base qw(HashRef);

use POSIX qw/ceil/;
use List::Util qw(first);

=head1 ATTRIBUTES

=head2 routes_config

Hash reference from L<Dancer::Plugin::Interchange6::Routes> plugin configuration
(required).

=cut

has routes_config => (
    is => 'rw',
    isa => HashRef,
    required => 1,
);

=head2 query

Query parameters from HTTP request.

=cut

has query => (
    is => 'rw',
    isa => HashRef,
    required => 1,
);

=head2 tokens

Template tokens (hash reference, required).

=cut

has tokens => (
    is => 'rw',
    isa => HashRef,
    required => 1,
);

=head2 current_view

Returns name of current view.

=cut

has current_view => (
    is => 'rwp',
);


=head2 current_sorting

Returns the name of the current sorting, after calling C<select_sorting>.

=head2 current_sorting_direction

Returns the name of the current sorting direction, after calling C<select_sorting>.

=cut

has current_sorting => ( is => 'rwp' );
has current_sorting_direction => ( is => 'rwp' );

=head2 views

Returns list of views.

=cut

has views => (
    is => 'rw',
    default => sub {
        return [
            {
                name => 'grid',
                title => 'Grid',
                icon_class => 'fa fa-th'
            },
            {
                name => 'list',
                title => 'List',
                icon_class => 'fa fa-th-list'
            },
        ]
    },
);

# determine which view to display
sub select_view {
    my ($self) = @_;
    my $routes_config = $self->routes_config;
    my $tokens = $self->tokens;
    my @views = @{$self->views};
    my $view = $self->query->{view};

    if (   !defined $view
        || !grep { $_ eq $view } map { $_->{name} } @views )
    {
        $view = $routes_config->{navigation}->{default_view} || 'grid';
    }
    $self->_set_current_view($view);

    $tokens->{"navigation-view-$view"} = 1;

    my $view_index = first { $views[$_]->{name} eq $view } 0..$#views;
    $views[$view_index]->{active} = 'active';
    $tokens->{views} = \@views;
}

sub select_rows {
    my ($self) = @_;
    my $routes_config = $self->routes_config;
    my $tokens = $self->tokens;

    # rows (products per page) 

    my $rows = $self->query->{rows};
    if ( !defined $rows || $rows !~ /^\d+$/ ) {
        $rows = $routes_config->{navigation}->{records} || 10;
    }

    my @rows_iterator;
    if ( $self->current_view eq 'grid' ) {
        $rows = ceil($rows/3)*3;
        $tokens->{per_page_iterator} = [ map { +{ value => 12 * $_ } } 1 .. 4 ];
    }
    else {
        $tokens->{per_page_iterator} = [ map { +{ value => 10 * $_ } } 1 .. 4 ];
    }
    $tokens->{per_page} = $rows;
}

sub select_sorting {
    my ($self) = @_;
    my $tokens = $self->tokens;
    my $query  = $self->query;

    my @order_by_iterator = (
        { value => 'priority',       label => 'Position' },
        { value => 'average_rating', label => 'Rating' },
        { value => 'selling_price',  label => 'Price' },
        { value => 'name',           label => 'Name' },
        { value => 'sku',            label => 'SKU' },
    );
    $tokens->{order_by_iterator} = \@order_by_iterator;

    my $order     = $query->{order};
    my $direction = $query->{dir};

    # maybe set default order(_by)
    if (   !defined $order
        || !grep { $_ eq $order } map { $_->{value} } @order_by_iterator )
    {
        $order = 'priority';
    }
    $tokens->{order_by} = $order;

    # maybe set default direction
    if ( !defined $direction || $direction !~ /^(asc|desc)/ ) {
        if ( $order =~ /^(average_rating|priority)$/ ) {
            $direction = 'desc';
        }
        else {
            $direction = 'asc';
        }
    }
    # asc/desc arrow
    if ( $direction eq 'asc' ) {
        $tokens->{reverse_order} = 'desc';
        $tokens->{order_by_glyph} =
          q(<span class="fa fa-long-arrow-up"></span>);
    }
    else {
        $tokens->{reverse_order} = 'asc';
        $tokens->{order_by_glyph} =
          q(<span class="fa fa-long-arrow-down"></span>);
    }
    $self->_set_current_sorting($order);
    $self->_set_current_sorting_direction($direction);
    return $order;
}


1;
