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
// $Id: EditView.php,v 1.111 2006/08/26 00:36:13 wayne Exp $

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Contacts/Forms.php');
require_once('include/TimeDate.php');

global $timedate;
global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
global $sugar_version, $sugar_config;

// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus = new Contact();

if(isset($_REQUEST['record'])) {
	$GLOBALS['log']->debug("In contact edit view, about to retrieve record: ".$_REQUEST['record']);
	$result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die($app_strings['ERROR_NO_RECORD']);
    }
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

//needed when creating a new contact with a default account value passed in
if (isset($_REQUEST['account_name']) && is_null($focus->account_name)) {
	$focus->account_name = $_REQUEST['account_name'];
}
if (isset($_REQUEST['account_id']) && is_null($focus->account_id)) {
	$focus->account_id = $_REQUEST['account_id'];
}

$prefillArray = array('account_name' => 'account_name', 
                      'account_id'   => 'account_id',
                      'first_name'   => 'first_name',
                      'last_name'    => 'last_name',
                      'phone_work'	 => 'phone_work',
                      'email1'       => 'email1',
                      'salutation'	 => 'salutation' );
foreach($prefillArray as $requestKey => $focusVar) {
    if (isset($_REQUEST[$requestKey]) && is_null($focus->$focusVar)) {
        $focus->$focusVar = urldecode($_REQUEST[$requestKey]);
    }
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Contact detail view");
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->first_name." ".$focus->last_name, true);
echo "\n</p>\n";

$xtpl=new XTemplate ('modules/Contacts/EditView.html');


$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);


///////////////////////////////////////
///
/// SETUP POPUPS

$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'reports_to_id',
		'name' => 'report_to_name',
		),
	);

$json = getJSONobj();
$encoded_contact_popup_request_data = $json->encode($popup_request_data);
$xtpl->assign('encoded_contact_popup_request_data', $encoded_contact_popup_request_data);

/// Users Popup
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'assigned_user_id',
		'user_name' => 'assigned_user_name',
		),
	);
$xtpl->assign('encoded_users_popup_request_data', $json->encode($popup_request_data));













if(isset($_REQUEST['record'])) { // do not overwrite address info when editing an record
	$popup_request_data = array(
		'call_back_function' => 'set_return',
		'form_name' => 'EditView',
		'field_to_name_array' => array(
			'id' => 'account_id',
			'name' => 'account_name',
			),
		);
}
else { 
	$popup_request_data = array(
		'call_back_function' => 'set_return',
		'form_name' => 'EditView',
		'field_to_name_array' => array(
			'id' => 'account_id',
			'name' => 'account_name',
			'billing_address_street' => 'primary_address_street',
			'billing_address_city' => 'primary_address_city',
			'billing_address_state' => 'primary_address_state',
			'billing_address_postalcode' => 'primary_address_postalcode',
			'billing_address_country' => 'primary_address_country',
			'phone_office' => 'phone_work',
			),
		);
}

$json = getJSONobj();
$encoded_account_popup_request_data = $json->encode($popup_request_data);
$xtpl->assign('encoded_account_popup_request_data', $encoded_account_popup_request_data);

//
///////////////////////////////////////


// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", "en");

$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}if (isset($_REQUEST['opportunity_id'])) $xtpl->assign("OPPORTUNITY_ID", $_REQUEST['opportunity_id']);



if (isset($_REQUEST['case_id'])) $xtpl->assign("CASE_ID", $_REQUEST['case_id']);
if (isset($_REQUEST['acase_id'])) $xtpl->assign("CASE_ID", $_REQUEST['acase_id']);
if (isset($_REQUEST['bug_id'])) $xtpl->assign("BUG_ID", $_REQUEST['bug_id']);
if(isset($_REQUEST['email_id'])) {	$xtpl->assign("EMAIL_ID", $_REQUEST['email_id']); }
if (! empty($_REQUEST['reports_to_id'])) 
{
	$xtpl->assign("REPORTS_TO_ID", $_REQUEST['reports_to_id']);
} 
else 
{
$xtpl->assign("REPORTS_TO_ID", $focus->reports_to_id);
}
if (isset($_REQUEST['report_to_name']))  $xtpl->assign("REPORT_TO_NAME", $_REQUEST['report_to_name']);
else $xtpl->assign("REPORTS_TO_NAME", $focus->report_to_name);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('account_name' => $qsd->getQSParent(), 
					'assigned_user_name' => $qsd->getQSUser(),



					);
