<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Subpanel Layout definition for Contacts
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

// $Id: ForAccounts.php,v 1.2 2006/06/06 17:57:57 majed Exp $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Contacts'),
	),

	'where' => '',
	
	

	'list_fields' => array(
		'first_name'=>array(
			'name'=>'first_name',
			'usage' => 'query_only',
		),
		'last_name'=>array(
			'name'=>'last_name',
		 	'usage' => 'query_only',
		),
		'name'=>array(
			'name'=>'name',		
			'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
		 	'module' => 'Contacts',
			'width' => '43%',
		),
		'primary_address_city'=>array(
			'name'=>'primary_address_city',		
			'vname' => 'LBL_LIST_CITY',
			'width' => '20%',
		),
		'primary_address_state'=>array(
			'name'=>'primary_address_state',		
			'vname' => 'LBL_LIST_STATE',
			'width' => '10%',
		),
		'email1'=>array(
			'name'=>'email1',		
			'vname' => 'LBL_LIST_EMAIL',
			'widget_class' => 'SubPanelEmailLink',
			'width' => '30%',
		),
		'phone_work'=>array (
			'name'=>'phone_work',		
			'vname' => 'LBL_LIST_PHONE',
			'width' => '15%',
		),
		'edit_button'=>array(
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Contacts',
			'width' => '5%',
		),
		'remove_button'=>array(
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Contacts',
			'width' => '5%',
		),
	),
);		
?>
