<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for Contacts
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
 
// $Id: layout_defs.php,v 1.18 2006/06/23 00:31:23 wayne Exp $

$layout_defs['Emails'] = array(
	// list of what Subpanels to show in the DetailView 
	'subpanel_setup' => array(
		'notes' => array(
			'order' => 5,
			'sort_order' => 'asc',
			'sort_by'	=> 'name',
			'subpanel_name' => 'default',
			'get_subpanel_data' => 'notes',
			'title_key' => 'LBL_NOTES_SUBPANEL_TITLE',
			'module' => 'Notes',
			'top_buttons' => array(),
		),		
        'accounts' => array(
			'order' => 10,
			'module' => 'Accounts',
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'subpanel_name' => 'ForEmails',
			'get_subpanel_data' => 'accounts',
			'add_subpanel_data' => 'account_id',
			'title_key' => 'LBL_ACCOUNTS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateButton'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),
        'contacts' => array(
			'order' => 20,
			'module' => 'Contacts',
			'sort_order' => 'asc',
			'sort_by' => 'last_name, first_name',
			'subpanel_name' => 'ForEmails',
			'get_subpanel_data' => 'contacts',
			'add_subpanel_data' => 'contact_id',
			'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateButton'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),
        'opportunities' => array(
			'order' => 25,
			'module' => 'Opportunities',
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'subpanel_name' => 'ForEmails',
			'get_subpanel_data' => 'opportunities',
			'add_subpanel_data' => 'opportunity_id',
			'title_key' => 'LBL_OPPORTUNITY_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateButton'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),
        'leads' => array(
			'order' => 30,
			'module' => 'Leads',
			'sort_order' => 'asc',
			'sort_by' => 'last_name, first_name',
			'subpanel_name' => 'ForEmails',
			'get_subpanel_data' => 'leads',
			'add_subpanel_data' => 'lead_id',
			'title_key' => 'LBL_LEADS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateButton'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),
        'cases' => array(
			'order' => 40,
			'module' => 'Cases',
			'sort_order' => 'desc',
			'sort_by' => 'case_number',
			'subpanel_name' => 'ForEmails',
			'get_subpanel_data' => 'cases',
			'add_subpanel_data' => 'case_id',
			'title_key' => 'LBL_CASES_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateButton'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),		
        'users' => array(
			'order' => 50,
			'module' => 'Users',
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'subpanel_name' => 'ForEmails',
			'get_subpanel_data' => 'users',
			'add_subpanel_data' => 'user_id',
			'title_key' => 'LBL_USERS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),
        'bugs' => array(
			'order' => 60,
			'module' => 'Bugs',
			'sort_order' => 'desc',
			'sort_by' => 'bug_number',
			'subpanel_name' => 'ForEmails',
			'get_subpanel_data' => 'bugs',
			'add_subpanel_data' => 'bug_id',
			'title_key' => 'LBL_BUGS_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateButton'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),

















        'project' => array(
			'order' => 80,
			'module' => 'Project',
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'subpanel_name' => 'ForEmails',
			'get_subpanel_data' => 'project',
			'add_subpanel_data' => 'project_id',
			'title_key' => 'LBL_PROJECT_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateButton'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),
        'projecttask' => array(
			'order' => 90,
			'module' => 'ProjectTask',
			'sort_order' => 'desc',
			'sort_by' => 'date_due',
			'subpanel_name' => 'ForEmails',
			'get_subpanel_data' => 'projecttask',
			'add_subpanel_data' => 'project_task_id',
			'title_key' => 'LBL_PROJECT_TASK_SUBPANEL_TITLE',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateButton'),
				array('widget_class' => 'SubPanelTopSelectButton', 'mode'=>'MultiSelect')
			),
		),

	),
);
?>
