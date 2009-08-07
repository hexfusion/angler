<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
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
 ********************************************************************************/

// $Id: SugarWidgetSubPanelCloseButton.php,v 1.10 2006/06/12 22:00:07 jacob Exp $

require_once('include/generic/SugarWidgets/SugarWidgetField.php');
//TODO Rename this to close button field
class SugarWidgetSubPanelCloseButton extends SugarWidgetField
{
	function displayList(&$layout_def)
	{
		global $app_strings,$image_path;
		$return_module = $_REQUEST['module'];
		$return_id = $_REQUEST['record'];
		$module_name = $layout_def['module'];
		$record_id = $layout_def['fields']['ID'];

		// calls and meetings are held.
		$new_status = 'Held';
		
		switch($module_name)
		{
			case 'Tasks':
				$new_status = 'Completed';
				break;
		}

		$html = "<a href='index.php?module=$module_name&action=EditView&record=$record_id&return_module=$return_module&return_action=DetailView&return_id=$return_id&status=$new_status'>".get_image($image_path."close_inline","alt='Close' border='0'")."</a>";
		
		return $html;

	}
}

?>
