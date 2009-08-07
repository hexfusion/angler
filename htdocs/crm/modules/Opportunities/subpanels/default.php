<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Subpanel Layout definition for Opportunities
 *
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

// $Id: default.php,v 1.7 2006/06/06 17:58:22 majed Exp $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Opportunities'),
	),

	'where' => '',
	
	

	'list_fields' => array(
		'name'=>array(
	 		'name' => 'name',
	 		'vname' => 'LBL_LIST_OPPORTUNITY_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '40%',
		),
		'account_name'=>array(
	 		'vname' => 'LBL_LIST_ACCOUNT_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
	 		'module' => 'Accounts',
			'width' => '31%',
			'target_record_key' => 'account_id',
			'target_module' => 'Accounts',
		),
		'sales_stage'=>array(
			'name' => 'sales_stage',
			'vname' => 'LBL_LIST_SALES_STAGE',
			'width' => '15%',
		),
		'date_closed'=>array(
			'name' => 'date_closed',
			'vname' => 'LBL_LIST_DATE_CLOSED',
			'width' => '15%',
		),
		'amount'=>array(
			'vname' => 'LBL_LIST_AMOUNT',
			'width' => '15%',
		),
	   	'assigned_user_name' => array (
			'name' => 'assigned_user_name',
		 	'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
	    ),
		'edit_button'=>array(
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Opportunities',
			'width' => '4%',
		),
		'amount_usdollar'=>array(
			'usage'=>'query_only',
		),
	),
);

?>
