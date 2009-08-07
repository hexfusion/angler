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
 * $Id: Note.php,v 1.91 2006/06/27 22:50:23 eddy Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/




require_once('data/SugarBean.php');
require_once('include/upload_file.php');

// Note is used to store customer information.
class Note extends SugarBean {
	var $field_name_map;
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $description;
	var $name;
	var $filename;
	// handle to an upload_file object
	// used in emails
	var $file;
	var $parent_type;
	var $parent_id;
	var $contact_id;
	var $portal_flag;




	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $file_mime_type;
	var $module_dir = "Notes";
	var $default_note_name_dom = array('Meeting notes', 'Reminder');
	var $table_name = "notes";
	var $new_schema = true;
	var $object_name = "Note";

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('contact_name', 'contact_phone', 'contact_email', 'parent_name','first_name','last_name');

	function Note() {
		parent::SugarBean();



	}

	function get_summary_text() {
		return "$this->name";
	}

	function create_list_query($order_by, $where, $show_deleted=0) {
		$contact_required = ereg("contacts\.first_name", $where);
		$contact_required = 1;
		$query = "SELECT ";
		$custom_join = $this->custom_fields->getJOIN();

		if($contact_required) {
    		$query .= "$this->table_name.*";



			if ( ( $this->db->dbType == 'mysql' ) or ( $this->db->dbType == 'oci8' ) )
			{
				$query .= ", concat(concat(contacts.first_name , ' '), contacts.last_name) AS contact_name";
			}
			if($this->db->dbType == 'mssql')
			{
				$query .= ", contacts.first_name + ' ' + contacts.last_name AS contact_name";
			}
			$query .= ", contacts.assigned_user_id contact_name_owner";

			if($custom_join) {
				$query .= $custom_join['select'];
			}

			$query.= " FROM notes ";






			
			$query .= " LEFT JOIN contacts ON notes.contact_id=contacts.id ";
			
			if($custom_join) {
   				$query .= $custom_join['join'];
 			}
 			$where_auto = '1=1';
			if($show_deleted == 0) {
				$where_auto = " (contacts.deleted IS NULL OR contacts.deleted=0) AND notes.deleted=0";
			} elseif($show_deleted == 1) {
				$where_auto = " notes.deleted=0 ";
			}
		} else {
			$query .= ' id, name, parent_type, parent_id, contact_id, filename, date_modified ';
			if($custom_join) {
   				$query .= $custom_join['select'];
 			}








 			if($custom_join) {
   				$query .= $custom_join['join'];
 			}

 			$where_auto = '1=1';
 			if($show_deleted == 0) {
				$where_auto = "deleted=0";
 			} elseif($show_deleted == 1) {
 				$where_auto = "deleted=1"; 	
 			}
		}

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;
		if($order_by != ""){
				 $query .=  " ORDER BY ". $this->process_order_by($order_by, null);
		
		}else
		{
			$query .= " ORDER BY notes.name";
		}
		return $query;
	}




	function create_export_query(&$order_by, &$where) {
		$custom_join = $this->custom_fields->getJOIN();
		$query = "SELECT notes.*, contacts.first_name, contacts.last_name ";

		if($custom_join) {
   			$query .= $custom_join['select'];
 		}
    	
    	$query .= " FROM notes ";




		
		$query .= "	LEFT JOIN contacts ON notes.contact_id=contacts.id ";
	
		if($custom_join) {
			$query .= $custom_join['join'];
		}
        
		$where_auto = " notes.deleted=0 AND (contacts.deleted IS NULL OR contacts.deleted=0)";
					
        if($where != "")
			$query .= "where $where AND ".$where_auto;
        else
			$query .= "where ".$where_auto;

        if($order_by != "")
			$query .=  " ORDER BY ". $this->process_order_by($order_by, null);
        else
			$query .= " ORDER BY notes.name";

		return $query;
	}

