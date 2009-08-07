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

 // $Id: EditCustomFieldsWizard.php,v 1.6 2006/08/29 05:22:00 majed Exp $

require_once('modules/Studio/DropDowns/DropDownHelper.php');
class EditCustomFieldsWizard extends StudioWizard {
	var $wizard = 'EditCustomFieldsWizard';
    function welcome(){
		return $GLOBALS['mod_strings']['LBL_EC_WELCOME'];
	}
	function back(){
	    ob_clean();
	   header('LOCATION:index.php?action=wizard&module=Studio&wizard=SelectModuleAction');
	    sugar_cleanup(true);
	}
	function options(){
		return array('ViewCustomFields'=>$GLOBALS['mod_strings']['LBL_EC_VIEW_CUSTOMFIELDS'],
		 'CreateCustomFields'=>$GLOBALS['mod_strings']['LBL_EC_CREATE_CUSTOMFIELD'],
		  'ClearCache'=>$GLOBALS['mod_strings']['LBL_EC_CLEAR_CACHE'],
		   'RepairCustomFields'=>$GLOBALS['mod_strings']['LBL_SW_REPAIR_CUSTOMFIELDS'],  );
	}
	
	function process($option){

		switch($option){
		    case 'ViewCustomFields':
		        parent::process($option);
		        require_once('modules/Studio/EditCustomFields/ListView.php');
		        break;
		    case 'CreateCustomFields':
		        if(empty($_REQUEST['to_pdf'])){
		          parent::process($option);
		        }
		        require_once('modules/Studio/EditCustomFields/EditView.php');
		        break;
		    case 'SaveCustomField':
		        require_once('modules/Studio/EditCustomFields/Save.php');
		        break;
		    case 'DeleteCustomField':
		        require_once('modules/Studio/EditCustomFields/Delete.php');
		        break;
		    case 'EditCustomField':
		        parent::process($option);
		        require_once('modules/Studio/EditCustomFields/EditView.php');
		        break;
		    case 'ClearCache':
		        require_once('modules/DynamicFields/DynamicField.php');
		        DynamicField::deleteCache();
		        echo '<script>YAHOO.util.Event.addListener(window, "load", function(){ajaxStatus.showStatus("cache cleared");window.setTimeout(\'ajaxStatus.hideStatus();\', 2000);});</script>';
		         parent::process($option);
		        
		        break;
		    case 'RepairCustomFields':
            	header('LOCATION:index.php?module=Administration&action=UpgradeFields');
            	sugar_cleanup(true);
		    default:
		         parent::process($option);
		}
	}
	
}

?>
