use utf8;

package Interchange6::Schema::Result::InventoryLog;

=head1 NAME

Interchange6::Schema::Result::InventoryLog

=cut

use Interchange6::Schema::Candy -components => [qw(InflateColumn::DateTime)];

=head1 DESCRIPTION

The InventoryLog class is used to store the time range used for the last inventory sync
as well as the status of that sync.

=head1 ACCESSORS

=head2 inventory_logs_id

Primary key.

=cut

primary_column inventory_logs_id => {
    data_type         => "integer",
    is_auto_increment => 1,
    sequence          => "inventory_logs_inventory_logs_id_seq",
};

=head2 begin

Date and time when this record was created returned as L<DateTime> object.
Value is auto-set on insert.  This value is used as the start time of the inventory sync.

=cut

column begin => {
    data_type         => "datetime",
};

=head2 end

Date and time when this record was created returned as L<DateTime> object.
Value is auto-set on insert.  This value is used as the end time of the inventory sync.

=cut

column end => {
    data_type         => "datetime",
    set_on_create     => 1,
};

=head2 complete

Did the inventory sync complete? Default is no.

=cut

column complete => {
    data_type     => "boolean",
    default_value => 0,
};

1;
