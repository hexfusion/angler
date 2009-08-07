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

require_once('modules/Users/User.php');

class SugarWidgetFieldDateTime extends SugarWidgetReportField {
	var $reporter;
	var $assigned_user;
	
    function SugarWidgetFieldDateTime(&$layout_manager) {
        parent::SugarWidgetReportField($layout_manager);
        $this->reporter = $this->layout_manager->getAttribute('reporter');  
    }
    
	// get the reporter attribute
    // deprecated, now called in the constructor
	function getReporter() {
//		$this->reporter = $this->layout_manager->getAttribute('reporter');	
	}
	
	// get the assigned user of the report
	function getAssignedUser() {  
		$json_obj = getJSONobj();
		
		$report_def_str = $json_obj->decode($this->reporter->report_def_str);
		
		if(empty($report_def_str['assigned_user_id'])) return false;

		$this->assigned_user = new User();
		$this->assigned_user->retrieve($report_def_str['assigned_user_id']);
		return true;
	}
		
	function queryFilterOn(& $layout_def) {
		global $timedate;
		
		if($this->getAssignedUser()) {
			$begin = $timedate->handle_offset($layout_def['input_name0'] . ' 00:00:00', $timedate->get_db_date_time_format(), false, $this->assigned_user);
			$end = $timedate->handle_offset($layout_def['input_name0'] . ' 23:59:59', $timedate->get_db_date_time_format(), false, $this->assigned_user);
		}
		else {
			$begin = $layout_def['input_name0']." 00:00:00";
     		$end = $layout_def['input_name0']." 23:59:59";
		}






			return $this->_get_column_select($layout_def).">='".PearDatabase :: quote($begin)."' AND ".$this->_get_column_select($layout_def)."<='".PearDatabase :: quote($end)."'\n";



	}

	function queryFilterBefore(& $layout_def) {
		global $timedate;
		
		if($this->getAssignedUser()) {
			$begin = $timedate->handle_offset($layout_def['input_name0'] . ' 00:00:00', $timedate->get_db_date_time_format(), false, $this->assigned_user);
		}
		else {
			$begin = $layout_def['input_name0']." 00:00:00";
		}






			return $this->_get_column_select($layout_def)."<'".PearDatabase :: quote($begin)."'\n";




	}

	function queryFilterAfter(& $layout_def) {
		global $timedate;

		if($this->getAssignedUser()) {		
			$begin = $timedate->handle_offset($layout_def['input_name0'] . ' 23:59:59', $timedate->get_db_date_time_format(), false, $this->assigned_user);
		}
		else {
			$begin = $layout_def['input_name0']." 23:59:59";
		}






			return $this->_get_column_select($layout_def).">'".PearDatabase :: quote($begin)."'\n";



	}

	function queryFilterBetween_Dates(& $layout_def) {
		global $timedate;
		
		if($this->getAssignedUser()) {
			$begin = $timedate->handle_offset($layout_def['input_name0'] . ' 00:00:00', $timedate->get_db_date_time_format(), false, $this->assigned_user);
			$end = $timedate->handle_offset($layout_def['input_name1'] . ' 23:59:59', $timedate->get_db_date_time_format(), false, $this->assigned_user);
		}
		else {
			$begin = $layout_def['input_name0']." 00:00:00";
			$end = $layout_def['input_name1']." 23:59:59";
		}






			return "(".$this->_get_column_select($layout_def).">='".PearDatabase :: quote($begin)."' AND \n".$this->_get_column_select($layout_def)."<='".PearDatabase :: quote($end)."')\n";



	}

