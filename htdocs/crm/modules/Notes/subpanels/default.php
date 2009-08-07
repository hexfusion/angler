<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Subpanel Layout definition for Leads
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

// $Id: default.php,v 1.6 2006/06/06 17:58:22 majed Exp $
$subpanel_layout = array(
	'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Notes'),
	),

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
			'width' => '9999%',
		),
/*todo AG
		array( // this column does not exist on
		    'name' => '$filename',
			'vname' => 'LBL_LIST_FILENAME',
			'width' => '9999%',
		),
		*/
		'contact_name'=>array(
			'module' => 'Contacts',
			'vname' => 'LBL_LIST_CONTACT_NAME',
		    'width' => '9999%',
            'target_record_key' => 'contact_id',
            'target_module' => 'Contacts',
            'widget_class' => 'SubPanelDetailViewLink',
		),
		'date_modified'=>array(
		 	'vname' => 'LBL_LIST_DATE_MODIFIED',
			'width' => '9999%',
		),
		'edit_button'=>array(
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Notes',
			'width' => '9999%',
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
