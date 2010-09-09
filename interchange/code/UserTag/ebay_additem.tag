# Interchange UserTag ebay_additem v1.6
#
# Copyright (C) 2005  Grant
#
# contributions Sam Batschelet
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
#
# 02-11-2009 updated XML to comply with newest API examples -Sam
# 09-08-2010 updated to include item condition 

#See http://developer.ebay.com for more information.

Usertag ebay_additem Order buy_it_now best_offer category condition_id condition_display description domestic_shipping_type domestic_shipping_rate duration int_shipping_type int_shipping_rate payment gallery gallery_url image_url price quantity insurance_fee storecategory subtitletext title type
Usertag ebay_additem Routine <<EOR
use Data::UUID;
use HTTP::Request::Common;
use LWP::UserAgent;
use XML::Simple;
$XML::Simple::PREFERRED_PARSER = 'XML::Parser';
sub {
	my ( $buy_it_now,$best_offer,$category,$condition_id,$condition_display,$description,$domestic_shipping_type,$domestic_shipping_rate,$duration,$int_shipping_type,$int_shipping_rate,$payment,$gallery,$gallery_url,$image_url,$price,$quantity,$insurance_fee,$storecategory,$subtitletext,$title,$type) = @_;
	my $user_agent = new LWP::UserAgent(
		timeout => 30,
		agent => 'ebay_additem',
    	);

$buy_it_now .= $_->{buy_it_now};
$best_offer .= $_->{best_offer};
$category .= $_->{category};
$condition_id .= $_->{condition_id};
$condition_display .= $_->{condition_display};
$description .= $_->{description};
$domestic_shipping_type .= $_->{domestic_shipping_type};
$domestic_shipping_rate .= $_->{domestic_shipping_rate};
$duration .= $_->{duration};
$int_shipping_type .= $_->{int_shipping_type};
$int_shipping_rate .= $_->{int_shipping_rate};
$payment .= $_->{payment};
$gallery_url .= $_->{gallery_url};
$image_url .= $_->{image_url};
$price .= $_->{price};
$quantity .= $_->{quantity};
$insurance_fee .= $_->{insurance_fee};
$storecategory .= $_->{storecategory};
$subtitletext .= $_->{subtitletext};
$title .= $_->{title};
$type .= $_->{type};

#This is needed if you are using htmlarea to view the description in ebay_additem.html
$description = $Tag->filter('textarea_get', $description);

my @strings=( $buy_it_now,$best_offer,$category,$condition_id,$condition_display,$description,$domestic_shipping_type,$domestic_shipping_rate,$duration,$int_shipping_type,$int_shipping_rate,$payment,$gallery,$gallery_url,$image_url,$price,$quantity,$insurance_fee,$storecategory,$subtitletext,$title,$type);

foreach my $string(@strings){
        trim($string);
}

sub trim{
    $_[0]=~s/^\s+//;
    $_[0]=~s/\s+$//;
    return;
}




# Creates a random UUID
	my $ug = new Data::UUID;
        my $uuid =  $ug->create_str();
	$uuid =~ s/[-]//g;
	$title =~ s:"":":g;
        my $request_token = tag_data('ebay_variable', 'value', 'auth_token');
        my $dev_token = tag_data('ebay_variable', 'value', 'dev_token');
        my $app_token = tag_data('ebay_variable', 'value', 'app_token');
        my $cert_token = tag_data('ebay_variable', 'value', 'cert_token');
	my $paypal_email = tag_data('ebay_variable', 'value', 'paypal_email');
        my $content = "<?xml version='1.0' encoding='utf-8'?>" .
			"<AddItemRequest xmlns='urn:ebay:apis:eBLBaseComponents'>" .
			"<RequesterCredentials>" .
			" <eBayAuthToken>AgAAAA**AQAAAA**aAAAAA**sAWJTA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wCl4emCpOApgidj6x9nY+seQ**S5YAAA**AAMAAA**Q2QZzHubyDK5VLWpCde6ZbdayCrorkvKFjOHDc3agS/VZuYS2Aqu7N6ykAdStzMblWMPOsAMT3hi3RWpk6CRzS2RsZYvIO8AzocZpvn9Xbl23imo5NnqjWg707mojHqVh74ambHfC9SSXx7AHXD4UvSDI3XT67o+kQMCtckHf907ahdr+dHKQzbt1Q1AQN+/I5RzGc/Ur0Tlwt9wDawCEa/u8ipB1GOJ3NQxMzekE5ede57U+NemChkPJccQLsQAmekPXPwQVXvJBbDSE2WdeaKYxe4tTWbl7J0WluU0nYSnXJ8t3Km/bimCUfA4dWaIz78Nqm9OhESO+V8fcjEIL772s6ipGOxoicrujkvR+jx1w/72oU8oqUCkKZbLGz9tkMAmoLJ1gLOUEk1C61adgtllyro1WIMBAqMJc51WZlwnVNZHR39zKNB4MP7buwSDTV78KD/7jZnr0eFPNE08O7VSXJK/gd8dl/V0Mafr89sydRr9NOiD2TL4qvmvPtCnEQ1mA8ztTrtPMdW+fquyBYTAOvYviricfqn1/Mi5h/ndB51hpHpAUD9P3LxlZl69O/zIRikJiAI+PBVWO+5Uaume5B+XI3J+1b8mCjU79twGQc9a7dANbQ2Bc+g2RlE9Sa1k1wOpVp5x+nZfo3JBnbt8oRrUBjKuKBGxkDrbMqh+dhrhi9wVn5sxT+UotcdDjMjYw6BqV3ZKd101qU4ctHhEOtcp7IO/Wc1TeEpug47rLLiW+pVozq98n76roDYg</eBayAuthToken>" .
			"</RequesterCredentials>" .
			"<ErrorLanguage>en_US</ErrorLanguage>" .
			"<WarningLevel>High</WarningLevel>" .
			"<Item>" .
			" <SubTitle>$subtitletext</SubTitle>" .
			" <Title>$title</Title>" .
			" <Description>$description</Description>" .
			" <PostalCode>13783</PostalCode>" .
			" <PrimaryCategory>" .
			"  <CategoryID>$category</CategoryID>" .
			" </PrimaryCategory>" .
			" <CategoryMappingAllowed>true</CategoryMappingAllowed>" .
			" <Site>US</Site>" .
			" <Quantity>$quantity</Quantity>" .			
			" <StartPrice>$price</StartPrice>" .
			" <ListingDuration>$duration</ListingDuration>" .
			" <ListingType>$type</ListingType>" .
			" <DispatchTimeMax>3</DispatchTimeMax>" .
			" if ($buy_it_now > 0 { <BuyItNowPrice>$buy_it_now</BuyItNowPrice>};" .
			" <BestOfferDetails>" .
			"  <BestOfferEnabled>$best_offer</BestOfferEnabled>" .
			" </BestOfferDetails>" .
			" <PostalCode>13783</PostalCode>" .
			" <ShippingDetails> " .  
			" <SalesTax>" .
			"  <SalesTaxPercent>8.0</SalesTaxPercent>" . 
			"  <SalesTaxState>NY</SalesTaxState>" .
			"  <ShippingIncludedInTax>false</ShippingIncludedInTax>" .
			" </SalesTax>" .
                        "  <ShippingType>Flat</ShippingType>" .
			"   <ShippingServiceOptions>" .
			"    <ShippingServicePriority>1</ShippingServicePriority>" .
			"    <ShippingService>$domestic_shipping_type</ShippingService>" .
			"    <ShippingServiceCost>$domestic_shipping_rate</ShippingServiceCost>" .
			"    <ShippingServiceAdditionalCost>0.50</ShippingServiceAdditionalCost>" .
			"   </ShippingServiceOptions>" .
			"  <InternationalShippingServiceOption>" .
                        "   <ShippingService>$int_shipping_type</ShippingService>" .
                        "   <ShippingServiceAdditionalCost currencyID='USD'>0.50</ShippingServiceAdditionalCost>" .
                        "   <ShippingServiceCost>$int_shipping_rate</ShippingServiceCost>" .
                        "   <ShippingServicePriority>1</ShippingServicePriority>" .
                        "   <ShipToLocation>Worldwide</ShipToLocation>" .
                        "  </InternationalShippingServiceOption>" .
			" </ShippingDetails>" .
                        " <ReturnPolicy>" .
                        "  <ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>" .
                        "  <RefundOption>MoneyBack</RefundOption>" .
                        "  <ReturnsWithinOption>Days_30</ReturnsWithinOption>" .
                        "  <Description>All items maybe returned within 30 days of purchase unused in same condition as sold for store credit or refund minus any eBay/Paypal fee's incurred to seller for return.  Customer is responsible for return postage as well as any damage.  All items guaranteed as described.</Description>" .
                        "  <ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>" .
                        " </ReturnPolicy>" .
			" <Country>US</Country>" .
			" <Currency>USD</Currency>" .
                        " <PaymentMethods>$payment</PaymentMethods>" .
                        " <PayPalEmailAddress>ebay\@westbranchresort.com</PayPalEmailAddress>" . 
	                " <PictureDetails>" .
                        "  <GalleryType>Gallery</GalleryType>" .
                        "  <GalleryURL>$gallery_url</GalleryURL>" .
                        "  <PictureURL>$image_url</PictureURL>" .
                        " </PictureDetails>" .
			" <UUID>$uuid</UUID>" .
			" <ConditionID>$condition_id</ConditionID>" .
			" <ConditionDisplayName>$condition_display</ConditionDisplayName>" .
                        "</Item>" .
                        "</AddItemRequest>";
	
			
    	my $request = $user_agent->request(POST 'https://api.ebay.com/ws/api.dll',
		X_EBAY_API_COMPATIBILITY_LEVEL => '603',
	        X_EBAY_API_SESSION_CERTIFICATE => "$dev_token;$app_token;$cert_token",
		X_EBAY_API_DEV_NAME => $dev_token,
		X_EBAY_API_APP_NAME => $app_token,
		X_EBAY_API_CERT_NAME => $cert_token,
		X_EBAY_API_CALL_NAME => 'AddItem',
		X_EBAY_API_SITEID => '0',
		X_EBAY_API_DETAIL_LEVEL => '0',
		Content_Type => 'text/xml',
                Content => $content,

    	);
    	my $response = $request->content;
	if ( index($response, '<?xml') == 0 ) {
    		my $xml_simple = XML::Simple->new(
    			KeyAttr    => [ ],
	      		ForceArray => [ ],
    		);
	    	my $eBay = $xml_simple->XMLin($response);
	    	my $result = $eBay->{Item};
	       	$Scratch->{item_id_response} = $result->{Id};
		if(!$Scratch->{item_id_response}) {
	       		$Scratch->{item_id_response} = $result->{DuplicateItemId};
		}
	        $Scratch->{additem_log} = $response;
	}
	else {
	       	$Scratch->{item_id_response} = '';
	        $Scratch->{additem_log} = $response;
	}
}
EOR
    
