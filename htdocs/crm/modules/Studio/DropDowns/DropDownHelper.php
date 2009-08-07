<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
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
 */

 // $Id: DropDownHelper.php,v 1.5 2006/08/22 19:53:00 awu Exp $

class DropDownHelper{
    var $modules = array();
    function getDropDownModules(){
        $dir = dir('modules');
        while($entry = $dir->read()){
            if(file_exists('modules/'. $entry . '/EditView.php')){
                $this->scanForDropDowns('modules/'. $entry . '/EditView.php', $entry);
            }
        }
        
    }
    
    function scanForDropDowns($filepath, $module){
        $contents = file_get_contents($filepath);
        $matches = array();
        preg_match_all('/app_list_strings\s*\[\s*[\'\"]([^\]]*)[\'\"]\s*]/', $contents, $matches);
        if(!empty($matches[1])){

            foreach($matches[1] as $match){
                $this->modules[$module][$match] = $match;
            }
   
        }       
        
    }
    
    /**
     * Takes in the request params from a save request and processes 
     * them for the save.
     *
     * @param REQUEST params  $params
     */
    function saveDropDown($params){
       $count = 0; 
       $dropdown = array();
       $dropdown_name = $params['dropdown_name'];
       $selected_lang = (!empty($params['dropdown_lang'])?$params['dropdown_lang']:$_SESSION['authenticated_user_language']);
       $my_list_strings = return_app_list_strings_language($selected_lang);
       while(isset($params['slot_' . $count])){
           
           $index = $params['slot_' . $count];
           $key = (isset($params['key_' . $index]))?$params['key_' . $index]: 'BLANK';
           $value = (isset($params['value_' . $index]))?$params['value_' . $index]: '';
           if($key == 'BLANK'){
               $key = '';
               
           }
         
           if(empty($params['delete_' . $index])){
            $dropdown[$key] = $value;
           }
           $count++;
       }
      
       if($selected_lang == $GLOBALS['current_language']){
       
           $GLOBALS['app_list_strings'][$dropdown_name] = $dropdown;
       }
        $contents = return_custom_app_list_strings_file_contents($selected_lang);
        $new_contents = replace_or_add_dropdown_type($dropdown_name,$dropdown, $contents);
       
        save_custom_app_list_strings_contents($new_contents, $selected_lang);
    	sugar_cache_put('app_list_strings', $GLOBALS['app_list_strings']);
    }
    
}


?>
