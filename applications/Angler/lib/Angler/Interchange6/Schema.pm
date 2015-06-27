use utf8;

package Angler::Interchange6::Schema;

use strict;
use warnings;

=head1 EXTEND

The following Angler::Interchange6::Schema classes extend
Interchange6::Schema

Angler::Interchange6::Schema::Result::ProductRedirect
Angler::Interchange6::Schema::Result::NavigationRedirect

=cut

Angler::Interchange6::Schema->load_classes(qw/ Result::NavigationRedirect Result::ProductRedirect /);

=head1 OVERRIDE

The following Interchange6::Schema classes are overidden

Interchange6::Schema::Result::Product
Interchange6::Schema::Result::Navigation
Interchange6::Schema::ResultSet::Product
Interchange6::Schema::ResultSet::Inventory

=cut

use Angler::Interchange6::Schema::Result::Product;
use Angler::Interchange6::Schema::Result::Navigation;
use Angler::Interchange6::Schema::Result::Inventory;
use Angler::Interchange6::Schema::ResultSet::Product;

use base 'Interchange6::Schema';

1;
