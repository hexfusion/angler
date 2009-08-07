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
 * $Id: Currency.php,v 1.54 2006/08/18 00:51:40 chris Exp $
 ********************************************************************************/



require_once('data/SugarBean.php');

// Contact is used to store customer information.
class Currency extends SugarBean
{
	// Stored fields
	var $id;
	var $iso4217;
	var $name;
	var $status;
	var $conversion_rate;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $symbol;
	var $hide = '';
	var $unhide = '';
	var $field_name_map;

	var $table_name = "currencies";
	var $object_name = "Currency";
	var $module_dir = "Currencies";
	var $new_schema = true;
	
	var $disable_num_format = true;
	
    function Currency()
	{
		parent::SugarBean();
		$this->field_defs['hide'] = array('name'=>'hide', 'source'=>'non-db', 'type'=>'varchar','len'=>25);
		$this->field_defs['unhide'] = array('name'=>'unhide', 'source'=>'non-db', 'type'=>'varchar','len'=>25);

		$this->disable_row_level_security =true;

	}


	function convertToDollar($amount, $precision = 6) {
		return round(($amount / $this->conversion_rate), $precision);
	}

    /**
     * convert amount from base("usdollar") to selected currency.
     */
	function convertFromDollar($amount, $precision = 6){

		return round(($amount * $this->conversion_rate), $precision);
	}

	function getDefaultCurrencyName(){
		global $sugar_config;
		return $sugar_config['default_currency_name'];
	}

	function getDefaultCurrencySymbol(){
		global $sugar_config;
		return $sugar_config['default_currency_symbol'];
	}

	function getDefaultISO4217(){
		global $sugar_config;
		return $sugar_config['default_currency_iso4217'];
	}

	function retrieveIDBySymbol($symbol, $encode = true){
	 	$query = "select id from currencies where symbol='$symbol' and deleted=0;";
	 	$result = $this->db->query($query);
	 	if($result){
	 	$row = $this->db->fetchByAssoc($result);
	 	if($row){
	 		return $row['id'];
	 	}
	 	}
	 	return '';
	 }


	 function list_view_parse_additional_sections(&$list_form)
	{
		global $isMerge;

		if(isset($isMerge) && $isMerge && $this->id != '-99'){
		$list_form->assign('PREROW', '<input name="mergecur[]" type="checkbox" value="'.$this->id.'">');
		}
		return $list_form;
	}
	function retrieve_id_by_name($name) {
	 	$query = "select id from currencies where name='$name' and deleted=0;";
	 	$result = $this->db->query($query);
	 	if($result){
	 	$row = $this->db->fetchByAssoc($result);
	 	if($row){
	 		return $row['id'];
	 	}
	 	}
	 	return '';		
	}
	
     function retrieve($id, $encode = true){
     	if($id == '-99'){
     		$this->name = 	$this->getDefaultCurrencyName();
     		$this->symbol = $this->getDefaultCurrencySymbol();
     		$this->id = '-99';
     		$this->conversion_rate = 1;
     		$this->iso4217 = $this->getDefaultISO4217();
     		$this->deleted = 0;
     		$this->status = 'Active';
     		$this->hide = '<!--';
     		$this->unhide = '-->';
     	}else{
     		parent::retrieve($id, $encode);
     	}
     	if(!isset($this->name) || $this->deleted == 1){
     		$this->name = 	$this->getDefaultCurrencyName();
     		$this->symbol = $this->getDefaultCurrencySymbol();
     		$this->conversion_rate = 1;
     		$this->iso4217 = $this->getDefaultISO4217();
     		$this->id = '-99';
     		$this->deleted = 0;
     		$this->status = 'Active';
     		$this->hide = '<!--';
     		$this->unhide = '-->';
     	}

     }
     
    /**
     * Method for returning the currency symbol, must return chr(2) for the € symbol
     * to display correctly in pdfs
     * Parameters:
     * 	none
     * Returns:
     * 	$symbol otherwise chr(2) for euro symbol
     */
     function getPdfCurrencySymbol() {
     	if($this->symbol == '&#8364;') 
     		return chr(2);
     	return $this->symbol;
     }
	function get_list_view_data() {
		$this->conversion_rate = format_number($this->conversion_rate, 10, 10);
		$data = parent::get_list_view_data();
		return $data;
	}
} // end currency class

/**
 * function format_number($amount, $round = 2, $decimals = 2, $params = array()) 
 * 
 * number formatting
 *
 * @param FLOAT $amount - # to be converted
 * @param INT $round - # of places to round (can be -)
 * @param INT $decimals - floating point precision
 * 
 * The following are passed in as an array of params:
 * @param BOOL $params['currency_symbol'] - true to display currency symbol
 * @param BOOL $params['convert'] - true to convert from USD dollar
 * @param BOOL $params['percentage'] - true to display % sign
 * @param BOOL $params['symbol_space'] - true to have space between currency symbol and amount
 * @param STRING $params['symbol_override'] - string to over default currency symbol
 * @param STRING $params['type'] - pass in 'pdf' for pdf currency symbol conversion
 * @param GUID $params['currency_id'] - currency_id to retreive, defaults to current user
 * 
 * @return STRING $amount - formatted number 
 */
