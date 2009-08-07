<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {sugar_evalcolumn} function plugin
 *
 * Type:     function<br>
 * Name:     sugar_evalcolumn<br>
 * Purpose:  evaluate a string by substituting values in the rowData parameter. Used for ListViews<br>
 * 
 * @author Wayne Pan {wayne at sugarcrm.com
 * @param array
 * @param Smarty
 */
function smarty_function_sugar_evalcolumn($params, &$smarty)
{
    if (!isset($params['var']) || !isset($params['rowData'])) {
    	if(!isset($params['var']))  
        	$smarty->trigger_error("evalcolumn: missing 'var' parameter");
    	if(!isset($params['rowData']))  
        	$smarty->trigger_error("evalcolumn: missing 'rowData' parameter");
        return;
    }

    if($params['var'] == '') {
        return;
    }

    $smarty->_compile_source('evaluated template', $params['var'], $_var_compiled);

	preg_match_all('/\{\$(.*)\}/U', $params['var'], $matches);

	for($wp = 0; $wp < count($matches[0]); $wp++) {
			$params['var'] = str_replace($matches[0][$wp], $params['rowData'][$matches[1][$wp]], $params['var']);	 
	}
	
	$_contents = $params['var'];

    if (!empty($params['assign'])) {
        $smarty->assign($params['assign'], $_contents);
    } else {
        return $_contents;
    }
}
?>
