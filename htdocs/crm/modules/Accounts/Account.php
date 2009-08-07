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
 * $Id: Account.php,v 1.173 2006/08/09 18:39:44 jenny Exp $
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
require_once('modules/Bugs/Bug.php');

// Account is used to store account information.
class Account extends SugarBean {
	var $field_name_map = array();
	// Stored fields
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $annual_revenue;
	var $billing_address_street;
	var $billing_address_city;
	var $billing_address_state;
	var $billing_address_country;
	var $billing_address_postalcode;

    var $billing_address_street_2;
    var $billing_address_street_3;
    var $billing_address_street_4;
    
	var $description;
	var $email1;
	var $email2;
	var $employees;
	var $id;
	var $industry;
	var $name;
	var $ownership;
	var $parent_id;
	var $phone_alternate;
	var $phone_fax;
	var $phone_office;
	var $rating;
	var $shipping_address_street;
	var $shipping_address_city;
	var $shipping_address_state;
	var $shipping_address_country;
	var $shipping_address_postalcode;
    
    var $shipping_address_street_2;    
    var $shipping_address_street_3;    
    var $shipping_address_street_4;    
    
	var $sic_code;
	var $ticker_symbol;
	var $account_type;
	var $website;
	var $custom_fields;

	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	// These are for related fields
	var $opportunity_id;
	var $case_id;
	var $contact_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $member_id;
	var $parent_name;
	var $assigned_user_name;
	var $account_id = '';
	var $account_name = '';
	var $bug_id ='';
	var $module_dir = 'Accounts';
	







	var $table_name = "accounts";





	var $object_name = "Account";

