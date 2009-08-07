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
$dictionary['Call'] = array('table' => 'calls', 'comment' => 'A Call is an activity representing a phone call',
                               'unified_search' => true,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
    'comment' => 'Unique identifier'
  ),
  'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=> true,
    'comment' => 'Date record was created'
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required'=> true,
    'comment' => 'Date record was last modified'
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
    'comment' => 'User assigned to the record'
  ),
  'assigned_user_name' => 
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'nondb',
    'table' => 'users',
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
    'comment' => 'User who last modified the record'
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
    'comment' => 'User who created the record'

  ),



























  	 'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_SUBJECT',
    'dbType' => 'varchar',
    'type' => 'name',
    'len' => '50',
    'comment' => 'Brief description of the call',
    'unified_search' => true,
  ),
   'duration_hours' => 
  array (
    'name' => 'duration_hours',
    'vname' => 'LBL_DURATION_HOURS',
    'type' => 'int',
    'len' => '2',
    'comment' => 'Call duration, hours portion'
  ),
  'duration_minutes' => 
  array (
    'name' => 'duration_minutes',
    'vname' => 'LBL_DURATION_MINUTES',
    'type' => 'int',
    'len' => '2',
    'comment' => 'Call duration, minutes portion'
  ),
   'date_start' => 
  array (
    'name' => 'date_start',
    'vname' => 'LBL_DATE',
    'type' => 'date',
    'rel_field' => 'time_start',
    'comment' => 'Date in which call is schedule to (or did) start'
  ),
  'time_start' => 
  array (
    'name' => 'time_start',
    'vname' => 'LBL_TIME',
    'type' => 'time',
    'rel_field' => 'date_start',
    'comment' => 'Time in which call is scheduled to (or did) start'
  ),
   'date_end' => 
  array (
    'name' => 'date_end',
    'vname' => 'LBL_DATE_END',
    'type' => 'date',
    'massupdate'=>false,
    'comment' => 'Date is which call is scheduled to (or did) end'
  ),
 'parent_type'=>
  array(
  	'name'=>'parent_type',
  	'vname'=>'LBL_LIST_RELATED_TO',
  	'type'=>'varchar',
  	'required'=>false,
    'reportable'=>false,
  	'len'=>25,
      'comment' => 'The Sugar object to which the call is related'
  	),
  'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'len' => '25',
    'options' => 'call_status_dom',
    'required'=>false,
    'comment' => 'The status of the call (Held, Not Held, etc.)'
  ),
  'direction' => 
  array (
    'name' => 'direction',
    'vname' => 'LBL_DIRECTION',
    'type' => 'enum',
    'len' => '25',
    'options' => 'call_direction_dom',
    'comment' => 'Indicates whether call is inbound or outbound'
  ),
    	 'parent_id'=>
  array(
  	'name'=>'parent_id',
  	'vname'=>'LBL_LIST_RELATED_TO',
  	'type'=>'id',
		'reportable'=>false,
      'comment' => 'The ID of the parent Sugar object identified by parent_type'
  	),
