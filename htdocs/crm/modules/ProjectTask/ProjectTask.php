<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Data access layer for the project_task table
 *
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
 */

// $Id: ProjectTask.php,v 1.55 2006/06/06 17:58:32 majed Exp $

require_once('data/SugarBean.php');

require_once('include/utils.php');


require_once('modules/Calls/Call.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');

class ProjectTask extends SugarBean {
	// database table columns
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;



	var $name;
	var $status;
	var $date_due;
	var $time_due;
	var $date_start;
	var $time_start;
	var $parent_id;
	var $priority;
	var $description;
	var $order_number;
	var $task_number;
	var $depends_on_id;
	var $milestone_flag;
	var $estimated_effort;
	var $actual_effort;
	var $utilization;
	var $percent_complete;
	var $deleted;

	// related information
	var $assigned_user_name;
	var $parent_name;
	var $depends_on_name;
	var $email_id;




	var $table_name = 'project_task';
	var $object_name = 'ProjectTask';
	var $module_dir = 'ProjectTask';


	var $field_name_map;
	var $new_schema = true;

	var $relationship_fields = array(
		'email_id' => 'emails',
	);

	//////////////////////////////////////////////////////////////////
	// METHODS
	//////////////////////////////////////////////////////////////////

	/*
	 *
	 */
	function ProjectTask()
	{
		parent::SugarBean();

		// default value for a clean instantiation
		$this->utilization = 100;

		global $current_user;
		if(empty($current_user))
		{
			$this->assigned_user_id = 1;
			$this->assigned_user_name = 'admin';
		}
		else
		{
			$this->assigned_user_id = $current_user->id;
			$this->assigned_user_name = $current_user->user_name;
		}












	}
	
	function save($check_notify = FALSE){
		parent::save($check_notify);
	}
	
	/**
	 * overriding the base class function to do a join with users table
	 */
	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		$custom_join = $this->custom_fields->getJOIN();

		$query = "SELECT users.user_name assigned_user_name, project.name parent_name, project.assigned_user_id parent_name_owner, project_task.*";

		if($custom_join)
		{
			$query .=  $custom_join['select'];
		}




		$query .= " FROM project_task ";





		
		$query .= "LEFT JOIN users ON project_task.assigned_user_id=users.id ";
		$query .= "LEFT JOIN project ON project_task.parent_id=project.id ";
		



		
		if($custom_join)
		{
			$query .=  $custom_join['join'];
		}

			$where_auto = '1=1';
				if($show_deleted == 0){
                	$where_auto = "$this->table_name.deleted=0 AND project.deleted=0";
				}else if($show_deleted == 1){
                	$where_auto = "$this->table_name.deleted=1";
				}

				if($where != '')
						  $query .= "where ($where) AND ".$where_auto;
				else
						  $query .= "where ".$where_auto;

				if(!empty($order_by))
						  $query .= " ORDER BY $order_by";
		//die($query);
		return $query;
	}

	/*
	 *
	 */
   function fill_in_additional_detail_fields()
   {
      $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);



		$this->depends_on_name = $this->_get_depends_on_name($this->depends_on_id);
		if(empty($this->depends_on_name))
		{
			$this->depends_on_id = '';
		}
		$this->parent_name = $this->_get_parent_name($this->parent_id);
		if(empty($this->parent_name))
		{
			$this->parent_id = '';
		}
   }

	/*
	 *
	 */
   function fill_in_additional_list_fields()
   {
      $this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
      $this->parent_name = $this->_get_parent_name($this->parent_id);
   }

	/*
	 *
	 */
	function get_summary_text()
	{
		return $this->name;
	}

	/*
	 *
	 */
	function _get_depends_on_name($depends_on_id)
	{
		$return_value = '';

		$query  = "SELECT name, assigned_user_id FROM {$this->table_name} WHERE id='{$depends_on_id}'";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");
		$row = $this->db->fetchByAssoc($result);
		if($row != null)
		{
			$this->depends_on_name_owner = $row['assigned_user_id'];
			$this->depends_on_name_mod = 'ProjectTask';
			$return_value = $row['name'];
		}

		return $return_value;
	}

	/*
	 *
	 */
	function _get_parent_name($parent_id)
	{
		$return_value = '';

		$query  = "SELECT name, assigned_user_id FROM project WHERE id='{$parent_id}'";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");
		$row = $this->db->fetchByAssoc($result);
		if($row != null)
		{
			$this->parent_name_owner = $row['assigned_user_id'];
			$this->parent_name_mod = 'Project';
			$return_value = $row['name'];
		}

		return $return_value;
	}

	/*
	 *
	 */
	function build_generic_where_clause ($the_query_string)
	{
		$where_clauses = array();
		$the_query_string = PearDatabase::quote(from_html($the_query_string));
		array_push($where_clauses, "project_task.name like '$the_query_string%'");

		$the_where = "";
		foreach($where_clauses as $clause)
		{
			if($the_where != "") $the_where .= " or ";
			$the_where .= $clause;
		}

		return $the_where;
	}

	function get_list_view_data(){
		global $action, $currentModule, $focus, $current_module_strings, $app_list_strings, $image_path, $timedate;
		$today = $timedate->handle_offset(date("Y-m-d H:i:s", time()), $timedate->dbDayFormat, true);
		$task_fields =$this->get_list_view_array();
		$date_due = $timedate->to_db_date($task_fields['DATE_DUE'],false);
		if (isset($this->parent_type)) 
			$task_fields['PARENT_MODULE'] = $this->parent_type;
		if ($this->status != "Completed" && $this->status != "Deferred" ) {
			$task_fields['SET_COMPLETE'] = "<a href='index.php?return_module=$currentModule&return_action=$action&return_id=" . ((!empty($focus->id)) ? $focus->id : "") . "&module=ProjectTask&action=EditView&record={$this->id}&status=Completed'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
		}
		if( $date_due	< $today){
			$task_fields['DATE_DUE']= "<font class='overdueTask'>".$task_fields['DATE_DUE']."</font>";
		}else if( $date_due	== $today ){
			$task_fields['DATE_DUE'] = "<font class='todaysTask'>".$task_fields['DATE_DUE']."</font>";
		}else{
			$task_fields['DATE_DUE'] = "<font class='futureTask'>".$task_fields['DATE_DUE']."</font>";
		}

		$task_fields['CONTACT_NAME']= return_name($task_fields,"FIRST_NAME","LAST_NAME");
		$task_fields['TITLE'] = '';
		if (!empty($task_fields['CONTACT_NAME'])) {
			$task_fields['TITLE'] .= $current_module_strings['LBL_LIST_CONTACT'].": ".$task_fields['CONTACT_NAME'];
		}

		return $task_fields;
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
			if(ACLController::checkAccess('Project', 'view', $is_owner)){
				$array_assign['PARENT'] = 'a';
			}else{
				$array_assign['PARENT'] = 'span';
			}
		$is_owner = false;
		if(!empty($this->depends_on_name)){
			
			if(!empty($this->depends_on_name_owner)){
				global $current_user;
				$is_owner = $current_user->id == $this->depends_on_name_owner;
			}
		}
			if( ACLController::checkAccess('ProjectTask', 'view', $is_owner)){
				$array_assign['PARENT_TASK'] = 'a';
			}else{
				$array_assign['PARENT_TASK'] = 'span';
			}
		
		return $array_assign;
	}
	
}
?>
