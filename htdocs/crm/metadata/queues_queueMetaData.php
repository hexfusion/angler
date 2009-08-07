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
/*********************************************************************************
 * Description:
 * Created On: Sep 28, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/
$dictionary['queues_queue'] = array ('table' => 'queues_queue',
	'fields' => array (
		'id' => array (
			'name' => 'id',
			'vname' => 'LBL_QUEUES_QUEUE_ID',
			'type' => 'id',
			'required' => true,
			'reportable' => false,
		),
		'deleted' => array (
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'default' => '0',
			'reportable'=>false,
		),
		'date_entered' => array (
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			'required' => true,
		),
		'date_modified' => array (
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required' => true,
		),
		'queue_id' => array (
			'name' => 'queue_id',
			'vname' => 'LBL_QUEUE_ID',
			'type' => 'id',
			'required' => true,
			'reportable'=>false,
		),
		'parent_id' => array (
			'name' => 'parent_id',
			'vname' => 'LBL_PARENT_ID',
			'type' => 'id',
			'required' => true,
			'reportable'=>false,
		),
	),
	'indices' => array (
		array(
			'name' => 'queues_queuepk',
			'type' =>'primary',
			'fields' => array(
				'id'
			)
		),
		array(
		'name' =>'idx_queue_id',
		'type'=>'index',
		'fields' => array(
			'queue_id'
			)
		),
		array(
		'name' =>'idx_parent_id',
		'type'=>'index',
		'fields' => array(
			'parent_id'
			)
		),
		array(
		'name' => 'compidx_queue_id_parent_id',
		'type' => 'alternate_key',
		'fields' => array (
			'queue_id',
			'parent_id'
			),
		),
	), /* end indices */
	'relationships' => array (
		'child_queues_rel'	=> array(
			'lhs_module'		=> 'Queues',
			'lhs_table'			=> 'queues',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Queues',
			'rhs_table'			=> 'queues',
			'rhs_key'			=> 'id',
			'relationship_type' => 'many-to-many',
			'join_table'		=> 'queues_queue', 
			'join_key_lhs'		=> 'queue_id', 
			'join_key_rhs'		=> 'parent_id'
		),
		'parent_queues_rel' => array(
			'lhs_module'		=> 'Queues',
			'lhs_table'			=> 'queues',
			'lhs_key' 			=> 'id',
			'rhs_module'		=> 'Queues',
			'rhs_table'			=> 'queues',
			'rhs_key' 			=> 'id',
			'relationship_type' => 'many-to-many',
			'join_table'		=> 'queues_queue', 
			'join_key_rhs'		=> 'queue_id', 
			'join_key_lhs'		=> 'parent_id'			
		),
	), /* end relationships */
);

?>
