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
 * $Id: ImportContact.php,v 1.32 2006/06/06 17:58:21 majed Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/


require_once('modules/Contacts/Contact.php');
require_once('modules/Import/UsersLastImport.php');

global $app_list_strings;

// Contact is used to store customer information.
class ImportContact extends Contact {
	// these are fields that may be set on import
	// but are to be processed and incorporated
	// into fields of the parent class
	var $db;
	var $full_name;
	var $primary_address_street_2;
	var $primary_address_street_3;
	var $alt_address_street_2;
	var $alt_address_street_3;

       // This is the list of the functions to run when importing
        var $special_functions =  array(
		"get_names_from_full_name"
		,"add_created_modified_dates"
		,"add_create_assigned_user_name"
		,"add_create_account"
		,"add_salutation"
		,"add_lead_source"
		,"add_birthdate"
		,"add_do_not_call"
		,"add_email_opt_out"
		,"add_primary_address_streets"
		,"add_alt_address_streets"
		);


	function add_salutation()
	{
                global $app_list_strings;

		if ( isset($this->salutation) &&
			! isset( $app_list_strings['salutation_dom'][ $this->salutation ]) )
		{
			$this->salutation = '';
		}
	}

	function add_lead_source()
	{
                global $app_list_strings;

		if ( isset($this->lead_source) &&
			! isset( $app_list_strings['lead_source_dom'][ $this->lead_source ]) )
		{
			$this->lead_source = '';
		}

	}


