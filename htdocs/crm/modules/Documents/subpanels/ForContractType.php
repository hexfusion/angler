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

// $Id$
$subpanel_layout = array(
	'top_buttons' => array(
       array('widget_class' => 'SubPanelTopCreateButton'),
	   array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Documents','field_to_name_array'=>array('document_revision_id'=>'REL_ATTRIBUTE_document_revision_id')),
	),

	'where' => '',
	
	

    'list_fields'=> array(
      'document_name'=> array(
	    	'name' => 'document_name',
	 		'vname' => 'LBL_LIST_DOCUMENT_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '30%',
	   ),
       'is_template'=>array(
 	    	'name' => 'is_template',
	 	    'vname' => 'LBL_LIST_IS_TEMPLATE',
		    'width' => '5%',
		    'widget_type'=>'checkbox',
		),
       'template_type'=>array(
 	    	'name' => 'template_types',
	 	    'vname' => 'LBL_LIST_TEMPLATE_TYPE',
		    'width' => '15%',
		),		
       'latest_revision'=>array(
 	    	'name' => 'latest_revision',
	 	    'vname' => 'LBL_LATEST_REVISION',
		    'width' => '10%',
            'sortable' => false
		),
		'edit_button'=>array(
			'widget_class' => 'SubPanelEditButton',
		 	'module' => 'Documents',
			'width' => '5%',
		),
		'remove_button'=>array(
			'widget_class' => 'SubPanelRemoveButton',
		 	'module' => 'Documents',
			'width' => '5%',
		),		
		'document_revision_id'=>array(
			'usage'=>'query_only'
		),
	),
);
?>
