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

/*	Default definitions for quicksearches
 * 
 */

class QuickSearchDefaults {	
	function getQSParent() {
		global $app_strings;
		
		$qsParent = array( 
					'method' => 'query',
					'modules' => array('Accounts'), 
					'group' => 'or', 
					'field_list' => array('name', 'id'), 
					'populate_list' => array('parent_name', 'parent_id'), 
					'conditions' => array(array('name'=>'name','op'=>'like_custom','end'=>'%','value'=>'')), 
					'order' => 'name', 
					'limit' => '30',
					'no_match_text' => $app_strings['ERR_SQS_NO_MATCH']
					);
					
		return $qsParent;
	}
	
	function getQSUser() {
		global $app_strings;
		
		$qsUser = array(  'method' => 'get_user_array', // special method  
						'field_list' => array('user_name', 'id'), 
						'populate_list' => array('assigned_user_name', 'assigned_user_id'), 
						'conditions' => array(array('name'=>'user_name','op'=>'like_custom','end'=>'%','value'=>'')),
						'limit' => '30','no_match_text' => $app_strings['ERR_SQS_NO_MATCH']);
		return $qsUser;
	}
	
















	
	function getQSScripts() {
		require_once('include/json_config.php');
		static $json_config = null;
		if(!isset($json_config)) $json_config = new json_config();
		
		global $sugar_version, $sugar_config, $theme;
		$qsScripts = '<script type="text/javascript" src="include/JSON.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script><script type="text/javascript">' . $json_config->get_static_json_server() . '</script>
		<script type="text/javascript" src="include/jsolait/init.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>
		<script type="text/javascript" src="include/jsolait/lib/urllib.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>
		<script type="text/javascript" src="include/javascript/jsclass_base.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>
		<script type="text/javascript" src="include/javascript/jsclass_async.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>
		<script type="text/javascript">sqsWaitGif = "themes/' . $theme . '/images/sqsWait.gif";</script>
		<script type="text/javascript" src="include/javascript/quicksearch.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>
		';
		
		return $qsScripts;
		
	}
	
	function getQSScriptsNoServer() {
		global $sugar_version, $sugar_config, $theme;
		
		$qsScriptsNoServer = '<script type="text/javascript" src="include/JSON.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>
		<script type="text/javascript" src="include/jsolait/init.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>
		<script type="text/javascript" src="include/jsolait/lib/urllib.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>
		<script type="text/javascript" src="include/javascript/jsclass_base.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>
		<script type="text/javascript" src="include/javascript/jsclass_async.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>
		<script type="text/javascript">sqsWaitGif = "themes/' . $theme . '/images/sqsWait.gif";</script>
		<script type="text/javascript" src="include/javascript/quicksearch.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>
		';
		
		return $qsScriptsNoServer;		
	}
	
	function getQSScriptsJSONAlreadyDefined() {
		global $sugar_version, $sugar_config, $theme;
		
		$qsScriptsJSONAlreadyDefined = '<script type="text/javascript">sqsWaitGif = "themes/' . $theme . '/images/sqsWait.gif";</script><script type="text/javascript" src="include/javascript/quicksearch.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>';
		return $qsScriptsJSONAlreadyDefined;
	}
	
	
}

?>
