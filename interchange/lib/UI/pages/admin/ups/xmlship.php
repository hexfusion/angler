<?
/*
UPS XML Shipping Tool PHP Function Library

file: xmlship.php


*** Copyrights

Ownership rights and intellectual property rights of this software belong to
Sonicode.  This software is protected by copyright laws and treaties. Title and
related rights in the content accessed through the software is the property of
the applicable content owner and may be protected by applicable law. This
license gives you no rights to such content.

*** Scope of grant

You may:
-	Use the software on one or more computers.
-	Customize the software's design to suit your own needs.

You may not:
-	Modify and/or remove the copyright notice in the the header of this source file.
-	Reverse engineer, disassemble, or create derivative works based on this script
	for distribution or usage outside your website.
-	Distribute this script without written consent from Sonicode.
-	Permit other individuals to use this script except under the terms listed above.

*** Third party modifications

Technical support will not be provided for third-party modifications to the
software including modifications to code to any license holder.

*** Disclaimer of warranty

The UPS XML Shipping Tool PHP Function Library is provided on an "as is" basis,
without warranty of any kind, including without limitation the warranties of
merchantability, fitness for a particular purpose and non-infringement. The
entire risk as to the quality and performance of this software is borne by you.

*/


/*
** Create a UPS Shipment
*/
function ups_ship_confirm($ship_to, $ship_from, $shipment) {
/////////////////////////////////////////////////////////////////////////////////////////////
// NOTE: The XML request docment contains some static values that can be changed for the
// requirements of your specific application.  Examples include LabelPrintMethod,
// LabelImageFormat, and LabelStockSize.  Please refer to the UPS Developer's Guide for
// allowed values for these fields.
//
// ALSO: Characters such as "&" "<" ">" """ "'" have to be replaced in regard of the W3C
// definition of XML.  These characters will break the XML document if they are not replaced.
//
// Last, but not least; international shipments will require a conversion from Imperial units
// to metric.  The DescriptionOfGoods tag will need to be set in the ups_ship_confirm() XML
// document.
/////////////////////////////////////////////////////////////////////////////////////////////

	/*	The global $CFG variable should contain any persistant data for your shipping
		application.
		
		$CFG->ups_xml_access_key			// Your UPS Premium Tools enabled Access Key
		$CFG->ups_userid					// Your UPS Premium Tools User ID
		$CFG->ups_password					// your UPS Premium Tools Password
		$CFG->ups_shipper_number			// Your Shipper Number
		$CFG->ups_testmode					// TRUE for test transactions, FALSE for live
		$CFG->companyname					// Your Company Name
		$CFG->companystreetaddress1			// Your Street Addres
		$CFG->companystreetaddress2			// Your Street Address
		$CFG->companycity					// Your City
		$CFG->companystate					// Your State
		$CFG->companyzipcode				// Your Zipcode
	
	*/
	global $CFG;

	/* the following is a list of fields that are required by this function
	
	$ship_to array - Where the shipment is shipping to
	$ship_to["company_name"]
	$ship_to["attn_name"]
	$ship_to["phone_dial_plan_number"]
	$ship_to["phone_line_number"]
	$ship_to["phone_extension"]
	$ship_to["address_1"]
	$ship_to["address_2"]
	$ship_to["address_3"]
	$ship_to["city"]
	$ship_to["state_province_code"]
	$ship_to["postal_code"]
	$ship_to["country_code"]

	$ship_from array - Where the shipment is shipping from
	$ship_from["company_name"]
	$ship_from["attn_name"]
	$ship_from["phone_dial_plan_number"]
	$ship_from["phone_line_number"]
	$ship_from["phone_extension"]
	$ship_from["address_1"]Street";
	$ship_from["address_2"]
	$ship_from["address_3"]
	$ship_from["city"]
	$ship_from["state_province_code"]
	$ship_from["postal_code"]
	$ship_from["country_code"]

	$shipment array - Details about the package and service options
	$shipment["bill_shipper_account_number"]
	$shipment["service_code"]
	$shipment["packaging_type"]
	$shipment["invoice_number"]
	$shipment["weight"]
	$shipment["insured_value"]
	*/


	// UPS will not allow a weight value of anything less than 0lbs
	if ($shipment["weight"] < 1) {
		$shipment["weight"] = 1;
	}


	// define some required values
	$access_license_number				= $CFG->ups_xml_access_key;
	$user_id							= $CFG->ups_userid;
	$password							= $CFG->ups_password;
	$label_height						= "4";
	$label_width						= "6";
	$shipper_name						= $CFG->companyname;
	$shipper_attn_name					= "Shipping Department";
	$shipper_phone_dial_plan_number		= "123456";
	$shipper_phone_line_number			= "7890";
	$shipper_phone_extension			= "";
	$label_type							= "GIF";
	$shipper_number						= $CFG->ups_shipper_number;
	$shipper_address_1					= $CFG->companystreetaddress1;
	$shipper_address_2					= $CFG->companystreetaddress2;
	$shipper_address_3					= "";
	$shipper_city						= $CFG->companycity;
	$shipper_state_province_code		= $CFG->companystate;
	$shipper_postal_code				= $CFG->companyzipcode;
	$shipper_country_code				= $CFG->companycountry;
	$dim_units							= "IN";
	$weight_units						= "LBS";
	if ($CFG->ups_testmode == "FALSE") {
		$post_url	= "https://www.ups.com/ups.app/xml/ShipConfirm";
	} else {
		$post_url	= "https://wwwcie.ups.com/ups.app/xml/ShipConfirm";
	}


// construct the xml query document
$xml_request = "<?xml version=\"1.0\"?>
<AccessRequest xml:lang=\"en-US\">
	<AccessLicenseNumber>
		$access_license_number
	</AccessLicenseNumber>
	<UserId>
		$user_id
	</UserId>
	<Password>
		$password
	</Password>
</AccessRequest>
<?xml version=\"1.0\"?>
<ShipmentConfirmRequest xml:lang=\"en-US\">
   <Request>
      <TransactionReference>
         <CustomerContext>ShipConfirmUS</CustomerContext>
         <XpciVersion>1.0001</XpciVersion>
      </TransactionReference>
      <RequestAction>ShipConfirm</RequestAction>
      <RequestOption>nonvalidate</RequestOption>
   </Request>
   <LabelSpecification>
      <LabelPrintMethod>
         <Code>$label_type</Code>
      </LabelPrintMethod>
      <LabelImageFormat>
      	<Code>$label_type</Code>
      </LabelImageFormat>
      <LabelStockSize>
      	<Height>$label_height</Height>
      	<Width>$label_width</Width>
      </LabelStockSize>
   </LabelSpecification>
   <Shipment>
      <Shipper>
         <Name>$shipper_name</Name>
         <AttentionName>$shipper_attn_name</AttentionName>
         <PhoneNumber>
            <StructuredPhoneNumber>
               <PhoneDialPlanNumber>$shipper_phone_dial_plan_number</PhoneDialPlanNumber>
               <PhoneLineNumber>$shipper_phone_line_number</PhoneLineNumber>
               <PhoneExtension>$shipper_phone_extension</PhoneExtension>
            </StructuredPhoneNumber>
         </PhoneNumber>
         <ShipperNumber>$shipper_number</ShipperNumber>
         <Address>
            <AddressLine1>$shipper_address_1</AddressLine1>
            <AddressLine2>$shipper_address_2</AddressLine2>
            <AddressLine3>$shipper_address_3</AddressLine3>
            <City>$shipper_city</City>
            <StateProvinceCode>$shipper_state_province_code</StateProvinceCode>
            <PostalCode>$shipper_postal_code</PostalCode>
            <CountryCode>$shipper_country_code</CountryCode>
         </Address>
      </Shipper>
      <ShipTo>
         <CompanyName>$ship_to[company_name]</CompanyName>
         <AttentionName>$ship_to[attn_name]</AttentionName>
         <PhoneNumber>
            <StructuredPhoneNumber>
               <PhoneDialPlanNumber>$ship_to[phone_dial_plan_number]</PhoneDialPlanNumber>
               <PhoneLineNumber>$ship_to[phone_line_number]</PhoneLineNumber>
               <PhoneExtension>$ship_to[phone_extension]</PhoneExtension>
            </StructuredPhoneNumber>
         </PhoneNumber>
         <Address>
            <AddressLine1>$ship_to[address_1]</AddressLine1>
            <AddressLine2>$ship_to[address_2]</AddressLine2>
            <AddressLine3>$ship_to[address_3]</AddressLine3>
            <City>$ship_to[city]</City>
            <StateProvinceCode>$ship_to[state_province_code]</StateProvinceCode>
            <PostalCode>$ship_to[postal_code]</PostalCode>
            <CountryCode>$ship_to[country_code]</CountryCode>
            <ResidentialAddress/>
         </Address>
      </ShipTo>
      <ShipFrom>
         <CompanyName>$ship_from[company_name]</CompanyName>
         <AttentionName>$ship_from[attn_name]</AttentionName>
         <PhoneNumber>
            <StructuredPhoneNumber>
               <PhoneDialPlanNumber>$ship_from[phone_dial_plan_number]</PhoneDialPlanNumber>
               <PhoneLineNumber>$ship_from[phone_line_number]</PhoneLineNumber>
               <PhoneExtension>$ship_from[phone_extension]</PhoneExtension>
            </StructuredPhoneNumber>
         </PhoneNumber>
         <Address>
            <AddressLine1>$ship_from[address_1]</AddressLine1>
            <AddressLine2>$ship_from[address_2]</AddressLine2>
            <AddressLine3>$ship_from[address_3]</AddressLine3>
            <City>$ship_from[city]</City>
            <StateProvinceCode>$ship_from[state_province_code]</StateProvinceCode>
            <PostalCode>$ship_from[postal_code]</PostalCode>
            <CountryCode>$ship_from[country_code]</CountryCode>
         </Address>
      </ShipFrom>
      <PaymentInformation>
         <Prepaid>
            <BillShipper>
               <AccountNumber>$shipment[bill_shipper_account_number]</AccountNumber>
            </BillShipper>
         </Prepaid>
      </PaymentInformation>
      <Service>
         <Code>$shipment[service_code]</Code>
      </Service>
      <Package>
         <PackagingType>
            <Code>$shipment[packaging_type]</Code>
         </PackagingType>
         <Dimensions>
         	<UnitOfMeasurement>
         		<Code>$dim_units</Code>
         	</UnitOfMeasurement>
         	<Length>$shipment[length]</Length>
         	<Width>$shipment[width]</Width>
         	<Height>$shipment[height]</Height>
         </Dimensions>
         <ReferenceNumber>
            <Code>IK</Code>
            <Value>$shipment[invoice_number]</Value>
         </ReferenceNumber>
         <PackageWeight>
            <UnitOfMeasurement>
               <Code>$weight_units</Code>
            </UnitOfMeasurement>
            <Weight>$shipment[weight]</Weight>
         </PackageWeight>
         <PackageServiceOptions>
            <InsuredValue>
               <CurrencyCode>USD</CurrencyCode>
               <MonetaryValue>$shipment[insured_value]</MonetaryValue>
            </InsuredValue>
         </PackageServiceOptions>
      </Package>
   </Shipment>
</ShipmentConfirmRequest>";

//print '<pre>'.htmlspecialchars($xml_request).'</pre>';

	// execute the curl function and return the result document to $result
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL,$post_url);
	curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_request");
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	$xml_result = curl_exec ($ch);
	curl_close ($ch);

	$data = parse_xml($xml_result);

	$result = array();
	if ($data["ShipmentConfirmResponse"]["#"]["Response"][0]["#"]["ResponseStatusCode"][0]["#"] == 1) {

		$result["response_status_code"]		= $data["ShipmentConfirmResponse"]["#"]["Response"][0]["#"]["ResponseStatusCode"][0]["#"];
		$result["transportation_charges"]	= $data["ShipmentConfirmResponse"]["#"]["ShipmentCharges"][0]["#"]["TransportationCharges"][0]["#"]["MonetaryValue"][0]["#"];
		$result["service_options_charges"]	= $data["ShipmentConfirmResponse"]["#"]["ShipmentCharges"][0]["#"]["ServiceOptionsCharges"][0]["#"]["MonetaryValue"][0]["#"];
		$result["total_charges"]			= $data["ShipmentConfirmResponse"]["#"]["ShipmentCharges"][0]["#"]["TotalCharges"][0]["#"]["MonetaryValue"][0]["#"];
		$result["billing_weight"]			= $data["ShipmentConfirmResponse"]["#"]["BillingWeight"][0]["#"]["Weight"][0]["#"];
		$result["tracking_number"]			= $data["ShipmentConfirmResponse"]["#"]["ShipmentIdentificationNumber"][0]["#"];
		$result["shipment_digest"]			= $data["ShipmentConfirmResponse"]["#"]["ShipmentDigest"][0]["#"];

	} else {

		$result["response_status_code"]		= $data["ShipmentConfirmResponse"]["#"]["Response"][0]["#"]["ResponseStatusCode"][0]["#"];
		$result["error_description"]		= $data["ShipmentConfirmResponse"]["#"]["Response"][0]["#"]["Error"][0]["#"]["ErrorDescription"][0]["#"];

	}

