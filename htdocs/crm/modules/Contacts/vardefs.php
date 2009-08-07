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
$dictionary['Contact'] = array('table' => 'contacts', 'audited'=>true, 'unified_search' => true, 'duplicate_merge'=>true, 'fields' =>
array (
	'id' =>
		array (
			'name' => 'id',
			'vname' => 'LBL_ID',
			'type' => 'id',
			'required'=>true,
			'reportable'=>false,
			'comment' => 'Unique identifier'
		),
	'deleted' =>
		array (
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'reportable'=>false,
			'default' => '0',
			'Importable' => false,
			'comment' => 'Record deletion indicator'
		),
	'date_entered' =>
		array (
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			'required' => true,
			'comment' => 'Date record created'
		),
	'date_modified' =>
		array (
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required' => true,
			'comment' => 'Date record last modified'
		),
	'modified_user_id' =>
		array (
			'name' => 'modified_user_id',
			'rname' => 'user_name',
			'id_name' => 'modified_user_id',
			'vname' => 'LBL_MODIFIED',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'isnull' => 'false',
			'reportable'=>true,
			'dbType' => 'id',
			'comment' => 'User who last modified record'
		),
	'assigned_user_id' =>
		array (
			'name' => 'assigned_user_id',
			'rname' => 'user_name',
			'id_name' => 'assigned_user_id',
			'vname' => 'LBL_ASSIGNED_TO',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'reportable'=>true,
			'isnull' => 'false',
			'dbType' => 'id',
			'audited'=>true,
			'comment' => 'User ID assigned to record',
            'duplicate_merge'=>'disabled'           
		),
	'assigned_user_name' =>
		array (
			'name' => 'assigned_user_name',
			'vname' => 'LBL_ASSIGNED_TO_NAME',
			'type' => 'varchar',
			'source' => 'non-db',
    			'table' => 'users',
            'duplicate_merge'=>'disabled'           
		),
	'created_by' =>
		array (
			'name' => 'created_by',
			'rname' => 'user_name',
			'id_name' => 'modified_user_id',
			'vname' => 'LBL_CREATED',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'isnull' => 'false',
			'dbType' => 'id',
			'comment' => 'User ID who created record'
		),




























	'salutation' =>
		array (
			'name' => 'salutation',
			'vname' => 'LBL_SALUTATION',
			'type' => 'enum',
			'options' => 'salutation_dom',
			'massupdate' => false,
			'len' => '5',
			'comment' => 'Contact salutation (e.g., Mr, Ms)'            
		),
	'full_name' =>
		array (
			'name' => 'full_name',
			'rname' => 'full_name',
			'vname' => 'LBL_FULL_NAME',
			'type' => 'name',
			'fields' => array('first_name', 'last_name'),
			'sort_on' => 'last_name',
			'source' => 'non-db',
			'len' => '510',
			'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
		),
	'name' =>
		array (
			'name' => 'name',
			'rname' => 'name',
			'vname' => 'LBL_NAME',
			'type' => 'varchar',
			'source' => 'non-db',
			'len' => '510',
			'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
			'Importable' => false
		),
	'first_name' =>
		array (
			'name' => 'first_name',
			'vname' => 'LBL_FIRST_NAME',
			'type' => 'varchar',
			'len' => '100',
			'unified_search' => true,
			'comment' => 'First name of the contact',



            
		),
	'last_name' =>
		array (
			'name' => 'last_name',
			'vname' => 'LBL_LAST_NAME',
			'type' => 'varchar',
			'len' => '100',
			'unified_search' => true, 
			'comment' => 'Last name of the contact',



		),
	'email_and_name1' =>
		array (
			'name' => 'email_and_name1',
			'rname' => 'email_and_name1',
			'vname' => 'LBL_NAME',
			'type' => 'varchar',
			'source' => 'non-db',
			'len' => '510',
			'Importable' => false
		),
	'lead_source' =>
		array (
			'name' => 'lead_source',
			'vname' => 'LBL_LEAD_SOURCE',
			'type' => 'enum',
			'options' => 'lead_source_dom',
			'len' => '100',
			'comment' => 'How did the contact come about',



		),
	'title' =>
		array (
			'name' => 'title',
			'vname' => 'LBL_TITLE',
			'type' => 'varchar',
			'len' => '50',
			'comment' => 'The title of the contact'
		),
	'department' =>
		array (
			'name' => 'department',
			'vname' => 'LBL_DEPARTMENT',
			'type' => 'varchar',
			'len' => '100',
			'comment' => 'The department of the contact',



		),
	'account_name' =>
		array (
			'name' => 'account_name',
			'rname' => 'name',
			'id_name' => 'account_id',
			'vname' => 'LBL_ACCOUNT_NAME',
			'join_name'=>'accounts',
			'type' => 'relate',
			'link' => 'accounts',
			'table' => 'accounts',
			'isnull' => 'true',
			'module' => 'Accounts',
			'dbType' => 'varchar',
			'len' => '255',
			'source' => 'non-db',
//			'unified_search' => true, 
		),
	'account_id' =>
		array (
			'name' => 'account_id',
			'rname' => 'id',
			'id_name' => 'account_id',
			'vname' => 'LBL_ACCOUNT_ID',
			'type' => 'relate',
			'table' => 'accounts',
			'isnull' => 'true',
			'module' => 'Accounts',
			'dbType' => 'varchar',
			'len' => '255',
			'reportable'=>false,
			'source' => 'non-db',
			'massupdate' => false,
            'duplicate_merge'=> 'disabled',

		),
	'opportunity_role_fields' =>
		array (
			'name' => 'opportunity_role_fields',
			'rname' => 'id',
			'relationship_fields'=>array('id' => 'opportunity_role_id', 'contact_role' => 'opportunity_role'),
			'vname' => 'LBL_ACCOUNT_NAME',
			'type' => 'relate',
			'link' => 'opportunities',
			'link_type' => 'relationship_info',
			'join_link_name' => 'opportunities_contacts',
			'source' => 'non-db',
			'Importable' => false,
            'duplicate_merge'=> 'disabled',

		),
	'opportunity_role_id' => 
		array(
			'name' => 'opportunity_role_id',
			'type' => 'varchar',
			'source' => 'non-db',
			'vname' => 'LBL_OPPORTUNITY_ROLE_ID',
		),
	'opportunity_role' =>
		array(
			'name' => 'opportunity_role',
			'type' => 'varchar',
			'source' => 'non-db',
			'vname' => 'LBL_OPPORTUNITY_ROLE',
		),
	'reports_to_id'=>
		array(
			'name' => 'reports_to_id',
			'vname' => 'LBL_REPORTS_TO_ID',
			'type' => 'id',
			'required'=>false,
			'reportable'=>false,
			'comment' => 'The contact this contact reports to'
		),
	'report_to_name' =>
		array (
			'name' => 'report_to_name',
			'rname' => 'last_name',
			'id_name' => 'reports_to_id',
			'vname' => 'LBL_REPORTS_TO',
			'type' => 'relate',
			'link' => 'reports_to_link',
			'table' => 'contacts',
			'isnull' => 'true',
			'module' => 'Contacts',
			'dbType' => 'varchar',
			'len' => 'id',
			'reportable'=>false,
			'source' => 'non-db',
		),
	'birthdate' =>
		array (
			'name' => 'birthdate',
			'vname' => 'LBL_BIRTHDATE',
			'massupdate' => false,
			'type' => 'date',
			'comment' => 'The birthdate of the contact'
		),
	'do_not_call' =>
		array (
			'name' => 'do_not_call',
			'vname' => 'LBL_DO_NOT_CALL',
			'type' => 'bool',
			'dbType' => 'varchar',
			'len' => '3',
			'default' => '0',
			'audited'=>true,
			'comment' => 'An indicator of whether contact can be called'
		),
	'phone_home' =>
		array (
			'name' => 'phone_home',
			'vname' => 'LBL_HOME_PHONE',
			'type' => 'phone',
			'dbType' => 'varchar',
			'len' => '25',
			'unified_search' => true, 
			'comment' => 'Home phone number of the contact',



		),
	'phone_mobile' =>
		array (
			'name' => 'phone_mobile',
			'vname' => 'LBL_MOBILE_PHONE',
			'type' => 'phone',
			'dbType' => 'varchar',
			'len' => '25',
			'unified_search' => true,
			'comment' => 'Mobile phone number of the contact',



		),
	'phone_work' =>
		array (
			'name' => 'phone_work',
			'vname' => 'LBL_OFFICE_PHONE',
			'type' => 'phone',
			'dbType' => 'varchar',
			'len' => '25',
			'audited'=>true,
			'unified_search' => true,
			'comment' => 'Work phone number of the contact',



		),
	'phone_other' =>
		array (
			'name' => 'phone_other',
			'vname' => 'LBL_OTHER_PHONE',
			'type' => 'phone',
			'dbType' => 'varchar',
			'len' => '25',
			'unified_search' => true,
			'comment' => 'Other phone number for the contact',



		),
	'phone_fax' =>
		array (
			'name' => 'phone_fax',
			'vname' => 'LBL_FAX_PHONE',
			'type' => 'phone',
			'dbType' => 'varchar',
			'len' => '25',
			'unified_search' => true,
			'comment' => 'Contact fax number',



		),
	'email1' =>
		array (
			'name' => 'email1',
			'vname' => 'LBL_EMAIL_ADDRESS',
			'type' => 'email',
			'dbType' => 'varchar',
			'len' => '100',
			'audited'=>true,
			'unified_search' => true,
			'comment' => 'Primary email address of the contact',



		),
	'email2' =>
		array (
			'name' => 'email2',
			'vname' => 'LBL_OTHER_EMAIL_ADDRESS',
			'type' => 'email',
			'dbType' => 'varchar',
			'len' => '100',
			'unified_search' => true,
			'comment' => 'Secondary email address of the contact',



		),
	'assistant' =>
		array (
			'name' => 'assistant',
			'vname' => 'LBL_ASSISTANT',
			'type' => 'varchar',
			'len' => '75',
			'unified_search' => true,
			'comment' => 'Name of the assistant of the contact',



		),
	'assistant_phone' =>
		array (
			'name' => 'assistant_phone',
			'vname' => 'LBL_ASSISTANT_PHONE',
			'type' => 'phone',
			'dbType' => 'varchar',
			'len' => '25',
			'unified_search' => true,
			'comment' => 'Phone number of the assistant of the contact',



		),
	'email_opt_out' =>
		array (
			'name' => 'email_opt_out',
			'vname' => 'LBL_EMAIL_OPT_OUT',
			'type' => 'bool',
			'dbType' => 'varchar',
			'len' => '3',
			'default' => '0',
			'comment' => 'Indicator whether the contact has elected to opt out of emails'
		),
	'primary_address_street' =>
		array (
			'name' => 'primary_address_street',
			'vname' => 'LBL_PRIMARY_ADDRESS_STREET',
			'type' => 'varchar',
			'len' => '150',
			'comment' => 'Street address for primary address',



		),
	'primary_address_city' =>
		array (
			'name' => 'primary_address_city',
			'vname' => 'LBL_PRIMARY_ADDRESS_CITY',
			'type' => 'varchar',
			'len' => '100',
			'comment' => 'City for primary address',



		),
	'primary_address_state' =>
		array (
			'name' => 'primary_address_state',
			'vname' => 'LBL_PRIMARY_ADDRESS_STATE',
			'type' => 'varchar',
			'len' => '100',
			'comment' => 'State for primary address',



		),
	'primary_address_postalcode' =>
		array (
			'name' => 'primary_address_postalcode',
			'vname' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
			'type' => 'varchar',
			'len' => '20',
			'comment' => 'Postal code for primary address',



            
		),
	'primary_address_country' =>
		array (
			'name' => 'primary_address_country',
			'vname' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
			'type' => 'varchar',
			'len' => '100',
			'comment' => 'Country for primary address',



		),
	'alt_address_street' =>
		array (
			'name' => 'alt_address_street',
			'vname' => 'LBL_ALT_ADDRESS_STREET',
			'type' => 'varchar',
			'len' => '150',
			'comment' => 'Street address for alternate address',



		),
	'alt_address_city' =>
		array (
			'name' => 'alt_address_city',
			'vname' => 'LBL_ALT_ADDRESS_CITY',
			'type' => 'varchar',
			'len' => '100',
			'comment' => 'City for alternate address',



		),
	'alt_address_state' =>
		array (
			'name' => 'alt_address_state',
			'vname' => 'LBL_ALT_ADDRESS_STATE',
			'type' => 'varchar',
			'len' => '100',
			'comment' => 'State for alternate address',



		),
	'alt_address_postalcode' =>
		array (
			'name' => 'alt_address_postalcode',
			'vname' => 'LBL_ALT_ADDRESS_POSTALCODE',
			'type' => 'varchar',
			'len' => '20',
			'comment' => 'Postal code for alternate address',



		),
	'alt_address_country' =>
		array (
			'name' => 'alt_address_country',
			'vname' => 'LBL_ALT_ADDRESS_COUNTRY',
			'type' => 'varchar',
			'len' => '100',
			'comment' => 'Country for alternate address',



		),
	
	'description' =>
		array (
			'name' => 'description',
			'vname' => 'LBL_DESCRIPTION',
			'type' => 'text',
			'comment' => 'Description of contact'
		),
	'portal_name' =>
		array (
			'name' => 'portal_name',
			'vname' => 'LBL_PORTAL_NAME',
			'type' => 'varchar',
			'len' => '255',
			'comment' => 'Name as it appears in the portal'
		),
	'portal_active' =>
		array (
			'name' => 'portal_active',
			'vname' => 'LBL_PORTAL_ACTIVE',
			'type' => 'bool',
			'required' => true,
			'default' => '0',
			'comment' => 'Indicator whether this contact is a portal user'
		),










	'portal_app' =>
		array (
			'name' => 'portal_app',
			'vname' => 'LBL_PORTAL_APP',
			'type' => 'varchar',
			'len' => '255',
			'comment' => 'Reference to the portal'
		),
	'invalid_email' =>
		array (
			'name' => 'invalid_email',
			'vname' => 'LBL_INVALID_EMAIL',
			'type' => 'bool',
			'comment' => 'Indicator that contact email address is invalid'
		),
	
	'primary_address_street_2' =>
		array (
			'name' => 'primary_address_street_2',
			'vname' => 'LBL_PRIMARY_ADDRESS_STREET_2',
			'type' => 'varchar',
			'len' => '150',
			'source' => 'non-db',
		),
	
	'primary_address_street_3' =>
		array (
			'name' => 'primary_address_street_3',
			'vname' => 'LBL_PRIMARY_ADDRESS_STREET_3',
			'type' => 'varchar',
			'len' => '150',
			'source' => 'non-db',
		),
	'alt_address_street_2' =>
		array (
			'name' => 'alt_address_street_2',
			'vname' => 'LBL_ALT_ADDRESS_STREET_2',
			'type' => 'varchar',
			'len' => '150',
			'source' => 'non-db',
		),
	'alt_address_street_3' =>
		array (
			'name' => 'alt_address_street_3',
			'vname' => 'LBL_ALT_ADDRESS_STREET_3',
			'type' => 'varchar',
			'len' => '150',
			'source' => 'non-db',
		),
	'accounts' =>
		array (
			'name' => 'accounts',
			'type' => 'link',
			'relationship' => 'accounts_contacts',
			'link_type' => 'one',
			'source' => 'non-db',
			'vname' => 'LBL_ACCOUNT',
		),
	'reports_to_link' =>
		array (
			'name' => 'reports_to_link',
			'type' => 'link',
			'relationship' => 'contact_direct_reports',
			'link_type' => 'one',
			'side' => 'right',
			'source' => 'non-db',
			'vname' => 'LBL_REPORTS_TO',
		),
	'opportunities' =>
		array (
			'name' => 'opportunities',
			'type' => 'link',
			'relationship' => 'opportunities_contacts',
			'source' => 'non-db',
			'module' => 'Opportunities',
			'bean_name' => 'Opportunity',
			'vname' => 'LBL_OPPORTUNITIES',
		),
	'bugs' =>
		array (
			'name' => 'bugs',
			'type' => 'link',
			'relationship' => 'contacts_bugs',
			'source' => 'non-db',
			'vname' => 'LBL_BUGS',
		),
	'calls' =>
		array (
			'name' => 'calls',
			'type' => 'link',
			'relationship' => 'calls_contacts',
			'source' => 'non-db',
			'vname' => 'LBL_CALLS',
		),
	'cases' =>
		array (
			'name' => 'cases',
			'type' => 'link',
			'relationship' => 'contacts_cases',
			'source' => 'non-db',
			'vname' => 'LBL_CASES',
		),  
	'direct_reports'=>
		array (
			'name' => 'direct_reports',
			'type' => 'link',
			'relationship' => 'contact_direct_reports',
			'source' => 'non-db',
			'vname' => 'LBL_DIRECT_REPORTS',
		),  
	'emails'=>
		array (
			'name' => 'emails',
			'type' => 'link',
			'relationship' => 'emails_contacts_rel',
			'source' => 'non-db',
			'vname' => 'LBL_EMAILS',
		),  
	'leads'=>
		array (
			'name' => 'leads',
			'type' => 'link',
			'relationship' => 'contact_leads',
			'source' => 'non-db',
			'vname' => 'LBL_LEADS',
		),  
	
    'products'=>
        array (
            'name' => 'products',
            'type' => 'link',
            'relationship' => 'contact_products',
            'source' => 'non-db',
            'vname' => 'LBL_PRODUCTS_TITLE',
        ),  
	'meetings'=>
		array (
			'name' => 'meetings',
			'type' => 'link',
			'relationship' => 'meetings_contacts',
			'source' => 'non-db',
			'vname' => 'LBL_MEETINGS',
		),  
	'notes'=>
		array (
			'name' => 'notes',
			'type' => 'link',
			'relationship' => 'contact_notes',
			'source' => 'non-db',
			'vname' => 'LBL_NOTES',
		),  
	'project'=>
		array (
			'name' => 'project',
			'type' => 'link',
			'relationship' => 'projects_contacts',
			'source' => 'non-db',
			'vname' => 'LBL_PROJECTS',
		),  













        
	'tasks'=>
		array (
			'name' => 'tasks',
			'type' => 'link',
			'relationship' => 'contact_tasks',
			'source' => 'non-db',
			'vname' => 'LBL_TASKS',
		),  
	'user_sync'=>
		array (
			'name' => 'users',
			'type' => 'link',
			'relationship' => 'contacts_users',
			'source' => 'non-db',
			'vname' => 'LBL_USER_SYNC',
		),  














	'created_by_link' =>
		array (
			'name' => 'created_by_link',
			'type' => 'link',
			'relationship' => 'contacts_created_by',
			'vname' => 'LBL_CREATED_BY_USER',
			'link_type' => 'one',
			'module' => 'Users',
			'bean_name' => 'User',
			'source' => 'non-db',
		),
	'modified_user_link' =>
		array (
			'name' => 'modified_user_link',
			'type' => 'link',
			'relationship' => 'contacts_modified_user',
			'vname' => 'LBL_MODIFIED_BY_USER',
			'link_type' => 'one',
			'module' => 'Users',
			'bean_name' => 'User',
			'source' => 'non-db',
		),
	'assigned_user_link' =>
		array (
			'name' => 'assigned_user_link',
			'type' => 'link',
			'relationship' => 'contacts_assigned_user',
			'vname' => 'LBL_ASSIGNED_TO_USER',
			'link_type' => 'one',
			'module' => 'Users',
			'bean_name' => 'User',
			'source' => 'non-db',
            'rname' => 'user_name',
            'id_name' => 'assigned_user_id',
            'table' => 'users', 
            'duplicate_merge'=>'enabled'           
		),
	'campaigns' => 
		array (
  			'name' => 'campaigns',
    		'type' => 'link',
    		'relationship' => 'contact_campaign_log',
    		'module'=>'CampaignLog',
    		'bean_name'=>'CampaignLog',
    		'source'=>'non-db',
			'vname'=>'LBL_CAMPAIGNS',
	  	),
	'c_accept_status_fields' =>
		array (
			'name' => 'c_accept_status_fields',
			'rname' => 'id',
			'relationship_fields'=>array('id' => 'accept_status_id', 'accept_status' => 'accept_status_name'),
			'vname' => 'LBL_LIST_ACCEPT_STATUS',
			'type' => 'relate',
			'link' => 'calls',
			'link_type' => 'relationship_info',
			'source' => 'non-db',
			'Importable' => false,
            'duplicate_merge'=> 'disabled',            
		),
	'm_accept_status_fields' =>
		array (
			'name' => 'm_accept_status_fields',
			'rname' => 'id',
			'relationship_fields'=>array('id' => 'accept_status_id', 'accept_status' => 'accept_status_name'),
			'vname' => 'LBL_LIST_ACCEPT_STATUS',
			'type' => 'relate',
			'link' => 'meetings',
			'link_type' => 'relationship_info',
			'source' => 'non-db',
			'Importable' => false,
            'duplicate_merge'=> 'disabled',            
		),
	'accept_status_id' => 
		array(
			'name' => 'accept_status_id',
			'type' => 'varchar',
			'source' => 'non-db',
			'vname' => 'LBL_LIST_ACCEPT_STATUS',
		),
	'accept_status_name' =>
		array(
			'massupdate' => false,
			'name' => 'accept_status_name',
			'type' => 'enum',
			'source' => 'non-db',
			'vname' => 'LBL_LIST_ACCEPT_STATUS',
			'options' => 'dom_meeting_accept_status',
			'Importable' => false,
		),
      'prospect_lists' => 
      array (
        'name' => 'prospect_lists',
        'type' => 'link',
        'relationship' => 'prospect_list_contacts',
        'module'=>'ProspectLists',
        'source'=>'non-db',
        'vname'=>'LBL_PROSPECT_LIST',
      ),	
), 
'indices' => array (
	array(
		'name' => 'contactspk',
		'type' => 'primary',
		'fields' => array('id')
	),
	array(
		'name' => 'idx_cont_last_first',
		'type' => 'index',
		'fields' => array('last_name', 'first_name', 'deleted')
	),
	array(
		'name' => 'idx_contacts_del_last', 'type' => 'index', 'fields'=>array(
		 'deleted', 'last_name')),
	array(
		'name' => 'idx_cont_del_reports', 'type' => 'index', 'fields'=>array(
		'deleted', 'reports_to_id', 'last_name')),





	array(
		'name' => 'idx_cont_assigned',
		'type' => 'index',
		'fields' => array('assigned_user_id')
	),
	array(
		'name' => 'idx_cont_email1',
		'type' => 'index',
		'fields' => array('email1')
	),
	array(
		'name' => 'idx_cont_email2',
		'type' => 'index',
		'fields' => array('email2')
	),
)
, 'relationships' => array (
'contact_direct_reports' => array('lhs_module' => 'Contacts', 'lhs_table' => 'contacts', 'lhs_key' => 'id',
			  'rhs_module' => 'Contacts', 'rhs_table' => 'contacts', 'rhs_key' => 'reports_to_id',	
	  'relationship_type' => 'one-to-many'),
'contact_leads' => array('lhs_module' => 'Contacts', 'lhs_table' => 'contacts', 'lhs_key' => 'id',
			  'rhs_module' => 'Leads', 'rhs_table' => 'leads', 'rhs_key' => 'contact_id',	
	  'relationship_type' => 'one-to-many')	
,'contact_notes' => array('lhs_module' => 'Contacts', 'lhs_table' => 'contacts', 'lhs_key' => 'id',
			  'rhs_module' => 'Notes', 'rhs_table' => 'notes', 'rhs_key' => 'contact_id',	
	  'relationship_type' => 'one-to-many')	
,'contact_tasks' => array('lhs_module' => 'Contacts', 'lhs_table' => 'contacts', 'lhs_key' => 'id',
			  'rhs_module' => 'Tasks', 'rhs_table' => 'tasks', 'rhs_key' => 'contact_id',	
	  'relationship_type' => 'one-to-many')	
,'contacts_assigned_user' =>
array('lhs_module' => 'Users', 'lhs_table' => 'users', 'lhs_key' => 'id',
'rhs_module' => 'Contacts', 'rhs_table' => 'contacts', 'rhs_key' => 'assigned_user_id',
'relationship_type' => 'one-to-many')
,'contacts_modified_user' =>
array('lhs_module' => 'Users', 'lhs_table' => 'users', 'lhs_key' => 'id',
'rhs_module' => 'Contacts', 'rhs_table' => 'contacts', 'rhs_key' => 'modified_user_id',
'relationship_type' => 'one-to-many')
,'contacts_created_by' =>
array('lhs_module' => 'Users', 'lhs_table' => 'users', 'lhs_key' => 'id',
'rhs_module' => 'Contacts', 'rhs_table' => 'contacts', 'rhs_key' => 'created_by',
'relationship_type' => 'one-to-many'),









		'contact_campaign_log' => array(
									'lhs_module'		=>	'Contacts', 
									'lhs_table'			=>	'contacts', 
									'lhs_key' 			=> 	'id',
						  			'rhs_module'		=>	'CampaignLog', 
									'rhs_table'			=>	'campaign_log', 
									'rhs_key' 			=> 	'target_id',	
						  			'relationship_type'	=>'one-to-many'
						  		),

),
//This enables optimistic locking for Saves From EditView
'optimistic_locking'=>true,
);
?>
