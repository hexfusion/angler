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
function makeStringSafe($str){
   if (get_magic_quotes_gpc()) {
       $str = stripslashes("$str");
   }

   if (function_exists("mysql_real_escape_string") ) {
       	$str = "" . mysql_real_escape_string("$str") . "";
   }else{
   		$str=addslashes("$str");
   }
   return $str;

}

/* UPS user information.  These values will be persisitant in your web application */
	$CFG->ups_userid			= "westbranchangler";				// Enter your UPS User ID
	$CFG->ups_password			= "mayfly6969";				// Enter your UPS Password
	$CFG->ups_xml_access_key	= "9C217B0FE030B960";		// Enter your UPS Access Key
	$CFG->ups_shipper_number	= "992W16";					// Enter your UPS Shipper Number
	$CFG->ups_testmode			= "FALSE";					// "TRUE" for test transactions "FALSE" for live transactions
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
	
$imagedir = "http://www.westbranchangler.com/test_code/";
//Set Ship to POST data

$to_company_name = htmlspecialchars($_GET[company], ENT_QUOTES);			// Ship To Company
$to_company_name = makeStringSafe($_GET['company']);
$to_name = htmlspecialchars($_GET[name], ENT_QUOTES);			// Ship To Company
$to_name  = makeStringSafe($_GET['name']);
$to_address_1 = htmlspecialchars($_GET[address_1], ENT_QUOTES);			// Ship To Company
$to_address_1  = makeStringSafe($_GET['address_1']);
//$to_address_1 = $address_1;
$to_address_2 = htmlspecialchars($_GET[address_2], ENT_QUOTES);			// Ship To Company
$to_address_2  = makeStringSafe($_GET['address_2']);
$to_address_3 = htmlspecialchars($_GET[address_3], ENT_QUOTES);			// Ship To Company
$to_address_3  = makeStringSafe($_GET['address_3']);
$to_city = htmlspecialchars($_GET[city], ENT_QUOTES);			// Ship To Company
$to_city  = makeStringSafe($_GET['city']);
$to_state = htmlspecialchars($_GET[state], ENT_QUOTES);			// Ship To Company
$to_state  = makeStringSafe($_GET['state']);
$to_zip = htmlspecialchars($_GET[zip], ENT_QUOTES);			// Ship To Company
$to_zip  = makeStringSafe($_GET['zip']);
$to_email = htmlspecialchars($_GET[email], ENT_QUOTES);			// Ship To Company
$to_email  = makeStringSafe($_GET['email']);
$to_phone = htmlspecialchars($_GET[phone], ENT_QUOTES);			// Ship To Company
$to_phone  = makeStringSafe($_GET['phone']);
$to_phone = preg_replace("/[^0-9A-Za-z]/", "", $to_phone); //Strip crap out of numbers
$to_country_code = "US";					// Ship To Country Code


$to_phone_dial_plan_number = substr($to_phone, 0, -4); 
$to_phone_line_number  = substr($to_phone, -4);
$to_phone_extension ="";


