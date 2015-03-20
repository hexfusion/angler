package Angler::Interchange6::Schema::Result::Navigation;

use strict;
use warnings;

use base 'DBIx::Class::Schema';

use Interchange6::Schema::Result::Navigation;
package Interchange6::Schema::Result::Navigation;

=head1 NAME

Angler::Interchange6::Schema::Result::Navigation

=head1 DESCRIPTION

Adds extra columns and methods to L<Interchange6::Schema::Result::Navigation>.

=head1 ACCESSORS

=head2 code 

ERP link to department.

=cut

__PACKAGE__->add_columns(
    code => {
        data_type => 'varchar',
        size      => 8,
        is_nullable   => 1
    },
);

1;

