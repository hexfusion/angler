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
$dictionary['User'] = array ( 'table' => 'users'
                                  , 'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
  ),
  'user_name' => 
  array (
    'name' => 'user_name',
    'vname' => 'LBL_USER_NAME',
    'dbType' => 'varchar',
    'type' => 'user_name',
    'len' => '60',
  ),
    'user_hash' => 
  array (
    'name' => 'user_hash',
    'vname' => 'LBL_USER_HASH',
    'type' => 'varchar',
    'len' => '32',
    'reportable'=>false,
  ),
  /**
   * authenticate_id is used by authentication plugins so they may place a quick lookup key for looking up a given user after authenticating through the plugin
   */
  'authenticate_id'=>
  array(
    'name' => 'authenticate_id',
    'vname' => 'LBL_AUTHENTICATE_ID',
    'type' => 'varchar',
    'len' => '100',
    'reportable'=>false,
  ),
    /**
   * sugar_login will force the user to use sugar authentication
   * regardless of what authentication the system is configured to use
   */
    'sugar_login' => 
  array (
    'name' => 'sugar_login',
    'vname' => 'LBL_SUGAR_LOGIN',
    'type' => 'bool',
    'default'=>'1',
    'reportable' => false
  ),
  'first_name' => 
  array (
    'name' => 'first_name',
    'vname' => 'LBL_FIRST_NAME',
    'dbType' => 'varchar',
    'type' => 'name',
    'len' => '30',
  ),
  'last_name' => 
  array (
    'name' => 'last_name',
    'vname' => 'LBL_LAST_NAME',
    'dbType' => 'varchar',
    'type' => 'name',
    'len' => '30',
  ),
  'full_name' =>
  array (
    'name' => 'full_name',
    'rname' => 'full_name',
    'vname' => 'LBL_NAME',
    'type' => 'name',
    'fields' => array('first_name','last_name'),
    'source' => 'non-db',
    'sort_on' => 'last_name',
    'sort_on2' => 'first_name',
     'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
    'len' => '510',
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
  ),
  'reports_to_id' => 
  array (
    'name' => 'reports_to_id',
    'vname' => 'LBL_REPORTS_ID',
    'type' => 'id',
    'reportable' =>false,
  ),
  'is_admin' => 
  array (
    'name' => 'is_admin',
    'vname' => 'LBL_IS_ADMIN',
    'type' => 'bool',
    'default'=>'0',
  ),
  'receive_notifications' => 
  array (
    'name' => 'receive_notifications',
    'vname' => 'LBL_RECEIVE_NOTIFICATIONS',
    'type' => 'bool',
    'default'=>'1',
  ),
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ), 
  'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=>true,
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required'=>true,
  ),
    'modified_user_id' => 
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
  ),
  'created_by' => 
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id'
  ),
  'title' => 
  array (
    'name' => 'title',
    'vname' => 'LBL_TITLE',
    'type' => 'varchar',
    'len' => '50',
  ),
  'department' => 
  array (
    'name' => 'department',
    'vname' => 'LBL_DEPARTMENT',
    'type' => 'varchar',
    'len' => '50',
  ), 
  'phone_home' => 
  array (
    'name' => 'phone_home',
    'vname' => 'LBL_HOME_PHONE',
    'type' => 'varchar',
    'len' => '50',
  ),
  'phone_mobile' => 
  array (
    'name' => 'phone_mobile',
    'vname' => 'LBL_MOBILE_PHONE',
    'type' => 'varchar',
    'len' => '50',
  ), 
  'phone_work' => 
  array (
    'name' => 'phone_work',
    'vname' => 'LBL_WORK_PHONE',
    'type' => 'varchar',
    'len' => '50',
  ), 
  'phone_other' => 
  array (
    'name' => 'phone_other',
    'vname' => 'LBL_OTHER_PHONE',
    'type' => 'varchar',
    'len' => '50',
  ),
  'phone_fax' => 
  array (
    'name' => 'phone_fax',
    'vname' => 'LBL_FAX_PHONE',
    'type' => 'varchar',
    'len' => '50',
  ),
  'email1' => 
  array (
    'name' => 'email1',
    'vname' => 'LBL_EMAIL',
    'type' => 'varchar',
    'len' => '100',
  ), 
  'email2' => 
  array (
    'name' => 'email2',
    'vname' => 'LBL_OTHER_EMAIL',
    'type' => 'varchar',
    'len' => '100',
  ), 
  'status' =>
  array(
  	'name' =>'status',
  	'vname' => 'LBL_STATUS', 
    'type' => 'enum',
    'len'=>'25',
    'options' => 'user_status_dom',
  ),
  'address_street' => 
  array (
    'name' => 'address_street',
    'vname' => 'LBL_ADDRESS_STREET',
    'type' => 'varchar',
    'len' => '150',
  ),
  'address_city' => 
  array (
    'name' => 'address_city',
    'vname' => 'LBL_ADDRESS_CITY',
    'type' => 'varchar',
    'len' => '100',
  ),
  'address_state' => 
  array (
    'name' => 'address_state',
    'vname' => 'LBL_ADDRESS_STATE',
    'type' => 'varchar',
    'len' => '100',
  ),
  'address_country' => 
  array (
    'name' => 'address_country',
    'vname' => 'LBL_ADDRESS_COUNTRY',
    'type' => 'varchar',
    'len' => '25',
  ),
  'address_postalcode' => 
  array (
    'name' => 'address_postalcode',
    'vname' => 'LBL_ADDRESS_POSTALCODE',
    'type' => 'varchar',
    'len' => '9',
  ),
  'user_preferences' => 
  array (
    'name' => 'user_preferences',
    'vname' => 'LBL_USER_PREFERENCES',
    'type' => 'text',
    'reportable' => false,
    'comment' => 'deprecated, see table user_preferences',
  ),









  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required'=>true,
    'reportable'=>false,
  ),
  'portal_only' => 
  array (
    'name' => 'portal_only',
    'vname' => 'LBL_PORTAL_ONLY',
    'type' => 'bool',
  ),  
  'employee_status' =>
  array(
  	'name' =>'employee_status',
  	'vname' => 'LBL_EMPLOYEE_STATUS', 
  	'type' =>'varchar', 
  	'len'=>'25',
  ),
  'messenger_id' =>
  array(
  	'name' =>'messenger_id',
  	'vname' => 'LBL_MESSENGER_ID', 
  	'type' =>'varchar', 
  	'len'=>'25',
  ),
  'messenger_type' =>
  array(
  	'name' =>'messenger_type',
  	'vname' => 'LBL_MESSENGER_TYPE', 
  	'type' =>'varchar', 
  	'len'=>'25',
  ),
  'calls' => 
  array (
  	'name' => 'calls',
    'type' => 'link',
    'relationship' => 'calls_users',
    'source'=>'non-db',
		'vname'=>'LBL_CALLS'
  ),
  'meetings' => 
  array (
  	'name' => 'meetings',
    'type' => 'link',
    'relationship' => 'meetings_users',
    'source'=>'non-db',
		'vname'=>'LBL_MEETINGS'
  ),










    'contacts_sync'=>
  array (
  	'name' => 'contacts',
    'type' => 'link',
    'relationship' => 'contacts_users',
    'source'=>'non-db',
		'vname'=>'LBL_CONTACTS_SYNC'
  ),  
  'reports_to_link' =>
  array (
    'name' => 'reports_to_link',
    'type' => 'link',
    'relationship' => 'user_direct_reports',
    'link_type'=>'one',
    'side'=>'right',
    'source'=>'non-db',
    'vname'=>'LBL_REPORTS_TO',
  ),
      'aclroles' => 
  array (
  	'name' => 'aclroles',
    'type' => 'link',
    'relationship' => 'acl_roles_users',
    'source'=>'non-db',
    'side'=>'right',
	'vname'=>'LBL_ROLES',
  ),
  'is_group' => 
  array (
    'name' => 'is_group',
    'vname' => 'LBL_GROUP',
    'type' => 'bool',
  ),
	/* to support Meetings SubPanels */
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
			'name' => 'accept_status_name',
			'type' => 'enum',
			'source' => 'non-db',
			'vname' => 'LBL_LIST_ACCEPT_STATUS',
			'options' => 'dom_meeting_accept_status',
			'massupdate' => false,			
		),
      'prospect_lists' => 
      array (
        'name' => 'prospect_lists',
        'type' => 'link',
        'relationship' => 'prospect_list_users',
        'module'=>'ProspectLists',
        'source'=>'non-db',
        'vname'=>'LBL_PROSPECT_LIST',
      ),    
),     
                               'indices' => array (
       array('name' =>'userspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'user_name_idx', 'type' =>'index', 'fields'=>array('user_name')),
),
'relationships' => array (
  'user_direct_reports' => array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id', 'rhs_module'=> 'Users', 'rhs_table'=> 'users', 'rhs_key' => 'reports_to_id',
                'relationship_type'=>'one-to-many'),
     'acl_roles_users' => array('lhs_module'=> 'ACLRoles', 'lhs_table'=> 'acl_roles', 'lhs_key' => 'id',
							  'rhs_module'=> 'Users', 'rhs_table'=> 'users', 'rhs_key' => 'id',
							  'relationship_type'=>'many-to-many',
							  'join_table'=> 'acl_roles_users', 'join_key_lhs'=>'role_id', 'join_key_rhs'=>'user_id'),  

	),
);