	var $new_schema = true;

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'opportunity_id', 'bug_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id'



	);
	var $relationship_fields = Array('opportunity_id'=>'opportunities', 'bug_id' => 'bugs', 'case_id'=>'cases', 
									'contact_id'=>'contacts', 'task_id'=>'tasks', 'note_id'=>'notes',
									'meeting_id'=>'meetings', 'call_id'=>'calls', 'email_id'=>'emails','member_id'=>'members',



									);

	function Account() {
        parent::SugarBean();
        
        $this->setupCustomFields('Accounts');
		foreach ($this->field_defs as $field)
		{
			$this->field_name_map[$field['name']] = $field;
		}











	}

	function get_summary_text()
	{
		return $this->name;
	}

































	function get_contacts() {
		return $this->get_linked_beans('contacts','Contact');
	}
	


	function clear_account_case_relationship($account_id='', $case_id='')
	{
		if (empty($case_id)) $where = '';
		else $where = " and id = '$case_id'";
		$query = "UPDATE cases SET account_name = '', account_id = '' WHERE account_id = '$account_id' AND deleted = 0 " . $where;
		$this->db->query($query,true,"Error clearing account to case relationship: ");
	}

	// This method is used to provide backward compatibility with old data that was prefixed with http://
	// We now automatically prefix http://
	function remove_redundant_http()
	{
		if(eregi("http://", $this->website))
		{
			$this->website = substr($this->website, 7);
		}
	}

	function fill_in_additional_list_fields()
	{
	// Fill in the assigned_user_name
	//	$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);







		$this->remove_redundant_http();
	}

	function fill_in_additional_detail_fields()
	{
		// Fill in the assigned_user_name
			$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);





		$query = "SELECT a1.name from accounts a1, accounts a2 where a1.id = a2.parent_id and a2.id = '$this->id' and a1.deleted=0";
		$result = $this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		if($row != null)
		{
			$this->parent_name = $row['name'];
		}
		else
		{
			$this->parent_name = '';
		}

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);

		$this->remove_redundant_http();
	}
	function get_list_view_data(){
		global $system_config;
		$temp_array = $this->get_list_view_array();
		$temp_array["ENCODED_NAME"]=$this->name;
//		$temp_array["ENCODED_NAME"]=htmlspecialchars($this->name, ENT_QUOTES);
		if(!empty($this->billing_address_state))
		{
			$temp_array["CITY"] = $this->billing_address_city . ', '. $this->billing_address_state;
		}
		else
		{
			$temp_array["CITY"] = $this->billing_address_city;
		}
		$temp_array["BILLING_ADDRESS_STREET"]  = preg_replace("/[\r]/",'',$this->billing_address_street);
		$temp_array["SHIPPING_ADDRESS_STREET"] = preg_replace("/[\r]/",'',$this->shipping_address_street);
		$temp_array["BILLING_ADDRESS_STREET"]  = preg_replace("/[\n]/",'\n',$temp_array["BILLING_ADDRESS_STREET"] );
		$temp_array["SHIPPING_ADDRESS_STREET"] = preg_replace("/[\n]/",'\n',$temp_array["SHIPPING_ADDRESS_STREET"] );
    	if(isset($system_config->settings['system_skypeout_on']) && $system_config->settings['system_skypeout_on'] == 1){
    	if(!empty($temp_array['PHONE_OFFICE']) && skype_formatted($temp_array['PHONE_OFFICE'])){
    		$temp_array['PHONE_OFFICE'] = '<a href="callto://' . $temp_array['PHONE_OFFICE']. '">'.$temp_array['PHONE_OFFICE']. '</a>' ;
    	}}
		return $temp_array;
	}
	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = PearDatabase::quote(from_html($the_query_string));
	array_push($where_clauses, "accounts.name like '$the_query_string%'");
	if (is_numeric($the_query_string)) {
		array_push($where_clauses, "accounts.phone_alternate like '%$the_query_string%'");
		array_push($where_clauses, "accounts.phone_fax like '%$the_query_string%'");
		array_push($where_clauses, "accounts.phone_office like '%$the_query_string%'");
	}

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if(!empty($the_where)) $the_where .= " or ";
		$the_where .= $clause;
	}

	return $the_where;
}

	function create_export_query(&$order_by, &$where)
        {
        	$custom_join = $this->custom_fields->getJOIN();
			$query = "SELECT
					accounts.*,
                    users.user_name as assigned_user_name ";



                     if($custom_join){
						$query .=  $custom_join['select'];
					}
                    $query .= "FROM accounts ";




			if($custom_join){
					$query .=  $custom_join['join'];
				}
            $query .= " LEFT JOIN users
                    	ON accounts.assigned_user_id=users.id ";




            $where_auto = " accounts.deleted=0 ";

            if($where != "")
                    $query .= "where ($where) AND ".$where_auto;
            else
                    $query .= "where ".$where_auto;

           if(!empty($order_by)){
            	//check to see if order by variable already has table name by looking for dot "."
            	$table_defined_already = strpos($order_by, ".");

            	if($table_defined_already === false){
            		//table not defined yet, define accounts to avoid "ambigous column" SQL error 
            		$query .= " ORDER BY $order_by";
            	}else{
            		//table already defined, just add it to end of query
            	    $query .= " ORDER BY $order_by";	
            	}
                    
            }

            return $query;
        }

        function create_list_query($order_by, $where, $show_deleted= 0)
        {

			$custom_join = $this->custom_fields->getJOIN();

                $query = "SELECT ";

                $query .= "
                    users.user_name assigned_user_name,
                    accounts.*";
                 if($custom_join){
					$query .=  $custom_join['select'];
				}



             $query .= " FROM  accounts ";
			




			 $query .= "LEFT JOIN users
                    	ON accounts.assigned_user_id=users.id ";
             if($custom_join){
					$query .=  $custom_join['join'];
				}



     		$where_auto = '1=1';
			if($show_deleted == 0){
            	$where_auto = " accounts.deleted=0 ";
			}else if($show_deleted == 1){
				$where_auto = " accounts.deleted=1 ";	
			}

            if($where != "")
                    $query .= "where ($where) AND ".$where_auto;
            else
                    $query .= "where ".$where_auto;

        if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY $this->table_name.name";
                    return $query;
        }

	function set_notification_body($xtpl, $account)
	{
		$xtpl->assign("ACCOUNT_NAME", $account->name);
		$xtpl->assign("ACCOUNT_TYPE", $account->account_type);
		$xtpl->assign("ACCOUNT_DESCRIPTION", $account->description);

		return $xtpl;
	}
	
	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}


}



?>
