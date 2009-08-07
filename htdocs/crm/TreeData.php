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
 //Request object must have these property values:
 //		Module: module name, this module should have a file called TreeData.php
 //		Function: name of the function to be called in TreeData.php, the function will be called statically.
 //		PARAM prefixed properties: array of these property/values will be passed to the function as parameter.
require_once('include/utils/file_utils.php');
require_once('data/SugarBean.php');
require_once('include/JSON.php');
require_once('include/entryPoint.php');

session_start();
$ret=array();
$params1=array();
$nodes=array();

$GLOBALS['log']->debug("TreeData:session started");

function authenticate()
{
	global $sugar_config;
 	$user_unique_key = (isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : "";
 	$server_unique_key = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : "";

 	if ($user_unique_key != $server_unique_key) {
		$GLOBALS['log']->debug("JSON_SERVER: user_unique_key:".$user_unique_key."!=".$server_unique_key);
        session_destroy();
        return null;
 	}

 	if(!isset($_SESSION['authenticated_user_id']))
 	{
 		$GLOBALS['log']->debug("JSON_SERVER: authenticated_user_id NOT SET. DESTROY");
        session_destroy();
        return null;
 	}

 	$current_user = new User();

 	$result = $current_user->retrieve($_SESSION['authenticated_user_id']);
 	$GLOBALS['log']->debug("JSON_SERVER: retrieved user from SESSION");

 	if($result == null)
 	{
		$GLOBALS['log']->debug("JSON_SERVER: could get a user from SESSION. DESTROY");
   		session_destroy();
   		return null;
 	}
	return $result;
}

if(!empty($sugar_config['session_dir'])) {
	session_save_path($sugar_config['session_dir']);
	$GLOBALS['log']->debug("JSON_SERVER:session_save_path:".$sugar_config['session_dir']);
}

//get language
$current_language = $sugar_config['default_language'];
// if the language is not set yet, then set it to the default language.
if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '') {
	$current_language = $_SESSION['authenticated_user_language'];
} 

//validate user.
$current_user = authenticate();

global $app_strings;
if (empty($app_strings)) {
    //set module and application string arrays based upon selected language
    $app_strings = return_application_language($current_language);
}

//get theme
$theme = $sugar_config['default_theme'];
if(isset($_SESSION['authenticated_user_theme']) && $_SESSION['authenticated_user_theme'] != '') {
	$theme = $_SESSION['authenticated_user_theme'];
}
//set image path
$image_path = 'themes/'.$theme.'/images/';

//process request parameters. consider following parameters.
//function, and all parameters prefixed with PARAM.
//PARAMT_ are tree level parameters.
//PARAMN_ are node level parameters.
//module  name and function name parameters are the only ones consumed
//by this file..
foreach ($_REQUEST as $key=>$value) {

	switch ($key) {
	
		case "function":
		case "call_back_function":
			$func_name=$value;
			$params1['TREE']['function']=$value;
			break;
			
		default:
			$pssplit=explode('_',$key);
			if ($pssplit[0] =='PARAMT') {
				unset($pssplit[0]);
				$params1['TREE'][implode('_',$pssplit)]=$value;				
			} else {
				if ($pssplit[0] =='PARAMN') {
					$depth=$pssplit[count($pssplit)-1];
					//parmeter is surrounded  by PARAMN_ and depth info.
					unset($pssplit[count($pssplit)-1]);unset($pssplit[0]);	
					$params1['NODES'][$depth][implode('_',$pssplit)]=$value;
				} else {
					if ($key=='module') {
						if (!isset($params1['TREE']['module'])) {
							$params1['TREE'][$key]=$value;	
						}
					} else { 	
						$params1['REQUEST'][$key]=$value;
					}					
				}
			}
	}	
}	
$modulename=$params1['TREE']['module']; ///module is a required parameter for the tree.
if (!empty($modulename) && !empty($func_name)) {
	//todo add file validation...
	//if (file_exists('modules/'.$modulename.'/TreeData.php')) {
		require_once('modules/'.$modulename.'/TreeData.php');
		if (function_exists($func_name)) {
			$ret=call_user_func($func_name,$params1);
		}
	//}
}

if (!empty($ret)) {
	echo $ret;
}
sugar_cleanup();
exit();
?>