$dictionary['UserSignature'] = array(
	'table' => 'users_signatures',
	'fields' => array(
		'id' => array(
			'name'		=> 'id',
			'vname'		=> 'LBL_ID',
			'type'		=> 'id',
			'required'	=> true,
		),
		'date_entered' => array (
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			'required'=>true,
		),
		'date_modified' => array (
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required'=>true,
		),
		'deleted' => array (
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'reportable'=>false,
		),
		'user_id' => array(
			'name' => 'user_id',
			'vname' => 'LBL_USER_ID',
			'type' => 'varchar',
			'len' => 36,
		),  
		'name' => array(
			'name' => 'name',
			'vname' => 'LBL_SUBJECT',
			'type' => 'varchar',
			'required' => false,
			'len' => '255',
		),
		'signature' => array(
			'name' => 'signature',
			'vname' => 'LBL_SIGNATURE',
			'type' => 'text',
			'reportable' => false,
		),
		'signature_html' => array(
			'name' => 'signature_html',
			'vname' => 'LBL_SIGNATURE_HTML',
			'type' => 'text',
			'reportable' => false,
		),

	),
	'indices' => array(
		array(
			'name' => 'users_signaturespk',
			'type' =>'primary',
			'fields' => array('id')
		),
		array(
			'name' => 'idx_user_id',
			'type' => 'index',
			'fields' => array('user_id')
		)
	),
);

?>
