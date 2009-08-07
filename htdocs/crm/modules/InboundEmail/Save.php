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
 * Description:
 * Created On: Oct 17, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/

require_once('modules/InboundEmail/InboundEmail.php');
require_once('include/dir_inc.php');
require_once('include/utils/file_utils.php');
require_once('include/utils.php');

global $current_user;

$focus = new InboundEmail();
$focus->retrieve($_REQUEST['record']);

foreach($focus->column_fields as $field) {
	if(isset($_REQUEST[$field])) {
		$focus->$field = $_REQUEST[$field];
	}
}
foreach($focus->additional_column_fields as $field) {
	if(isset($_REQUEST[$field])) {
		$value = $_REQUEST[$field];
		$focus->$field = $value;
	}
}
foreach($focus->required_fields as $field) {
	if(isset($_REQUEST[$field])) {
		$value = $_REQUEST[$field];
		$focus->$field = $value;
	}
}
$focus->email_password = $_REQUEST['email_password'];
$focus->protocol = $_REQUEST['protocol'];

/////////////////////////////////////////////////////////
////	SERVICE STRING CONCATENATION

$optimum = $focus->findOptimumSettings('', $focus->email_user, $focus->email_password, $focus->server_url, $focus->port, $focus->protocol, $focus->mailbox);
if(is_array($optimum) && (count($optimum) > 0)) {
	$focus->service = $optimum['serial'];
} else {
	// no save
	// allowing bad save to allow Email Campaigns configuration to continue even without IMAP
	$focus->service = "::::::".$focus->protocol."::::"; // save bogus info.
	$error = "&error=true";
} 
////	END SERVICE STRING CONCAT
/////////////////////////////////////////////////////////

if(isset($_REQUEST['mark_read']) && $_REQUEST['mark_read'] == 1) {
	$focus->delete_seen = 0;
} else {
	$focus->delete_seen = 1;
}

// handle stored_options serialization
if(isset($_REQUEST['only_since']) && $_REQUEST['only_since'] == 1) {
	$onlySince = true;
} else {
	$onlySince = false;
}
$stored_options = array();
$stored_options['from_name'] = $_REQUEST['from_name'];
$stored_options['from_addr'] = $_REQUEST['from_addr'];
$stored_options['only_since'] = $onlySince;
$stored_options['filter_domain'] = $_REQUEST['filter_domain'];
$focus->stored_options = base64_encode(serialize($stored_options));

$GLOBALS['log']->info('----->InboundEmail now saving self');

////////////////////////////////////////////////////////////////////////////////
////    CREATE MAILBOX QUEUE
////////////////////////////////////////////////////////////////////////////////
if(isset($_REQUEST['group_id']) && $_REQUEST['group_id'] == 'new') {
	if($uid = $focus->groupUserDupeCheck()) {
		$focus->group_id = $uid;
	} else {
		require_once('modules/Users/User.php');
		$group = new User();
		$group->user_name	= $focus->name;
		$group->last_name	= $focus->name;
		$group->is_group	= 1;
		$group->deleted		= 0;
		$group->status		= 'Active'; // cn: bug 6711
		$timezone = lookupTimezone();
		$group->setPreference('timezone', $timezone);
		$group->save();
		$focus->group_id = $group->id;
	}
} elseif(!empty($_REQUEST['group_id']) && $_REQUEST['group_id'] != 'new') {
	$focus->group_id = $_REQUEST['group_id'];
}







////////////////////////////////////////////////////////////////////////////////
////    SEND US TO SAVE DESTINATION
////////////////////////////////////////////////////////////////////////////////
//_ppd($focus);
$focus->save();

$_REQUEST['return_id'] = $focus->id;


$edit='';
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") {
	$return_module = $_REQUEST['return_module'];
} else {
	$return_module = "InboundEmail";
}
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") {
	$return_action = $_REQUEST['return_action'];
} else {
	$return_action = "DetailView";
}
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") {
	$return_id = $_REQUEST['return_id'];
}
if(!empty($_REQUEST['edit'])) {
	$return_id='';
	$edit='&edit=true';
}

$GLOBALS['log']->debug("Saved record with id of ".$return_id);

/*// cache results
if(!file_exists($focus->InboundEmailCachePath) || !file_exists($focus->InboundEmailCachePath.'/'.$focus->InboundEmailCacheFile)) {
	// create directory if not existent
	mkdir_recursive($focus->InboundEmailCachePath, false);
}
// write cache file
write_array_to_file('InboundEmailCached', $focus->getInboundEmailWithGuids(), $focus->InboundEmailCachePath.'/'.$focus->InboundEmailCacheFile);
*/


header("Location: index.php?module=$return_module&action=$return_action&record=$return_id$edit$error");
?>
