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
* $Id: Updater.php,v 1.10 2006/07/18 05:10:33 majed Exp $
********************************************************************************/

include_once('XTemplate/xtpl.php');
include_once('modules/Administration/updater_utils.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $sugar_config;

$xtpl=new XTemplate ('modules/Administration/Updater.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['useraction']) && ($_REQUEST['useraction']=='Save' || $_REQUEST['useraction']=='CheckNow')) {
	if(!empty($_REQUEST['type']) && $_REQUEST['type'] == 'automatic') {
		set_CheckUpdates_config_setting('automatic');
	}else{
		set_CheckUpdates_config_setting('manual');
	}

	$beat=false;
	if(!empty($_REQUEST['beat'])) {
		$beat=true;
	}
	if ($beat != get_sugarbeat()) {
		set_sugarbeat($beat);
	}
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_CONFIGURE_UPDATER'], true);
echo "\n</p>\n";

if (get_sugarbeat()) $xtpl->assign("SEND_STAT_CHECKED", "checked");

if (get_CheckUpdates_config_setting()=='automatic') {
	$xtpl->assign("AUTOMATIC_CHECKED", "checked");
}


if (isset($_REQUEST['useraction']) && $_REQUEST['useraction']=='CheckNow') {
	check_now(get_sugarbeat());
	loadLicense();

}



$xtpl->parse('main.stats');





$has_updates= false;
if(!empty($license->settings['license_latest_versions'])){

	$encodedVersions = $license->settings['license_latest_versions'];

	$versions = unserialize(base64_decode( $encodedVersions));
	include('sugar_version.php');
	if(!empty($versions)){
		foreach($versions as $version){
			if($version['version'] > $sugar_version )
			{
				$has_updates = true;
				$xtpl->assign("VERSION", $version);
				$xtpl->parse('main.updates.version');
			}
		}
	}
	if(!$has_updates){
		$xtpl->parse('main.noupdates');
	}else{
		$xtpl->parse('main.updates');
	}
}

//return module and index.
$xtpl->assign("RETURN_MODULE", "Administration");
$xtpl->assign("RETURN_ACTION", "index");

$xtpl->parse("main");
$xtpl->out("main");
?>
