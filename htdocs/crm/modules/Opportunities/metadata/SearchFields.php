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
$searchFields['Opportunities'] = 
	array (
		'name' => array( 'query_type'=>'default'),
		'account_name'=> array('query_type'=>'default','db_field'=>array('accounts.name')),
		'amount'=> array('query_type'=>'default'),
		'next_step'=> array('query_type'=>'default'),
		'probability'=> array('query_type'=>'default'),
		'lead_source'=> array('query_type'=>'default', 'operator'=>'=', 'options' => 'lead_source_dom', 'template_var' => 'LEAD_SOURCE_OPTIONS'),
		'opportunity_type'=> array('query_type'=>'default', 'operator'=>'=', 'options' => 'opportunity_type_dom', 'template_var' => 'TYPE_OPTIONS'),
		'sales_stage'=> array('query_type'=>'default', 'operator'=>'=', 'options' => 'sales_stage_dom', 'template_var' => 'SALES_STAGE_OPTIONS', 'options_add_blank' => true),
		'current_user_only'=> array('query_type'=>'default','db_field'=>array('assigned_user_id'),'my_items'=>true),
		'assigned_user_id'=> array('query_type'=>'default'),
	);
?>
