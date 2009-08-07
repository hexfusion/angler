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
$searchFields['Bugs'] = 
	array (
		'name' => array( 'query_type'=>'default'),
        'status'=> array('query_type'=>'default', 'options' => 'bug_status_dom', 'template_var' => 'STATUS_OPTIONS'),
        'priority'=> array('query_type'=>'default', 'options' => 'bug_priority_dom', 'template_var' => 'PRIORITY_OPTIONS'),
		'found_in_release'=> array('query_type'=>'default','options' => 'bug_release_dom', 'template_var' => 'RELEASE_OPTIONS', 'options_add_blank' => true),
        'resolution'=> array('query_type'=>'default', 'options' => 'bug_resolution_dom', 'template_var' => 'RESOLUTION_OPTIONS'),
		'bug_number'=> array('query_type'=>'default','operator'=>'in'),
		'current_user_only'=> array('query_type'=>'default','db_field'=>array('assigned_user_id'),'my_items'=>true),
		'assigned_user_id'=> array('query_type'=>'default'),
        'type'=> array('query_type'=>'default', 'options' => 'bug_type_dom', 'template_var' => 'TYPE_OPTIONS', 'options_add_blank' => true),
	);
?>
