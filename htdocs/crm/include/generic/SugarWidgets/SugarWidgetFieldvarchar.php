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

class SugarWidgetFieldVarchar extends SugarWidgetReportField
{

 function queryFilterEquals(&$layout_def)
 {
		return $this->_get_column_select($layout_def)."='".PearDatabase::quote($layout_def['input_name0'])."'\n";
 }

 function queryFilterNot_Equals_Str(&$layout_def)
 {
		return $this->_get_column_select($layout_def)."!='".PearDatabase::quote($layout_def['input_name0'])."'\n";
 }

 function queryFilterContains(&$layout_def)
 {
		return $this->_get_column_select($layout_def)." LIKE '%".PearDatabase::quote($layout_def['input_name0'])."%'\n";
 }

 function queryFilterStarts_With(&$layout_def)
 {
		return $this->_get_column_select($layout_def)." LIKE '".PearDatabase::quote($layout_def['input_name0'])."%'\n";
 }

 function queryFilterEnds_With(&$layout_def)
 {
		return $this->_get_column_select($layout_def)." LIKE '%".PearDatabase::quote($layout_def['input_name0'])."'\n";
 }
 
 function queryFilterone_of(&$layout_def)
 {
    foreach($layout_def['input_name0'] as $key => $value) {
        $layout_def['input_name0'][$key] = PearDatabase::quote($value); 
    }
    return $this->_get_column_select($layout_def) . " IN ('" . implode("','", $layout_def['input_name0']) . "')\n";
 }
  
 function displayInput(&$layout_def) 
 {
 		$str = '<input type="text" size="20" value="' . $layout_def['input_name0'] . '" name="' . $layout_def['name'] . '">';
 		return $str;
 }
}

?>
