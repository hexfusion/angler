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
$dictionary['files'] = array(
	'table' => 'files',
	'fields' => array(
		array(
			'name' =>'id',
			'type' =>'varchar',
			'len'=>'36'
		),
		array(
			'name' =>'name',
			'type' =>'varchar',
			'len'=>'36',
		),
		array(
			'name' =>'content',
			'type' =>'blob'
		),
		array(
			'name' => 'date_modified',
			'type' => 'datetime',
			'len' => '',
		),
		array(
			'name' =>'deleted',
			'type' =>'bool',
			'len'=>'1',
			'default'=>'0',
			'required'=>true
		),
		array(
			'name' => 'date_entered',
			'type' => 'datetime',
			'len' => '',
			'required' => true
		),
		array(
			'name' =>'assigned_user_id',
			'type' =>'varchar',
			'len'=>'36',
		),
	),
	'indices' => array(
		array(
			'name' => 'filespk',
			'type' => 'primary',
			'fields' => array('id')
		),
		array(
			'name' => 'idx_cont_owner_id_and_name',
			'type' =>'index',
			'fields' => array(
				'assigned_user_id',
				'name',
				'deleted')
		),
	),
);
?>
