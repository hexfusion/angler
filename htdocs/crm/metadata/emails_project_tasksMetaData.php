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
$dictionary['emails_project_task'] = array ('table' => 'emails_project_tasks',
	'fields' => array (
		array(	'name'		=> 'id',
				'type'		=> 'varchar',
				'len'		=> '36',
		),
		array(	'name'		=> 'email_id',
				'type'		=> 'varchar',	
				'len'		=> '36',
		),
		array(	'name'		=> 'project_task_id',
				'type'		=> 'varchar',
				'len'		=> '36',
		),
		array(	'name'		=> 'date_modified',
				'type'		=> 'datetime'
		),
		array(	'name'		=> 'deleted',
				'type'		=> 'bool',
				'len'		=> '1',
				'default'	=> '0',
				'required'	=> true
		),
	),
	'indices' => array (
	    	array('name' =>'emails_project_taskpk', 'type' =>'primary', 'fields'=>array('id')),
			array('name' =>'idx_ept_email', 'type' =>'index', 'fields'=>array('email_id')),
			array('name' =>'idx_ept_project_task', 'type' =>'index', 'fields'=>array('project_task_id')),
	),
/* added to support InboundEmail */
	'relationships' => array (
		'emails_project_task_rel' => array(
			'lhs_module'		=> 'Emails',
			'lhs_table'			=> 'emails',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'ProjectTask',
			'rhs_table'			=> 'project_task',
			'rhs_key'			=> 'id',
			'relationship_type'	=> 'many-to-many',
			'join_table'		=> 'emails_project_tasks',
			'join_key_lhs'		=> 'email_id',
			'join_key_rhs'		=> 'project_task_id'
		)
	)
);

?>
