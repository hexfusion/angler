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
 * $Id: DetailView.php,v 1.23 2006/08/19 06:02:29 chris Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Prospects/Prospect.php');
require_once('modules/Prospects/Forms.php');
require_once('include/DetailView/DetailView.php');
require_once('modules/Leads/Lead.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;


$focus = new Prospect();


$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("PROSPECT", $focus, $offset);
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
echo get_module_title($mod_strings['LBL_MODULE_ID'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->first_name." ".$focus->last_name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Prospect detail view");

$xtpl=new XTemplate ('modules/Prospects/DetailView.html');
$sub_xtpl = new XTemplate ('modules/Prospects/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);

///////////////////////////////////////////////////////////////////////////////
////	TO SUPPORT LEGACY XTEMPLATES
$xtpl->assign('FIRST_NAME', $focus->first_name);
$xtpl->assign('LAST_NAME', $focus->last_name);
$xtpl->assign("SALUTATION", $app_list_strings['salutation_dom'][$focus->salutation]."&nbsp;");
////	END SUPPORT LEGACY XTEMPLATES
///////////////////////////////////////////////////////////////////////////////

$xtpl->assign("FULL_NAME", $focus->full_name);
$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);
if ($focus->birthdate == '0000-00-00') $xtpl->assign("BIRTHDATE", '');
else $xtpl->assign("BIRTHDATE", $focus->birthdate);
if ($focus->do_not_call == 'on') $xtpl->assign("DO_NOT_CALL", "checked");
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
$xtpl->assign("PHONE_WORK", $focus->phone_work);
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("ASSISTANT", $focus->assistant);
$xtpl->assign("ASSISTANT_PHONE", $focus->assistant_phone);
if ($focus->invalid_email == '1') $xtpl->assign("INVALID_EMAIL", "checked");
$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}
if ($focus->email_opt_out == 'on')
{
	$xtpl->assign("EMAIL_OPT_OUT", "checked");
}
$xtpl->assign("PRIMARY_ADDRESS_STREET", nl2br($focus->primary_address_street));
if (empty($focus->primary_address_state))
{
	$xtpl->assign("PRIMARY_ADDRESS_CITY", $focus->primary_address_city);
}
else
{
	$xtpl->assign("PRIMARY_ADDRESS_CITY", $focus->primary_address_city.', ');
}
$xtpl->assign("PRIMARY_ADDRESS_STATE", $focus->primary_address_state);
$xtpl->assign("PRIMARY_ADDRESS_POSTALCODE", $focus->primary_address_postalcode);
$xtpl->assign("PRIMARY_ADDRESS_COUNTRY", $focus->primary_address_country);
$xtpl->assign("ALT_ADDRESS_STREET", nl2br($focus->alt_address_street));
if (empty($focus->alt_address_state))
{
	$xtpl->assign("ALT_ADDRESS_CITY", $focus->alt_address_city);
}
else
{
	$xtpl->assign("ALT_ADDRESS_CITY", $focus->alt_address_city.', ');
}
$xtpl->assign("ALT_ADDRESS_STATE", $focus->alt_address_state);
$xtpl->assign("ALT_ADDRESS_POSTALCODE", $focus->alt_address_postalcode);
$xtpl->assign("ALT_ADDRESS_COUNTRY", $focus->alt_address_country);
$xtpl->assign("DESCRIPTION", nl2br(url2html($focus->description)));
$xtpl->assign("DATE_MODIFIED",$focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);

$detailView->processListNavigation($xtpl, "PROSPECT", $offset);
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse("main.open_source");




$preform = "<table width='100%' border='1' cellspacing='0' cellpadding='0'><tr><td><table width='100%'><tr><td>";
$displayPreform = false;
//$tags = $focus->listviewACLHelper();
if(isset($focus->lead_id) && !empty($focus->lead_id)){
	//get lead name
	$lead = new Lead();
	$lead->retrieve($focus->lead_id);
	
	//$tag = $tags['LEAD'];
	$displayPreform = true;
	$preform .= $mod_strings["LBL_CONVERTED_LEAD"]."&nbsp;<a href='index.php?module=Leads&action=DetailView&record=".$focus->lead_id."'>".$lead->name."</a>";
}
$preform.= "</td></tr></table></td></tr></table>";
if($displayPreform){
	$xtpl->assign("PREVIEW", $preform);
}


$xtpl->parse("main");
$xtpl->out("main");

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'Prospects');
echo $subpanel->display();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Prospects')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;

?>
