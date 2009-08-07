<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * LayoutManager
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

// $Id: LayoutManager.php,v 1.37 2006/07/14 17:43:30 awu Exp $

require_once('include/generic/SugarWidgets/SugarWidgetSubPanelTopButton.php');
require_once('include/generic/SugarWidgets/SugarWidgetReportField.php');
require_once('include/database/DBHelper.php');

class LayoutManager
{
	var $defs = array();
	var $widget_prefix = 'SugarWidget';
	var $default_widget_name = 'Field';
	var $DBHelper;

	function LayoutManager()
	{
		// set a sane default for context
		$this->defs['context'] = 'Detail';
		$this->DBHelper = new DBHelper();
	}	

	function setAttribute($key,$value)
	{
		$this->defs[$key] = $value;
	}	

	function setAttributePtr($key,&$value)
	{
		$this->defs[$key] = $value;
	}	

	function getAttribute($key)
	{
		if ( isset($this->defs[$key]))
		{
			return $this->defs[$key];
		} else {
			return null;
		}
	}	

	// Take the class name from the widget definition and use the class to look it up
	// $use_default will default classes to SugarWidgetFieldxxxxx
	function getClassFromWidgetDef($widget_def, $use_default = false)
	{
		static $class_map = array(
			'SugarWidgetSubPanelTopCreateButton' => array(
				'widget_class'=>'SugarWidgetSubPanelTopButton',
				'title'=>'LBL_NEW_BUTTON_TITLE',
				'access_key'=>'LBL_NEW_BUTTON_KEY',
				'form_value'=>'LBL_NEW_BUTTON_LABEL',
				'ACL'=>'edit',
			),
            'SugarWidgetSubPanelTopButtonQuickCreate' => array(
                'widget_class'=>'SugarWidgetSubPanelTopButtonQuickCreate',
                'title'=>'LBL_NEW_BUTTON_TITLE',
                'access_key'=>'LBL_NEW_BUTTON_KEY',
                'form_value'=>'LBL_NEW_BUTTON_LABEL',
                'ACL'=>'edit',
            ),
			'SugarWidgetSubPanelTopScheduleMeetingButton' => array(
				'widget_class'=>'SugarWidgetSubPanelTopScheduleMeetingButton',
				'module'=>'Meetings',
				'title'=>'LBL_NEW_BUTTON_TITLE',
				'access_key'=>'LBL_NEW_BUTTON_KEY',
				'form_value'=>'LNK_NEW_MEETING',
				'ACL'=>'edit',
			),
			'SugarWidgetSubPanelTopScheduleCallButton' => array(
				'widget_class'=>'SugarWidgetSubPanelTopScheduleCallButton',
				'module'=>'Calls',
				'title'=>'LBL_NEW_BUTTON_TITLE',
				'access_key'=>'LBL_NEW_BUTTON_KEY',
				'form_value'=>'LNK_NEW_CALL',
				'ACL'=>'edit',
			),
			'SugarWidgetSubPanelTopCreateTaskButton' => array(
				'widget_class'=>'SugarWidgetSubPanelTopCreateTaskButton',
				'module'=>'Tasks',
				'title'=>'LBL_NEW_BUTTON_TITLE',
				'access_key'=>'LBL_NEW_BUTTON_KEY',
				'form_value'=>'LNK_NEW_TASK',
				'ACL'=>'edit',
			),
			'SugarWidgetSubPanelTopCreateNoteButton' => array(
				'widget_class'=>'SugarWidgetSubPanelTopCreateNoteButton',
				'module'=>'Notes',
				'title'=>'LBL_NEW_BUTTON_TITLE',
				'access_key'=>'LBL_NEW_BUTTON_KEY',
				'form_value'=>'LNK_NEW_NOTE',
				'ACL'=>'edit',
			),	
			'SugarWidgetSubPanelTopCreateContactAccountButton' => array(
				'widget_class'=>'SugarWidgetSubPanelTopButton',
				'module'=>'Contacts',
				'title'=>'LBL_NEW_BUTTON_TITLE',
				'access_key'=>'LBL_NEW_BUTTON_KEY',
				'form_value'=>'LBL_NEW_BUTTON_LABEL',
        		'additional_form_fields' => array(
        			'primary_address_street' => 'shipping_address_street',
					'primary_address_city' => 'shipping_address_city',
					'primary_address_state' => 'shipping_address_state',
					'primary_address_country' => 'shipping_address_country',
					'primary_address_postalcode' => 'shipping_address_postalcode',
					'to_email_addrs' => 'email1'
					),
				'ACL'=>'edit',
			),
			'SugarWidgetSubPanelTopCreateContact' => array(
				'widget_class'=>'SugarWidgetSubPanelTopButton',
				'module'=>'Contacts',
				'title'=>'LBL_NEW_BUTTON_TITLE',
				'access_key'=>'LBL_NEW_BUTTON_KEY',
				'form_value'=>'LBL_NEW_BUTTON_LABEL',
        		'additional_form_fields' => array(
        			'account_id' => 'account_id',
					'account_name' => 'account_name',
				),
				'ACL'=>'edit',
			),
			'SugarWidgetSubPanelTopCreateRevisionButton'=> array(
				'widget_class'=>'SugarWidgetSubPanelTopButton',
				'module'=>'DocumentRevisions',
				'title'=>'LBL_NEW_BUTTON_TITLE',
				'access_key'=>'LBL_NEW_BUTTON_KEY',
				'form_value'=>'LBL_NEW_BUTTON_LABEL',
        		'additional_form_fields' => array(
        			'parent_name'=>'document_name',
					'document_name' => 'document_name',
					'document_revision' => 'latest_revision',
					'document_filename' => 'filename',
        			'document_revision_id' => 'document_revision_id',					
				),
				'ACL'=>'edit',
			),
			
			'SugarWidgetSubPanelTopCreateDirectReport' => array(
				'widget_class'=>'SugarWidgetSubPanelTopButton',
				'module'=>'Contacts',
				'title'=>'LBL_NEW_BUTTON_TITLE',
				'access_key'=>'LBL_NEW_BUTTON_KEY',
				'form_value'=>'LBL_NEW_BUTTON_LABEL',
        		'additional_form_fields' => array(
        			'reports_to_name' => 'name',
					'reports_to_id' => 'id',
				),
				'ACL'=>'edit',
			),
			'SugarWidgetSubPanelTopSelectFromReportButton' => array(
				'widget_class'=>'SugarWidgetSubPanelTopSelectButton',
				'module'=>'Reports',
				'title'=>'LBL_SELECT_REPORTS_BUTTON_LABEL',
				'access_key'=>'LBL_SELECT_BUTTON_KEY',
				'form_value'=>'LBL_SELECT_REPORTS_BUTTON_LABEL',
				'ACL'=>'edit',
				'add_to_passthru_data'=>array (
					'return_type'=>'report',
				)
			),
					
			'SugarWidgetSubPanelAddToProspectListButton' => array(
				'widget_class'=>'SugarWidgetSubPanelTopSelectButton',
				'module'=>'ProspectLists',
				'title'=>'LBL_ADD_TO_PROSPECT_LIST_BUTTON_LABEL',
				'access_key'=>'LBL_ADD_TO_PROSPECT_LIST_BUTTON_KEY',
				'form_value'=>'LBL_ADD_TO_PROSPECT_LIST_BUTTON_LABEL',
				'ACL'=>'edit',
				'add_to_passthru_data'=>array (
					'return_type'=>'addtoprospectlist',
					'parent_module'=>'ProspectLists',
					'parent_type'=>'ProspectList',
					'child_id'=>'target_id',
					'link_attribute'=>'target_type',
					'link_type'=>'polymorphic',	 //polymorphic or default				
				)
			),				
		);			

		if($use_default) {
			switch($widget_def['name']) {
				case 'assigned_user_id':
					$widget_def['widget_class'] = 'Fielduser_name';
					break;





				default:
					$widget_def['widget_class'] = 'Field' . $this->DBHelper->getFieldType($widget_def);
			}
		}
		
		if(empty($widget_def['widget_class']))
		{
			// Default the class to SugarWidgetField
			$class_name = $this->widget_prefix.$this->default_widget_name;
		}
		else 
		{
			$class_name = $this->widget_prefix.$widget_def['widget_class'];
		}
//_pp($class_name);
		// Check to see if this is one of the known class mappings.
		if(!empty($class_map[$class_name]))
		{
			if (empty($class_map[$class_name]['widget_class'])) {
				return new SugarWidgetSubPanelTopButton($class_map[$class_name]);				
			}  else {

				if (!class_exists($class_map[$class_name]['widget_class'])) {
					require_once('include/generic/SugarWidgets/'.$class_map[$class_name]['widget_class'].'.php');
				}
				//if (isset($widget_def['query'])) {
				//	$class_map[$class_name]['query']=$widget_def['query'];
				//}
				return new $class_map[$class_name]['widget_class']($class_map[$class_name]);
			}
		}
//			_pp($class_name);
		// At this point, we have a class name and we do not have a valid class defined.
		if(!class_exists($class_name))
		{

			// The class does not exist.  Try including it.
			require_once('include/generic/SugarWidgets/'.$class_name.'.php');
			
			if(!class_exists($class_name))
			{
				// If we still do not have a class, oops....
				die("LayoutManager: Class not found:".$class_name);
			}
		}
		return new $class_name($this); // cache disabled $this->getClassFromCache($class_name);
	}

	function widgetDisplay($widget_def, $use_default = false)
	{
		$theclass = $this->getClassFromWidgetDef($widget_def, $use_default);
		return $theclass->display($widget_def);
	}	

	function widgetQuery($widget_def, $use_default = false)
	{
		$theclass = $this->getClassFromWidgetDef($widget_def, $use_default);
//				_pp($theclass);
		return $theclass->query($widget_def);
	}
	
	// display an input field
	// module is the parent module of the def
	function widgetDisplayInput($widget_def, $use_default = false)
	{
//		_pp($widget_def);
		$theclass = $this->getClassFromWidgetDef($widget_def, $use_default);
		return $theclass->displayInput($widget_def);
	}	

}
?>
