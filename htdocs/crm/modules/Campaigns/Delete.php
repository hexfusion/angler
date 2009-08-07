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
 * $Id: Delete.php,v 1.10 2006/06/06 17:57:56 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

if(!isset($_REQUEST['record']))
	sugar_die("A record number must be specified to delete the campaign.");

require_once('modules/Campaigns/Campaign.php');

$focus = new Campaign();
$focus->retrieve($_REQUEST['record']);

if (isset($_REQUEST['mode']) and  $_REQUEST['mode']=='Test') {
	//deletes all data associated with the test run.

	//delete from emails table.	
	if ($focus->db->dbType=='mysql') {
		
		$query="update  emails "; 
		$query.="inner join campaign_log on campaign_log.related_id = emails.id and campaign_log.campaign_id = '{$focus->id}' ";
		$query.="inner join prospect_lists on campaign_log.list_id = prospect_lists.id and prospect_lists.list_type='test' ";
		$query.="set emails.deleted=1 ";
	} else {










	}
	$focus->db->query($query);
		
	//delete from message queue.
	if ($focus->db->dbType=='mysql') {
		$query="delete emailman.* from emailman ";
		$query.="inner join prospect_lists on emailman.list_id = prospect_lists.id and prospect_lists.list_type='test' ";
		$query.="WHERE emailman.campaign_id = '{$focus->id}' ";
	} else {








	}
	$focus->db->query($query);

	//delete from campaign_log
	if ($focus->db->dbType=='mysql') {
		$query="update  campaign_log "; 
		$query.="inner join prospect_lists on campaign_log.list_id = prospect_lists.id and prospect_lists.list_type='test' ";
		$query.="set campaign_log.deleted=1 ";
		$query.="where campaign_log.campaign_id='{$focus->id}' ";
	} else {









	}
	$focus->db->query($query);
} else {
	if(!$focus->ACLAccess('Delete')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
	}
	$focus->mark_deleted($_REQUEST['record']);
}
$return_id=!empty($_REQUEST['return_id'])?$_REQUEST['return_id']:$focus->id;
header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$return_id);
?>
