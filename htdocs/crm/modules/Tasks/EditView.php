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
 * $Id: EditView.php,v 1.106 2006/08/03 00:13:47 wayne Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Tasks/Task.php');

global $timedate; 
require_once('include/javascript/javascript.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $theme;

$json = getJSONobj();

$focus = new Task();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

if(isset($_REQUEST['name'])) {
    $focus->name = ($_REQUEST['name']);
}

if(isset($_REQUEST['description'])) {
    $focus->description = ($_REQUEST['description']);
}

if(isset($_REQUEST['date_due'])) {
    $focus->date_due = ($_REQUEST['date_due']);
}

if(isset($_REQUEST['time_due'])) {
    $focus->time_due = ($_REQUEST['time_due']);
}

if(isset($_REQUEST['date_start'])) {
    $focus->date_start = ($_REQUEST['date_start']);
}

if(isset($_REQUEST['time_start'])) {
    $focus->time_start = ($_REQUEST['time_start']);
}

if(isset($_REQUEST['priority'])) {
    $focus->priority = $_REQUEST['priority'];
}
elseif (empty($focus->priority)) {
	$focus->priority = $app_list_strings['task_priority_default'];
}

if(isset($_REQUEST['status'])) {
    $focus->status= $_REQUEST['status'];
}
elseif (empty($focus->status)) {
	$focus->status = $app_list_strings['task_status_default'];
}


//setting default flag value so due and start dates and times not required
if (!isset($focus->id)) {
	$focus->date_due_flag = 'on';
	$focus->date_start_flag = 'on';
}

//needed when creating a new case with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['parent_name'])) {
	$focus->parent_name = $_REQUEST['parent_name'];
}
if (isset($_REQUEST['parent_id'])) {
	$focus->parent_id = $_REQUEST['parent_id'];
}
if (isset($_REQUEST['parent_type'])) {
	$focus->parent_type = $_REQUEST['parent_type'];
	
	//If we dont have this below, then you will see the contact show up in the account text field when
	//you first make a task related to a contact
	if($_REQUEST['parent_type']=="Contacts"){
		$focus->parent_name = "";	
	}	
	
}
elseif (!isset($focus->parent_type)) {
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Task detail view");

$xtpl=new XTemplate ('modules/Tasks/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", "en");$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('parent_name' => $qsd->getQSParent(), 
					'assigned_user_name' => $qsd->getQSUser(),



					);
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js() . $quicksearch_js);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("CONTACT_NAME", $focus->contact_name);
$xtpl->assign("CONTACT_PHONE", $focus->contact_phone);
$xtpl->assign("CONTACT_EMAIL", $focus->contact_email);
$xtpl->assign("CONTACT_ID", $focus->contact_id);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
$parent_types = $app_list_strings['record_type_display'];
$disabled_parent_types = ACLController::disabledModuleList($parent_types,false, 'list');
foreach($disabled_parent_types as $disabled_parent_type){
	if($disabled_parent_type != $focus->parent_type){
		unset($parent_types[$disabled_parent_type]);
	}
}
///////////////////////////////////////
///
/// SETUP PARENT POPUP

$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'parent_id',
		'name' => 'parent_name',
		),
	);

$encoded_popup_request_data =$json->encode($popup_request_data);
$change_parent_button = "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' tabindex='2' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_SELECT_BUTTON_LABEL']."' name='change_parent'"." onclick='open_popup(document.EditView.parent_type.value, 600, 400, \"&tree=ProductsProd\", true, false, {$encoded_popup_request_data});' />";
$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
//
///////////////////////////////////////

///////////////////////////////////////
///
/// SETUP CONTACT POPUP

$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'contact_id',
		'name' => 'contact_name',
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













//
///////////////////////////////////////

if ($focus->parent_type == "Account") $xtpl->assign("DEFAULT_SEARCH", "&query=true&account_id=$focus->parent_id&account_name=".urlencode($focus->parent_name));

$xtpl->assign("STATUS", $focus->status);
if ($focus->date_due_flag == 'on') {
	//$xtpl->assign("DATE_DUE_NONE", "checked");
	$xtpl->assign("READONLY", "readonly");
}
if ($focus->date_due == '0000-00-00') $xtpl->assign("DATE_DUE", '');
else $xtpl->assign("DATE_DUE", $focus->date_due);
if ($timedate->to_db_time($focus->time_due) == '00:00') $xtpl->assign("TIME_DUE", '');
else{
	$xtpl->assign("TIME_DUE", substr($focus->time_due,0,5));	
}

if ($focus->date_start_flag == 'on') {
	//$xtpl->assign("DATE_START_NONE", "checked");
	$xtpl->assign("READONLY", "readonly");
}
if ($focus->date_start == '0000-00-00') $xtpl->assign("DATE_START", '');
else $xtpl->assign("DATE_START", $focus->date_start);
if ($timedate->to_db_time($focus->time_start) == '00:00') $xtpl->assign("TIME_START", '');
else{
	$xtpl->assign("TIME_START", substr($focus->time_start,0,5));	
}

$xtpl->assign("DUE_TIME_MERIDIEM", $timedate->AMPMMenu('due_', $focus->time_due));
$xtpl->assign("START_TIME_MERIDIEM", $timedate->AMPMMenu('start_', $focus->time_start));
$xtpl->assign("TIME_FORMAT", '('. $timedate->get_user_time_format().')');
$xtpl->assign("USER_DATEFORMAT", '('. $timedate->get_user_date_format().')');
$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
$xtpl->assign("DESCRIPTION", $focus->description);

if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id = $current_user->id;
if (empty($focus->assigned_name) && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );
$xtpl->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['task_priority_dom'], $focus->priority));

// we have a hack where Contact has its own select box - Contacts needs to be in that
// app_list_strings array for Emails - so hack the hack, and pull Contacts out.
foreach($app_list_strings['record_type_display'] as $k=>$v) {
	if($k != 'Contacts') {
		$recordTypeDisplay[$k] = $v;
	}
}
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id(parse_list_modules($recordTypeDisplay, false), $focus->parent_type));
$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['task_status_dom'], $focus->status));
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
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
	$xtpl->assign('START', base64_encode($_SERVER['HTTP_REFERER']));
	
}
////	END INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////

echo <<<EOQ
<script>
function changeQS() {
	new_module = document.EditView.parent_type.value;
	if(new_module == 'Contacts' || new_module == 'Leads' || typeof(disabledModules[new_module]) != 'undefined') {
		sqs_objects['parent_name']['disable'] = true;
		document.getElementById('parent_name').readOnly = true;
	}
	else {
		sqs_objects['parent_name']['disable'] = false;
		document.getElementById('parent_name').readOnly = false;
	}
	
	sqs_objects['parent_name']['module'] = new_module;	
}
</script>
EOQ;


echo '<script>var disabledModules='. $json->encode($disabled_parent_types) . ';</script>';
if( !ACLController::checkAccess('Contacts', 'list', true)){
	$xtpl->assign('DISABLED_CONTACT', 'disabled="disabled"');
}
$xtpl->parse("main");

$xtpl->out("main");
echo '<script>checkParentType(document.EditView.parent_type.value, document.EditView.change_parent);</script>';
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');




$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
$javascript->addToValidateBinaryDependency('parent_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_LIST_RELATED_TO'], 'false', '', 'parent_id');

echo $javascript->getScript();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Tasks')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
