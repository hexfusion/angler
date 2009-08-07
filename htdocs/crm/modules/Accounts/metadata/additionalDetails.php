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

function additionalDetailsAccount($fields) {
	static $mod_strings;
	global $app_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Accounts');
	}
	$overlib_string = '';
	
	if(!empty($fields['BILLING_ADDRESS_STREET']) || !empty($fields['BILLING_ADDRESS_CITY']) ||
		!empty($fields['BILLING_ADDRESS_STATE']) || !empty($fields['BILLING_ADDRESS_POSTALCODE']) ||
		!empty($fields['BILLING_ADDRESS_COUNTRY']))
			$overlib_string .= '<b>' . $mod_strings['LBL_BILLING_ADDRESS'] . '</b><br>';
	if(!empty($fields['BILLING_ADDRESS_STREET'])) $overlib_string .= $fields['BILLING_ADDRESS_STREET'] . '<br>';
	if(!empty($fields['BILLING_ADDRESS_STREET_2'])) $overlib_string .= $fields['BILLING_ADDRESS_STREET_2'] . '<br>';
	if(!empty($fields['BILLING_ADDRESS_STREET_3'])) $overlib_string .= $fields['BILLING_ADDRESS_STREET_3'] . '<br>';
	if(!empty($fields['BILLING_ADDRESS_STREET_4'])) $overlib_string .= $fields['BILLING_ADDRESS_STREET_4'] . '<br>';
	if(!empty($fields['BILLING_ADDRESS_CITY'])) $overlib_string .= $fields['BILLING_ADDRESS_CITY'] . ', ';
	if(!empty($fields['BILLING_ADDRESS_STATE'])) $overlib_string .= $fields['BILLING_ADDRESS_STATE'] . ' ';
	if(!empty($fields['BILLING_ADDRESS_POSTALCODE'])) $overlib_string .= $fields['BILLING_ADDRESS_POSTALCODE'] . ' ';
	if(!empty($fields['BILLING_ADDRESS_COUNTRY'])) $overlib_string .= $fields['BILLING_ADDRESS_COUNTRY'] . '<br>';
	
	if(strlen($overlib_string) > 0 && !(strrpos($overlib_string, '<br>') == strlen($overlib_string) - 4)) 
		$overlib_string .= '<br>';  
	
	if(!empty($fields['PHONE_FAX'])) $overlib_string .= '<b>'. $mod_strings['LBL_FAX'] . '</b> ' . $fields['PHONE_FAX'] . '<br>';
	if(!empty($fields['PHONE_ALTERNATE'])) $overlib_string .= '<b>'. $mod_strings['LBL_PHONE_ALT'] . '</b> ' . $fields['PHONE_ALTERNATE'] . '<br>';
	if(!empty($fields['WEBSITE'])) 
		$overlib_string .= '<a target=_blank href=http://'. $fields['WEBSITE'] . '>' . $fields['WEBSITE'] . '</a><br>';
	if(!empty($fields['INDUSTRY'])) $overlib_string .= '<b>'. $mod_strings['LBL_INDUSTRY'] . '</b> ' . $fields['INDUSTRY'] . '<br>';
	if(!empty($fields['DESCRIPTION'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
	}	

	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => "index.php?action=EditView&module=Accounts&return_module=Accounts&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=Accounts&return_module=Accounts&record={$fields['ID']}");
}
 
 ?>
 
 
