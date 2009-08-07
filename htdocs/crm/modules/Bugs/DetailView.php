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
 * $Id: DetailView.php,v 1.43 2006/08/03 00:21:27 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Bugs/Bug.php');
require_once('modules/Bugs/Forms.php');
require_once('include/DetailView/DetailView.php');


global $mod_strings;
global $app_strings;

$focus = new Bug();

$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("BUG", $focus, $offset);
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
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_ID'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Bug detail view");

$xtpl=new XTemplate ('modules/Bugs/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);



$xtpl->assign("BUG_NUMBER", $focus->bug_number);




$xtpl->assign("STATUS", $app_list_strings['bug_status_dom'][$focus->status]);
		//, "created_by"
		//, "created_by_hash"
if (!empty($focus->resolution))
$xtpl->assign("RESOLUTION", $app_list_strings['bug_resolution_dom'][$focus->resolution]);
if (!empty($focus->found_in_release))
$xtpl->assign("RELEASE", $focus->release_name);
if (isset($focus->priority)) {
	$priority=$focus->priority;
}
if (!empty($focus->type))
$xtpl->assign("TYPE", $app_list_strings['bug_type_dom'][$focus->type]);

else {
	$priority = '';
}
$xtpl->assign("PRIORITY", $app_list_strings['bug_priority_dom'][$focus->priority]);
$xtpl->assign("DESCRIPTION", nl2br(url2html($focus->description)));
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);

if (!empty($focus->fixed_in_release))
$xtpl->assign("FIXED_IN_RELEASE", $focus->fixed_in_release_name);
if (isset($focus->work_log)) $xtpl->assign("WORK_LOG", nl2br(url2html($focus->work_log)));

if (!empty($focus->source)) 
$xtpl->assign("SOURCE", $app_list_strings['source_dom'][$focus->source]);


if (!empty($focus->product_category)) 
$xtpl->assign("PRODUCT_CATEGORY", $app_list_strings['product_category_dom'][$focus->product_category]);





global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

$detailView->processListNavigation($xtpl, "BUG", $offset, $focus->is_AuditEnabled());
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse("main.open_source");













$xtpl->parse("main");
$xtpl->out("main");

$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();
ob_start();
echo $old_contents;

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'Bugs');
echo $subpanel->display();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Bugs')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
