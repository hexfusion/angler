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
* $Id: updater_utils.php,v 1.53 2006/09/05 22:02:03 majed Exp $
********************************************************************************/
require_once('include/utils/encryption_utils.php');

function getSystemInfo($send_usage_info=true){
	global $sugar_config;
	global $db, $authLevel;
	$info=array();

	include('sugar_version.php');
	$info['sugar_version']=$sugar_version;

	$info['sugar_flavor']=$sugar_flavor;

	if($send_usage_info){
		$info['sugar_db_version']=$sugar_db_version;
		if($authLevel > 0){
			if(isset($_SERVER['SERVER_ADDR']))
				$info['ip_address'] = $_SERVER['SERVER_ADDR'];
			else
				$info['ip_address'] = '127.0.0.1';
		}
		$info['application_key']=$sugar_config['unique_key'];
		$info['php_version']=phpversion();
		$info['server_software'] = $_SERVER['SERVER_SOFTWARE'];

		//get user count.

		$user_list = get_user_array(false);
		$info['users']=count($user_list);



		$query="select count(*) count from users where status='Active' and deleted=0 and is_admin='1'";
		$result=$db->query($query, 'fetching admin count', false);
		$row = $db->fetchByAssoc($result);
		if(!empty($row)) {
			$info['admin_users'] = $row['count'];
		}
		if(empty($authLevel)){
			$authLevel = 0;
		}
		$query="select count(*) count from users";
		$result=$db->query($query, 'fetching all users count', false);
		$row = $db->fetchByAssoc($result);

		if(!empty($row)) {
			$info['registered_users'] = $row['count'];
		}
		$lastMonth = db_convert("'".date('Y-m-d H:i:s' , strtotime('-1 month')) . "'", 'datetime');
		if( !$send_usage_info){
			$info['users_active_30_days'] = -1;
		}
		else{
			$query = "SELECT count( DISTINCT users.id ) user_count FROM tracker, users WHERE users.id = tracker.user_id AND  tracker.date_modified >= $lastMonth";
			$result=$db->query($query, 'fetching last 30 users count', false);
			$row = $db->fetchByAssoc($result);
			$info['users_active_30_days'] = $row['user_count'];
			
		}
		
        












		if(!$send_usage_info){
			$info['latest_tracker_id'] = -1;
		}else{
			$query="select id from tracker order by date_modified desc";
			$result=$db->query($query,'fetching most recent tracker entry',false);
			$row=$db->fetchByAssoc($result);
			if (!empty($row)) {
				$info['latest_tracker_id']=$row['id'];
			}
		}

		$dbManager = & DBManager::getInstance();
		$info['db_type']=$sugar_config['dbconfig']['db_type'];
		$info['db_version']=$dbManager->version();
	}
	$info['auth_level'] = $authLevel;






















	return $info;

}


function check_now($send_usage_info=true, $get_request_data=false, $response_data = false ) {
	global $sugar_config, $timedate;
	global $db, $license;










	$return_array=array();
	if(empty($license))loadLicense();
	if(!$response_data){
		$info = getSystemInfo($send_usage_info);

		require_once('include/nusoap/nusoap.php');

		$GLOBALS['log']->debug('USING HTTPS TO CONNECT TO HEARTBEAT');
		$sclient = new nusoapclient('https://updates.sugarcrm.com/heartbeat/soap.php', false, false, false, false, false, 15, 15);
		$ping = $sclient->call('sugarPing', array());
		if(empty($ping) || $sclient->getError()){
			$sclient = '';
		}

		if(empty($sclient)){
			$GLOBALS['log']->debug('USING HTTP TO CONNECT TO HEARTBEAT');
			$sclient = new nusoapclient('http://updates.sugarcrm.com/heartbeat/soap.php', false, false, false, false, false, 15, 15);
		}














			$key = '4829482749329';




		$encoded = sugarEncode($key, serialize($info));

		if($get_request_data){
			$request_data = array('key'=>$key, 'data'=>$encoded);
			return serialize($request_data);
		}
		$encodedResult = $sclient->call('sugarHome', array('key'=>$key, 'data'=>$encoded));
	}else{
		$encodedResult = 	$response_data['data'];
		$key = $response_data['key'];

	}

	if($response_data || !$sclient->getError()){
		$serializedResultData = sugarDecode($key,$encodedResult);
		$resultData = unserialize($serializedResultData);
		if($response_data && empty($resultData))$resultData['validation'] = 'invalid';
	}else
	{
		$resultData = array();
		$resultData['versions'] = array();

	}

	if($response_data || !$sclient->getError() )
	{








		if(!empty($resultData['msg'])){
			if(!empty($resultData['msg']['admin'])){
				$license->saveSetting('license', 'msg_admin', $resultData['msg']['admin']);
			}else{
				$license->saveSetting('license', 'msg_admin','');
			}
			if(!empty($resultData['msg']['all'])){
				$license->saveSetting('license', 'msg_all', $resultData['msg']['all']);
			}else{
				$license->saveSetting('license', 'msg_all','');
			}
		}else{
			$license->saveSetting('license', 'msg_admin','');
			$license->saveSetting('license', 'msg_all','');
		}
		$license->saveSetting('license', 'last_validation', 'success');
		unset($_SESSION['COULD_NOT_CONNECT']);
	}
	else
	{
		$resultData = array();
		$resultData['versions'] = array();

		$license->saveSetting('license', 'last_validation_fail', gmdate('Y-m-d H:i:s'));
		$license->saveSetting('license', 'last_validation', 'failed');
		
		if( empty($license->settings['last_validation_success']) && empty($license->settings['last_validation_failed']) && empty($license->settings['license_vk_end_date'])){
			$license->saveSetting('license', 'vk_end_date', gmdate('Y-m-d H:i:s'));
			
			$license->saveSetting('license', 'validation_key', base64_encode(serialize(array('verified'=>false))));
		}
		$_SESSION['COULD_NOT_CONNECT'] =gmdate('Y-m-d H:i:s');

	}
	if(!empty($resultData['versions'])){

		$license->saveSetting('license', 'latest_versions',base64_encode(serialize($resultData['versions'])));
	}else{
		$resultData['versions'] = array();
		$license->saveSetting('license', 'latest_versions','')	;
	}




	include('sugar_version.php');

	if(sizeof($resultData) == 1 && !empty($resultData['versions'][0]['version']) &&  $resultData['versions'][0]['version'] < $sugar_version)
	{
		$resultData['versions'][0]['version'] = $sugar_version;
		$resultData['versions'][0]['description'] = "You have the latest version.";
	}


	return $resultData['versions'];
}
function set_CheckUpdates_config_setting($value) {
	include_once('modules/Administration/Administration.php');

	$admin=new Administration();
	$admin->saveSetting('Update','CheckUpdates',$value);
}
/* return's value for the 'CheckUpdates' config setting
* if the setting does not exist one gets created with a default value of automatic.
*/
function get_CheckUpdates_config_setting() {

	$checkupdates='automatic';
	require_once('modules/Administration/Administration.php');

	$admin=new Administration();
	$admin=$admin->retrieveSettings('Update',true);
	if (empty($admin->settings) or empty($admin->settings['Update_CheckUpdates'])) {
		$admin->saveSetting('Update','CheckUpdates','automatic');
	} else {
		$checkupdates=$admin->settings['Update_CheckUpdates'];
	}
	return $checkupdates;
}

