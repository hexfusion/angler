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
 * $Id: DetailView.php,v 1.135 2006/08/26 01:23:41 jbenterou Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/DetailView/DetailView.php');
require_once('include/export_utils.php');
require_once('include/timezone/timezones.php');
require_once('include/utils.php');
require_once('modules/Users/User.php');
require_once('modules/Administration/Administration.php');

global $current_user;
global $theme;
global $app_strings;
global $mod_strings;
global $timezones;

if (!is_admin($current_user) && ($_REQUEST['record'] != $current_user->id)) sugar_die("Unauthorized access to administration.");

$focus = new User();


$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or !empty($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("USER", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Users&action=index");
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

if(isset($_REQUEST['reset_preferences'])){
	$current_user->resetPreferences($focus);
}
if(isset($_REQUEST['reset_homepage'])){
    $current_user->resetPreferences($focus, 'home');
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->full_name." (".$focus->user_name.")", true);
echo "\n</p>\n";
global $theme;
global $app_list_strings;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


$GLOBALS['log']->info("User detail view");

$xtpl=new XTemplate ('modules/Users/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("USER_NAME", $focus->user_name);
$xtpl->assign("FULL_NAME", $focus->full_name);

///////////////////////////////////////////////////////////////////////////////
////	TO SUPPORT LEGACY XTEMPLATES
$xtpl->assign('FIRST_NAME', $focus->first_name);
$xtpl->assign('LAST_NAME', $focus->last_name);
////	END SUPPORT LEGACY XTEMPLATES
///////////////////////////////////////////////////////////////////////////////

if($focus->is_group) { $status = $mod_strings['LBL_GROUP_USER_STATUS']; }
else { $status = $app_list_strings['user_status_dom'][$focus->status]; }
$xtpl->assign("STATUS", $status);

$detailView->processListNavigation($xtpl, "USER", $offset);
$reminder_time = $focus->getPreference('reminder_time');

if(empty($reminder_time)){
	$reminder_time = -1;
}
if($reminder_time != -1){
	$xtpl->assign("REMINDER_CHECKED", 'checked');
	$xtpl->assign("REMINDER_TIME", translate('reminder_time_options', '', $reminder_time));
}
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');
if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id)
		&& !empty($sugar_config['default_user_name'])
		&& $sugar_config['default_user_name'] == $focus->user_name
		&& isset($sugar_config['lock_default_user_name'])
		&& $sugar_config['lock_default_user_name']) {
	$buttons = "<input title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='EditView'\" type='submit' name='Edit' value='  ".$app_strings['LBL_EDIT_BUTTON_LABEL']."  '>  ";
}
elseif (is_admin($current_user) || $_REQUEST['record'] == $current_user->id) {
	$buttons = "<input title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='EditView'\" type='submit' name='Edit' value='  ".$app_strings['LBL_EDIT_BUTTON_LABEL']."  '>  ";
	if (!$focus->is_group) {
		$buttons .= "<input title='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_TITLE']."' accessKey='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=Users&action=ChangePassword&form=DetailView\",\"test\",\"width=320,height=230,resizable=1,scrollbars=1\");' type='button' name='password' value='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_LABEL']."'>  ";
	}
}

if(isset($_SERVER['QUERY_STRING'])) $the_query_string = $_SERVER['QUERY_STRING'];
else $the_query_string = '';

if (is_admin($current_user)) $buttons .= "<input title='".$app_strings['LBL_DUPLICATE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_DUPLICATE_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value=true; this.form.action.value='EditView'\" type='submit' name='Duplicate' value=' ".$app_strings['LBL_DUPLICATE_BUTTON_LABEL']." '>";
$buttons .="<td width='100%' align='right' nowrap><a onclick='return confirm(\"{$mod_strings['LBL_RESET_HOMEPAGE_WARNING']}\");' href='".$_SERVER['PHP_SELF'] .'?'.$the_query_string."&reset_homepage=true' >". $mod_strings['LBL_RESET_HOMEPAGE']. "</a> | <a onclick='return confirm(\"{$mod_strings['LBL_RESET_PREFERENCES_WARNING']}\");' href='".$_SERVER['PHP_SELF'] .'?'.$the_query_string."&reset_preferences=true' >". $mod_strings['LBL_RESET_PREFERENCES']. " </a>";
if (isset($buttons)) $xtpl->assign("BUTTONS", $buttons);





require_once("include/templates/TemplateGroupChooser.php");
require_once("modules/MySettings/TabController.php");
$chooser = new TemplateGroupChooser();
$controller = new TabController();

//if(is_admin($current_user) || $controller->get_users_can_edit())
if(is_admin($current_user))
{
	$chooser->display_third_tabs = true;
	$chooser->args['third_name'] = 'remove_tabs';
	$chooser->args['third_label'] =  $mod_strings['LBL_REMOVED_TABS'];
}
elseif(!$controller->get_users_can_edit())
{
	$chooser->display_hide_tabs = false;
}
else
{
	$chooser->display_hide_tabs = true;
}

$chooser->args['id'] = 'edit_tabs';
$chooser->args['values_array'] = $controller->get_tabs($focus);
$chooser->args['left_name'] = 'display_tabs';
$chooser->args['right_name'] = 'hide_tabs';
$chooser->args['left_label'] =  $mod_strings['LBL_DISPLAY_TABS'];
$chooser->args['right_label'] =  $mod_strings['LBL_HIDE_TABS'];
$chooser->args['title'] =  $mod_strings['LBL_EDIT_TABS'];
$chooser->args['disable'] = true;

foreach ($chooser->args['values_array'][0] as $key=>$value)
{
$chooser->args['values_array'][0][$key] = $app_list_strings['moduleList'][$key];
}
foreach ($chooser->args['values_array'][1] as $key=>$value)
{
$chooser->args['values_array'][1][$key] = $app_list_strings['moduleList'][$key];
}


$xtpl->assign("TAB_CHOOSER", $chooser->display());
$xtpl->assign("CHOOSE_WHICH", $mod_strings['LBL_CHOOSE_WHICH']);
$xtpl->parse("user_info.tabchooser");


$xtpl->parse("main");
$xtpl->out("main");

if(!empty($focus->is_group) && $focus->is_group == 1){
	$xtpl->assign("IS_GROUP", "checked");
}
if(!empty($focus->portal_only) && $focus->portal_only == 1){
    $admin = new Administration();
    $admin->retrieveSettings('license');
    if(isset($admin->settings['license_num_portal_users'])){
        $num_portal_users = $admin->settings['license_num_portal_users'];
        if(isset($num_portal_users) && $num_portal_users > 0){
	       $xtpl->assign("IS_PORTALONLY", "checked");
        }
    }
}
if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id) && $focus->is_admin == '1') {
	$xtpl->assign("IS_ADMIN", "checked");
}