return $result;

}

/*
** Accept a UPS shipment
*/
function ups_ship_accept($shipment_digest) {
	global $CFG;

	$access_license_number	= $CFG->ups_xml_access_key;
	$user_id				= $CFG->ups_userid;
	$password				= $CFG->ups_password;
	if ($CFG->ups_testmode == "FALSE") {
		$post_url	= "https://www.ups.com/ups.app/xml/ShipAccept";
	} else {
		$post_url	= "https://wwwcie.ups.com/ups.app/xml/ShipAccept";
	}

$xml_request = "<?xml version=\"1.0\"?>
<AccessRequest xml:lang=\"en-US\">
	<AccessLicenseNumber>
		$access_license_number
	</AccessLicenseNumber>
	<UserId>
		$user_id
	</UserId>
	<Password>
		$password
	</Password>
</AccessRequest>
<?xml version=\"1.0\"?>
<ShipmentAcceptRequest>
   <Request>
      <TransactionReference>
         <CustomerContext>TR01</CustomerContext>
         <XpciVersion>1.0001</XpciVersion>
      </TransactionReference>
      <RequestAction>ShipAccept</RequestAction>
      <RequestOption>01</RequestOption>
   </Request>
   <ShipmentDigest>$shipment_digest</ShipmentDigest>
</ShipmentAcceptRequest>";


	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL,$post_url);
	curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_request");
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	$xml_result = curl_exec ($ch);
	curl_close ($ch);

	$data = parse_xml($xml_result);

	$result = array();

	if ($data["ShipmentAcceptResponse"]["#"]["Response"][0]["#"]["ResponseStatusCode"][0]["#"] == 1) {
	// there were no errors... so return an array containing the information we need

		$result["response_status_code"]		= $data["ShipmentAcceptResponse"]["#"]["Response"][0]["#"]["ResponseStatusCode"][0]["#"];
		$result["transportation_charges"]	= $data["ShipmentAcceptResponse"]["#"]["ShipmentResults"][0]["#"]["ShipmentCharges"][0]["#"]["TransportationCharges"][0]["#"]["MonetaryValue"][0]["#"];
		$result["service_options_charges"]	= $data["ShipmentAcceptResponse"]["#"]["ShipmentResults"][0]["#"]["ShipmentCharges"][0]["#"]["ServiceOptionsCharges"][0]["#"]["MonetaryValue"][0]["#"];
		$result["total_charges"]			= $data["ShipmentAcceptResponse"]["#"]["ShipmentResults"][0]["#"]["ShipmentCharges"][0]["#"]["TotalCharges"][0]["#"]["MonetaryValue"][0]["#"];
		$result["billing_weight"]			= $data["ShipmentAcceptResponse"]["#"]["ShipmentResults"][0]["#"]["BillingWeight"][0]["#"]["Weight"][0]["#"];
		$result["tracking_number"]			= $data["ShipmentAcceptResponse"]["#"]["ShipmentResults"][0]["#"]["PackageResults"][0]["#"]["TrackingNumber"][0]["#"];
		$result["label_image_format"]		= $data["ShipmentAcceptResponse"]["#"]["ShipmentResults"][0]["#"]["PackageResults"][0]["#"]["LabelImage"][0]["#"]["LabelImageFormat"][0]["#"]["Code"][0]["#"];
		$result["graphic_image"]			= $data["ShipmentAcceptResponse"]["#"]["ShipmentResults"][0]["#"]["PackageResults"][0]["#"]["LabelImage"][0]["#"]["GraphicImage"][0]["#"];

	} else {
	// there was an error... so return an array containing the response code and error description

		$result["response_status_code"]		= $data["ShipmentAcceptResponse"]["#"]["Response"][0]["#"]["ResponseStatusCode"][0]["#"];
		$result["error_description"]		= $data["ShipmentAcceptResponse"]["#"]["Response"][0]["#"]["Error"][0]["#"]["ErrorDescription"][0]["#"];

	}

