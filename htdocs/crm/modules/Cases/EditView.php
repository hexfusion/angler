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
 * $Id: EditView.php,v 1.83 2006/08/17 22:32:57 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Cases/Case.php');
require_once('modules/Cases/Forms.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $sugar_version, $sugar_config;


$focus = new aCase();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

//needed when creating a new case with a default account value passed in
if (isset($_REQUEST['account_name']) && is_null($focus->account_name)) {
	$focus->account_name = $_REQUEST['account_name'];

}
if (isset($_REQUEST['account_id']) && is_null($focus->account_id)) {
	$focus->account_id = $_REQUEST['account_id'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}

if (!empty($_REQUEST['status'])) {
	$focus->status = $_REQUEST['status'];
}
elseif (empty($focus->status)) {
	$focus->status = $app_list_strings['case_status_default_key'];
}

$prefillArray = array('priority'	 => 'priority',
					  'name' 	 	 => 'name',
					  'description'  => 'description',



 					);
foreach($prefillArray as $requestKey => $focusVar) {
    if (isset($_REQUEST[$requestKey]) && is_null($focus->$focusVar)) {
        $focus->$focusVar = urldecode($_REQUEST[$requestKey]);
    }
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Case detail view");

$xtpl=new XTemplate ('modules/Cases/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
if(isset($_REQUEST['bug_id'])) $xtpl->assign("BUG_ID", $_REQUEST['bug_id']);
if(isset($_REQUEST['email_id'])) $xtpl->assign("EMAIL_ID", $_REQUEST['email_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$json = getJSONobj();
require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('account_name' => $qsd->getQSParent(), 
					'assigned_user_name' => $qsd->getQSUser(),



					);
$sqs_objects['account_name']['populate_list'] = array('account_name', 'account_id');
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js() . $quicksearch_js);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("ACCOUNT_ID", $focus->account_id);
$xtpl->assign("CONTACT_ID", $focus->contact_id);



if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");


///////////////////////////////////////
///
/// SETUP POPUP

$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'account_id',
		'name' => 'account_name',
		),
	);
	
$encoded_popup_request_data = $json->encode($popup_request_data);
$xtpl->assign('encoded_popup_request_data', $encoded_popup_request_data);
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












//
///////////////////////////////////////

$xtpl->assign("DATE_ENTERED", $focus->date_entered);



$xtpl->assign("CASE_NUMBER", $focus->case_number);




$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("RESOLUTION", $focus->resolution);
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}
if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id = $current_user->id;
if (empty($focus->assigned_name) && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );

$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['case_status_dom'], $focus->status));
if (empty($focus->priority)) {
	$xtpl->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['case_priority_dom'], $app_list_strings['case_priority_default_key']));
}
else {
	$xtpl->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['case_priority_dom'], $focus->priority));
}

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');















///////////////////////////////////////////////////////////////////////////////
////	INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////
if(isset($_REQUEST['case_name'])) {
	$xtpl->assign('NAME',str_replace('_',' ',$_REQUEST['case_name']));
}
if(isset($_REQUEST['inbound_email_id'])) {
	require_once('modules/Emails/Email.php');
	$email = new Email();
	$email->retrieve($_REQUEST['inbound_email_id']);
	// check lock status
	$email->checkPessimisticLock();
	// change ownership to trigger pessimistic locking
	$email->assigned_user_id = $current_user->id;
	$email->save();
	
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


$xtpl->parse("main");

$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addToValidateBinaryDependency('account_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_MEMBER_OF'], 'false', '', 'account_id');




$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
$javascript->addAllFields('');
echo $javascript->getScript();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Cases')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;

?>