if ($focus->receive_notifications) $xtpl->assign("RECEIVE_NOTIFICATIONS", "checked");

if($focus->getPreference('gridline') == 'on') {
$xtpl->assign("GRIDLINE_CHECK", "checked");
}

if($focus->getPreference('mailmerge_on') == 'on') {
$xtpl->assign("MAILMERGE_ON", "checked");
}








$xtpl->assign("SETTINGS_URL", $sugar_config['site_url']);

$xtpl->assign("EXPORT_DELIMITER", getDelimiter());
$xtpl->assign('EXPORT_CHARSET', $locale->getExportCharset());
$xtpl->assign('USE_REAL_NAMES', $focus->getPreference('use_real_names'));


global $timedate;
$xtpl->assign("DATEFORMAT", $sugar_config['date_formats'][$timedate->get_date_format()]);
$xtpl->assign("TIMEFORMAT", $sugar_config['time_formats'][$timedate->get_time_format()]);

$userTZ = $focus->getPreference('timezone');
if(!empty($userTZ)) {
	$value = $timezones[$userTZ];
}
if(!empty($value['dstOffset'])) {
	$dst = " (+DST)";
} else {
	$dst = "";
}
$gmtOffset = ($value['gmtOffset'] / 60);
if(!strstr($gmtOffset,'-')) {
	$gmtOffset = "+".$gmtOffset;
}

$xtpl->assign("TIMEZONE", $userTZ. str_replace('_',' '," (GMT".$gmtOffset.") ".$dst) );
$datef = $focus->getPreference('datef');
$timef = $focus->getPreference('timef');

if(!empty($datef))
$xtpl->assign("DATEFORMAT", $sugar_config['date_formats'][$datef]);
if(!empty($timef))
$xtpl->assign("TIMEFORMAT", $sugar_config['time_formats'][$timef]);

$num_grp_sep = $focus->getPreference('num_grp_sep'); 
$dec_sep = $focus->getPreference('dec_sep');
$xtpl->assign("NUM_GRP_SEP", (empty($num_grp_sep) ? $sugar_config['default_number_grouping_seperator'] : $num_grp_sep));
$xtpl->assign("DEC_SEP", (empty($dec_sep) ? $sugar_config['default_decimal_seperator'] : $dec_sep));
 

