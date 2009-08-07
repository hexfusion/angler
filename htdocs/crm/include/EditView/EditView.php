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
 
require_once('include/Sugar_Smarty.php');

class EditView {
    /**
     * smarty object
     * @var object
     */
    var $ss;
    /**
     * location of template to use
     * @var string 
     */
    var $template;
    /**
     * Module to use
     * @var string 
     */
    var $module;
    
    /**
     * 
     * @param string $module module to use
     * @param string $template template of the form to retreive
     */
    function EditView($module, $template) {
        $this->module = $module;
        $this->template = $template;
        $this->ss = new Sugar_Smarty();
    }

    /**
     * Processes / setups the template
     * assigns all things to the template like mod_srings and app_strings
     * 
     */
    function process() {
        global $current_language, $app_strings, $sugar_version, $sugar_config, $timedate, $theme;;
        $module_strings = return_module_language($current_language, $this->module);
       
        $this->ss->assign('SUGAR_VERSION', $sugar_version);
        $this->ss->assign('JS_CUSTOM_VERSION', $sugar_config['js_custom_version']);
        $this->ss->assign('THEME', $theme);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('MOD', $module_strings);
    }

   
    /**
     * Displays the template
     * 
     * @return string HTML of parsed template
     */
    function display() {
        return $this->ss->fetch($this->template);
    }

}
?>