function set_last_check_version_config_setting($value) {
	include_once('modules/Administration/Administration.php');

	$admin=new Administration();
	$admin->saveSetting('Update','last_check_version',$value);
}
function get_last_check_version_config_setting() {

	include_once('modules/Administration/Administration.php');

	$admin=new Administration();
	$admin=$admin->retrieveSettings('Update');
	if (empty($admin->settings) or empty($admin->settings['Update_last_check_version'])) {
		return null;
	} else {
		return $admin->settings['Update_last_check_version'];
	}
}


function set_last_check_date_config_setting($value) {
	include_once('modules/Administration/Administration.php');

	$admin=new Administration();
	$admin->saveSetting('Update','last_check_date',$value);
}
function get_last_check_date_config_setting() {

	require_once('modules/Administration/Administration.php');

	$admin=new Administration();
	$admin=$admin->retrieveSettings('Update');
	if (empty($admin->settings) or empty($admin->settings['Update_last_check_date'])) {
		return 0;
	} else {
		return $admin->settings['Update_last_check_date'];
	}
}

function set_sugarbeat($value) {
	global $sugar_config;
	$_SUGARBEAT="sugarbeet";
	$sugar_config[$_SUGARBEAT] = $value;
	write_array_to_file( "sugar_config", $sugar_config, "config.php" );
}
function get_sugarbeat() {




	global $sugar_config;
	$_SUGARBEAT="sugarbeet";

	if (isset($sugar_config[$_SUGARBEAT]) && $sugar_config[$_SUGARBEAT] == false) {
	return false;
	}



	return true;

}



function shouldCheckSugar(){
	global $license;
	if(



	get_CheckUpdates_config_setting() == 'automatic' ){
		return true;
	}

	return false;
}

































































































































































































































function loadLicense(){
	
	$GLOBALS['license']=new Administration();
	$GLOBALS['license']=$GLOBALS['license']->retrieveSettings('license', true);

}

function loginLicense(){
	global $current_user, $license, $authLevel;
	if(empty($license))loadLicense();
      








	$authLevel = 0;
	
	if (shouldCheckSugar()) {
	   

		$last_check_date=get_last_check_date_config_setting();
		$current_date_time=time();
		$time_period=3*23*3600 ;
		if (($current_date_time - $last_check_date) > $time_period



		) {
			$version = check_now(get_sugarbeat());

			unset($_SESSION['license_seats_needed']);






			loadLicense();
			set_last_check_date_config_setting("$current_date_time");
			include('sugar_version.php');

			if(!empty($version)&& count($version) == 1 && $version[0]['version'] > $sugar_version  && is_admin($current_user))
			{
				//set session variables.
				$_SESSION['available_version']=$version[0]['version'];
				$_SESSION['available_version_description']=$version[0]['description'];
				set_last_check_version_config_setting($version[0]['version']);
			}
		}
	}



















}








?>
