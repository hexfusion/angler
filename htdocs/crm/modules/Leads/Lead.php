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
 * $Id: Lead.php,v 1.86 2006/08/17 23:11:17 jenny Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Tasks/Task.php');
require_once('modules/Notes/Note.php');
require_once('modules/Meetings/Meeting.php');
require_once('modules/Calls/Call.php');
require_once('modules/Emails/Email.php');

// Lead is used to store profile information for people who may become customers.
class Lead extends SugarBean {
	var $field_name_map;
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;



	var $description;
	var $salutation;
	var $first_name;
	var $last_name;
	var $title;
	var $department;
	var $reports_to_id;
	var $do_not_call;
	var $phone_home;
	var $phone_mobile;
	var $phone_work;
	var $phone_other;
	var $phone_fax;
	var $refered_by;
	var $email1;
	var $email2;
	var $email_opt_out;
	var $primary_address_street;
	var $primary_address_city;
	var $primary_address_state;
	var $primary_address_postalcode;
	var $primary_address_country;
	var $alt_address_street;
	var $alt_address_city;
	var $alt_address_state;
	var $alt_address_postalcode;
	var $alt_address_country;
	var $name;
	var $full_name;
	var $portal_name;
	var $portal_app;
	var $contact_id;
	var $contact_name;
	var $account_id;
	var $opportunity_id;
	var $opportunity_name;
	var $opportunity_amount;
	//used for vcard export only
	var $birthdate;
	var $invalid_email;
	var $status;
	var $status_description;

	var $lead_source;
	var $lead_source_description;
	// These are for related fields
	var $account_name;
	var $account_site;
	var $account_description;
	var $case_role;
	var $case_rel_id;
	var $case_id;
	var $task_id;
	var $note_id;
	var $meeting_id;
	var $call_id;
	var $email_id;
	var $assigned_user_name;
	var $campaign_id;
    var $alt_address_street_2;
    var $alt_address_street_3;
    var $primary_address_street_2;
    var $primary_address_street_3;
     





	var $table_name = "leads";

	var $object_name = "Lead";
	var $object_names = "Leads";
	var $module_dir = "Leads";


	var $new_schema = true;

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id');
	var $relationship_fields = Array('email_id'=>'emails');

	function Lead() {
		parent::SugarBean();
		global $current_user;
		global $app_list_strings;









	}

	// need to override to have a name field created for this class
	function retrieve($id = -1, $encode=true) {
		global $locale;
		
		$ret_val = parent::retrieve($id, $encode);
		// make a properly formatted first and last name
		$this->_create_proper_name_field();
		return $ret_val;
	}
	

	function get_summary_text()
	{
	    $this->_create_proper_name_field();
        return $this->name;
	}

	function get_account()
	{
		if(isset($this->account_id) && !empty($this->account_id)){
			$query = "SELECT name , assigned_user_id account_name_owner FROM accounts WHERE id='{$this->account_id}'";

	        //requireSingleResult has beeen deprecated.
			//$result = $this->db->requireSingleResult($query); 
			$result = $this->db->limitQuery($query,0,1,true, "Want only a single row");
			
			if(!empty($result)){
				$row = $this->db->fetchByAssoc($result);
				$this->account_name = $row['name'];
				$this->account_name_owner = $row['account_name_owner'];
				$this->account_name_mod = 'Accounts';
			}

	}}
	function get_opportunity()
	{
		if(isset($this->opportunity_id) && !empty($this->opportunity_id)){
			$query = "SELECT name, assigned_user_id opportunity_name_owner FROM opportunities WHERE id='{$this->opportunity_id}'";

	        //requireSingleResult has beeen deprecated.
			//$result = $this->db->requireSingleResult($query); 
			$result = $this->db->limitQuery($query,0,1,true, "Want only a single row");
			
			if(!empty($result)){
				$row = $this->db->fetchByAssoc($result);
				$this->opportunity_name = $row['name'];
				$this->opportunity_name_owner = $row['opportunity_name_owner'];
				$this->opportunity_name_mod = 'Opportunities';
			}

	}}
	function get_contact()
	{
		if(isset($this->contact_id) && !empty($this->contact_id)){
			$query = "SELECT first_name, last_name, assigned_user_id contact_name_owner FROM contacts WHERE id='{$this->contact_id}'";

	        //requireSingleResult has beeen deprecated.
			//$result = $this->db->requireSingleResult($query);
			$result = $this->db->limitQuery($query,0,1,true, "Want only a single row");
			if(!empty($result)){
				$row= $this->db->fetchByAssoc($result);
				$this->contact_name = $row['first_name']. ' '. $row['last_name'];
				$this->contact_name_owner = $row['contact_name_owner'];
				$this->contact_name_mod = 'Contacts';
			}

	}}

