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
require_once('include/generic/SugarWidgets/SugarWidgetFielddatetime.php');

class SugarWidgetFieldDate extends SugarWidgetFieldDateTime
{
        function & displayList($layout_def)
        {
                global $timedate;
                // i guess qualifier and column_function are the same..
                if (! empty($layout_def['column_function']))
                 {
                        $func_name = 'displayList'.$layout_def['column_function'];
                        if ( method_exists($this,$func_name))
                        {
                                $display = $this->$func_name($layout_def)." \n";
                                return $display;
                        }
                }
                $content = $this->displayListPlain($layout_def);
                
                // Get the time context of the date, important to calculate DST
                if(substr_count($layout_def['name'], 'date') > 0) { 
                    $layout_def_time = $layout_def;
                    $layout_def_time['name'] = str_replace('date', 'time', $layout_def_time['name']);
                    $time = $this->displayListPlain($layout_def_time);
                }
         
                $content = $this->displayListPlain($layout_def);
                
                if(!empty($time)) { // able to get the time context of the date            	
                	$td = explode(' ', $timedate->to_display_date_time($content . ' ' . $time));
	                return $td[0];
                }
                else { // assume there is no time context
                    $td = $timedate->to_display_date($content, false); // avoid php notice of returing by reference
                	return $td;
                }
        }

 function queryFilterBefore_old(&$layout_def)
 {
  global $timedate;










			return $this->_get_column_select($layout_def)."<'".PearDatabase::quote($layout_def['input_name0'])."'\n";



 }

 function queryFilterAfter_old(&$layout_def)
 {
  global $timedate;











  		return $this->_get_column_select($layout_def).">'".PearDatabase::quote($layout_def['input_name0'])."'\n";



 }

 function queryFilterBetween_Dates_old(&$layout_def)
 {
  global $timedate;








			return "(".$this->_get_column_select($layout_def).">='".PearDatabase::quote($layout_def['input_name0'])."' AND \n".  $this->_get_column_select($layout_def)."<='".PearDatabase::quote($layout_def['input_name1'])."')\n";



 }

    function queryFilterNot_Equals_str(& $layout_def) {
        global $timedate;
        
        if($this->getAssignedUser()) {
            $begin = $timedate->handle_offset($layout_def['input_name0'], $timedate->dbDayFormat, false, $this->assigned_user);
        }
        else {
            $begin = $layout_def['input_name0'];
        }

        if ($this->reporter->db->dbType == 'oci8') {




        } elseif($this->reporter->db->dbType == 'mssql') {
            return "".$this->_get_column_select($layout_def)."!='".PearDatabase :: quote($begin)."'\n";
        }else{
            return "ISNULL(".$this->_get_column_select($layout_def).") OR \n(".$this->_get_column_select($layout_def)."!='".PearDatabase :: quote($begin)."')\n";
        }

    }

    function queryFilterOn(& $layout_def) {
        global $timedate;
        
        if($this->getAssignedUser()) {
            $begin = $timedate->handle_offset($layout_def['input_name0'], $timedate->dbDayFormat, false, $this->assigned_user);
        }
        else {
            $begin = $layout_def['input_name0'];
        }






            return $this->_get_column_select($layout_def)."='".PearDatabase :: quote($begin)."'\n";



    }
    function queryFilterBefore(& $layout_def) {
        global $timedate;
        
        if($this->getAssignedUser()) {
            $begin = $timedate->handle_offset($layout_def['input_name0'], $timedate->dbDayFormat, false, $this->assigned_user);
        }
        else {
            $begin = $layout_def['input_name0'];
        }






            return $this->_get_column_select($layout_def)."<'".PearDatabase :: quote($begin)."'\n";




    }
    
    function queryFilterAfter(& $layout_def) {
        global $timedate;

        if($this->getAssignedUser()) {      
            $begin = $timedate->handle_offset($layout_def['input_name0'], $timedate->dbDayFormat, false, $this->assigned_user);
        }
        else {
            $begin = $layout_def['input_name0'];
        }






            return $this->_get_column_select($layout_def).">'".PearDatabase :: quote($begin)."'\n";



    }
    function queryFilterBetween_Dates(& $layout_def) {
        global $timedate;
        
        if($this->getAssignedUser()) {
            $begin = $timedate->handle_offset($layout_def['input_name0'], $timedate->dbDayFormat, false, $this->assigned_user);
            $end = $timedate->handle_offset($layout_def['input_name1'], $timedate->dbDayFormat, false, $this->assigned_user);
        }
        else {
            $begin = $layout_def['input_name0'];
            $end = $layout_def['input_name1'];
        }






            return "(".$this->_get_column_select($layout_def).">='".PearDatabase :: quote($begin)."' AND \n".$this->_get_column_select($layout_def)."<='".PearDatabase :: quote($end)."')\n";



    }
    
 
}

?>
