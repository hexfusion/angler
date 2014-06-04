package Angler::Inventory;

use strict;
use warnings;
use POSIX qw(strftime);

my $now = strftime "%F %H:%M:%S", localtime;

=head2 search_qbpos_sales_receipt($schema, $start, $end)

Input a timestamp $start and $end this method will return a hash with the following
SalesReceiptItemLine data for items that have been sold during this timeframe.

upc,alu,sales_reciept_number,qty,created,item_id

As these items have been sold each of these items should be removed from inventory

=cut

sub search_qbpos_sales_receipts {
    my ($schema, $start, $end) = @_;
    unless ($schema && $start) {
        die "Submodule search_qbpos_sales_receipts requires both schema and start time";
    }
    unless ($end) {
        my $end = $now;
    }
    # This query because of the nathure of the qbpos driver could take 30 mins to run.
    my $sales_reciept_rs = $schema->resultset('SalesReceiptItem')->search({
        -and => [
            TimeCreated => { '>=', $start },
            TimeCreated => { '<=', $end },
        ],
    });

    my @receiptlines;

    while (my $record = $sales_receipt_rs->next) {
        #inflate
        my $receipt_item = {$record->get_inflated_columns};
        push @saleslines,
            {
                upc => $item->{salesreceiptitemupc},
                alu => $item->{salesreceiptitemalu},
                sales_reciept_number => $item->{salesreceiptnumber},
                qty => $item->{itemscount},
                created => $item->{timecreated},
                item_id => $item->{salesreceiptitemlistid}
             };
    };
    return \@receiptlines;
}

=head2 search_qbpos_sales_orders($schema, $start, $end)

This is very simular to sales_receipt_sync except this method searches recent open
sales orders for inventory items.  Although these items have not been paid for yet they
need to be removed from current available inventory and put on hold.

=cut

sub search_qbpos_sales_orders {
    my ($schema, $start, $end) = @_;

    unless ($schema && $start) {
        die "Submodule search_qbpos_sales_orders requires both schema and start time";
    }
    unless ($end) {
        my $end = $now;
    }
    # items that are on open sales orders also need to be considered sold.
    my $sales_order_item_rs = $schema->resultset('SalesOrderItem')->search({
        -and => [
            TimeCreated => { '>=', $start },
            TimeCreated => { '<=', $end },
            SalesOrderStatusDesc => { '=', 'Open' },
        ],
    });

    my @solines;

    while (my $record = $sales_order_rs->next) {
        #inflate
        my $so_item = {$record->get_inflated_columns};
        push @solines,
            {
                upc => $item->{salesreceiptitemupc},
                alu => $item->{salesreceiptitemalu},
                sales_reciept_number => $item->{salesreceiptnumber},
                qty => $item->{itemscount},
                created => $item->{timecreated},
                item_id => $item->{salesreceiptitemlistid}
             };
    };
    return  /@solines;
}

1;
