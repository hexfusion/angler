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
$dictionary['Relationship'] = 

	array('table' => 'relationships'
         ,'fields' => array (
  			'id' => 
  			array (
    			'name' => 'id',
    			'vname' => 'LBL_ID',
    			'type' => 'id',
    			'required'=>true,
  			),
  			
  			'relationship_name' => 
  			array (
    			'name' => 'relationship_name',
    			'vname' => 'LBL_RELATIONSHIP_NAME',
    			'type' => 'varchar',
    			'required'=>true,
    			'len' => 150
  			),
  			'lhs_module' => 
  			array (
    			'name' => 'lhs_module',
    			'vname' => 'LBL_LHS_MODULE',
    			'type' => 'varchar',
    			'required'=>true,
    			'len' => 100
  			),
  			'lhs_table' => 
  			array (
    			'name' => 'lhs_table',
    			'vname' => 'LBL_LHS_TABLE',
    			'type' => 'varchar',
    			'required'=>true,
    			'len' => 64
  			),
  			'lhs_key' => 
  			array (
    			'name' => 'lhs_key',
    			'vname' => 'LBL_LHS_KEY',
    			'type' => 'varchar',
    			'required'=>true,
    			'len' => 64
  			),
  			'rhs_module' => 
  			array (
    			'name' => 'rhs_module',
    			'vname' => 'LBL_RHS_MODULE',
    			'type' => 'varchar',
    			'required'=>true,
    			'len' => 100
  			),
  			'rhs_table' => 
  			array (
    			'name' => 'rhs_table',
    			'vname' => 'LBL_RHS_TABLE',
    			'type' => 'varchar',
    			'required'=>true,
    			'len' => 64
  			),
  			'rhs_key' => 
  			array (
    			'name' => 'rhs_key',
    			'vname' => 'LBL_RHS_KEY',
    			'type' => 'varchar',
    			'required'=>true,
    			'len' => 64
  			),
  			'join_table' => 
  			array (
    			'name' => 'join_table',
    			'vname' => 'LBL_JOIN_TABLE',
    			'type' => 'varchar',
    			'len' => 64
  			),
  			'join_key_lhs' => 
  			array (
    			'name' => 'join_key_lhs',
    			'vname' => 'LBL_JOIN_KEY_LHS',
    			'type' => 'varchar',
    			'len' => 64
  			),
  			'join_key_rhs' => 
  			array (
    			'name' => 'join_key_rhs',
    			'vname' => 'LBL_JOIN_KEY_RHS',
    			'type' => 'varchar',
    			'len' => 64
  			),
  			'relationship_type' => 
  			array (
    			'name' => 'relationship_type',
    			'vname' => 'LBL_RELATIONSHIP_TYPE',
    			'type' => 'varchar',
    			'len' => 64
  			),
  			'relationship_role_column' => 
  			array (
    			'name' => 'relationship_role_column',
    			'vname' => 'LBL_RELATIONSHIP_ROLE_COLUMN',
    			'type' => 'varchar',
    			'len' => 64
  			),
  			'relationship_role_column_value' => 
  			array (
    			'name' => 'relationship_role_column_value',
    			'vname' => 'LBL_RELATIONSHIP_ROLE_COLUMN_VALUE',
    			'type' => 'varchar',
    			'len' => 50
  			),
  			'reverse' => 
  			array (
    			'name' => 'reverse',
    			'vname' => 'LBL_REVERSE',
    			'type' => 'bool',
    			'default' => '0'
  			),
  		 	'deleted' => 
  			array (
    			'name' => 'deleted',
    			'vname' => 'LBL_DELETED',
    			'type' => 'bool',
    			'reportable'=>false,
    			'default' => '0'
  			),
  			  			
	)
	, 'indices' => array (
       array('name' =>'relationshippk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_rel_name', 'type' =>'index', 'fields'=>array('relationship_name')),    
    )
);


?>
