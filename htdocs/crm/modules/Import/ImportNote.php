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
 * $Id: ImportNote.php,v 1.17 2006/06/29 18:32:13 eddy Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/


require_once('modules/Import/UsersLastImport.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Leads/Lead.php');



require_once('include/modules.php');
require_once('include/utils.php');

global $app_list_strings;

class ImportNote extends Note {
	// these are fields that may be set on import
	// but are to be processed and incorporated
	// into fields of the parent class
	var $db;

       // This is the list of the functions to run when importing
        var $special_functions =  array(
		'add_created_modified_dates',
		'add_contact_id',
        	'add_parent_id',
		'add_subject',
	);

	function add_subject()
	{
		if ( ! empty($this->name) &&  strlen($this->name) > 76 )
		{
		$this->name = substr($this->name,0,76) . "...";
		}
	}

        function add_parent_id()
        {
		global $beanList;
		$parent_beans = array(
			'Accounts',
			'Opportunities',
			'Cases',
			'Leads',




		);

		$parent_name = '';
		$parent_id = '';

		foreach ( $parent_beans as $name)
		{
			$bean = $beanList[$name];
			$id_name = strtolower($bean).'_id';

                	if ( isset($this->$id_name) )
                	{
				$parent_name = $name;
				$parent_id = $this->$id_name;
                        	break;
                	}
		}

                $parent_id = convert_id($parent_id);

               	$focus = new $bean();

               	$query = "select * from {$focus->table_name} WHERE id='". PearDatabase::quote($parent_id)."'";

                $result = $this->db->query($query)
                       or sugar_die("Error selecting sugarbean: ");

                $row = $this->db->fetchByAssoc($result, -1, false);

                if ( empty( $row['id']))
                {
                        return;
                }
		$this->parent_id = $parent_id;
		$this->parent_type = $parent_name;

        }


  	function add_contact_id()
        {
                if ( empty($this->contact_id) )
                {
			return;
                }

		// clean up the id if it has funny chars
		$this->contact_id = convert_id($this->contact_id);

		$focus = new Contact();
		$query = "select * from {$focus->table_name} WHERE id='". PearDatabase::quote($this->contact_id)."'";

		$result = $this->db->query($query)
                       or sugar_die("Error selecting sugarbean: ");

                $row = $this->db->fetchByAssoc($result, -1, false);

		if ( empty( $row['id']))
		{
			$this->contact_id = '';
			return;
		}
		// assign this to the owner of the contact
		// if it hasnt been already
		if ( ! isset($this->assigned_user_id))
		{
			$this->assigned_user_id = $row['assigned_user_id'];
		}
		
        }
		
	//module prefix used by ImportSteplast when calling ListView.php
	var $list_view_prefix = 'NOTE';

	//columns to be displayed in listview for displaying user's last import in ImportSteplast.php
	var $list_fields = Array(
					  'id'
					, 'name'
					, 'description'
					, 'contact_name'
					, 'contact_id'
					);

	//this list defines what beans get populated during an import of notes 
	var $related_modules = array("Notes",); 
		
	function ImportNote() {
		;
		parent::SugarBean();
	}
	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		global $current_user;
		$query = '';

		if ( ( $this->db->dbType == 'mysql' ) or ( $this->db->dbType == 'oci8' ) )  // RPS
		{
               $query = "SELECT notes.*,
					contacts.id as contact_id,
					CONCAT(CONCAT(contacts.first_name, ' ' ), contacts.last_name) as contact_name
                                FROM users_last_import,notes ";
		}

		if ( $this->db->dbType == 'mssql' )   // RPS
		{
               $query = "SELECT notes.*,
					contacts.id as contact_id,
					contacts.first_name +  ' '  + contacts.last_name as contact_name
                                FROM users_last_import,notes ";
		}				
			    $query .= "LEFT JOIN contacts ON 
				contacts.id = notes.contact_id
                        	WHERE
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Notes'
				AND users_last_import.bean_id=notes.id
				AND users_last_import.deleted=0
				AND (contacts.deleted IS NULL OR contacts.deleted=0)
                                AND notes.deleted=0 ";
		if(! empty($order_by))
		{
			$query .= " ORDER BY $order_by";
		}

		return $query;

	
	}
}


?>