	function create_list_query($order_by, $where, $show_deleted=0)
	{
		$custom_join = $this->custom_fields->getJOIN();
                $query = "SELECT ";
              

			$query .= "$this->table_name.*, users.user_name assigned_user_name";



			if($custom_join){
   				$query .= $custom_join['select'];
 			}
            $query .= " FROM leads ";





			$query .= "			LEFT JOIN users
                                ON leads.assigned_user_id=users.id ";



			if($custom_join){
  				$query .= $custom_join['join'];
			}
			$where_auto = '1=1';
			if($show_deleted == 0){
				$where_auto = " leads.deleted=0 ";
			}else if($show_deleted == 1){
				$where_auto = " leads.deleted=1 ";
			}	

		if($where != "")
			$query .= "where ($where) AND ".$where_auto;
		else
			$query .= "where ".$where_auto; //."and (leads.converted='0')";

		if(!empty($order_by))
			$query .= " ORDER BY $order_by";

		return $query;
	}



        function create_export_query(&$order_by, &$where)
        {
                         $custom_join = $this->custom_fields->getJOIN();
                         $query = "SELECT
                                leads.*,

                                users.user_name assigned_user_name";



                         if($custom_join){
   							$query .= $custom_join['select'];
 						}
                         $query .= " FROM leads ";




			$query .= "			LEFT JOIN users
                                ON leads.assigned_user_id=users.id ";



            	if($custom_join){
  					$query .= $custom_join['join'];
				}

                        $where_auto = " leads.deleted=0 ";

                if($where != "")
                        $query .= "where ($where) AND ".$where_auto;
                else
                        $query .= "where ".$where_auto;

                if(!empty($order_by))
                        $query .= " ORDER BY $order_by";

                return $query;
        }

