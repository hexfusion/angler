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
$dictionary['Bug'] = array('table' => 'bugs',    'audited'=>true, 'comment' => 'Bugs are defects in products and services','duplicate_merge'=>true
                               ,'unified_search' => true,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
    'comment' => 'Unique identifier'
  ),
  'bug_number' => 
  array (
    'name' => 'bug_number',
    'vname' => 'LBL_NUMBER',
    'type' => 'int',
    'len' => 11,
    'required'=>true,
    'auto_increment'=>true,
    'unified_search' => true,
    'comment' => 'Visual unique identifier',
    'duplicate_merge' => 'disabled',
  ),

    'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=>true,
    'comment' => 'Date record created'
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required'=>true,
    'comment' => 'Date record last modified'
  ),
    'modified_user_id' => 
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_MODIFIED',
    'type' => 'assigned_user_name',
    'table' => 'modified_user_id_users',
    'isnull' => 'false',
    'dbType' => 'varchar',
    'len' => 36,
    'required'=>true,
    'reportable'=>true,
    'comment' => 'User who last modified record'
  ),
   'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'relate',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'varchar',
    'reportable'=>true,
    'len' => 36,
    'audited'=>true,
    'comment' => 'User assigned to record',
    'module'=>'Users',
    'duplicate_merge'=>'disabled'           
    
  ),
  'assigned_user_name' => 
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'nondb',
    'table'=>'users',
    'duplicate_merge'=>'disabled'
  ),




























 'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_CREATED_BY',
    'type' => 'bool',
    'required' => true,
    'reportable'=>false,
    'comment' => 'Record deletion indicator'
  ),
 'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_SUBJECT',
    'type' => 'name',
    'dbType' => 'varchar',
    'len' => 255,
    'audited'=>true,
    'unified_search' => true,
    'comment' => 'The short description of the bug',
    'merge_filter' => 'selected',
  ),

    'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'options' => 'bug_status_dom',
    'len'=>25,
    'audited'=>true,
    'comment' => 'The status of the bug',
    'merge_filter' => 'enabled',
  )
  ,'priority' => 
  array (
    'name' => 'priority',
    'vname' => 'LBL_PRIORITY',
    'type' => 'enum',
    'options' => 'bug_priority_dom',
    'len'=>25,
    'audited'=>true,
    'comment' => 'An indication of the priorty of the bug',
    'merge_filter' => 'enabled',
  ),
    'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'A full description of the bug'
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
    'dbType' => 'varchar',
    'len' => 36,
    'comment' => 'User that created the record'
  ),

  'resolution' => 
  array (
    'name' => 'resolution',
    'vname' => 'LBL_RESOLUTION',
    'type' => 'enum',
    'options' => 'bug_resolution_dom',
    'len'=>255,
    'audited'=>true,
    'comment' => 'An indication of how the bug was resolved',
    'merge_filter' => 'enabled',
  ),
  'found_in_release'=>
  	array(
  	'name'=>'found_in_release',
  	'type' => 'id',
  	'dbType' => 'varchar',
  	 'vname' => 'LBL_FOUND_IN_RELEASE',
  	'len'=>255,
  	'reportable'=>false,
      'comment' => 'The software or service release that manifested the bug',
      'duplicate_merge' => 'disabled',
  	),
