use utf8;
package Angler::Erp::Schema::Result::Vendor;

=head1 NAME

Angler::Erp::Schema::Result::Vendor - Vendors List Table

=cut

use strict;
use warnings;

use base 'DBIx::Class::Core';

=head1 TABLE: C<Vendor>

=cut

__PACKAGE__->table("Vendor");

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

=head2 accountnumber

  data_type: 'varchar'
  is_nullable: 1
  size: [99,0]

=head2 city

  data_type: 'varchar'
  is_nullable: 1
  size: [31,0]

=head2 companyname

  data_type: 'varchar'
  is_nullable: 1
  size: [40,0]

=head2 country

  data_type: 'varchar'
  is_nullable: 1
  size: [31,0]

=head2 email

  data_type: 'varchar'
  is_nullable: 1
  size: [99,0]

=head2 firstname

  data_type: 'varchar'
  is_nullable: 1
  size: [30,0]

=head2 isinactive

  data_type: 'bit'
  is_nullable: 1
  size: [1,0]

=head2 lastname

  data_type: 'varchar'
  is_nullable: 1
  size: [30,0]

=head2 notes

  data_type: 'varchar'
  is_nullable: 1
  size: [245,0]

=head2 phone

  data_type: 'varchar'
  is_nullable: 1
  size: [21,0]

=head2 phone2

  data_type: 'varchar'
  is_nullable: 1
  size: [21,0]

=head2 phone3

  data_type: 'varchar'
  is_nullable: 1
  size: [21,0]

=head2 phone4

  data_type: 'varchar'
  is_nullable: 1
  size: [21,0]

=head2 postalcode

  data_type: 'varchar'
  is_nullable: 1
  size: [13,0]

=head2 salutation

  data_type: 'varchar'
  is_nullable: 1
  size: [15,0]

=head2 state

  data_type: 'varchar'
  is_nullable: 1
  size: [21,0]

=head2 storeexchangestatus

  data_type: 'varchar'
  is_nullable: 1
  size: [12,0]

=head2 street

  data_type: 'varchar'
  is_nullable: 1
  size: [41,0]

=head2 terms

  data_type: 'varchar'
  is_nullable: 1
  size: [200,0]

=head2 termsdiscount

  data_type: 'decimal'
  is_nullable: 1
  size: [11,2]

=head2 termsdiscountdays

  data_type: 'integer'
  is_nullable: 1
  size: [10,0]

=head2 termsnetdays

  data_type: 'integer'
  is_nullable: 1
  size: [10,0]

=head2 vendorcode

  data_type: 'varchar'
  is_nullable: 1
  size: [3,0]

=head2 customfieldother

  data_type: 'varchar'
  is_nullable: 1
  size: [30,0]

=cut

__PACKAGE__->add_columns(
  "listid",
  { data_type => "varchar", is_nullable => 0, size => [36, 0] },
  "timecreated",
  { data_type => "timestamp", is_nullable => 1, size => [23, 3] },
  "timemodified",
  { data_type => "timestamp", is_nullable => 1, size => [23, 3] },
  "accountnumber",
  { data_type => "varchar", is_nullable => 1, size => [99, 0] },
  "city",
  { data_type => "varchar", is_nullable => 1, size => [31, 0] },
  "companyname",
  { data_type => "varchar", is_nullable => 1, size => [40, 0] },
  "country",
  { data_type => "varchar", is_nullable => 1, size => [31, 0] },
  "email",
  { data_type => "varchar", is_nullable => 1, size => [99, 0] },
  "firstname",
  { data_type => "varchar", is_nullable => 1, size => [30, 0] },
  "isinactive",
  { data_type => "bit", is_nullable => 1, size => [1, 0] },
  "lastname",
  { data_type => "varchar", is_nullable => 1, size => [30, 0] },
  "notes",
  { data_type => "varchar", is_nullable => 1, size => [245, 0] },
  "phone",
  { data_type => "varchar", is_nullable => 1, size => [21, 0] },
  "phone2",
  { data_type => "varchar", is_nullable => 1, size => [21, 0] },
  "phone3",
  { data_type => "varchar", is_nullable => 1, size => [21, 0] },
  "phone4",
  { data_type => "varchar", is_nullable => 1, size => [21, 0] },
  "postalcode",
  { data_type => "varchar", is_nullable => 1, size => [13, 0] },
  "salutation",
  { data_type => "varchar", is_nullable => 1, size => [15, 0] },
  "state",
  { data_type => "varchar", is_nullable => 1, size => [21, 0] },
  "storeexchangestatus",
  { data_type => "varchar", is_nullable => 1, size => [12, 0] },
  "street",
  { data_type => "varchar", is_nullable => 1, size => [41, 0] },
  "terms",
  { data_type => "varchar", is_nullable => 1, size => [200, 0] },
  "termsdiscount",
  { data_type => "decimal", is_nullable => 1, size => [11, 2] },
  "termsdiscountdays",
  { data_type => "integer", is_nullable => 1, size => [10, 0] },
  "termsnetdays",
  { data_type => "integer", is_nullable => 1, size => [10, 0] },
  "vendorcode",
  { data_type => "varchar", is_nullable => 1, size => [3, 0] },
  "customfieldother",
  { data_type => "varchar", is_nullable => 1, size => [30, 0] },
);

=head1 PRIMARY KEY

=over 4

=item * L</listid>

=back

=cut

__PACKAGE__->set_primary_key("listid");


1;

