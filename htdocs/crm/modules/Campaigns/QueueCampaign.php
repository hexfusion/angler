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
 * $Id: QueueCampaign.php,v 1.21 2006/06/06 17:57:56 majed Exp $
 * Description: Schedules email for delivery. emailman table holds emails for delivery.
 * A cron job polls the emailman table and delivers emails when intended send date time is reached.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('modules/Campaigns/Campaign.php');
require_once('modules/ProspectLists/ProspectList.php');
require_once('modules/EmailMan/EmailMan.php');

require_once('include/utils.php');
global $timedate;
global $current_user;
global $mod_strings;

$campaign = new Campaign();
$campaign->retrieve($_REQUEST['record']);
$err_messages=array();

$test=false;
if (isset($_REQUEST['mode']) && $_REQUEST['mode'] =='test') {
	$test=true;
}

//if campaign status is 'sending' disallow this step.
if (!empty($campaign->status) && $campaign->status == 'sending') {
	$err_messages[]=$mod_strings['ERR_SENDING_NOW'];
}
if ($campaign->db->dbType=='oci8') {



} else {
	$current_date= "'".gmdate("Y-m-d H:i:s")."'";
}

//start scheduling now.....
foreach ($_POST['mass'] as $message_id) {

	//fetch email marketing definition.
	if (!class_exists('EmailMarketing')) require_once('modules/EmailMarketing/EmailMarketing.php');
	
	$marketing = new EmailMarketing();
	$marketing->retrieve($message_id);
	
	//make sure that the marketing message has a mailbox.
	//
	if (empty($marketing->inbound_email_id)) {
		
		echo "<p>";
		echo "<h4>{$mod_strings['ERR_NO_MAILBOX']}</h4>";
		echo "<BR><a href='index.php?module=EmailMarketing&action=EditView&record={$marketing->id}'>$marketing->name</a>";
		echo "</p>";
		sugar_die('');		
	}


	global $timedate;
	$mergedvalue=$timedate->merge_date_time($marketing->date_start,$marketing->time_start);
	
	if ($campaign->db->dbType=='oci8') {







	} else {
		if ($test) {
			$send_date_time="'".gmdate("Y-m-d H:i:s", mktime(0,0,0,8,15,1996))."'";		
		} else {
			$send_date_time= "'".$timedate->to_db_date($mergedvalue) . ' ' .$timedate->to_db_time($mergedvalue)."'";	
		}
	}

	//find all prospect lists associated with this email marketing message.
	if ($marketing->all_prospect_lists == 1) {
		$query="SELECT prospect_lists.id prospect_list_id from prospect_lists ";
		$query.=" INNER JOIN prospect_list_campaigns plc ON plc.prospect_list_id = prospect_lists.id";
		$query.=" WHERE plc.campaign_id='{$campaign->id}'"; 
		$query.=" AND prospect_lists.deleted=0";
		$query.=" AND plc.deleted=0";
		if ($test) {
			$query.=" AND prospect_lists.list_type='test'";		
		} else {
			$query.=" AND prospect_lists.list_type!='test' AND prospect_lists.list_type not like 'exempt%'";					
		}
	} else {
		$query="select email_marketing_prospect_lists.* FROM email_marketing_prospect_lists ";
		$query.=" inner join prospect_lists on prospect_lists.id = email_marketing_prospect_lists.prospect_list_id";
		$query.=" WHERE prospect_lists.deleted=0 and email_marketing_id = '$message_id' and email_marketing_prospect_lists.deleted=0";
		
		if ($test) {
			$query.=" AND prospect_lists.list_type='test'";		
		} else {
			$query.=" AND prospect_lists.list_type!='test' AND prospect_lists.list_type not like 'exempt%'";					
		}
	}
	$result=$campaign->db->query($query);
	while (($row=$campaign->db->fetchByAssoc($result))!=null ) {
		

		$prospect_list_id=$row['prospect_list_id'];
		
		//delete all messages for the current campaign and current email marketing message.
		$delete_emailman_query="delete from emailman where campaign_id='{$campaign->id}' and marketing_id='{$message_id}' and list_id='{$prospect_list_id}'";
		$campaign->db->query($delete_emailman_query);
		
		$insert_query= "INSERT INTO emailman (date_entered, user_id, campaign_id, marketing_id,list_id, related_id, related_type, send_date_time";
		if ($campaign->db->dbType=='oci8') {



		}
		$insert_query.=')'; 
		$insert_query.= " SELECT $current_date,'{$current_user->id}',plc.campaign_id,'{$message_id}',plp.prospect_list_id, plp.related_id, plp.related_type,{$send_date_time} ";  
		if ($campaign->db->dbType=='oci8') {



		}
		$insert_query.= "FROM prospect_lists_prospects plp ";
		$insert_query.= "INNER JOIN prospect_list_campaigns plc ON plc.prospect_list_id = plp.prospect_list_id "; 
		$insert_query.= "WHERE plp.prospect_list_id = '{$prospect_list_id}' ";
		$insert_query.= "AND plp.deleted=0 ";
		$insert_query.= "AND plc.deleted=0 ";
		$insert_query.= "AND plc.campaign_id='{$campaign->id}'";

		if ($campaign->db->dbType=='oci8') {










		}
		$campaign->db->query($insert_query);
	}
}

//delete all entries from the emailman table that belong to the exempt list.
if (!$test) {	
	//id based exempt list treatment.
	if ($campaign->db->dbType !='oci8') {
		$delete_query = "DELETE emailman.* FROM emailman ";  
		$delete_query.= "INNER JOIN prospect_lists_prospects plp on plp.related_id = emailman.related_id and  plp.related_type = emailman.related_type ";
		$delete_query.= "INNER JOIN prospect_lists pl ON pl.id = plp.prospect_list_id ";
		$delete_query.= "WHERE plp.deleted=0 ";
		$delete_query.= "AND pl.list_type = 'exempt' ";
		$delete_query.= "AND emailman.campaign_id='{$campaign->id}'";

		$campaign->db->query($delete_query);	
	}
}
$return_module=isset($_REQUEST['return_module'])?$_REQUEST['return_module']:'Campaigns';
$return_action=isset($_REQUEST['return_action'])?$_REQUEST['return_action']:'DetailView';
$return_id=$_REQUEST['record'];

if ($test) {
	//navigate to EmailManDelivery..
	$header_URL = "Location: index.php?action=EmailManDelivery&module=EmailMan&campaign_id={$_REQUEST['record']}&return_module={$return_module}&return_action={$return_action}&return_id={$return_id}&mode=test";
} else {
	//navigate back to campaign detail view...
	$header_URL = "Location: index.php?action={$return_action}&module={$return_module}&record={$return_id}";
}
$GLOBALS['log']->debug("about to post header URL of: $header_URL");
header($header_URL);
?>
