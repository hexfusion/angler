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
 * $Id: Call.php,v 1.165 2006/08/09 19:28:41 jenny Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/




require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Users/User.php');

// Call is used to store customer information.
class Call extends SugarBean {
	var $field_name_map;
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;



	var $description;
	var $name;
	var $status;
	var $date_start;
	var $time_start;
	var $duration_hours;
	var $duration_minutes;
	var $date_end;
	var $parent_type;
	var $parent_id;
	var $contact_id;
	var $user_id;
	var $direction;
	var $reminder_time;
	var $required;
	var $accept_status;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $account_id;
	var $opportunity_id;
	var $case_id;
	var $assigned_user_name;
	var $note_id;
    var $outlook_id;



	var $update_vcal = true;
	var $contacts_arr;
	var $users_arr;
	var $default_call_name_values = array('Assemble catalogs', 'Make travel arrangements', 'Send a letter', 'Send contract', 'Send fax', 'Send a follow-up letter', 'Send literature', 'Send proposal', 'Send quote');
	var $minutes_value_default = 15;
	var $minutes_values = array('0'=>'00','15'=>'15','30'=>'30','45'=>'45');
	var $table_name = "calls";
	var $rel_users_table = "calls_users";
	var $rel_contacts_table = "calls_contacts";
	var $module_dir = 'Calls';
	var $object_name = "Call";
	var $new_schema = true;
	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = array('assigned_user_name', 'assigned_user_id', 'contact_id', 'user_id', 'contact_name');
	var $relationship_fields = array(	'account_id'		=> 'accounts',
										'opportunity_id'	=> 'opportunities',
										'contact_id'		=> 'contacts',
										'case_id'			=> 'cases',
										'user_id'			=> 'users',
										'assigned_user_id'	=> 'users',
										'note_id'			=> 'notes',
								);

	function Call() {
		parent::SugarBean();
		global $app_list_strings;
	
       	$this->setupCustomFields('Calls');

		foreach ($this->field_defs as $field) {
			$this->field_name_map[$field['name']] = $field;
		}



	}

    // save date_end by calculating user input
    // this is for calendar
	function save($check_notify = FALSE) {
		global $timedate;
		
        if(	isset($this->date_start) && 
        	isset($this->time_start) && 
        	isset($this->duration_hours) &&
        	isset($this->duration_minutes) ) {
			$date_time_start = DateTime::get_time_start($timedate->to_db_date($this->date_start,false),$this->time_start.":00");
            $date_time_end = DateTime::get_time_end($date_time_start,$this->duration_hours,$this->duration_minutes);
            $this->date_end = $timedate->to_display_date("{$date_time_end->year}-{$date_time_end->month}-{$date_time_end->day}");
        }

		if(!empty($_REQUEST['send_invites']) && $_REQUEST['send_invites'] == '1') {
			$check_notify = true;
        } else {
			$check_notify = false;
        }

        parent::save($check_notify);
        global $current_user;
        require_once('modules/vCals/vCal.php');
        
        if($this->update_vcal) {
			vCal::cache_sugar_vcal($current_user);
        }
	}

	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_contacts()
	{
		// First, get the list of IDs.
		$query = "SELECT contact_id as id from calls_contacts where call_id='$this->id' AND deleted=0";

		return $this->build_related_list($query, new Contact());
	}


	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query($order_by, $where, $show_deleted=0)
	{
		$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT ";
		$query .= "
			calls.*,";
			if ( preg_match("/calls_users\.user_id/",$where))
			{
				$query .= "calls_users.required,
				calls_users.accept_status,";
			}

			$query .= "
			users.user_name as assigned_user_name";



			if($custom_join){
   				$query .= $custom_join['select'];
 			}

			// this line will help generate a GMT-metric to compare to a locale's timezone

			if ( ( $this->db->dbType == 'mysql' ) or ( $this->db->dbType == 'oci8' ) )
			{	
				$query .= ", CONCAT( calls.date_start, CONCAT(' ', calls.time_start) ) AS datetime ";
				if ( preg_match("/contacts/",$where)){
					$query .= ", contacts.first_name, contacts.last_name";	
					$query .= ", contacts.assigned_user_id contact_name_owner";
				}
				$query .= " FROM calls ";
			}

			if ( $this->db->dbType == 'mssql' )   
			{	
				$query .= ", calls.date_start + ' ' + calls.time_start AS datetime ";
				if ( preg_match("/contacts/",$where)){
					$query .= ", contacts.first_name, contacts.last_name";	
					$query .= ", contacts.assigned_user_id contact_name_owner";
				}
				$query .= " FROM calls ";
			}




			if ( preg_match("/contacts/",$where)){
				$query .=	"LEFT JOIN calls_contacts
	                    ON calls.id=calls_contacts.call_id
	                    LEFT JOIN contacts
	                    ON calls_contacts.contact_id=contacts.id ";
			}
			if ( preg_match("/calls_users\.user_id/",$where))
			{
		$query .= "LEFT JOIN calls_users
			ON calls.id=calls_users.call_id and calls_users.deleted=0 ";
			}



			$query .= " 
			LEFT JOIN users
			ON calls.assigned_user_id=users.id ";
			if($custom_join){
  				$query .= $custom_join['join'];
			}
			$where_auto = '1=1';
       		 if($show_deleted == 0){
            	$where_auto = " $this->table_name.deleted=0  ";
			}else if($show_deleted == 1){
				$where_auto = " $this->table_name.deleted=1 ";	
			}

			//$where_auto .= " GROUP BY calls.id";

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
		$query .=  " ORDER BY ". $this->process_order_by($order_by, null);
		else
			$query .= " ORDER BY calls.name";
		return $query;
	}

