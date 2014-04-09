package Angler::Tax;

use strict;
use warnings;

=head2 taxable_location($schema, $state);

returns 0|1

=cut

sub rate {
    my ($schema, $state, $subtotal) = @_;
    my $tax_total;
    my $rate;
    my $taxable = taxable_location($schema, $state);

    if ($taxable) {
        $tax_total = ( 0.08 * $subtotal);
    }
    return $tax_total;
}

=head2 rate($schema, $state, $subtotal);

returns tax amount.

=cut

sub taxable_location {
    my ($schema, $state) = @_;

        unless ($state) {
            return 0;
        }
        my $state_iso_code = $state->state_iso_code;
        unless ($state_iso_code eq 'NY') {
            return 0;
        }
    return 1;
}

1;
