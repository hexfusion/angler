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
 
require_once('include/EditView/QuickCreate.php');
require_once('modules/Calls/Call.php');
require_once('include/javascript/javascript.php');

class CallsQuickCreate extends QuickCreate {
    
    var $javascript;
    
    function process() {
        global $current_user, $timedate, $app_list_strings, $current_language, $mod_strings;
        $mod_strings = return_module_language($current_language, 'Calls');
        
        parent::process();

		$this->ss->assign("TIME_FORMAT", '('. $timedate->get_user_time_format().')');
		$this->ss->assign("USER_DATEFORMAT", '('. $timedate->get_user_date_format().')');
		$this->ss->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());

        
        if($this->viaAJAX) { // override for ajax call
            $this->ss->assign('saveOnclick', "onclick='if(check_form(\"callsQuickCreate\")) return SUGAR.subpanelUtils.inlineSave(this.form.id, \"activities\"); else return false;'");
            $this->ss->assign('cancelOnclick', "onclick='return SUGAR.subpanelUtils.cancelCreate(\"subpanel_activities\")';");
        }
        
        $this->ss->assign('viaAJAX', $this->viaAJAX);

        $this->javascript = new javascript();
        $this->javascript->setFormName('callsQuickCreate');
        
        $focus = new Call();
        $this->javascript->setSugarBean($focus);
        $this->javascript->addAllFields('');
        
		if (is_null($focus->date_start))
			$focus->date_start = $timedate->to_display_date(gmdate('Y-m-d H:i:s'));
		if (is_null($focus->time_start))
			$focus->time_start = $timedate->to_display_time(gmdate('Y-m-d H:i:s'), true);
		if (!isset ($focus->duration_hours))
			$focus->duration_hours = "1";

		$this->ss->assign("DATE_START", $focus->date_start);
		$this->ss->assign("TIME_START", substr($focus->time_start,0,5));
		$time_start_hour = intval(substr($focus->time_start, 0, 2));
		$time_start_minutes = substr($focus->time_start, 3, 5);
		
		if ($time_start_minutes > 0 && $time_start_minutes < 15) {
			$time_start_minutes = "15";
		} else
			if ($time_start_minutes > 15 && $time_start_minutes < 30) {
				$time_start_minutes = "30";
			} else
				if ($time_start_minutes > 30 && $time_start_minutes < 45) {
					$time_start_minutes = "45";
				} else
					if ($time_start_minutes > 45) {
						$time_start_hour += 1;
						$time_start_minutes = "00";
					}
		
		$hours_arr = array ();
		$num_of_hours = 13;
		$start_at = 1;
		
		if (empty ($time_meridiem)) {
			$num_of_hours = 24;
			$start_at = 0;
		}
		
		for ($i = $start_at; $i < $num_of_hours; $i ++) {
			$i = $i."";
			if (strlen($i) == 1) {
				$i = "0".$i;
			}
			$hours_arr[$i] = $i;
		}

        $this->ss->assign("TIME_START_HOUR_OPTIONS", get_select_options_with_id($hours_arr, $time_start_hour));
		$this->ss->assign("TIME_START_MINUTE_OPTIONS", get_select_options_with_id($focus->minutes_values, $time_start_minutes));

		$this->ss->assign("DURATION_HOURS", $focus->duration_hours);
		$this->ss->assign("DURATION_MINUTES_OPTIONS", get_select_options_with_id($focus->minutes_values, $focus->duration_minutes));

		$focus->direction = (isset ($app_list_strings['call_direction_dom']['Outbound']) ? 'Outbound' : $focus->direction);
		$focus->status = (isset ($app_list_strings['call_status_dom']['Planned']) ? 'Outbound' : $focus->status);

		$this->ss->assign("DIRECTION_OPTIONS", get_select_options_with_id($app_list_strings['call_direction_dom'], $focus->direction));
		$this->ss->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['call_status_dom'], $focus->status));

        $this->ss->assign('additionalScripts', $this->javascript->getScript(false));
    }   
}
?>
