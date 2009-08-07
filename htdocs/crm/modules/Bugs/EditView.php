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
 * $Id: EditView.php,v 1.52 2006/08/16 16:34:11 awu Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Bugs/Bug.php');
require_once('modules/Bugs/Forms.php');
require_once('modules/Releases/Release.php');

global $app_strings;
global $mod_strings;
global $mod_strings;
global $current_user;
global $sugar_version, $sugar_config;

$focus = new Bug();
$seedRelease = new Release();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	$focus->bug_number="";
}

$prefillArray = array('priority'	 => 'priority',
					  'name' 	 	 => 'name',
					  'description'  => 'description',
					  'status' 	 	 => 'status',
					  'type' 	 	 => 'type',
 					);
foreach($prefillArray as $requestKey => $focusVar) {
    if (isset($_REQUEST[$requestKey]) && is_null($focus->$focusVar)) {
        $focus->$focusVar = urldecode($_REQUEST[$requestKey]);
    }
}

if (is_null($focus->status)) {
	$focus->status = $app_list_strings['bug_status_default_key'];
}


echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_ID'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Bug detail view");

$xtpl=new XTemplate ('modules/Bugs/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}



/// Users Popup
$json = getJSONobj();
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'assigned_user_id',
		'user_name' => 'assigned_user_name',
		),
	);
$xtpl->assign('encoded_users_popup_request_data', $json->encode($popup_request_data));













//account_id will be set when user chooses to create a new bug from account detail view.
if (isset($_REQUEST['account_id'])) $xtpl->assign("ACCOUNT_ID", $_REQUEST['account_id']);
if (isset($_REQUEST['contact_id'])) $xtpl->assign("CONTACT_ID", $_REQUEST['contact_id']);
if (isset($_REQUEST['email_id'])) $xtpl->assign("EMAIL_ID", $_REQUEST['email_id']);    
//set the case_id, if set.
//with new concept of subpanels it, when the subpanel is displayed it pulls from the class name which in the situation of Cases is aCase so the form is generated
//with acase_id instead of case_id, so I have done the mapping here
if (isset($_REQUEST['acase_id'])) $xtpl->assign("CASE_ID",$_REQUEST['acase_id']);
else if(isset($_REQUEST['case_id'])) $xtpl->assign("CASE_ID",$_REQUEST['case_id']);
require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();

$sqs_objects = array(
					'assigned_user_name' => $qsd->getQSUser(),



					);
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js(). $quicksearch_js);
$xtpl->assign("ID", $focus->id);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

if (isset($focus->fixed_in_release)) $xtpl->assign("FIXED_IN_RELEASE", $focus->fixed_in_release);
if (isset($focus->work_log)) $xtpl->assign("WORK_LOG", $focus->work_log);


if (!empty($focus->product_category)) {
$xtpl->assign("PRODUCT_CATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['product_category_dom'],$focus->product_category));
}
else {
	$xtpl->assign("PRODUCT_CATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['product_category_dom'],$app_list_strings['product_category_default_key']));
}





$xtpl->assign("BUG_NUMBER", $focus->bug_number);





if(!isset($_REQUEST['isDuplicate'])) $xtpl->assign("BUG_NUMBER", $focus->bug_number);
$xtpl->assign("DESCRIPTION", $focus->description);
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
$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['bug_status_dom'], $focus->status));
if (!empty($focus->resolution)) {
$xtpl->assign("RESOLUTION_OPTIONS", get_select_options_with_id($app_list_strings['bug_resolution_dom'],$focus->resolution));
}
else {
	$xtpl->assign("RESOLUTION_OPTIONS", get_select_options_with_id($app_list_strings['bug_resolution_dom'],$app_list_strings['bug_resolution_default_key']));
}
if (!empty($focus->type)) {
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['bug_type_dom'],$focus->type));
}
else {
	$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['bug_type_dom'],$app_list_strings['bug_type_default_key']));
}

$xtpl->assign("RELEASE_OPTIONS", get_select_options_with_id($seedRelease->get_releases(TRUE, "Active"), $focus->found_in_release));

$xtpl->assign("FIXED_IN_RELEASE_OPTIONS", get_select_options_with_id($seedRelease->get_releases(TRUE, "Active"), $focus->fixed_in_release));



if (empty($focus->priority)) {
	$xtpl->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['bug_priority_dom'], $app_list_strings['bug_priority_default_key']));
}
else {
	$xtpl->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['bug_priority_dom'], $focus->priority));
}

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
	
	$xtpl->assign('INBOUND_EMAIL_ID',$_REQUEST['inbound_email_id']);
	$xtpl->assign('RETURN_ACTION', 'EditView');
	$xtpl->assign('RETURN_MODULE', 'Emails');
	$xtpl->assign('TYPE', 'out');
	$xtpl->assign('NAME', $email->name);
	$xtpl->assign('DESCRIPTION', $email->getDescription());
	//$xtpl->assign('SOURCE', 'InboundEmail');
	$focus->source = 'InboundEmail';
	$xtpl->assign('START', base64_encode($_SERVER['HTTP_REFERER']));
	
}
////	END INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////



if (!empty($focus->source)) {
$xtpl->assign("SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['source_dom'],$focus->source));
}
else {
	$xtpl->assign("SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['source_dom'],$app_list_strings['source_default_key']));
}



$xtpl->parse("main");

$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');




$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');

echo $javascript->getScript();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Bugs')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
