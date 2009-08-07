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
/*********************************************************************************
 * $Id: CurrencyService.php,v 1.2 2006/06/06 17:57:47 majed Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

if(!class_exists('PearDatabase')) {
	require_once('include/database/PearDatabase');
}

class CurrencyService {
	var $currencyDefault;
	var $currencyFrom;
	var $currencyTo;

	var $numbers;
	var $db;
	
	/**
	 * sole constructor
	 */
	function CurrencyService() {
		global $sugar_config;
		
		$this->db = &PearDatabase::getInstance();
		
	}
	
	/**
	 * inserts default (usually US Dollar) as default currency
	 */
	function insertDefaults() {
		global $sugar_config;
		
		$insert=true;
		
		if($insert) {
			$q = "INSERT INTO currencies (id, name, symbol, iso4217, conversion_rate, status, deleted, date_entered, date_modified, created_by)
					VALUES('".create_guid()."', 
						'{$sugar_config['default_currency_name']}',
						'{$sugar_config['default_currency_symbol']}',
						'{$sugar_config['default_currency_iso4217']}',
						1.0, 'Active', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '1')";
		}	
	}
	
} // end class def
?>
