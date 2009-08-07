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
/*********************************************************************************
 * $Id: ConfigureTabs.php,v 1.10 2006/07/30 03:16:09 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/




require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Administration/Administration.php');
require_once('modules/Administration/Forms.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");


$title = get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_CONFIGURE_TABS'], true);

global $theme;
global $currentModule;
$theme_path = "themes/".$theme."/";
$image_path = $theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Administration ConfigureTabs view");
require_once("modules/MySettings/TabController.php");
$controller = new TabController();
$tabs = $controller->get_tabs_system();
$groups = array();
$groups[$mod_strings['LBL_DISPLAY_TABS']] = array();
foreach ($tabs[0] as $key=>$value)
{
	
$groups[$mod_strings['LBL_DISPLAY_TABS']][$key] = array('label'=>'<span style="font-size:90%">'.$app_list_strings['moduleList'][$key] . '</span>');
}
$groups[ $mod_strings['LBL_HIDE_TABS']]= array();
foreach ($tabs[1] as $key=>$value)
{
$groups[ $mod_strings['LBL_HIDE_TABS']][$key]  = array('label'=>$app_list_strings['moduleList'][$key]);
}


global $app_list_strings, $app_strings;
require_once('include/Sugar_Smarty.php');
$smarty = new Sugar_Smarty();
$user_can_edit = $controller->get_users_can_edit();
$smarty->assign('APP', $GLOBALS['app_strings']);
$smarty->assign('MOD', $GLOBALS['mod_strings']);
$smarty->assign('title',  $title);
$smarty->assign('user_can_edit',  $user_can_edit);
$smarty->assign('hideKeys', true);
$smarty->assign('groups',$groups);
$smarty->assign('description',  $mod_strings['LBL_CONFIG_TABS']);
$buttons = $smarty->fetch("modules/Administration/ConfigureTabForm.tpl");
$smarty->assign('buttons', $buttons);
$smarty->display("modules/Studio/ListViewEditor/EditView.tpl");




?>
