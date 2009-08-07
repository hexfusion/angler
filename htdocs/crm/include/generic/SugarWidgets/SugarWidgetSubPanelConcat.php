<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelDateTime
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

// $Id: SugarWidgetSubPanelConcat.php,v 1.3 2006/06/06 17:57:52 majed Exp $

require_once('include/generic/SugarWidgets/SugarWidgetField.php');

class SugarWidgetSubPanelConcat extends SugarWidgetField
{
	function displayList(&$layout_def)
	{
		$value='';
		if (isset($layout_def['source']) and is_array($layout_def['source']) and isset($layout_def['fields']) and is_array($layout_def['fields'])) {
			
			foreach ($layout_def['source'] as $field) {
			
				if (isset($layout_def['fields'][strtoupper($field)])) {
					$value.=$layout_def['fields'][strtoupper($field)];
				} else {
					$value.=$field;
				}	
			}
		}
		return $value;
	}
}
?>