return $result;

}

/*
** Void an existing UPS Shipment
*/
function ups_ship_void($tracking_number) {
	global $CFG;

	$access_license_number	= $CFG->ups_xml_access_key;
	$user_id				= $CFG->ups_userid;
	$password				= $CFG->ups_password;
	if ($CFG->ups_testmode == "FALSE") {
		$post_url	= "https://www.ups.com/ups.app/xml/Void";
	} else {
		$post_url	= "https://wwwcie.ups.com/ups.app/xml/Void";
	}

$xml_request = "<?xml version=\"1.0\"?>
<AccessRequest xml:lang=\"en-US\">
	<AccessLicenseNumber>
		$access_license_number
	</AccessLicenseNumber>
	<UserId>
		$user_id
	</UserId>
	<Password>
		$password
	</Password>
</AccessRequest>
<?xml version=\"1.0\"?>
<VoidShipmentRequest>
   <Request>
		<TransactionReference>
			<CustomerContext>Void</CustomerContext>
			<XpciVersion>1.0001</XpciVersion>
		</TransactionReference>
			<RequestAction>Void</RequestAction>
			<RequestOption>1</RequestOption>
	</Request>
		<ShipmentIdentificationNumber>$tracking_number</ShipmentIdentificationNumber>
</VoidShipmentRequest>";


	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL,$post_url);
	curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_request");
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	$xml_result = curl_exec ($ch);
	curl_close ($ch);

	$data = parse_xml($xml_result);


	$result = array();

	if ($data["VoidShipmentResponse"]["#"]["Response"][0]["#"]["ResponseStatusCode"][0]["#"] == 1) {
	// there were no errors... so return an array containing the information we need

		$result["response_status_code"]		= $data["VoidShipmentResponse"]["#"]["Response"][0]["#"]["ResponseStatusCode"][0]["#"];

	} else {
	// there was an error... so return an array containing the response code and error description

		$result["response_status_code"]		= $data["VoidShipmentResponse"]["#"]["Response"][0]["#"]["ResponseStatusCode"][0]["#"];
		$result["error_description"]		= $data["VoidShipmentResponse"]["#"]["Response"][0]["#"]["Error"][0]["#"]["ErrorDescription"][0]["#"];

	}

