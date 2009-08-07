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


/* UPS user information.  These values will be persisitant in your web application */
	$CFG->ups_userid			= "westbranchangler";				// Enter your UPS User ID
	$CFG->ups_password			= "mayfly6969";				// Enter your UPS Password
	$CFG->ups_xml_access_key	= "2C2099897BC29298";		// Enter your UPS Access Key
	$CFG->ups_shipper_number	= "992W16";					// Enter your UPS Shipper Number
	$CFG->ups_testmode			= "TRUE";					// "TRUE" for test transactions "FALSE" for live transactions
	$CFG->companyname			= "West Branch Angler";			// Your Company Name
	$CFG->companystreetaddress1	= "150 Faulkner Rd";	// Your Street Addres
	$CFG->companystreetaddress2	= "";						// Your Street Address
	$CFG->companycity			= "Hancock";				// Your City
	$CFG->companystate			= "NY";						// Your State
	$CFG->companyzipcode		= "13783";					// Your Zipcode
	$CFG->companycountry		= "US";					// Your Country Code

/*
	You will most likely want to load this data from a database, or receive the data from
	a html form.  For details on each of the field requirements, please refer to the UPS
	API Documentation.
*/

	$ship_to["company_name"]					= "Some Company";			// Ship To Company
	$ship_to["attn_name"]						= "Jon Doe";				// Ship To Name
	$ship_to["phone_dial_plan_number"]			= "123456";					// Ship To First 6 Of Phone Number
	$ship_to["phone_line_number"]				= "7890";					// Ship To Last 4 Of Phone Number
	$ship_to["phone_extension"]					= "123";					// Ship To Phone Extension
	$ship_to["address_1"]						= "123 Maple Street";		// Ship To 1st Address Line
	$ship_to["address_2"]						= "";						// Ship To 2nd Address Line
	$ship_to["address_3"]						= "";						// Ship To 3rd Address Line
	$ship_to["city"]							= "Eugene";					// Ship To City
	$ship_to["state_province_code"]				= "OR";						// Ship To State
	$ship_to["postal_code"]						= "97402";					// Ship To Postal Code
	$ship_to["country_code"]					= "US";					// Ship To Country Code

	$ship_from["company_name"]					= "Sonicode";				// Ship From Company
	$ship_from["attn_name"]						= "";						// Ship From Name
	$ship_from["phone_dial_plan_number"]		= "123456";					// Ship From First 6 Of Phone Number
	$ship_from["phone_line_number"]				= "7890";					// Ship From Last 4 Of Phone Number
	$ship_from["phone_extension"]				= "123";					// Ship From Phone Extension
	$ship_from["address_1"]						= "3980 Roosevelt Blvd.";	// Ship From 1st Address Line
	$ship_from["address_2"]						= "Suite C";				// Ship From 2nd Address Line
	$ship_from["address_3"]						= "";						// Ship From 3rd Address Line
	$ship_from["city"]							= "Eugene";					// Ship From City
	$ship_from["state_province_code"]			= "OR";						// Ship From State
	$ship_from["postal_code"]					= "97402";					// Ship From Postal Code
	$ship_from["country_code"]					= "US";						// Ship From Country Code

	$shipment["bill_shipper_account_number"]	= "992W16";	// This will bill the shipper
	$shipment["service_code"]					= "03";
	$shipment["packaging_type"]					= "02";						// 02 For "Your Packaging"
	$shipment["invoice_number"]					= "12345";					// Invoice Number
	$shipment["weight"]							= "2";						// Total Weight Of Package (Not Less Than 1lb.)
	$shipment["insured_value"]					= "120.00";					// Insured Value Of Package
	$shipment["length"]							= "12";						// Package Length
	$shipment["width"]							= "12";						// Package Width
	$shipment["height"]							= "12";						// Package Height
	
// Include the required functions supplied by the UPS XML Shipping Tool PHP Function Library
include("xmlship.php");

// Post the XML query for UPS ship confirm
$result = ups_ship_confirm($ship_to, $ship_from, $shipment);
	
if ($result["response_status_code"] == 1) {
	// The result was successful

	 print "Transportation Charges: ".number_format($result["transportation_charges"],2)."<br>";
	 print "Service Option Charges: ".number_format($result["service_options_charges"],2)."<br>";
	 print "Package Length: ".$result["package_length"]."<br>";
	 print "Package Width: ".$result["package_width"]."<br>";
	 print "Package Height: ".$result["package_height"]."<br>";
	 print "Total Charges: ".number_format($result["total_charges"],2)."<br>";
	 print "Billing Weight: ".$result["billing_weight"]."<br>";
	 print "Tracking Number: ".$result["tracking_number"]."<br>";
	 print "Insured Value: ".number_format($result["insured_value"],2)."<br>";
	 print "Shipment Digest: ".$result["shipment_digest"]."<br>";

	$ship_accept = ups_ship_accept($result['shipment_digest']);

	if ($ship_accept["response_status_code"] == 1) {
	
		print "Tracking Number: ".$ship_accept["tracking_number"]."<br>";
		print "Label Image Format: ".$ship_accept["label_image_format"]."<br>";
	
		$filename = 'test.gif';
		$graphic_image = base64_decode($ship_accept["graphic_image"]);

		    if (!$handle = fopen($filename, 'w')) {
		         echo "Cannot open file ($filename)";
		         exit;
		    }

		    // Write graphic image data to our opened file.
		    if (fwrite($handle, $graphic_image) === FALSE) {
		        echo "Cannot write to file ($filename)";
		        exit;
		    }

		    echo "Success, wrote grapic image data to file ($filename)<br>";
			print '<img src="test.gif" width="640">';
		    fclose($handle);

	}

} else {
	// There was an error

	print "Error Idiot: ".$result["error_description"];

}

/*
NOTES:

Once the Ship Confirm request is successful, you will want to move on to the
Ship Accept transaction.  You will only need to pass the
$result["shipment_digest"] value to the ups_ship_accept function for UPS
to finalize the shipment as "billable" and return the shipping label.  The
shipping label data is base64 encoded for transport from UPS's XML servers.
You will need to simply base64 decode the result data and output the data
to be spooled to the printer.  Thermal label support exists, as well as GIF
labels to be printed on laser printers.  Please consult your UPS Ship Tools
API documentation for more details on shipping options.
*/

?>