	function add_birthdate()
	{
		if ( isset($this->birthdate))
		{
			if (! preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/',$this->birthdate))
			{
				$this->birthdate = '';
			}
		}

	}

	function add_do_not_call()
	{
		if ( isset($this->do_not_call) && $this->do_not_call != 'on')
		{
			$this->do_not_call = '';
		}

	}

	function add_email_opt_out()
	{
		if ( isset($this->email_opt_out) && $this->email_opt_out != 'on')
		{
			$this->email_opt_out = '';
		}
	}

	function add_primary_address_streets()
	{
		if ( isset($this->primary_address_street_2))
		{
			$this->primary_address_street .= " ". $this->primary_address_street_2;
		}

		if ( isset($this->primary_address_street_3))
		{
			$this->primary_address_street .= " ". $this->primary_address_street_3;
		}
	}

	function add_alt_address_streets()
	{
		if ( isset($this->alt_address_street_2))
		{
			$this->alt_address_street .= " ". $this->alt_address_street_2;
		}

		if ( isset($this->alt_address_street_3))
		{
			$this->alt_address_street .= " ". $this->alt_address_street_3;
		}

	}

        function get_names_from_full_name()
        {
		if ( ! isset($this->full_name))
		{
			return;
		}
                $arr = array();

                $name_arr = preg_split('/\s+/',$this->full_name);

                if ( count($name_arr) == 1)
                {
                        $this->last_name = $this->full_name;
                }
		else
		{
                	$this->first_name = array_shift($name_arr);

                	$this->last_name = join(' ',$name_arr);
		}

        }

        function add_create_account()
        {
		// global is defined in UsersLastImport.php
		global $imported_ids;
                global $current_user;

		if ( (! isset($this->account_name) || $this->account_name == '') &&
			(! isset($this->account_id) || $this->account_id == '') )
		{
			return;
		}

                $arr = array();

		// check if it already exists
                $focus = new Account();

		$query = '';

		// if user is defining the account id to be associated with this contact..
		if ( isset($this->account_id) && $this->account_id != '')
		{
					$this->account_id = convert_id($this->account_id);
                	$query = "select * from {$focus->table_name} WHERE id='". PearDatabase::quote($this->account_id)."'";
		}
		// else user is defining the account name to be associated with this contact..
		else
		{
                	$query = "select * from {$focus->table_name} WHERE name='". PearDatabase::quote($this->account_name)."'";
		}

                $GLOBALS['log']->info($query);

                $result = $this->db->query($query)
                       or sugar_die("Error selecting sugarbean: ");

                $row = $this->db->fetchByAssoc($result, -1, false);

		// we found a row with that id
                if (isset($row['id']) && $row['id'] != -1)
                {
                        // if it exists but was deleted, just remove it entirely
                        if ( isset($row['deleted']) && $row['deleted'] == 1)
                        {
                                $query2 = "delete from {$focus->table_name} WHERE id='". PearDatabase::quote($row['id'])."'";

                                $GLOBALS['log']->info($query2);

                                $result2 = $this->db->query($query2)
                                        or sugar_die("Error deleting existing sugarbean: ");

                        }
			// else just use this id to link the contact to the account
                        else
                        {
                                $focus->id = $row['id'];
                        }
                }

		// if we didnt find the account, so create it
                if (! isset($focus->id) || $focus->id == '')
                {
                        $focus->name = $this->account_name;
			if ( isset($this->assigned_user_id))
			{
                        	$focus->assigned_user_id = $this->assigned_user_id;
                        	$focus->modified_user_id = $this->assigned_user_id;
			}
			else
			{
                        	$focus->assigned_user_id = $current_user->id;
                        	$focus->modified_user_id = $current_user->id;
			}

			if ( isset($this->modified_date))
			{
                        	$focus->modified_date = $this->modified_date;
			}
			// if we are providing the account id:
			if ( isset($this->account_id)  &&
                                $this->account_id != '')
                        {
				$focus->new_with_id = true;
                                $focus->id = $this->account_id;
                        }

                        $focus->save();
			// avoid duplicate mappings:
			if (! isset( $imported_ids[$focus->id]) )
			{
				// save the new account as a users_last_import
                		$last_import = new UsersLastImport();
                		$last_import->assigned_user_id = $current_user->id;
                		$last_import->bean_type = "Accounts";
                		$last_import->bean_id = $focus->id;
                		$last_import->save();
				$imported_ids[$focus->id] = 1;
			}
                }

		// now just link the account
                $this->account_id = $focus->id;

        }

        function add_create_assigned_user_name()
        {
		// global is defined in UsersLastImport.php
		global $imported_ids;
                global $current_user;

		if ( empty($this->assigned_user_name))
		{
			return;
		}

		$user_name = $this->assigned_user_name;
		
		// check if it already exists
        $focus = new User();

       	$query = "select * from {$focus->table_name} WHERE user_name='{$user_name}'";

        $GLOBALS['log']->info($query);

        $result = $this->db->query($query)
               or sugar_die("Error selecting sugarbean: ");

        $row = $this->db->fetchByAssoc($result, -1, false);

		// we found a row with that id
                if (isset($row['id']) && $row['id'] != -1)
                {
                        // if it exists but was deleted, just remove it entirely
                        if ( isset($row['deleted']) && $row['deleted'] == 1)
                        {
                                $query2 = "delete from {$focus->table_name} WHERE id='". PearDatabase::quote($row['id'])."'";

                                $GLOBALS['log']->info($query2);

                                $result2 = $this->db->query($query2)
                                        or sugar_die("Error deleting existing sugarbean: ");

                        }

			// else just use this id to link the user to the contact
                        else
                        {
                                $focus->id = $row['id'];
                        }
                }

		

		// now just link the account
                $this->assigned_user_id = $focus->id;
                $this->modified_user_id = $focus->id;

        }

	//removed importable_fields, this array is now generated in the import wizard. and The array is based
	//on the meta defined in the vardef file for the contacts module.

	//module prefix used by ImportSteplast when calling ListView.php
	var $list_view_prefix = 'CONTACT';

	//columns to be displayed in listview for displaying user's last import in ImportSteplast.php
	var $list_fields = Array(
	  		'id', 
			'first_name', 
			'last_name', 
			'account_name', 
			'account_id', 
			'title', 
			'email1', 
			'phone_work', 
			'assigned_user_name', 
			'assigned_user_id');

	//this list defines what beans get populated during an import of contacts
	var $related_modules = array("Contacts","Accounts",); 
		
	function ImportContact() {
		parent::Contact();
	}

	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		global $current_user;
		$query = '';

			$query = "SELECT distinct
				accounts.name as account_name,
				accounts.id as account_id,
				contacts.id,
				contacts.assigned_user_id,
				contacts.first_name,
				contacts.last_name,
				contacts.phone_work,
				contacts.title,
				contacts.email1,
                		users.user_name as assigned_user_name ";



				$query.=" FROM users_last_import,contacts
                                LEFT JOIN users
                                ON contacts.assigned_user_id=users.id
				LEFT JOIN accounts_contacts
				ON contacts.id=accounts_contacts.contact_id
				LEFT JOIN accounts
				ON accounts_contacts.account_id=accounts.id";



				$query.=" WHERE
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Contacts'
				AND users_last_import.bean_id=contacts.id
				AND users_last_import.deleted=0
				AND contacts.deleted=0
			";
		if(! empty($order_by))
		{
			$query .= " ORDER BY $order_by";
		}

		return $query;
	}
}



?>
