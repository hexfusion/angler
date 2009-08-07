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

$dictionary['Opportunity'] = array('table' => 'opportunities','audited'=>true, 'unified_search' => true,'duplicate_merge'=>true,
		'comment' => 'An opportunity is the target of selling activities',
		'fields' => array (
  
  'id' => 
  array (
    'name' => 'id',
    'type' => 'id',
    'vname' => 'LBL_ID',
    'required'=>true,
    'reportable'=>false,
    'comment' => 'Unique identifier'
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
    'comment' => 'User who is assigned this record',
    'duplicate_merge'=>'disabled'           
  ),
   'assigned_user_name' => 
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_ASSIGNED_TO_NAME',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'nondb',
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
    'reportable'=>true,
    'comment' => 'User who created this record'
  ),



























  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'default' => '0',
    'reportable'=>false,
    'comment' => 'Record deletion indicator'
    
  ), 
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_OPPORTUNITY_NAME',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => '50',
    'unified_search' => true,
    'comment' => 'Name of the opportunity',



  ),
  'opportunity_type' => 
  array (
    'name' => 'opportunity_type',
    'vname' => 'LBL_TYPE',
    'type' => 'enum',
    'options'=> 'opportunity_type_dom',
    'len' => '255',
    'audited'=>true,
    'comment' => 'Type of opportunity (ex: Existing, New)',



  ),
      'account_name' => 
  array (
    'name' => 'account_name',
    'rname' => 'name',
    'id_name' => 'account_id',
    'vname' => 'LBL_ACCOUNT_NAME',
    'type' => 'relate',
    'table' => 'accounts',
    'join_name'=>'accounts',
    'isnull' => 'true',
    'module' => 'Accounts',
    'dbType' => 'varchar',
    'link'=>'accounts',
    'len' => '255',
   	 'source'=>'non-db',
   	 'unified_search' => true, 
  ),
  'account_id' => 
  array (
    'name' => 'account_id',
    'vname' => 'LBL_ACCOUNT_ID',
    'type' => 'id',
   	 'source'=>'non-db',
   	 'audited'=>true,
  ),
  
  'lead_source' => 
  array (
    'name' => 'lead_source',
    'vname' => 'LBL_LEAD_SOURCE',
    'type' => 'enum',
    'options' => 'lead_source_dom',
    'len' => '50',
    'comment' => 'Source of the opportunity',



  ),
  'amount' => 
  array (
    'name' => 'amount',
    'vname' => 'LBL_RAW_AMOUNT',
    'type' => 'float',
    'dbtype' => 'double',
    'comment' => 'Unconverted amount of the opportunity',
    'duplicate_merge'=>'disabled',
  ), 
  'amount_backup' => 
  array (
    'name' => 'amount_backup',
    'vname' => 'LBL_AMOUNT_BACKUP',
    'type' => 'varchar',
    'len' => '25',
    'comment' => 'Copy of amount used for conversion purposes',
    'duplicate_merge'=>'disabled',
  ),
  'amount_usdollar' => 
  array (
    'name' => 'amount_usdollar',
    'vname' => 'LBL_AMOUNT',
    'type' => 'currency',
    'dbType' => 'double',
    'disable_num_format' => true,
    'audited'=>true,
    'comment' => 'Formatted amount of the opportunity'
  ),
  'currency_id' => 
  array (
    'name' => 'currency_id',
    'type' => 'id',
    'vname' => 'LBL_CURRENCY_ID',
    'reportable'=>false,
    'comment' => 'Currency used for display purposes'
  ),
  'date_closed' => 
  array (
    'name' => 'date_closed',
    'vname' => 'LBL_DATE_CLOSED',
    'type' => 'date',
    'audited'=>true,
    'comment' => 'Expected or actual date the oppportunity will close'
    
  ),
  'next_step' => 
  array (
    'name' => 'next_step',
    'vname' => 'LBL_NEXT_STEP',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'The next step in the sales process',



  ),
  'sales_stage' => 
  array (
    'name' => 'sales_stage',
    'vname' => 'LBL_SALES_STAGE',
    'type' => 'enum',
    'options' => 'sales_stage_dom',
    'len' => '25',
    'audited'=>true,
    'comment' => 'Indication of progression towards closure',



  ),
  'probability' => 
  array (
    'name' => 'probability',
    'vname' => 'LBL_PROBABILITY',
    'type' => 'int',
    'dbtype' => 'double',
    'audited'=>true,
    'comment' => 'The probability of closure',



  ),
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Full description of the opportunity'
  ),
  
  'accounts' => 
  array (
  	'name' => 'accounts',
    'type' => 'link',
    'relationship' => 'accounts_opportunities',
    'source'=>'non-db',
		'link_type'=>'one',
    'module'=>'Accounts',
    'bean_name'=>'Account',
		'vname'=>'LBL_ACCOUNTS',
  ),
  'contacts' => 
  array (
  	'name' => 'contacts',
    'type' => 'link',
    'relationship' => 'opportunities_contacts',
    'source'=>'non-db',
    'module'=>'Contacts',
    'bean_name'=>'Contact',
		'vname'=>'LBL_CONTACTS',
  ),
  'tasks' => 
  array (
  	'name' => 'tasks',
    'type' => 'link',
    'relationship' => 'opportunity_tasks',
    'source'=>'non-db',
		'vname'=>'LBL_TASKS',
  ),
  'notes' => 
  array (
  	'name' => 'notes',
    'type' => 'link',
    'relationship' => 'opportunity_notes',
    'source'=>'non-db',
		'vname'=>'LBL_NOTES',
  ),
  'meetings' => 
  array (
  	'name' => 'meetings',
    'type' => 'link',
    'relationship' => 'opportunity_meetings',
    'source'=>'non-db',
		'vname'=>'LBL_MEETINGS',
  ),
  'calls' => 
  array (
  	'name' => 'calls',
    'type' => 'link',
    'relationship' => 'opportunity_calls',
    'source'=>'non-db',
		'vname'=>'LBL_CALLS',
  ),
  'emails' => 
  array (
  	'name' => 'emails',
    'type' => 'link',
    'relationship' => 'emails_opportunities_rel',/* reldef in emails */
    'source'=>'non-db',
		'vname'=>'LBL_EMAILS',
  ),










  'project' => 
  array (
  	'name' => 'project',
    'type' => 'link',
    'relationship' => 'projects_opportunities',
    'source'=>'non-db',
		'vname'=>'LBL_PROJECTS',
  ),
  'leads' => 
  array (
  	'name' => 'leads',
    'type' => 'link',
    'relationship' => 'opportunity_leads',
    'source'=>'non-db',
		'vname'=>'LBL_LEADS',
  ),














  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'opportunities_created_by',
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
    'relationship' => 'opportunities_modified_user',
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
    'relationship' => 'opportunities_assigned_user',
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










),
		'indices' => array (
			array(
				'name' => 'opportunitiespk',
				'type' =>'primary',
				'fields'=>array('id'),
			),
			array(
				'name' => 'idx_opp_name',
				'type' => 'index',
				'fields' => array('name'),
			),







			array(
				'name' => 'idx_opp_assigned',
				'type' => 'index',
				'fields' => array('assigned_user_id'),
			),
		),
		
 'relationships' => array (
	'opportunity_calls' => array('lhs_module'=> 'Opportunities', 'lhs_table'=> 'opportunities', 'lhs_key' => 'id',
							  'rhs_module'=> 'Calls', 'rhs_table'=> 'calls', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Opportunities')
	,'opportunity_meetings' => array('lhs_module'=> 'Opportunities', 'lhs_table'=> 'opportunities', 'lhs_key' => 'id',
							  'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Opportunities')
	,'opportunity_tasks' => array('lhs_module'=> 'Opportunities', 'lhs_table'=> 'opportunities', 'lhs_key' => 'id',
							  'rhs_module'=> 'Tasks', 'rhs_table'=> 'tasks', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Opportunities')	
	,'opportunity_notes' => array('lhs_module'=> 'Opportunities', 'lhs_table'=> 'opportunities', 'lhs_key' => 'id',
							  'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Opportunities')	
	,'opportunity_emails' => array('lhs_module'=> 'Opportunities', 'lhs_table'=> 'opportunities', 'lhs_key' => 'id',
							  'rhs_module'=> 'Emails', 'rhs_table'=> 'emails', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Opportunities')
	,'opportunity_leads' => array('lhs_module'=> 'Opportunities', 'lhs_table'=> 'opportunities', 'lhs_key' => 'id',
							  'rhs_module'=> 'Leads', 'rhs_table'=> 'leads', 'rhs_key' => 'opportunity_id',	
							  'relationship_type'=>'one-to-many')
    
  ,'opportunities_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Opportunities', 'rhs_table'=> 'opportunities', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')

   ,'opportunities_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Opportunities', 'rhs_table'=> 'opportunities', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'opportunities_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Opportunities', 'rhs_table'=> 'opportunities', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')







)
//This enables optimistic locking for Saves From EditView
	,'optimistic_locking'=>true,
);
?>
