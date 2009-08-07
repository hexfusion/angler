<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for Cases
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

// $Id: ForQueues.php,v 1.7 2006/06/06 17:58:20 majed Exp $

//$layout_defs['ForQueues'] = array(
//	'top_buttons' => array(
//			array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Queues'),
//		),
//);


$subpanel_layout = array(
	'top_buttons' => array(
			array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Queues'),
	),
	'where' => "",

	'fill_in_additional_fields'=>true,
	'list_fields' => array(
/*		'mass_update' => array (
			
		),
*/		'object_image'=>array(
			'widget_class' => 'SubPanelIcon',
 		 	'width' => '2%',
		),
		'name'=>array(
			 'vname' => 'LBL_LIST_SUBJECT',
			 'widget_class' => 'SubPanelDetailViewLink',
			 'width' => '68%',
		),
		'case_name'=>array(
			 'widget_class' => 'SubPanelDetailViewLink',
			 'target_record_key' => 'case_id',
			 'target_module' => 'Cases',
			 'module' => 'Cases',
			 'vname' => 'LBL_LIST_CASE',
			 'width' => '20%',
			 'force_exists'=>true,
			 'sortable'=>false,
		),
		'contact_id'=>array(
			'usage'=>'query_only',
			'force_exists'=>true,
		)	,
/*		'parent_name'=>array(
			 'vname' => 'LBL_LIST_RELATED_TO',		
			 'width' => '22%',
			 'target_record_key' => 'parent_id',
			 'target_module_key'=>'parent_type',
			 'widget_class' => 'SubPanelDetailViewLink',
			  'sortable'=>false,	
		),*/
		'date_modified'=>array(
			'vname' => 'LBL_DATE_MODIFIED',
			 'width' => '10%',
		),
/*		'edit_button'=>array(
			 'widget_class' => 'SubPanelEditButton',
			 'width' => '2%',
		),
		'remove_button'=>array(
			 'widget_class' => 'SubPanelRemoveButton',
			 'width' => '2%',
		),
		'parent_id'=>array(
			'usage'=>'query_only',
		),
		'parent_type'=>array(
			'usage'=>'query_only',
		),
		'filename'=>array(
			'usage'=>'query_only',
			'force_exists'=>true
			),		
*/		
	),
);		

?>
