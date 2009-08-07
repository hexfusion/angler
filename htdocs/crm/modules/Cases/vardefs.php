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

$dictionary['Case'] = array('table' => 'cases','audited'=>true, 'unified_search' => true,'duplicate_merge'=>true,
		'comment' => 'Cases are issues or problems that a customer asks a support representative to resolve'
                               ,'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
    'comment' => 'Unique identifier'
  ),
  'case_number' =>
  array (
    'name' => 'case_number',
    'vname' => 'LBL_NUMBER',
    'type' => 'int',
    'required'=>true, 
    'len' => '11',
    'isnull' => 'false',
    'auto_increment'=>true,
    'unified_search' => true,
    'comment' => 'Visible case identifier',



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
    'reportable'=>true,
    'dbType' => 'id',
    'required'=>true,
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
    'reportable'=>true,
    'dbType' => 'id',
    'audited'=>true,
    'comment' => 'User ID assigned to record',
            'duplicate_merge'=>'disabled'           
  ),
  'assigned_user_name' =>
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_ASSIGNED_TO',
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
    'id_name' => 'created_by',
    'vname' => 'LBL_CREATED',
    'type' => 'assigned_user_name',
    'table' => 'created_by_users',
    'isnull' => 'false',
    'dbType' => 'id',
    'comment' => 'User ID who created the record'
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
    'len' => '255',
    'unified_search' => true,
    'comment' => 'The subject of the case',



    
  ),

   'account_name' =>
  array (
    'name' => 'account_name',
    'rname' => 'name',
    'id_name' => 'account_id',
    'vname' => 'LBL_ACCOUNT_NAME',
    'type' => 'relate',
    'link'=>'account',
    'table' => 'accounts',
    'join_name'=>'accounts',
    'isnull' => 'true',
    'module' => 'Accounts',
    'dbType' => 'varchar',
    'len' => 100,
    'source'=>'non-db',
    'unified_search' => true,
    'comment' => 'The name of the account represented by the account_id field'
  ),
   'account_name1' =>
  array (
    'name' => 'account_name1',
    'source'=>'non-db',
    'type'=>'text',
    'len' => 100,
  ),

    'account_id'=>
  	array(
  	'name'=>'account_id',
  	'type' => 'id',
  	'reportable'=>false,
  	'vname'=>'LBL_ACCOUNT_ID',
  	'audited'=>true,
  	'comment' => 'The account to which the case is associated'
  	),

  'status' =>
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'options' => 'case_status_dom',
    'len'=>25,
    'audited'=>true,
    'comment' => 'The status of the case',



    
  ),
   'priority' =>
  array (
    'name' => 'priority',
    'vname' => 'LBL_PRIORITY',
    'type' => 'enum',
    'options' => 'case_priority_dom',
    'len'=>25,
    'audited'=>true,
    'comment' => 'The priority of the case',



    
  ),
     'description' =>
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'The case description'
  ),
  'resolution' =>
  array (
    'name' => 'resolution',
    'vname' => 'LBL_RESOLUTION',
    'type' => 'text',
    'comment' => 'The resolution of the case'
  ),









  'tasks' =>
  array (
  	'name' => 'tasks',
    'type' => 'link',
    'relationship' => 'case_tasks',
    'source'=>'non-db',
		'vname'=>'LBL_TASKS',
  ),
  'notes' =>
  array (
  	'name' => 'notes',
    'type' => 'link',
    'relationship' => 'case_notes',
    'source'=>'non-db',
		'vname'=>'LBL_NOTES',
  ),
  'meetings' =>
  array (
  	'name' => 'meetings',
    'type' => 'link',
    'relationship' => 'case_meetings',
    'bean_name'=>'Meeting',
    'source'=>'non-db',
		'vname'=>'LBL_MEETINGS',
  ),
  'emails' =>
  array (
  	'name' => 'emails',
    'type' => 'link',
    'relationship' => 'emails_cases_rel',/* reldef in emails */
    'source'=>'non-db',
		'vname'=>'LBL_EMAILS',
  ),
  'calls' =>
  array (
  	'name' => 'calls',
    'type' => 'link',
    'relationship' => 'case_calls',
    'source'=>'non-db',
		'vname'=>'LBL_CALLS',
  ),
  'bugs' =>
  array (
  	'name' => 'bugs',
    'type' => 'link',
    'relationship' => 'cases_bugs',
    'source'=>'non-db',
		'vname'=>'LBL_BUGS',
  ),
  'contacts' =>
  array (
  	'name' => 'contacts',
    'type' => 'link',
    'relationship' => 'contacts_cases',
    'source'=>'non-db',
		'vname'=>'LBL_CONTACTS',
  ),
  'account' =>
  array (
  	'name' => 'account',
    'type' => 'link',
    'relationship' => 'account_cases',
		'link_type'=>'one',
		'side'=>'right',
    'source'=>'non-db',
		'vname'=>'LBL_ACCOUNT',
  ),














  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'cases_created_by',
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
    'relationship' => 'cases_modified_user',
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
    'relationship' => 'cases_assigned_user',
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

  ), 'indices' => array (
       array('name' =>'casespk', 'type' =>'primary', 'fields'=>array('id')),



       array('name' =>'case_number' , 'type'=>'index' , 'fields'=>array('case_number')),





       array('name' =>'idx_case_name', 'type' =>'index', 'fields'=>array('name')),
                                                      )

, 'relationships' => array (
	'case_calls' => array('lhs_module'=> 'Cases', 'lhs_table'=> 'cases', 'lhs_key' => 'id',
							  'rhs_module'=> 'Calls', 'rhs_table'=> 'calls', 'rhs_key' => 'parent_id',
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Cases')

	,'case_tasks' => array('lhs_module'=> 'Cases', 'lhs_table'=> 'cases', 'lhs_key' => 'id',
							  'rhs_module'=> 'Tasks', 'rhs_table'=> 'tasks', 'rhs_key' => 'parent_id',
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Cases')

	,'case_notes' => array('lhs_module'=> 'Cases', 'lhs_table'=> 'cases', 'lhs_key' => 'id',
							  'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'parent_id',
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Cases')

	,'case_meetings' => array('lhs_module'=> 'Cases', 'lhs_table'=> 'cases', 'lhs_key' => 'id',
							  'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'parent_id',
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Cases')

	,'case_emails' => array('lhs_module'=> 'Cases', 'lhs_table'=> 'cases', 'lhs_key' => 'id',
							  'rhs_module'=> 'Emails', 'rhs_table'=> 'emails', 'rhs_key' => 'parent_id',
							  'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Cases')
    ,
   'cases_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Cases', 'rhs_table'=> 'cases', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')

   ,'cases_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Cases', 'rhs_table'=> 'cases', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'cases_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Cases', 'rhs_table'=> 'cases', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')










)
//This enables optimistic locking for Saves From EditView
	,'optimistic_locking'=>true,
);
?>
