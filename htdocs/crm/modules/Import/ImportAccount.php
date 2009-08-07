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
 * $Id: ImportAccount.php,v 1.27 2006/08/25 21:24:41 eddy Exp $
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
require_once('modules/Accounts/Account.php');

global $app_list_strings;

// Account is used to store account information.
class ImportAccount extends Account {
	 var $db;

	// these are fields that may be set on import
	// but are to be processed and incorporated
	// into fields of the parent class


	// This is the list of fields that are required.
	var $required_fields =  array("name"=>1);
	
	// This is the list of the functions to run when importing
	var $special_functions =  array(
	"add_billing_address_streets"
	,"add_create_assigned_user_name"
	,"add_shipping_address_streets"
	,"fix_website"
	,"add_industry"
	,"add_type"
    ,"add_member_of_name"
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

        function add_member_of_name()
        {
        // global is defined in UsersLastImport.php
        global $imported_ids;
        global $current_user;

        if ( (! isset($this->account_name) || $this->account_name == '') &&
            (! isset($this->parent_id) || $this->parent_id== '') )
        {
            return;
        }

                $arr = array();

        // check if it already exists
                $focus = new Account();

        $query = '';

        // if user is defining the account id to be associated with this contact..
        if ( isset($this->parent_id) && $this->parent_id!= '')
        {
                    $this->parent_id = convert_id($this->parent_id);
                    $query = "select * from {$focus->table_name} WHERE id='". PearDatabase::quote($this->parent_id)."'";
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
            // else just use this id to link the member_of to the account
                        else
                        {
                                $focus->id = $row['id'];
                        }
                }

        // if we didnt find the account, so create it
                if (! isset($focus->id) || $focus->id == '')
                {
                    $focus->name = $this->account_name;
                        if ( isset($this->parent_id))
                        {
                                        $focus->parent_id = $this->parent_id;
                        }
                        else
                        {
                                        $focus->parent_id = $current_user->id;
                        }
            
                        if ( isset($this->modified_date))
                        {
                                        $focus->modified_date = $this->modified_date;
                        }
                        // if we are providing the account id:
                        if ( isset($this->parent_id)  &&
                                            $this->parent_id != '')
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
                $this->parent_id = $focus->id;

        }


    function fix_website()
	{
		if ( isset($this->website) &&
			preg_match("/^http:\/\//",$this->website) )
		{
			$this->website = substr($this->website,7);
		}	
	}

	
	function add_industry()
	{
		global $app_list_strings;
		if ( isset($this->industry) &&
			! isset( $app_list_strings['industry_dom'][$this->industry]))
		{
			unset($this->industry);
		}	
	}

	function add_type()
	{
		global $app_list_strings;
		if ( isset($this->type) &&
			! isset($app_list_strings['account_type_dom'][$this->type]))
		{
			unset($this->type);
		}	
	}

	function add_billing_address_streets() 
	{ 
		if ( isset($this->billing_address_street_2)) 
		{ 
			$this->billing_address_street .= 
				" ". $this->billing_address_street_2; 
		} 

		if ( isset($this->billing_address_street_3)) 
		{  
			$this->billing_address_street .= 
				" ". $this->billing_address_street_3; 
		} 
		if ( isset($this->billing_address_street_4)) 
		{  
			$this->billing_address_street .= 
				" ". $this->billing_address_street_4; 
		}
	}

	function add_shipping_address_streets() 
	{ 
		if ( isset($this->shipping_address_street_2)) 
		{ 
			$this->shipping_address_street .= 
				" ". $this->shipping_address_street_2; 
		} 

		if ( isset($this->shipping_address_street_3)) 
		{  
			$this->shipping_address_street .= 
				" ". $this->shipping_address_street_3; 
		} 

		if ( isset($this->shipping_address_street_4)) 
		{  
			$this->shipping_address_street .= 
				" ". $this->shipping_address_street_4; 
		} 
	}


	//module prefix used by ImportSteplast when calling ListView.php
	var $list_view_prefix = 'ACCOUNT';

	//removed importable_fields definition, this array is now built during the import process using the account
	//modules vardefs.

	//columns to be displayed in listview for displaying user's last import in ImportSteplast.php
	var $list_fields = Array(
			 'id'
			,'name'
			,'website'
			,'phone_office'
			,'billing_address_city'
			,'assigned_user_name'
			,'assigned_user_id'
			);

	//this list defines what beans get populated during an import of accounts
	var $related_modules = array("Accounts",); 
	

	function ImportAccount() {
		parent::Account();
	}

	function create_list_query($order_by, $where, $show_deleted = 0)
	{
		global $current_user;
		$query = '';

			$query = "SELECT distinct accounts.id,accounts.name, 
							accounts.billing_address_city,
							accounts.billing_address_state, 
								accounts.phone_office,
								accounts.assigned_user_id,
                                users.user_name as assigned_user_name ";
                



				
				$query.=" FROM users_last_import,accounts
                LEFT JOIN users ON accounts.assigned_user_id=users.id";
                



				
				$query.="	WHERE
				users_last_import.assigned_user_id=
					'{$current_user->id}'
				AND users_last_import.bean_type='Accounts'
				AND users_last_import.bean_id=accounts.id
				AND users_last_import.deleted=0
				AND accounts.deleted=0";
	if(! empty($order_by))
		{
			$query .= " ORDER BY $order_by";
		}

		return $query;

	
	}
}
?>
