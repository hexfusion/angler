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

$dictionary['product_bundle_note'] = array (
	'table' => 'product_bundle_note',
	'fields' => array (
       array('name' =>'id', 'type' =>'varchar', 'len'=>'36')
      , array ('name' => 'date_modified','type' => 'datetime')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required' => true,)
      , array('name' =>'bundle_id', 'type' =>'varchar', 'len'=>'36', )
      , array('name' =>'note_id', 'type' =>'varchar', 'len'=>'36', )
      , array('name' =>'note_index', 'type' =>'int', 'len'=>'11', 'default'=>'0', 'required' => true,)      
	),
	'indices' => array (
       array('name' =>'prod_bundl_notepk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_pbn_bundle', 'type' =>'index', 'fields'=>array('bundle_id'))
      , array('name' =>'idx_pbn_note', 'type' =>'index', 'fields'=>array('note_id'))
      , array('name' =>'idx_pbn_pb_nb', 'type'=>'alternate_key', 'fields'=>array('note_id','bundle_id'))
	),
	'relationships' => array ('product_bundle_note' => array('lhs_module'=> 'ProductBundles', 'lhs_table'=> 'product_bundles', 'lhs_key' => 'id',
		'rhs_module'=> 'ProductBundleNotes', 'rhs_table'=> 'product_bundle_note', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'product_bundle_note', 'join_key_lhs'=>'bundle_id', 'join_key_rhs'=>'note_id'))		
);
?>
