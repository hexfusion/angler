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
 * $Id: UnifiedSearchAdvanced.php,v 1.21 2006/08/24 22:06:07 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('include/Sugar_Smarty.php');
require_once('modules/ACL/ACLController.php');

class UnifiedSearchAdvanced {
	
	function getDropDownDiv($tpl = 'modules/Home/UnifiedSearchAdvanced.tpl') {
        global $app_list_strings;
        
		if(!file_exists('cache/modules/unified_search_modules.php')) 
			$this->buildCache();
		include('cache/modules/unified_search_modules.php');

		global $mod_strings, $modListHeader, $app_list_strings, $current_user, $app_strings, $image_path, $beanList;
		$users_modules = $current_user->getPreference('globalSearch', 'search');
		
		if(!isset($users_modules)) { // preferences are empty, select all
			$users_modules = array();
			foreach($unified_search_modules as $module=>$data) {
				$users_modules[$module] = $beanList[$module];
			}
			$current_user->setPreference('globalSearch', $users_modules, 0, 'search');
		}
		$sugar_smarty = new Sugar_Smarty();
		
		$modules_to_search = array();
		foreach($unified_search_modules as $module => $data) {
			if(array_key_exists($module, $modListHeader)) {
				if(ACLController :: checkAccess($module, 'list')) {
					$modules_to_search[$module] = array('translated' => $app_list_strings['moduleList'][$module]);
					if(array_key_exists($module, $users_modules)) $modules_to_search[$module]['checked'] = true;
					else $modules_to_search[$module]['checked'] = false;
				}
			}
		}
		
		if(!empty($_REQUEST['query_string'])) $sugar_smarty->assign('query_string', $_REQUEST['query_string']);
        else $sugar_smarty->assign('query_string', '');
		$sugar_smarty->assign('IMAGE_PATH', $image_path);
        if (file_exists($image_path . 'searchButton.gif')) {
            $sugar_smarty->assign('USE_SEARCH_GIF', 1);
            $sugar_smarty->assign('LBL_SEARCH_BUTTON_LABEL', $app_strings['LBL_SEARCH_BUTTON_LABEL']);
        }    
        else {
            $sugar_smarty->assign('USE_SEARCH_GIF', 0);
            $sugar_smarty->assign('LBL_SEARCH_BUTTON_LABEL', $app_strings['LBL_GO_BUTTON_LABEL']);
        }
		$sugar_smarty->assign('MODULES_TO_SEARCH', $modules_to_search);
		$sugar_smarty->debugging = true;

		return $sugar_smarty->fetch($tpl);
	}
	
