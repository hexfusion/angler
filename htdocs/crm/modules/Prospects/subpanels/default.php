<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Subpanel Layout definition for Bugs
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

// $Id: default.php,v 1.10 2006/09/06 02:28:58 eddy Exp $

$subpanel_layout = array(
	'top_buttons' => array(
      		array('widget_class'=>'SubPanelTopCreateButton'),
			array('widget_class'=>'SubPanelTopSelectButton'),
		),

	'where' => '',


    'list_fields'=> array(
    	'first_name' => array(
		 	'usage' => 'query_only',
    	),
    	'last_name' => array(
		 	'usage' => 'query_only',
    	),
        'full_name'=>array(
		 	'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '40%',
            'sort_by' => 'last_name',
		),
		'title'=>array(
		 	'vname' => 'LBL_LIST_TITLE',
			'width' => '25%',
		),
		'email1'=>array(
		 	'vname' => 'LBL_LIST_EMAIL_ADDRESS',
			'width' => '15%',
			'widget_class' => 'SubPanelEmailLink',
		),
		'phone_work'=>array(
		 	'vname' => 'LBL_LIST_PHONE',
			'width' => '10%',
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