	function create_export_query(&$order_by, &$where)
        {
            $contact_required = ereg("contacts", $where);
			$custom_join = $this->custom_fields->getJOIN();
            if($contact_required)
            {
                    $query = "SELECT calls.*, contacts.first_name, contacts.last_name";



                    if($custom_join){
   						$query .= $custom_join['select'];
 					}
                    $query .= " FROM contacts, calls, calls_contacts ";
                    $where_auto = "calls_contacts.contact_id = contacts.id AND calls_contacts.call_id = calls.id AND calls.deleted=0 AND contacts.deleted=0";
            }
            else
            {
                    $query = 'SELECT calls.*';



                   	if($custom_join){
   						$query .= $custom_join['select'];
 					}
                    $query .= ' FROM calls ';
                    $where_auto = "calls.deleted=0";
            }









			if($custom_join){
  				$query .= $custom_join['join'];
			}

			if($where != "")
                    $query .= "where $where AND ".$where_auto;
            else
                    $query .= "where ".$where_auto;

            if($order_by != "")
                    $query .=  " ORDER BY ". $this->process_order_by($order_by, null);	
            else
                    $query .= " ORDER BY calls.name";

            return $query;
        }



	function fill_in_additional_list_fields()
	{
//		$this->fill_in_additional_detail_fields();
//		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$query  = "SELECT c.first_name, c.last_name, c.phone_work, c.email1, c.id FROM contacts  c, calls_contacts  c_c ";
		$query .= "WHERE c_c.contact_id=c.id AND c_c.call_id='$this->id' AND c_c.deleted=0 AND c.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = Array();
		$row = $this->db->fetchByAssoc($result);

		$GLOBALS['log']->info("additional call fields $query");

		if($row != null)
		{
			$this->contact_name = return_name($row, 'first_name', 'last_name');
			$this->contact_phone = $row['phone_work'];
			$this->contact_id = $row['id'];
			$this->contact_email = $row['email1'];
			$GLOBALS['log']->debug("Call($this->id): contact_name = $this->contact_name");
			$GLOBALS['log']->debug("Call($this->id): contact_phone = $this->contact_phone");
			$GLOBALS['log']->debug("Call($this->id): contact_id = $this->contact_id");
			$GLOBALS['log']->debug("Call($this->id): contact_email1 = $this->contact_email");
		}
		else {
			$this->contact_name = '';
			$this->contact_phone = '';
			$this->contact_id = '';
			$this->contact_email = '';
			$GLOBALS['log']->debug("Call($this->id): contact_name = $this->contact_name");
			$GLOBALS['log']->debug("Call($this->id): contact_phone = $this->contact_phone");
			$GLOBALS['log']->debug("Call($this->id): contact_id = $this->contact_id");
			$GLOBALS['log']->debug("Call($this->id): contact_email1 = $this->contact_email");
		}
		$this->fill_in_additional_parent_fields();

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
	}

