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
 
// $Id: layout_defs.php,v 1.20 2006/06/23 00:33:01 wayne Exp $

$layout_defs['Calls'] = array(
	// sets up which panels to show, in which order, and with what linked_fields
	'subpanel_setup' => array(
        'contacts' => array(
			'top_buttons' => array(),
			'order' => 10,
			'module' => 'Contacts',
			'sort_order' => 'asc',
			'sort_by' => 'last_name, first_name',
			'subpanel_name' => 'ForCalls',
			'get_subpanel_data' => 'contacts',
			'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
		),
        'users' => array(
			'top_buttons' => array(),
			'order' => 20,
			'module' => 'Users',
			'sort_order' => 'asc',
			'sort_by' => 'full_name',
			'subpanel_name' => 'ForCalls',
			'get_subpanel_data' => 'users',
			'title_key' => 'LBL_USERS_SUBPANEL_TITLE',
		),
		'history' => array(
			'order' => 30,
			'title_key' => 'LBL_HISTORY_SUBPANEL_TITLE',
			'type' => 'collection',
			'subpanel_name' => 'history',   //this values is not associated with a physical file.
			'sort_order' => 'desc',
			'sort_by' => 'date_modified',
			'header_definition_from_subpanel'=> 'calls',
			'module'=>'History',
			'top_buttons' => array(
				array('widget_class' => 'SubPanelTopCreateNoteButton'),
			),	
			'collection_list' => array(		
				'notes' => array(
					'module' => 'Notes',
					'subpanel_name' => 'ForHistory',
					'get_subpanel_data' => 'notes',
				),		
			),	
		), /* end history subpanel def */
	),
);
?>