return $result;

}


/*
** Parse the XML document
*/
function xml_get_depth($vals, &$i) { 
	$children = array(); 
	if (isset($vals[$i]['value'])) array_push($children, $vals[$i]['value']); 

	while (++$i < count($vals)) { 

		switch ($vals[$i]['type']) { 

			case 'cdata': 
				array_push($children, $vals[$i]['value']); 
 			break; 

			case 'complete': 
				$tagname = $vals[$i]['tag'];
				if (isset($children["$tagname"])) {
					$size = sizeof($children["$tagname"]);
				} else {
					$size = 0;
				}

				if (isset($vals[$i]['value'])) {
					$children[$tagname][$size]["#"] = $vals[$i]['value'];
				}
				if(isset($vals[$i]["attributes"])) {
					$children[$tagname][$size]["@"] 
									= $vals[$i]["attributes"];
				}
			break; 

			case 'open': 
				$tagname = $vals[$i]['tag'];
				if (isset($children["$tagname"])) {
					$size = sizeof($children["$tagname"]);
				} else {
				$size = 0;
				}
				if(isset($vals[$i]["attributes"])) {
					$children["$tagname"][$size]["@"] 
									= $vals[$i]["attributes"];
					$children["$tagname"][$size]["#"] = xml_get_depth($vals, $i);
				} else {
					$children["$tagname"][$size]["#"] = xml_get_depth($vals, $i);
				}
			break; 

			case 'close':
				return $children; 
			break;
		} 

	} 

	return $children;

}


/*
** Parse the XML document
*/
function parse_xml($data) {

	$vals = $index = $array = array();
	$parser = xml_parser_create();
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, $data, $vals, $index);
	xml_parser_free($parser);

	$i = 0; 

if (isset($vals[$i]['tag'])) {
	$tagname = $vals[$i]['tag'];
	if (isset($vals[$i]["attributes"])) {
		$array[$tagname]["@"] = $vals[$i]["attributes"];
	}

	$array[$tagname]["#"] = xml_get_depth($vals, $i);
}
	return $array;
}

?>
