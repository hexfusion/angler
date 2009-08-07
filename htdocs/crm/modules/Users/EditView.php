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
 * $Id: EditView.php,v 1.136 2006/09/02 01:25:29 chris Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('include/export_utils.php');
require_once('modules/Users/User.php');
require_once('modules/Users/Forms.php');
require_once('modules/Users/UserSignature.php');
require_once('modules/Administration/Administration.php');


$admin = new Administration();
$admin->retrieveSettings("notify");

global $app_strings;
global $app_list_strings;
global $mod_strings;

$admin = new Administration();
$admin->retrieveSettings();

$focus = new User();

if(!is_admin($current_user) && $_REQUEST['record'] != $current_user->id) sugar_die("Unauthorized access to administration.");

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	$focus->user_name = "";
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->first_name." ".$focus->last_name." (".$focus->user_name.")", true);
echo "\n</p>\n";
global $theme;
$theme_path='themes/'.$theme.'/';
$image_path=$theme_path.'images/';
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info('User edit view');
$xtpl=new XTemplate ('modules/Users/EditView.html');
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);

if (isset($_REQUEST['error_string'])) $xtpl->assign('ERROR_STRING', '<span class="error">Error: '.$_REQUEST['error_string'].'</span>');
if (isset($_REQUEST['return_module'])) $xtpl->assign('RETURN_MODULE', $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign('RETURN_ACTION', $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign('RETURN_ID', $_REQUEST['return_id']);
else { $xtpl->assign('RETURN_ACTION', 'ListView'); }

$xtpl->assign('JAVASCRIPT', get_set_focus_js().user_get_validate_record_js().user_get_chooser_js().user_get_confsettings_js().'<script type="text/javascript" language="Javascript" src="modules/Users/User.js"></script>');
$xtpl->assign('IMAGE_PATH', $image_path);$xtpl->assign('PRINT_URL', 'index.php?'.$GLOBALS['request_string']);
$xtpl->assign('ID', $focus->id);
$xtpl->assign('USER_NAME', $focus->user_name);
$xtpl->assign('FIRST_NAME', $focus->first_name);
$xtpl->assign('LAST_NAME', $focus->last_name);
$xtpl->assign('TITLE', $focus->title);
$xtpl->assign('DEPARTMENT', $focus->department);
$xtpl->assign('REPORTS_TO_ID', $focus->reports_to_id);
$xtpl->assign('REPORTS_TO_NAME', $focus->reports_to_name);
$xtpl->assign('PHONE_HOME', $focus->phone_home);
$xtpl->assign('PHONE_MOBILE', $focus->phone_mobile);
$xtpl->assign('PHONE_WORK', $focus->phone_work);
$xtpl->assign('PHONE_OTHER', $focus->phone_other);
$xtpl->assign('PHONE_FAX', $focus->phone_fax);
$xtpl->assign('EMAIL1', $focus->email1);
$xtpl->assign('EMAIL2', $focus->email2);
$xtpl->assign('ADDRESS_STREET', $focus->address_street);
$xtpl->assign('ADDRESS_CITY', $focus->address_city);
$xtpl->assign('ADDRESS_STATE', $focus->address_state);
$xtpl->assign('ADDRESS_POSTALCODE', $focus->address_postalcode);
$xtpl->assign('ADDRESS_COUNTRY', $focus->address_country);
$xtpl->assign('DESCRIPTION', $focus->description);
$xtpl->assign('EXPORT_DELIMITER', getDelimiter());
$xtpl->assign('EXPORT_CHARSET', get_select_options_with_id($locale->availableCharsets, $locale->getExportCharset()));
if($focus->getPreference('use_real_names') == 'on') {
	$xtpl->assign('USE_REAL_NAMES', 'CHECKED');
}
if($focus->getPreference('no_opps') == 'on') {
    $xtpl->assign('NO_OPPS', 'CHECKED');
}



///////////////////////////////////////////////////////////////////////////////
////	NEW USER CREATION ONLY
if(empty($focus->id)) {
	$xtpl->assign('LBL_NEW_PASSWORD1', $mod_strings['LBL_NEW_PASSWORD1'].': <span class="required">'.$app_strings['LBL_REQUIRED_SYMBOL'].'</span>');
	$xtpl->assign('LBL_NEW_PASSWORD2', $mod_strings['LBL_NEW_PASSWORD2'].': <span class="required">'.$app_strings['LBL_REQUIRED_SYMBOL'].'</span>');
	$xtpl->assign('NEW_PASSWORD1', '<input id="new_password1" name="new_password1" tabindex="2" type="password" size="25" maxlength="25">');
	$xtpl->assign('NEW_PASSWORD2', '<input id="new_password2" name="new_password2" tabindex="2" type="password" size="25" maxlength="25">');
}
////	END NEW USER CREATION ONLY
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	REDIRECTS FROM COMPOSE EMAIL SCREEN
if(isset($_REQUEST['type']) && $_REQUEST['return_module'] == 'Emails') {
	$xtpl->assign('REDIRECT_EMAILS_TYPE', $_REQUEST['type']);
}
////	END REDIRECTS FROM COMPOSE EMAIL SCREEN
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	LOCALE SETTINGS
////	Date/time format
$dformat = $locale->getPrecedentPreference('datef');
$tformat = $locale->getPrecedentPreference('timef');
$timeOptions = get_select_options_with_id($sugar_config['time_formats'], $tformat);
$dateOptions = get_select_options_with_id($sugar_config['date_formats'], $dformat);
$xtpl->assign('TIMEOPTIONS', $timeOptions);
$xtpl->assign('DATEOPTIONS', $dateOptions);



//// Timezone
if(empty($focus->id)) { // remove default timezone for new users (set later)
    $focus->user_preferences['timezone'] = '';
}
require_once('include/timezone/timezones.php');
global $timezones;

$userTZ = $focus->getPreference('timezone');
if(empty($userTZ)) {
	$focus->setPreference('timezone', date('T'));
}

if(empty($userTZ)) 
	$userTZ = lookupTimezone();  

if(!$focus->getPreference('ut')) {
	$xtpl->assign('PROMPTTZ', ' checked');
}

if(is_admin($current_user)) 
	$xtpl->parse('main.prompttz');

$timezoneOptions = '';
ksort($timezones);
foreach($timezones as $key => $value) {
	$selected = ($userTZ == $key) ? ' SELECTED' : '';
	$dst = !empty($value['dstOffset']) ? ' (+DST)' : '';
	$gmtOffset = ($value['gmtOffset'] / 60);

	if(!strstr($gmtOffset,'-')) {
		$gmtOffset = '+'.$gmtOffset;
	}
	$timezoneOptions .= "<option value='$key'".$selected.">".str_replace(array('_','North'), array(' ', 'N.'),$key). " (GMT".$gmtOffset.") ".$dst."</option>";
}
$xtpl->assign('TIMEZONEOPTIONS', $timezoneOptions);

//// Numbers and Currency display
require_once('modules/Currencies/ListCurrency.php');
$currency = new ListCurrency();

if($focus->getPreference('currency')) {
	$selectCurrency = $currency->getSelectOptions($focus->getPreference('currency'));
	$xtpl->assign("CURRENCY", $selectCurrency);
} else {
	$selectCurrency = $currency->getSelectOptions();
	$xtpl->assign("CURRENCY", $selectCurrency);
}

$currenciesVars = "";
$i=0;
foreach($locale->currencies as $id => $arrVal) {
	$currenciesVars .= "currencies[{$i}] = '{$arrVal['symbol']}';\n";
	$i++;
}
$currencySymbolsJs = <<<eoq
var currencies = new Object;
{$currenciesVars}
function setSymbolValue(id) {
	document.getElementById('symbol').value = currencies[id];
}
eoq;
$xtpl->assign('currencySymbolJs', $currencySymbolsJs); 


// fill significant digits dropdown
$signficantDigits = $locale->getPrecedentPreference('default_currency_significant_digits');
$sigDigits = '';
for($i=0; $i<=6; $i++) {
	$sigDigitsSelected = ($signficantDigits == $i) ? ' SELECTED' : '';
	$sigDigits .= "<option value='{$i}'{$sigDigitsSelected}>{$i}</option>";
}
$xtpl->assign('sigDigits', $sigDigits);

$num_grp_sep = $focus->getPreference('num_grp_sep'); 
$dec_sep = $focus->getPreference('dec_sep');
$xtpl->assign("NUM_GRP_SEP", (empty($num_grp_sep) ? $sugar_config['default_number_grouping_seperator'] : $num_grp_sep));
$xtpl->assign("DEC_SEP", (empty($dec_sep) ? $sugar_config['default_decimal_seperator'] : $dec_sep));

$xtpl->assign('getNumberJs', $locale->getNumberJs());


//// Name display format
$xtpl->assign('default_locale_name_format', $locale->getLocaleFormatMacro());
$xtpl->assign('getNameJs', $locale->getNameJs());

////	END LOCALE SETTINGS
///////////////////////////////////////////////////////////////////////////////






require_once($theme_path.'config.php');

$user_max_tabs = $focus->getPreference('max_tabs');
if(isset($user_max_tabs) && $user_max_tabs > 0) {
	$xtpl->assign("MAX_TAB", $user_max_tabs);
} elseif(isset($max_tabs) && $max_tabs > 0) {
    $xtpl->assign("MAX_TAB", $max_tabs);
} else {
    $xtpl->assign("MAX_TAB", $GLOBALS['sugar_config']['default_max_tabs']);
}

$user_max_subtabs = $focus->getPreference('max_subtabs');
if(isset($user_max_subtabs) && $user_max_subtabs > 0) {
    $xtpl->assign("MAX_SUBTAB", $user_max_subtabs);
} else {
    $xtpl->assign("MAX_SUBTAB", $GLOBALS['sugar_config']['default_max_subtabs']);
}

$user_swap_last_viewed = $focus->getPreference('swap_last_viewed');
if(isset($user_swap_last_viewed)) {
    $xtpl->assign("SWAP_LAST_VIEWED", $user_swap_last_viewed?'checked':'');
} else {
    $xtpl->assign("SWAP_LAST_VIEWED", $GLOBALS['sugar_config']['default_swap_last_viewed']?'checked':'');
}

$user_swap_shortcuts = $focus->getPreference('swap_shortcuts');
if(isset($user_swap_shortcuts)) {
    $xtpl->assign("SWAP_SHORTCUT", $user_swap_shortcuts?'checked':'');
} else {
    $xtpl->assign("SWAP_SHORTCUT", $GLOBALS['sugar_config']['default_swap_shortcuts']?'checked':'');
}

$user_subpanel_tabs = $focus->getPreference('subpanel_tabs');
if(isset($user_subpanel_tabs)) {
    $xtpl->assign("SUBPANEL_TABS", $user_subpanel_tabs?'checked':'');
} else {
    $xtpl->assign("SUBPANEL_TABS", $GLOBALS['sugar_config']['default_subpanel_tabs']?'checked':'');
}

$user_subpanel_links = $focus->getPreference('subpanel_links');
$xtpl->assign("SUBPANEL_LINKS", $user_subpanel_links?'checked':'');
if(isset($user_subpanel_links)) {
    $xtpl->assign("SUBPANEL_LINKS", $user_subpanel_links?'checked':'');
} else {
    $xtpl->assign("SUBPANEL_LINKS", $GLOBALS['sugar_config']['default_subpanel_links']?'checked':'');
}

$user_navigation_paradigm = $focus->getPreference('navigation_paradigm');
if(isset($user_navigation_paradigm)) {
    $xtpl->assign("NAVADIGMS", get_select_options_with_id($app_list_strings['navigation_paradigms'], $user_navigation_paradigm));
} else {
    $xtpl->assign("NAVADIGMS", get_select_options_with_id($app_list_strings['navigation_paradigms'], $GLOBALS['sugar_config']['default_navigation_paradigm']));
}



$xtpl->assign("MAIL_SENDTYPE", get_select_options_with_id($app_list_strings['notifymail_sendtype'], $focus->getPreference('mail_sendtype')));
$reminder_time = $focus->getPreference('reminder_time');
if(empty($reminder_time)){
	$reminder_time = -1;
}

$xtpl->assign("REMINDER_TIME_OPTIONS", get_select_options_with_id($app_list_strings['reminder_time_options'],$reminder_time));
if($reminder_time > -1){
	$xtpl->assign("REMINDER_TIME_DISPLAY", 'inline');
	$xtpl->assign("REMINDER_CHECKED", 'checked');
}else{
	$xtpl->assign("REMINDER_TIME_DISPLAY", 'none');
}

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');
if (is_admin($current_user)) {
	$status  = "<td class='dataLabel'>".$mod_strings['LBL_STATUS']." <span class='required'>".$app_strings['LBL_REQUIRED_SYMBOL']."</span></td>\n";
	$status .= "<td><select name='status' tabindex='1'";
	if(!empty($sugar_config['default_user_name']) &&
		$sugar_config['default_user_name']== $focus->user_name &&
		isset($sugar_config['lock_default_user_name']) &&
		$sugar_config['lock_default_user_name'] )
	{
		$status .= ' disabled="disabled" ';
	}
	$status .= ">";
	$status .= get_select_options_with_id($app_list_strings['user_status_dom'], $focus->status);
	$status .= "</select></td>\n";
	$xtpl->assign("USER_STATUS_OPTIONS", $status);
}
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}


if(!empty($sugar_config['default_user_name']) &&
	$sugar_config['default_user_name'] == $focus->user_name &&
	isset($sugar_config['lock_default_user_name']) &&
	$sugar_config['lock_default_user_name'])
{
	$status .= ' disabled ';
	$xtpl->assign('FIRST_NAME_DISABLED', 'disabled="disabled"');
	$xtpl->assign('USER_NAME_DISABLED', 'disabled="disabled"');
	$xtpl->assign('LAST_NAME_DISABLED', 'disabled="disabled"');
	$xtpl->assign('IS_ADMIN_DISABLED', 'disabled="disabled"');
	$xtpl->assign('IS_PORTAL_ONLY_DISABLED', 'disabled="disabled"');
	$xtpl->assign('IS_GROUP_DISABLED', 'disabled="disabled"');
}

if ($focus->receive_notifications || (!isset($focus->id) && $admin->settings['notify_send_by_default'])) $xtpl->assign("RECEIVE_NOTIFICATIONS", "checked");


if($focus->getPreference('gridline') == 'on') {
$xtpl->assign('GRIDLINE', 'checked');
}

if($focus->getPreference('mailmerge_on') == 'on') {
$xtpl->assign('MAILMERGE_ON', 'checked');
}








if(!empty($focus->portal_only) && $focus->portal_only){
	if (is_admin($current_user) && !is_admin($focus)){
		$xtpl->assign('IS_PORTALONLY', 'checked');
	}else{
		$xtpl->assign('IS_PORTALONLY', 'disabled');
	}
}
else
{
	if (!is_admin($current_user)|| is_admin($focus)){
		$xtpl->assign('IS_PORTALONLY', 'disabled');
	}
}

if(isset($admin->settings['license_num_portal_users'])){
    $num_portal_users = $admin->settings['license_num_portal_users'];
    if(!isset($num_portal_users) || $num_portal_users <= 0){
        $xtpl->assign('IS_PORTALONLY', 'disabled');    
    }
}else{
    $xtpl->assign('IS_PORTALONLY', 'disabled');      
}

if(!empty($focus->is_group) && $focus->is_group){
	if (is_admin($current_user) && !is_admin($focus)){
		$xtpl->assign('IS_GROUP', 'checked');
	}else{
		$xtpl->assign('IS_GROUP', 'disabled');
	}
}
else
{
	if (!is_admin($current_user)|| is_admin($focus)){
		$xtpl->assign('IS_GROUP', 'disabled');
	}
}

if(is_admin($focus))
{
	$xtpl->assign('IS_ADMIN', 'checked');
}

$reports_to_change_button_html = '';

if(is_admin($current_user))
{
	//////////////////////////////////////
	///
	/// SETUP USER POPUP
	
	$popup_request_data = array(
		'call_back_function' => 'set_return',
		'form_name' => 'EditView',
		'field_to_name_array' => array(
			'id' => 'reports_to_id',
			'name' => 'reports_to_name',
			),
		);
	
	$json = getJSONobj();
	$encoded_popup_request_data = $json->encode($popup_request_data);
	$xtpl->assign('encoded_popup_request_data', $encoded_popup_request_data);
	
	//
	///////////////////////////////////////

	$reports_to_change_button_html = '<input type="button"'
	. " title=\"{$app_strings['LBL_SELECT_BUTTON_TITLE']}\""
	. " accesskey=\"{$app_strings['LBL_SELECT_BUTTON_KEY']}\""
	. " value=\"{$app_strings['LBL_SELECT_BUTTON_LABEL']}\""
	. ' tabindex="5" class="button" name="btn1"'
	. " onclick='open_popup(\"Users\", 600, 400, \"\", true, false, {$encoded_popup_request_data});'"
	. "' />";
}
else
{
	$xtpl->assign('IS_ADMIN_DISABLED', 'disabled="disabled"');
}





$xtpl->assign('REPORTS_TO_CHANGE_BUTTON', $reports_to_change_button_html);


/* Module Tab Chooser */
require_once('include/templates/TemplateGroupChooser.php');
require_once('modules/MySettings/TabController.php');
$chooser = new TemplateGroupChooser();
$controller = new TabController();

echo "<script>SUGAR.tabChooser.freezeOptions('display_tabs', 'hide_tabs', 'Home');</script>";

if(is_admin($current_user))
{
	$chooser->display_hide_tabs = true;
	$chooser->display_third_tabs = true;
	$chooser->args['third_name'] = 'remove_tabs';
	$chooser->args['third_label'] =  $mod_strings['LBL_REMOVED_TABS'];
	//$xtpl->parse("main.tabchooser");
}

if(is_admin($current_user) || $controller->get_users_can_edit())
{
	$chooser->display_hide_tabs = true;	
}
else 
{
	$chooser->display_hide_tabs = false;
}

$chooser->args['id'] = 'edit_tabs';
$chooser->args['values_array'] = $controller->get_tabs($focus);
foreach ($chooser->args['values_array'][0] as $key=>$value)
{
    $chooser->args['values_array'][0][$key] = $app_list_strings['moduleList'][$key];
}
foreach ($chooser->args['values_array'][1] as $key=>$value)
{
    $chooser->args['values_array'][1][$key] = $app_list_strings['moduleList'][$key];
}
foreach ($chooser->args['values_array'][2] as $key=>$value)
{
    $chooser->args['values_array'][2][$key] = $app_list_strings['moduleList'][$key];
}
$chooser->args['left_name'] = 'display_tabs';
$chooser->args['right_name'] = 'hide_tabs';

$chooser->args['left_label'] =  $mod_strings['LBL_DISPLAY_TABS'];
$chooser->args['right_label'] =  $mod_strings['LBL_HIDE_TABS'];
$chooser->args['title'] =  $mod_strings['LBL_EDIT_TABS'];
$xtpl->assign('TAB_CHOOSER', $chooser->display());
$xtpl->assign('CHOOSER_SCRIPT','set_chooser();');
$xtpl->assign('CHOOSE_WHICH', $mod_strings['LBL_CHOOSE_WHICH']);



/* Grouped Tab Chooser */
//$chooser = new TemplateGroupChooser();
//
//if(is_admin($current_user))
//{
//    $chooser->display_hide_tabs = true;
//    $chooser->display_third_tabs = true;
//    $chooser->args['third_name'] = 'remove_tabs';
//    $chooser->args['third_label'] =  $mod_strings['LBL_REMOVED_TABS'];
//    //$xtpl->parse("main.tabchooser");
//}
//
//if(is_admin($current_user) || $controller->get_users_can_edit())
//{
//    $chooser->display_hide_tabs = true; 
//}
//else 
//{
//    $chooser->display_hide_tabs = false;
//}
//
//$chooser->args['id'] = 'edit_groups';
//$chooser->args['values_array'] = $controller->get_tabs($focus);
//foreach ($chooser->args['values_array'][0] as $key=>$value)
//{
//$chooser->args['values_array'][0][$key] = $app_list_strings['moduleList'][$key];
//}
//foreach ($chooser->args['values_array'][1] as $key=>$value)
//{
//$chooser->args['values_array'][1][$key] = $app_list_strings['moduleList'][$key];
//}
//$chooser->args['left_name'] = 'display_tabs';
//$chooser->args['right_name'] = 'hide_tabs';
//
//$chooser->args['left_label'] =  $mod_strings['LBL_DISPLAY_TABS'];
//$chooser->args['right_label'] =  $mod_strings['LBL_HIDE_TABS'];
//$chooser->args['title'] =  $mod_strings['LBL_EDIT_TABS'];
//$xtpl->assign('TAB_CHOOSER', $chooser->display());
//$xtpl->assign('CHOOSER_SCRIPT','set_chooser();');
//$xtpl->assign('CHOOSE_WHICH', $mod_strings['LBL_CHOOSE_WHICH']);





///////////////////////////////////////////////////////////////////////////////
////	EMAIL OPTIONS
// from name/address
$xtpl->assign('MAIL_FROMNAME', $focus->getPreference('mail_fromname'));
$xtpl->assign('MAIL_FROMADDRESS', $focus->getPreference('mail_fromaddress'));
// sendmail/SMTP server
$xtpl->assign('MAIL_SMTPSERVER', $focus->getPreference('mail_smtpserver' ));
$xtpl->assign('MAIL_SMTPPORT', ($focus->getPreference('mail_smtpport') == null) ? '25' : $focus->getPreference('mail_smtpport'));
$xtpl->assign('MAIL_SMTPUSER', $focus->getPreference('mail_smtpuser'));
$xtpl->assign('MAIL_SMTPPASS', $focus->getPreference('mail_smtppass'));
if ($focus->getPreference('mail_smtpauth_req')) {
	$xtpl->assign('MAIL_SMTPAUTH_REQ', ' checked');
}
$xtpl->assign('MAIL_SMTPAUTH', $focus->getPreference('mail_smtpauth' ));
$xtpl->assign('SIGNATURES', $focus->getSignatures(false, $focus->getPreference('signature_default')));
$xtpl->assign('SIGNATURE_BUTTONS', $focus->getSignatureButtons());
if($sigDef = $focus->getPreference('signature_default')) {
	$xtpl->assign('SIGNATURE_DEFAULT_ID', $sigDef);	
}
if($focus->getPreference('signature_prepend')) {
	$xtpl->assign('SIGNATURE_PREPEND', 'CHECKED');	
}
$showCounts = '';
if($focus->getPreference('email_show_counts')) {
	$showCounts = 'CHECKED';
}
$xtpl->assign('EMAIL_LINK_TYPE', get_select_options_with_id($app_list_strings['dom_email_link_type'], $focus->getPreference('email_link_type')));
$xtpl->assign('EMAIL_SHOW_COUNTS', $showCounts);
$xtpl->assign('EMAIL_EDITOR_OPTION', get_select_options_with_id($app_list_strings['dom_email_editor_option'], $focus->getPreference('email_editor_option')));
$xtpl->assign('EMAIL_CHARSET', get_select_options_with_id($locale->getCharsetSelect(), $locale->getPrecedentPreference('default_email_charset')) );
/////	END EMAIL OPTIONS
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	INBOUND EMAIL SETUP
if(function_exists('imap_open')) {
	require_once('modules/Emails/Email.php');
	require_once('modules/InboundEmail/InboundEmail.php');
	$ie = new InboundEmail();
	$email = new Email();
	$xtpl->assign('ROLLOVER', $email->rolloverStyle);
	$xtpl->assign('IE_HIDE', '');
	$xtpl->assign('IE_NAME', $focus->user_name);
	$xtpl->assign('GROUP_ID', $focus->id);
	$xtpl->assign('THEME', $theme);
	$status = get_select_options_with_id($app_list_strings['user_status_dom'],'');
	$mailbox_type = get_select_options_with_id($app_list_strings['dom_email_server_type'], '');
	$mailbox = 'INBOX';
	$beans = $ie->retrieveByGroupId($focus->id);
	if(!empty($beans)) {
		foreach($beans as $bean) {
			// default MAILBOX value
			if(!empty($bean->mailbox)) {
				$mailbox = $bean->mailbox;
			}
			
			if(!empty($bean->stored_options)) {
				$storedOptions = unserialize(base64_decode($bean->stored_options));
				$from_name = $storedOptions['from_name'];
				$from_addr = $storedOptions['from_addr'];
				if($storedOptions['only_since']) {
					$only_since = 'CHECKED';
				} else {
					$only_since = '';
				}
				if(isset($storedOptions['filter_domain']) && !empty($storedOptions['filter_domain'])) {
					$filterDomain = $storedOptions['filter_domain']; 
				} else {
					$filterDomain = '';
				}
			} else { // initialize empty vars for template
				$from_name = '';
				$from_addr = '';
				$only_since = '';
				$filterDomain = '';
			}
			// service options breakdown
			$tls = '';
			$notls = '';
			$cert = '';
			$novalidate_cert = '';
			$ssl = '';
			$protocol = '';
			if(!empty($bean->service)) {
				// will always have 2 values: /tls || /notls and /validate-cert || /novalidate-cert
				$exServ = explode('::', $bean->service);
				if($exServ[0] == 'tls') {
					$tls = 'CHECKED';
				} elseif($exServ[5] == 'notls') {
					$notls = 'CHECKED';
				}
				if($exServ[1] == 'validate-cert') {
					$cert = 'CHECKED';
				} elseif($exServ[4] == 'novalidate-cert') {
					$novalidate_cert = 'CHECKED';
				}
				if(isset($exServ[2]) && !empty($exServ[2]) && $exServ[2] == 'ssl') {
					$ssl = 'CHECKED';
				}
				if(isset($exServ[3]) && !empty($exServ[3])) {
					$protocol = $exServ[3];
				}
			}
			$mark_read = 'CHECKED';
			if($bean->delete_seen == 1) {
				$mark_read = '';
			}
	
			// mailbox type
			$mailbox_type = get_select_options_with_id($app_list_strings['dom_email_server_type'], $protocol);
			$status = get_select_options_with_id($app_list_strings['user_status_dom'], $bean->status);
			$xtpl->assign('IE_HIDE', '');
			$xtpl->assign('IE_ID', $bean->id);
			$xtpl->assign('IE_PORT', $bean->port);
			$xtpl->assign('SERVER_URL', $bean->server_url);
			$xtpl->assign('USER', $bean->email_user);
			$xtpl->assign('PASSWORD', $bean->email_password);
			$xtpl->assign('MAILBOX', $mailbox);
			$xtpl->assign('TLS', $tls);
			$xtpl->assign('NOTLS', $notls);
			$xtpl->assign('CERT', $cert);
			$xtpl->assign('NOVALIDATE_CERT', $novalidate_cert);
			$xtpl->assign('SSL', $ssl);
			$xtpl->assign('MARK_READ', $mark_read);
			$xtpl->assign('MAILBOX_TYPE', $mailbox_type);
			$xtpl->assign('ONLY_SINCE', $only_since);
			if($bean->port == 110 || $bean->port == 995) {
				$xtpl->assign('DISPLAY', "display:''");
			} else {
				$xtpl->assign('DISPLAY', "display:none");
			}
		}
	}
	$xtpl->assign('PROTOCOL', $mailbox_type);
	$xtpl->assign('IE_STATUS', $status);
	
	$xtpl->parse('main.inbound_email');
}
////	END INBOUND EMAIL SETUP
///////////////////////////////////////////////////////////////////////////////

$employee_status = '<select tabindex="5" name="employee_status">';
$employee_status .= get_select_options_with_id($app_list_strings['employee_status_dom'], $focus->employee_status);
$employee_status .= '</select>';
$xtpl->assign('EMPLOYEE_STATUS_OPTIONS', $employee_status);

$messenger_type = '<select tabindex="5" name="messenger_type">';
$messenger_type .= get_select_options_with_id($app_list_strings['messenger_type_dom'], $focus->messenger_type);
$messenger_type .= '</select>';
$xtpl->assign('MESSENGER_TYPE_OPTIONS', $messenger_type);
$xtpl->assign('MESSENGER_ID', $focus->messenger_id);


$xtpl->assign('CALENDAR_PUBLISH_KEY', $focus->getPreference('calendar_publish_key' ));
$xtpl->parse('main.freebusy');
$xtpl->parse('main');
$xtpl->out('main');

?>