	function fill_in_additional_parent_fields()
	{
		$this->parent_name = '';
		global $app_strings, $beanFiles, $beanList;
		if ( ! isset($beanList[$this->parent_type]))
		{
			$this->parent_name = '';
			return;
		}
	    	$beanType = $beanList[$this->parent_type];
		require_once($beanFiles[$beanType]);
		$parent = new $beanType();
		if ($this->parent_type == "Leads" || $this->parent_type == "Contacts") {
			$query = "SELECT first_name, last_name, assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
		}
		else {
			
			$query = "SELECT name ";
			if(isset($parent->field_defs['assigned_user_id'])){
				$query .= " , assigned_user_id parent_name_owner ";
			}else{
				$query .= " , created_by parent_name_owner ";
			}
			$query .= " from $parent->table_name where id = '$this->parent_id'";
		}
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if ($row && !empty($row['parent_name_owner'])){
			$this->parent_name_owner = $row['parent_name_owner'];
			$this->parent_name_mod = $this->parent_type;
		}
		if (($this->parent_type == "Leads" || $this->parent_type == "Contacts") and $row != null)
		{
			$this->parent_name = '';
			if ($row['first_name'] != '') $this->parent_name .= stripslashes($row['first_name']). ' ';
			if ($row['last_name'] != '') $this->parent_name .= stripslashes($row['last_name']);
		}
		elseif($row != null)
		{
			$this->parent_name = stripslashes($row['name']);
		}
		else {
			$this->parent_name = '';
		}
	}

	function get_list_view_data(){
		$call_fields = $this->get_list_view_array();
		global $app_list_strings, $focus, $action, $currentModule, $image_path;
		if (isset($focus->id)) $id = $focus->id;
		else $id = '';
		if (isset($this->parent_type) && $this->parent_type != null)
		{
			$call_fields['PARENT_MODULE'] = $this->parent_type;
		}
		if ($this->status == "Planned") {
			//cn: added this if() to deal with sequential Closes in Meetings.  this is a hack to a hack (formbase.php->handleRedirect)
			if(empty($action)) { $action = "index"; }
			$call_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$id&action=EditView&status=Held&module=Calls&record=$this->id&status=Held'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
		}		
		global $timedate;
		$today = date('Y-m-d H:i:s', time());
		$nextday = date('Y-m-d', time() + 3600*24);
		$mergeTime = $timedate->merge_date_time($call_fields['DATE_START'], $call_fields['TIME_START']);
		$date_db = $timedate->to_db($mergeTime);
		if( $date_db	< $today){
			$call_fields['DATE_START']= "<font class='overdueTask'>".$call_fields['DATE_START']."</font>";
		}else if($date_db < $nextday){
			$call_fields['DATE_START'] = "<font class='todaysTask'>".$call_fields['DATE_START']."</font>";
		}else{
			$call_fields['DATE_START'] = "<font class='futureTask'>".$call_fields['DATE_START']."</font>";
		}
		$call_fields['CONTACT_ID'] = empty($this->contact_id) ? '' : $this->contact_id;

		$call_fields['PARENT_NAME'] = $this->parent_name;
		
		return $call_fields;
	}

	function set_notification_body($xtpl, $call) {
		global $sugar_config;
		global $app_list_strings;
		global $current_user;
		global $app_list_strings;
		global $timedate;
		
		$prefDate = User::getUserDateTimePreferences($call->current_notify_user);
		$x = date($prefDate['date']." ".$prefDate['time'], strtotime(($call->date_start . " " . $call->time_start)));
		$xOffset = $timedate->handle_offset($x, $prefDate['date']." ".$prefDate['time'], true, $current_user);

		if ( strtolower(get_class($call->current_notify_user)) == 'contact' ) {
			$xtpl->assign("ACCEPT_URL", $sugar_config['site_url'].
				  '/acceptDecline.php?module=Calls&contact_id='.$call->current_notify_user->id.'&record='.$call->id);
		} else {
			$xtpl->assign("ACCEPT_URL", $sugar_config['site_url'].
				  '/acceptDecline.php?module=Calls&user_id='.$call->current_notify_user->id.'&record='.$call->id);
		}

		$xtpl->assign("CALL_TO", $call->current_notify_user->new_assigned_user_name);
		$xtpl->assign("CALL_SUBJECT", $call->name);
		$xtpl->assign("CALL_STARTDATE", $xOffset . " " . (!empty($app_list_strings['dom_timezones_extra'][$prefDate['userGmtOffset']]) ? $app_list_strings['dom_timezones_extra'][$prefDate['userGmtOffset']] : $prefDate['userGmt']));
		$xtpl->assign("CALL_HOURS", $call->duration_hours);
		$xtpl->assign("CALL_MINUTES", $call->duration_minutes);
		$xtpl->assign("CALL_STATUS", ((isset($call->status))?$app_list_strings['call_status_dom'][$call->status] : ""));
		$xtpl->assign("CALL_DESCRIPTION", $call->description);
		
		return $xtpl;
	}


