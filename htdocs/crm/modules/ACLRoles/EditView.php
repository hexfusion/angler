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

$sugar_smarty = new Sugar_Smarty();

$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);
$sugar_smarty->assign('APP_LIST', $app_list_strings);
$role = new ACLRole();
$role_name = '';
$return= array('module'=>'ACLRoles', 'action'=>'index', 'record'=>'');
if(!empty($_REQUEST['record'])){
	$role->retrieve($_REQUEST['record']);
	$categories = ACLRole::getRoleActions($_REQUEST['record']);
	
	$role_name =  $role->name;
	if(!empty($_REQUEST['isDuplicate'])){
		//role id is stripped here in duplicate so anything using role id after this will not have it
		$role->id = '';
	}else{
		$return['record']= $role->id;
		$return['action']='DetailView';
	}
	
}else{
	$categories = ACLRole::getRoleActions('');
}
$sugar_smarty->assign('ROLE', $role->toArray());
$tdwidth = 10;

if(isset($_REQUEST['return_module'])){
	$return['module']=$_REQUEST['return_module'];
	if(isset($_REQUEST['return_action']))$return['action']=$_REQUEST['return_action'];
	if(isset($_REQUEST['return_record']))$return['record']=$_REQUEST['return_record'];
}

$sugar_smarty->assign('RETURN', $return);
$names = ACLAction::setupCategoriesMatrix($categories);

$sugar_smarty->assign('CATEGORIES', $categories);
$sugar_smarty->assign('TDWIDTH', $tdwidth);
$sugar_smarty->assign('ACTION_NAMES', $names);

echo "\n</p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_MODULE_NAME'].':'. $role_name, true);
echo "\n</p>\n";
echo $sugar_smarty->fetch('modules/ACLRoles/EditView.tpl');

?>
