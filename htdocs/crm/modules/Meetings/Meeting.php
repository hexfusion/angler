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
 * $Id: Meeting.php,v 1.174 2006/08/09 19:29:14 jenny Exp $
 * Description:	 TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/




require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Users/User.php');
require_once('modules/Calendar/DateTime.php');

// Meeting is used to store customer information.
class Meeting extends SugarBean {
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;



	var $description;
	var $name;
	var $location;
	var $status;
	var $date_start;
	var $time_start;
	var $date_end;
	var $duration_hours;
	var $duration_minutes;
	var $parent_type;
	var $parent_id;
	var $field_name_map;
	var $contact_id;
	var $user_id;
	var $reminder_time;
	var $required;
	var $accept_status;
	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $account_id;
	var $opportunity_id;
	var $case_id;
	var $assigned_user_name;
    var $outlook_id;
    



	var $update_vcal = true;
	var $contacts_arr;
	var $users_arr;
	  // when assoc w/ a user/contact:
	var $default_meeting_name_values = array('Follow-up on proposal', 'Initial discussion', 'Review needs', 'Discuss pricing', 'Demo', 'Introduce all players', );
	var $minutes_values = array('0'=>'00','15'=>'15','30'=>'30','45'=>'45');
	var $table_name = "meetings";
	var $rel_users_table = "meetings_users";
	var $rel_contacts_table = "meetings_contacts";
	var $module_dir = "Meetings";
	var $object_name = "Meeting";

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = array('assigned_user_name', 'assigned_user_id', 'contact_id', 'user_id', 'contact_name', 'accept_status');

	var $relationship_fields = array('account_id'=>'account','opportunity_id'=>'opportunity','case_id'=>'case',
									 'assigned_user_id'=>'users','contact_id'=>'contacts', 'user_id'=>'users');

	// so you can run get_users() twice and run query only once
	var $cached_get_users = null;

	function Meeting() {
		;
		parent::SugarBean();
		$this->setupCustomFields('Meetings');
		foreach ($this->field_defs as $field)
		{
			$this->field_name_map[$field['name']] = $field;
		}



	}

	var $new_schema = true;

	// save date_end by calculating user input
	// this is for calendar
	function save($check_notify = FALSE)
	{
		global $timedate;

		if ( isset($this->date_start)
			&& isset($this->time_start)
			&& isset($this->duration_hours)
			&& isset($this->duration_minutes)
		)
		{
			$date_time_start = DateTime::get_time_start($timedate->to_db_date($this->date_start,false),$this->time_start.":00");
			$date_time_end = DateTime::get_time_end($date_time_start,$this->duration_hours,$this->duration_minutes);
			$this->date_end = $timedate->to_display_date("{$date_time_end->year}-{$date_time_end->month}-{$date_time_end->day}");

		}

	if ( ! empty($_REQUEST['send_invites']) && $_REQUEST['send_invites'] == '1')
	{
	 $check_notify = true;
	}
	else
	{
	 $check_notify = false;
	}
		parent::save($check_notify);


		global $current_user;
		require_once('modules/vCals/vCal.php');
		if ( $this->update_vcal )
		{
			vCal::cache_sugar_vcal($current_user);
		}
	}

	// this is for calendar
	function mark_deleted($id)
	{
		parent::mark_deleted($id);
		global $current_user;
		require_once('modules/vCals/vCal.php');
		if ( $this->update_vcal )
		{
			vCal::cache_sugar_vcal($current_user);
		}
		}

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		$custom_join = $this->custom_fields->getJOIN();

				$query = "SELECT ";

		$query .= "
				$this->table_name.*,";

								if ( preg_match("/meetings_users\.user_id/",$where))
								{
										$query .= "meetings_users.required,
												meetings_users.accept_status,";


								}


			$query .= "
				users.user_name as assigned_user_name";




			// added to generate a GMT metric to compare against a locale's timezone
			if ( ( $this->db->dbType == 'mysql' ) or ( $this->db->dbType == 'oci8' ) )
			{
				$query .= ", CONCAT( meetings.date_start, CONCAT(' ', meetings.time_start) ) AS datetime ";
			}
			if ( $this->db->dbType == 'mssql' )   
			{
				$query .= ", meetings.date_start + ' ' +  meetings.time_start AS datetime ";				
			}

