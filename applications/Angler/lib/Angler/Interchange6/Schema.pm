use utf8;

package Angler::Interchange6::Schema;

use strict;
use warnings;

=head1 EXTEND

The following Angler::Interchange6::Schema classes extend
Interchange6::Schema

Angler::Interchange6::Schema::Result::MyNewClass

=cut

#Angler::Interchange6::Schema->load_classes(qw/ Result::MyNewCLass /);

=head1 OVERRIDE

The following Interchange6::Schema classes are overidden

Interchange6::Schema::Result::Product
Interchange6::Schema::Result::Navigation
Interchange6::Schema::ResultSet::Product

=cut

use Angler::Interchange6::Schema::Result::Product;
use Angler::Interchange6::Schema::Result::Navigation;
use Angler::Interchange6::Schema::ResultSet::Product;

use base 'Interchange6::Schema';

1;
