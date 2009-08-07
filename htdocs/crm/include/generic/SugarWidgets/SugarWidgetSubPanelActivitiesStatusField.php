<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelActivitiesStatusField
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

// $Id: SugarWidgetSubPanelActivitiesStatusField.php,v 1.7 2006/06/06 17:57:52 majed Exp $

require_once('include/generic/SugarWidgets/SugarWidgetField.php');
require_once('include/utils.php');

class SugarWidgetSubPanelActivitiesStatusField extends SugarWidgetField
{
	function displayList(&$layout_def)
	{
		global $current_language;
		$app_list_strings = return_app_list_strings_language($current_language);
		
		$module = empty($layout_def['module']) ? '' : $layout_def['module'];
		
		if(isset($layout_def['varname']))
		{
			$key = strtoupper($layout_def['varname']);
		}
		else
		{
			$key = $this->_get_column_alias($layout_def);
			$key = strtoupper($key);
		}

		$value = $layout_def['fields'][$key];
		// cn: bug 5813, removing double-derivation of lang-pack value
		return $value;
	}
}

?>
