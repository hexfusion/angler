# Interchange UserTag ebay_getsellerlist v1.0
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

Usertag ebay_getsellerlist Order
Usertag ebay_getsellerlist Routine <<EOR
use HTTP::Request::Common;
use LWP::UserAgent;
use XML::Simple;
$XML::Simple::PREFERRED_PARSER = 'XML::Parser';
sub {
	my $user_agent = new LWP::UserAgent(
		timeout => 30,
		agent => 'ebay_getsellerlist',
	);
        my $request_token = tag_data('ebay_variable', 'value', 'auth_token');
	my $dev_token = tag_data('ebay_variable', 'value', 'dev_token');
        my $app_token = tag_data('ebay_variable', 'value', 'app_token');
        my $cert_token = tag_data('ebay_variable', 'value', 'cert_token');
	my $content = "<?xml version='1.0' encoding='iso-8859-1'?><request><RequestToken>$request_token</RequestToken><ErrorLevel>1</ErrorLevel><DetailLevel>16</DetailLevel><Verb>GetSellerList</Verb><SiteId>0</SiteId><Days>0</Days></request>";
    	my $request = $user_agent->request(POST tag_data('ebay_variable', 'value', 'request_url'),
		X_EBAY_API_COMPATIBILITY_LEVEL => '305',
                X_EBAY_API_SESSION_CERTIFICATE => "$dev_token;$app_token;$cert_token",
		X_EBAY_API_DEV_NAME => $dev_token,
		X_EBAY_API_APP_NAME => $app_token,
		X_EBAY_API_CERT_NAME => $cert_token,
		X_EBAY_API_CALL_NAME => 'GetSellerList',
		X_EBAY_API_SITEID => '0',
		X_EBAY_API_DETAIL_LEVEL => '16',
		Content_Type => 'text/xml',
                Content => $content,
    	);
    	my $response = $request->content;
	if ( index($response, '<?xml') == 0 ) {
	    	my $xml_simple = XML::Simple->new(
      			KeyAttr    => [ ],
      			ForceArray => [ 'Item' ],
	    	);
    		my $eBay = $xml_simple->XMLin($response);
	    	my $result = $eBay->{SellerList};
	    	my $items = $result->{Item};
	        $Scratch->{count_response} = $result->{Count};
		foreach my $item (@$items) {
			$Scratch->{id_response} .= "$item->{Id} ";
		}
	        $Scratch->{getsellertransactions_log} = $response;
	}
	else {
	        $Scratch->{count_response} = '';
	        $Scratch->{getsellertransactions_log} = $response;
	}
}
EOR