$pk_packing_type = "02";
$pk_insured_value ="0.00";
$pk_shipping_id = htmlspecialchars($_GET[shipping_id], ENT_QUOTES);
$pk_shipping_id = makeStringSafe($_GET['shipping_id']);
$pk_service = htmlspecialchars($_GET[shipping_service], ENT_QUOTES);			// Ship To Company
$pk_service  = makeStringSafe($_GET['shipping_service']);
$pk_invoice = htmlspecialchars($_GET[order], ENT_QUOTES);			// Ship To Company
$pk_invoice  = makeStringSafe($_GET['order']);
$pk_weight = htmlspecialchars($_GET[package_weight], ENT_QUOTES);			// Ship To Company
$pk_weight  = makeStringSafe($_GET['package_weight']);
$pk_width = htmlspecialchars($_GET[package_width], ENT_QUOTES);			// Ship To Company
$pk_width  = makeStringSafe($_GET['package_width']);
$pk_height = htmlspecialchars($_GET[package_height], ENT_QUOTES);			// Ship To Company
$pk_height  = makeStringSafe($_GET['package_height']);
$pk_length = htmlspecialchars($_GET[package_length], ENT_QUOTES);			// Ship To Company
$pk_length  = makeStringSafe($_GET['package_length']);
$pk_box = htmlspecialchars($_GET[box], ENT_QUOTES);			// Ship To Company
$pk_box  = makeStringSafe($_GET['box']);


	
	$ship_to["company_name"]					= $to_company_name;			// Ship To Company
	$ship_to["attn_name"]						= $to_name;				// Ship To Name
	$ship_to["phone_dial_plan_number"]			= $to_phone_dial_plan_number;					// Ship To First 6 Of Phone Number
	$ship_to["phone_line_number"]				= $to_phone_line_number;					// Ship To Last 4 Of Phone Number
	$ship_to["phone_extension"]					= $phone_extension;					// Ship To Phone Extension
	$ship_to["address_1"]						= $to_address_1;		// Ship To 1st Address Line
	$ship_to["address_2"]						= $to_address_2;						// Ship To 2nd Address Line
	$ship_to["address_3"]						= $to_address_3;						// Ship To 3rd Address Line
	$ship_to["city"]							= $to_city;					// Ship To City
	$ship_to["state_province_code"]				= $to_state;						// Ship To State
	$ship_to["postal_code"]						= $to_zip;					// Ship To Postal Code
	$ship_to["country_code"]					= $country_code;					// Ship To Country Code

	$ship_from["company_name"]					= "West Branch Angler";				// Ship From Company
	$ship_from["attn_name"]						= "Sam Batschelet";						// Ship From Name
	$ship_from["phone_dial_plan_number"]		= "607467";					// Ship From First 6 Of Phone Number
	$ship_from["phone_line_number"]				= "5525";					// Ship From Last 4 Of Phone Number
	$ship_from["phone_extension"]				= "";					// Ship From Phone Extension
	$ship_from["address_1"]						= "150 Faulkner Rd";	// Ship From 1st Address Line
	$ship_from["address_2"]						= "";				// Ship From 2nd Address Line
	$ship_from["address_3"]						= "";						// Ship From 3rd Address Line
	$ship_from["city"]							= "Hancock";					// Ship From City
	$ship_from["state_province_code"]			= "NY";						// Ship From State
	$ship_from["postal_code"]					= "13783";					// Ship From Postal Code
	$ship_from["country_code"]					= "US";						// Ship From Country Code

	$shipment["bill_shipper_account_number"]	= $CFG->ups_shipper_number;	// This will bill the shipper
	$shipment["service_code"]					= $pk_service;
	$shipment["packaging_type"]					= $pk_packing_type;						// 02 For "Your Packaging"
	$shipment["invoice_number"]					= $pk_invoice;					// Invoice Number
	$shipment["weight"]							= $pk_weight;						// Total Weight Of Package (Not Less Than 1lb.)
	$shipment["insured_value"]					= $pk_insured_value;					// Insured Value Of Package
	$shipment["length"]							= $pk_length;						// Package Length
	$shipment["width"]							= $pk_width;						// Package Width
	$shipment["height"]							= $pk_height;						// Package Height
	
	
	
			//Lets make sure we didn't already ship this box
		
	include 'config.php';
	include 'opendb.php';
	
				//Fix me
		$moderator_check_query  = "SELECT * FROM ups_shipments_boxs WHERE shipping_id='$pk_shipping_id' AND box='$pk_box' ";
		
		$moderator_check_exec = mysql_query($moderator_check_query);
		$num_rows = mysql_num_rows($moderator_check_exec); 
		//$num_rows = mysql_num_rows($double);
		
		mysql_query($moderator_check_query) or die('Error, searching for existing box shipment');
		
		$moderator_check_query = "FLUSH PRIVILEGES";
		mysql_query($moderator_check_query) or die('Error, searching for existing box shipment');
		
		include 'closedb.php';
		
		
		// There is already a record
		if ($num_rows != 0) {
			die ("ERROR: There is already a shipment for this box to reship you must cancel the 1st shipment")."<br>";

		
		 } else {
	
	
	
// Include the required functions supplied by the UPS XML Shipping Tool PHP Function Library
include("xmlship.php");

// Post the XML query for UPS ship confirm
$result = ups_ship_confirm($ship_to, $ship_from, $shipment);
	
if ($result["response_status_code"] == 1) {
	// The result was successful
	

	// print "Transportation Charges: ".number_format($result["transportation_charges"],2)."<br>";
	// print "Service Option Charges: ".number_format($result["service_options_charges"],2)."<br>";
	// print "Package Length: ".$result["package_length"]."<br>";
	// print "Package Width: ".$result["package_width"]."<br>";
	// print "Package Height: ".$result["package_height"]."<br>";
	 print "Total Charges: ".number_format($result["total_charges"],2)."<br>";
	// print "Billing Weight: ".$result["billing_weight"]."<br>";
	// print "Tracking Number: ".$result["tracking_number"]."<br>";
	// print "Insured Value: ".number_format($result["insured_value"],2)."<br>";
//	 print "Shipment Digest: ".$result["shipment_digest"]."<br>";
	
	 	$res_transportation_charges = number_format($result["transportation_charges"],2);
		$res_service_options_charges = number_format($result["service_options_charges"],2);
		$res_package_length =  $result["package_length"];
		$res_package_height = $result["package_height"];
		$res_total_charges = number_format($result["total_charges"],2);
		$res_billing_weight = $result["billing_weight"];
		$res_tracking_number = $result["tracking_number"];
		$res_insured_value = number_format($result["insured_value"],2);
		$res_shipment_digest = $result["shipment_digest"];
		$pk_ship_status ="shipped";

	$ship_accept = ups_ship_accept($result['shipment_digest']);

	if ($ship_accept["response_status_code"] == 1) {
	

	
	
		
		include 'config.php';
		include 'opendb.php';
		
	
		
		$query = "INSERT INTO ups_shipments_boxs (shipping_id, code, company_name, attn_name, phone_dial_plan_number, phone_line_number, phone_extension, address_1, address_2, address_3, city, state_province_code, postal_code, country_code, email, bill_shipper_account_number, service_code, packing_type, invoice_number, weight, insured_value, length, width, height, res_service_options_charges, res_package_length, res_package_height, res_total_charges, res_billing_weight, res_tracking_number, res_insured_value, res_shipment_digest, box, ship_status) VALUES ('$pk_shipping_id','$pk_invoice', '$to_company_name', '$to_name', '$to_phone_dial_plan_number', '$to_phone_line_number', '$to_phone_extension','$to_address_1', '$to_address_2', '$to_address_3', '$to_city', '$to_state','$to_zip','$to_country_code', '$to_email','$CFG->ups_shipper_number', '$pk_service','$pk_packing_type', '$pk_invoice', '$pk_weight', '$pk_insured_value', '$pk_length', '$pk_width', '$pk_height', '$res_service_options_charges', '$res_package_length', '$res_package_height', '$res_total_charges', '$res_billing_weight', '$res_tracking_number', '$res_insured_value', '$res_shipment_digest', '$pk_box', '$pk_ship_status')";
		
		mysql_query($query) or die('Error, insert ups_shipments_boxs failed');
		
		$query = "FLUSH PRIVILEGES";
		mysql_query($query) or die('Error, insert ups_shipments_boxs failed');
		
	include 'closedb.php';
	//	echo "New Box Added";
		
		include 'config.php';
		include 'opendb.php';
	
				
		$query2 = "UPDATE ups_shipments_items SET ship_status='shipped' , res_tracking_number = '$res_tracking_number' WHERE order_number = '$pk_invoice' AND shipping_id = '$pk_shipping_id' AND box = '$pk_box' ";
		
		mysql_query($query2) or die('Error, update  ups_shipments_items failed');
		
		$query2 = "FLUSH PRIVILEGES";
		mysql_query($query2) or die('Error, insert ups_shipments_items failed');
		
		include 'closedb.php';
//		echo "Items Updated";
	
		print "Tracking Number: ".$ship_accept["tracking_number"]."<br>";
//		print "Label Image Format: ".$ship_accept["label_image_format"]."<br>";	
	
		$filename = $res_tracking_number.".gif";
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

	//	    echo "Success, wrote grapic image data to file ($filename)<br>";
			echo "click image<br>";
			print "<a href=".$imagedir.$res_tracking_number.".gif target=_blank><img src=".$imagedir.$res_tracking_number.".gif width=640></a>";
		    fclose($handle);

	}

} else {
	// There was an error

	print "Error: ".$result["error_description"];

}
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