	function queryFilterNot_Equals_str(& $layout_def) {
		global $timedate;
		
		if($this->getAssignedUser()) {
			$begin = $timedate->handle_offset($layout_def['input_name0'] . ' 00:00:00', $timedate->get_db_date_time_format(), false, $this->assigned_user);
			$end = $timedate->handle_offset($layout_def['input_name0'] . ' 23:59:59', $timedate->get_db_date_time_format(), false, $this->assigned_user);
		}
		else {
			$begin = $layout_def['input_name0']." 00:00:00";
			$end = $layout_def['input_name0']." 23:59:59";
		}

		if ($this->reporter->db->dbType == 'oci8') {




		} elseif ($this->reporter->db->dbType == 'mssql'){
            return "(".$this->_get_column_select($layout_def)."<'".PearDatabase :: quote($begin)."' AND ".$this->_get_column_select($layout_def).">'".PearDatabase :: quote($end)."')\n";

		}else{
            return "ISNULL(".$this->_get_column_select($layout_def).") OR \n(".$this->_get_column_select($layout_def)."<'".PearDatabase :: quote($begin)."' AND ".$this->_get_column_select($layout_def).">'".PearDatabase :: quote($end)."')\n";
        }
	}

	function queryFilterTP_yesterday(& $layout_def) {
		global $timedate;

		$today = getdate();
		$be = mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']) - (24 * 60 * 60);
		$ed = mktime(23, 59, 59, $today['mon'], $today['mday'], $today['year']) - (24 * 60 * 60);

		$begin = gmdate('Y-m-d H:i:s', $be);
		$end = gmdate('Y-m-d H:i:s', $ed);

		if($this->getAssignedUser()) {
			$begin = $timedate->handle_offset($begin, $timedate->get_db_date_time_format(), false, $this->assigned_user);
			$end = $timedate->handle_offset($end, $timedate->get_db_date_time_format(), false, $this->assigned_user);
		}

		if ($this->reporter->db->dbType == 'oci8') {









		} 
		
		if ($this->reporter->db->dbType == 'mysql')
		{
			if (isset ($layout_def['rel_field'])) {
				$field_name = "CONCAT(".$this->_get_column_select($layout_def).",' ',".$layout_def['rel_field'].")";
			} else {
				$field_name = $this->_get_column_select($layout_def);
			}
			return $field_name.">='".PearDatabase :: quote($begin)."' AND ".$field_name."<='".PearDatabase :: quote($end)."'\n";
		}

		if ($this->reporter->db->dbType == 'mssql')
		{
			if (isset ($layout_def['rel_field'])) {
				$field_name = $this->_get_column_select($layout_def) . " + ' ' + " . $layout_def['rel_field'].")";
			} else {
				$field_name = $this->_get_column_select($layout_def);
			}
			return $field_name.">='".PearDatabase :: quote($begin)."' AND ".$field_name."<='".PearDatabase :: quote($end)."'\n";
		}		
	}
	function queryFilterTP_today(& $layout_def) {
		global $timedate;
		
		$today = getdate();
		$be = mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']);
		$ed = mktime(23, 59, 59, $today['mon'], $today['mday'], $today['year']);

		$begin = gmdate('Y-m-d H:i:s', $be);
		$end = gmdate('Y-m-d H:i:s', $ed);
		
		if($this->getAssignedUser()) {
			$begin = $timedate->handle_offset($begin, $timedate->get_db_date_time_format(), false, $this->assigned_user);
			$end = $timedate->handle_offset($end, $timedate->get_db_date_time_format(), false, $this->assigned_user);
		}

		if ($this->reporter->db->dbType == 'oci8') {









		}elseif($this->reporter->db->dbType == 'mssql'){
            if (isset ($layout_def['rel_field'])) {
                $field_name = "(".$this->_get_column_select($layout_def)." + ' ' + ".$layout_def['rel_field'].")";
            } else {
                $field_name = $this->_get_column_select($layout_def);
            }
            return $field_name.">='".PearDatabase :: quote($begin)."' AND ".$field_name."<='".PearDatabase :: quote($end)."'\n";
           
        } else {
			if (isset ($layout_def['rel_field'])) {
				$field_name = "CONCAT(".$this->_get_column_select($layout_def).",' ',".$layout_def['rel_field'].")";
			} else {
				$field_name = $this->_get_column_select($layout_def);
			}
			return $field_name.">='".PearDatabase :: quote($begin)."' AND ".$field_name."<='".PearDatabase :: quote($end)."'\n";
		}
	}

