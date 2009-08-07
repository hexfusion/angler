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

///////////////////////////////////////////////////////////////////////////////
////	HELPER FUNCTIONS
function json_retrieve() {
	global $beanFiles,$beanList;
	//header('Content-type: text/xml');
	require_once($beanFiles[$beanList[$_REQUEST['module']]]);
	$focus = new $beanList[$_REQUEST['module']];

	$focus->retrieve($_REQUEST['record']);

	$all_fields = array_merge($focus->column_fields,$focus->additional_column_fields);

	$js_fields_arr = array();
	print "{fields:{";

	foreach($all_fields as $field) {
		if(isset($focus->$field)) {
			$focus->$field =  from_html($focus->$field);
			$focus->$field =  preg_replace("/\r\n/","<BR>",$focus->$field);
			$focus->$field =  preg_replace("/\n/","<BR>",$focus->$field);
			array_push( $js_fields_arr , "\"$field\":\"".addslashes($focus->$field)."\"");
		}
	}
	print implode(",",$js_fields_arr);
	print "}";
	print "}";
}

function json_get_full_list() {
	global $beanFiles;
	global $beanList;

	require_once('include/utils.php');
	require_once($beanFiles[$beanList[$_REQUEST['module']]]);

	$json = getJSONobj();

	$where = str_replace('\\','', rawurldecode($_REQUEST['where']));
	$order = str_replace('\\','', rawurldecode($_REQUEST['order']));
	$focus = new $beanList[$_REQUEST['module']];
	$fullList = $focus->get_full_list($order, $where, '');
	$all_fields = array_merge($focus->column_fields,$focus->additional_column_fields);

	$js_fields_arr = array();
	
	$i=1; // js doesn't like 0 index?
	foreach($fullList as $note) {
		$js_fields_arr[$i] = array();
		
		foreach($all_fields as $field) {
			if(isset($note->$field)) {
				$note->$field = from_html($note->$field);
				$note->$field = preg_replace('/\r\n/','<BR>',$note->$field);
				$note->$field = preg_replace('/\n/','<BR>',$note->$field);
				$js_fields_arr[$i][$field] = addslashes($note->$field);
			}
		}
		$i++;
	}
	
	$out = $json->encode($js_fields_arr);
	print($out);
}
////	END HELPER FUNCTIONS
///////////////////////////////////////////////////////////////////////////////


clean_special_arguments();

// called from another file
$GLOBALS['log'] = LoggerManager::getLogger('json.php');

// check for old config format.
if(empty($sugar_config) && isset($dbconfig['db_host_name'])) {
   make_sugar_config($sugar_config);
}

insert_charset_header();

if(!empty($sugar_config['session_dir'])) {
	session_save_path($sugar_config['session_dir']);
}

session_start();

$user_unique_key = (isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : "";
$server_unique_key = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : "";

if($user_unique_key != $server_unique_key) {
	session_destroy();
	header('Location: index.php?action=Login&module=Users');
	exit();
}

if(!isset($_SESSION['authenticated_user_id'])) {
	// TODO change this to a translated string.
	session_destroy();
	die('An active session is required to export content');
}

$current_user = new User();

$result = $current_user->retrieve($_SESSION['authenticated_user_id']);
if($result == null) {
	session_destroy();
	die('An active session is required to export content');
}

$supported_functions = array('retrieve','get_full_list');
if(in_array($_REQUEST['action'],$supported_functions)) {
	call_user_func('json_'.$_REQUEST['action']);
}

sugar_cleanup();
?>
