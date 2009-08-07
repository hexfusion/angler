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
 

function additionalDetailsOpportunity($fields) {
	global $current_language;
	$mod_strings = return_module_language($current_language, 'Opportunities');
	$overlib_string = '';
	
	if(!empty($fields['LEAD_SOURCE'])) $overlib_string .= '<b>'. $mod_strings['LBL_LEAD_SOURCE'] . '</b> ' . $fields['LEAD_SOURCE'] . '<br>';
	if(!empty($fields['PROBABILITY'])) $overlib_string .= '<b>'. $mod_strings['LBL_PROBABILITY'] . '</b> ' . $fields['PROBABILITY'] . '<br>';
	if(!empty($fields['NEXT_STEP'])) $overlib_string .= '<b>'. $mod_strings['LBL_NEXT_STEP'] . '</b> ' . $fields['NEXT_STEP'] . '<br>';
	if(!empty($fields['OPPORTUNITY_TYPE'])) $overlib_string .= '<b>'. $mod_strings['LBL_TYPE'] . '</b> ' . $fields['OPPORTUNITY_TYPE'] . '<br>';

	if(!empty($fields['DESCRIPTION'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ';
		$overlib_string .= substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
	}	
	
	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => "index.php?action=EditView&module=Opportunities&return_module=Opportunities&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=Opportunities&return_module=Opportunities&record={$fields['ID']}");
}
 
 ?>
 
