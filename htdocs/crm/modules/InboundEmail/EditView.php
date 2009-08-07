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
$_REQUEST['edit']='true';
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

require_once('modules/InboundEmail/InboundEmail.php');
require_once('modules/Emails/Email.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once($theme_path.'layout_utils.php');
require_once('include/templates/TemplateGroupChooser.php');
require_once('modules/InboundEmail/Forms.php');
require_once('include/javascript/javascript.php');

// GLOBALS
global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus = new InboundEmail();
$focus->checkImap();
$javascript = new Javascript();
$email = new Email();
/* Start standard EditView setup logic */

if(isset($_REQUEST['record'])) {
	$GLOBALS['log']->debug("In InboundEmail edit view, about to retrieve record: ".$_REQUEST['record']);
	$result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die($app_strings['ERROR_NO_RECORD']);
    }
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$GLOBALS['log']->debug("isDuplicate found - duplicating record of id: ".$focus->id);
	$focus->id = "";
}

$GLOBALS['log']->info("InboundEmail Edit View");
/* End standard EditView setup logic */

/* Start custom setup logic */
// status drop down
$status = get_select_options_with_id_separate_key($app_list_strings['user_status_dom'],$app_list_strings['user_status_dom'], $focus->status);
// Groups
$selectGroups = '<option value="new">'.$mod_strings['LBL_CREATE_NEW_GROUP'].'</option>';

// handle if this I-E is personal or group
$isPersonal = false;
if(!empty($focus->id)) {
	$isPersonal = $focus->handleIsPersonal();
}
if($selects = $focus->getGroupsWithSelectOptions()) {
	$selectGroups .= $selects;
}
if($isPersonal) {
	// stomp out standard
	$selectGroups = '<option value="'.$focus->group_id.'">'.$focus->getUserNameFromGroupId().'</option>';
}
// default MAILBOX value
if(empty($focus->mailbox)) {
	$mailbox = 'INBOX';
} else {
	$mailbox = $focus->mailbox;
}

// service options breakdown
$tls = '';
$notls = '';
$cert = '';
$novalidate_cert = '';
$ssl = '';
if(!empty($focus->service)) {
	// will always have 2 values: /tls || /notls and /validate-cert || /novalidate-cert
	$exServ = explode('::', $focus->service);
	if($exServ[0] == 'tls') {
		$tls = "CHECKED";
	} elseif($exServ[5] == 'notls') {
		$notls = "CHECKED";
	}
	if($exServ[1] == 'validate-cert') {
		$cert = "CHECKED";
	} elseif($exServ[4] == 'novalidate-cert') {
		$novalidate_cert = 'CHECKED';
	}
	if(isset($exServ[2]) && !empty($exServ[2]) && $exServ[2] == 'ssl') {
		$ssl = "CHECKED";
	}
}
$mark_read = '';
if($focus->delete_seen == 0 || empty($focus->delete_seen)) {
	$mark_read = 'CHECKED';
}

// mailbox type
$mailbox_type = get_select_options_with_id($app_list_strings['dom_mailbox_type'], $focus->mailbox_type);

// auto-reply email template
$email_templates_arr = get_bean_select_array(true, 'EmailTemplate','name', '','name',true);

if(!empty($focus->stored_options)) {
	$storedOptions = unserialize(base64_decode($focus->stored_options));
	$from_name = $storedOptions['from_name'];
	$from_addr = $storedOptions['from_addr'];
	if($storedOptions['only_since']) {
		$only_since = 'CHECKED';
	} else {
		$only_since = '';
	}
	if(isset($storedOptions['filter_domain']) && !empty($storedOptions['filter_domain'])) {
		$filterDomain = $storedOptions['filter_domain']; 
	} else {
		$filterDomain = '';
	}
} else { // initialize empty vars for template
	$from_name = '';
	$from_addr = '';
	$only_since = '';
	$filterDomain = '';
}

// return action
if(isset($focus->id)) {
	$return_action = 'DetailView';
} else {
	$return_action = 'ListView';
}

