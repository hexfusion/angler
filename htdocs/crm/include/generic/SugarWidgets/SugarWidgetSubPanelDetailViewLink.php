<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelDetailViewLink
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

// $Id: SugarWidgetSubPanelDetailViewLink.php,v 1.12 2006/06/06 17:57:52 majed Exp $

require_once('include/generic/SugarWidgets/SugarWidgetField.php');

class SugarWidgetSubPanelDetailViewLink extends SugarWidgetField
{
	function displayList(&$layout_def)
	{
		
			
		$module = '';
		$record = '';

		if(isset($layout_def['varname']))
		{
			$key = strtoupper($layout_def['varname']);
		}
		else
		{
			$key = $this->_get_column_alias($layout_def);
			$key = strtoupper($key);
		}
		if (empty($layout_def['fields'][$key])) {
			return "";
		} else {
			$value = $layout_def['fields'][$key];
		}
			
		
		if(empty($layout_def['target_record_key']))
		{
			$record = $layout_def['fields']['ID'];
		}
		else
		{
			$record_key = strtoupper($layout_def['target_record_key']);
			$record = $layout_def['fields'][$record_key];
		}

		if(!empty($layout_def['target_module_key'])) { 
			if (!empty($layout_def['fields'][strtoupper($layout_def['target_module_key'])])) {
				$module=$layout_def['fields'][strtoupper($layout_def['target_module_key'])];
			}	
		}

		if (empty($module)) {
			if(empty($layout_def['target_module']))
			{
				$module = $layout_def['module'];
			}
		else
			{
				$module = $layout_def['target_module'];
			}
		}
		

		$action = 'DetailView';
		$value = $layout_def['fields'][$key];
		global $current_user;
		
		if(  
			($layout_def['DetailView'] && !$layout_def['owner_module'] 
			||  $layout_def['DetailView'] && !ACLController::moduleSupportsACL($layout_def['owner_module']) 
			|| ACLController::checkAccess($layout_def['owner_module'], 'view', $layout_def['owner_id'] == $current_user->id))){
			return '<a href="index.php?module=' . $module
			. '&action=' . $action
			. '&record=' . $record
			. '" class="listViewTdLinkS1">' . "$value</a>";
		}else{
			return $value;
		}
		
	}
}

?>
