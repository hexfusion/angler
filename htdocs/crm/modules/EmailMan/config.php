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
 * $Id: config.php,v 1.12 2006/08/25 19:39:17 chris Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Administration/Administration.php');
require_once('modules/EmailMan/Forms.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_ID'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_CONFIGURE_SETTINGS'], true);
echo "\n</p>\n";
global $theme;
global $currentModule;
$theme_path = "themes/".$theme."/";
$image_path = $theme_path."images/";


require_once($theme_path.'layout_utils.php');

$focus = new Administration();
$focus->retrieveSettings(); //retrieve all admin settings.
$GLOBALS['log']->info("Mass Emailer(EmailMan) ConfigureSettings view");

$xtpl=new XTemplate ('modules/EmailMan/config.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("RETURN_MODULE", "Administration");
$xtpl->assign("RETURN_ACTION", "index");

$xtpl->assign("MODULE", $currentModule);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("HEADER", get_module_title("EmailMan", "{MOD.LBL_CONFIGURE_SETTINGS}", true));

$xtpl->assign("notify_fromaddress", $focus->settings['notify_fromaddress']);
$xtpl->assign("notify_send_by_default", ($focus->settings['notify_send_by_default']) ? "checked='checked'" : "");
$xtpl->assign("notify_send_from_assigning_user", ($focus->settings['notify_send_from_assigning_user']) ? "checked='checked'" : "");
$xtpl->assign("notify_on", ($focus->settings['notify_on']) ? "checked='checked'" : "");
$xtpl->assign("notify_fromname", $focus->settings['notify_fromname']);
$xtpl->assign("mail_smtpserver", $focus->settings['mail_smtpserver']);
$xtpl->assign("mail_smtpport", $focus->settings['mail_smtpport']);
$xtpl->assign("mail_sendtype_options", get_select_options_with_id($app_list_strings['notifymail_sendtype'], $focus->settings['mail_sendtype']));
$xtpl->assign("mail_smtpuser", $focus->settings['mail_smtpuser']);
$xtpl->assign("mail_smtppass", $focus->settings['mail_smtppass']);
$xtpl->assign("mail_smtpauth_req", ($focus->settings['mail_smtpauth_req']) ? "checked='checked'" : "");

///////////////////////////////////////////////////////////////////////////////
////	USER EMAIL DEFAULTS
// editors
$editors = $app_list_strings['dom_email_editor_option'];
$newEditors = array();
foreach($editors as $k => $v) {
	if($k != "") { $newEditors[$k] = $v; }
}
$defaultEditor = '';
if(isset($sugar_config['email_default_editor'])) {
	$defaultEditor = $sugar_config['email_default_editor'];
}
$xtpl->assign('DEFAULT_EDITOR', get_select_options_with_id($newEditors, $defaultEditor));

//clients
$clients = $app_list_strings['dom_email_link_type'];
$newClients = array();
foreach($clients as $k => $v) {
	if($k != '') { $newClients[$k] = $v; }
}
$defaultClient = '';
if(isset($sugar_config['email_default_client'])) {
	$defaultClient = $sugar_config['email_default_client'];
}
$xtpl->assign('DEFAULT_CLIENT', get_select_options_with_id($newClients, $defaultClient));

// charet
$charsets = get_select_options_with_id($locale->getCharsetSelect(), $sugar_config['default_email_charset']);
$xtpl->assign('DEFAULT_EMAIL_CHARSET', $charsets);
////	END USER EMAIL DEFAULTS
///////////////////////////////////////////////////////////////////////////////


//setting to manage.
//emails_per_run
//tracking_entities_location_type default or custom
//tracking_entities_location http://www.sugarcrm.com/track/
if (isset($focus->settings['massemailer_campaign_emails_per_run']) && !empty($focus->settings['massemailer_campaign_emails_per_run'])) {
	$xtpl->assign("EMAILS_PER_RUN", $focus->settings['massemailer_campaign_emails_per_run']);
} else  {
	$xtpl->assign("EMAILS_PER_RUN", 500);
}

if (!isset($focus->settings['massemailer_tracking_entities_location_type']) or empty($focus->settings['massemailer_tracking_entities_location_type']) or $focus->settings['massemailer_tracking_entities_location_type']=='1') {
	$xtpl->assign("DEFAULT_CHECKED", "checked");
	$xtpl->assign("TRACKING_ENTRIES_LOCATION_STATE", "disabled");
	$xtpl->assign("TRACKING_ENTRIES_LOCATION",$mod_strings['TRACKING_ENTRIES_LOCATION_DEFAULT_VALUE']);
} else  {
	$xtpl->assign("USERDEFINED_CHECKED", "checked");
	$xtpl->assign("TRACKING_ENTRIES_LOCATION",$focus->settings["massemailer_tracking_entities_location"]);
}

$xtpl->assign("JAVASCRIPT",get_validate_record_js());
$xtpl->parse("main");

$xtpl->out("main");
?>
