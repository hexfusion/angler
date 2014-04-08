package Angler::Tax;

use strict;
use warnings;

=head2 tax_location($schema, $state);

returns 0|1

=cut

sub tax {
    my ($schema, $state, $cart) = @_;
    my $tax_total;
    my $rate;
    my $taxable = taxable_location($schema, $state);

    if ($taxable) {
        $rate = tax_rate;
        $tax_total = ( '0.0'. $rate * $cart->subtotal);
    }
    return $tax_total;
}

sub taxable_location {
    my ($schema, $state) = @_;
        unless ($state) {
            die "taxable location requires a state";
        }
        unless ($state->state_iso_code = 'NY') {
            return 0;
        }
    return 1;
}


sub tax_rate {
    return '8';
}
1;
