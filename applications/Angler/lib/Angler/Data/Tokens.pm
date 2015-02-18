package Angler::Data::Tokens;

use strict;
use warnings;

use Moo;
use Data::Transpose 0.0012;
use Data::Transpose::Prefix;
use Angler::Shipping;

has schema => (
    is => 'ro',
    required => 1,
);

has form => (
    is => 'ro',
);

has country => (
    is => 'ro',
);

=head1 DESCRIPTION

This is a collection of useful form tokens

=cut

=head2 checkout

these are the default tokens used in checkout form and include billing and shipping states, countries, card_months and card_years

=head2 countries

input schema and return country resultset

=cut

sub checkout {
    my ($self) = @_;
    my $values = $self->form->{values};
    my $tokens;

    $tokens->{'countries'} = $self->countries;
    $tokens->{'states'} = $self->_states;
    $tokens->{'card_months'} = $self->card_months;
    $tokens->{'card_years'} = $self->card_years;

    return $tokens;

}

sub countries {
    my ($self) = @_;

    my $countries = Angler::Shipping::deliverable_countries($self->schema);

    return $countries;
};

=head2 states

Returns an array reference of active State result rows ordered by name for
the requested country.

=cut

sub states {
    my ($self) = @_;
    my $values = $self->form->{values};

    my $states = [ $self->schema->resultset('State')->search(
            {country_iso_code => $self->country,
             active => 1,
            },
            {order_by => 'name'},
    )];
    return $states;
};

=head2 _states

internal only see states

=cut

sub _states {
    my ($self) = @_;
    my $values = $self->form->{values};

    my $states = [ $self->schema->resultset('State')->search(
            {country_iso_code => $values->{billing_country},
             active => 1,
            },
            {order_by => 'name'},
    )];
    return $states;
};

=head2 card_months

tokens for credit card expiration months

=cut

sub card_months {
    my @months;

    for my $i (1..12) {
        push @months, {value => $i, label => $i};
    }

    return \@months;
}

=head2 card_years

tokens for credit card years

=cut

sub card_years {
    my @years;
    my $cur_year = DateTime->now->year;

    for my $i ($cur_year..$cur_year+20) {
        push @years, {value => substr($i,0,2), label => $i};
    }

    return \@years;
}

1;