require_once('modules/Currencies/Currency.php');
$currency  = new Currency();
if($focus->getPreference('currency') ) {
	$currency->retrieve($focus->getPreference('currency'));
	$xtpl->assign("CURRENCY", $currency->iso4217 .' '.$currency->symbol );
} else {
	$xtpl->assign("CURRENCY", $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol() );
}

if($focus->getPreference('no_opps') == 'on') {
    $xtpl->assign('NO_OPPS', 'CHECKED');
}

$xtpl->assign('CURRENCY_SIG_DIGITS', $locale->getPrecedentPreference('default_currency_significant_digits'));

$xtpl->parse("user_settings");
$xtpl->out("user_settings");

$xtpl->assign('NAME_FORMAT', $focus->getLocaleFormatDesc());
$xtpl->parse('user_locale');
$xtpl->out('user_locale');

$xtpl->assign("DESCRIPTION", nl2br(url2html($focus->description)));
$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);
$xtpl->assign("REPORTS_TO_ID", $focus->reports_to_id);
$xtpl->assign("REPORTS_TO_NAME", $focus->reports_to_name);
$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
$xtpl->assign("PHONE_WORK", $focus->phone_work);
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("EMPLOYEE_STATUS", $focus->employee_status);
$xtpl->assign("MESSENGER_ID", $focus->messenger_id);
$xtpl->assign("MESSENGER_TYPE", $focus->messenger_type);
$xtpl->assign("ADDRESS_STREET", $focus->address_street);
$xtpl->assign("ADDRESS_CITY", $focus->address_city);
$xtpl->assign("ADDRESS_STATE", $focus->address_state);
$xtpl->assign("ADDRESS_POSTALCODE", $focus->address_postalcode);
$xtpl->assign("ADDRESS_COUNTRY", $focus->address_country);

$xtpl->assign("CALENDAR_PUBLISH_KEY", $focus->getPreference('calendar_publish_key' ));
if (! empty($current_user->email1))
{
  $xtpl->assign("CALENDAR_PUBLISH_URL", $sugar_config['site_url'].'/vcal_server.php/type=vfb&email='.$focus->email1.'&source=outlook&key='.$focus->getPreference('calendar_publish_key' ));
  $xtpl->assign("CALENDAR_SEARCH_URL", $sugar_config['site_url'].'/vcal_server.php/type=vfb&email=%NAME%@%SERVER%');
}
else
{
  $xtpl->assign("CALENDAR_PUBLISH_URL", $sugar_config['site_url'].'/vcal_server.php/type=vfb&user_name='.$focus->user_name.'&source=outlook&key='.$focus->getPreference('calendar_publish_key' ));
  $xtpl->assign("CALENDAR_SEARCH_URL", $sugar_config['site_url'].'/vcal_server.php/type=vfb&email=%NAME%@%SERVER%');
}
$xtpl->parse("user_info.freebusy");

$user_max_tabs = intval($focus->getPreference('max_tabs'));
if(isset($user_max_tabs) && $user_max_tabs > 0)
    $xtpl->assign("MAX_TAB", $user_max_tabs);
elseif(isset($max_tabs) && $max_tabs > 0)
    $xtpl->assign("MAX_TAB", $max_tabs);
else
    $xtpl->assign("MAX_TAB", $GLOBALS['sugar_config']['default_max_tabs']);

$user_max_subtabs = intval($focus->getPreference('max_subtabs'));
if(isset($user_max_subtabs) && $user_max_subtabs > 0)
    $xtpl->assign("MAX_SUBTAB", $user_max_subtabs);
else
    $xtpl->assign("MAX_SUBTAB", $GLOBALS['sugar_config']['default_max_subtabs']);

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
    $xtpl->assign("NAVIGATION_PARADIGM", $app_list_strings['navigation_paradigms'][$user_navigation_paradigm]);
} else {
    $xtpl->assign("NAVIGATION_PARADIGM", $app_list_strings['navigation_paradigms'][$GLOBALS['sugar_config']['default_navigation_paradigm']]);
}

$xtpl->parse("user_info.layoutopts");

///////////////////////////////////////////////////////////////////////////////
////	EMAIL DETAILS
$xtpl->assign('MAIL_FROMNAME', $focus->getPreference('mail_fromname'));
$xtpl->assign('MAIL_FROMADDRESS', $focus->getPreference('mail_fromaddress'));
$mail_sendtype = $focus->getPreference('mail_sendtype');
$xtpl->assign('MAIL_SENDTYPE', $mail_sendtype);

