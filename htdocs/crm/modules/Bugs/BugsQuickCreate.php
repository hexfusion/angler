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
 
require_once('include/EditView/QuickCreate.php');
require_once('modules/Bugs/Bug.php');
require_once('include/javascript/javascript.php');

class BugsQuickCreate extends QuickCreate {
    
    var $javascript;
    
    function process() {
        global $current_user, $timedate, $app_list_strings, $current_language, $mod_strings;
        $mod_strings = return_module_language($current_language, 'Bugs');
        
        parent::process();
  
        $this->ss->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['bug_priority_dom'], $app_list_strings['bug_priority_default_key']));
        $this->ss->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['bug_status_dom'], $app_list_strings['bug_status_default_key']));
        $this->ss->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['bug_type_dom'],$app_list_strings['bug_type_default_key']));

        if($this->viaAJAX) { // override for ajax call
            $this->ss->assign('saveOnclick', "onclick='if(check_form(\"bugsQuickCreate\")) return SUGAR.subpanelUtils.inlineSave(this.form.id, \"bugs\"); else return false;'");
            $this->ss->assign('cancelOnclick', "onclick='return SUGAR.subpanelUtils.cancelCreate(\"subpanel_bugs\")';");
        }
        
        $this->ss->assign('viaAJAX', $this->viaAJAX);

        $this->javascript = new javascript();
        $this->javascript->setFormName('bugsQuickCreate');
        
        $focus = new Bug();
        $this->javascript->setSugarBean($focus);
        $this->javascript->addAllFields('');

        $this->ss->assign('additionalScripts', $this->javascript->getScript(false));
    }   
}
?>
