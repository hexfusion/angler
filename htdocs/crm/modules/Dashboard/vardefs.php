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
$dictionary['Dashboard'] = array('table' => 'dashboards',
'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
    'comment' => 'Unique identifier'
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_CREATED_BY',
    'type' => 'bool',
    'required'=>true,
    'reportable'=>false,
    'comment' => 'Record deletion indicator'
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
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
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
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'reportable'=>true,
    'comment' => 'User ID assigned to record'
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
    'dbType' => 'id',
    'comment' => 'User ID who created record'
  ),










  'name' => 
  array (
    'name' => 'name',
    'type' => 'varchar',
    'len' => '100',
    'vname' => 'LBL_TITLE',
    'comment' => 'Name (title) of the dashboard report object'
  ),
  'description' => 
  array (
    'name' => 'description',
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'comment' => 'Description of the dashboard object'
  ),
  'content' => 
  array (
    'name' => 'content',
    'type' => 'text',
    'vname' => 'LBL_CONTENT',
  ),













  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'dashboards_created_by',
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
    'relationship' => 'dashboards_modified_user',
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
    'relationship' => 'dashboards_assigned_user',
    'vname' => 'LBL_ASSIGNED_TO_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
)
,
 'relationships' => array (

  'dashboards_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Dashboards', 'rhs_table'=> 'dashboards', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')

   ,'dashboards_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Dashboards', 'rhs_table'=> 'dashboards', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'dashboards_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Dashboards', 'rhs_table'=> 'dashboards', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')







)

                                                      , 'indices' => array (
       array('name' =>'dashboardspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_dashboard_name', 'type'=>'index', 'fields'=>array('name','deleted'))
                                                      )

                            );
?>
