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
 *********************************************************************************/

function additionalDetailsCampaign($fields) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Campaigns');
	}
		
	$overlib_string = '';
	
	if(!empty($fields['START_DATE'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_CAMPAIGN_START_DATE'] . '</b> ' . $fields['START_DATE'] . '<br>';

	if(!empty($fields['TRACKER_TEXT'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_TRACKER_TEXT'] . '</b> ' . $fields['TRACKER_TEXT'] . '<br>';
	if(!empty($fields['REFER_URL'])) 
		$overlib_string .= '<a target=_blank href='. $fields['REFER_URL'] . '>' . $fields['REFER_URL'] . '</a><br>';
	
	if(!empty($fields['OBJECTIVE'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_CAMPAIGN_OBJECTIVE'] . '</b> ' . substr($fields['OBJECTIVE'], 0, 300);
		if(strlen($fields['OBJECTIVE']) > 300) $overlib_string .= '...';
		$overlib_string .= '<br>';
	}	
	if(!empty($fields['CONTENT'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_CAMPAIGN_CONTENT'] . '</b> ' . substr($fields['CONTENT'], 0, 300);
		if(strlen($fields['CONTENT']) > 300) $overlib_string .= '...';
	}	
	
	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => "index.php?action=EditView&module=Campaigns&return_module=Campaigns&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=Campaigns&return_module=Campaigns&record={$fields['ID']}");
}
 
 ?>
 
 