	function search() {
        if(!file_exists('cache/modules/unified_search_modules.php')) 
            $this->buildCache();
		include('cache/modules/unified_search_modules.php');
		require_once('include/ListView/ListViewSmarty.php');
		require_once('include/utils.php');

		global $modListHeader, $beanList, $beanFiles, $current_language, $app_strings, $current_user, $mod_strings;
        $home_mod_strings = return_module_language($current_language, 'Home');
        
		$overlib = true;
		$_REQUEST['query_string'] = PearDatabase::quote(from_html(clean_string($_REQUEST['query_string'], 'UNIFIED_SEARCH')));

        if(!empty($_REQUEST['advanced']) && $_REQUEST['advanced'] != 'false') {
            $modules_to_search = array(); 
    		foreach($_REQUEST as $param => $value) {
    			if(preg_match('/^search_mod_(.*)$/', $param, $match)) {
    					$modules_to_search[$match[1]] = $beanList[$match[1]];
    			}
    		}
           $current_user->setPreference('globalSearch', $modules_to_search, 0, 'search'); // save selections to user preference
        }
        else {
            $users_modules = $current_user->getPreference('globalSearch', 'search');
    		if(isset($users_modules)) { // use user's previous selections 
    			$modules_to_search = $users_modules;
    		}
            else { // select all the modules (ie first time user has used global search)
                foreach($unified_search_modules as $module=>$data) {
                    $modules_to_search[$module] = $beanList[$module];
                }

                $current_user->setPreference('globalSearch', $modules_to_search, 'search');
            }
        }
		echo $this->getDropDownDiv('modules/Home/UnifiedSearchAdvancedForm.tpl');
		
		$module_results = array();
		$module_counts = array();
		$has_results = false;

        if(!empty($_REQUEST['query_string'])) {
    		foreach($modules_to_search as $name => $beanName) {
    			if(array_key_exists($name, $modListHeader)) {
    				$where_clauses_array = array();
    				foreach($unified_search_modules[$name]['fields'] as $field=>$def) {
                        $clause = '';
                        if(isset($def['table']))  {// if field is from joining table
                            $clause = "{$def['table']}.{$def['rname']} ";
                        }
                        else {
                            $clause = "{$unified_search_modules[$name]['table']}.$field ";
                        }

                        switch($def['type']) {
                            case 'int':
                                if(is_numeric($_REQUEST['query_string']))  
                                    $clause .=  "in ('{$_REQUEST['query_string']}')";
                                else
                                    $clause .=  "in ('-1')";
                                break;
                            default:
                                $clause .=  "LIKE '{$_REQUEST['query_string']}%'";
                                break;    
                        }

                        array_push($where_clauses_array, $clause);
    				}
    
    				$where = implode(' or ', $where_clauses_array);
    				
    				require_once($beanFiles[$beanName]);
    				$seed = new $beanName();
    				
    				$lv = new ListViewSmarty();
    				$lv->lvd->additionalDetails = false;
    				$mod_strings = return_module_language($current_language, $seed->module_dir);
    				if(file_exists('custom/modules/'.$seed->module_dir.'/metadata/listviewdefs.php')){
    					require_once('custom/modules/'.$seed->module_dir.'/metadata/listviewdefs.php');	
    				}else{
    					require_once('modules/'.$seed->module_dir.'/metadata/listviewdefs.php');
    				}
                    $displayColumns = array();
    				foreach($listViewDefs[$seed->module_dir] as $colName => $param) {
                        if(!empty($param['default']) && $param['default'] == true) 
                            $displayColumns[$colName] = $param;
                    }
                    
                    if(count($displayColumns) > 0) $lv->displayColumns = $displayColumns; 
                    else $lv->displayColumns = $listViewDefs[$seed->module_dir];
                    
    				$lv->export = false;
                    $lv->mergeduplicates = false;
    				$lv->multiSelect = false;
    				if($overlib) {
    					$lv->overlib = true;
    					$overlib = false;
    				}
    				else $lv->overlib = false;
    			
    				$lv->setup($seed, 'include/ListView/ListViewGeneric.tpl', $where, 0, 10);
    				
    				$module_results[$name] = '<br /><br />' . get_form_header($GLOBALS['app_list_strings']['moduleList'][$seed->module_dir] . ' (' . $lv->data['pageData']['offsets']['total'] . ')', '', false);
    				$module_counts[$name] = $lv->data['pageData']['offsets']['total'];
    				 
    				if($lv->data['pageData']['offsets']['total'] == 0) {
    					$module_results[$name] .= '<h2>' . $home_mod_strings['LBL_NO_RESULTS_IN_MODULE'] . '</h2>';
    				}
    				else { 
    					$has_results = true;
    					$module_results[$name] .= $lv->display(false, false);
    				}
    			}
    		}
        }
		
		if($has_results) {
			arsort($module_counts);
			foreach($module_counts as $name=>$value) {
				echo $module_results[$name];
			}
		}
		else {
			echo '<br>';
            echo $home_mod_strings['LBL_NO_RESULTS'];
            echo $home_mod_strings['LBL_NO_RESULTS_TIPS'];
		}
		
	}
	
	function buildCache() {
//		echo 'build cache';
		require_once('include/utils.php');
		require_once('include/database/DBHelper.php');
		
		$dbh = new DBHelper();
		global $beanList, $dictionary;
		
		$supported_modules = array();
		$supported_types = array('varchar', 'char', 'int'); // support data types 
		
		foreach($beanList as $module_name=>$bean_name) {
			if($bean_name == 'aCase') $bean_name = 'Case';
			if(file_exists("modules/$module_name/vardefs.php")) {
				require_once("modules/$module_name/vardefs.php");
				if(isset($dictionary[$bean_name]['unified_search']) && $dictionary[$bean_name]['unified_search']) { // if bean participates in uf
					$fields = array();
					foreach($dictionary[$bean_name]['fields'] as $field=>$def) {
						if(isset($def['unified_search']) && $def['unified_search'] &&
							in_array($dbh->getFieldType($def), $supported_types)) { // if field participates
								$fields[$def['name']] = array('vname' => $def['vname'], 
															  'type' => $def['type']);
								if($def['type'] == 'relate') {
									$fields[$def['name']]['table'] = $def['table'];
									$fields[$def['name']]['rname'] = $def['rname'];
								} 
						} 
					}
					if(count($fields) > 0) {
						$supported_modules[$module_name]['table'] = $dictionary[$bean_name]['table'];
						$supported_modules[$module_name]['fields'] = $fields;
					}
				}
			}	
		}
		asort($supported_modules);
		write_array_to_file('unified_search_modules', $supported_modules, 'cache/modules/unified_search_modules.php');
	}
}

?>