	function queryFilterTP_tomorrow(& $layout_def) {
		global $timedate;

		$current_date = gmdate('Y-m-d H:i:s', time() + (24 * 60 * 60));
		$current_date = $timedate->to_display_date($current_date);
		$begin = $current_date." 00:00:00";
		$end = $current_date." 23:59:59";

		if($this->getAssignedUser()) {
			$begin = $timedate->handle_offset($begin, $timedate->get_db_date_time_format(), false, $this->assigned_user);
			$end = $timedate->handle_offset($end, $timedate->get_db_date_time_format(), false, $this->assigned_user);
		}

		if ($this->reporter->db->dbType == 'oci8') {



		} else {
			return $this->_get_column_select($layout_def).">='".PearDatabase :: quote($begin)."' AND ".$this->_get_column_select($layout_def)."<='".PearDatabase :: quote($end)."'\n";
		}
	}

	function queryFilterTP_last_7_days(& $layout_def) {
		if ($this->reporter->db->dbType == 'oci8') {



		} elseif ($this->reporter->db->dbType == 'mssql'){
			return "LEFT(".$this->_get_column_select($layout_def).",11) BETWEEN LEFT((DATEADD(dd,-7,GETDATE())),11) AND LEFT(GETDATE(),11)";
		}else{
            return "LEFT(".$this->_get_column_select($layout_def).",10) BETWEEN LEFT((current_date - interval '7' day),10) AND LEFT(current_date,10)";
        }
	}

	function queryFilterTP_next_7_days(& $layout_def) {







			return "LEFT(".$this->_get_column_select($layout_def).",10) BETWEEN LEFT(current_date,10) AND LEFT((current_date + interval '7' day),10)";



	}

	function queryFilterTP_last_month(& $layout_def) {







			return "LEFT(".$this->_get_column_select($layout_def).",7) = LEFT( (current_date  - interval '1' month),7)";



	}

	function queryFilterTP_this_month(& $layout_def) {







			return "LEFT(".$this->_get_column_select($layout_def).",7) = LEFT( current_date,7)";



	}

	function queryFilterTP_next_month(& $layout_def) {







			return "LEFT(".$this->_get_column_select($layout_def).",7) = LEFT( (current_date  + interval '1' month),7)";



	}

	function queryFilterTP_last_30_days(& $layout_def) {
		if ($this->reporter->db->dbType == 'oci8') {



		} elseif ($this->reporter->db->dbType == 'mssql'){
                        return $this->_get_column_select($layout_def)." BETWEEN (DATEADD(dd,-30,GETDATE())) AND (GETDATE())";
        }else {
			return $this->_get_column_select($layout_def)." BETWEEN (current_date - interval '1' month) AND (current_date)";
		}
	}

	function queryFilterTP_next_30_days(& $layout_def) {
		if ($this->reporter->db->dbType == 'oci8') {



		}elseif ($this->reporter->db->dbType == 'mssql'){
                return $this->_get_column_select($layout_def)." BETWEEN (GETDATE()) AND (DATEADD(dd,30,GETDATE()))";
        } else {
			return $this->_get_column_select($layout_def)." BETWEEN (current_date) AND (current_date + interval '1' month)";
		}
	}

	function queryFilterTP_last_quarter(& $layout_def) {
//		return "LEFT(".$this->_get_column_select($layout_def).",10) BETWEEN (current_date + interval '1' month) AND current_date";
	}

	function queryFilterTP_this_quarter(& $layout_def) {
	}

	function queryFilterTP_last_year(& $layout_def) {








			return "LEFT(".$this->_get_column_select($layout_def).",4) = EXTRACT(YEAR FROM ( current_date  - interval '1' year))";



	}

	function queryFilterTP_this_year(& $layout_def) {








			return "LEFT(".$this->_get_column_select($layout_def).",4) = EXTRACT(YEAR FROM ( current_date ))";



	}

	function queryFilterTP_next_year(& $layout_def) {








			return "LEFT(".$this->_get_column_select($layout_def).",4) = EXTRACT(YEAR FROM ( current_date  + interval '1' year))";



	}

	function queryGroupBy($layout_def) {
		// i guess qualifier and column_function are the same..
		if (!empty ($layout_def['qualifier'])) {
			$func_name = 'queryGroupBy'.$layout_def['qualifier'];
			//print_r($layout_def);
			//print $func_name;
			if (method_exists($this, $func_name)) {
				return $this-> $func_name ($layout_def)." \n";
			}
		}
		return parent :: queryGroupBy($layout_def)." \n";
	}

