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
$dictionary['Lead'] = array('table' => 'leads','audited'=>true, 'unified_search' => true,'duplicate_merge'=>true, 
		'comment' => 'Leads are persons of interest early in a sales cycle', 'fields' => array (

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
    'required' => 'true',
    'default' => '0',
    'reportable'=>false,
    'comment' => 'Record deletion indicator'
  ),
  'converted' => 
  array (
    'name' => 'converted',
    'vname' => 'LBL_CONVERTED',
    'type' => 'bool',
    'required' => 'true',
    'default' => '0',
    'comment' => 'Has Lead been converted to a Contact (and other Sugar objects)'
  ),
   'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required' => 'true',
    'comment' => 'Date record created'
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required' => 'true',
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
    'dbType' => 'id',
	'reportable'=>true,
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
    'isnull' => 'false',
    'dbType' => 'id',
    'reportable'=>true,
	'audited'=>true,
	'comment' => 'User assigned to this record',
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
    'id_name' => 'created_by',
    'vname' => 'LBL_CREATED',
    'type' => 'assigned_user_name',
    'table' => 'created_by_users',
    'isnull' => 'false',
    'dbType' => 'id',
    'comment' => 'User who created record'
  ),




























   'salutation' => 
  array (
    'name' => 'salutation',
    'vname' => 'LBL_SALUTATION',
    'type' => 'enum',
    'options' => 'salutation_dom',
    'massupdate' => false,
    'len'=>'5',
    'comment' => 'Salutation (ex: Mr, Mrs, Ms)'
  ),
 'full_name' => 
  array (
    'name' => 'full_name',
    'rname' => 'full_name',
    'vname' => 'LBL_FULL_NAME',
    'type' => 'name',
    'fields' => array('first_name','last_name'),
    'source' => 'non-db',
    'sort_on' => 'last_name',
    'len' => '510',
    'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
  ),
   'name' => 
  array (
    'name' => 'full_name',
    'rname' => 'full_name',
    'vname' => 'LBL_NAME',
    'fields' => array('first_name','last_name'),
    'type' => 'name',
    'source' => 'non-db',
    'len' => '510',
    'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
  ),
  'first_name' => 
  array (
    'name' => 'first_name',
    'vname' => 'LBL_FIRST_NAME',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => '25',
    'unified_search' => true,
    'comment' => 'First name of Lead',
    'merge_filter' => 'selected',     
    
  ),
  'last_name' => 
  array (
    'name' => 'last_name',
    'vname' => 'LBL_LAST_NAME',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => '25',
    'unified_search' => true,
    'comment' => 'Last name of Lead',
    'merge_filter' => 'selected', 
  ),
  'title' => 
  array (
    'name' => 'title',
    'vname' => 'LBL_TITLE',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Lead title (ex: Director))'
  ), 
  'refered_by' => 
  array (
    'name' => 'refered_by',
    'vname' => 'LBL_REFERED_BY',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Identifies who refered the lead',
    'merge_filter' => 'enabled', 
  ),  
  'lead_source' => 
  array (
    'name' => 'lead_source',
    'vname' => 'LBL_LEAD_SOURCE',
    'type' => 'enum',
    'options'=> 'lead_source_dom',
    'len' => '100',
	'audited'=>true,
	'comment' => 'Lead source (ex: Web, print)',
    'merge_filter' => 'enabled', 
  ),
  'lead_source_description' => 
  array (
    'name' => 'lead_source_description',
    'vname' => 'LBL_LEAD_SOURCE_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Description of the lead source'
  ),
  'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'len' => '100',
    'options' => 'lead_status_dom',
	'audited'=>true,
	'comment' => 'Status of the lead',
    'merge_filter' => 'enabled', 
  ),
  'status_description' => 
  array (
    'name' => 'status_description',
    'vname' => 'LBL_STATUS_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Description of the status of the lead'
  ),
  'department' => 
  array (
    'name' => 'department',
    'vname' => 'LBL_DEPARTMENT',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Department the lead belongs to',
    'merge_filter' => 'enabled', 
  ),
  'reports_to_id' => 
  array (
    'name' => 'reports_to_id',
    'vname' => 'LBL_REPORTS_TO_ID',
    'type' => 'id',
    'reportable'=>false,
    'comment' => 'ID of Contact the Lead reports to'
  ),
    'report_to_name' => 
  array (
    'name' => 'report_to_name',
    'rname' => 'name',
    'id_name' => 'reports_to_id',
    'vname' => 'LBL_REPORTS_TO',
    'type' => 'relate',
   // 'link'=>'reports_to_link',
    'table' => 'contacts',
    'isnull' => 'true',
    'module' => 'Contacts',
    'dbType' => 'varchar',
    'len' => 'id',
   	'source'=>'non-db',
    'reportable'=>false,
  ),
    'reports_to_link' => 
  array (
  	'name' => 'reports_to_link',
    'type' => 'link',
    'relationship' => 'lead_direct_reports',
		'link_type'=>'one',
		'side'=>'right',
    'source'=>'non-db',
		'vname'=>'LBL_REPORTS_TO',
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
	'comment' => 'Do Not Call indicator'
  ),
  'phone_home' => 
  array (
    'name' => 'phone_home',
    'vname' => 'LBL_HOME_PHONE',
    'type' => 'phone',
    'dbType' => 'varchar',
    'len' => '25',
    'unified_search' => true, 
    'comment' => 'Home phone number',
    'merge_filter' => 'enabled', 
  ),
  'phone_mobile' => 
  array (
    'name' => 'phone_mobile',
    'vname' => 'LBL_MOBILE_PHONE',
    'type' => 'phone',
    'dbType' => 'varchar',
    'len' => '25',
    'unified_search' => true, 
    'comment' => 'Mobile or cell phone number',
    'merge_filter' => 'enabled', 
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
	'comment' => 'Office phone number',
    'merge_filter' => 'enabled', 
  ),
  'phone_other' => 
  array (
    'name' => 'phone_other',
    'vname' => 'LBL_OTHER_PHONE',
    'type' => 'phone',
    'dbType' => 'varchar',
    'len' => '25',
    'unified_search' => true, 
    'comment' => 'Other phone number',
    'merge_filter' => 'enabled', 
  ),
  'phone_fax' => 
  array (
    'name' => 'phone_fax',
    'vname' => 'LBL_FAX_PHONE',
    'type' => 'phone',
    'dbType' => 'varchar',
    'len' => '25',
    'unified_search' => true, 
    'comment' => 'Fax phone number',
    'merge_filter' => 'enabled', 
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
	'comment' => 'Main email address of lead',
    'merge_filter' => 'enabled', 
  ),
  'email2' => 
  array (
    'name' => 'email2',
    'vname' => 'LBL_OTHER_EMAIL_ADDRESS',
    'type' => 'email',
    'dbType' => 'varchar',
    'len' => '100',
    'unified_search' => true, 
    'comment' => 'Secondary email address of lead',
    'merge_filter' => 'enabled', 
  ),
  'email_opt_out' => 
  array (
    'name' => 'email_opt_out',
    'vname' => 'LBL_EMAIL_OPT_OUT',
    'type' => 'bool',
    'dbType' => 'varchar',
    'len' => '3',
    'default' => '0',
	'audited'=>true,
	'comment' => 'Indicator signaling if lead elects to opt out of email campaigns'
  ),
  'primary_address_street' => 
  array (
    'name' => 'primary_address_street',
    'vname' => 'LBL_PRIMARY_ADDRESS_STREET',
    'type' => 'varchar',
    'len' => '150',
    'comment' => 'Primary street address',
    'merge_filter' => 'enabled', 
  ),
  'primary_address_city' => 
  array (
    'name' => 'primary_address_city',
    'vname' => 'LBL_PRIMARY_ADDRESS_CITY',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Primary address city',
    'merge_filter' => 'enabled', 
  ),
  'primary_address_state' => 
  array (
    'name' => 'primary_address_state',
    'vname' => 'LBL_PRIMARY_ADDRESS_STATE',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Primary address state',
    'merge_filter' => 'enabled', 
  ),
  'primary_address_postalcode' => 
  array (
    'name' => 'primary_address_postalcode',
    'vname' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
    'type' => 'varchar',
    'len' => '20',
    'comment' => 'Primary address postal code',
    'merge_filter' => 'enabled', 
  ),
  'primary_address_country' => 
  array (
    'name' => 'primary_address_country',
    'vname' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Primary address country',
    'merge_filter' => 'enabled', 
  ),
  'alt_address_street' => 
  array (
    'name' => 'alt_address_street',
    'vname' => 'LBL_ALT_ADDRESS_STREET',
    'type' => 'varchar',
    'len' => '150',
    'comment' => 'Alternate street address',
    'merge_filter' => 'enabled', 
  ),
  'alt_address_city' => 
  array (
    'name' => 'alt_address_city',
    'vname' => 'LBL_ALT_ADDRESS_CITY',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Alternate address city',
    'merge_filter' => 'enabled', 
  ),
  'alt_address_state' => 
  array (
    'name' => 'alt_address_state',
    'vname' => 'LBL_ALT_ADDRESS_STATE',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Alternate address state',
    'merge_filter' => 'enabled', 
  ),
  'alt_address_postalcode' => 
  array (
    'name' => 'alt_address_postalcode',
    'vname' => 'LBL_ALT_ADDRESS_POSTALCODE',
    'type' => 'varchar',
    'len' => '20',
    'comment' => 'Alternate address postal code',
    'merge_filter' => 'enabled', 
  ),
  'alt_address_country' => 
  array (
    'name' => 'alt_address_country',
    'vname' => 'LBL_ALT_ADDRESS_COUNTRY',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Alternate address country',
    'merge_filter' => 'enabled', 
  ),
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Lead description'
  ),
  'account_name' => 
  array (
    'name' => 'account_name',
    'vname' => 'LBL_ACCOUNT_NAME',
    'type' => 'varchar',
    'len' => '150',
    'unified_search' => true, 
    'comment' => 'Account name for lead'
  ),
  'account_description' => 
  array (
    'name' => 'account_description',
    'vname' => 'LBL_ACCOUNT_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Description of lead account'
  ),
  'contact_id' => 
  array (
    'name' => 'contact_id',
    'type' => 'id',
    'reportable'=>false,
    'vname'=>'LBL_CONTACT_ID',
	'comment' => 'If converted, Contact ID resulting from the conversion'    
  ),
  'account_id' => 
  array (
    'name' => 'account_id',
    'type' => 'id',
    'reportable'=>false,
    'vname'=>'LBL_ACCOUNT_ID',
	'comment' => 'If converted, Account ID resulting from the conversion'    
  ),  
  'opportunity_id' => 
  array (
    'name' => 'opportunity_id',
    'type' => 'id',
    'reportable'=>false,
    'vname'=>'LBL_OPPORTUNITY_ID',
	'comment' => 'If converted, Opportunity ID resulting from the conversion'    
  ),
  'opportunity_name' => 
  array (
    'name' => 'opportunity_name',
    'vname' => 'LBL_OPPORTUNITY_NAME',
    'type' => 'varchar',
    'len' => '255',
    'comment' => 'Opportunity name associated with lead'
  ),
  'opportunity_amount' => 
  array (
    'name' => 'opportunity_amount',
    'vname' => 'LBL_OPPORTUNITY_AMOUNT',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Amount of the opportunity'
  ),
  'campaign_id' => 
  array (
    'name' => 'campaign_id',
    'type' => 'id',
    'reportable'=>false,
    'vname'=>'LBL_CAMPAIGN_ID',
	'comment' => 'Campaign that generated lead'    
  ),  
  'portal_name' => 
  array (
    'name' => 'portal_name',
    'vname' => 'LBL_PORTAL_NAME',
    'type' => 'varchar',
    'len' => '255',
    'comment' => 'Portal user name when lead created via lead portal'
  ),
  'portal_app' => 
  array (
    'name' => 'portal_app',
    'vname' => 'LBL_PORTAL_APP',
    'type' => 'varchar',
    'len' => '255',
    'comment' => 'Portal application that resulted in created of lead'
  ), 
    'invalid_email' => 
  array (
    'name' => 'invalid_email',
    'vname' => 'LBL_INVALID_EMAIL',
    'type' => 'bool',
    'comment' => 'Indicator that email address for lead is invalid'
  ),  

  'primary_address_street_2' => 
  array (
    'name' => 'primary_address_street_2',
    'vname' => 'LBL_PRIMARY_ADDRESS_STREET_2',
    'type' => 'varchar',
    'len' => '150',
    'source'=>'non-db',
  ),

  'primary_address_street_3' => 
  array (
    'name' => 'primary_address_street_3',
    'vname' => 'LBL_PRIMARY_ADDRESS_STREET_3',
    'type' => 'varchar',
    'len' => '150',
    'source'=>'non-db',
  ),
  'alt_address_street_2' => 
  array (
    'name' => 'alt_address_street_2',
    'vname' => 'LBL_ALT_ADDRESS_STREET_2',
    'type' => 'varchar',
    'len' => '150',
    'source'=>'non-db',
  ),
  'alt_address_street_3' => 
  array (
    'name' => 'alt_address_street_3',
    'vname' => 'LBL_ALT_ADDRESS_STREET_3',
    'type' => 'varchar',
    'len' => '150',
    'source'=>'non-db',
  ),
  
  'tasks' => 
  array (
  	'name' => 'tasks',
    'type' => 'link',
    'relationship' => 'lead_tasks',
    'source'=>'non-db',
		'vname'=>'LBL_TASKS',
  ),  
  'notes' => 
  array (
  	'name' => 'notes',
    'type' => 'link',
    'relationship' => 'lead_notes',
    'source'=>'non-db',
		'vname'=>'LBL_NOTES',
  ),  
  'meetings' => 
  array (
  	'name' => 'meetings',
    'type' => 'link',
    'relationship' => 'lead_meetings',
    'source'=>'non-db',
		'vname'=>'LBL_MEETINGS',
  ), 
  'calls' => 
  array (
  	'name' => 'calls',
    'type' => 'link',
    'relationship' => 'lead_calls',
    'source'=>'non-db',
		'vname'=>'LBL_CALLS',
  ),   
  'emails' => 
  array (
  	'name' => 'emails',
    'type' => 'link',
    'relationship' => 'emails_leads_rel',
    'source'=>'non-db',
		'vname'=>'LBL_EMAILS',
  ),   














  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'leads_created_by',
    'vname' => 'LBL_CREATED_BY_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
  'modified_user_link' =>
  array (
        'name' => 'modified_user_link',
    'type' => 'link',
    'relationship' => 'leads_modified_user',
    'vname' => 'LBL_MODIFIED_BY_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
  'assigned_user_link' =>
  array (
        'name' => 'assigned_user_link',
    'type' => 'link',
    'relationship' => 'leads_assigned_user',
    'vname' => 'LBL_ASSIGNED_TO_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
    'duplicate_merge'=>'enabled',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'table' => 'users',         
  ),
'campaigns' => 
		array (
  			'name' => 'campaigns',
    		'type' => 'link',
    		'relationship' => 'lead_campaign_log',
    		'module'=>'CampaignLog',
    		'bean_name'=>'CampaignLog',
    		'source'=>'non-db',
			'vname'=>'LBL_CAMPAIGNS',
	  	),
      'prospect_lists' => 
      array (
        'name' => 'prospect_lists',
        'type' => 'link',
        'relationship' => 'prospect_list_leads',
        'module'=>'ProspectLists',
        'source'=>'non-db',
        'vname'=>'LBL_PROSPECT_LIST',
      ),    

)
                                                      , 'indices' => array (
       array('name' =>'leadspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_lead_last_first', 'type'=>'index', 'fields'=>array('last_name','first_name','deleted')),
       array('name' =>'idx_lead_del_stat', 'type'=>'index', 'fields'=>array('last_name','status','deleted','first_name')),
       array('name' =>'idx_lead_opp_del', 'type'=>'index', 'fields'=>array('opportunity_id','deleted',)),
       array('name' =>'idx_leads_acct_del', 'type'=>'index', 'fields'=>array('account_id','deleted',)),




       array(
		'name' => 'idx_lead_email1',
		'type' => 'index',
		'fields' => array('email1', 'deleted')
	),
	array(
		'name' => 'idx_lead_email2',
		'type' => 'index',
		'fields' => array('email2', 'deleted')
	),
        array('name' =>'idx_lead_assigned', 'type'=>'index', 'fields'=>array('assigned_user_id')),
        array('name' =>'idx_lead_contact', 'type'=>'index', 'fields'=>array('contact_id')),
        
                                             )
