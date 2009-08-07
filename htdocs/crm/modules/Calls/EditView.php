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
 * $Id: EditView.php,v 1.116 2006/08/03 00:01:54 wayne Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once ('XTemplate/xtpl.php');
require_once ('data/Tracker.php');
require_once ('modules/Calls/Call.php');
require_once ('include/time.php');
require_once('include/json_config.php');
$json_config = new json_config();

global $timedate;
global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;
global $sugar_version, $sugar_config;

$json = getJSONobj();
// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus = & new Call();

if (!empty ($_REQUEST['record'])) {
	$focus->retrieve($_REQUEST['record']);
}
if (isset ($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$json_id = $focus->id;
	$focus->id = "";
	//reset call direction and status to outbound and planned.
	$focus->direction = (isset ($app_list_strings['call_direction_dom']['Outbound']) ? 'Outbound' : $focus->direction);
	$focus->status = (isset ($app_list_strings['call_status_dom']['Planned']) ? 'Outbound' : $focus->status);
}

if (isset ($_REQUEST['name'])) {
	$focus->name = $_REQUEST['name'];
}

if (isset ($_REQUEST['description'])) {
	$focus->description = $_REQUEST['description'];
}

if (isset($_REQUEST['date_start']))
	$focus->date_start = $_REQUEST['date_start'];

if (isset($_REQUEST['time_hour_start']))
	$focus->time_hour_start = $_REQUEST['time_hour_start'];

if (isset($_REQUEST['time_minute_start']))
	$focus->time_minute_start = $_REQUEST['time_minute_start'];

if (isset ($_REQUEST['status'])) {
	$focus->status = $_REQUEST['status'];
}
elseif (empty ($focus->status)) {
	$focus->status = $app_list_strings['call_status_default'];
}

if (isset ($_REQUEST['direction'])) {
	$focus->direction = $_REQUEST['direction'];
}
elseif (empty ($focus->direction)) {
	$focus->direction = $app_list_strings['call_direction_default'];
}

if (isset($_REQUEST['duration_hours']))
	$focus->duration_hours = $_REQUEST['duration_hours'];

if (isset($_REQUEST['duration_minutes']))
	$focus->duration_minutes = $_REQUEST['duration_minutes'];

if (!isset ($focus->duration_minutes)) {
	$focus->duration_minutes = $focus->minutes_value_default;
}

//setting default date and time
if (is_null($focus->date_start))
	$focus->date_start = $timedate->to_display_date(gmdate('Y-m-d H:i:s'));
if (is_null($focus->time_start))
	$focus->time_start = $timedate->to_display_time(gmdate('Y-m-d H:i:s'), true);
if (is_null($focus->duration_hours))
	$focus->duration_hours = "0";
if (is_null($focus->duration_minutes))
	$focus->duration_minutes = "1";

//needed when creating a new call with default values passed in
if (isset ($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];

}
if (isset ($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset ($_REQUEST['parent_name'])) {
  $focus->parent_name = $_REQUEST['parent_name'];
}
if (isset ($_REQUEST['parent_id'])) {
	$focus->parent_id = $_REQUEST['parent_id'];
}
if (isset ($_REQUEST['parent_type'])) {
	$focus->parent_type = $_REQUEST['parent_type'];

}
elseif (is_null($focus->parent_type)) {
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path = "themes/".$theme."/";
$image_path = $theme_path."images/";
require_once ($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Call detail view");

$xtpl = new XTemplate('modules/Calls/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", "en");

if (empty ($focus->id))
	$xtpl->assign("USER_ID", $current_user->id);
if (empty ($focus->id) && isset ($_REQUEST['contact_id']))
	$xtpl->assign("CONTACT_ID", $_REQUEST['contact_id']);

if (isset ($_REQUEST['return_module']))
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset ($_REQUEST['return_action']))
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset ($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
} else {
	// if no $_REQUEST['return_id'] is set, and we press "Cancel" we get an error.
	// if RETURN_ACTION is "index", we just go back to module's default page.
	$xtpl->assign("RETURN_ACTION", "index");	
}

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('parent_name' => $qsd->getQSParent(), 
					'assigned_user_name' => $qsd->getQSUser(),



					);
$quicksearch_js = $qsd->getQSScriptsJSONAlreadyDefined();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) 
					 . ';changeQS();</script>'; // change the parent type of the quicksearch
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js() . $quicksearch_js);
$xtpl->assign("ID", $focus->id);
if (isset ($json_id) && !empty ($json_id)) {
	$xtpl->assign("JSON_ID", $json_id);
	$xtpl->assign('JSON_CONFIG_JAVASCRIPT', $json_config->get_static_json_server(false, true, 'Calls', $json_id));
} else {
	$xtpl->assign("JSON_ID", $focus->id);
	$xtpl->assign('JSON_CONFIG_JAVASCRIPT', $json_config->get_static_json_server(false, true, 'Calls', $focus->id));
}

$xtpl->assign("MODULE_NAME", "Calls");
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("PARENT_RECORD_TYPE", $focus->parent_type);
$xtpl->assign("PARENT_ID", $focus->parent_id);
if (isset ($focus->name))
	$xtpl->assign("NAME", $focus->name);
else
	$xtpl->assign("NAME", "");

require_once ('modules/DynamicFields/templates/Files/EditView.php');



























$xtpl->parse("main.open_source");




$xtpl->assign("DATE_START", $focus->date_start);
//$xtpl->assign("TIME_START", substr($focus->time_start,0,5));
if (isset($focus->time_hour_start)) {
	$time_start_hour = $focus->time_hour_start;
}
else {
	$time_start_hour = intval(substr($focus->time_start, 0, 2));
}

if (isset($focus->time_minute_start)) {
	$time_start_minutes = $focus->time_minute_start;
}
else { 
	$time_start_minutes = substr($focus->time_start, 3, 5);
	if ($time_start_minutes > 0 && $time_start_minutes < 15) {
		$time_start_minutes = "15";
	} else
		if ($time_start_minutes > 15 && $time_start_minutes < 30) {
			$time_start_minutes = "30";
		} else
			if ($time_start_minutes > 30 && $time_start_minutes < 45) {
				$time_start_minutes = "45";
			} else
				if ($time_start_minutes > 45) {
					$time_start_hour += 1;
					$time_start_minutes = "00";
				}
}

$xtpl->assign("TIME_START", substr($focus->time_start, 0, 5));
$time_meridiem = $timedate->AMPMMenu('', $focus->time_start, 'onchange="SugarWidgetScheduler.update_time();"');
$xtpl->assign("TIME_MERIDIEM", $time_meridiem);

$hours_arr = array ();
$num_of_hours = 13;
$start_at = 1;

if (empty ($time_meridiem)) {
	$num_of_hours = 24;
	$start_at = 0;
}

for ($i = $start_at; $i < $num_of_hours; $i ++) {
	$i = $i."";
	if (strlen($i) == 1) {
		$i = "0".$i;
	}
	$hours_arr[$i] = $i;
}

$titleHeader = str_replace("</p><p>", "", get_form_header($mod_strings['LBL_SCHEDULING_FORM_TITLE'], "", false));
$xtpl->assign("LBL_SCHEDULING_FORM_TITLE", $titleHeader);
$xtpl->assign("TIME_START_HOUR_OPTIONS", get_select_options_with_id($hours_arr, $time_start_hour));
$xtpl->assign("TIME_START_MINUTE_OPTIONS", get_select_options_with_id($focus->minutes_values, $time_start_minutes));

$xtpl->assign("DESCRIPTION", $focus->description);
$time_format = $timedate->get_user_time_format();
if (preg_match('/\d([^\d])\d/', $time_format, $match)) {
	$xtpl->assign("TIME_SEPARATOR", $match[1]);
} else {
	$xtpl->assign("TIME_SEPARATOR", ":");
}
$xtpl->assign("TIME_FORMAT", '('.$time_format.')');

$xtpl->assign("USER_DATEFORMAT", '('.$timedate->get_user_date_format().')');
$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
if (empty ($focus->assigned_user_id) && empty ($focus->id))
	$focus->assigned_user_id = $current_user->id;
if (empty($focus->assigned_name) && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );
$xtpl->assign("DURATION_HOURS", $focus->duration_hours);
$parent_types = $app_list_strings['record_type_display'];
$disabled_parent_types = ACLController::disabledModuleList($parent_types,false, 'list');
foreach($disabled_parent_types as $disabled_parent_type){
	if($disabled_parent_type != $focus->parent_type){
		unset($parent_types[$disabled_parent_type]);
	}
}
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($parent_types, $focus->parent_type));
$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['call_status_dom'], $focus->status));
$xtpl->assign("DIRECTION_OPTIONS", get_select_options_with_id($app_list_strings['call_direction_dom'], $focus->direction));
$xtpl->assign("DURATION_MINUTES_OPTIONS", get_select_options_with_id($focus->minutes_values, $focus->duration_minutes));

