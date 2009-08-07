<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Subpanel Layout definition for Accounts
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

// $Id: ForHistory.php,v 1.8 2006/06/06 17:58:22 majed Exp $

$subpanel_layout = array(
	//Removed button because this layout def is a component of
	//the activities sub-panel.

	'where' => '',
					
					
	'list_fields' => array(
		'object_image'=>array(
			'widget_class' => 'SubPanelIcon',
 		 	'width' => '2%',
 		 	'image2'=>'attachment',
 		 	'image2_url_field'=>'file_url'
		),
		'name'=>array(
			 'vname' => 'LBL_LIST_SUBJECT',
			 'widget_class' => 'SubPanelDetailViewLink',
			 'width' => '30%',
		),
		'status'=>array(
			 'widget_class' => 'SubPanelActivitiesStatusField',
			 'vname' => 'LBL_LIST_STATUS',
			 'width' => '15%',
			 'force_exists'=>true //this will create a fake field in the case a field is not defined
			 
		),
		'contact_name'=>array(
			 'widget_class'			=> 'SubPanelDetailViewLink',
			 'target_record_key'	=> 'contact_id',
			 'target_module'		=> 'Contacts',
			 'module'				=> 'Contacts',
			 'vname'				=> 'LBL_LIST_CONTACT',
			 'width'				=> '11%',
			 'sortable'=>false,
		),
		
		'date_modified'=>array(
			 'vname' => 'LBL_LIST_DATE_MODIFIED',
			 'width' => '10%',
		),
		'assigned_user_name' => array (
			'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
			 'force_exists'=>true //this will create a fake field since this field is not defined
		),
		'assigned_user_owner' => array (
			 'force_exists'=>true, //this will create a fake field since this field is not defined
			'usage'=>'query_only'
		),
		'assigned_user_mod' => array (
			 'force_exists'=>true, //this will create a fake field since this field is not defined
			'usage'=>'query_only'
		),
		'edit_button'=>array(
			 'widget_class' => 'SubPanelEditButton',
			 'width' => '2%',
		),
		'remove_button'=>array(
			 'widget_class' => 'SubPanelRemoveButton',
			 'width' => '2%',
		),
		'file_url'=>array(
			'usage'=>'query_only'
			),
		'filename'=>array(
			'usage'=>'query_only'
			),
				
	),
);		
?>
