<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelTopButton
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

// $Id: SugarWidgetSubPanelTopButton.php,v 1.27 2006/06/23 18:15:12 ajay Exp $

require_once('include/generic/SugarWidgets/SugarWidget.php');

class SugarWidgetSubPanelTopButton extends SugarWidget
{
    var $module;
	var $title;
	var $access_key;
	var $form_value;
	var $additional_form_fields;
	var $acl;

//TODO rename defines to layout defs and make it a member variable instead of passing it multiple layers with extra copying.
	
	/** Take the keys for the strings and look them up.  Module is literal, the rest are label keys
	*/
	function SugarWidgetSubPanelTopButton($module='', $title='', $access_key='', $form_value='')
	{
		global $app_strings;

		if(is_array($module))
		{
			// it is really the class details from the mapping
			$class_details = $module;
			
			// If keys were passed into the constructor, translate them from keys to values.
			if(!empty($class_details['module']))
				$this->module = $class_details['module'];
			if(!empty($class_details['title']))
				$this->title = $app_strings[$class_details['title']];
			if(!empty($class_details['access_key']))
				$this->access_key = $app_strings[$class_details['access_key']];
			if(!empty($class_details['form_value']))
				$this->form_value = translate($class_details['form_value'], $this->module);
			if(!empty($class_details['additional_form_fields']))
				$this->additional_form_fields = $class_details['additional_form_fields'];
			if(!empty($class_details['ACL'])){
				$this->acl = $class_details['ACL'];
			}
		}
		else
		{
			$this->module = $module;
		
			// If keys were passed into the constructor, translate them from keys to values.
			if(!empty($title))
				$this->title = $app_strings[$title];
			if(!empty($access_key))
				$this->access_key = $app_strings[$access_key];
			if(!empty($form_value))
				$this->form_value = translate($form_value, $module);
		}
	}
	
    function &_get_form($defines, $additionalFormFields = null)
    {
        global $app_strings;
        global $currentModule;

        // Create the additional form fields with real values if they were not passed in
        if(empty($additionalFormFields) && $this->additional_form_fields)
        {
            foreach($this->additional_form_fields as $key=>$value)
            {
                if(!empty($defines['focus']->$value))
                {
                    $additionalFormFields[$key] = $defines['focus']->$value;
                }
                else
                {
                    $additionalFormFields[$key] = '';
                }
            }
        }
        
        if(!empty($this->module))
        {
            $defines['child_module_name'] = $this->module;
        }
        else
        {
            $defines['child_module_name'] = $defines['module'];
        }

        $defines['parent_bean_name'] = get_class( $defines['focus']);

        $form = 'form' . $defines['child_module_name'];
        $button = '<form action="index.php" method="post" name="form" id="form' . $form . "\">\n";

        //module_button is used to override the value of module name
        $button .= "<input type='hidden' name='module' value='".$defines['child_module_name']."'>\n";
        $button .= "<input type='hidden' name='".strtolower($defines['parent_bean_name'])."_id' value='".$defines['focus']->id."'>\n";

        if(isset($defines['focus']->name))
        {
            $button .= "<input type='hidden' name='".strtolower($defines['parent_bean_name'])."_name' value='".$defines['focus']->name."'>";
        }

        $button .= '<input type="hidden" name="return_module" value="' . $currentModule . "\" />\n";
        $button .= '<input type="hidden" name="return_action" value="' . $defines['action'] . "\" />\n";
        $button .= '<input type="hidden" name="return_id" value="' . $defines['focus']->id . "\" />\n";
         
        // TODO: move this out and get $additionalFormFields working properly
        if(empty($additionalFormFields['parent_type']))
        {
            if($defines['focus']->object_name=='Contact') {
                $additionalFormFields['parent_type'] = 'Accounts';
            }
            else {
                $additionalFormFields['parent_type'] = $defines['focus']->module_dir;
            }
        }
        if(empty($additionalFormFields['parent_name']))
        {
            if($defines['focus']->object_name=='Contact') {
                $additionalFormFields['parent_name'] = $defines['focus']->account_name;
                $additionalFormFields['account_name'] = $defines['focus']->account_name;
            }
            else {
                $additionalFormFields['parent_name'] = $defines['focus']->name;
            }
        }
        if(empty($additionalFormFields['parent_id']))
        {
            if($defines['focus']->object_name=='Contact') {
                $additionalFormFields['parent_id'] = $defines['focus']->account_id;
                $additionalFormFields['account_id'] = $defines['focus']->account_id;
            }
            else {
                $additionalFormFields['parent_id'] = $defines['focus']->id;
            }
        }

        if (!empty($defines['child_module_name']) and $defines['child_module_name']=='Contacts' and !empty($defines['parent_bean_name']) and $defines['parent_bean_name']=='contact' ) {
            if (!empty($defines['focus']->id ) and !empty($defines['focus']->name)) {
                $button .= '<input type="hidden" name="reports_to_id" value="'. $defines['focus']->id .'"  />' . "\n";
                $button .= '<input type="hidden" name="reports_to_name" value="'. $defines['focus']->name .'"  />' . "\n";
            }
        }
        $button .= '<input type="hidden" name="action" value="EditView" />' . "\n";
        
        // fill in additional form fields for all but action
        foreach($additionalFormFields as $key => $value)
        {
            if($key != 'action')
            {
                $button .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
            }
        }

        return $button;
    }

	/** This default function is used to create the HTML for a simple button */
	function display($defines, $additionalFormFields = null)
	{
		$temp='';
		if(!empty($this->acl) && ACLController::moduleSupportsACL($defines['module'])  &&  !ACLController::checkAccess($defines['module'], $this->acl, true)){
			$button = "<input title='$this->title'  class='button' type='button' name='button' value='  $this->form_value  ' disabled/>\n</form>";
			return $temp;
		}
		
		global $app_strings;
		
		$button = $this->_get_form($defines, $additionalFormFields);
		$button .= "<input title='$this->title' accesskey='$this->access_key' class='button' type='submit' name='button' value='  $this->form_value  ' />\n</form>";
		return $button;
	}

	/**
	 * Returns a string that is the JSON encoded version of the popup request.
	 * Perhaps this function should be moved to a more globally accessible location?
	 */
	function _create_json_encoded_popup_request($popup_request_data)
	{
		$popup_request_array = array();
		
		if(!empty($popup_request_data['call_back_function']))
		{
			$popup_request_array[] = '"call_back_function":"' . $popup_request_data['call_back_function'] . '"';
		}

		if(!empty($popup_request_data['form_name']))
		{
			$popup_request_array[] = '"form_name":"' . $popup_request_data['form_name'] . '"';
		}
		
		if(!empty($popup_request_data['field_to_name_array']))
		{
			$field_to_name_array = array();
			foreach($popup_request_data['field_to_name_array'] as $field => $name)
			{
				$field_to_name_array[] = '"' . $field . '":"' . $name . '"';
			}
			
			$popup_request_array[] = '"field_to_name_array":{' . implode(',', $field_to_name_array) . '}';
		}

		if(!empty($popup_request_data['passthru_data']))
		{
			$passthru_array = array();
			foreach($popup_request_data['passthru_data'] as $field => $name)
			{
				$passthru_array[] = '"' . $field . '":"' . $name . '"';
			}
			
			$popup_request_array[] = '"passthru_data":{' . implode(',', $passthru_array) . '}';
		}

		$encoded_popup_request = '{' . implode(',', $popup_request_array) . '}';
		
		return $encoded_popup_request;
	}
}
?>