	function queryOrderBy($layout_def) {
		// i guess qualifier and column_function are the same..
        if ($this->reporter->db->dbType == 'mssql'){
            //do nothing if this is for mssql, do not run group by

        }
		elseif (!empty ($layout_def['qualifier'])) {
			$func_name ='queryGroupBy'.$layout_def['qualifier'];
			if (method_exists($this, $func_name)) {
				return $this-> $func_name ($layout_def)." \n";
			}
		}
		$order_by = parent :: queryOrderBy($layout_def)." \n";
		return $order_by;
	}

    function displayListPlain($layout_def) {
        global $timedate;
        
        $content = parent:: displayListPlain($layout_def);
        if(count(explode(' ', $content)) == 2) 
            return $timedate->to_display_date_time($content);
        else 
            return $content;
    }
    
    function displayList($layout_def) {
        global $timedate;
        // i guess qualifier and column_function are the same..
        if (!empty ($layout_def['column_function'])) {
            $func_name = 'displayList'.$layout_def['column_function'];
            if (method_exists($this, $func_name)) {
                return $this-> $func_name ($layout_def)." \n";
            }
        }
        $content = parent :: displayListPlain($layout_def);

        return $timedate->to_display_date_time($content);
    }

	function querySelect(& $layout_def) {
		// i guess qualifier and column_function are the same..
		if (!empty ($layout_def['column_function'])) {
			$func_name = 'querySelect'.$layout_def['column_function'];
			if (method_exists($this, $func_name)) {
				return $this-> $func_name ($layout_def)." \n";
			}
		}
		return parent :: querySelect($layout_def)." \n";
	}
	function & displayListyear(& $layout_def) {
		global $app_list_strings;
		if (preg_match('/(\d{4})/', $this->displayListPlain($layout_def), $match)) {
			return $match[1];
		}
		return '';

	}

	function & displayListmonth(& $layout_def) {
		global $app_list_strings;
		$display = '';
		if (preg_match('/(\d{4})-(\d\d)/', $this->displayListPlain($layout_def), $match)) {
			$match[2] = preg_replace('/^0/', '', $match[2]);
			$display = $app_list_strings['dom_cal_month_long'][$match[2]]." {$match[1]}";
		}
		return $display;

	}
	function querySelectmonth(& $layout_def) {
        if ($this->reporter->db->dbType == 'oci8') {



        }elseif($this->reporter->db->dbType == 'mssql') {
            return "LEFT( ".$this->_get_column_select($layout_def).",6 ) ".$this->_get_column_alias($layout_def)." \n";
        } else {
            return "LEFT( ".$this->_get_column_select($layout_def).",7 ) ".$this->_get_column_alias($layout_def)." \n";
        }
	}

	function queryGroupByMonth($layout_def) {
		if ($this->reporter->db->dbType == 'oci8') {



		}elseif($this->reporter->db->dbType == 'mssql') {
            return "LEFT(".$this->_get_column_select($layout_def).", 6) \n";
        }else {
			return "LEFT(".$this->_get_column_select($layout_def).", 7) \n";
		}
	}

	function querySelectyear(& $layout_def) {
		if ($this->reporter->db->dbType == 'oci8') {



		}elseif($this->reporter->db->dbType == 'mssql') {
            return "LEFT( ".$this->_get_column_select($layout_def).",5 ) ".$this->_get_column_alias($layout_def)." \n";
        } else {
			return "LEFT( ".$this->_get_column_select($layout_def).",10 ) ".$this->_get_column_alias($layout_def)." \n";
		}
	}

	function queryGroupByYear($layout_def) {
		if ($this->reporter->db->dbType == 'oci8') {



		}elseif($this->reporter->db->dbType == 'mssql') {
            return "LEFT(".$this->_get_column_select($layout_def).", 5) \n";
        } else {
			return "LEFT(".$this->_get_column_select($layout_def).", 10) \n";
		}
	}

