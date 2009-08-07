<?php
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

 // $Id$

global $sugar_version;
if(substr($sugar_version,0,5) == "4.0.1")
{
    if(empty($GLOBALS['sugarEntry']))
        $GLOBALS['sugarEntry'] = true;
}
else if(!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$uw_files = array(
	'modules/UpgradeWizard/cancel.php',
    'modules/UpgradeWizard/commit.php',
    'modules/UpgradeWizard/end.php',
    'modules/UpgradeWizard/Forms.php',
    'modules/UpgradeWizard/index.php',
    'modules/UpgradeWizard/Menu.php',
    'modules/UpgradeWizard/preflight.php',
    'modules/UpgradeWizard/start.php',
    'modules/UpgradeWizard/systemCheck.php',
    'modules/UpgradeWizard/systemCheckJson.php',
    'modules/UpgradeWizard/upload.php',
    'modules/UpgradeWizard/uw_main.tpl',
    'modules/UpgradeWizard/uw_utils.php',
    'modules/UpgradeWizard/upgradeWizard.js',
    'modules/UpgradeWizard/language/en_us.lang.php',
    'include/utils/encryption_utils.php',
    'include/Pear/Crypt_Blowfish/Blowfish.php',
    'include/Pear/Crypt_Blowfish/Blowfish/DefaultKey.php',
    'include/utils.php',
    'include/utils/external_cache.php',
    'include/language/en_us.lang.php',
    'include/modules.php',
    'include/Localization/Localization.php',
    'install/language/en_us.lang.php',
    'XTemplate/xtpl.php',
    'include/database/DBHelper.php',
    'include/database/DBManager.php',
    'include/database/DBManagerFactory.php',
    'include/database/MssqlHelper.php',
    'include/database/MssqlManager.php',
    'include/database/MysqlHelper.php',
    'include/database/MysqlManager.php',
    'include/database/PearDatabase.php',




);

?>