if(isset($mail_sendtype) && $mail_sendtype == 'SMTP') {
	$xtpl->assign('MAIL_SMTPSERVER', $focus->getPreference('mail_smtpserver' ));
	$xtpl->assign('MAIL_SMTPPORT', $focus->getPreference('mail_smtpport'));
	$xtpl->assign('MAIL_SMTPAUTH', $focus->getPreference('mail_smtpauth' ));
	$mail_smtpauth = $focus->getPreference('mail_smtpauth_req' ) ;
	if ($focus->getPreference('mail_smtpauth_req' ) ) {
		$xtpl->assign('MAIL_SMTPUSER', $focus->getPreference('mail_smtpuser'));
		$xtpl->assign('MAIL_SMTPAUTH_REQ', ' checked');
		$xtpl->parse('user_info.show_smtp.show_smtp_auth');
	}
	$xtpl->parse('user_info.show_smtp');
}
//_ppd($focus->getPreference('signature_prepend'));
if($sigDef = $focus->getPreference('signature_default')) {
	require_once('modules/Users/UserSignature.php');
	$sig = new UserSignature();
	$sig->retrieve($sigDef);
	if($focus->getPreference('email_editor_option') == 'PLAIN') {
		$xtpl->assign('DEFAULT_SIGNATURE', $sig->signature);
	} else {
		$xtpl->assign('DEFAULT_SIGNATURE', from_html($sig->signature_html));
	}
}

$getCounts = $focus->getPreference('email_show_counts');
if(empty($getCounts)) { $getCounts = 0; }
$xtpl->assign('EMAIL_EDITOR_OPTION', $app_list_strings['dom_email_editor_option'][$focus->getPreference('email_editor_option')]);
$xtpl->assign('SIGNATURE_PREPEND', $app_list_strings['dom_switch_bool'][$focus->getPreference('signature_prepend')]);
$xtpl->assign('EMAIL_SHOW_COUNTS', $app_list_strings['dom_int_bool'][$getCounts]);
$xtpl->assign('EMAIL_LINK_TYPE', $app_list_strings['dom_email_link_type'][$focus->getPreference('email_link_type')]);
$xtpl->assign('EMAIL_CHARSET', $locale->getPrecedentPreference('default_email_charset'));
///////////////////////////////////////////////////////////////////////////////
////	INBOUND EMAIL
if(function_exists('imap_open')) {
	if($focus->hasPersonalEmail()) {
		require_once('modules/InboundEmail/InboundEmail.php');
		$ie = new InboundEmail();
		$beans = $ie->retrieveByGroupId($focus->id);
		if(!empty($beans)) {
			foreach($beans as $bean) {
				$xtpl->assign('SERVER_URL', $bean->server_url);
				$xtpl->assign('IE_STATUS', $bean->status);
				$xtpl->assign('USER', $bean->email_user);
				$xtpl->assign('MAILBOX', $bean->mailbox);
				$xtpl->assign('MAILBOX_TYPE', $app_list_strings['dom_mailbox_type'][$bean->mailbox_type]);
				$xtpl->assign('SERVER_TYPE', strtoupper($bean->protocol));
				
				if($bean->tls == 'tls') {
					$tls = $app_list_strings['dom_email_bool']['bool_true'];
				} else {
					$tls = $app_list_strings['dom_email_bool']['bool_false'];
				}
				if($bean->ssl == 'ssl') {
					$ssl = $app_list_strings['dom_email_bool']['bool_true'];
				} else {
					$ssl = $app_list_strings['dom_email_bool']['bool_false'];
				}
				if($bean->ca == 'validate-cert') {
					$ca = $app_list_strings['dom_email_bool']['bool_true'];
				} else {
					$ca = $app_list_strings['dom_email_bool']['bool_false'];
				}
				$xtpl->assign('SSL', $ssl);
				$xtpl->assign('TLS', $tls);
				$xtpl->assign('CERT', $ca);
	
				$storedOptions = unserialize(base64_decode($bean->stored_options));
				// only-since option
				if($storedOptions['only_since'] == 1) {
					$onlySince = $mod_strings['LBL_ONLY_SINCE_YES'];
				} else {
					$onlySince = $mod_strings['LBL_ONLY_SINCE_NO'];
				}
				$xtpl->assign('ONLY_SINCE', $onlySince);
				
				if($bean->delete_seen == 1) {
					$delete_seen = $mod_strings['LBL_MARK_READ_NO'];
				} else {
					$delete_seen = $mod_strings['LBL_MARK_READ_YES'];
				}
				$xtpl->assign('MARK_READ', $delete_seen);
	













	
			}
		}
	}
	$xtpl->parse('user_info.inbound_email');
}
////	END EMAIL DETAILS
///////////////////////////////////////////////////////////////////////////////




$xtpl->parse("user_info");
$xtpl->out("user_info");


require_once('modules/ACLRoles/DetailUserRole.php');










echo "</td></tr>\n";

?>
