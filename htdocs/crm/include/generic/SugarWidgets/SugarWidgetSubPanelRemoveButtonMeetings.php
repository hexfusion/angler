<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelRemoveButtonMeetings
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

// $Id: SugarWidgetSubPanelRemoveButtonMeetings.php,v 1.5 2006/07/28 22:47:22 jbenterou Exp $

require_once('include/generic/SugarWidgets/SugarWidgetField.php');

class SugarWidgetSubPanelRemoveButtonMeetings extends SugarWidgetField
{
	function displayHeaderCell(&$layout_def)
	{
		return '&nbsp;';
	}

	function displayList(&$layout_def)
	{
		global $app_strings;
		global $image_path;
				
		$parent_record_id = $_REQUEST['record'];
		$parent_module = $_REQUEST['module'];

		$action = 'DeleteRelationship';
		$record = $layout_def['fields']['ID'];

		$return_module = $_REQUEST['module'];
		$return_action = 'SubPanelViewer';
		$subpanel = $layout_def['subpanel_id'];
		$return_id = $_REQUEST['record'];
		
		
		if($return_module == 'Calls') {
			require_once('modules/Calls/Call.php');
			$focus = new Call();
		}
		else if($return_module == 'Meetings') {
			require_once('modules/Meetings/Meeting.php');
			$focus = new Meeting();
		}
        /* Handle case where we generate subpanels from MySettings/LoadTabSubpanels.php */
        else if($return_module == 'MySettings') {
        	global $beanList, $beanFiles;
            $return_module = $_REQUEST['loadModule'];
            
            $class = $beanList[$return_module];
            require_once($beanFiles[$class]);
            $focus = new $class();
        }
		
		$focus->retrieve($return_id);
		
		if($focus->assigned_user_id == $record) return '';
		
		if (isset($layout_def['linked_field_set']) && !empty($layout_def['linked_field_set'])) {
			$linked_field= $layout_def['linked_field_set'] ;
		} else {
			$linked_field = $layout_def['linked_field'];
		}
		$refresh_page = 0;
		if(!empty($layout_def['refresh_page'])){
			$refresh_page = 1;
		}
		$return_url = "index.php?module=$return_module&action=$return_action&subpanel=$subpanel&record=$return_id&sugar_body_only=1";

		$icon_remove_text = $app_strings['LNK_REMOVE'];
		$icon_remove_html = get_image($image_path . 'delete_inline',
			'align="absmiddle" alt="' . $icon_remove_text . '" border="0"');
		$remove_url = $layout_def['start_link_wrapper']
			. "index.php?module=$parent_module"
			. "&action=$action"
			. "&record=$parent_record_id"
			. "&linked_field=$linked_field"
			. "&linked_id=$record"
			. "&return_url=" . urlencode(urlencode($return_url))
			. "&refresh_page=$refresh_page"
			. $layout_def['end_link_wrapper'];
		$remove_confirmation_text = $app_strings['NTC_REMOVE_CONFIRMATION'];
		//based on listview since that lets you select records
		if($layout_def['ListView']){
			return '<a href="' . $remove_url . '"'
			. ' class="listViewTdToolsS1"'
			. " onclick=\"return confirm('$remove_confirmation_text');\""
			. ">$icon_remove_html&nbsp;$icon_remove_text</a>";
		}else{
			return '';
		}
	}
}
?>
