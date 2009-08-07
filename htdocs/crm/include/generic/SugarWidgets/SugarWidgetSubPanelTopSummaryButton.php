<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelTopCreateNoteButton
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

// $Id: SugarWidgetSubPanelTopSummaryButton.php,v 1.7 2006/06/06 17:57:53 majed Exp $

require_once('include/generic/SugarWidgets/SugarWidgetSubPanelTopButton.php');

class SugarWidgetSubPanelTopSummaryButton extends SugarWidgetSubPanelTopButton
{
	function display($widget_data)
	{
		
		
		global $app_strings;
		global $currentModule;

		$popup_request_data = array(
			'call_back_function' => 'set_return',
			'form_name' => 'EditView',
			'field_to_name_array' => array(),
		);

		$json_encoded_php_array = $this->_create_json_encoded_popup_request($popup_request_data);
		$title = $app_strings['LBL_ACCUMULATED_HISTORY_BUTTON_TITLE'];
		$accesskey = $app_strings['LBL_ACCUMULATED_HISTORY_BUTTON_KEY'];
		$value = $app_strings['LBL_ACCUMULATED_HISTORY_BUTTON_LABEL'];
		$module_name = 'Activities';
		$id = $widget_data['focus']->id;
		$initial_filter = "&record=$id&module_name=$currentModule";
		if(ACLController::moduleSupportsACL($widget_data['module']) && !ACLController::checkAccess($widget_data['module'], 'detail', true)){
			$temp =  '<input disabled type="button" name="summary_button" id="summary_button"'
			. ' class="button"'
			. ' title="' . $title . '"'
			. ' value="' . $value . '"';
			return $tmp;
		}
		return '<input type="button" name="summary_button" id="summary_button"'
			. ' class="button"'
			. ' title="' . $title . '"'
			. ' accesskey="' . $accesskey . '"'
			. ' value="' . $value . '"'
			. " onclick='open_popup(\"$module_name\",600,400,\"$initial_filter\",false,false,$json_encoded_php_array);' />\n";
	}
}
?>
