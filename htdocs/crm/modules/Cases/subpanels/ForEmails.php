<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Subpanel Layout definition for Cases
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

// $Id: ForEmails.php,v 1.4 2006/08/22 00:53:43 majed Exp $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Cases'),
	),

	'where' => '',
	
	

	'list_fields' => array(
		'case_number'=>array(
	 		'vname' => 'LBL_LIST_NUMBER',
			'width' => '6%',
		),
		
		'name'=>array(
	 		'vname' => 'LBL_LIST_SUBJECT',
			'widget_class' => 'SubPanelDetailViewLink',
		 	'width' => '30%',
		),
		'assigned_user_name'=>array(
	 		'vname' => 'LBL_LIST_ASSIGNED',
			'widget_class' => 'SubPanelDetailViewLink',
		 	'width' => '30%',
		),
		'account_name'=>array(
	 		'module' => 'Accounts',
			'widget_class' => 'SubPanelDetailViewLink',
	 		'vname' => 'LBL_LIST_ACCOUNT_NAME',
			'width' => '30%',
		),
		'status'=>array(
	 		'vname' => 'LBL_LIST_STATUS',
			'width' => '10%',
		),
		'edit_button'=>array(
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Cases',
			'width' => '4%',
		),
		'remove_button'=>array(
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Cases',
			'width' => '5%',
		),
	),
);

?>