$reminder_time = $current_user->getPreference('reminder_time');
if (!empty ($focus->reminder_time)) {
	$reminder_time = $focus->reminder_time;
}
$xtpl->assign("REMINDER_TIME_OPTIONS", get_select_options_with_id($app_list_strings['reminder_time_options'], $reminder_time));
if (empty ($reminder_time)) {
	$reminder_time = -1;
}
if ($reminder_time > -1) {
	$xtpl->assign("REMINDER_TIME_DISPLAY", 'inline');
	$xtpl->assign("REMINDER_CHECKED", 'checked');
} else {
	$xtpl->assign("REMINDER_TIME_DISPLAY", 'none');
}

if (!empty ($focus->parent_type)) {
	///////////////////////////////////////
	///
	/// SETUP PARENT POPUP

	$popup_request_data = array ('call_back_function' => 'set_return', 'form_name' => 'EditView', 'field_to_name_array' => array ('id' => 'parent_id', 'name' => 'parent_name',),);

	

	// must urlencode to put into the filter request string
	// because IE gets an out of memory error when it is passed
	// as the usual object literal
	$encoded_popup_request_data = urlencode($json->encode($popup_request_data));

	//
	///////////////////////////////////////

	$change_parent_button = "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' tabindex='2' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_SELECT_BUTTON_LABEL']."' name='change_parent' onclick='open_popup(document.EditView.parent_type.value, 600, 400, \"&request_data=$encoded_popup_request_data&tree=ProductsProd\", true, false, {});' />";
	$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
}
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


global $current_user;
if (is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty ($_SESSION['editinplace'])) {
	$record = '';
	if (!empty ($_REQUEST['record'])) {
		$record = $_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT", "<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action']."&from_module=".$_REQUEST['module']."&record=".$record."'>".get_image($image_path."EditLayout", "border='0' alt='Edit Layout' align='bottom'")."</a>");
}
echo '<script>var disabledModules='. $json->encode($disabled_parent_types) . ';</script>';
$xtpl->parse("main");

$xtpl->out("main");
echo '<script>checkParentType(document.EditView.parent_type.value, document.EditView.change_parent);</script>';
require_once ('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');




$javascript->addToValidateBinaryDependency('parent_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_LIST_RELATED_TO'], 'false', '', 'parent_id');
$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
echo $javascript->getScript();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Calls')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;

?>
