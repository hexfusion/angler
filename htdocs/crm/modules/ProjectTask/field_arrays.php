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
 * $Id: field_arrays.php,v 1.3 2006/06/06 17:58:32 majed Exp $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['ProjectTask'] = array ('column_fields' => array(
		'id',
		'date_entered',
		'date_modified',
		'assigned_user_id',
		'modified_user_id',
		'created_by',



		'name',
		'status',
		'date_due',
		'time_due',
		'date_start',
		'time_start',
		'parent_id',
		'priority',
		'description',
		'order_number',
		'task_number',
		'depends_on_id',
		'milestone_flag',
		'estimated_effort',
		'actual_effort',
		'utilization',
		'percent_complete',
		'deleted',
	),
        'list_fields' =>  array(
		'id',
		'parent_id',
		'parent_name',
		'priority',
		'name',
		'order_number',
		'date_start',
		'date_due',
		'percent_complete',
		'status',
		'assigned_user_id',
		'depends_on_id',
		'assigned_user_name',
	),
    'required_fields' =>  array('name'=>1, 'parent_id'=>2,),
);
?>
