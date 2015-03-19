use utf8;

package Angler::Interchange6::Schema;

use strict;
use warnings;

=head1 OVERRIDE

The following Interchange6::Schema classes are overidden

Interchange6::Schema::Result::Product

=cut

use Angler::Interchange6::Schema::Result::Product;
use Angler::Interchange6::Schema::Result::Navigation;

use base 'Interchange6::Schema';

1;