, 'relationships' => array (
'lead_direct_reports' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
							  'rhs_module'=> 'Leads', 'rhs_table'=> 'leads', 'rhs_key' => 'reports_to_id',	
							  'relationship_type'=>'one-to-many'),
	'lead_tasks' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
							  'rhs_module'=> 'Tasks', 'rhs_table'=> 'tasks', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Leads')	
	,'lead_notes' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
							  'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Leads')	

	,'lead_meetings' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
							  'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Leads')	

	,'lead_calls' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
							  'rhs_module'=> 'Calls', 'rhs_table'=> 'calls', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Leads')	

	,'lead_emails' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
							  'rhs_module'=> 'Emails', 'rhs_table'=> 'emails', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Leads'),	
	'lead_campaign_log' => array(
									'lhs_module'		=>	'Leads', 
									'lhs_table'			=>	'leads', 
									'lhs_key' 			=> 	'id',
						  			'rhs_module'		=>	'CampaignLog', 
									'rhs_table'			=>	'campaign_log', 
									'rhs_key' 			=> 	'target_id',	
						  			'relationship_type'	=>'one-to-many'
						  		)

  ,'leads_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Leads', 'rhs_table'=> 'leads', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')

   ,'leads_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Leads', 'rhs_table'=> 'leads', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'leads_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Leads', 'rhs_table'=> 'leads', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')






	)
	//This enables optimistic locking for Saves From EditView
	,'optimistic_locking'=>true,
);
?>
