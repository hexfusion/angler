<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Subpanel Layout definition for Leads
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

// $Id: default.php,v 1.10 2006/08/03 22:12:05 jenny Exp $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Accounts'),
	),

	'where' => '',
	
	

	'list_fields' => array(
		'first_name'=>array(
		 	'usage' => 'query_only',
		),
		'last_name'=>array(
		 	'usage' => 'query_only',
		),
		'name'=>array(
			'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
            'sort_order' => 'asc',
            'sort_by' => 'last_name',
		 	'module' => 'Leads',
			'width' => '20%',
		),
		'refered_by'=>array(
	 		'vname' => 'LBL_LIST_REFERED_BY',
			'width' => '13%',
		),
		'lead_source'=>array(
	 		'vname' => 'LBL_LIST_LEAD_SOURCE',
			'width' => '13%',
		),
		'phone_work'=>array(
	 		'vname' => 'LBL_LIST_PHONE',
			'width' => '10%',
		),
		'email1'=>array(
	 		'vname' => 'LBL_LIST_EMAIL_ADDRESS',
			'width' => '10%',
			'widget_class' => 'SubPanelEmailLink',
		),
		'lead_source_description'=>array(
	 		'name' => 'lead_source_description',
	 		'vname' => 'LBL_LIST_LEAD_SOURCE_DESCRIPTION',
			'width' => '26%',
			'sortable'=>false,
		),
		'assigned_user_name' => array (
			'name' => 'assigned_user_name',
			'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
		),
		'edit_button'=>array(
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Leads',
			'width' => '4%',
		),
		'remove_button'=>array(
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Leads',
	 		'width' => '4%',
		),
	),
);

?>