// javascript
$javascript->setSugarBean($focus);
$javascript->setFormName('EditView');
$javascript->addRequiredFields();
$javascript->addFieldGeneric('email_user', 'alpha', $mod_strings['LBL_LOGIN'], true);
$javascript->addFieldGeneric('email_password', 'alpha', $mod_strings['LBL_PASSWORD'], true);

$r = $focus->db->query('SELECT value FROM config WHERE name = \'fromname\'');
$a = $focus->db->fetchByAssoc($r);
$default_from_name = $a['value'];
$r = $focus->db->query('SELECT value FROM config WHERE name = \'fromaddress\'');
$a = $focus->db->fetchByAssoc($r);
$default_from_addr = $a['value'];

/* End custom setup logic */


// TEMPLATE ASSIGNMENTS
$xtpl = new XTemplate('modules/InboundEmail/EditView.html');
// if no IMAP libraries available, disable Save/Test Settings
if(!function_exists('imap_open')) {
	$xtpl->assign('IE_DISABLED', 'DISABLED');	
}
// standard assigns
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);
$xtpl->assign('THEME', $theme);
$xtpl->assign('GRIDLINE', $gridline);
$xtpl->assign('MODULE', 'InboundEmail');
$xtpl->assign('RETURN_MODULE', 'InboundEmail');
$xtpl->assign('RETURN_ID', $focus->id);
$xtpl->assign('RETURN_ACTION', $return_action);
$xtpl->assign('JAVASCRIPT', get_set_focus_js().$javascript->getScript());
// module specific
$xtpl->assign('ROLLOVER', $email->rolloverStyle);
$xtpl->assign('MODULE_TITLE', get_module_title($mod_strings['LBL_MODULE_TITLE'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true));
$xtpl->assign('ID', $focus->id);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign('STATUS', $status);
$xtpl->assign('SERVER_URL', $focus->server_url);
$xtpl->assign('USER', $focus->email_user);
$xtpl->assign('PASSWORD', $focus->email_password);
$xtpl->assign('MAILBOX', $mailbox);
$xtpl->assign('TLS', $tls);
$xtpl->assign('NOTLS', $notls);
$xtpl->assign('CERT', $cert);
$xtpl->assign('NOVALIDATE_CERT', $novalidate_cert);
$xtpl->assign('SSL', $ssl);
$xtpl->assign('PROTOCOL', get_select_options_with_id($app_list_strings['dom_email_server_type'], $focus->protocol));
$xtpl->assign('MARK_READ', $mark_read);
$xtpl->assign('MAILBOX_TYPE', $mailbox_type);
$xtpl->assign('TEMPLATE_ID', $focus->template_id);
$xtpl->assign('EMAIL_TEMPLATE_OPTIONS', get_select_options_with_id($email_templates_arr, $focus->template_id));
$xtpl->assign('ONLY_SINCE', $only_since);
$xtpl->assign('FILTER_DOMAIN', $filterDomain);
// groups
$xtpl->assign('GROUP_ID', $selectGroups);
// auto-reply stuff
$xtpl->assign('FROM_NAME', $from_name);
$xtpl->assign('FROM_ADDR', $from_addr);
$xtpl->assign('DEFAULT_FROM_NAME', $default_from_name);
$xtpl->assign('DEFAULT_FROM_ADDR', $default_from_addr);
if($focus->template_id) {
	$xtpl->assign("EDIT_TEMPLATE","visibility:inline");
}
else {
	$xtpl->assign("EDIT_TEMPLATE","visibility:hidden");
}
if($focus->port == 110 || $focus->port == 995) {
	$xtpl->assign('DISPLAY', "display:''");
} else {
	$xtpl->assign('DISPLAY', "display:none");
}
if($isPersonal) {
	$xtpl->assign('DISABLE_GROUP', 'DISABLED');
}


















// WINDOWS work arounds
//if(is_windows()) {
//	$xtpl->assign('MAYBE', '<style> div.maybe { display:none; }</style>');
//}
// PARSE AND PRINT
$xtpl->parse("main");
$xtpl->out("main");
?>