'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'A full description of the purpose and outcome of call'
  ),
    'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'reportable'=>false,
    'comment' => 'Record deltion indicator'
  ),
   'reminder_time'=>array (
    'name' => 'reminder_time',
    'vname' => 'LBL_REMINDER_TIME',
    'type' => 'int',
    'required' => false,
    'default' =>-1,
    'len' => '4',
    'comment' => 'Specifies when a reminder alert should be issued; -1 means no alert; otherwise the number of seconds prior to the start'
  ),
   	 'outlook_id' => 
  array (
    'name' => 'outlook_id',
    'vname' => 'LBL_OUTLOOK_ID',
    'type' => 'varchar',
    'len' => '255',
    'reportable' => false,
    'comment' => 'When the Sugar Plug-in for Microsoft Outlook syncs an Outlook appointment, this is the Outlook appointment item ID'
  ),
  'accept_status' => array (
    'name' => 'accept_status',
    'vname' => 'LBL_SUBJECT',
    'dbType' => 'varchar',
    'type' => 'varchar', 
    'len' => '20',  
    'source'=>'non-db',
  ),
  
 
  
  
 
 
  'contact_name' => 
  array (
    'name' => 'contact_name',
    'rname' => 'last_name',
    'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
    'id_name' => 'contact_id',
    'massupdate' => false,
    'vname' => 'LBL_CONTACT_NAME',
    'type' => 'relate',
    'link'=>'contacts',
    'table' => 'contacts',
    'isnull' => 'true',
    'module' => 'Contacts',
    'join_name' => 'contacts',
    'dbType' => 'varchar',
    'source'=>'non-db',
    'len' => 36,
  ),
  
  'account' => 
  array (
  	'name' => 'account',
    'type' => 'link',
    'relationship' => 'account_calls',
		'link_type'=>'one',
    'source'=>'non-db',
		'vname'=>'LBL_ACCOUNT',
  ),
  'opportunity' => 
  array (
  	'name' => 'opportunity',
    'type' => 'link',
    'relationship' => 'opportunity_calls',
    'source'=>'non-db',
		'link_type'=>'one',
		'vname'=>'LBL_OPPORTUNITY',
  ),
  'case' => 
  array (
  	'name' => 'case',
    'type' => 'link',
    'relationship' => 'case_calls',
    'source'=>'non-db',
		'link_type'=>'one',
		'vname'=>'LBL_CASE',
  ),
  'accounts' =>
  array (
    'name' => 'accounts',
    'type' => 'link',
    'relationship' => 'account_calls',
    'module'=>'Accounts',
    'bean_name'=>'Account',
    'source'=>'non-db',
    'vname'=>'LBL_ACCOUNT',
  ),
  'contacts' => 
  array (
  	'name' => 'contacts',
    'type' => 'link',
    'relationship' => 'calls_contacts',
    'source'=>'non-db',
		'vname'=>'LBL_CONTACTS',
  ),
  'users' => 
  array (
  	'name' => 'users',
    'type' => 'link',
    'relationship' => 'calls_users',
    'source'=>'non-db',
		'vname'=>'LBL_USERS',
  ),
 'notes' => 
  array (
  	'name' => 'notes',
    'type' => 'link',
    'relationship' => 'calls_notes',
    'module'=>'Notes',
    'bean_name'=>'Note',
    'source'=>'non-db',
		'vname'=>'LBL_NOTES',
  ),













  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'calls_created_by',
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
    'relationship' => 'calls_modified_user',
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
    'relationship' => 'calls_assigned_user',
    'vname' => 'LBL_ASSIGNED_TO_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),



)
                                                      , 'indices' => array (
       array('name' =>'callspk', 'type' =>'primary', 'fields'=>array('id')),
        array('name' =>'idx_call_name', 'type' =>'index', 'fields'=>array('name'))
)
, 'relationships' => array (
		'calls_assigned_user' => array(
			'lhs_module'		=> 'Users',
			'lhs_table'			=> 'users',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Calls',
			'rhs_table'			=> 'calls',
			'rhs_key'			=> 'assigned_user_id',
			'relationship_type'	=> 'one-to-many'
		),
		'calls_modified_user' => array(
			'lhs_module'		=> 'Users',
			'lhs_table'			=> 'users',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Calls',
			'rhs_table'			=> 'calls',
			'rhs_key'			=> 'modified_user_id',
			'relationship_type'	=> 'one-to-many'
		),
		'calls_created_by' => array(
			'lhs_module'		=> 'Users',
			'lhs_table'			=> 'users',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Calls',
			'rhs_table'			=> 'calls',
			'rhs_key'			=> 'created_by',
			'relationship_type'	=> 'one-to-many'
		),











		'calls_notes' => array(
			'lhs_module'		=> 'Calls',
			'lhs_table'			=> 'calls',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Notes',
			'rhs_table'			=> 'notes',
			'rhs_key'			=> 'parent_id',
			'relationship_type'	=> 'one-to-many',
		),
	),
//This enables optimistic locking for Saves From EditView
	'optimistic_locking'	=> true,
);
?>