function format_number($amount, $round = 2, $decimals = 2, $params = array()) {
	global $app_strings, $current_user, $sugar_config, $locale;
	static $current_users_currency = null;
	static $last_override_currency = null;
	static $override_currency_id = null;
	static $currency;
	
	$seps = get_number_seperators();
	$num_grp_sep = $seps[0];
	$dec_sep = $seps[1];
	
	// only create a currency object if we need it
	if((!empty($params['currency_symbol']) && $params['currency_symbol']) ||
	   (!empty($params['convert']) && $params['convert']) ||
	   (!empty($params['currency_id']))) {
	   		// if we have an override currency_id
	   		if(!empty($params['currency_id'])) {
	   			if($override_currency_id != $params['currency_id']) {
		   			$override_currency_id = $params['currency_id'];
		   			$currency = new Currency();
		   			$currency->retrieve($override_currency_id);
		   			$last_override_currency = $currency;
	   			} 
	   			else {
	   				$currency = $last_override_currency;
	   			}
	   			
	   		}
			elseif(!isset($current_users_currency)) { // else use current user's
				$current_users_currency = new Currency();
				if($current_user->getPreference('currency')) $current_users_currency->retrieve($current_user->getPreference('currency'));
				else $current_users_currency->retrieve('-99'); // use default if none set
				$currency = $current_users_currency;
			}
	}
	
	if(!empty($params['convert']) && $params['convert']) {
		$amount = $currency->convertFromDollar($amount, 6);
	}

	if(!empty($params['currency_symbol']) && $params['currency_symbol']) {
		if(!empty($params['symbol_override'])) {
			$symbol = $params['symbol_override'];
		}
		elseif(!empty($params['type']) && $params['type'] == 'pdf') {
			$symbol = $currency->getPdfCurrencySymbol();
			$symbol_space = false;
		} else {
			if(empty($currency->symbol))
				$symbol = $currency->getDefaultCurrencySymbol();
			else 
				$symbol = $currency->symbol;
			$symbol_space = true;
		}
	} else {
		$symbol = '';
	}
	
	if(isset($params['charset_convert'])) {
		$symbol = $locale->translateCharset($symbol, 'UTF-8', $locale->getExportCharset());
	}
	
	//TODO: display human readable - easy
	$human = false;
	
	if($human == false) {
		$amount = number_format(round($amount, $round), $decimals, $dec_sep, $num_grp_sep);
		$amount = format_place_symbol($amount, $symbol, (empty($params['symbol_space']) ? false : true));		
	} else {
	//call for the switch
		if($amount > 1000) {
			$amount = round(($amount / 1000), 0);
			$amount = $amount . 'k';
			$amount = format_place_symbol($amount, $symbol, (empty($params['symbol_space']) ? false : true));				
		} else {
			$amount = format_place_symbol($amount, $symbol, (empty($params['symbol_space']) ? false : true));		
		}		
	//end call for switch
	}
	
	if(!empty($params['percentage']) && $params['percentage']) $amount .= $app_strings['LBL_PERCENTAGE_SYMBOL'];
	return $amount;	
} //end function format_number


function format_place_symbol($amount, $symbol, $symbol_space) {
	if($symbol != '') {
		if($symbol_space == true) {
			$amount = $symbol . '&nbsp;' . $amount;
		} else {
			$amount = $symbol . $amount;
		}	
	}
	return $amount;	
}	

function unformat_number($string) {
	static $currency = null;
	if(!isset($currency)) {
		global $current_user;
		$currency = new Currency();
		if($current_user->getPreference('currency')) $currency->retrieve($current_user->getPreference('currency'));
		else $currency->retrieve('-99'); // use default if none set
	}
	
	$seps = get_number_seperators();
	// remove num_grp_sep and replace decimal seperater with decimal
	$string = trim(str_replace(array($seps[0], $seps[1]), array('', '.'), $string));
	$string = preg_replace('/^' . preg_quote($currency->symbol) . '/', '',  $string); // remove currency symbol in the beginning of there is one	
	
	return trim($string);
}

// deprecated use format_number() above
function format_money($amount, $for_display = TRUE )
{
	// This function formats an amount for display.
	// Later on, this should be converted to use proper thousand and decimal seperators
	// Currently, it stays closer to the existing format, and just rounds to two decimal points
	if ( isset($amount) )
	{
		if ( $for_display )
		{
			return sprintf("%0.02f",$amount);
		}
		else
		{
			// If it's an editable field, don't use a thousand seperator.
			// Or perhaps we will want to, but it doesn't matter right now.
			return sprintf("%0.02f",$amount);
		}
	}
	else
	{
		return;
	}
}

// returns the array(1000s seperator, decimal seperator)
function get_number_seperators() {
	global $current_user, $sugar_config;
	
	static $dec_sep = null;
	static $num_grp_sep = null;
	
	if($dec_sep == null) {
		$user_dec_sep = $current_user->getPreference('dec_sep');
		$dec_sep = (empty($user_dec_sep) ? $sugar_config['default_decimal_seperator'] : $user_dec_sep);
	}
	if($num_grp_sep == null) {
 		$user_num_grp_sep = $current_user->getPreference('num_grp_sep');
		$num_grp_sep = (empty($user_num_grp_sep) ? $sugar_config['default_number_grouping_seperator'] : $user_num_grp_sep);
	}
	
	return array($num_grp_sep, $dec_sep);	
}

?>
