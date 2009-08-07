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

class SugarWidgetFieldEnum extends SugarWidgetReportField {

    function SugarWidgetFieldEnum(&$layout_manager) {
        parent::SugarWidgetReportField($layout_manager);
        $this->reporter = $this->layout_manager->getAttribute('reporter');  
    }
    
	function queryFilteris(& $layout_def) {
		$input_name0 = $layout_def['input_name0'];
		if (is_array($layout_def['input_name0'])) {
			$input_name0 = $layout_def['input_name0'][0];
		}

		return $this->_get_column_select($layout_def)."='".PearDatabase :: quote($input_name0)."'\n";
	}

	function queryFilterone_of(& $layout_def) {
		$arr = array ();
		foreach ($layout_def['input_name0'] as $value) {
			array_push($arr, "'".PearDatabase :: quote($value)."'");
		}
		$str = implode(",", $arr);
		return $this->_get_column_select($layout_def)." IN (".$str.")\n";
	}

	function & displayListPlain($layout_def) {
		$field_def = $this->reporter->all_fields[$layout_def['column_key']];

		if (empty ($field_def['fields']) || empty ($field_def['fields'][0]) || empty ($field_def['fields'][1]))
			$cell = translate($field_def['options'], $field_def['module'], $this->_get_list_value($layout_def));
		if (is_array($cell)) {
			$cell = '';
		}
		return $cell;
	}

	function & queryOrderBy($layout_def) {
		$field_def = $this->reporter->all_fields[$layout_def['column_key']];
		if (!empty ($field_def['sort_on'])) {
			$order_by = $layout_def['table_alias'].".".$field_def['sort_on'];
		} else {





				$order_by = $this->_get_column_select($layout_def);



		}

		$list = translate($field_def['options'], $field_def['module']);

		$order_by_arr = array ();




















			if (empty ($layout_def['sort_dir']) || $layout_def['sort_dir'] == 'a') {
				$order_dir = " DESC";
			} else {
				$order_dir = " ASC";
			}

			foreach ($list as $key => $value) {
				array_push($order_by_arr, $order_by."='".$key."' $order_dir\n");
			}
			$thisarr = implode(',', $order_by_arr);
			return $thisarr;




    }
    
    function displayInput(&$layout_def) {
        global $app_list_strings;
        $str = '<select multiple="true" size="3" name="' . $layout_def['name'] . '[]">';
        $str .= get_select_options_with_id($app_list_strings[$layout_def['options']], $layout_def['input_name0']);
        $str .= '</select>';
        return $str;
    }
}
?>