    function converted_lead($leadid, $contactid, $accountid, $opportunityid){
    	$query = "UPDATE leads set status='Converted', converted='1', contact_id=$contactid, account_id=$accountid, opportunity_id=$opportunityid where  id=$leadid and deleted=0";
		$this->db->query($query,true,"Error converting lead: ");
    }

	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();
		$this->get_account();
		
	}

	function fill_in_additional_detail_fields()
	{
		//Fill in the assigned_user_name
		//if(!empty($this->status))
		//$this->status = translate('lead_status_dom', '', $this->status);
		$this->name = $this->first_name . ' ' . $this->last_name;
		$this->get_contact();
		$this->get_opportunity();
		$this->get_account();
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);




		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
	}
	function get_list_view_data(){
		global $app_list_strings;
		global $current_user;
		
		$this->_create_proper_name_field();
		$temp_array = $this->get_list_view_array();
		$temp_array['STATUS'] = (empty($temp_array['STATUS'])) ? '' : $temp_array['STATUS'];		
		$temp_array['ENCODED_NAME']=$this->name;   
		$temp_array['NAME']=$this->name;
		$temp_array['EMAIL1_LINK'] = $current_user->getEmailLink('email1', $this, '', '', 'ListView');
    	return $temp_array;
	}

	/**
		builds a generic search based on the query string using or
		do not include any $this-> because this is called on without having the class instantiated
	*/
	function build_generic_where_clause ($the_query_string) {
	$where_clauses = Array();
	$the_query_string = PearDatabase::quote(from_html($the_query_string));

	array_push($where_clauses, "leads.last_name like '$the_query_string%'");
	array_push($where_clauses, "leads.account_name like '$the_query_string%'");
	array_push($where_clauses, "leads.first_name like '$the_query_string%'");
	array_push($where_clauses, "leads.email1 like '$the_query_string%'");
	array_push($where_clauses, "leads.email2 like '$the_query_string%'");
	if (is_numeric($the_query_string)) {
		array_push($where_clauses, "leads.phone_home like '%$the_query_string%'");
		array_push($where_clauses, "leads.phone_mobile like '%$the_query_string%'");
		array_push($where_clauses, "leads.phone_work like '%$the_query_string%'");
		array_push($where_clauses, "leads.phone_other like '%$the_query_string%'");
		array_push($where_clauses, "leads.phone_fax like '%$the_query_string%'");

	}

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}


	return $the_where;
	}

	function set_notification_body($xtpl, $lead)
	{
		global $app_list_strings;		
		
		$xtpl->assign("LEAD_NAME", trim($lead->first_name . " " . $lead->last_name));
		$xtpl->assign("LEAD_SOURCE", (isset($lead->lead_source) ? $app_list_strings['lead_source_dom'][$lead->lead_source] : ""));
		$xtpl->assign("LEAD_STATUS", (isset($lead->status)? $app_list_strings['lead_status_dom'][$lead->status]:""));
		$xtpl->assign("LEAD_DESCRIPTION", $lead->description);

		return $xtpl;
	}

	/**
	 * Generate the name field from the first_name and last_name fields.
	 */
	function _create_proper_name_field() {
        global $locale;
        $full_name = $locale->getLocaleFormattedName($this->first_name, $this->last_name, $this->salutation);
        $this->name = $full_name;
        $this->full_name = $full_name; 

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
		if(!empty($this->account_name)){
			
			if(!empty($this->account_name_owner)){
				global $current_user;
				$is_owner = $current_user->id == $this->account_name_owner;
			}
		}
			if( ACLController::checkAccess('Accounts', 'view', $is_owner)){
				$array_assign['ACCOUNT'] = 'a';
			}else{
				$array_assign['ACCOUNT'] = 'span';
			}
		$is_owner = false;
		if(!empty($this->opportunity_name)){
			
			if(!empty($this->opportunity_name_owner)){
				global $current_user;
				$is_owner = $current_user->id == $this->opportunity_name_owner;
			}
		}
			if( ACLController::checkAccess('Opportunities', 'view', $is_owner)){
				$array_assign['OPPORTUNITY'] = 'a';
			}else{
				$array_assign['OPPORTUNITY'] = 'span';
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

//carrys forward custom lead fields to contacts, accounts, opportunities during Lead Conversion
	function convertCustomFieldsForm(&$form, &$tempBean, &$prefix) {

		global $mod_strings, $app_list_strings, $app_strings, $lbl_required_symbol;

		foreach($this->field_defs as $field => $value) {
			
			if(!empty($value['custom_type']) AND isset($value['custom_type']) ) {
				if( !empty($tempBean->field_defs[$field]) AND isset($tempBean->field_defs[$field]) ) {
					$form .= "<tr><td nowrap colspan='4' class='dataLabel'>".$mod_strings[$tempBean->field_defs[$field]['vname']].":";

					if( !empty($tempBean->custom_fields->avail_fields[$field]['required']) AND ( ($tempBean->custom_fields->avail_fields[$field]['required']== 1) OR ($tempBean->custom_fields->avail_fields[$field]['required']== '1') OR ($tempBean->custom_fields->avail_fields[$field]['required']== 'true') OR ($tempBean->custom_fields->avail_fields[$field]['required']== true) ) ) {
						$form .= "&nbsp;<span class='required'>".$lbl_required_symbol."</span>";
					}
					$form .= "</td></tr>";
					$form .= "<tr><td nowrap colspan='4' class='dataField' nowrap>";
					
					if(!empty($value['options']) AND isset($value['options']) ) {
						$form .= "<select name='".$prefix.$field."'>";
						$form .= get_select_options_with_id($app_list_strings[$value['options']], $this->$field);
						$form .= "</select";
					} elseif($value['custom_type'] == 'bool' ) {
						if( ($this->$field == 1) OR ($this->$field == '1') ) { $checked = 'checked'; } else { $checked = ''; }
						$form .= "<input type='checkbox' name='".$prefix.$field."' id='".$prefix.$field."'  value='1' ".$checked."/>";
					} elseif($value['custom_type'] == 'text' ) {
						$form .= "<textarea name='".$prefix.$field."' rows='6' cols='50'>".$this->$field."</textarea>";
					} elseif($value['custom_type'] == 'date' ) {
						$form .= "<input name='".$prefix.$field."' id='jscal_field".$field."' type='text'  size='11' maxlength='10' value='".$this->$field."'>&nbsp;<img src='themes/Sugar/images/jscalendar.gif' alt='Enter Date'  id='jscal_trigger".$field."' align='absmiddle'> <span class='dateFormat'>yyyy-mm-dd</span><script type='text/javascript'>Calendar.setup ({inputField : 'jscal_field".$field."', ifFormat : '%Y-%m-%d', showsTime : false, button : 'jscal_trigger".$field."', singleClick : true, step : 1}); addToValidate('ConvertLead', '".$field."', 'date', false,'".$mod_strings[$tempBean->field_defs[$field]['vname']]."' );</script>";
					} else {
						$form .= "<input name='".$prefix.$field."' type='text' value='".$this->$field."'>";
						
						if($this->custom_fields->avail_fields[$field]['data_type'] == 'int') {
							$form .= "<script>addToValidate('ConvertLead', '".$prefix.$field."', 'int', false,'".$prefix.":".$mod_strings[$tempBean->field_defs[$field]['vname']]."' );</script>";
						}
						elseif($this->custom_fields->avail_fields[$field]['data_type'] == 'float') {
							$form .= "<script>addToValidate('ConvertLead', '".$prefix.$field."', 'float', false,'".$prefix.":".$mod_strings[$tempBean->field_defs[$field]['vname']]."' );</script>";
						}

					}

					if( !empty($tempBean->custom_fields->avail_fields[$field]['required']) AND ( ($tempBean->custom_fields->avail_fields[$field]['required']== 1) OR ($tempBean->custom_fields->avail_fields[$field]['required']== '1') OR ($tempBean->custom_fields->avail_fields[$field]['required']== 'true') OR ($tempBean->custom_fields->avail_fields[$field]['required']== true) ) ) {
							$form .= "<script>addToValidate('ConvertLead', '".$prefix.$field."', 'relate', true,'".$prefix.":".$mod_strings[$tempBean->field_defs[$field]['vname']]."' );</script>";
						}

					$form .= "</td></tr>";
					

				}
			}

		}

		return true;
	}
}

?>
