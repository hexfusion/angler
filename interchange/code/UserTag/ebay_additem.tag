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
			" <eBayAuthToken>AgAAAA**AQAAAA**aAAAAA**Xk21SQ**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wCl4emCpOApgidj6x9nY+seQ**S5YAAA**AAMAAA**QZM7ghxRweUPP0+Oz2bgEnWSaguc6HhoiNLqjLBF3dsI8mobxhcq6u/KXoVTKLEeweseF4z/rvWrhQ3RgvH9wxCbnCiW3U4rZpY17V+WtayQhldABx+pv/QivLTIxgkXOz4GSfOoArrzUfHFeNQ6P4Bwh5poObIu8JWTBoy+2qlGCZgD8f9DXfl2Ettxy4WaeKTTQhubH+glu6ClvV0s7+OfBMOTopOKSifG4m8ANYmpKda3Z10aS+pI6CCPGhO3rGkglHBkJnH08AcqPdNwVUBF8LkA0MJ7S24dOAQqjvCaPf2ytxeFzV1MMbE2I2WCFmIr52BCSlIcluFZ/5zQ9ZRmrBqURhZTHzmX4nK7TkQBbq+EfSeOfzihDiGOIOWKYIN7XbZtGCkseORqy6/y0VIuS0Twn0wsxuzQ4I87p7sJmAmbJMiA8CjI6pN3Zt4KGpgFBi86VP6Dm+AVzctfKxpxu0K49QxoyIqxvTBNbLnV4Zr6esIoJoAV9gLG+KLaKK7rIQA6IHV+dAogyAvfj2N5VuCWY8ucBpEPQUZttvqy+GNO9vez8ZbPHb20vowY4zz0d8FXqEpEREYL28acHr6w7LbGEMH/RKzHEOEZr4ubDMWRSeomI/HLaShY76Uc2ZPiIirbzaOEAxKQJhEcCQnbBxsrm2NPKsmUXU6Bwl1It/4oxkzECh8ylxu57MJ0kPWaii6XOjJV8JcIS4Hkr/qKuxj9hXWGXAQ44W4WzYMxlBRrJsbb0ghKES1zAIp2</eBayAuthToken>" .
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
    
