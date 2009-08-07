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

 // $Id: SelectModuleAction.php,v 1.6 2006/08/26 02:46:03 majed Exp $

class SelectModuleAction extends StudioWizard {
	var $wizard = 'SelectModuleAction';
    function welcome(){
		return $GLOBALS['mod_strings']['LBL_SMA_WELCOME'];
	}
	
	function options(){
		return array('SelectModuleLayout'=>$GLOBALS['mod_strings']['LBL_SMA_EDIT_LAYOUT'], 'EditCustomFields'=>$GLOBALS['mod_strings']['LBL_SMA_EDIT_CUSTOMFIELDS'], 'EditDropDowns'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_DROPDOWNS'] ,);
	}
	function back(){
	    ob_clean();
	    header('LOCATION:index.php?action=wizard&module=Studio&wizard=SelectModuleWizard');
	    sugar_cleanup(true);
	}
	
	function process($option){
		switch($option){
			case 'SelectModuleLayout':
			    header("LOCATION:index.php?module=Studio&action=index&setLayout=true");
			    sugar_cleanup(true);
				break;
			case 'EditCustomFields':
				header('LOCATION:index.php?module=Studio&action=wizard&wizard=EditCustomFieldsWizard');
				sugar_cleanup(true);
			case 'EditDropDowns':
				header('LOCATION:index.php?module=Studio&action=wizard&wizard=EditDropDownWizard&option=EditDropdown&dropdown_module=' . $_SESSION['studio']['module']);
				sugar_cleanup(true);
			case 'ManageBackups':
				require_once('modules/Studio/wizards/'. $option . '.php');
				$newWiz = new $option();
				$newWiz->display();
				break;
			default:
				$this->display();
		}
	}
	
}
?>
