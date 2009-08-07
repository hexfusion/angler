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

class SugarWidgetFieldTime extends SugarWidgetFieldDateTime
{
        function displayList($layout_def)
        {
                global $timedate;
                // i guess qualifier and column_function are the same..
                if (! empty($layout_def['column_function']))
                 {
                        $func_name = 'displayList'.$layout_def['column_function'];
                        if ( method_exists($this,$func_name))
                        {
                                return $this->$func_name($layout_def)." \n";
                        }
                }
                
                // Get the date context of the time, important for DST
                $layout_def_date = $layout_def;
                $layout_def_date['name'] = str_replace('time', 'date', $layout_def_date['name']);
                $date = $this->displayListPlain($layout_def_date);
                
                $content = $this->displayListPlain($layout_def);
                
                if(!empty($date)) { // able to get the date context of the time            	
                	$td = explode(' ', $timedate->to_display_date_time($date . ' ' . $content));
	                return $td[1];
                }
                else { // assume there is no time context
                 	return $timedate->to_display_time($content);
                }
        }
}

?>
