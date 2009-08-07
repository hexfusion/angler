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

 // $Id: EditView.php,v 1.16 2006/08/22 19:28:18 awu Exp $

if(!is_admin($current_user)){
	sugar_die('Admin Only');	
}

require_once('modules/Administration/Forms.php');
echo get_module_title($mod_strings['LBL_MODULE_ID'], $mod_strings['LBL_MODULE_NAME'].": ", true);
require_once('modules/Configurator/Configurator.php');

$configurator = new Configurator();
$focus = new Administration();

if(!empty($_POST['save'])){
	$configurator->saveConfig();	
	$focus->saveConfig();
}

$focus->retrieveSettings();
if(!empty($_POST['restore'])){
	$configurator->restoreConfig();	
}

require_once('include/Sugar_Smarty.php');
$sugar_smarty = new Sugar_Smarty();


$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);
$sugar_smarty->assign('APP_LIST', $app_list_strings);
$sugar_smarty->assign('config', $configurator->config);
$sugar_smarty->assign('error', $configurator->errors);
$sugar_smarty->assign('THEMES', unserialize($_SESSION['avail_themes']));
$sugar_smarty->assign('LANGUAGES', get_languages());
$sugar_smarty->assign("JAVASCRIPT",get_set_focus_js(). get_configsettings_js());

$sugar_smarty->assign("settings", $focus->settings);
$sugar_smarty->assign("mail_sendtype_options", get_select_options_with_id($app_list_strings['notifymail_sendtype'], $focus->settings['mail_sendtype']));
if(!empty($focus->settings['proxy_on'])){
	$sugar_smarty->assign("PROXY_CONFIG_DISPLAY", 'inline');
}else{
	$sugar_smarty->assign("PROXY_CONFIG_DISPLAY", 'none');
}
if(!empty($focus->settings['proxy_auth'])){
	$sugar_smarty->assign("PROXY_AUTH_DISPLAY", 'inline');
}else{
		$sugar_smarty->assign("PROXY_AUTH_DISPLAY", 'none');
}










$sugar_smarty->assign("exportCharsets", get_select_options_with_id($locale->availableCharsets, $sugar_config['default_export_charset']));
$sugar_smarty->display('modules/Configurator/EditView.tpl');

require_once("include/javascript/javascript.php");
$javascript = new javascript();
$javascript->setFormName("ConfigureSettings");
$javascript->addFieldGeneric("notify_fromaddress", "email", $mod_strings['LBL_NOTIFY_FROMADDRESS'], TRUE, "");
$javascript->addFieldGeneric("notify_subject", "varchar", $mod_strings['LBL_NOTIFY_SUBJECT'], TRUE, "");
$javascript->addFieldGeneric("proxy_host", "varchar", $mod_strings['LBL_PROXY_HOST'], TRUE, "");
$javascript->addFieldGeneric("proxy_port", "int", $mod_strings['LBL_PROXY_PORT'], TRUE, "");
$javascript->addFieldGeneric("proxy_password", "varchar", $mod_strings['LBL_PROXY_PASSWORD'], TRUE, "");
$javascript->addFieldGeneric("proxy_username", "varchar", $mod_strings['LBL_PROXY_USERNAME'], TRUE, "");
$javascript->addFieldRange("system_session_timeout", "int", $mod_strings['SESSION_TIMEOUT'], TRUE, "", 0, $ini_session_val);
echo $javascript->getScript();
?>
