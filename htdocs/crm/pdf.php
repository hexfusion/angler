<?php
 if(!defined('sugarEntry'))define('sugarEntry', true);
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
require_once('include/entryPoint.php');
global $beanList, $beanFiles;

session_start();

///////////////////////////////////////////////////////////////////////////////
////    HANDLE SESSION SECURITY
$user_unique_key = (isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : '';
$server_unique_key = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : '';

if($user_unique_key != $server_unique_key) {
	session_destroy();
	header("Location: index.php?action=Login&module=Users");
	exit();
} elseif(!isset($_SESSION['authenticated_user_id'])) {
	// TODO change this to a translated string.
	session_destroy();
	die("An active session is required to export content");
}

$current_user = new User();
$result = $current_user->retrieve($_SESSION['authenticated_user_id']);
if($result == null) {
	session_destroy();
	die("An active session is required");
}

if(isset($_REQUEST['module']) && isset($_REQUEST['action']) && isset($_REQUEST['record'])) {
	$currentModule = clean_string($_REQUEST['module']);
	$action = clean_string($_REQUEST['action']);
	$record = clean_string($_REQUEST['record']);
} else {
	die ("module, action, and record id all are required");
}
////    END SECURITY HANDLING
///////////////////////////////////////////////////////////////////////////////

// if the language is not set yet, then set it to the default language.
if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '') {
	$current_language = $_SESSION['authenticated_user_language'];
} else {
	$current_language = $sugar_config['default_language'];
}
$GLOBALS['log']->debug('current_language is: '.$current_language);

//set module and application string arrays based upon selected language
$app_strings = return_application_language($current_language);
$app_list_strings = return_app_list_strings_language($current_language);
$mod_strings = return_module_language($current_language, $currentModule);

$entity = $beanList[$currentModule];
require_once($beanFiles[$entity]);
$focus = new $entity();
$focus->retrieve(clean_string($_REQUEST['record']));

include("modules/$currentModule/$action.php");
sugar_cleanup();
exit;
?>
