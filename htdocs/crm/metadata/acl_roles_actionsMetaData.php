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
$dictionary['acl_roles_actions'] = array ( 

	'table' => 'acl_roles_actions',

	'fields' => array (
		array (
			'name' => 'id',
			'type' => 'varchar',
			'len' => '36',
		),
		array (
			'name' => 'role_id',
			'type' => 'varchar',
			'len' => '36',
		),
		array (
			'name' => 'action_id',
			'type' => 'varchar',
			'len' => '36',
		),
		array (
			'name' => 'access_override',
			'type' => 'int',
			'len' => '3',
			'required' => false,
		)
      , array ('name' => 'date_modified','type' => 'datetime'),
		array (
			'name' => 'deleted',
			'type' => 'bool',
			'len' => '1',
			'default' => '0'
		),
	),
	
	'indices' => array (
		array (
			'name' => 'acl_roles_actionspk',
			'type' => 'primary',
			'fields' => array ( 'id' )
		),
		array (
			'name' => 'idx_acl_role_id',
			'type' => 'index',
			'fields' => array ('role_id')
		),
		array (
			'name' => 'idx_acl_action_id',
			'type' => 'index',
			'fields' => array ('action_id')
		),
		 array('name' => 'idx_aclrole_action', 'type'=>'alternate_key', 'fields'=>array('role_id','action_id'))
	),
	'relationships' => array ('acl_roles_actions' => array('lhs_module'=> 'ACLRoles', 'lhs_table'=> 'acl_role', 'lhs_key' => 'id',
							  'rhs_module'=> 'ACLActions', 'rhs_table'=> 'acl_action', 'rhs_key' => 'id',
							  'relationship_type'=>'many-to-many',
							  'join_table'=> 'acl_roles_actions', 'join_key_lhs'=>'role_id', 'join_key_rhs'=>'action_id')),
	
)
                                  
?>
