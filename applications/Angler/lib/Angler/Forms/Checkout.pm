package Angler::Forms::Checkout;

use strict;
use warnings;

use Moo;
use Data::Transpose 0.0012;
use Data::Transpose::Prefix;

has address => (
    is => 'ro',
    required => 1,
);

has prefix => (
    is => 'ro',
);

sub transpose {
    my ($self) = @_;
    my $tp;

    if ($self->prefix) {
        $tp = Data::Transpose::Prefix->new(prefix => $self->prefix);
    }
    else {
        $tp = Data::Transpose->new();
    }
    $tp->field('first_name');
    $tp->field('last_name');
    $tp->field('address');
    $tp->field('address_2');
    $tp->field('company');
    $tp->field('postal_code');
    $tp->field('city');
    $tp->field('phone');
    $tp->field('states_id')->target('state');
    $tp->field('country_iso_code')->target('country');

    my $form_values = $tp->transpose_object($self->address);

    return $form_values;
}

1;