			if($custom_join){
				$query .= $custom_join['select'];
			}
			$query .= " FROM meetings ";





		$query .= "
";



								if ( preg_match("/meetings_users\.user_id/",$where))
								{
				$query .= "		 LEFT JOIN meetings_users
								ON meetings_users.meeting_id=meetings.id and meetings_users.deleted=0 ";
				}
								$query .= "
								LEFT JOIN users
								ON meetings.assigned_user_id=users.id ";
								if($custom_join){
						$query .= $custom_join['join'];
				}

								if ( preg_match("/contacts/",$where))
								{
									$query .= "LEFT JOIN meetings_contacts
	                    					   ON meetings.id=meetings_contacts.meeting_id
	                    					   LEFT JOIN contacts
	                    					   ON meetings_contacts.contact_id=contacts.id ";
								}
		$where_auto = '1=1';
		if($show_deleted == 0){
			$where_auto = " meetings.deleted=0 ";
		}else if($show_deleted == 1){
			$where_auto = " meetings.deleted=1 ";
		}
		if($where != "")
			$query .= "WHERE $where AND ".$where_auto;
		else
			$query .= "WHERE ".$where_auto;

		if($order_by != ""){
			$query .=  " ORDER BY ". $this->process_order_by($order_by, null);
		}else{
			$query .= " ORDER BY meetings.name";
		}
		return $query;

	}

		function create_export_query(&$order_by, &$where)
		{
				$contact_required = ereg("contacts", $where);
				$custom_join = $this->custom_fields->getJOIN();
				if($contact_required)
				{
						$query = "SELECT meetings.*, contacts.first_name, contacts.last_name, contacts.assigned_user_id contact_name_owner ";



						if($custom_join){
							$query .= $custom_join['select'];
						}
						$query .= " FROM contacts, meetings, meetings_contacts ";
						$where_auto = "meetings_contacts.contact_id = contacts.id AND meetings_contacts.meeting_id = meetings.id AND meetings.deleted=0 AND contacts.deleted=0";
				}
				else
				{
						$query = 'SELECT meetings.*';



						if($custom_join){
							$query .= $custom_join['select'];
						}
						$query .= ' FROM meetings ';
						$where_auto = "meetings.deleted=0";
				}





				if($custom_join){
					$query .= $custom_join['join'];
				}

				if($where != "")
						$query .= "where $where AND ".$where_auto;
				else
						$query .= "where ".$where_auto;

				if($order_by != "")
						$query .= " ORDER BY $order_by";
				else 
                { 
                    $alternate_order_by =  $this->process_order_by($order_by, null);  
                    if ($alternate_order_by != "")
    					$query .=  " ORDER BY ". $alternate_order_by;
                }
				return $query;

		}



	function fill_in_additional_list_fields()
	{
//		$this->fill_in_additional_detail_fields();
		//$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$query	= "SELECT contacts.first_name, contacts.last_name, contacts.phone_work, contacts.email1, contacts.id , contacts.assigned_user_id contact_name_owner FROM contacts, meetings_contacts ";
		$query .= "WHERE meetings_contacts.contact_id=contacts.id AND meetings_contacts.meeting_id='$this->id' AND meetings_contacts.deleted=0 AND contacts.deleted=0";
		$result =$this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		$GLOBALS['log']->info($row);

		if($row != null)
		{
			$this->contact_name = return_name($row, 'first_name', 'last_name');
			$this->contact_phone = $row['phone_work'];
			$this->contact_id = $row['id'];
			$this->contact_email = $row['email1'];
			$this->contact_name_owner = $row['contact_name_owner'];
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

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);

		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_parent_fields()
	{
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

		if(($this->parent_type == "Leads" || $this->parent_type == "Contacts") and $row != null)
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
		$meeting_fields = $this->get_list_view_array();
		global $app_list_strings, $focus, $action, $currentModule, $image_path;
		if (isset($this->parent_type))
			$meeting_fields['PARENT_MODULE'] = $this->parent_type;
		if ($this->status == "Planned") {
			//cn: added this if() to deal with sequential Closes in Meetings.  this is a hack to a hack (formbase.php->handleRedirect)
			if(empty($action)) { $action = "index"; }
			$meeting_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=$this->id&action=EditView&status=Held&module=Meetings&record=$this->id&status=Held'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
		}
		global $timedate;
		$today = gmdate('Y-m-d H:i:s', time());
		$nextday = gmdate('Y-m-d', time() + 3600*24);
		$mergeTime = $timedate->merge_date_time($meeting_fields['DATE_START'], $meeting_fields['TIME_START']);
		$date_db = $timedate->to_db($mergeTime);
		if( $date_db	< $today	){
			$meeting_fields['DATE_START']= "<font class='overdueTask'>".$meeting_fields['DATE_START']."</font>";
		}else if( $date_db	< $nextday ){
			$meeting_fields['DATE_START'] = "<font class='todaysTask'>".$meeting_fields['DATE_START']."</font>";
		}else{
			$meeting_fields['DATE_START'] = "<font class='futureTask'>".$meeting_fields['DATE_START']."</font>";
		}
		$meeting_fields['CONTACT_ID'] = empty($this->contact_id) ? '' : $this->contact_id;

		$meeting_fields['PARENT_NAME'] = $this->parent_name;

		return $meeting_fields;
	}

	function set_notification_body($xtpl, &$meeting) {
		global $sugar_config;
		global $app_list_strings;
		global $current_user;
		global $timedate;

		$prefDate = User::getUserDateTimePreferences($meeting->current_notify_user);

		// BEGIN contributed code -- see bug #6433
		$x = date($prefDate['date']." ".$prefDate['time'], strtotime(($meeting->date_start . " " . $meeting->time_start)));
		// END contributed code

		$xOffset = $timedate->handle_offset($x, $prefDate['date']." ".$prefDate['time'], true, $current_user);

		if ( strtolower(get_class($meeting->current_notify_user)) == 'contact' ) {
			$xtpl->assign("ACCEPT_URL", $sugar_config['site_url'].
						  '/acceptDecline.php?module=Meetings&contact_id='.$meeting->current_notify_user->id.'&record='.$meeting->id);
		} else {
			$xtpl->assign("ACCEPT_URL", $sugar_config['site_url'].
						  '/acceptDecline.php?module=Meetings&user_id='.$meeting->current_notify_user->id.'&record='.$meeting->id);
		}
		$xtpl->assign("MEETING_TO", $meeting->current_notify_user->new_assigned_user_name);
		$xtpl->assign("MEETING_SUBJECT", $meeting->name);
		$xtpl->assign("MEETING_STATUS",(isset($meeting->status)? $app_list_strings['meeting_status_dom'][$meeting->status]:"" ));
		$xtpl->assign("MEETING_STARTDATE", $xOffset." ".$prefDate['userGmt']);
		$xtpl->assign("MEETING_HOURS", $meeting->duration_hours);
		$xtpl->assign("MEETING_MINUTES", $meeting->duration_minutes);
		$xtpl->assign("MEETING_DESCRIPTION", $meeting->description);

		return $xtpl;
  }

	function get_meeting_users() {
		$template = new User();
		// First, get the list of IDs.
		$query = "SELECT meetings_users.required, meetings_users.accept_status, meetings_users.user_id from meetings_users where meetings_users.meeting_id='$this->id' AND meetings_users.deleted=0";
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

  function get_invite_meetings(&$user)
  {
	$template = $this;
	// First, get the list of IDs.
	$query = "SELECT meetings_users.required, meetings_users.accept_status, meetings_users.meeting_id from meetings_users where meetings_users.user_id='$user->id' AND ( meetings_users.accept_status IS NULL OR  meetings_users.accept_status='none') AND meetings_users.deleted=0";
	$GLOBALS['log']->debug("Finding linked records $this->object_name: ".$query);


	$result = $this->db->query($query, true);


	$list = Array();


	while($row = $this->db->fetchByAssoc($result))
	{
	  $record = $template->retrieve($row['meeting_id']);
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
			$relate_values = array('user_id'=>$user->id,'meeting_id'=>$this->id);
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
			$relate_values = array('contact_id'=>$user->id,'meeting_id'=>$this->id);
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
