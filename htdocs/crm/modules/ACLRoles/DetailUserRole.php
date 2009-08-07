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
require_once('modules/ACL/ACLController.php');
global $app_list_strings;

$focus = new User();
$focus->retrieve($_REQUEST['record']);


$sugar_smarty = new Sugar_Smarty();
$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);
$sugar_smarty->assign('APP_LIST', $app_list_strings);

$categories = ACLAction::getUserActions($_REQUEST['record'],true);

//clear out any removed tabs from user display
if(!is_admin($current_user)){
	$tabs = $focus->getPreference('display_tabs');
	global $modInvisList, $modInvisListActivities;
	if(!empty($tabs)){
		foreach($categories as $key=>$value){
			if(!in_array($key, $tabs) &&  !in_array($key, $modInvisList) && !in_array($key, $modInvisListActivities) ){
				unset($categories[$key]);
				
			}
		}
		
	}
}

$names = array();
$tdwidth = 10;
$names = ACLAction::setupCategoriesMatrix($categories);

$sugar_smarty->assign('APP', $app_list_strings);
$sugar_smarty->assign('CATEGORIES', $categories);
$sugar_smarty->assign('TDWIDTH', $tdwidth);
$sugar_smarty->assign('ACTION_NAMES', $names);

$title = get_module_title( '',$mod_strings['LBL_ROLES_SUBPANEL_TITLE'], '');

$sugar_smarty->assign('TITLE', $title);
$sugar_smarty->assign('USER_ID', $focus->id);
$sugar_smarty->assign('LAYOUT_DEF_KEY', 'UserRoles');
echo $sugar_smarty->fetch('modules/ACLRoles/DetailViewUser.tpl');


//this gets its layout_defs.php file from the user not from ACLRoles so look in modules/Users for the layout defs
require_once('include/SubPanel/SubPanelTiles.php');
$modules_exempt_from_availability_check=array('Users'=>'Users','ACLRoles'=>'ACLRoles',);
$subpanel = new SubPanelTiles($focus, 'UserRoles');

echo $subpanel->display(true,true);



?>
