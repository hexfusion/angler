use utf8;
package Angler::Erp::Schema::Result::ItemInventory;

=head1 NAME

Angler::Erp::Schema::Result::ItemInventory - Inventory Items List Table

=cut

use strict;
use warnings;

use base 'DBIx::Class::Core';

=head1 TABLE: C<ItemInventory>

=cut

__PACKAGE__->table("ItemInventory");

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

=head2 alu

  data_type: 'varchar'
  is_nullable: 1
  size: [20,0]

=head2 attribute

  data_type: 'varchar'
  is_nullable: 1
  size: [8,0]

=head2 cogsaccount

  data_type: 'varchar'
  is_nullable: 1
  size: [159,0]

=head2 cost

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 departmentcode

  data_type: 'varchar'
  is_nullable: 1
  size: [3,0]

=head2 departmentlistid

  data_type: 'varchar'
  is_nullable: 1
  size: [36,0]

=head2 desc1

  data_type: 'varchar'
  is_nullable: 1
  size: [30,0]

=head2 desc2

  data_type: 'varchar'
  is_nullable: 1
  size: [30,0]

=head2 incomeaccount

  data_type: 'varchar'
  is_nullable: 1
  size: [159,0]

=head2 isbelowreorder

  data_type: 'bit'
  is_nullable: 1
  size: [1,0]

=head2 iseligibleforcommission

  data_type: 'bit'
  is_nullable: 1
  size: [1,0]

=head2 isprintingtags

  data_type: 'bit'
  is_nullable: 1
  size: [1,0]

=head2 isunorderable

  data_type: 'bit'
  is_nullable: 1
  size: [1,0]

=head2 itemnumber

  data_type: 'integer'
  is_nullable: 1
  size: [10,0]

=head2 itemtype

  data_type: 'varchar'
  is_nullable: 1
  size: [13,0]

=head2 lastreceived

  data_type: 'date'
  is_nullable: 1
  size: [10,0]

=head2 marginpercent

  data_type: 'integer'
  is_nullable: 1
  size: [10,0]

=head2 markuppercent

  data_type: 'integer'
  is_nullable: 1
  size: [10,0]

=head2 msrp

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 onhandstore01

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 onhandstore02

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 onhandstore03

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 onhandstore04

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 onhandstore05

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 onhandstore06

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 onhandstore07

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 onhandstore08

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 onhandstore09

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 onhandstore10

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 ordercost

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 price1

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 price2

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 price3

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 price4

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 price5

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 quantityonhand

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 quantityonorder

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 reorderpoint

  data_type: 'decimal'
  is_nullable: 1
  size: [13,5]

=head2 serialflag

  data_type: 'varchar'
  is_nullable: 1
  size: [8,0]

=head2 size

  data_type: 'varchar'
  is_nullable: 1
  size: [8,0]

=head2 storeexchangestatus

  data_type: 'varchar'
  is_nullable: 1
  size: [12,0]

=head2 taxcode

  data_type: 'varchar'
  is_nullable: 1
  size: [36,0]

=head2 unitofmeasure

  data_type: 'varchar'
  is_nullable: 1
  size: [20,0]

=head2 upc

  data_type: 'varchar'
  is_nullable: 1
  size: [18,0]

=head2 vendorcode

  data_type: 'varchar'
  is_nullable: 1
  size: [3,0]

=head2 vendorlistid

  data_type: 'varchar'
  is_nullable: 1
  size: [36,0]

=head2 vendorinfo2alu

  data_type: 'varchar'
  is_nullable: 1
  size: [20,0]

=head2 vendorinfo2ordercost

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 vendorinfo2upc

  data_type: 'varchar'
  is_nullable: 1
  size: [18,0]

=head2 vendorinfo2vendorlistid

  data_type: 'varchar'
  is_nullable: 1
  size: [36,0]

=head2 vendorinfo3alu

  data_type: 'varchar'
  is_nullable: 1
  size: [20,0]

=head2 vendorinfo3ordercost

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 vendorinfo3upc

  data_type: 'varchar'
  is_nullable: 1
  size: [18,0]

=head2 vendorinfo3vendorlistid

  data_type: 'varchar'
  is_nullable: 1
  size: [36,0]

=head2 vendorinfo4alu

  data_type: 'varchar'
  is_nullable: 1
  size: [20,0]

=head2 vendorinfo4ordercost

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 vendorinfo4upc

  data_type: 'varchar'
  is_nullable: 1
  size: [18,0]

=head2 vendorinfo4vendorlistid

  data_type: 'varchar'
  is_nullable: 1
  size: [36,0]

=head2 vendorinfo5alu

  data_type: 'varchar'
  is_nullable: 1
  size: [20,0]

=head2 vendorinfo5ordercost

  data_type: 'decimal'
  is_nullable: 1
  size: [14,5]

=head2 vendorinfo5upc

  data_type: 'varchar'
  is_nullable: 1
  size: [18,0]

=head2 vendorinfo5vendorlistid

  data_type: 'varchar'
  is_nullable: 1
  size: [36,0]

=head2 customfieldcode

  data_type: 'varchar'
  is_nullable: 1
  size: [30,0]

=head2 customfieldlife_cycle_code

  data_type: 'varchar'
  is_nullable: 1
  size: [30,0]

=head2 customfieldmanf_sku

  data_type: 'varchar'
  is_nullable: 1
  size: [30,0]

