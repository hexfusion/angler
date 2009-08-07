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

$dictionary['SchedulersJobs'] = array('table' => 'schedulers_times',
	'fields' => array (
		'id' => array (
			'name' => 'id',
			'vname' => 'LBL_NAME',
			'type' => 'id',
			'len' => '36',
			'required' => true,
			'reportable'=>false,
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
		'scheduler_id' => array (
			'name' => 'scheduler_id',
			'vname' => 'LBL_SCHEDULER_ID',
			'type' => 'id',
			'db_type' => 'varchar',
			'len' => 36,
			'required' => true,
			'reportable' => false,
		),
		'execute_time' => array (
			'name' => 'execute_time',
			'vname' => 'LBL_EXECUTE_TIME',
			'type' => 'datetime',
			'required' => true,
			'reportable' => true,
		),
		'status' => array (
			'name' => 'status',
			'vname' => 'LBL_STATUS',
			'type' => 'varchar',
			'len' => '25',
			'required' => true,
			'reportable' => true,
			'default' => 'ready',
		),
	),
	'indices' => array (
		array(
			'name' =>'schedulers_timespk',
			'type' =>'primary',
			'fields' => array(
				'id'
			)
		),
		array(
		'name' =>'idx_scheduler_id',
		'type'=>'index',
		'fields' => array(
			'scheduler_id',
			'execute_time',
			)
		),
	),
);


?>
