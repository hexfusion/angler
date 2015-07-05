use utf8;

package Angler::Interchange6::Schema;

use strict;
use warnings;

our $VERSION = 1;

=head1 EXTEND

The following Angler::Interchange6::Schema classes extend
Interchange6::Schema

example usage:

Angler::Interchange6::Schema->load_classes(qw/ Result::DBICDHStorage Result::DBICDHStorageResult Result::DeploymentHandle /);

=cut

Angler::Interchange6::Schema->load_classes(qw/ Result::DBICDHStorageResult /);

=head1 OVERRIDE

The following Interchange6::Schema classes are overidden

Interchange6::Schema::Result::Product
Interchange6::Schema::Result::Navigation
Interchange6::Schema::ResultSet::Product
Interchange6::Schema::ResultSet::Inventory

=cut

use Angler::Interchange6::Schema::DBICDHStorage;
use Angler::Interchange6::Schema::DeploymentHandler;
use Angler::Interchange6::Schema::Result::Product;
use Angler::Interchange6::Schema::Result::Navigation;
use Angler::Interchange6::Schema::Result::Inventory;
use Angler::Interchange6::Schema::ResultSet::Product;

use base 'Interchange6::Schema';

1;
