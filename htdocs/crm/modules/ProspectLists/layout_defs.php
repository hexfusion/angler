<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for ProspectLists
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
 
// $Id: layout_defs.php,v 1.21 2006/06/23 00:29:00 wayne Exp $
	
$layout_defs['ProspectLists'] = array(
	// list of what Subpanels to show in the DetailView 
	'subpanel_setup' => array(
        'prospects' => array(
			'order' => 10,
			'sort_by' => 'last_name',
			'sort_order' => 'asc',
			'module' => 'Prospects',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'prospects',
			'title_key' => 'LBL_PROSPECTS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class'=>'SubPanelTopSelectButton','mode'=>'MultiSelect'),



			),
		),
        'contacts' => array(
			'order' => 20,
			'module' => 'Contacts',
			'sort_by' => 'last_name, first_name',
			'sort_order' => 'asc',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'contacts',
			'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class'=>'SubPanelTopSelectButton','mode'=>'MultiSelect'),



			),
		),
        'leads' => array(
			'order' => 30,
			'module' => 'Leads',
			'sort_by' => 'last_name, first_name',
			'sort_order' => 'asc',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'leads',
			'title_key' => 'LBL_LEADS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class'=>'SubPanelTopSelectButton','mode'=>'MultiSelect'),



			),
		),
        'users' => array(
			'order' => 40,
			'module' => 'Users',
			'sort_by' => 'name',
			'sort_order' => 'asc',
			'subpanel_name' => 'ForProspectLists',
			'get_subpanel_data' => 'users',
			'title_key' => 'LBL_USERS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class'=>'SubPanelTopSelectButton','mode'=>'MultiSelect'),



			),
		),
	),
);
?>
