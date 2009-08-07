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
 * $Id: DetailView.php,v 1.75 2006/08/10 18:47:52 jenny Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Tasks/Task.php');
require_once('include/time.php');
require_once('include/DetailView/DetailView.php');


global $app_strings;

$focus = new Task();

$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("TASK", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Accounts&action=index");
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

//needed when creating a new task with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['opportunity_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['opportunity_name'];
}
if (isset($_REQUEST['opportunity_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['opportunity_id'];
}
if (isset($_REQUEST['account_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['account_name'];
}
if (isset($_REQUEST['account_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['account_id'];
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Task detail view");

$xtpl=new XTemplate ('modules/Tasks/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("CONTACT_NAME", $focus->contact_name);
$xtpl->assign("CONTACT_PHONE", $focus->contact_phone);
$xtpl->assign("CONTACT_EMAIL", $focus->contact_email);
$xtpl->assign("CONTACT_ID", $focus->contact_id);

if (!empty($focus->parent_type)) $xtpl->assign("PARENT_TYPE", $app_list_strings['record_type_display'][$focus->parent_type]);
if (!empty($focus->parent_type)) $xtpl->assign("PARENT_MODULE", $focus->parent_type);
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

$detailView->processListNavigation($xtpl, "TASK", $offset);
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse("main.open_source");



global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}
$xtpl->assign("STATUS", $focus->status);
if ($focus->date_due == '0000-00-00') $xtpl->assign("DATE_DUE", '');
else $xtpl->assign("DATE_DUE", $focus->date_due);
if ($focus->time_due == '00:00' || $focus->time_due == '00:00:00') $xtpl->assign("TIME_DUE", '');
else $xtpl->assign("TIME_DUE", $focus->time_due);

if ($focus->date_start == '0000-00-00') $xtpl->assign("DATE_START", '');
else $xtpl->assign("DATE_START", $focus->date_start);
if ($focus->time_start == '00:00' || $focus->time_start == '00:00:00') $xtpl->assign("TIME_START", '');
else $xtpl->assign("TIME_START", $focus->time_start);

$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);

$xtpl->assign("DESCRIPTION", nl2br(url2html($focus->description)));

if (!empty($focus->priority)) $xtpl->assign("PRIORITY", $app_list_strings['task_priority_dom'][$focus->priority]);
if (!empty($focus->parent_type)) $xtpl->assign("TYPE", $app_list_strings['record_type_display'][$focus->parent_type]);
$xtpl->assign("STATUS", $app_list_strings['task_status_dom'][$focus->status]);
if ($app_list_strings['task_status_dom'][$focus->status] != "Completed")
{
    $close_and_create_button = '<input title="'.$app_strings['LBL_CLOSE_AND_CREATE_BUTTON_TITLE'].'" ' .
            'accessKey="'.$app_strings['LBL_CLOSE_AND_CREATE_BUTTON_KEY'] .'" class="button" ' .
            'onclick="this.form.status.value=\'Completed\';this.form.action.value=\'Save\';this.form.return_module.value=\'Tasks\';' .
            'this.form.isDuplicate.value=true;this.form.isSaveAndNew.value=true;this.form.return_action.value=\'EditView\'; ' .
            'this.form.return_id.value=\''.$focus->id.'\';" type="submit" name="button" ' .
            'value="' .$app_strings['LBL_CLOSE_AND_CREATE_BUTTON_LABEL'].  '" >';
    $xtpl->assign("CLOSE_AND_CREATE_BUTTON", $close_and_create_button);
}


$xtpl->assign("TAG", $focus->listviewACLHelper());
$xtpl->parse("main");

$xtpl->out("main");

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Tasks')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
