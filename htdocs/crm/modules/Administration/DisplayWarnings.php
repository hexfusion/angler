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

// $Id: DisplayWarnings.php,v 1.14 2006/07/03 18:16:20 roger Exp $

function displayAdminError($errorString){
	echo '<p class="error">' . $errorString .'</p>';
}

if(isset($_SESSION['rebuild_relationships'])){
	displayAdminError(translate('MSG_REBUILD_RELATIONSHIPS', 'Administration'));
}

if(isset($_SESSION['rebuild_extensions'])){
	displayAdminError(translate('MSG_REBUILD_EXTENSIONS', 'Administration'));
}

if (empty($license)){
	$license=new Administration();
	$license=$license->retrieveSettings('license');
}


















if(!empty($_SESSION['HomeOnly'])){
	displayAdminError(translate('FATAL_LICENSE_ALTERED', 'Administration'));
}

if(isset($license) && !empty($license->settings['license_msg_all'])){
	displayAdminError($license->settings['license_msg_all']);	
}
if(is_admin($current_user)){
if(!empty($_SESSION['COULD_NOT_CONNECT'])){
	displayAdminError(translate('LBL_COULD_NOT_CONNECT', 'Administration') . ' '. $timedate->to_display_date_time($_SESSION['COULD_NOT_CONNECT']));		
}
if(!empty($_SESSION['EXCEEDING_OC_LICENSES']) && $_SESSION['EXCEEDING_OC_LICENSES'] == true){
    displayAdminError(translate('LBL_EXCEEDING_OC_LICENSES', 'Administration'));
}
if(isset($license) && !empty($license->settings['license_msg_admin'])){
	displayAdminError($license->settings['license_msg_admin']);	
}
 if(!empty($dbconfig['db_host_name']) || $sugar_config['sugar_version'] != $sugar_version ){
       		displayAdminError(translate('WARN_REPAIR_CONFIG', 'Administration'));
        }

        if( !isset($sugar_config['installer_locked']) || $sugar_config['installer_locked'] == false ){
        	displayAdminError(translate('WARN_INSTALLER_LOCKED', 'Administration'));
		}




























































		if(isset($_SESSION['invalid_versions'])){
			$invalid_versions = $_SESSION['invalid_versions'];
			foreach($invalid_versions as $invalid){
				displayAdminError(translate('WARN_UPGRADE', 'Administration'). $invalid['name'] .translate('WARN_UPGRADE2', 'Administration'));
			}
		}

	
		
		if (isset($_SESSION['available_version'])){
			if($_SESSION['available_version'] != $sugar_version)
			{
				displayAdminError(translate('WARN_UPGRADE', 'Administration').$_SESSION['available_version']." : ".$_SESSION['available_version_description']);
			}
		}

		if (!isset($_SESSION['dst_fixed']) || $_SESSION['dst_fixed'] != true) {
			$qDst = "SELECT count(*) AS dst FROM versions WHERE name = 'DST Fix'";
			$rDst = $db->query($qDst);
			$rowsDst = $db->fetchByAssoc($rDst);
			if($rowsDst['dst'] > 0) {
				$_SESSION['dst_fixed'] = true;
			} else {
				$_SESSION['dst_fixed'] = false;
				displayAdminError($app_strings['LBL_DST_NEEDS_FIXIN']);
			}

		}

		if(isset($_SESSION['administrator_error']))
		{
			// Only print DB errors once otherwise they will still look broken
			// after they are fixed.
			displayAdminError($_SESSION['administrator_error']);
		}

		unset($_SESSION['administrator_error']);
}

?>
