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
 
require_once('include/utils.php');

function additionalDetailsBug($fields) {
	static $mod_strings;
	global $app_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Bugs');
	}
		
	$overlib_string = '';

	if(!empty($fields['DATE_ENTERED'])) 
		$overlib_string .= '<b>'. $app_strings['LBL_DATE_ENTERED'] . '</b> ' . $fields['DATE_ENTERED'] . '<br>';
	if(!empty($fields['SOURCE'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_SOURCE'] . '</b> ' . $fields['SOURCE'] . '<br>';
	if(!empty($fields['PRODUCT_CATEGORY'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_PRODUCT_CATEGORY'] . '</b> ' . $fields['PRODUCT_CATEGORY'] . '<br>';
	if(!empty($fields['RESOLUTION'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_RESOLUTION'] . '</b> ' . $fields['RESOLUTION'] . '<br>';
				
	if(!empty($fields['DESCRIPTION'])) { 
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
		$overlib_string .= '<br>';
	}
		
	if(!empty($fields['WORK_LOG'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_WORK_LOG'] . '</b> ' . substr($fields['WORK_LOG'], 0, 300);
		if(strlen($fields['WORK_LOG']) > 300) $overlib_string .= '...';
	}	

	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => "index.php?action=EditView&module=Bugs&return_module=Bugs&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=Bugs&return_module=Bugs&record={$fields['ID']}");

}
 
 ?>
 
 
