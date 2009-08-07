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
 * $Id: CampaignLog.php,v 1.20 2006/08/25 21:27:16 eddy Exp $
 * Description:
 ********************************************************************************/




require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('modules/ProspectLists/ProspectList.php');

class CampaignLog extends SugarBean {

	var $table_name = 'campaign_log';
	var $object_name = 'CampaignLog';
	var $module_dir = 'CampaignLog';
	
	var $new_schema = true;
		
	var $campaign_id;
	var $target_tracker_key;
	var $target_id;
	var $target_type;
	var $activity_type;
	var $activity_date;
	var $related_id;
	var $related_type;
	var $deleted;
	var $list_id;
	var $hits;
	var $more_information;
	function CampaignLog() {
		global $sugar_config;
		parent::SugarBean();
		




	}	

	function get_list_view_data(){
		$temp_array = $this->get_list_view_array();
        //make sure that both items in array are set to some value, else return null
        if(!(isset($temp_array['TARGET_TYPE']) && $temp_array['TARGET_TYPE']!= '') || !(isset($temp_array['TARGET_ID']) && $temp_array['TARGET_ID']!= ''))
        {   //needed values to construct query are empty/null, so return null
            $GLOBALS['log']->debug("CampaignLog.php:get_list_view_data duntion: temp_array['TARGET_TYPE'] and/or temp_array['TARGET_ID'] are empty, return null");
            $emptyArr = array();
            return $emptyArr; 
        }
		if ( ( $this->db->dbType == 'mysql' ) or ( $this->db->dbType == 'oci8' ) )
		{			
			$query="select CONCAT(CONCAT(first_name, ' '), last_name) name , email1 from ".strtolower($temp_array['TARGET_TYPE']) .  " where id ='{$temp_array['TARGET_ID']}'";			
		}
		if($this->db->dbType == 'mssql')
		{	
			     $query="select (first_name + ' ' + last_name) name , email1 from ".strtolower($temp_array['TARGET_TYPE']) .  " where id ='{$temp_array['TARGET_ID']}'";
		}

		$result=$this->db->query($query);
		$row=$this->db->fetchByAssoc($result);
		if ($row) {
			$temp_array['RECIPIENT_NAME']=$row['name'];		
			$temp_array['RECIPIENT_EMAIL']=$row['email1'];		
		}
		return $temp_array;
	}
	
	function create_list_query($order_by, $where, $show_deleted=0) {
	
		$query  = "SELECT campaign_log.*, campaigns.name campaign_name, campaigns.objective campaign_objective, campaigns.content campaign_content";
		$query .= " FROM campaign_log";
		$query .= " LEFT JOIN campaigns ON campaigns.id = campaign_id AND campaigns.deleted=0";

        if(!empty($where))
			$query .= " WHERE ($where) ";

        if(!empty($order_by))
			$query .= " ORDER BY $order_by";

		return $query;
	}

	//this function is called statically by the campaing_log subpanel.
	 function get_related_name($related_id, $related_type) {
	 	$db= & PearDatabase::getInstance();
	 	if ($related_type == 'Emails') {
	 		$query="SELECT name from emails where id='$related_id'";
	 		$result=$db->query($query);
	 		$row=$db->fetchByAssoc($result);
	 		if ($row != null) {
	 			return $row['name'];
	 		}
	 	}
	 	if ($related_type == 'Contacts') {
	 		$query="SELECT first_name, last_name from emails where id='$related_id'";
	 		$result=$db->query($query);
	 		$row=$db->fetchByAssoc($result);
	 		if ($row != null) {
	 			return $row['first_name'] . ' ' . $row['last_name'];
	 		}
	 	}
	 	if ($related_type == 'CampaignTrackers') {
	 		$query="SELECT tracker_url from campaign_trkrs where id='$related_id'";
	 		$result=$db->query($query);
	 		$row=$db->fetchByAssoc($result);
	 		if ($row != null) {
	 			return $row['tracker_url'] ;
	 		}
	 	}

		return $related_id.$related_type;
	}
	
}

?>