	function fill_in_additional_list_fields() {
		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_detail_fields() {
		//TODO:  Seems odd we need to clear out these values so that list views don't show the previous rows value if current value is blank
		$this->contact_name = '';
		$this->contact_phone = '';
		$this->contact_email = '';
		$this->parent_name = '';
		$this->contact_name_owner = '';
		$this->contact_name_mod = '';

		if(isset($this->contact_id) && $this->contact_id != '') {
			require_once("modules/Contacts/Contact.php");
			$contact = new Contact();
			$query = "SELECT first_name, last_name, phone_work, email1, assigned_user_id contact_name_owner from $contact->table_name where id = '$this->contact_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null) {
				echo $this->contact_name_owner;
				$this->contact_name_owner = $row['contact_name_owner'];
				$this->contact_name_mod = 'Contacts';
				$this->contact_name = return_name($row, 'first_name', 'last_name');
				
				if($row['phone_work'] != '') 
					$this->contact_phone = $row['phone_work'];
				else
					$this->contact_phone = '';
				if($row['email1'] != '') 
					$this->contact_email = $row['email1'];
				else 
					$this->contact_email = '';
			}

		}




		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_parent_fields() {
		global $app_strings;
		$this->parent_name = '';
		if($this->parent_type == "Opportunities") {
			require_once("modules/Opportunities/Opportunity.php");
			$parent = new Opportunity();
			$query = "SELECT name , assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			if($row && !empty($row['parent_name_owner'])) {
				$this->parent_name_owner = $row['parent_name_owner'];
				$this->parent_name_mod = $this->parent_type;
			}
			if($row != null) {
				if($row['name'] != '') stripslashes($this->parent_name = $row['name']);
			}
		} elseif($this->parent_type == "Emails") {
			require_once("modules/Emails/Email.php");
			$parent = new Email();
			$query = "SELECT name , assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			if($row && !empty($row['parent_name_owner'])) {
				$this->parent_name_owner = $row['parent_name_owner'];
				$this->parent_name_mod = $this->parent_type;
			}
			if($row != null) {
				if($row['name'] != '') 
					stripslashes($this->parent_name = $row['name']);
			}
		} elseif($this->parent_type == "Cases") {
			require_once("modules/Cases/Case.php");
			$parent = new aCase();
			$query = "SELECT name , assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			if($row && !empty($row['parent_name_owner'])) {
				$this->parent_name_owner = $row['parent_name_owner'];
				$this->parent_name_mod = $this->parent_type;
			}
			if($row != null) {
				if($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		} elseif($this->parent_type == "Bugs") {
			require_once("modules/Bugs/Bug.php");
			$parent = new Bug();
			$query = "SELECT name , assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			if($row && !empty($row['parent_name_owner'])) {
				$this->parent_name_owner = $row['parent_name_owner'];
				$this->parent_name_mod = $this->parent_type;
			}
			if($row != null) {
				if($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		} elseif($this->parent_type == "ProjectTask") {
			require_once("modules/ProjectTask/ProjectTask.php");
			$parent = new ProjectTask();
			$query = "SELECT name , assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			if($row && !empty($row['parent_name_owner'])) {
				$this->parent_name_owner = $row['parent_name_owner'];
				$this->parent_name_mod = $this->parent_type;
			}
			if($row != null) {
				if($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		} elseif($this->parent_type == "Project") {
			require_once("modules/Project/Project.php");
			$parent = new Project();
			$query = "SELECT name , assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			if($row && !empty($row['parent_name_owner'])) {
				$this->parent_name_owner = $row['parent_name_owner'];
				$this->parent_name_mod = $this->parent_type;
			}
			if($row != null) {
				if($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		} elseif($this->parent_type == "Accounts") {
			require_once("modules/Accounts/Account.php");
			$parent = new Account();
			$query = "SELECT name , assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			if($row && !empty($row['parent_name_owner'])) {
				$this->parent_name_owner = $row['parent_name_owner'];
				$this->parent_name_mod = $this->parent_type;
			}
			if($row != null) {
				if($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		} elseif($this->parent_type == "Leads") {
			require_once("modules/Leads/Lead.php");
			$parent = new Lead();
			$query = "SELECT first_name, last_name , assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
			$result = $this->db->query($query,true, " Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);
			if($row && !empty($row['parent_name_owner'])) {
				$this->parent_name_owner = $row['parent_name_owner'];
				$this->parent_name_mod = $this->parent_type;
			}
			if($row != null) {
				$this->parent_name = '';
                if($row['first_name'] != '') 
                	$this->parent_name .= stripslashes($row['first_name']). ' ';
				if($row['last_name'] != '') 
					$this->parent_name .= stripslashes($row['last_name']);
			}
		} elseif($this->parent_type == "Issues") {
        	require_once("modules/Issues/Issue.php");
            $parent = new Issue();

            $query = "SELECT name , assigned_user_id parent_name_owner from $parent->table_name where id = '$this->parent_id'";
			$result = $this->db->query($query, TRUE, "Error filling in additional detail fields: ");
			$row = $this->db->fetchByAssoc($result);
			if($row && !empty($row['parent_name_owner'])) {
				$this->parent_name_owner = $row['parent_name_owner'];
				$this->parent_name_mod = $this->parent_type;
			}
			if(!is_null($row)) {
 				$this->parent_name = '';
				if(!empty($row['name'])) 
					$this->parent_name .= stripslashes($row['name']);
			}
		}



































































		elseif ($this->parent_type == "Contacts") {
			require_once("modules/Contacts/Contact.php");
			$parent = new Contact();
			$query = "SELECT first_name, last_name, assigned_user_id parent_name_owner  from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, $app_strings['ERR_CREATING_FIELDS']);


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);

			if ($row && !empty($row['parent_name_owner'])){
			$this->parent_name_owner = $row['parent_name_owner'];
			$this->parent_name_mod = $this->parent_type;
			}
			if($row != null)
			{
				$this->parent_name = '';
				if ($row['first_name'] != '') $this->parent_name .= stripslashes($row['first_name']). ' ';
				if ($row['last_name'] != '') $this->parent_name .= stripslashes($row['last_name']);
			}
		}
	}
	
	function get_list_view_data() {
		$note_fields = $this->get_list_view_array();
		global $app_list_strings, $focus, $action, $currentModule,$mod_strings;
		$note_fields["DATE_MODIFIED"] = substr($note_fields["DATE_MODIFIED"], 0 , 10);
		if(isset($this->parent_type)) {
			$note_fields['PARENT_MODULE'] = $this->parent_type;
		}

		if(!isset($this->filename) || $this->filename != '')
                {
                        $note_fields['FILENAME'] = $this->filename;
                        $note_fields['FILE_URL'] = UploadFile::get_url($this->filename,$this->id);
                }

		global $current_language;
		$mod_strings = return_module_language($current_language, 'Notes');
		$note_fields['STATUS']=$mod_strings['LBL_NOTE_STATUS'];


		return $note_fields;
	}
	
	function listviewACLHelper() {
		$array_assign = parent::listviewACLHelper();
		$is_owner = false;
		if(!empty($this->parent_name)) {
			if(!empty($this->parent_name_owner)) {
				global $current_user;
				$is_owner = $current_user->id == $this->parent_name_owner;
			}
		}
			
		if(!ACLController::moduleSupportsACL($this->parent_type) || ACLController::checkAccess($this->parent_type, 'view', $is_owner)) {
			$array_assign['PARENT'] = 'a';
		} else {
			$array_assign['PARENT'] = 'span';
		}
		
		$is_owner = false;
		if(!empty($this->contact_name)) {
			if(!empty($this->contact_name_owner)) {
				global $current_user;
				$is_owner = $current_user->id == $this->contact_name_owner;
			}
		}
			
		if( ACLController::checkAccess('Contacts', 'view', $is_owner)) {
			$array_assign['CONTACT'] = 'a';
		} else {
			$array_assign['CONTACT'] = 'span';
		}
		
		return $array_assign;
	}
	
	function bean_implements($interface) {
		switch($interface) {
			case 'ACL':return true;
		}
		return false;
	}

}
?>
