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
 * $Id: SetTimezone.php,v 1.16 2006/07/23 01:15:04 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


require_once('data/Tracker.php');
require_once('include/JSON.php');
require_once('include/timezone/timezones.php');
require_once('modules/Administration/Administration.php');
require_once('modules/Users/Forms.php');
require_once('modules/Users/User.php');
require_once('XTemplate/xtpl.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$admin = new Administration();
$admin->retrieveSettings("notify");

///////////////////////////////////////////////////////////////////////////////
////	HELPER FUNCTIONS
function lookupTimezone_special($userOffset){
	$defaultZones = array('America/New_York'=>1, 'America/Los_Angeles'=>1,'America/Chicago'=>1, 'America/Denver'=>1,'America/Anchorage'=>1, 'America/Phoenix'=>1, 'Europe/Amsterdam'=>1,'Europe/Athens'=>1,'Europe/London'=>1, 'Australia/Sydney'=>1, 'Australia/Perth'=>1);
	global $timezones;

	$gmtOffset = $userOffset;
	$selectedZone = ' ';
	foreach($timezones as $zoneName=>$zone){
		if($zone['gmtOffset'] == $gmtOffset){
			$selectedZone = $zoneName;
		}
		if(!empty($defaultZones[$selectedZone]) ){
			return $selectedZone;
		}
	}
	return $selectedZone;
}
////	END HELPER FUNCTIONS
///////////////////////////////////////////////////////////////////////////////

if(!empty($_REQUEST['userOffset'])) { // ajax call to lookup timezone
    echo 'userTimezone = "' . lookupTimezone_special($_REQUEST['userOffset']) . '";';
    die();
}
$admin = new Administration();
$admin->retrieveSettings();
$xtpl = new XTemplate('modules/Users/SetTimezone.html');
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);


$selectedZone = $current_user->getPreference('timezone');
if(empty($selectedZone) && !empty($_REQUEST['gmto'])) {
	$selectedZone = lookupTimezone_special(-1 * $_REQUEST['gmto']);
}

$timezoneOptions = '';
ksort($timezones);

foreach($timezones as $key => $value) {
	if( $selectedZone== $key) {
		$selected = " SELECTED";
	} else { 
		$selected = "";
	}
	if(!empty($value['dstOffset'])) {
		$dst = " (+DST)";
	} else {
		$dst = "";
	}
	$gmtOffset = ($value['gmtOffset'] / 60);
	if(!strstr($gmtOffset,'-')) {
		$gmtOffset = "+".$gmtOffset;
	}
	$timezoneOptions .= "<option value='$key'".$selected.">".str_replace(array('_','North'), array(' ', 'N.'),$key). " (GMT".$gmtOffset.") ".$dst."</option>";
}
$xtpl->assign('TIMEZONEOPTIONS', $timezoneOptions);
$xtpl->parse('main');
$xtpl->out('main');
?>