	function get_call_users() {
		$template = new User();
		// First, get the list of IDs.
		$query = "SELECT calls_users.required, calls_users.accept_status, calls_users.user_id from calls_users where calls_users.call_id='$this->id' AND calls_users.deleted=0";
		$GLOBALS['log']->debug("Finding linked records $this->object_name: ".$query);
		$result = $this->db->query($query, true);
		$list = Array();
		
		while($row = $this->db->fetchByAssoc($result)) {
			$template = new User(); // PHP 5 will retrieve by reference, always over-writing the "old" one
			$record = $template->retrieve($row['user_id']);
			$template->required = $row['required'];
			$template->accept_status = $row['accept_status'];
			
			if($record != null) {
			    // this copies the object into the array
				$list[] = $template;
			}
		}
		return $list;
	}


  function get_invite_calls(&$user)
  {
    $template = $this;
    // First, get the list of IDs.
    $query = "SELECT calls_users.required, calls_users.accept_status, calls_users.call_id from calls_users where calls_users.user_id='$user->id' AND ( calls_users.accept_status IS NULL OR  calls_users.accept_status='none') AND calls_users.deleted=0";
    $GLOBALS['log']->debug("Finding linked records $this->object_name: ".$query);


    $result = $this->db->query($query, true);


    $list = Array();


    while($row = $this->db->fetchByAssoc($result))
    {
      $record = $template->retrieve($row['call_id']);
      $template->required = $row['required'];
      $template->accept_status = $row['accept_status'];


      if($record != null)
      {
        // this copies the object into the array
        $list[] = $template;
      }
    }
    return $list;

  }


  function set_accept_status(&$user,$status)
  {
    if ( $user->object_name == 'User')
    {
      $relate_values = array('user_id'=>$user->id,'call_id'=>$this->id);
      $data_values = array('accept_status'=>$status);
      $this->set_relationship($this->rel_users_table, $relate_values, true, true,$data_values);
      global $current_user;
      require_once('modules/vCals/vCal.php');
      if ( $this->update_vcal )
      {
        vCal::cache_sugar_vcal($user);
      }
    }
    else if ( $user->object_name == 'Contact')
    {
      $relate_values = array('contact_id'=>$user->id,'call_id'=>$this->id);
      $data_values = array('accept_status'=>$status);
      $this->set_relationship($this->rel_contacts_table, $relate_values, true, true,$data_values);
    }
  }



	function get_notification_recipients() {
		$list = array();
		if(!is_array($this->contacts_arr)) {
			$this->contacts_arr =	array();
		}
		
		if(!is_array($this->users_arr)) {
			$this->users_arr =	array();
		}
		
		foreach($this->users_arr as $user_id) {
			$notify_user = new User();
			$notify_user->retrieve($user_id);
			$notify_user->new_assigned_user_name = $notify_user->first_name.' '.$notify_user->last_name;
			$GLOBALS['log']->info("Notifications: recipient is $notify_user->new_assigned_user_name");
			$list[] = $notify_user;
		}
		
		foreach($this->contacts_arr as $contact_id) {
			$notify_user = new Contact();
			$notify_user->retrieve($contact_id);
			$notify_user->new_assigned_user_name = $notify_user->first_name.' '.$notify_user->last_name;
			$GLOBALS['log']->info("Notifications: recipient is $notify_user->new_assigned_user_name");
			$list[] = $notify_user;
		}
		
		return $list;
	}









  
  function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}
	function listviewACLHelper(){
		$array_assign = parent::listviewACLHelper();
		$is_owner = false;
		if(!empty($this->parent_name)){
			
			if(!empty($this->parent_name_owner)){
				global $current_user;
				$is_owner = $current_user->id == $this->parent_name_owner;
			}
		}
			if(!ACLController::moduleSupportsACL($this->parent_type) || ACLController::checkAccess($this->parent_type, 'view', $is_owner)){
				$array_assign['PARENT'] = 'a';
			}else{
				$array_assign['PARENT'] = 'span';
			}
		$is_owner = false;
		if(!empty($this->contact_name)){
			
			if(!empty($this->contact_name_owner)){
				global $current_user;
				$is_owner = $current_user->id == $this->contact_name_owner;
			}
		}
			if( ACLController::checkAccess('Contacts', 'view', $is_owner)){
				$array_assign['CONTACT'] = 'a';
			}else{
				$array_assign['CONTACT'] = 'span';
			}
		
		return $array_assign;
	}
}
?>
