use utf8;
package Angler::Erp::Schema::Result::Department;

=head1 NAME

Angler::Erp::Schema::Result::Department - Department List Table

=cut

use strict;
use warnings;

use base 'DBIx::Class::Core';

=head1 TABLE: C<Department>

=cut

__PACKAGE__->table("Department");

=head1 ACCESSORS

=head2 listid

  data_type: 'varchar'
  is_nullable: 0
  size: [36,0]

=head2 timecreated

  data_type: 'timestamp'
  is_nullable: 1
  size: [23,3]

=head2 timemodified

  data_type: 'timestamp'
  is_nullable: 1
  size: [23,3]

=head2 defaultmarginpercent

  data_type: 'integer'
  is_nullable: 1
  size: [10,0]

=head2 defaultmarkuppercent

  data_type: 'integer'
  is_nullable: 1
  size: [10,0]

=head2 departmentcode

  data_type: 'varchar'
  is_nullable: 0
  size: [3,0]

=head2 departmentname

  data_type: 'varchar'
  is_nullable: 0
  size: [30,0]

=head2 storeexchangestatus

  data_type: 'varchar'
  is_nullable: 1
  size: [12,0]

=head2 taxcode

  data_type: 'varchar'
  is_nullable: 0
  size: [100,0]

=cut

__PACKAGE__->add_columns(
  "listid",
  { data_type => "varchar", is_nullable => 0, size => [36, 0] },
  "timecreated",
  { data_type => "timestamp", is_nullable => 1, size => [23, 3] },
  "timemodified",
  { data_type => "timestamp", is_nullable => 1, size => [23, 3] },
  "defaultmarginpercent",
  { data_type => "integer", is_nullable => 1, size => [10, 0] },
  "defaultmarkuppercent",
  { data_type => "integer", is_nullable => 1, size => [10, 0] },
  "departmentcode",
  { data_type => "varchar", is_nullable => 0, size => [3, 0] },
  "departmentname",
  { data_type => "varchar", is_nullable => 0, size => [30, 0] },
  "storeexchangestatus",
  { data_type => "varchar", is_nullable => 1, size => [12, 0] },
  "taxcode",
  { data_type => "varchar", is_nullable => 0, size => [100, 0] },
);


=head1 PRIMARY KEY

=over 4

=item * L</listid>

=back

=cut

__PACKAGE__->set_primary_key("listid");


1;

