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

                                                                                       
require_once('include/generic/SugarWidgets/SugarWidget.php');
                                                                                       
require_once('modules/Currencies/Currency.php');
global $current_user;
                                                                                       
$global_currency_obj = null;
                                                                                       
function get_currency()
{
        global $current_user,$global_currency_obj;
        if (empty($global_currency_obj))
        {
        $global_currency_obj = new Currency();
      //  $global_currency_symbol = '$';
                                                                                       
        if($current_user->getPreference('currency') )
        {
                $global_currency_obj->retrieve($current_user->getPreference('currency'));
        }
        else
        {
                $global_currency_obj->retrieve('-99');
        }
        }
        return $global_currency_obj;
}


class SugarWidgetFieldCurrency extends SugarWidgetFieldInt
{
        function & displayList($layout_def)
        {
//                $global_currency_obj = get_currency();
                  $display = format_number($this->displayListPlain($layout_def), 2, 2, array('convert' => true, 'currency_symbol' => true));
//                $display =  $global_currency_obj->symbol. round($global_currency_obj->convertFromDollar($this->displayListPlain($layout_def)),2);

                return $display;
        }
                                                                                       
 function queryFilterEquals(&$layout_def)
 {
		$global_currency_obj = get_currency();
                return $this->_get_column_select($layout_def)."=".PearDatabase::quote( round($global_currency_obj->convertToDollar($layout_def['input_name0'])))."\n";
 }
                                                                                       
 function queryFilterNot_Equals(&$layout_def)
 {
		$global_currency_obj = get_currency();
                return $this->_get_column_select($layout_def)."!=".PearDatabase::quote( round($global_currency_obj->convertToDollar($layout_def['input_name0'])))."\n";
 }
                                                                                       
 function queryFilterGreater(&$layout_def)
 {
		$global_currency_obj = get_currency();
                return $this->_get_column_select($layout_def)." > ".PearDatabase::quote( round($global_currency_obj->convertToDollar($layout_def['input_name0'])))."\n";
 }
                                                                                       
 function queryFilterLess(&$layout_def)
 {
		$global_currency_obj = get_currency();
                return $this->_get_column_select($layout_def)." < ".PearDatabase::quote( round($global_currency_obj->convertToDollar($layout_def['input_name0'])))."\n";
 }

 function queryFilterBetween(&$layout_def){
 	$global_currency_obj = get_currency();
    return $this->_get_column_select($layout_def)." > ".PearDatabase::quote( round($global_currency_obj->convertToDollar($layout_def['input_name0']))). " AND ". $this->_get_column_select($layout_def)." < ".PearDatabase::quote( round($global_currency_obj->convertToDollar($layout_def['input_name1'])))."\n";
 }


}

?>
