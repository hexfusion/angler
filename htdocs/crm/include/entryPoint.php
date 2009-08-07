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
 * $Id: entryPoint.php,v 1.4 2006/09/01 19:37:35 chris Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

/**
 * Known Entry Points as of 4.5
 * acceptDecline.php
 * campaign_tracker.php
 * campaign_trackerv2.php
 * cron.php
 * dictionary.php
 * download.php
 * emailmandelivery.php
 * export_dataset.php
 * export.php
 * image.php
 * index.php
 * install.php 
 * json.php
 * json_server.php
 * leadCapture.php
 * maintenance.php
 * metagen.php
 * oc_convert.php
 * pdf.php
 * phprint.php
 * process_queue.php
 * process_workflow.php
 * removeme.php
 * schedulers.php
 * soap.php
 * sugar_version.php
 * TreeData.php
 * tree_level.php
 * tree.php
 * vcal_server.php
 * vCard.php
 * zipatcher.php */

if(empty($startTime))
{
    $startTime = microtime();
}


// config|_override.php
require_once ('config.php'); // provides $sugar_config
// load up the config_override.php file.  This is used to provide default user settings
if(is_file('config_override.php')) {
	require_once ('config_override.php');
}
///////////////////////////////////////////////////////////////////////////////
////	DATA SECURITY MEASURES
require_once ('include/utils.php');
clean_special_arguments();
clean_incoming_data();
////	END DATA SECURITY MEASURES
///////////////////////////////////////////////////////////////////////////////

// cn: set php.ini settings at entry points
setPhpIniSettings();

require_once ('sugar_version.php'); // provides $sugar_version, $sugar_db_version, $sugar_flavor
require_once ('include/database/PearDatabase.php');
require_once ('include/database/DBManager.php');
require_once ('include/database/DBManagerFactory.php');
require_once ('include/dir_inc.php');
require_once ('include/Localization/Localization.php');
require_once ('include/javascript/jsAlerts.php');
require_once ('include/TimeDate.php');
require_once ('include/modules.php'); // provides $moduleList, $beanList, $beanFiles, $modInvisList, $adminOnlyList, $modInvisListActivities
require_once ('include/utils/file_utils.php');
require_once ('log4php/LoggerManager.php');
require_once ('modules/ACL/ACLController.php');
require_once ('modules/Administration/Administration.php');
require_once ('modules/Administration/updater_utils.php');
require_once ('modules/Users/User.php');
require_once ('modules/Users/authentication/AuthenticationController.php');



///////////////////////////////////////////////////////////////////////////////
////	SETTING DEFAULT VAR VALUES
// Track the number of SQL queiries
$sql_queries = 0;
$GLOBALS['log'] = LoggerManager::getLogger('SugarCRM');
$error_notice = '';
$use_current_user_login = false;

// Allow for the session information to be passed via the URL for printing.
if(isset($_GET['PHPSESSID'])){
    if(!empty($_COOKIE['PHPSESSID']) && strcmp($_GET['PHPSESSID'],$_COOKIE['PHPSESSID']) == 0) {
        session_id($_REQUEST['PHPSESSID']);
    }else{
        unset($_GET['PHPSESSID']);
    }
}
if(!empty($sugar_config['session_dir'])) {
	session_save_path($sugar_config['session_dir']);
}

$db = & DBManager :: getInstance();
$dbmann = DBManager :: getInstance();
$timedate = new TimeDate();
$locale = new Localization();

// Emails uses the REQUEST_URI later to construct dynamic URLs.
// IIS does not pass this field to prevent an error, if it is not set, we will assign it to ''.
if (!isset ($_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = '';
}

$current_user = new User();
$current_entity = null;
////	END SETTING DEFAULT VAR VALUES
///////////////////////////////////////////////////////////////////////////////

?>
