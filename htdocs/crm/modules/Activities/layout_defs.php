<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for Activities
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

// $Id: layout_defs.php,v 1.21 2006/06/12 22:00:08 jacob Exp $

$layout_defs['Activities'] = array( // the key to the layout_defs must be the name of the module dir
	'default_subpanel_define' => array(
		'subpanel_title' => 'LBL_DEFAULT_SUBPANEL_TITLE',
		'top_buttons' => array(
			array('widget_class' => 'SubPanelTopCreateTaskButton'),
			array('widget_class' => 'SubPanelTopScheduleMeetingButton'),
			array('widget_class' => 'SubPanelTopScheduleCallButton'),
			array('widget_class' => 'SubPanelTopComposeEmailButton'),
		),
		'list_fields' => array(
			'Meetings' => array(
				'columns' => array(
					array(
//TODO remove name=nothing and make it safe
//TODO update layout editor to match new file structure
					
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelIcon',
			 		 	'module' => 'Meetings',
			 		 	'width' => '2%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelCloseButton',
			 		 	'module' => 'Meetings',
			 		 	'vname' => 'LBL_LIST_CLOSE',
			 		 	'width' => '6%',
					),
					array(
			 		 	'name' => 'name',
			 		 	'vname' => 'LBL_LIST_SUBJECT',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'width' => '30%',
					),
					array(
			 		 	'name' => 'status',
						'widget_class' => 'SubPanelActivitiesStatusField',
			 		 	'vname' => 'LBL_LIST_STATUS',
			 		 	'width' => '15%',
					),
					array(
			 		 	'name' => 'contact_name',
			 		 	'module' => 'Contacts',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'target_record_key' => 'contact_id',
			 		 	'target_module' => 'Contacts',
			 		 	'vname' => 'LBL_LIST_CONTACT',
			 		 	'width' => '11%',
					),
					array(
			 		 	'name' => 'parent_name',
			 		 	'module' => 'Meetings',
			 		 	'vname' => 'LBL_LIST_RELATED_TO',
			 		 	'width' => '22%',
					),
					array(
			 		 	'name' => 'date_start',
			 		 	//'db_alias_to' => 'the_date',
			 		 	'vname' => 'LBL_LIST_DUE_DATE',
			 		 	'width' => '10%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelEditButton',
			 		 	'module' => 'Meetings',
			 		 	'width' => '2%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelRemoveButton',
						'linked_field' => 'meetings',
			 		 	'module' => 'Meetings',
			 		 	'width' => '2%',
					),
				),
				'where' => "(meetings.status='Planned')",
				'order_by' => 'meetings.date_start',
			),
			'Tasks' => array(
				'columns' => array(
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelIcon',
			 		 	'module' => 'Tasks',
			 		 	'width' => '2%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelCloseButton',
			 		 	'module' => 'Tasks',
			 		 	'vname' => 'LBL_LIST_CLOSE',
			 		 	'width' => '6%',
					),
					array(
			 		 	'name' => 'name',
			 		 	'vname' => 'LBL_LIST_SUBJECT',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'width' => '30%',
					),
					array(
			 		 	'name' => 'status',
						'widget_class' => 'SubPanelActivitiesStatusField',
			 		 	'vname' => 'LBL_LIST_STATUS',
			 		 	'width' => '15%',
					),
					array(
			 		 	'name' => 'contact_name',
			 		 	'module' => 'Contacts',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'target_record_key' => 'contact_id',
			 		 	'target_module' => 'Contacts',
			 		 	'vname' => 'LBL_LIST_CONTACT',
			 		 	'width' => '11%',
					),
					array(
			 		 	'name' => 'parent_name',
			 		 	'module' => 'Tasks',
			 		 	'vname' => 'LBL_LIST_RELATED_TO',
			 		 	'width' => '22%',
					),
					array(
			 		 	'name' => 'date_start',
			 		 	//'db_alias_to' => 'the_date',
			 		 	'vname' => 'LBL_LIST_DUE_DATE',
			 		 	'width' => '10%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelEditButton',
			 		 	'module' => 'Tasks',
			 		 	'width' => '2%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelRemoveButton',
						'linked_field' => 'tasks',
			 		 	'module' => 'Tasks',
			 		 	'width' => '2%',
					),
				),
				'where' => "(tasks.status='Not Started' OR tasks.status='In Progress' OR tasks.status='Pending Input')",
				'order_by' => 'tasks.date_start',
			),
			'Calls' => array(
				'columns' => array(
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelIcon',
			 		 	'module' => 'Calls',
			 		 	'width' => '2%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelCloseButton',
			 		 	'module' => 'Calls',
			 		 	'vname' => 'LBL_LIST_CLOSE',
			 		 	'width' => '6%',
					),
					array(
			 		 	'name' => 'name',
			 		 	'vname' => 'LBL_LIST_SUBJECT',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'width' => '30%',
					),
					array(
			 		 	'name' => 'status',
						'widget_class' => 'SubPanelActivitiesStatusField',
			 		 	'vname' => 'LBL_LIST_STATUS',
			 		 	'width' => '15%',
					),
					array(
			 		 	'name' => 'contact_name',
			 		 	'module' => 'Contacts',
						'widget_class' => 'SubPanelDetailViewLink',
			 		 	'target_record_key' => 'contact_id',
			 		 	'target_module' => 'Contacts',
			 		 	'vname' => 'LBL_LIST_CONTACT',
			 		 	'width' => '11%',
					),
					array(
			 		 	'name' => 'parent_name',
			 		 	'module' => 'Calls',
			 		 	'vname' => 'LBL_LIST_RELATED_TO',
			 		 	'width' => '20%',
					),
					array(
			 		 	'name' => 'date_start',
			 		 	//'db_alias_to' => 'the_date',
			 		 	'vname'=>'LBL_LIST_DUE_DATE',
			 		 	'width' => '22%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelEditButton',
			 		 	'module' => 'Calls',
			 		 	'width' => '2%',
					),
					array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelRemoveButton',
						'linked_field' => 'calls',
			 		 	'module' => 'Calls',
			 		 	'width' => '2%',
					),
				),
				'where' => "(calls.status='Planned')",
				'order_by' => 'calls.date_start',
			),
		),
	),
);
?>
