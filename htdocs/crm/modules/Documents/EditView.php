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
 * $Id: EditView.php,v 1.38 2006/08/03 00:08:50 wayne Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Documents/Document.php');
require_once('modules/DocumentRevisions/DocumentRevision.php');
require_once('modules/Notes/Forms.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $sugar_version, $sugar_config;

$focus = new Document();
$load_signed=false;
if ((isset($_REQUEST['load_signed_id']) and !empty($_REQUEST['load_signed_id']))) {
	$load_signed=true;
	if (isset($_REQUEST['record'])) {
		$focus->related_doc_id=$_REQUEST['record'];
	}
	if (isset($_REQUEST['selected_revision_id'])) {	
		$focus->related_doc_rev_id=$_REQUEST['selected_revision_id'];
	}
}

if(!$load_signed and isset($_REQUEST['record'])) {
   	$focus->retrieve($_REQUEST['record']);
}
$old_id = '';
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	$focus->id = "";
	$old_id=$_REQUEST['old_id'];
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->document_name, true); 
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Document detail view");

$xtpl=new XTemplate ('modules/Documents/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);














if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);










$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js() . $quicksearch_js);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("DOCUMENT_NAME",$focus->document_name);
$xtpl->assign("DESCRIPTION",$focus->description);
$xtpl->assign("FILENAME_TEXT",$focus->filename);
$xtpl->assign("REVISION",$focus->latest_revision);
$xtpl->assign("OLD_ID",$old_id);

if (isset($focus->id)) {
	$xtpl->assign("FILE_OR_HIDDEN","hidden");
	
	if (!isset($_REQUEST['isDuplicate']) || empty($_REQUEST['isDuplicate'])) {
		$xtpl->assign("DISABLED","disabled");
	}
} else {
	$xtpl->assign("FILE_OR_HIDDEN","file");
}
if (empty($focus->active_date)) {
	global $timedate;
	$xtpl->assign("ACTIVE_DATE",$timedate->to_display_date(gmdate("Y-m-d H:i:s"), true) );
		
} else {
	$xtpl->assign("ACTIVE_DATE",$focus->active_date);
}
$xtpl->assign("EXP_DATE",$focus->exp_date);

if (isset($focus->category_id)) $xtpl->assign("CATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_category_dom'], $focus->category_id));
else $xtpl->assign("CATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_category_dom'], ''));

if (isset($focus->subcategory_id)) $xtpl->assign("SUBCATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_subcategory_dom'], $focus->subcategory_id));
else $xtpl->assign("SUBCATEGORY_OPTIONS", get_select_options_with_id($app_list_strings['document_subcategory_dom'], ''));

if (isset($focus->status_id)) $xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['document_status_dom'], $focus->status_id));
else $xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['document_status_dom'], ''));













$xtpl->parse("main.open_source");




global $timedate;
$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
$xtpl->assign("USER_DATE_FORMAT", $timedate->get_user_date_format());

if ($focus->is_template ==1 ) {
	$xtpl->assign("IS_TEMPLATE_CHECKED","checked");
} else {
	$xtpl->assign("TEMPLATE_TYPE_DISABLED","disabled");
	
}
if (isset($focus->template_type)) $xtpl->assign("TEMPLATE_TYPE_OPTIONS", get_select_options_with_id($app_list_strings['document_template_type_dom'], $focus->template_type));
else $xtpl->assign("TEMPLATE_TYPE_OPTIONS", get_select_options_with_id($app_list_strings['document_template_type_dom'], ''));

$popup_request_data = array(
	'call_back_function' => 'document_set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'related_doc_id',
		'document_name' => 'related_document_name',
		),
	);
$json = getJSONobj();
$xtpl->assign('encoded_document_popup_request_data', $json->encode($popup_request_data));

//get related document name.
if (!empty($focus->related_doc_id)) {
	$xtpl->assign("RELATED_DOCUMENT_NAME",Document::get_document_name($focus->related_doc_id));
	$xtpl->assign("RELATED_DOCUMENT_ID",$focus->related_doc_id);
	if (!empty( $focus->related_doc_rev_id)) {
		$xtpl->assign("RELATED_DOCUMENT_REVISION_OPTIONS", get_select_options_with_id(DocumentRevision::get_document_revisions($focus->related_doc_id), $focus->related_doc_rev_id));
	} else {
		$xtpl->assign("RELATED_DOCUMENT_REVISION_OPTIONS", get_select_options_with_id(DocumentRevision::get_document_revisions($focus->related_doc_id), ''));	
	}
} else {
	$xtpl->assign("RELATED_DOCUMENT_REVISION_DISABLED","disabled");	
}
//set parent information in the form.
if (isset($_REQUEST['parent_id'])) $xtpl->assign("PARENT_ID",$_REQUEST['parent_id']);	

if (isset($_REQUEST['parent_name'])) {
	$xtpl->assign("PARENT_NAME",$_REQUEST['parent_name']);
	
	if (!empty($_REQUEST['parent_type'])) {
		switch (strtolower($_REQUEST['parent_type'])) {
		
			case "contracts" :
				$xtpl->assign("LBL_PARENT_NAME",$mod_strings['LBL_CONTRACT_NAME']);
				break;
			
//todo remove leads case.
			case "leads" :
				$xtpl->assign("LBL_PARENT_NAME",$mod_strings['LBL_CONTRACT_NAME']);
				break;
		}
		$xtpl->parse("main.parent_name");
	}	
}
	
if (isset($_REQUEST['parent_type'])) $xtpl->assign("PARENT_TYPE",$_REQUEST['parent_type']);	

if ($load_signed) {
	$xtpl->assign("RELATED_DOCUMENT_REVISION_DISABLED","disabled");	
	$xtpl->assign("RELATED_DOCUMENT_BUTTON_AVAILABILITY","hidden");	
	$xtpl->assign("LOAD_SIGNED_ID",$_REQUEST['load_signed_id']);
} else {
	$xtpl->assign("RELATED_DOCUMENT_BUTTON_AVAILABILITY","button");	
}

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

$xtpl->parse("main");
$xtpl->out("main");

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Documents')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;

?>
