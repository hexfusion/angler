<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {sugar_currency_format} function plugin
 *
 * Type:     function<br>
 * Name:     sugar_currency_format<br>
 * Purpose:  formats a number
 * 
 * @author Wayne Pan {wayne at sugarcrm.com}
 * @param array
 * @param Smarty
 */
function smarty_function_sugar_currency_format($params, &$smarty) {
    require_once('modules/Currencies/Currency.php');
    global $locale;
    
	if(!isset($params['var']) || $params['var'] == '') {  
        return '';
    }

    $smarty->_compile_source('evaluated template', $params['var'], $_var_compiled);
    
    $currencyId = $locale->getPrecedentPreference('currency');
    $convert = empty($currencyId) ? false : true;
    
	$_contents = format_number($params['var'], empty($params['round']) ? 2 : $params['round'], 
								$locale->getPrecedentPreference('default_currency_significant_digits'),
								array(
									'currency_symbol'	=> $locale->getPrecedentPreference('default_currency_symbol'),
									'currency_id'		=> $currencyId,
									'convert'			=> $convert,
								)
							);
/*	$_contents = format_number($params['var'], empty($params['round']) ? 3 : $params['round'], 
                               empty($params['decimals']) ? 2 : $params['decimals'],
                               empty($params['symbol']) ? array('currency_symbol' => true) : array());
*/
    if (!empty($params['assign'])) {
        $smarty->assign($params['assign'], $_contents);
    } else {
        return $_contents;
    }
}
?>
