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

// $Id: default.php,v 1.12 2006/06/20 19:32:32 awu Exp $
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelAddToProspectListButton','create'=>'true'),			
	),

	'where' => '',


	'list_fields' => array(
		'recipient_name'=>array(
			'vname' => 'LBL_LIST_RECIPIENT_NAME',
			'width' => '14%',
			'sortable'=>false,
		),
		'recipient_email'=>array(
			'vname' => 'LBL_LIST_RECIPIENT_EMAIL',
			'width' => '14%',
			'sortable'=>false,
		),		

		'activity_type' => array(
			'vname' => 'LBL_ACTIVITY_TYPE',
			'width' => '14%',
		),
		'activity_date' => array(
			'vname' => 'LBL_ACTIVITY_DATE',
			'width' => '14%',
		),
		'related_name' => array(
			'widget_class' => 'SubPanelDetailViewLink',
			'target_record_key' => 'related_id',
			'target_module_key' => 'related_type',			
			'vname' => 'LBL_RELATED',
			'width' => '39%',
			'sortable'=>false,
		),
		'hits' => array(
			'vname' => 'LBL_HITS',
			'width' => '5%',
		),		
		'target_id'=>array(
			'usage' =>'query_only',
		),
		'target_type'=>array(
			'usage' =>'query_only',
		),
		'related_id'=>array(
			'usage' =>'query_only',
		),
		'related_type'=>array(
			'usage' =>'query_only',
		),
	),
);		
?>
