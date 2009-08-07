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
 * $Id: Menu.php,v 1.18 2006/06/06 17:58:20 majed Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $mod_strings;
global $current_user;

$default = 'index.php?module=Emails&action=ListView&assigned_user_id='.$current_user->id;
require_once('modules/Emails/Email.php');
$e = new Email();

// my inbox
if(ACLController::checkAccess('Emails', 'edit', true)) {
	$r = $e->db->query("SELECT count(*) AS c FROM emails WHERE deleted=0 AND assigned_user_id = '".$current_user->id."' AND type = 'inbound' AND status = 'unread'");
	$a = $e->db->fetchByAssoc($r);
	$module_menu[] = array($default.'&type=inbound', $mod_strings['LNK_MY_INBOX'].'&nbsp;&nbsp;<b>('.$a['c'].' '.$mod_strings['LBL_NEW'].')</b>',"EmailFolder","Emails");
}
// check My Mail
if($current_user->hasPersonalEmail()) {
	$module_menu[] = array($default.'&action=Check&type=personal', $mod_strings['LNK_CHECK_MY_INBOX'], 'EmailFolder', 'Emails');
}
// my drafts
if(ACLController::checkAccess('Emails', 'edit', true)) $module_menu[] = array($default.'&type=draft', $mod_strings['LNK_MY_DRAFTS'],"EmailFolder", 'Emails');
// sent
if(ACLController::checkAccess('Emails', 'list', true)) $module_menu[] = array($default.'&type=out', $mod_strings['LNK_SENT_EMAIL_LIST'],"EmailFolder","Emails");
// my archives
if(ACLController::checkAccess('Emails', 'list', true)) $module_menu[] = array($default.'&type=archived', $mod_strings['LNK_MY_ARCHIVED_LIST'],"EmailFolder","Emails");
// group inbox
if(ACLController::checkAccess('Emails', 'edit', true)) {
	$r = $e->db->query('SELECT id FROM users WHERE users.is_group = 1 AND deleted = 0');
	$groupIds = '';
	$groupNew = '';
	while($a = $e->db->fetchByAssoc($r)) {
		if($groupIds != '') {$groupIds .= ', ';}
		$groupIds .= "'".$a['id']."'";
	}
	
	$total = 0;
	if(strlen($groupIds) > 0) {
		$r = $e->db->query('SELECT count(*) AS c FROM emails WHERE deleted=0 AND assigned_user_id IN ('.$groupIds.') AND type = \'inbound\' AND status = \'unread\'');
		if(is_resource($r)) {
			$a = $e->db->fetchByAssoc($r);
			if($a['c'] > 0) {
				$total = $a['c'];
			}
		}
	}

	$groupNew = '<b>&nbsp;&nbsp;('.$total.' '.$mod_strings['LBL_NEW'].')</b>';
	$module_menu[] = array('index.php?module=Emails&action=ListViewGroup', $mod_strings['LNK_GROUP_INBOX'].$groupNew,"EmailFolder", 'Emails');
}
// visual split
$module_menu[] = array('','','','Emails');
// compose
if(ACLController::checkAccess('Emails', 'edit', true)) $module_menu[] = array("index.php?module=Emails&action=EditView&type=out&return_module=Emails&return_action=DetailView", $mod_strings['LNK_NEW_SEND_EMAIL'],"CreateEmails", 'Emails');
// create archived
if(ACLController::checkAccess('Emails', 'edit', true)) $module_menu[] = array("index.php?module=Emails&action=EditView&type=archived&return_module=Emails&return_action=DetailView", $mod_strings['LNK_NEW_ARCHIVE_EMAIL'],"CreateEmails", 'Emails');
// create email template
if(ACLController::checkAccess('EmailTemplates', 'edit', true)) $module_menu[] = array("index.php?module=EmailTemplates&action=EditView&return_module=EmailTemplates&return_action=DetailView", $mod_strings['LNK_NEW_EMAIL_TEMPLATE'],"CreateEmails","Emails");
// all drafts
if(ACLController::checkAccess('Emails', 'list', true)) $module_menu[] = array("index.php?module=Emails&action=ListView&type=draft", $mod_strings['LNK_DRAFTS_EMAIL_LIST'],"EmailFolder", 'Emails');
// all emails
if(ACLController::checkAccess('Emails', 'list', true)) $module_menu[] = array("index.php?module=Emails&action=ListViewAll&all=true", $mod_strings['LNK_ALL_EMAIL_LIST'],"EmailFolder","Emails");
// email templates
if(ACLController::checkAccess('EmailTemplates', 'edit', true)) $module_menu[] = array("index.php?module=EmailTemplates&action=index", $mod_strings['LNK_EMAIL_TEMPLATE_LIST'],"EmailFolder", 'Emails');
?>
