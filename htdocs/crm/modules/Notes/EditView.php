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
 * $Id: EditView.php,v 1.77 2006/07/26 00:02:51 awu Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Notes/Note.php');
require_once('modules/Notes/Forms.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $sugar_version, $sugar_config;

$focus = new Note();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
$old_id = '';

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
	if (! empty($focus->filename) )
	{	
	 $old_id = $focus->id;
	}
	$focus->id = "";
}

if (isset ($_REQUEST['name'])) {
	$focus->name = $_REQUEST['name'];
}

if (isset ($_REQUEST['description'])) {
	$focus->description = $_REQUEST['description'];
}

//setting default flag value so due date and time not required
if (!isset($focus->id)) $focus->date_due_flag = 'on';

//needed when creating a new case with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['parent_name']) && is_null($focus->parent_name)) {
	$focus->parent_name = $_REQUEST['parent_name'];
}
if (isset($_REQUEST['parent_id']) && is_null($focus->parent_id)) {
	$focus->parent_id = $_REQUEST['parent_id'];
}
if (isset($_REQUEST['parent_type'])) {
	$focus->parent_type = $_REQUEST['parent_type'];
}
elseif (!isset($focus->parent_type)) {
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}

if($_REQUEST['return_module'] == 'Calendar' && ($focus->parent_type == 'Calls' || $focus->parent_type == 'Meetings')){
	$_REQUEST['return_module'] = $focus->parent_type;
}

if (isset($_REQUEST['filename']) && $_REQUEST['isDuplicate'] != 'true') {
        $focus->filename = $_REQUEST['filename'];
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true); 
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Note detail view");

$xtpl=new XTemplate ('modules/Notes/EditView.html');
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

$json = getJSONobj();

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('parent_name' => $qsd->getQSParent(), 



					);
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) 
					 . ';changeQS();</script>'; // change the parent type of the quicksearch
					 
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js() . $quicksearch_js);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("PARENT_RECORD_TYPE", $focus->parent_type);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("CONTACT_NAME", $focus->contact_name);
$xtpl->assign("CONTACT_PHONE", $focus->contact_phone);
$xtpl->assign("CONTACT_EMAIL", $focus->contact_email);
$xtpl->assign("CONTACT_ID", $focus->contact_id);

$parent_types = $app_list_strings['record_type_display_notes'];
$disabled_parent_types = ACLController::disabledModuleList($parent_types,false, 'list');
foreach($disabled_parent_types as $disabled_parent_type){
	if($disabled_parent_type != $focus->parent_type){
		unset($parent_types[$disabled_parent_type]);
	}
}
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

///////////////////////////////////////
// SETUP PARENT POPUP

$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'parent_id',
		'name' => 'parent_name',
		),
	);

$encoded_popup_request_data = $json->encode($popup_request_data);
$xtpl->assign('encoded_popup_request_data', $encoded_popup_request_data);

//
///////////////////////////////////////

///////////////////////////////////////
// SETUP ACCOUNT POPUP

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











//
///////////////////////////////////////

$xtpl->assign("OLD_ID", $old_id );

if ( empty($focus->filename))
{
	$xtpl->assign("FILENAME_TEXT", "");
	$xtpl->assign("FILENAME", "");
}
else
{
	$xtpl->assign("FILENAME_TEXT", "(".$focus->filename.")");
	$xtpl->assign("FILENAME", $focus->filename);
}
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}

	$change_parent_button = "<input type='button' class='button' title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accesskey='".$app_strings['LBL_SELECT_BUTTON_KEY']."' tabindex='3' value='".$app_strings['LBL_SELECT_BUTTON_LABEL']."' name='change_parent' onclick='open_popup(document.EditView.parent_type.value, 600, 400, \"&request_data=". urlencode($encoded_popup_request_data)."&tree=ProductsProd\", true, false, {});'>";
	$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);


$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($parent_types, $focus->parent_type));

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

$admin = new Administration();
$admin->retrieveSettings();
if (isset($admin->settings['portal_on']) && $admin->settings['portal_on']) 
{
	$toggle_js = <<<ENDQ
	if (this.document.getElementById('parent_type').value == 'Cases') {
		this.document.getElementById('portal_flag_row').style.display = 'inline';
	}
	else {
		this.document.getElementById('portal_flag_row').style.display = 'none';
	}
ENDQ;
	$xtpl->assign("TOGGLE_JS", $toggle_js);
	if ($focus->portal_flag) 
	{
		$xtpl->assign("PORTAL_FLAG", "checked='checked'");
	}
	$xtpl->parse("main.portal_on");
}

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
if(!ACLController::checkAccess('Contacts','list', true)){
	$xtpl->assign('CONTACT_DISABLED', 'disabled="disabled"');
}
$xtpl->parse("main");

$xtpl->out("main");
echo '<script>checkParentType(document.EditView.parent_type.value, document.EditView.change_parent);</script>';
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');




$javascript->addToValidateBinaryDependency('parent_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_MEMBER_OF'], 'false', '', 'parent_id');

echo $javascript->getScript();

?>
