package Angler::Forms::Checkout;

use strict;
use warnings;

use Moo;
use Data::Transpose 0.0008;

has address => (
    is => 'ro',
    required => 1,
);

sub transpose {
    my ($self) = @_;
    my $tp = Data::Transpose->new;

    $tp->field('first_name');
    $tp->field('last_name');
    $tp->field('address');
    $tp->field('address_2');
    $tp->field('company');
    $tp->field('postal_code');
    $tp->field('city');
    $tp->field('phone');
    $tp->field('state_iso_code')->target('state');
    $tp->field('country_iso_code')->target('country');

    my $form_values = $tp->transpose_object($self->address);

    return $form_values;
}

1;
