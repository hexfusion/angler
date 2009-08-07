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
require_once('modules/InboundEmail/InboundEmail.php');
global $current_user;

if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'personal') {
	if($current_user->hasPersonalEmail()) {
		
		$ie = new InboundEmail();
		$beans = $ie->retrieveByGroupId($current_user->id);
		if(!empty($beans)) {
			foreach($beans as $bean) {
				$bean->connectMailserver();
				$newMsgs = $bean->getNewMessageIds();
				if(is_array($newMsgs)) {
					foreach($newMsgs as $k => $msgNo) {
						$bean->importOneEmail($msgNo);
					}
				}
				imap_expunge($bean->conn);
				imap_close($bean->conn);
			}	
		}
	}
	header('Location: index.php?module=Emails&action=ListView&type=inbound&assigned_user_id='.$current_user->id);
} elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'group') {
	$ie = new InboundEmail();
	// this query only polls Group Inboxes
	$r = $ie->db->query('SELECT inbound_email.id FROM inbound_email JOIN users ON inbound_email.group_id = users.id WHERE inbound_email.deleted=0 AND inbound_email.status = \'Active\' AND mailbox_type != \'bounce\' AND users.deleted = 0 AND users.is_group = 1');

	while($a = $ie->db->fetchByAssoc($r)) {
		$ieX = new InboundEmail();
		$ieX->retrieve($a['id']);
		$ieX->connectMailserver();
	
		$newMsgs = $ieX->getNewMessageIds();
		if(is_array($newMsgs)) {
			foreach($newMsgs as $k => $msgNo) {
				$ieX->importOneEmail($msgNo);
			}
		}
		imap_expunge($ieX->conn);
		imap_close($ieX->conn);
	}
	header('Location: index.php?module=Emails&action=ListViewGroup');
} else { // fail gracefully
	header('Location: index.php?module=Emails&action=index');
}


?>
