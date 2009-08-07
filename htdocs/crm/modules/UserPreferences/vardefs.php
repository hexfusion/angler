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

$GLOBALS['dictionary']['UserPreference'] = array('table' => 'user_preferences',
'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
  ),
  'category' => 
  array (
    'name' => 'category',
    'type' => 'varchar',
    'len' => 50,
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'type' => 'bool',
    'default' => '0',
    'required'=>true,
  ),
  'date_entered' => 
  array (
    'name' => 'date_entered',
    'type' => 'datetime',
    'required' => true,
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'type' => 'datetime',
    'required' => true,
  ),
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
  ),
  'assigned_user_name' => 
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_ASSIGNED_TO_NAME',
    'type' => 'varchar',
    'reportable'=>false,
    'massupdate' => false,
    'source'=>'nondb',
    'table' => 'users',
  ),
  'contents' => 
  array (
    'name' => 'contents',
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'isnull' => true,
  ),
),
 

'indices' => array (
       array('name' =>'userpreferencespk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_userprefnamecat', 'type'=>'index', 'fields'=>array('assigned_user_id','category')),
      )
);
?>
