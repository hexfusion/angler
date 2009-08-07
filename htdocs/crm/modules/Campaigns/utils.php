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
 * $Id: utils.php,v 1.13 2006/06/06 17:57:56 majed Exp $
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/*
 *returns a list of objects a message can be scoped by, the list contacts the current campaign
 *name and list of all prospects associated with this campaign..
 *
 */
function get_message_scope_dom($campaign_id, $campaign_name,$db=null, $mod_strings=array()) {
		
	//find prospect list attached to this campaign..
	$query =  "SELECT prospect_list_id, prospect_lists.name "; 
	$query .= "FROM prospect_list_campaigns ";
	$query .= "INNER join prospect_lists on prospect_lists.id = prospect_list_campaigns.prospect_list_id "; 
	$query .= "WHERE prospect_lists.deleted = 0 "; 
	$query .= "AND prospect_list_campaigns.deleted=0 "; 
	$query .= "AND campaign_id='".$campaign_id."'";
	$query.=" and prospect_lists.list_type not like 'exempt%'";
	
	if (empty($db)) {
		if (!class_exists('PearDatabase')) {
			
		}
		$db = & PearDatabase::getInstance();
	}
	if (empty($mod_strings) or !isset($mod_strings['LBL_DEFAULT'])) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Campaigns');
	}
	
	//add campaign to the result array.
	//$return_array[$campaign_id]= $campaign_name . ' (' . $mod_strings['LBL_DEFAULT'] . ')';
	
	$result=$db->query($query);
	while(($row=$db->fetchByAssoc($result))!= null) {
		$return_array[$row['prospect_list_id']]=$row['name'];
	}
	if (empty($return_array)) $return_array=array(); 	
	else return $return_array;
}

function get_campaign_mailboxes(&$emails) {
	if (!class_exists('InboundEmail')) {
		require('modules/InboundEmail/InboundEmail.php');
	}
	$query =  "select id,name,stored_options from inbound_email where mailbox_type='bounce' and status='Active'"; 
	if (empty($db)) {
		if (!class_exists('PearDatabase')) {
			
		}
		$db = & PearDatabase::getInstance();
	}
	$result=$db->query($query);
	while(($row=$db->fetchByAssoc($result))!= null) {
		$return_array[$row['id']]=$row['name'];
		
		$emails[$row['id']]=InboundEmail::get_stored_options('from_addr','nobody@example.com',$row['stored_options']);
		
	}

	if (empty($return_array)) $return_array=array(''=>''); 	
	return $return_array;
	
}

function log_campaign_activity($identifier, $activity, $update=true, $clicked_url_key=null) {

	$return_array = array();
		
	if (!class_exists('PearDatabase')) {
		
	}
	$db = & PearDatabase::getInstance();

	$query1="select * from campaign_log where target_tracker_key='$identifier' and activity_type='$activity'";
	if (!empty($clicked_url_key)) {
		$query1.=" AND related_id='$clicked_url_key'";
	}
	$current=$db->query($query1);
	$row=$db->fetchByAssoc($current);

	if ($row==null) {	
		$query="select * from campaign_log where target_tracker_key='$identifier' and activity_type='targeted'";
		$targeted=$db->query($query);
		$row=$db->fetchByAssoc($targeted);
		if ($row) {
			$data['id']="'" . create_guid() . "'";
			$data['campaign_id']="'" . $row['campaign_id'] . "'";
			$data['target_tracker_key']="'" . $identifier . "'";
			$data['target_id']="'" .  $row['target_id'] . "'";
			$data['target_type']="'" .  $row['target_type'] . "'";
			$data['activity_type']="'" .  $activity . "'";
			$data['activity_date']="'" . gmdate("Y-m-d H:i:s") . "'";
			$data['list_id']="'" .  $row['list_id'] . "'";
			$data['hits']=1;
			if (!empty($clicked_url_key)) {
				$data['related_id']="'".$clicked_url_key."'";
				$data['related_type']="'".'CampaignTrackers'."'";				
			}
			//values for return array..
			$return_array['target_id']=$row['target_id'];
			$return_array['target_type']=$row['target_type'];		

			$insert_query="INSERT into campaign_log (" . implode(",",array_keys($data)) . ")"; 
			$insert_query.=" VALUES  (" . implode(",",array_values($data)) . ")"; 
			$db->query($insert_query);
		}
	} else {

		$return_array['target_id']= $row['target_id'];
		$return_array['target_type']= $row['target_type'];		

		$query1="update campaign_log set hits=hits+1 where id='{$row['id']}'";
		$current=$db->query($query1);
	}
	return $return_array;
}

function get_campaign_urls($campaign_id) {
	$return_array=array();

	if (!empty($campaign_id)) {
		
		if (!class_exists('PearDatabase')) {
			
		}
		$db = & PearDatabase::getInstance();

		$query1="select * from campaign_trkrs where campaign_id='$campaign_id' and deleted=0";
		$current=$db->query($query1);
		while (($row=$db->fetchByAssoc($current)) != null) {
			$return_array['{'.$row['tracker_name'].'}']=$row['tracker_name'] . ' : ' . $row['tracker_url'];
		}		
	}
	return $return_array;
}
?>
