<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
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

 // $Id: StudioUpgradeParser.php,v 1.4 2006/08/22 19:59:34 awu Exp $


require_once('modules/Studio/parsers/StudioParser.php');
class StudioUpgradeParser extends StudioParser{
/**
	 * UPDATE SLOTS - CODE TO UPGRADE SLOTS IN EXISTING FILES
	 * SWITCHES SLOTS TO SPANS
	 */
	function parseSlots($str) {
		preg_match_all("'<[ ]*slot[^>]*>(.*?)</[ ]*slot[^>]*>'si", $str, $this->positions, PREG_SET_ORDER);
	}
	
	function parseTDSlots($str) {
	     $slotTds = array();
		preg_match_all("'(.*?)(<\[ ]*td[^>]*>\s*<[ ]*td[^>]*>)(.*?)'si", $str, $slotTds, PREG_SET_ORDER);
		return $slotTds;
	}

	function cleanUpSlots() {
	    return;
		$totalPositions = count($this->positions);
		foreach ($this->positions as $key => $position) {
			if ($key < $totalPositions -1) {

				if (substr_count($position[0], 'MOD.') > 0) {
					if (substr_count($this->positions[$key +1][0], 'MOD.') > 0) {
						unset ($this->positions[$key]);
					}
				}
			}
		}
		$this->positions = array_values($this->positions);
	}
	function replaceH4Slots($string){
	 
	    return  preg_replace("'(<[ ]*h4[^>]*>)<[ ]*slot[^>]*>(.*?)</[ ]*slot[^>]*>(</[ ]*h4[^>]*>)'si", '$1$2$3', $string);
	   
	}
	function repairSlotTDS($string){
	    $tds  = $this->parseTDSlots($string);
	    for ($i = 0; $i < sizeof($tds); $i ++) {
	        $td = $tds[$i];
	        if(!empty($td)){
	            print_r($td);
	            die();
	        }
	    }
	}
	function upgradeSlots() {
	   
		$view = $this->curText;
		
		
		$counter = 0;
		$return_view = '';
		$slotCount = 0;
		for ($i = 0; $i < sizeof($this->positions); $i ++) {
			$slot = $this->positions[$i];
			if ($i % 2 == 0) {
				$slotCount ++;
				$displayCount = $slotCount;
			} else {
				$displayCount = $slotCount.'b';
			}
            
			$explode = explode($slot[0], $view, 2);
			$explode[0] .= "<span sugar='slot$displayCount'>";
			$explode[1] = "</span sugar='slot'>".$explode[1];
			$this->repairSlotTDS($slot[1]);
			$return_view .= $explode[0].$slot[1];
			$view = $explode[1];
			$counter ++;
		}
		$newView = $return_view.$view;
		$newView = str_replace(array ('<slot>', '</slot>'), array ('', ''), $newView);
		
		return $newView;
	}
	
}
?>