'release_name'=>
  array (
    'name' => 'release_name',
    'rname' => 'name',
    'vname'=>'LBL_FOUND_IN_RELEASE',
    'type' => 'relate',
    'dbType'=>'varchar',
    'reportable'=>false,
    'source'=>'non-db',
    'table'=>'releases',
    'merge_filter' => 'enabled',
    'id_name'=>'found_in_release',
    'module'=>'Releases',
    'link' => 'release_link',
  ),
  'type' => 
  array (
    'name' => 'type',
    'vname' => 'LBL_TYPE',
    'type' => 'enum',
    'options' => 'bug_type_dom',
    'len'=>255,
    'comment' => 'The type of bug (ex: bug, feature)',
    'merge_filter' => 'enabled',
  ),
    'fixed_in_release'=>
  	array(
  	'name'=>'fixed_in_release',
  	'type' => 'id',
  	'dbType' => 'varchar',
  	 'vname' => 'LBL_FIXED_IN_RELEASE',
  	'len'=>255,
  	'reportable'=>false,
      'comment' => 'The software or service release that corrected the bug',
      'duplicate_merge' => 'disabled',
  	),
   'fixed_in_release_name'=>
  array (
    'name' => 'fixed_in_release_name',
    'rname' => 'name',
    'id_name' => 'fixed_in_release',
    'vname' => 'LBL_FIXED_IN_RELEASE',
    'type' => 'relate',
    'table' => 'releases',
    'isnull' => 'false',
    'massupdate' => false,
    'module' => 'Releases',
    'dbType' => 'varchar',
    'len' => 36,
    'source'=>'non-db',
    'link' => 'fixed_in_release_link',
  ),
   'work_log' => 
  array (
    'name' => 'work_log',
    'vname' => 'LBL_WORK_LOG',
    'type' => 'text',
    'comment' => 'Free-form text used to denote activities of interest'
  ),
    'source' => 
  array (
    'name' => 'source',
    'vname' => 'LBL_SOURCE',
    'type' => 'enum',
    'options'=>'source_dom',
    'len' => 255,
    'comment' => 'An indicator of how the bug was entered (ex: via web, email, etc.)'
  ),
    'product_category' => 
  array (
    'name' => 'product_category',
    'vname' => 'LBL_PRODUCT_CATEGORY',
    'type' => 'enum',
    'options'=>'product_category_dom',
    'len' => 255,
    'comment' => 'Where the bug was discovered (ex: Accounts, Contacts, Leads)'
  ),









  	
  'tasks' => 
  array (
  	'name' => 'tasks',
    'type' => 'link',
    'relationship' => 'bug_tasks',
    'source'=>'non-db',
		'vname'=>'LBL_TASKS'
  ),
  'notes' => 
  array (
  	'name' => 'notes',
    'type' => 'link',
    'relationship' => 'bug_notes',
    'source'=>'non-db',
		'vname'=>'LBL_NOTES'
  ),
  'meetings' => 
  array (
  	'name' => 'meetings',
    'type' => 'link',
    'relationship' => 'bug_meetings',
    'source'=>'non-db',
		'vname'=>'LBL_MEETINGS'
  ),
  'calls' => 
  array (
  	'name' => 'calls',
    'type' => 'link',
    'relationship' => 'bug_calls',
    'source'=>'non-db',
		'vname'=>'LBL_CALLS'
  ),
  'emails' => 
  array (
  	'name' => 'emails',
    'type' => 'link',
    'relationship' => 'emails_bugs_rel',/* reldef in emails */
    'source'=>'non-db',
		'vname'=>'LBL_EMAILS'
  ),
  'contacts' =>
  array (
  	'name' => 'contacts',
    'type' => 'link',
    'relationship' => 'contacts_bugs',
    'source'=>'non-db',
		'vname'=>'LBL_CONTACTS'
  ),
  'accounts' => 
  array (
  	'name' => 'accounts',
    'type' => 'link',
    'relationship' => 'accounts_bugs',
    'source'=>'non-db',
		'vname'=>'LBL_ACCOUNTS'
  ),
  'cases' => 
  array (
  	'name' => 'cases',
    'type' => 'link',
    'relationship' => 'cases_bugs',
    'source'=>'non-db',
		'vname'=>'LBL_CASES'
  ),















  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'bugs_created_by',
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
    'relationship' => 'bugs_modified_user',
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
    'relationship' => 'bugs_assigned_user',
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
  'release_link' =>
  array (
        'name' => 'release_link',
    'type' => 'link',
    'relationship' => 'bugs_release',
    'vname' => 'LBL_FOUND_IN_RELEASE',
    'link_type' => 'one',
    'module'=>'Releases',
    'bean_name'=>'Release',
    'source'=>'non-db',
  ),
  'fixed_in_release_link' =>
  array (
        'name' => 'fixed_in_release_link',
    'type' => 'link',
    'relationship' => 'bugs_fixed_in_release',
    'vname' => 'LBL_FIXED_IN_RELEASE',
    'link_type' => 'one',
    'module'=>'Releases',
    'bean_name'=>'Release',
    'source'=>'non-db',
  ),



)
                                                      , 'indices' => array (
       array('name' =>'bugspk', 'type' =>'primary', 'fields'=>array('id')),



      array('name' =>'bug_number', 'type' =>'index', 'fields'=>array('bug_number')),




        
         array('name' =>'idx_bug_name', 'type' =>'index', 'fields'=>array('name'))
                                                      )

, 'relationships' => array (
	'bug_tasks' => array('lhs_module'=> 'Bugs', 'lhs_table'=> 'bugs', 'lhs_key' => 'id',
							  'rhs_module'=> 'Tasks', 'rhs_table'=> 'tasks', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Bugs')	
	,'bug_meetings' => array('lhs_module'=> 'Bugs', 'lhs_table'=> 'bugs', 'lhs_key' => 'id',
							  'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Bugs')	
	,'bug_calls' => array('lhs_module'=> 'Bugs', 'lhs_table'=> 'bugs', 'lhs_key' => 'id',
							  'rhs_module'=> 'Calls', 'rhs_table'=> 'calls', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Bugs')	
	,'bug_emails' => array('lhs_module'=> 'Bugs', 'lhs_table'=> 'bugs', 'lhs_key' => 'id',
							  'rhs_module'=> 'Emails', 'rhs_table'=> 'emails', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Bugs')	
	,'bug_notes' => array('lhs_module'=> 'Bugs', 'lhs_table'=> 'bugs', 'lhs_key' => 'id',
							  'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'parent_id',	
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Bugs')	
    
  ,'bugs_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Bugs', 'rhs_table'=> 'bugs', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')

   ,'bugs_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Bugs', 'rhs_table'=> 'bugs', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'bugs_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Bugs', 'rhs_table'=> 'bugs', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')







   ,'bugs_release' =>
   array('lhs_module'=> 'Releases', 'lhs_table'=> 'releases', 'lhs_key' => 'id',
   'rhs_module'=> 'Bugs', 'rhs_table'=> 'bugs', 'rhs_key' => 'found_in_release',
   'relationship_type'=>'one-to-many')
   ,'bugs_fixed_in_release' =>
   array('lhs_module'=> 'Releases', 'lhs_table'=> 'releases', 'lhs_key' => 'id',
   'rhs_module'=> 'Bugs', 'rhs_table'=> 'bugs', 'rhs_key' => 'fixed_in_release',
   'relationship_type'=>'one-to-many')

),         //This enables optimistic locking for Saves From EditView
	'optimistic_locking'=>true,                   
                            );
?>
