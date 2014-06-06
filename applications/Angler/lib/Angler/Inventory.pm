package Angler::Inventory;

use strict;
use warnings;
use POSIX qw(strftime);

my $now = strftime "%F %H:%M:%S.000", localtime;

=head2 search_qbpos_sales_receipt($dbh, $start, $end)

Input a timestamp $start and $end this method will return a hash with the following
SalesReceiptItemLine data for items that have been sold during this timeframe.

upc,alu,sales_reciept_number,qty,created,item_id

As these items have been sold each of these items should be removed from inventory

=cut

sub search_qbpos_sales_receipts {
    my ($dbh, $start, $end) = @_;
    unless ($dbh && $start) {
        die "Submodule search_qbpos_sales_receipts requires both dbh and start inputs";
    }
    unless ($end) {
        my $end = $now;
    }
    # This query because of the nathure of the qbpos driver could take 30 mins to run.
    my $sth = $dbh->prepare("select salesreceiptitemupc as upc, salesreceiptitemalu as alu,
        salesreceiptnumber as sales_receipt_number, itemscount as qty, timecreated as created,
        salesreceiptitemlistid as item_id from SalesReceiptItem where TimeCreated >= {ts '$start'} AND TimeCreated <= {ts '$end'}");

    $sth->execute;

    my @receiptlines = $sth->fetchall_arrayref({});

    return \@receiptlines;
}

=head2 search_qbpos_sales_orders($dbh, $start, $end)

This is very simular to sales_receipt_sync except this method searches recent open
sales orders for inventory items.  Although these items have not been paid for yet they
need to be removed from current available inventory and put on hold.

=cut

sub search_qbpos_sales_orders {
    my ($dbh, $start, $end) = @_;

    unless ($dbh && $start) {
        die "Submodule search_qbpos_sales_orders requires both dbh and start inputs";
    }
    unless ($end) {
        my $end = $now;
    }
    # items that are on open sales orders also need to be considered sold.
    my $sth = $dbh->prepare("select salesreceiptitemupc as upc, salesreceiptitemalu as alu,
        salesreceiptnumber as sales_reciept_number, itemscount as qty, timecreated as created,
        salesreceiptitemlistid as item_id from SalesOrderItem where TimeCreated >= {ts '$start'} AND TimeCreated <= {ts '$end'} AND salesorderstatusdesc = 'Open'");

    my @solines = $sth->fetchall_arrayref({});

    return  /@solines;
}

1;
