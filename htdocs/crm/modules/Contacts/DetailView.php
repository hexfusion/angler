<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * DetailView for Contacts
 *
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
 */

// $Id: DetailView.php,v 1.114 2006/08/25 01:19:54 wayne Exp $

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Contacts/Forms.php');
require_once('include/DetailView/DetailView.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;


$focus = new Contact();

$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("CONTACT", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus = $result;
	$focus->_create_proper_name_field();
} else {
	header("Location: index.php?module=Accounts&action=index");
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
////	END SETUP
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	OUTPUT 
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->first_name." ".$focus->last_name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Contact detail view");

$xtpl=new XTemplate ('modules/Contacts/DetailView.html');
$sub_xtpl = new XTemplate ('modules/Contacts/DetailView.html');

$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("PREFORM", "<form name='vcard' action='vCard.php'><input type='hidden' name='contact_id' value='".$focus->id."'></form>");
$xtpl->assign('NAME', $focus->name);
$xtpl->assign("VCARD_LINK", "<input type='button' class='button' name='vCardButton' value='vCard' onClick='document.vcard.submit();' title='vCard'>");
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("ACCOUNT_ID", $focus->account_id);
$LS = trim($focus->lead_source);
if(!empty($LS)){
	$xtpl->assign("LEAD_SOURCE", $app_list_strings['lead_source_dom'][$focus->lead_source]);
} else {
	$xtpl->assign("LEAD_SOURCE", " ");
}

///////////////////////////////////////////////////////////////////////////////
////	TO SUPPORT LEGACY XTEMPLATES
$xtpl->assign("URLENCODED_FIRST_NAME", urlencode($focus->first_name));
$xtpl->assign("URLENCODED_LAST_NAME", urlencode($focus->last_name));
$xtpl->assign("URLENCODED_EMAIL1", urlencode($focus->email1));
$xtpl->assign("URLENCODED_EMAIL2", urlencode($focus->email2));
$xtpl->assign('FULL_NAME', urlencode(trim($focus->full_name))); // not used?
$xtpl->assign('FIRST_NAME', $focus->first_name);
$xtpl->assign('LAST_NAME', $focus->last_name);
$xtpl->assign('SALUTATION', $focus->salutation);
////	END SUPPORT LEGACY XTEMPLATES
///////////////////////////////////////////////////////////////////////////////

$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);
if ($focus->birthdate == '0000-00-00') $xtpl->assign("BIRTHDATE", '');
else $xtpl->assign("BIRTHDATE", $focus->birthdate);
if ($focus->do_not_call == 'on') $xtpl->assign("DO_NOT_CALL", "checked");
if (!empty($focus->contacts_users_id)) $xtpl->assign("SYNC_CONTACT", "checked");
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("REPORTS_TO_ID", $focus->reports_to_id);
$xtpl->assign("REPORTS_TO_NAME", $focus->report_to_name);
global $system_config;

if(isset($system_config->settings['system_skypeout_on']) && $system_config->settings['system_skypeout_on'] == 1){
  if(!empty($focus->phone_home) && skype_formatted($focus->phone_home)) $focus->phone_home = '<a href="callto://' . $focus->phone_home. '">'.$focus->phone_home. '</a>' ;  	
  if(!empty($focus->phone_work) && skype_formatted($focus->phone_work)) $focus->phone_work = '<a href="callto://' . $focus->phone_work. '">'.$focus->phone_work. '</a>' ;  		
  if(!empty($focus->phone_mobile)&& skype_formatted($focus->phone_mobile)) $focus->phone_mobile = '<a href="callto://' . $focus->phone_mobile. '">'.$focus->phone_mobile. '</a>' ;  		
  if(!empty($focus->phone_other)&& skype_formatted($focus->phone_other)) $focus->phone_other = '<a href="callto://' . $focus->phone_other. '">'.$focus->phone_other. '</a>' ;  		
  	
}



$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
$xtpl->assign("PHONE_WORK", $focus->phone_work);
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign('EMAIL1_LINK', $current_user->getEmailLink('email1', $focus));
$xtpl->assign('EMAIL2_LINK', $current_user->getEmailLink('email2', $focus));
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("ASSISTANT", $focus->assistant);
$xtpl->assign("ASSISTANT_PHONE", $focus->assistant_phone);
if ($focus->invalid_email == '1') $xtpl->assign("INVALID_EMAIL", "checked");
$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);
$xtpl->assign("PORTAL_NAME", $focus->portal_name);
$xtpl->assign("PORTAL_ACTIVE", empty($focus->portal_active) ? '' : 'checked');

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

$detailView->processListNavigation($xtpl, "CONTACT", $offset, $focus->is_AuditEnabled());
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse("main.open_source");













$xtpl->assign("TAG", $focus->listviewACLHelper());
$xtpl->parse("main");
$xtpl->out("main");
$sub_xtpl = $xtpl;


require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'Contacts');
echo $subpanel->display();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' .$savedSearch->getSelect('Contacts')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
