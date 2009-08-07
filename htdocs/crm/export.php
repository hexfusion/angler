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
//fixes IE bug where the file name can't be overwritten in the header on IE
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
    session_cache_limiter("public");
}

ob_start();
require_once('include/export_utils.php');

//set module and application string arrays based upon selected language
$app_strings = return_application_language($sugar_config['default_language']);
session_start();

$user_unique_key = (isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : "";
$server_unique_key = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : "";

if($user_unique_key != $server_unique_key) {
	session_destroy();
	exit();
}

if(!isset($_SESSION['authenticated_user_id'])) {
	session_destroy();
	die($app_strings['ERR_NEED_ACTIVE_SESSION']);
}

// get the current User
$current_user = new User();
$result = $current_user->retrieve($_SESSION['authenticated_user_id']);
if($result == null) {
	session_destroy();
	die($app_strings['ERR_NEED_ACTIVE_SESSION']);
}

$the_module = clean_string($_REQUEST['module']);

if($sugar_config['disable_export'] || (!empty($sugar_config['admin_export_only']) && !(is_admin($current_user) || (ACLController::moduleSupportsACL($the_module)  && ACLAction::getUserAccessLevel($current_user->id,$the_module, 'access') == ACL_ALLOW_ENABLED && ACLAction::getUserAccessLevel($current_user->id, $the_module, 'admin') == ACL_ALLOW_ADMIN)))){
	die($app_strings['ERR_EXPORT_DISABLED']);
}

if(!empty($_REQUEST['uid'])) 
	$content = export(clean_string($_REQUEST['module']), $_REQUEST['uid']);
else 
	$content = export(clean_string($_REQUEST['module']));

///////////////////////////////////////////////////////////////////////////////
////	BUILD THE EXPORT FILE
ob_clean();
header("Pragma: cache");
header("Content-type: application/octet-stream; charset=".$locale->getExportCharset());
header("Content-Disposition: attachment; filename={$_REQUEST['module']}.csv");
header("Content-transfer-encoding: binary");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header("Cache-Control: post-check=0, pre-check=0", false );
header("Content-Length: ".strlen($content));

print $locale->translateCharset($content, 'UTF-8', $locale->getExportCharset());

sugar_cleanup(true);
?>