=head2 customfieldrelease_season

  data_type: 'varchar'
  is_nullable: 1
  size: [30,0]

=head2 customfieldsku

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
  "alu",
  { data_type => "varchar", is_nullable => 1, size => [20, 0] },
  "attribute",
  { data_type => "varchar", is_nullable => 1, size => [8, 0] },
  "cogsaccount",
  { data_type => "varchar", is_nullable => 1, size => [159, 0] },
  "cost",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "departmentcode",
  { data_type => "varchar", is_nullable => 1, size => [3, 0] },
  "departmentlistid",
  { data_type => "varchar", is_nullable => 1, size => [36, 0] },
  "desc1",
  { data_type => "varchar", is_nullable => 1, size => [30, 0] },
  "desc2",
  { data_type => "varchar", is_nullable => 1, size => [30, 0] },
  "incomeaccount",
  { data_type => "varchar", is_nullable => 1, size => [159, 0] },
  "isbelowreorder",
  { data_type => "bit", is_nullable => 1, size => [1, 0] },
  "iseligibleforcommission",
  { data_type => "bit", is_nullable => 1, size => [1, 0] },
  "isprintingtags",
  { data_type => "bit", is_nullable => 1, size => [1, 0] },
  "isunorderable",
  { data_type => "bit", is_nullable => 1, size => [1, 0] },
  "itemnumber",
  { data_type => "integer", is_nullable => 1, size => [10, 0] },
  "itemtype",
  { data_type => "varchar", is_nullable => 1, size => [13, 0] },
  "lastreceived",
  { data_type => "date", is_nullable => 1, size => [10, 0] },
  "marginpercent",
  { data_type => "integer", is_nullable => 1, size => [10, 0] },
  "markuppercent",
  { data_type => "integer", is_nullable => 1, size => [10, 0] },
  "msrp",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "onhandstore01",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "onhandstore02",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "onhandstore03",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "onhandstore04",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "onhandstore05",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "onhandstore06",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "onhandstore07",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "onhandstore08",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "onhandstore09",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "onhandstore10",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "ordercost",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "price1",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "price2",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "price3",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "price4",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "price5",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "quantityonhand",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "quantityonorder",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "reorderpoint",
  { data_type => "decimal", is_nullable => 1, size => [13, 5] },
  "serialflag",
  { data_type => "varchar", is_nullable => 1, size => [8, 0] },
  "size",
  { data_type => "varchar", is_nullable => 1, size => [8, 0] },
  "storeexchangestatus",
  { data_type => "varchar", is_nullable => 1, size => [12, 0] },
  "taxcode",
  { data_type => "varchar", is_nullable => 1, size => [36, 0] },
  "unitofmeasure",
  { data_type => "varchar", is_nullable => 1, size => [20, 0] },
  "upc",
  { data_type => "varchar", is_nullable => 1, size => [18, 0] },
  "vendorcode",
  { data_type => "varchar", is_nullable => 1, size => [3, 0] },
  "vendorlistid",
  { data_type => "varchar", is_nullable => 1, size => [36, 0] },
  "vendorinfo2alu",
  { data_type => "varchar", is_nullable => 1, size => [20, 0] },
  "vendorinfo2ordercost",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "vendorinfo2upc",
  { data_type => "varchar", is_nullable => 1, size => [18, 0] },
  "vendorinfo2vendorlistid",
  { data_type => "varchar", is_nullable => 1, size => [36, 0] },
  "vendorinfo3alu",
  { data_type => "varchar", is_nullable => 1, size => [20, 0] },
  "vendorinfo3ordercost",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "vendorinfo3upc",
  { data_type => "varchar", is_nullable => 1, size => [18, 0] },
  "vendorinfo3vendorlistid",
  { data_type => "varchar", is_nullable => 1, size => [36, 0] },
  "vendorinfo4alu",
  { data_type => "varchar", is_nullable => 1, size => [20, 0] },
  "vendorinfo4ordercost",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "vendorinfo4upc",
  { data_type => "varchar", is_nullable => 1, size => [18, 0] },
  "vendorinfo4vendorlistid",
  { data_type => "varchar", is_nullable => 1, size => [36, 0] },
  "vendorinfo5alu",
  { data_type => "varchar", is_nullable => 1, size => [20, 0] },
  "vendorinfo5ordercost",
  { data_type => "decimal", is_nullable => 1, size => [14, 5] },
  "vendorinfo5upc",
  { data_type => "varchar", is_nullable => 1, size => [18, 0] },
  "vendorinfo5vendorlistid",
  { data_type => "varchar", is_nullable => 1, size => [36, 0] },
  "customfieldcode",
  { data_type => "varchar", is_nullable => 1, size => [30, 0] },
  "customfieldlife_cycle_code",
  { data_type => "varchar", is_nullable => 1, size => [30, 0] },
  "customfieldmanf_sku",
  { data_type => "varchar", is_nullable => 1, size => [30, 0] },
  "customfieldrelease_season",
  { data_type => "varchar", is_nullable => 1, size => [30, 0] },
  "customfieldsku",
  { data_type => "varchar", is_nullable => 1, size => [30, 0] },
);

=head1 PRIMARY KEY

=over 4

=item * L</listid>

=back

=cut

__PACKAGE__->set_primary_key("listid");


1;

