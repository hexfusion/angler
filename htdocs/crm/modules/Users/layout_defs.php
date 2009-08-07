<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for Users
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
 
// $Id: layout_defs.php,v 1.11 2006/06/06 17:58:54 majed Exp $

$layout_defs['Users'] = array(
	// default subpanel provided by this SugarBean
	'subpanel_setup' => array(
        'aclroles' => array(
			'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'ACLRoles'),),
			'order' => 20,
			'sort_by' => 'name',
			'sort_order' => 'asc',
			'module' => 'ACLRoles',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'aclroles',
			'add_subpanel_data' => 'role_id',
			'refresh_page'=>1,
			'title_key' => 'LBL_ROLES_SUBPANEL_TITLE',
		),
	),
	'default_subpanel_define' => array(
		'subpanel_title' => 'LBL_DEFAULT_SUBPANEL_TITLE',
		'sort_by' => 'name',
		'sort_order' => 'asc',
		'top_buttons' => array(
			array('widget_class' => 'SubPanelTopCreateButton'),
			array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Users', 'mode' => 'MultiSelect'),
		),
		'list_fields' => array(
			'Users' => array(
				'columns' => array(
					array(
						'name' => 'first_name',
			 		 	'usage' => 'query_only',
					),
					array(
						'name' => 'last_name',
			 		 	'usage' => 'query_only',
					),
					array(
						'name' => 'name',
						'vname' => 'LBL_LIST_NAME',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'module' => 'Users',
		 		 		'width' => '25%',
					),
					array(
						'name' => 'user_name',
						'vname' => 'LBL_LIST_USER_NAME',
						'width' => '25%',
					),
					array(
						'name'=>'email1',
						'vname' => 'LBL_LIST_EMAIL',
						'width' => '25%',
					),
					array (
						'name' => 'phone_work',
						'vname' => 'LBL_LIST_PHONE',
						'width' => '21%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelRemoveButton',
			 		 	'module' => 'Users',
						'width' => '4%',
						'linked_field' => 'users',
					),
				),
			),
		),
	),
);
$layout_defs['UserRoles'] = array(
	// sets up which panels to show, in which order, and with what linked_fields
	'subpanel_setup' => array(
        'aclroles' => array(
			'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'ACLRoles', 'mode' => 'MultiSelect'),),
			'order' => 20,
			'sort_by' => 'name',
			'sort_order' => 'asc',
			'module' => 'ACLRoles',
			'refresh_page'=>1,
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'aclroles',
			'add_subpanel_data' => 'role_id',
			'title_key' => 'LBL_ROLES_SUBPANEL_TITLE',
		),
	),
	);
global $current_user;
if(is_admin($current_user)){
	$layout_defs['Users']['subpanel_setup']['aclroles']['subpanel_name'] = 'admin';
	$layout_defs['UserRoles']['subpanel_setup']['aclroles']['subpanel_name'] = 'admin';
}else{
	
	$layout_defs['Users']['subpanel_setup']['aclroles']['top_buttons'] = array();
	
	$layout_defs['UserRoles']['subpanel_setup']['aclroles']['top_buttons'] = array();
}
?>
