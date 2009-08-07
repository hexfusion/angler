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
$sugar_smarty = new Sugar_Smarty();
$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);
$sugar_smarty->assign('APP_LIST', $app_list_strings);
$role = new ACLRole();
$role->retrieve($_REQUEST['record']);
$categories = ACLRole::getRoleActions($_REQUEST['record']);
$tdwidth = 10;
$names = ACLAction::setupCategoriesMatrix($categories);

$sugar_smarty->assign('ROLE', $role->toArray());
$sugar_smarty->assign('CATEGORIES', $categories);
$sugar_smarty->assign('TDWIDTH', $tdwidth);
$sugar_smarty->assign('ACTION_NAMES', $names);

$return= array('module'=>'ACLRoles', 'action'=>'index', 'record'=>'');
if(isset($_REQUEST['return_module'])){
	$return['module']=$_REQUEST['return_module'];
	if(isset($_REQUEST['return_action']))$return['action']=$_REQUEST['return_action'];
	if(isset($_REQUEST['return_record']))$return['record']=$_REQUEST['return_recrod'];
}
$sugar_smarty->assign('RETURN', $return);
echo "\n</p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_MODULE_NAME'].':'. $role->name, true);
echo "\n</p>\n";
//$sugar_smarty->assign('TITLE', $title);
$hide_hide_supanels = true;
echo $sugar_smarty->fetch('modules/ACLRoles/DetailView.tpl');
//for subpanels the variable must be named focus;
$focus =& $role;
$_REQUEST['module'] = 'ACLRoles';
require_once('include/SubPanel/SubPanelTiles.php');

$subpanel = new SubPanelTiles($role, 'ACLRoles');

echo $subpanel->display();
?>