if(isset($_REQUEST['record'])) { // do not overwrite address info when editing an record
	$sqs_objects['account_name']['field_list'] = array('name', 'id');
	$sqs_objects['account_name']['populate_list'] = array('account_name', 'account_id');
}
else {
	$sqs_objects['account_name']['field_list'] = array('name', 'id', 'billing_address_street', 'billing_address_city', 'billing_address_state', 'billing_address_postalcode', 'billing_address_country','phone_office');
	$sqs_objects['account_name']['populate_list'] = array('account_name', 'account_id', 'primary_address_street', 'primary_address_city', 'primary_address_state', 'primary_address_postalcode', 'primary_address_country', 'phone_work');
}
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js() . $quicksearch_js);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("ACCOUNT_ID", $focus->account_id);
$xtpl->assign("HEADER", get_module_title("Contacts", "{MOD.LBL_CONTACT}  ".$focus->first_name." ".$focus->last_name, true));
if (isset($focus->first_name)) $xtpl->assign("FIRST_NAME", $focus->first_name);
else $xtpl->assign("FIRST_NAME", "");
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);
if ($focus->birthdate == '0000-00-00') $xtpl->assign("BIRTHDATE", '');
else $xtpl->assign("BIRTHDATE", $focus->birthdate);
$xtpl->assign('USER_DATEFORMAT', $timedate->get_user_date_format());
if ($focus->do_not_call == 'on') $xtpl->assign("DO_NOT_CALL", "checked");
if (!empty($focus->contacts_users_id)) $xtpl->assign("SYNC_CONTACT", "checked");
$xtpl->assign("DEFAULT_SEARCH", "&account_id=$focus->account_id&account_name=".urlencode($focus->account_name));
$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
if (! empty($_REQUEST['phone_work'])) 
{
 $xtpl->assign("PHONE_WORK", $_REQUEST['phone_work']);
}else
{
 $xtpl->assign("PHONE_WORK", $focus->phone_work);
}
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("ASSISTANT", $focus->assistant);
if ($focus->invalid_email == '1') $xtpl->assign("INVALID_EMAIL", "checked");
$xtpl->assign("ASSISTANT_PHONE", $focus->assistant_phone);
if ($focus->email_opt_out == 'on') $xtpl->assign("EMAIL_OPT_OUT", "checked");
if (isset($_REQUEST['primary_address_street'])) $xtpl->assign("PRIMARY_ADDRESS_STREET", $_REQUEST['primary_address_street']);
else $xtpl->assign("PRIMARY_ADDRESS_STREET", $focus->primary_address_street);
if (isset($_REQUEST['primary_address_city'])) $xtpl->assign("PRIMARY_ADDRESS_CITY", $_REQUEST['primary_address_city']);
else $xtpl->assign("PRIMARY_ADDRESS_CITY", $focus->primary_address_city);
if (isset($_REQUEST['primary_address_state'])) $xtpl->assign("PRIMARY_ADDRESS_STATE", $_REQUEST['primary_address_state']);
else $xtpl->assign("PRIMARY_ADDRESS_STATE", $focus->primary_address_state);
if (isset($_REQUEST['primary_address_postalcode'])) $xtpl->assign("PRIMARY_ADDRESS_POSTALCODE", $_REQUEST['primary_address_postalcode']);
else $xtpl->assign("PRIMARY_ADDRESS_POSTALCODE", $focus->primary_address_postalcode);
if (isset($_REQUEST['primary_address_country'])) $xtpl->assign("PRIMARY_ADDRESS_COUNTRY", $_REQUEST['primary_address_country']);
else $xtpl->assign("PRIMARY_ADDRESS_COUNTRY", $focus->primary_address_country);
$xtpl->assign("ALT_ADDRESS_STREET", $focus->alt_address_street);
$xtpl->assign("ALT_ADDRESS_CITY", $focus->alt_address_city);
$xtpl->assign("ALT_ADDRESS_STATE", $focus->alt_address_state);
$xtpl->assign("ALT_ADDRESS_POSTALCODE", $focus->alt_address_postalcode);
$xtpl->assign("ALT_ADDRESS_COUNTRY", $focus->alt_address_country);
$xtpl->assign("DESCRIPTION", $focus->description);

if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}
if(!ACLController::checkAccess('Accounts','list',true)){
	$xtpl->assign('DISABLED_ACCOUNT', 'disabled="disabled"');
}
if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id = $current_user->id;
if (empty($focus->assigned_name) && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );
$xtpl->assign("LEAD_SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['lead_source_dom'], $focus->lead_source));
$xtpl->assign("SALUTATION_OPTIONS", get_select_options_with_id($app_list_strings['salutation_dom'], $focus->salutation));

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');
















///////////////////////////////////////////////////////////////////////////////
////	INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////
if(isset($_REQUEST['inbound_email_id'])) {
	require_once('modules/Emails/Email.php');
	$email = new Email();
	$email->retrieve($_REQUEST['inbound_email_id']);
	// check lock status
	$email->checkPessimisticLock();
	// change ownership to trigger pessimistic locking
	$email->assigned_user_id = $current_user->id;
	$email->save();
	
	$xtpl->assign('FIRST_NAME', $email->getName('first'));
	$xtpl->assign('LAST_NAME', $email->getName('last'));
	$xtpl->assign('EMAIL1', $email->from_addr);
	$xtpl->assign('INBOUND_EMAIL_ID',$_REQUEST['inbound_email_id']);
	$xtpl->assign('RETURN_ACTION', 'EditView');
	$xtpl->assign('RETURN_MODULE', 'Emails');
	$xtpl->assign('TYPE', 'out');
	$xtpl->assign('NAME', $email->name);
	$xtpl->assign('DESCRIPTION', $email->getDescription());
	$xtpl->assign('START', base64_encode($_SERVER['HTTP_REFERER']));
	
}
////	END INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////

$xtpl->assign("PORTAL_NAME", $focus->portal_name);
if(!empty($focus->portal_active) && $focus->portal_active == 1){
	$xtpl->assign("IS_PORTAL_ACTIVE", 'checked');	
}







$xtpl->parse("main");
$xtpl->out("main");

require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
$javascript->addToValidateBinaryDependency('account_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_ACCOUNT_NAME'], 'false', '', 'account_id');







$javascript->addToValidateBinaryDependency('user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
echo $javascript->getScript();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Contacts')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
