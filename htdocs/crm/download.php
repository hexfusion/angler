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
global $sugar_config;

session_start();

if(empty($_REQUEST['id']) || empty($_REQUEST['type']) || !isset($_SESSION['authenticated_user_id'])) {
	die("Not a Valid Entry Point");
} else {
	// cn: bug 8753: current_user's preferred export charset not being honored
	$current_user->retrieve($_SESSION['authenticated_user_id']);
	$current_language = $_SESSION['authenticated_user_language'];
	$app_strings = return_application_language($current_language);
	$local_location = $sugar_config['upload_dir']."/".$_REQUEST['id'];
	
	if (!file_exists( $local_location ))
	{
		die($app_strings['ERR_INVALID_FILE_REFERENCE']);
	}
	else if (strpos($local_location, "../") || strpos($local_location, "..\\") ) {
		die($app_strings['ERR_INVALID_FILE_REFERENCE']);
	}
	else
	{
		global $locale;
		
		if (strtolower($_REQUEST['type']) == 'documents'){
			$query = "SELECT filename FROM document_revisions WHERE id = '" . $_REQUEST['id'] ."'";
		}
		else if (strtolower($_REQUEST['type']) == 'notes'){
			$query = "SELECT filename FROM notes WHERE id = '" . $_REQUEST['id'] ."'";
		}
		
		$rs = $db->query($query);
		$row = $db->fetchByAssoc($rs);
		
		if (empty($row)){
			die($app_strings['ERR_INVALID_FILE_REFERENCE']);
		}
		
		$name = $locale->translateCharset($row['filename'], 'UTF-8', $locale->getExportCharset());
		$download_location=$sugar_config['upload_dir']."/".$_REQUEST['id'];
		
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: application/force-download");
		header("Content-Length: " . filesize($local_location));
		header("Content-disposition: attachment; filename=\"".$name."\";");
		header("Pragma: no-cache");
		header("Expires: 0");
		set_time_limit(0);
		
		ob_start();
		if (filesize($local_location) < 2097152) {
			readfile($download_location);
		}
		else {
			readfile_chunked($download_location);
		}
		@ob_flush();
	}
}
?>
