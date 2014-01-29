# Interchange UserTag ebay_getsellertransactions v1.2
#
# Copyright (C) 2005  Grant
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA
# 02111-1307, USA.

# See http://developer.ebay.com for more information.

Usertag ebay_getsellertransactions Order
Usertag ebay_getsellertransactions Routine <<EOR
use HTTP::Request::Common;
use LWP::UserAgent;
use XML::Simple;
$XML::Simple::PREFERRED_PARSER = 'XML::Parser';
sub {
	my $user_agent = new LWP::UserAgent(
		timeout => 30,
		agent => 'ebay_getsellertransactions',
	);
        my $request_token = tag_data('ebay_variable', 'value', 'auth_token');
	my $lastmodifiedfrom = $Tag->time( { gmt => '1', adjust => '-47' }, '%Y-%m-%d %H:%M:%S' );
	my $lastmodifiedto = $Tag->time( { gmt => '1' }, '%Y-%m-%d %H:%M:%S' );
	my $dev_token = tag_data('ebay_variable', 'value', 'dev_token');
        my $app_token = tag_data('ebay_variable', 'value', 'app_token');
        my $cert_token = tag_data('ebay_variable', 'value', 'cert_token');
	my $content = "<?xml version='1.0' encoding='iso-8859-1'?><request><RequestToken>$request_token</RequestToken><ErrorLevel>1</ErrorLevel><DetailLevel>4</DetailLevel><Verb>GetSellerTransactions</Verb><SiteId>0</SiteId><LastModifiedFrom>$lastmodifiedfrom</LastModifiedFrom><LastModifiedTo>$lastmodifiedto</LastModifiedTo></request>";
    	my $request = $user_agent->request(POST tag_data('ebay_variable', 'value', 'request_url'),
		X_EBAY_API_COMPATIBILITY_LEVEL => '305',
                X_EBAY_API_SESSION_CERTIFICATE => "$dev_token;$app_token;$cert_token",
		X_EBAY_API_DEV_NAME => $dev_token,
		X_EBAY_API_APP_NAME => $app_token,
		X_EBAY_API_CERT_NAME => $cert_token,
		X_EBAY_API_CALL_NAME => 'GetSellerTransactions',
		X_EBAY_API_SITEID => '0',
		X_EBAY_API_DETAIL_LEVEL => '4',
		Content_Type => 'text/xml',
                Content => $content,
    	);
    	my $response = $request->content;
	if ( index($response, '<?xml') == 0 ) {
	    	my $xml_simple = XML::Simple->new(
      			KeyAttr    => [ ],
      			ForceArray => [ 'Transaction' ],
	    	);
    		my $eBay = $xml_simple->XMLin($response);
	    	my $result = $eBay->{GetSellerTransactionsResult};
	    	my $transactions = $result->{Transactions}->{Transaction};
	        $Scratch->{count_response} = $result->{Transactions}->{Count};
		foreach my $transaction (@$transactions) {
			$Scratch->{item_id_response} .= "$transaction->{ItemId} ";
			$Scratch->{transaction_id_response} .= "$transaction->{TransactionId} ";
	       		$Scratch->{sku_response} .= "$transaction->{ApplicationData} ";
			$Scratch->{quantity_response} .= "$transaction->{QuantityPurchased} ";
			$Scratch->{price_response} .= "$transaction->{Price} ";
			$Scratch->{email_address_response} .= "$transaction->{Buyer}->{User}->{Email} ";
			$Scratch->{user_id_response} .= "$transaction->{Buyer}->{User}->{UserId} ";
		}
	        $Scratch->{getsellertransactions_log} = $response;
	}
	else {
	        $Scratch->{count_response} = '';
	        $Scratch->{getsellertransactions_log} = $response;
	}
}
EOR
