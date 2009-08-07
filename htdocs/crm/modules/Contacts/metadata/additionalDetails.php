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

function additionalDetailsContact($fields) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Contacts');
	}
	 
	$overlib_string = '';
	if(!empty($fields['PRIMARY_ADDRESS_STREET']) || !empty($fields['PRIMARY_ADDRESS_CITY']) ||
		!empty($fields['PRIMARY_ADDRESS_STATE']) || !empty($fields['PRIMARY_ADDRESS_POSTALCODE']) ||
		!empty($fields['PRIMARY_ADDRESS_COUNTRY']))
			$overlib_string .= '<b>' . $mod_strings['LBL_PRIMARY_ADDRESS'] . '</b><br>';
	if(!empty($fields['PRIMARY_ADDRESS_STREET'])) $overlib_string .= $fields['PRIMARY_ADDRESS_STREET'] . '<br>';
	if(!empty($fields['PRIMARY_ADDRESS_STREET_2'])) $overlib_string .= $fields['PRIMARY_ADDRESS_STREET_2'] . '<br>';
	if(!empty($fields['PRIMARY_ADDRESS_STREET_3'])) $overlib_string .= $fields['PRIMARY_ADDRESS_STREET_3'] . '<br>';
	if(!empty($fields['PRIMARY_ADDRESS_CITY'])) $overlib_string .= $fields['PRIMARY_ADDRESS_CITY'] . ', ';
	if(!empty($fields['PRIMARY_ADDRESS_STATE'])) $overlib_string .= $fields['PRIMARY_ADDRESS_STATE'] . ' ';
	if(!empty($fields['PRIMARY_ADDRESS_POSTALCODE'])) $overlib_string .= $fields['PRIMARY_ADDRESS_POSTALCODE'] . ' ';
	if(!empty($fields['PRIMARY_ADDRESS_COUNTRY'])) $overlib_string .= $fields['PRIMARY_ADDRESS_COUNTRY'] . '<br>';
	if(strlen($overlib_string) > 0 && !(strrpos($overlib_string, '<br>') == strlen($overlib_string) - 4)) 
		$overlib_string .= '<br>';  
	if(!empty($fields['PHONE_MOBILE'])) $overlib_string .= '<b>'. $mod_strings['LBL_MOBILE_PHONE'] . '</b> ' . $fields['PHONE_MOBILE'] . '<br>';
	if(!empty($fields['PHONE_HOME'])) $overlib_string .= '<b>'. $mod_strings['LBL_HOME_PHONE'] . '</b> ' . $fields['PHONE_HOME'] . '<br>';
	if(!empty($fields['PHONE_OTHER'])) $overlib_string .= '<b>'. $mod_strings['LBL_OTHER_PHONE'] . '</b> ' . $fields['PHONE_OTHER'] . '<br>';

	if(!empty($fields['DATE_MODIFIED'])) $overlib_string .= '<b>'. $mod_strings['LBL_DATE_MODIFIED'] . '</b> ' . $fields['DATE_MODIFIED'] . '<br>';
	
	if(!empty($fields['DESCRIPTION'])) { 
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
	}	
	
	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => "index.php?action=EditView&module=Contacts&return_module=Contacts&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=Contacts&return_module=Contacts&record={$fields['ID']}");
}
 
 ?>
 
 
