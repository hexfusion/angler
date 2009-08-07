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
 * $Id: ImportLead.php,v 1.22 2006/06/06 17:58:21 majed Exp $
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 ********************************************************************************/



require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Calls/Call.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('modules/Leads/Lead.php');

global $app_list_strings;

class ImportLead extends Lead {
	 var $db;

	// these are fields that may be set on import
	// but are to be processed and incorporated
	// into fields of the parent class


	// This is the list of fields that are required.
	var $required_fields =  array("last_name"=>1);
	
	// This is the list of the functions to run when importing
	var $special_functions =  array(
		"get_names_from_full_name"
			,"add_create_assigned_user_name"
                ,"add_salutation"
                ,"add_lead_status"
                ,"add_lead_source"
                ,"add_do_not_call"
                ,"add_email_opt_out"
		,"add_primary_address_streets"
		,"add_alt_address_streets"

	 );


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
		//on the meta defined in the vardef file for the leads module.

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

        function add_lead_status()
        {
                global $app_list_strings;
                if ( isset($this->status) &&
                        ! isset( $app_list_strings['lead_status_dom'][ $this->status ]) )
                {
                        $this->status = '';
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


	//module prefix used by ImportSteplast when calling ListView.php
	var $list_view_prefix = 'LEAD';

	//columns to be displayed in listview for displaying user's last import in ImportSteplast.php
	var $list_fields = Array(
					  'id'
					, 'first_name'
					, 'last_name'
					, 'account_name'
					, 'title'
					, 'email1'
					, 'phone_work'
					, 'assigned_user_name'
					, 'assigned_user_id'
					, 'lead_source'
					, 'lead_source_description'
					, 'refered_by'
					, 'opportunity_name'
					, 'opportunity_amount'
					, 'date_entered'
					, 'status'
					);

	//this list defines what beans get populated during an import of this leads
	var $related_modules = array("Leads",); 
		

	function ImportLead() {
		parent::Lead();
	}

	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		global $current_user;
		$query = '';


             $query = "SELECT
                                leads.account_name,
                                leads.account_id,
                                leads.status,
                                users.user_name as assigned_user_name,
                                leads.id,
                                leads.first_name,
                                leads.last_name,
                                leads.phone_work,
                                leads.lead_source,
                                leads.title,
                                leads.email1,
                                leads.date_entered
                                FROM users_last_import,leads
                                LEFT JOIN users
                                ON leads.assigned_user_id=users.id
                        	WHERE
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Leads'
				AND users_last_import.bean_id=leads.id
				AND users_last_import.deleted=0
                                AND leads.deleted=0 ";
		if(! empty($order_by))
		{
			$query .= " ORDER BY $order_by";
		}

		return $query;


	} 
}



?>
