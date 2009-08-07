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
 * $Id: DetailView.php,v 1.37 2006/08/29 20:53:08 awu Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Documents/Document.php');
require_once('modules/DocumentRevisions/DocumentRevision.php');
require_once('include/upload_file.php');
require_once('include/ListView/ListView.php');
require_once('include/DetailView/DetailView.php');


global $app_strings;
global $mod_strings;
global $app_list_strings;
global $gridline;


$mod_strings = return_module_language($current_language, 'Documents');

$focus = new Document();
$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("DOCUMENT", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Accounts&action=index");
}

$old_id="";
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {

	$focus->id = "";
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->document_name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Document detail view");

$xtpl=new XTemplate ('modules/Documents/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);

$xtpl->assign("DOCUMENT_NAME", $focus->document_name);
$xtpl->assign("REVISION", $focus->latest_revision);

if (isset($focus->category_id)) {
	$xtpl->assign("CATEGORY", $app_list_strings['document_category_dom'][$focus->category_id]);
}
if (isset($focus->subcategory_id)) {
	$xtpl->assign("SUBCATEGORY", $app_list_strings['document_subcategory_dom'][$focus->subcategory_id]);
}

global $locale;

$save_file = basename($focus->file_url_noimage);

$xtpl->assign("STATUS", $app_list_strings['document_status_dom'][$focus->status_id]);
$xtpl->assign("DESCRIPTION", from_html($focus->description));
$xtpl->assign("FILE_URL", $focus->file_url);
$xtpl->assign("ACTIVE_DATE", $focus->active_date);
$xtpl->assign("EXP_DATE", $focus->exp_date);
$xtpl->assign("FILE_NAME", $focus->filename);
$xtpl->assign("SAVE_FILE", $save_file);
$xtpl->assign("FILE_URL_NOIMAGE", $focus->file_url_noimage);
$xtpl->assign("LAST_REV_CREATOR", $focus->last_rev_created_name);
if (isset($focus->last_rev_create_date)) {
	$xtpl->assign("LAST_REV_DATE", $focus->last_rev_create_date);
} else {
	$xtpl->assign("LAST_REV_DATE",  "");
}
$xtpl->assign("DOCUMENT_REVISION_ID", $focus->document_revision_id);
$detailView->processListNavigation($xtpl, "DOCUMENT", $offset);











$xtpl->parse("main.open_source");



if (!empty($focus->related_doc_id)) {
	$xtpl->assign("RELATED_DOCUMENT_NAME",Document::get_document_name($focus->related_doc_id));
}
if (!empty($focus->related_doc_rev_id)) {
	$xtpl->assign("RELATED_DOC_REV_ID",$focus->related_doc_rev_id);	
	$xtpl->assign("RELATED_DOCUMENT_VERSION",DocumentRevision::get_document_revision_name($focus->related_doc_rev_id));
}
if (!empty($focus->is_template) && $focus->is_template == 1) {
	$xtpl->assign("IS_TEMPLATE_CHECKED","checked");
}
if (!empty($focus->template_type)) {
	$xtpl->assign("TEMPLATE_TYPE", $app_list_strings['document_template_type_dom'][$focus->template_type]);
}

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');

$xtpl->parse("main");
$xtpl->out("main");

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'Documents');
echo $subpanel->display();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Documents')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
