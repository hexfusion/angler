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
 * $Id: EditView.php,v 1.25 2006/06/06 17:58:19 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/EmailMarketing/EmailMarketing.php');
require_once('modules/EmailMarketing/Forms.php');


global $timedate;
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus = new EmailMarketing();
if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("EmailMarketing Edit View");
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
$xtpl=new XTemplate ('modules/EmailMarketing/EditView.html');
if(!ACLController::checkAccess('EmailTemplates', 'edit', true)){
	unset($mod_strings['LBL_CREATE_EMAIL_TEMPLATE']);
	unset($mod_strings['LBL_EDIT_EMAIL_TEMPLATE']);
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", "en");
$xtpl->assign("USER_DATEFORMAT", '('. $timedate->get_user_date_format().')');
$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());
$xtpl->assign("TIME_MERIDIEM", $timedate->AMPMMenu('', $focus->time_start));

if (isset($_REQUEST['return_module'])) {
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
} else {
	$xtpl->assign("RETURN_MODULE", 'Campaigns');
}
if (isset($_REQUEST['return_action'])) { 
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
} else {
	$xtpl->assign("RETURN_ACTION", 'DetailView');
}	
if (isset($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
} else {
	if (!empty($focus->campaign_id)) {
		$xtpl->assign("RETURN_ID", $focus->campaign_id);
	}
}

if($focus->campaign_id) {
	$campaign_id=$focus->campaign_id;
}
else {
	$campaign_id=$_REQUEST['campaign_id'];
}
$xtpl->assign("CAMPAIGN_ID", $campaign_id);


$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("FROM_NAME", $focus->from_name);
$xtpl->assign("FROM_ADDR", $focus->from_addr);
$xtpl->assign("DATE_START", $focus->date_start);
$xtpl->assign("TIME_START", $focus->time_start);
$xtpl->assign("TIME_MERIDIEM", $timedate->AMPMMenu('', ''));
$xtpl->assign("TIME_FORMAT", '('. $timedate->get_user_time_format().')');

$email_templates_arr = get_bean_select_array(true, 'EmailTemplate','name','','name');
if($focus->template_id) {
	$xtpl->assign("TEMPLATE_ID", $focus->template_id);
	$xtpl->assign("EMAIL_TEMPLATE_OPTIONS", get_select_options_with_id($email_templates_arr, $focus->template_id));
	$xtpl->assign("EDIT_TEMPLATE","visibility:inline");
}
else {
	$xtpl->assign("EMAIL_TEMPLATE_OPTIONS", get_select_options_with_id($email_templates_arr, ""));
	$xtpl->assign("EDIT_TEMPLATE","visibility:hidden");
}

//include campaign utils..
require_once('modules/Campaigns/utils.php');
if (empty($_REQUEST['campaign_name'])) {
	require_once('modules/Campaigns/Campaign.php');
	$campaign = new Campaign();
	$campaign->retrieve($campaign_id);
	$campaign_name=$campaign->name;
} else {
	$campaign_name=$_REQUEST['campaign_name'];
}
$scope_options=get_message_scope_dom($campaign_id,$campaign_name,$focus->db);
$prospectlists=array();
if (isset($focus->all_prospect_lists) && $focus->all_prospect_lists==1) {
	$xtpl->assign("ALL_PROSPECT_LISTS_CHECKED","checked");
	$xtpl->assign("MESSAGE_FOR_DISABLED","disabled");		
}	
else {
	//get select prospect list.
	if (!empty($focus->id)) {
		$focus->load_relationship('prospectlists');
		$prospectlists=$focus->prospectlists->get();
	};
}
if (empty($prospectlists)) $prospectlists=array();
if (empty($scope_options)) $scope_options=array();
$xtpl->assign("SCOPE_OPTIONS", get_select_options_with_id($scope_options, $prospectlists));

$emails=array();
$mailboxes=get_campaign_mailboxes($emails);


//add empty options.
$emails['']='nobody@example.com';
$mailboxes['']='';

//inbound_email_id
$default_email_address='nobody@example.com';
$from_emails = '';
foreach ($mailboxes as $id=>$name) {
	if (!empty($from_emails)) {
		$from_emails.=',';
	}
	if ($id=='') {
		$from_emails.="'EMPTY','$name','$emails[$id]'";
	} else {
		$from_emails.="'$id','$name','$emails[$id]'";
	}
	if ($id==$focus->inbound_email_id) {
		$default_email_address=$emails[$id];
	}
}
$xtpl->assign("FROM_EMAILS",$from_emails);
$xtpl->assign("DEFAULT_FROM_EMAIL",$default_email_address);

if (empty($focus->inbound_email_id)) {
	$xtpl->assign("MAILBOXES", get_select_options_with_id($mailboxes, ''));
} else {
	$xtpl->assign("MAILBOXES", get_select_options_with_id($mailboxes, $focus->inbound_email_id));	
}

$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['email_marketing_status_dom'], $focus->status));

$xtpl->parse("main");
$xtpl->out("main");


require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();
?>
