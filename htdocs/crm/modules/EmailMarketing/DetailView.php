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
 * $Id: DetailView.php,v 1.10 2006/06/06 17:58:19 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/EmailMarketing/EmailMarketing.php');
require_once('modules/EmailMarketing/Forms.php');

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

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("EmailMarketing Edit View");
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
$xtpl=new XTemplate ('modules/EmailMarketing/DetailView.html');

$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

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
$xtpl->assign("JAVASCRIPT", get_set_focus_js());
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("FROM_NAME", $focus->from_name);
$xtpl->assign("FROM_ADDR", $focus->from_addr);
$xtpl->assign("DATE_START", $focus->date_start);
$xtpl->assign("TIME_START", $focus->time_start);

$email_templates_arr = get_bean_select_array(true, 'EmailTemplate','name');
if($focus->template_id) {
	$xtpl->assign("EMAIL_TEMPLATE", $email_templates_arr[$focus->template_id]);
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

if (!empty($focus->all_prospect_lists)) {
	$xtpl->assign("MESSAGE_FOR", $mod_strings['LBL_ALL_PROSPECT_LISTS']);
} else {
	$xtpl->assign("MESSAGE_FOR", $mod_strings['LBL_RELATED_PROSPECT_LISTS']);
}

if (!empty($focus->status)) {
	$xtpl->assign("STATUS",$app_list_strings['email_marketing_status_dom'][$focus->status]);
}
$emails=array();
$mailboxes=get_campaign_mailboxes($emails);
//_ppd($emails[$focus->inbound_email_id]);
if (!empty($focus->inbound_email_id) && isset($mailboxes[$focus->inbound_email_id])) {
	$xtpl->assign("FROM_MAILBOX_NAME", $mailboxes[$focus->inbound_email_id]."&nbsp;&lt;{$emails[$focus->inbound_email_id]}&gt;");
}

$xtpl->parse("main");
$xtpl->out("main");

require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'EmailMarketing');

if ($focus->all_prospect_lists == 1) {
	$subpanel->subpanel_definitions->exclude_tab('prospectlists');
}
else {
	$subpanel->subpanel_definitions->exclude_tab('allprospectlists');

}

echo $subpanel->display();

?>
