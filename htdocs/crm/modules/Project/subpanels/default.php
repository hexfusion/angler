<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Subpanel Layout definition for Projects
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

// $Id: default.php,v 1.7 2006/06/06 17:58:33 majed Exp $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Accounts'),
	),

	'where' => '',
	
	
	'fill_in_additional_fields'=>true,
	'list_fields' => array(
		'name'=>array(
	 		'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '35%',
		),
		'assigned_user_name'=>array(
	 		'vname' => 'LBL_LIST_ASSIGNED_USER_ID',
			'widget_class' => 'SubPanelDetailViewLink',
	 		'module' => 'Users',
	 		'target_record_key' => 'assigned_user_id',
 		 	'target_module' => 'Users',
			'width' => '15%',
			 'sortable'=>false,	
		),
		'total_estimated_effort'=>array(
	 		'vname' => 'LBL_LIST_TOTAL_ESTIMATED_EFFORT',
			'width' => '25%',
			 'sortable'=>false,	
		),
		'total_actual_effort'=>array(
	 		'vname' => 'LBL_LIST_TOTAL_ACTUAL_EFFORT',
			'width' => '25%',
			 'sortable'=>false,	
		),
	),
);

?>
