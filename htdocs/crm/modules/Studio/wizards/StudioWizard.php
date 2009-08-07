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

 // $Id: StudioWizard.php,v 1.9 2006/08/22 20:48:13 awu Exp $


class StudioWizard{
    var $tplfile = 'modules/Studio/wizards/tpls/wizard.tpl';
    var $wizard = 'StudioWizard';
    var $status = '';
    var $assign = array();
    
    function welcome(){
        return $GLOBALS['mod_strings']['LBL_SW_WELCOME'];
    }

    function options(){
        return array('SelectModuleWizard'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_MODULE'], 'EditDropDownWizard'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_DROPDOWNS'], 'ConfigureTabs'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_TABS'],'RenameTabs'=>$GLOBALS['mod_strings']['LBL_SW_RENAME_TABS'],'ConfigureGroupTabs'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_GROUPTABS'],'Portal'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_PORTAL'],



        'RepairCustomFields'=>$GLOBALS['mod_strings']['LBL_SW_REPAIR_CUSTOMFIELDS'],
        );
    }
    function back(){}
    function process($option){
        switch($option)
        {
            case 'SelectModuleWizard':
                require_once('modules/Studio/wizards/'. $option . '.php');
                $newWiz = new $option();
                $newWiz->display();
                break;
            case 'EditDropDownWizard':
                require_once('modules/Studio/wizards/'. $option . '.php');
                $newWiz = new $option();
                $newWiz->display();
                break;
            case 'ConfigureTabs':
                header('LOCATION:index.php?module=Administration&action=ConfigureTabs');
                sugar_cleanup(true);
            case 'RenameTabs':
                $_REQUEST['dropdown_name'] = 'moduleList';
                require_once('modules/Studio/wizards/EditDropDownWizard.php');
                $newWiz = new EditDropDownWizard();
                $newWiz->process('EditDropdown');
                break;
             case 'ConfigureGroupTabs':
                require_once('modules/Studio/TabGroups.php');
                break;
            case 'Workflow':
                header('LOCATION:index.php?module=WorkFlow&action=ListView');
                sugar_cleanup(true);
            case 'Portal':
                header('LOCATION:index.php?module=iFrames&action=index');
                sugar_cleanup(true);
            case 'RepairCustomFields':
            	header('LOCATION:index.php?module=Administration&action=UpgradeFields');
            	sugar_cleanup(true);
            case 'Classic':
                header('LOCATION:index.php?module=DynamicLayout&action=index');
                sugar_cleanup(true);
            default:
                $this->display();
        }
    }
    function display(){
        global $mod_strings;
        echo "\n<p>\n";
        echo get_module_title($mod_strings['LBL_MODULE_TITLE'], $mod_strings['LBL_MODULE_TITLE'], true); 
        echo "\n</p>\n";
        $sugar_smarty = new Sugar_Smarty();
        $sugar_smarty->assign('welcome', $this->welcome());
        $sugar_smarty->assign('options', $this->options());
        $sugar_smarty->assign('MOD', $GLOBALS['mod_strings']);
        $sugar_smarty->assign('option', (!empty($_REQUEST['option'])?$_REQUEST['option']:''));
        $sugar_smarty->assign('wizard',$this->wizard);
        $sugar_smarty->assign('status', $this->status);
        foreach($this->assign as $name=>$value){
            $sugar_smarty->assign($name, $value);
        }
        $sugar_smarty->display($this->tplfile);
    }

}
?>
