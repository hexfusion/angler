<?php
/**
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
 */

 // $Id: vardef_meta_arrays.php,v 1.22 2006/08/22 18:56:15 awu Exp $
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

//holds various filter arrays for displaying vardef dropdowns
//You can add your own if you would like

$vardef_meta_array = array (

	'standard_display' => array(	
		'inclusion' =>	array(
		//end inclusion
		),			
		'exclusion' =>	array(	
			'type' => array('id'),
			'name' => array('parent_type', 'deleted'),
			'reportable' => array('false'),		
		//end exclusion
		),	
		'inc_override' => array(
			'type' => array('team_list'),	
		//end inc_override
		),	
		'ex_override' => array(
		//end ex_override
		)
	//end standard_display	
	),	
//////////////////////////////////////////////////////////////////	
	'normal_trigger' => array(
		'inclusion' =>	array(
		//end inclusion
		),	
		'exclusion' =>	array(	
			'type' => array('id', 'link', 'datetime', 'date'),
			'custom_type' => array('id', 'link', 'datetime', 'date'),
			'name' => array('assigned_user_name', 'parent_type', 'amount_backup', 'amount_usdollar', 'deleted','filename', 'file_mime_type', 'file_url'),
			'reportable' => array('false'),
			'source' => array('non-db'),
		//end exclusion
		),
		
		'inc_override' => array(
			'type' => array('team_list', 'assigned_user_name'),
		//end inc_override
		),
		'ex_override' => array(
			'name' => array('team_name', 'account_name'),
		//end ex_override
		)
	
	//end normal_trigger
	),
	//////////////////////////////////////////////////////////////////	
	'normal_date_trigger' => array(
		'inclusion' =>	array(
		//end inclusion
		),	
		'exclusion' =>	array(	
			'type' => array('id', 'link'),
			'custom_type' => array('id', 'link'),
			'name' => array('assigned_user_name', 'parent_type', 'amount_backup', 'amount_usdollar', 'deleted','filename', 'file_mime_type', 'file_url'),
			'reportable' => array('false'),
			'source' => array('non-db'),
		//end exclusion
		),
		
		'inc_override' => array(
			'type' => array('team_list', 'assigned_user_name'),
		//end inc_override
		),
		'ex_override' => array(
			'name' => array('team_name', 'account_name'),
		//end ex_override
		)
	
	//end normal_trigger
	),
//////////////////////////////////////////////////////////////////		
		'time_trigger' => array(
		'inclusion' =>	array(
		//end inclusion
		),	
		'exclusion' =>	array(	
			'type' => array('id', 'link', 'team_list', 'time'),
			'custom_type' => array('id', 'link', 'team_list', 'time'),
			'name' => array('parent_type', 'team_name', 'assigned_user_name', 'parent_type', 'amount_backup', 'amount_usdollar', 'deleted' ,'filename', 'file_mime_type', 'file_url'),
			'reportable' => array('true'),
			'source' => array('non-db'),
		//end exclusion
		),
		
		'inc_override' => array(
		//end inc_override
		),
		'ex_override' => array(
		//end ex_override
		)
	
	//end time_trigger
	),	
//////////////////////////////////////////////////////////////////			
	'action_filter' => array(
		'inclusion' =>	array(
		//end inclusion
		),	
		'exclusion' =>	array(	
			'type' => array('id', 'link', 'datetime', 'time'),
			'custom_type' => array('id', 'link', 'datetime', 'time'),
			'reportable' => array('false'),
			'source' => array('non-db'),
			'name' => array('created_by', 'parent_type', 'deleted', 'assigned_user_name', 'amount_backup', 'amount_usdollar', 'deleted' ,'filename', 'file_mime_type', 'file_url'),
		//end exclusion
		),
		
		'inc_override' => array(
			'type' => array('team_list'),
			'name' => array('assigned_user_id', 'time_start', 'date_start'),
		//end inc_override
		),
		'ex_override' => array(
			'name' => array('team_name', 'account_name'),
		//end ex_override
		)
	
	//end action_filter
	),
//////////////////////////////////////////////////////////////////	
	'rel_filter' => array(
		'inclusion' =>	array(
			'type' => array('link'),
		//end inclusion
		),	
		'exclusion' =>	array(	
		'name' => array('direct_reports', 'accept_status'),
		//end exclusion
		),
		
		'inc_override' => array(
			'name' => array('accounts', 'account', 'member_of'),
		//end inc_override
		),
		'ex_override' => array(
			'link_type' => array('one'),
			'name' => array('users'),
		//end ex_override
		)
	
	//end rel_filter
	),	
///////////////////////////////////////////////////////////	
	'template_filter' => array(
		'inclusion' =>	array(
		//end inclusion
		),	
		'exclusion' =>	array(	
			'type' => array('id', 'link'),
			'custom_type' => array('id', 'link'),
			'reportable' => array('false'),
			'source' => array('non-db'),
			'name' => array('created_by', 'parent_type', 'deleted', 'assigned_user_name', 'amount_backup', 'amount_usdollar', 'filename', 'file_mime_type', 'file_url'),
		//end exclusion
		),
		
		'inc_override' => array(
			'type' => array('team_list'),
			'name' => array('assigned_user_id', 'full_name'),
		//end inc_override
		),
		'ex_override' => array(
			'name' => array('team_name', 'account_name'),
		//end ex_override
		)
	
	//end template_filter
	),	
//////////////////////////////////////////////////////////////
	'alert_trigger' => array(
		'inclusion' =>	array(
		//end inclusion
		),	
		'exclusion' =>	array(	
			'type' => array('id', 'link', 'datetime', 'date'),
			'custom_type' => array('id', 'link', 'datetime', 'date'),
			'name' => array('assigned_user_name', 'parent_type', 'amount_backup', 'amount_usdollar', 'deleted', 'filename', 'file_mime_type', 'file_url'),
			'reportable' => array('false'),
			'source' => array('non-db'),
		//end exclusion
		),
		
		'inc_override' => array(
			'type' => array('team_list', 'assigned_user_name'),
			'name' => array('full_name'),
		//end inc_override
		),
		'ex_override' => array(
			'name' => array('team_name', 'account_name'),
		//end ex_override
		)
	
	//end alert_trigger
	),	
//////////////////////////////////////////////////////////////////	
	'template_rel_filter' => array(
		'inclusion' =>	array(
			'type' => array('link'),
		//end inclusion
		),	
		'exclusion' =>	array(	
		'name' => array('direct_reports'),
		//end exclusion
		),
		
		'inc_override' => array(
			'name' => array('accounts', 'account', 'member_of', 'assigned_user_link'),
		//end inc_override
		),
		'ex_override' => array(
			'link_type' => array('one'),
			'name' => array('users'),
		//end ex_override
		)
	
	//end template_rel_filter
	),		
);

?>
