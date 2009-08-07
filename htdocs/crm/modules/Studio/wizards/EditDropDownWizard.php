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

 // $Id: EditDropDownWizard.php,v 1.3 2006/08/22 20:48:13 awu Exp $

require_once('modules/Studio/DropDowns/DropDownHelper.php');
class EditDropDownWizard extends StudioWizard {
	var $wizard = 'EditDropDownWizard';
    function welcome(){
		return $GLOBALS['mod_strings']['LBL_ED_WELCOME'];
	}
	function back(){
	    
	    ob_clean();
	     if(!empty($_SESSION['studio']['module'])){
	        header('LOCATION:index.php?action=wizard&module=Studio&wizard=SelectModuleAction');
	        sugar_cleanup(true);
	     }
	     header('LOCATION:index.php?action=wizard&module=Studio&wizard=StudioWizard');
	    sugar_cleanup(true);
	     
	    
	   
	}
	function options(){
		return array('EditDropdown'=>$GLOBALS['mod_strings']['LBL_SW_EDIT_DROPDOWNS'], 'CreateDropdown'=>$GLOBALS['mod_strings']['LBL_ED_CREATE_DROPDOWN'] );
	}
	
	function process($option){
		switch($option){
		    case 'EditDropdown':
		        parent::process($option);
		        require_once('modules/Studio/DropDowns/EditView.php');
		        break;
		    case 'SaveDropDown':
		        DropDownHelper::saveDropDown($_REQUEST);
		        require_once('modules/Studio/DropDowns/EditView.php');
		        break;
		    case 'CreateDropdown':
		          parent::process($option);
		        $_REQUEST['newDropdown'] = true;
		       require_once('modules/Studio/DropDowns/EditView.php');
		        break;
		    default:
		         parent::process($option);
		}
	}
	
}

?>
