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
// $Id: EditView.php,v 1.44 2006/06/08 18:24:46 awu Exp $

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/ProjectTask/ProjectTask.php');
require_once('modules/ProjectTask/Forms.php');
require_once('include/time.php');

global $timedate;
global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;
global $sugar_version, $sugar_config;

// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus = new ProjectTask();

if(!empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("ProjectTask detail view");

$xtpl=new XTemplate ('modules/ProjectTask/EditView.html');
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);

///
/// Populate the fields with existing data
///
$prefillArray = array('name' 	 	 => 'name',
					  'description'  => 'description',
					  'project_name'	 => 'parent_name',
					  'parent_id' 	 => 'parent_id',
					  'percent_complete' => 'percent_complete', 
					  'status' 		 => 'status'					  
 					);
 					
foreach($prefillArray as $requestKey => $focusVar) {
    if (isset($_REQUEST[$requestKey]) && is_null($focus->$focusVar)) {
        $focus->$focusVar = urldecode($_REQUEST[$requestKey]);
    }
} 					

if(isset($_REQUEST['email_id'])) { $xtpl->assign('EMAIL_ID', $_REQUEST['email_id']); }
$xtpl->assign('name', $focus->name);
if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id = $current_user->id;
if (empty($focus->assigned_name) && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );














if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$the_status = empty($_REQUEST['status']) ? $focus->status
	: $_REQUEST['status'];

///////////////////////////////////////
///
/// SETUP DEPENDS ON POPUP

$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'depends_on_id',
		'name' => 'depends_on_name',
		),
	);

$json = getJSONobj();
$encoded_depends_on_popup_request_data = $json->encode($popup_request_data);
$xtpl->assign('encoded_depends_on_popup_request_data', $encoded_depends_on_popup_request_data);

//
///////////////////////////////////////

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

$encoded_parent_popup_request_data = $json->encode($popup_request_data);
$xtpl->assign('encoded_parent_popup_request_data', $encoded_parent_popup_request_data);

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

$options = get_select_options_with_id($app_list_strings['project_task_status_options'], $the_status);
$xtpl->assign('status_options', $options);
$xtpl->assign('id', $focus->id);
$xtpl->assign('date_due', $focus->date_due);
$xtpl->assign('time_due', substr($focus->time_due,0,5));
$xtpl->assign('date_start', $focus->date_start);
$xtpl->assign('time_start', substr($focus->time_start,0,5));
$xtpl->assign('parent_id', $focus->parent_id);
$xtpl->assign('parent_name', $focus->parent_name);
$xtpl->assign('priority_options', get_select_options_with_id($app_list_strings['project_task_priority_options'], $focus->priority));
$xtpl->assign('task_number', $focus->task_number);
$xtpl->assign('depends_on_id', $focus->depends_on_id);
$xtpl->assign('depends_on_name', $focus->depends_on_name);
$xtpl->assign('order_number', $focus->order_number);


if(!empty($focus->milestone_flag) && $focus->milestone_flag == 'on')
{
	$xtpl->assign('milestone_checked', 'checked="checked"');
}
$xtpl->assign('estimated_effort', $focus->estimated_effort);
$xtpl->assign('actual_effort', $focus->actual_effort);
$xtpl->assign('utilization', $focus->utilization);
$xtpl->assign('percent_complete', $focus->percent_complete);
$xtpl->assign('description', $focus->description);
$xtpl->assign('utilization_options',
	get_select_options_with_id($app_list_strings['project_task_utilization_options'],
	$focus->utilization));
$xtpl->assign('task_number', $focus->task_number);

//setting default date and time
if (is_null($focus->date_start))$focus->date_start = $timedate->to_display_date(date('Y-m-d'));
if (is_null($focus->time_start))$focus->time_start = $timedate->to_display_time(date('H:i:s'), true);

//cn:
$xtpl->assign('time_start_meridian', $timedate->AMPMMenu('time_start_', $focus->time_start));
$xtpl->assign('time_due_meridian', $timedate->AMPMMenu('time_due_', $focus->time_due));
//endcn:
$xtpl->assign("user_dateformat", '('. $timedate->get_user_date_format().')');
$xtpl->assign("time_format", '('. $timedate->get_user_time_format().')');
if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign('RETURN_ID', $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
if (isset($_REQUEST['parent_id'])) $xtpl->assign('parent_id', $_REQUEST['parent_id']);
if (isset($_REQUEST['parent_name'])) $xtpl->assign('parent_name', $_REQUEST['parent_name']);


$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
$xtpl->assign("THEME", $theme);

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('parent_name' => $qsd->getQSParent(), 
					'assigned_user_name' => $qsd->getQSUser(),



					);
$sqs_objects['parent_name']['modules'] = array('Project');
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js() . $quicksearch_js);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("id", $focus->id);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}




$xtpl->parse("main.open_source");



$xtpl->parse("main");

$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
$javascript->addToValidateBinaryDependency('parent_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_PARENT_ID'], 'false', '', 'parent_id');




$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
echo $javascript->getScript();

?>
