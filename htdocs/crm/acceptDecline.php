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
require_once('modules/Contacts/Contact.php');
global $sugar_config, $dbconfig, $beanList, $beanFiles;

// check for old config format.
if(empty($sugar_config) && isset($dbconfig['db_host_name']))
{
   make_sugar_config($sugar_config);
}

if (!empty($sugar_config['session_dir'])) {
	session_save_path($sugar_config['session_dir']);
}

session_start();

if ( ! empty($_REQUEST['user_id']))
{
 $result = $current_user->retrieve($_REQUEST['user_id']);
 if($result == null)
 {
 	session_destroy();
 	sugar_cleanup();
 	die("The user id doesn't exist");
 }
 $current_entity = $current_user;
}
else if ( ! empty($_REQUEST['contact_id']))
{
 $current_entity = new Contact();
	$current_entity->disable_row_level_security = true;
 $result = $current_entity->retrieve($_REQUEST['contact_id']);
 if($result == null)
 {
 	session_destroy();
 	sugar_cleanup();
 	die("The contact id doesn't exist");
 }
}

	$bean = $beanList[clean_string($_REQUEST['module'])];
	require_once($beanFiles[$bean]);
	$focus = new $bean;
	$focus->disable_row_level_security = true;
  $result = $focus->retrieve($_REQUEST['record']);

if($result == null)
{
	session_destroy();
	sugar_cleanup();
	die("The focus id doesn't exist");
}

// if the language is not set yet, then set it to the default language.
/*
if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '')
{
  $current_language = $_SESSION['authenticated_user_language'];
}
else
{
  $current_language = $sugar_config['default_language'];
}
*/
$current_language = $sugar_config['default_language'];
$GLOBALS['log']->debug('current_language is: '.$current_language);
$app_strings = return_application_language($current_language);
$app_list_strings = return_app_list_strings_language($current_language);

$focus->set_accept_status($current_entity,$_REQUEST['accept_status']);

print $app_strings['LBL_STATUS_UPDATED']."<BR><BR>";
print $app_strings['LBL_STATUS']. " ". $app_list_strings['dom_meeting_accept_status'][$_REQUEST['accept_status']];
print "<BR><BR>";
print "<a href='#' onclick='window.close(); return false;'>".$app_strings['LBL_CLOSE_WINDOW']."</a><br>";
sugar_cleanup();
exit;
?>