	function querySelectquarter(& $layout_def) {
		if ($this->reporter->db->dbType == 'oci8') {



		} 
	
		elseif ($this->reporter->db->dbType == 'mysql')  
		{
			return "CONCAT(LEFT(".$this->_get_column_select($layout_def).", 4), '-', QUARTER(".$this->_get_column_select($layout_def).") )".$this->_get_column_alias($layout_def)."\n";			
		}

		elseif ($this->reporter->db->dbType == 'mssql')    
		{
			return "LEFT(".$this->_get_column_select($layout_def).", 4) +  '-' + convert(varchar(20), DatePart(q," . $this->_get_column_select($layout_def).") ) ".$this->_get_column_alias($layout_def)."\n";			
		}


	}

	function displayListquarter(& $layout_def) {
		if (preg_match('/(\d{4})-(\d)/', $this->displayListPlain($layout_def), $match)) {
			return "Q".$match[2]." ".$match[1];
		}
		return '';

	}

	function queryGroupByQuarter($layout_def) {
		$this->getReporter();

		if ($this->reporter->db->dbType == 'oci8') {



			
		}elseif ($this->reporter->db->dbType == 'mysql')  
		{
			return "CONCAT(LEFT(".$this->_get_column_select($layout_def).", 4), '-', QUARTER(".$this->_get_column_select($layout_def).") )\n";
		}
		elseif ($this->reporter->db->dbType == 'mssql') 
		{			
			return "LEFT(".$this->_get_column_select($layout_def).", 4) +  '-' + convert(varchar(20), DatePart(q," . $this->_get_column_select($layout_def).") )\n";	
						
		}
	
		
	}
    
    function displayInput(&$layout_def) {
        global $timedate, $image_path, $current_language, $app_strings;
        $home_mod_strings = return_module_language($current_language, 'Home');
        $filterTypes = array(' '                 => $app_strings['LBL_NONE'],
                             'TP_today'         => $home_mod_strings['LBL_TODAY'],
                             'TP_yesterday'     => $home_mod_strings['LBL_YESTERDAY'],
                             'TP_tomorrow'      => $home_mod_strings['LBL_TOMORROW'],
                             'TP_this_month'    => $home_mod_strings['LBL_THIS_MONTH'],
                             'TP_this_year'     => $home_mod_strings['LBL_THIS_YEAR'],
                             'TP_last_30_days'  => $home_mod_strings['LBL_LAST_30_DAYS'],
                             'TP_last_7_days'   => $home_mod_strings['LBL_LAST_7_DAYS'],
                             'TP_last_month'    => $home_mod_strings['LBL_LAST_MONTH'],
                             'TP_last_year'     => $home_mod_strings['LBL_LAST_YEAR'],
                             'TP_next_30_days'  => $home_mod_strings['LBL_NEXT_30_DAYS'],
                             'TP_next_7_days'   => $home_mod_strings['LBL_NEXT_7_DAYS'],
                             'TP_next_month'    => $home_mod_strings['LBL_NEXT_MONTH'],
                             'TP_next_year'     => $home_mod_strings['LBL_NEXT_YEAR'],
                             );
        
        $cal_dateformat = $timedate->get_cal_date_format();
        $str = "<select name='type_{$layout_def['name']}'>";
        $str .= get_select_options_with_id($filterTypes, (empty($layout_def['input_name0']) ? '' : $layout_def['input_name0']));
//        foreach($filterTypes as $value => $label) {
//            $str .= '<option value="' . $value . '">' . $label. '</option>';
//        }
        $str .= "</select>";
/*        $str .= "<input id='jscal_field{$layout_def['name']}' name='date_{$layout_def['name']}' onblur='parseDate(this, \"{$cal_dateformat}\");' tabindex='1' size='11' maxlength='10' type='text' value='{$layout_def['input_name0']}'> 
                <img src='{$image_path}jscalendar.gif' alt='{$cal_dateformat}' id='jscal_trigger' align='absmiddle'>
                <script type='text/javascript'>
                Calendar.setup ({
                    inputField : 'jscal_field{$layout_def['name']}', ifFormat : '{$cal_dateformat}', onClose: function(cal) { cal.hide();}, showsTime : false, button : 'jscal_trigger', singleClick : true, step : 1
                });
                </script>";*/
                
                
        return $str;
    }
}
?>

