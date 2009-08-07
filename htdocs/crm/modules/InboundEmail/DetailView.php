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
 * Description:
 * Created On: Oct 17, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/
require_once('modules/InboundEmail/InboundEmail.php');
require_once('include/DetailView/DetailView.php');
require_once('modules/Emails/Email.php');

global $mod_strings;
global $app_strings;
global $sugar_config;
global $timedate;
global $theme;

/* start standard DetailView layout process */
$GLOBALS['log']->info("InboundEmails DetailView");
$focus = new InboundEmail();
$focus->retrieve($_REQUEST['record']);
$focus->checkImap();
$detailView = new DetailView();
$offset=0;

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_TITLE'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
/* end standard DetailView layout process */
$exServ = explode('::',$focus->service);
if($focus->delete_seen == 1) {
	$delete_seen = $mod_strings['LBL_MARK_READ_NO'];
} else {
	$delete_seen = $mod_strings['LBL_MARK_READ_YES'];
}

// deferred
//$r = $focus->db->query("SELECT id, name FROM queues WHERE owner_id = '".$focus->id."'");
//$a = $focus->db->fetchByAssoc($r);
//$queue = '<a href="index.php?module=Queues&action=EditView&record='.$a['id'].'">'.$a['name'].'</a>';
$groupName = '';
if($focus->group_id) {
	require_once('modules/Groups/Group.php');
	$group = new Group();
	$group->retrieve($focus->group_id);
	$groupName = $group->user_name;
}

if($focus->template_id) {
	require_once('modules/EmailTemplates/EmailTemplate.php');
	$et = new EmailTemplate();
	$et->retrieve($focus->template_id);
	$emailTemplate = $et->name;
} else {
	$emailTemplate = 'None';
}
if($focus->tls == 'tls') {
	$tls = $app_list_strings['dom_email_bool']['bool_true'];
} else {
	$tls = $app_list_strings['dom_email_bool']['bool_false'];
}
if($focus->ssl == 'ssl') {
	$ssl = $app_list_strings['dom_email_bool']['bool_true'];
} else {
	$ssl = $app_list_strings['dom_email_bool']['bool_false'];
}
if($focus->ca == 'validate-cert') {
	$ca = $app_list_strings['dom_email_bool']['bool_true'];
} else {
	$ca = $app_list_strings['dom_email_bool']['bool_false'];
}


// FROM NAME FROM ADDRESS STRINGS
$email = new Email();
$from = $email->getSystemDefaultEmail();
$fromNameAddr = $from['name'].' &lt;'.$from['email'].'&gt; <br><em>('.$mod_strings['LBL_SYSTEM_DEFAULT'].')</em>';
$onlySince = $mod_strings['LBL_ONLY_SINCE_NO'];

if(!empty($focus->stored_options)) {
	// Reply FROM NAME and Address
	$storedOptions = unserialize(base64_decode($focus->stored_options));
	if(!empty($storedOptions['from_name']) && !empty($storedOptions['from_addr'])) {
		$fromNameAddr = $storedOptions['from_name'].' &lt;'.$storedOptions['from_addr'].'&gt;';
	} 
	// only-since option
	if($storedOptions['only_since']) {
		$onlySince = $mod_strings['LBL_ONLY_SINCE_YES'];
	} else {
		$onlySince = $mod_strings['LBL_ONLY_SINCE_NO'];
	}
	// filter-domain
	if(isset($storedOptions['filter_domain']) && !empty($storedOptions['filter_domain'])) {
		$filterDomain = $storedOptions['filter_domain'];
	} else {
		$filterDomain = $app_strings['NTC_NO_ITEMS_DISPLAY'];
	}
}	

$xtpl = new XTemplate('modules/InboundEmail/DetailView.html');
////	ERRORS from Save
if(isset($_REQUEST['error'])) {
	$xtpl->assign('ERROR', "<div class='error'>".$mod_strings['ERR_NO_OPTS_SAVED']."</div>");	
}
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);
$xtpl->assign('CREATED_BY', $focus->created_by_name);
$xtpl->assign('MODIFIED_BY', $focus->modified_by_name);
$xtpl->assign('THEME', $theme);
$xtpl->assign('GRIDLINE', $gridline);
$xtpl->assign('IMAGE_PATH', $image_path);$xtpl->assign('PRINT_URL', 'index.php?'.$GLOBALS['request_string']);
$xtpl->assign('ID', $focus->id);
$xtpl->assign('STATUS', $focus->status);
$xtpl->assign('SERVER_URL', $focus->server_url);
$xtpl->assign('USER', $focus->email_user);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign('MAILBOX', $focus->mailbox);
$xtpl->assign('SERVER_TYPE', $app_list_strings['dom_email_server_type'][$focus->protocol]);
$xtpl->assign('SSL', $ssl);
$xtpl->assign('TLS', $tls);
$xtpl->assign('CERT', $ca);
$xtpl->assign('MARK_READ', $delete_seen);
// deferred
//$xtpl->assign('QUEUE', $queue);
$xtpl->assign('GROUP_NAME', $groupName);
$xtpl->assign('MAILBOX_TYPE', $app_list_strings['dom_mailbox_type'][$focus->mailbox_type]);
$xtpl->assign('EMAIL_TEMPLATE', $emailTemplate);
$xtpl->assign('FROM_NAME_ADDR', $fromNameAddr);
$xtpl->assign('ONLY_SINCE', $onlySince);
$xtpl->assign('FILTER_DOMAIN', $filterDomain);













if($focus->handleIsPersonal()) {
	$xtpl->assign('LBL_GROUP_QUEUE', $mod_strings['LBL_ASSIGN_TO_USER']);
} else {
	$xtpl->assign('LBL_GROUP_QUEUE', $mod_strings['LBL_GROUP_QUEUE']);
}
$xtpl->parse('main');
$xtpl->out('main');
?>
