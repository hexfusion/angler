<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: export_utils.php,v 1.4 2006/07/28 01:39:23 jenny Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

/**
 * gets the system default delimiter or an user-preference based override
 * @return string the delimiter
 */
function getDelimiter() {
	global $sugar_config;
	global $current_user;
	
	$delimiter = ','; // default to "comma"
	$userDelimiter = $current_user->getPreference('export_delimiter');
	$delimiter = empty($sugar_config['export_delimiter']) ? $delimiter : $sugar_config['export_delimiter'];
	$delimiter = empty($userDelimiter) ? $delimiter : $userDelimiter;
	
	return $delimiter;
}


/**
 * builds up a delimited string for export
 * @param string type the bean-type to export
 * @param array records an array of records if coming directly from a query
 * @return string delimited string for export
 */
function export($type, $records = null) {
	global $beanList;
	global $beanFiles;
	global $current_user;
	global $app_strings;

	$contact_fields = array(
		"id"=>"Contact ID"
		,"lead_source"=>"Lead Source"
		,"date_entered"=>"Date Entered"
		,"date_modified"=>"Date Modified"
		,"first_name"=>"First Name"
		,"last_name"=>"Last Name"
		,"salutation"=>"Salutation"
		,"birthdate"=>"Lead Source"
		,"do_not_call"=>"Do Not Call"
		,"email_opt_out"=>"Email Opt Out"
		,"title"=>"Title"
		,"department"=>"Department"
		,"birthdate"=>"Birthdate"
		,"do_not_call"=>"Do Not Call"
		,"phone_home"=>"Phone (Home)"
		,"phone_mobile"=>"Phone (Mobile)"
		,"phone_work"=>"Phone (Work)"
		,"phone_other"=>"Phone (Other)"
		,"phone_fax"=>"Fax"
		,"email1"=>"Email"
		,"email2"=>"Email (Other)"
		,"assistant"=>"Assistant"
		,"assistant_phone"=>"Assistant Phone"
		,"primary_address_street"=>"Primary Address Street"
		,"primary_address_city"=>"Primary Address City"
		,"primary_address_state"=>"Primary Address State"
		,"primary_address_postalcode"=>"Primary Address Postalcode"
		,"primary_address_country"=>"Primary Address Country"
		,"alt_address_street"=>"Other Address Street"
		,"alt_address_city"=>"Other Address City"
		,"alt_address_state"=>"Other Address State"
		,"alt_address_postalcode"=>"Other Address Postalcode"
		,"alt_address_country"=>"Other Address Country"
		,"description"=>"Description"
	);
	
	$account_fields = array(
		"id"=>"Account ID",
		"name"=>"Account Name",
		"website"=>"Website",
		"industry"=>"Industry",
		"account_type"=>"Type",
		"ticker_symbol"=>"Ticker Symbol",
		"employees"=>"Employees",
		"ownership"=>"Ownership",
		"phone_office"=>"Phone",
		"phone_fax"=>"Fax",
		"phone_alternate"=>"Other Phone",
		"email1"=>"Email",
		"email2"=>"Other Email",
		"rating"=>"Rating",
		"sic_code"=>"SIC Code",
		"annual_revenue"=>"Annual Revenue",
		"billing_address_street"=>"Billing Address Street",
		"billing_address_city"=>"Billing Address City",
		"billing_address_state"=>"Billing Address State",
		"billing_address_postalcode"=>"Billing Address Postalcode",
		"billing_address_country"=>"Billing Address Country",
		"shipping_address_street"=>"Shipping Address Street",
		"shipping_address_city"=>"Shipping Address City",
		"shipping_address_state"=>"Shipping Address State",
		"shipping_address_postalcode"=>"Shipping Address Postalcode",
		"shipping_address_country"=>"Shipping Address Country",
		"description"=>"Description"
	);	
	$focus = 0;
	$content = '';

	$bean = $beanList[$type];
	require_once($beanFiles[$bean]);
	$focus = new $bean;

	$db = PearDatabase::getInstance();

	if($records) {
		$records = explode(',', $records);
		$records = "'" . implode("','", $records) . "'";
		$where = "{$focus->table_name}.id in ($records)";
	} elseif (isset($_REQUEST['all']) ) {
		$where = '';
	} else {
		if(isset($_SESSION['export_where']) && !empty($_SESSION['export_where'])) { // bug 4679
			$where = $_SESSION['export_where'];
		} else {
			$where = '';
		}
	}
	
	$order_by = "";
	if($focus->bean_implements('ACL')){
		if(!ACLController::checkAccess($focus->module_dir, 'export', true)){
			ACLController::displayNoAccess();
			sugar_die('');
		}
		if(ACLController::requireOwner($focus->module_dir, 'export')){
			if(!empty($where)){
				$where .= ' AND ';
			}
			$where .= $focus->getOwnerWhere($current_user->id);
		}

	}
    // Export entire list was broken because the where clause already has "where" in it
    // and when the query is built, it has a "where" as well, so the query was ill-formed.
    // Eliminating the "where" here so that the query can be constructed correctly.
    $beginWhere = substr(trim($where), 0, 5);
    if ($beginWhere == "where")
        $where = substr(trim($where), 5, strlen($where));
        
    $query = $focus->create_export_query($order_by,$where);
	$result = $db->query($query, true, $app_strings['ERR_EXPORT_TYPE'].$type.": <BR>.".$query);
	$fields_array = $db->getFieldsArray($result);

	// setup the "header" line with quotation marks
	$header = implode("\"".getDelimiter()."\"", array_values($fields_array));
	$header = "\"" .$header;
	$header .= "\"\r\n";
	$content .= $header;

	while($val = $db->fetchByAssoc($result, -1, false)) {
		$new_arr = array();

		foreach (array_values($val) as $value) {
			array_push($new_arr, preg_replace("/\"/","\"\"", $value));
		}

		$line = implode("\"".getDelimiter()."\"", $new_arr);
		$line = "\"" .$line;
		$line .= "\"\r\n";

		$content .= $line;
	}
	return $content;
}

?>
