<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * EditView for Email
 *
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

// $Id: EditView.php,v 1.164 2006/08/18 21:30:16 chris Exp $

$GLOBALS['log']->info("Email edit view");

require_once('modules/Emails/Email.php');
require_once('modules/EmailTemplates/EmailTemplate.php');
require_once('XTemplate/xtpl.php');

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $sugar_version, $sugar_config;
global $timedate;

///////////////////////////////////////////////////////////////////////////////
////	PREPROCESS BEAN DATA FOR DISPLAY
$focus = new Email();
$email_type = 'archived';

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(!empty($_REQUEST['type'])) {
	$email_type = $_REQUEST['type'];
} elseif(!empty($focus->id)) {
	$email_type = $focus->type;
} else {
	$email_type = 'archived';
}

$focus->type = $email_type;

//needed when creating a new email with default values passed in
if(isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}

if(!empty($_REQUEST['load_id']) && !empty($beanList[$_REQUEST['load_module']])) {
	$class_name = $beanList[$_REQUEST['load_module']];
	require_once($beanFiles[$class_name]);
	$contact = new $class_name();
	if($contact->retrieve($_REQUEST['load_id'])) {
    	$link_id = $class_name . '_id';
    	$focus->$link_id = $_REQUEST['load_id'];
    	$focus->contact_name = (!empty($contact->first_name))?$contact->first_name . ' ' . $contact->last_name:$contact->last_name ;
    	$focus->to_addrs_names = $focus->contact_name;
    	$focus->to_addrs_ids = $_REQUEST['load_id'];
    	$focus->to_addrs_emails = $contact->email1;
    	$focus->to_addrs = "$focus->contact_name <$contact->email1>";
    	if(!empty($_REQUEST['parent_type']) && empty($app_list_strings['record_type_display'][$_REQUEST['parent_type']])){
    		if(!empty($app_list_strings['record_type_display'][$_REQUEST['load_module']])){
    			$_REQUEST['parent_type'] = $_REQUEST['load_module'];
    			$_REQUEST['parent_id'] = $focus->contact_id;
    			$_REQUEST['parent_name'] = $focus->to_addrs_names;
    		} else {
    			unset($_REQUEST['parent_type']);
    			unset($_REQUEST['parent_id']);
    			unset($_REQUEST['parent_name']);
    		}
    	}
	}
}
if(isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if(isset($_REQUEST['parent_name'])) {
  $focus->parent_name = $_REQUEST['parent_name'];
}
if(isset($_REQUEST['parent_id'])) {
	$focus->parent_id = $_REQUEST['parent_id'];
}
if(isset($_REQUEST['parent_type'])) {
	$focus->parent_type = $_REQUEST['parent_type'];
}
elseif(is_null($focus->parent_type)) {
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}
if(isset($_REQUEST['to_email_addrs'])) {
	$focus->to_addrs = $_REQUEST['to_email_addrs'];
}
// needed when clicking through a Contacts detail view:
if(isset($_REQUEST['to_addrs_ids'])) {
	$focus->to_addrs_ids = $_REQUEST['to_addrs_ids'];
}
if(isset($_REQUEST['to_addrs_emails'])) {
	$focus->to_addrs_emails = $_REQUEST['to_addrs_emails'];
}
if(isset($_REQUEST['to_addrs_names'])) {
	$focus->to_addrs_names = $_REQUEST['to_addrs_names'];
}
// user's email, go through 3 levels of precedence:
$from = $current_user->getEmailInfo();
////	END PREPROCESSING
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	XTEMPLATE ASSIGNMENT
if($email_type == 'archived') {
	echo get_module_title('Emails', $mod_strings['LBL_ARCHIVED_MODULE_NAME'].":", true);
	$xtpl=new XTemplate('modules/Emails/EditViewArchive.html');
} else {
	echo get_module_title('Emails', $mod_strings['LBL_COMPOSE_MODULE_NAME'].":", true);
	$xtpl=new XTemplate('modules/Emails/EditView.html');
}
echo "\n</p>\n";

// CHECK USER'S EMAIL SETTINGS TO ENABLE/DISABLE 'SEND' BUTTON
if(!$focus->check_email_settings() &&($email_type == 'out' || $email_type == 'draft')) {
	print "<font color='red'>".$mod_strings['WARNING_SETTINGS_NOT_CONF']." <a href='index.php?module=Users&action=EditView&record=".$current_user->id."&return_module=Emails&type=out&return_action=EditView'>".$mod_strings['LBL_EDIT_MY_SETTINGS']."</a></font>";
	$xtpl->assign("DISABLE_SEND", 'DISABLED');
}

// CHECK THAT SERVER HAS A PLACE TO PUT UPLOADED TEMP FILES SO THAT ATTACHMENTS WILL WORK
// cn: Bug 5995
$tmpUploadDir = ini_get('upload_tmp_dir');
if(!empty($tmpUploadDir)) {
	if(!is_writable($tmpUploadDir)) {
		echo "<font color='red'>{$mod_strings['WARNING_UPLOAD_DIR_NOT_WRITABLE']}</font>";
	}
} else {
	//echo "<font color='red'>{$mod_strings['WARNING_NO_UPLOAD_DIR']}</font>";
}


///////////////////////////////////////////////////////////////////////////////
////	INBOUND EMAIL HANDLING
if(isset($_REQUEST['email_name'])) {
	$name = str_replace('_',' ',$_REQUEST['email_name']);	
}
if(isset($_REQUEST['inbound_email_id'])) {
	$quoted = '';
	$quotedHtml = '';
	$ieMail = new Email();
	$ieMail->retrieve($_REQUEST['inbound_email_id']);
	
	$desc						= nl2br(trim($ieMail->description));
	$quotedHtml				= $focus->quoteHtmlEmail($ieMail->description_html);
	
	$exDesc = explode('<br />', $desc);
	foreach($exDesc as $k => $line) {
		$quoted .= '> '.trim($line)."\r";
	}

	// prefill empties with the other's contents
	if(empty($quotedHtml) && !empty($quoted)) {
		$quotedHtml = $focus->quoteHtmlEmail($desc);
	}
	if(empty($quoted) && !empty($quotedHtml)) {
		$quoted = strip_tags(br2nl($quotedHtml));
	}

	if($_REQUEST['type'] != 'forward') {
		$ieMailName = 'RE: '.$ieMail->name;
	} else {
		$ieMailName = $ieMail->name;
	}

	$focus->id					= null; // nulling this to prevent overwriting a replied email(we're basically doing a "Duplicate" function)
	$focus->to_addrs			= $ieMail->from_addr;
	$focus->description 		= $quoted; // don't know what i was thinking: ''; // this will be filled on save/send
	$focus->description_html	= $quotedHtml; // cn: bug 7357 - htmlentities() breaks FCKEditor
	$focus->parent_type			= $ieMail->parent_type;
	$focus->parent_id			= $ieMail->parent_id;
	$focus->parent_name			= $ieMail->parent_name;
	$focus->name				= $ieMailName;
	$xtpl->assign('INBOUND_EMAIL_ID',$_REQUEST['inbound_email_id']);
	// un/READ flags
	if(!empty($ieMail->status)) {
		// "Read" flag for InboundEmail
		if($ieMail->status == 'unread') {
			// creating a new instance here to avoid data corruption below
			$e = new Email();
			$e->retrieve($ieMail->id);
			$e->status = 'read';
			$e->save();
			$email_type = $e->status;
		}
	}
	
	// setup for my/mailbox email switcher
	$mbox = $ieMail->getMailboxDefaultEmail();
	$user = $current_user->getPreferredEmail();
	$useGroup = '&nbsp;<input id="use_mbox" name="use_mbox" type="checkbox" CHECKED onClick="switchEmail()" > 
				<script type="text/javascript">
				function switchEmail() {
					var mboxName = "'.$mbox['name'].'";
					var mboxAddr = "'.$mbox['email'].'";
					var userName = "'.$user['name'].'";
					var userAddr = "'.$user['email'].'";
				
					if(document.getElementById("use_mbox").checked) {
						document.getElementById("from_addr_field").value = mboxName + " <" + mboxAddr + ">";
						document.getElementById("from_addr_name").value = mboxName;
						document.getElementById("from_addr_email").value = mboxAddr;
					} else {
						document.getElementById("from_addr_field").value = userName + " <" + userAddr + ">";
						document.getElementById("from_addr_name").value = userName;
						document.getElementById("from_addr_email").value = userAddr;
					}
				 
				}
				
				</script>';
	$useGroup .= $mod_strings['LBL_USE_MAILBOX_INFO'];
	
	$xtpl->assign('FROM_ADDR_GROUP', $useGroup);
}
////	END INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	SUBJECT FIELD MANIPULATION
$name = '';
if(!empty($_REQUEST['parent_id']) && !empty($_REQUEST['parent_type'])) {
	$focus->parent_id = $_REQUEST['parent_id'];
	$focus->parent_type = $_REQUEST['parent_type'];
}
if(!empty($focus->parent_id) && !empty($focus->parent_type)) {
	if($focus->parent_type == 'Cases') {
		require_once('modules/Cases/Case.php');
		$myCase = new aCase();
		$myCase->retrieve($focus->parent_id);
		$myCaseMacro = $myCase->getEmailSubjectMacro();
		if(isset($ieMail->name) && !empty($ieMail->name)) { // if replying directly to an InboundEmail
			$oldEmailSubj = $ieMail->name;
		} elseif(isset($_REQUEST['parent_name']) && !empty($_REQUEST['parent_name'])) {
			$oldEmailSubj = $_REQUEST['parent_name'];
		} else {
			$oldEmailSubj = $focus->name; // replying to an email using old subject
		}
		
		if(!preg_match('/^re:/i', $oldEmailSubj)) {
			$oldEmailSubj = 'RE: '.$oldEmailSubj;
		}
		$focus->name = $oldEmailSubj;
		
		if(strpos($focus->name, str_replace('%1',$myCase->case_number,$myCaseMacro))) {
			$name = $focus->name;
		} else {
			$name = $focus->name.' '.str_replace('%1',$myCase->case_number,$myCaseMacro);
		}
	} else {
		$name = $focus->name;
	}
} else {
	if(empty($focus->name)) {
		$name = '';
	} else {
		$name = $focus->name;
	}
}
if($email_type == 'forward') {
	$name = 'FW: '.$name;	
}
////	END SUBJECT FIELD MANIPULATION
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	GENERAL TEMPLATE ASSIGNMENTS
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);

if(!isset($focus->id)) $xtpl->assign('USER_ID', $current_user->id);
if(!isset($focus->id) && isset($_REQUEST['contact_id'])) $xtpl->assign('CONTACT_ID', $_REQUEST['contact_id']);

if(isset($_REQUEST['return_module']) && !empty($_REQUEST['return_module'])) {
	$xtpl->assign('RETURN_MODULE', $_REQUEST['return_module']);
} else {
	$xtpl->assign('RETURN_MODULE', 'Emails');
}
if(isset($_REQUEST['return_action']) && !empty($_REQUEST['return_action'])) {
	$xtpl->assign('RETURN_ACTION', $_REQUEST['return_action']);
} else {
	$xtpl->assign('RETURN_ACTION', 'DetailView');
}
if(isset($_REQUEST['return_id']) && !empty($_REQUEST['return_id'])) {
	$xtpl->assign('RETURN_ID', $_REQUEST['return_id']);
}
// handle Create $module then Cancel
if(empty($_REQUEST['return_id']) && !isset($_REQUEST['type'])) {
	$xtpl->assign('RETURN_ACTION', 'index');
}

$xtpl->assign('THEME', $theme);
$xtpl->assign('IMAGE_PATH', $image_path);$xtpl->assign('PRINT_URL', 'index.php?'.$GLOBALS['request_string']);

	///////////////////////////////////////////////////////////////////////////////
	////	QUICKSEARCH CODE
	require_once('include/QuickSearchDefaults.php');
	$qsd = new QuickSearchDefaults();
	$sqs_objects = array('parent_name' => $qsd->getQSParent(), 
						'assigned_user_name' => $qsd->getQSUser(),



						);

	
	$json = getJSONobj();

	$quicksearch_js = $qsd->getQSScripts();
	$sqs_objects_encoded = $json->encode($sqs_objects);
	$quicksearch_js .= <<<EOQ
		<script type="text/javascript" language="javascript">sqs_objects = $sqs_objects_encoded; 
			function changeQS() {
				//new_module = document.getElementById('parent_type').value;
				new_module = document.EditView.parent_type.value;
				if(new_module == 'Contacts' || new_module == 'Leads' || typeof(disabledModules[new_module]) != 'undefined') {
					sqs_objects['parent_name']['disable'] = true;
					document.getElementById('parent_name').readOnly = true;
				}
				else {
					sqs_objects['parent_name']['disable'] = false;
					document.getElementById('parent_name').readOnly = false;
				}
				
				sqs_objects['parent_name']['module'] = new_module;
			}
			changeQS();
		</script>
EOQ;
	$xtpl->assign('JAVASCRIPT', get_set_focus_js().$quicksearch_js);
	////	END QUICKSEARCH CODE
	///////////////////////////////////////////////////////////////////////////////




$xtpl->assign('ID', $focus->id);

if(isset($_REQUEST['parent_type']) && !empty($_REQUEST['parent_type']) && isset($_REQUEST['parent_id']) && !empty($_REQUEST['parent_id'])) {
	$xtpl->assign('OBJECT_ID', $_REQUEST['parent_id']);
	$xtpl->assign('OBJECT_TYPE', $_REQUEST['parent_type']);
}
$xtpl->assign('FROM_ADDR', $focus->from_addr);
//// prevent TO: prefill when type is 'forward'
if($email_type != 'forward') {
	$xtpl->assign('TO_ADDRS', $focus->to_addrs);
	$xtpl->assign('TO_ADDRS_IDS', $focus->to_addrs_ids);
	$xtpl->assign('TO_ADDRS_NAMES', $focus->to_addrs_names);
	$xtpl->assign('TO_ADDRS_EMAILS', $focus->to_addrs_emails);
	$xtpl->assign('CC_ADDRS', $focus->cc_addrs);
	$xtpl->assign('CC_ADDRS_IDS', $focus->cc_addrs_ids);
	$xtpl->assign('CC_ADDRS_NAMES', $focus->cc_addrs_names);
	$xtpl->assign('CC_ADDRS_EMAILS', $focus->cc_addrs_emails);
	$xtpl->assign('BCC_ADDRS', $focus->bcc_addrs);
	$xtpl->assign('BCC_ADDRS_IDS', $focus->bcc_addrs_ids);
	$xtpl->assign('BCC_ADDRS_NAMES', $focus->bcc_addrs_names);
	$xtpl->assign('BCC_ADDRS_EMAILS', $focus->bcc_addrs_emails);
}
$xtpl->assign('FROM_ADDR', $from['name'].' <'.$from['email'].'>');
$xtpl->assign('FROM_ADDR_NAME', $from['name']);
$xtpl->assign('FROM_ADDR_EMAIL', $from['email']);

$xtpl->assign('NAME', $name);
$xtpl->assign('DESCRIPTION_HTML', $focus->description_html);
$xtpl->assign('DESCRIPTION', $focus->description);
$xtpl->assign('TYPE',$email_type);

// Unimplemented until jscalendar language files are fixed
// $xtpl->assign('CALENDAR_LANG',((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign('CALENDAR_LANG', 'en');
$xtpl->assign('CALENDAR_DATEFORMAT', $timedate->get_cal_date_format());
$xtpl->assign('DATE_START', $focus->date_start);
$xtpl->assign('TIME_FORMAT', '('. $timedate->get_user_time_format().')');
$xtpl->assign('TIME_START', substr($focus->time_start,0,5));
$xtpl->assign('TIME_MERIDIEM', $timedate->AMPMMenu('',$focus->time_start));

$parent_types = $app_list_strings['record_type_display'];
$disabled_parent_types = ACLController::disabledModuleList($parent_types,false, 'list');

foreach($disabled_parent_types as $disabled_parent_type){
	if($disabled_parent_type != $focus->parent_type){
		unset($parent_types[$disabled_parent_type]);
	}
}

$xtpl->assign('TYPE_OPTIONS', get_select_options_with_id($parent_types, $focus->parent_type));
$xtpl->assign('USER_DATEFORMAT', '('. $timedate->get_user_date_format().')');
$xtpl->assign('PARENT_NAME', $focus->parent_name);
$xtpl->assign('PARENT_ID', $focus->parent_id);
if(empty($focus->parent_type)) {
	$xtpl->assign('PARENT_RECORD_TYPE', '');
} else {
	$xtpl->assign('PARENT_RECORD_TYPE', $focus->parent_type);
} 

if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign('ADMIN_EDIT',"<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

////	END GENERAL TEMPLATE ASSIGNMENTS
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////
///
/// SETUP PARENT POPUP

$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'parent_id',
		'name' => 'parent_name',
		),
	);

$encoded_popup_request_data = $json->encode($popup_request_data);

/// Users Popup
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'assigned_user_id',
		'user_name' => 'assigned_user_name',
		),
	);
$xtpl->assign('encoded_users_popup_request_data', $json->encode($popup_request_data));













//
///////////////////////////////////////

$change_parent_button = '<input type="button" name="button" tabindex="2" class="button" '
	. 'title="' . $app_strings['LBL_SELECT_BUTTON_TITLE'] . '" '
	. 'accesskey="' . $app_strings['LBL_SELECT_BUTTON_KEY'] . '" '
	. 'value="'	. $app_strings['LBL_SELECT_BUTTON_LABEL'] . '" '
	. "onclick='open_popup(document.EditView.parent_type.value,600,400,\"&tree=ProductsProd\",true,false,$encoded_popup_request_data);' />\n";
$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);

$button_attr = '';
if(!ACLController::checkAccess('Contacts', 'list', true)){
	$button_attr = 'disabled="disabled"';
}

$change_to_addrs_button = '<input type="button" name="to_button" tabindex="3" class="button" '
	. 'title="' . $app_strings['LBL_SELECT_BUTTON_TITLE'] . '" '
	. 'accesskey="' . $app_strings['LBL_SELECT_BUTTON_KEY'] . '" '
	. 'value="'	. $mod_strings['LBL_EMAIL_SELECTOR'] . '" '
	. "onclick='button_change_onclick(this);' $button_attr />\n";
$xtpl->assign("CHANGE_TO_ADDRS_BUTTON", $change_to_addrs_button);

$change_cc_addrs_button = '<input type="button" name="cc_button" tabindex="3" class="button" '
	. 'title="' . $app_strings['LBL_SELECT_BUTTON_TITLE'] . '" '
	. 'accesskey="' . $app_strings['LBL_SELECT_BUTTON_KEY'] . '" '
	. 'value="'	. $mod_strings['LBL_EMAIL_SELECTOR'] . '" '
	. "onclick='button_change_onclick(this);' $button_attr />\n";
$xtpl->assign("CHANGE_CC_ADDRS_BUTTON", $change_cc_addrs_button);

$change_bcc_addrs_button = '<input type="button" name="bcc_button" tabindex="3" class="button" '
	. 'title="' . $app_strings['LBL_SELECT_BUTTON_TITLE'] . '" '
	. 'accesskey="' . $app_strings['LBL_SELECT_BUTTON_KEY'] . '" '
	. 'value="'	. $mod_strings['LBL_EMAIL_SELECTOR'] . '" '
	. "onclick='button_change_onclick(this);' $button_attr />\n";
$xtpl->assign("CHANGE_BCC_ADDRS_BUTTON", $change_bcc_addrs_button);


///////////////////////////////////////
////	USER ASSIGNMENT
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])) {
	$record = '';
	if(!empty($_REQUEST['record'])) {
		$record = $_REQUEST['record'];
	}
	$xtpl->assign('ADMIN_EDIT',"<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

if(empty($focus->assigned_user_id) && empty($focus->id))
	$focus->assigned_user_id = $current_user->id;
if(empty($focus->assigned_name) && empty($focus->id))
	$focus->assigned_user_name = $current_user->user_name;
$xtpl->assign('ASSIGNED_USER_OPTIONS', get_select_options_with_id(get_user_array(TRUE, 'Active', $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign('ASSIGNED_USER_NAME', $focus->assigned_user_name);
$xtpl->assign('ASSIGNED_USER_ID', $focus->assigned_user_id);	
$xtpl->assign('DURATION_HOURS', $focus->duration_hours);
$xtpl->assign('TYPE_OPTIONS', get_select_options_with_id($parent_types, $focus->parent_type));

if(isset($focus->duration_minutes)) {
	$xtpl->assign('DURATION_MINUTES_OPTIONS', get_select_options_with_id($focus->minutes_values,$focus->duration_minutes));
}
////	END USER ASSIGNMENT
///////////////////////////////////////



//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');


///////////////////////////////////////
////	ATTACHMENTS
$attachments = '';
if(!empty($focus->id) || (!empty($_REQUEST['record']) && $_REQUEST['type'] == 'forward')) {
	$focusId = empty($focus->id) ? $_REQUEST['record'] : $focus->id;
	$note = new Note();
	$where = "notes.parent_id='{$focusId}' AND notes.filename IS NOT NULL";
	$notes_list = $note->get_full_list("", $where,true);

	if(!isset($notes_list)) {
		$notes_list = array();
	}
	for($i = 0;$i < count($notes_list);$i++) {
		$the_note = $notes_list[$i];
		if(empty($the_note->filename)) {
			continue;
		}
		$attachments .= '<input type="checkbox" name="remove_attachment[]" value="'.$the_note->id.'"> '.$app_strings['LNK_REMOVE'].'&nbsp;&nbsp;';
		$attachments .= '<a href="'.UploadFile::get_url($the_note->filename,$the_note->id).'" target="_blank">'. $the_note->filename .'</a><br>';
		
	}
}
$attJs  = '<script type="text/javascript">';
$attJs .= 'var file_path = "'.$sugar_config['site_url'].'/'.$sugar_config['upload_dir'].'";';
$attJs .= 'var lnk_remove = "'.$app_strings['LNK_REMOVE'].'";';
$attJs .= '</script>';
$xtpl->assign('ATTACHMENTS', $attachments);
$xtpl->assign('ATTACHMENTS_JAVASCRIPT', $attJs);
////	END ATTACHMENTS
///////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	DOCUMENTS
$popup_request_data = array(
	'call_back_function' => 'document_set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'related_doc_id',
		'document_name' => 'related_document_name',
		),
	);
$json = getJSONobj();
$xtpl->assign('encoded_document_popup_request_data', $json->encode($popup_request_data));
////	END DOCUMENTS
///////////////////////////////////////////////////////////////////////////////

$parse_open = true;
















if($parse_open) {
	$xtpl->parse('main.open_source_1');
}
///////////////////////////////////////////////////////////////////////////////
////	EMAIL TEMPLATES
if(ACLController::checkAccess('EmailTemplates', 'list', true) && ACLController::checkAccess('EmailTemplates', 'view', true)) {
	$et = new EmailTemplate();
	$etResult = $focus->db->query($et->create_list_query('','',''));
	$email_templates_arr[] = '';
	while($etA = $focus->db->fetchByAssoc($etResult)) {
		$email_templates_arr[$etA['id']] = $etA['name'];
	}
} else {
	$email_templates_arr = array('' => $app_strings['LBL_NONE']);
}

$xtpl->assign('EMAIL_TEMPLATE_OPTIONS', get_select_options_with_id($email_templates_arr, ''));
////	END EMAIL TEMPLATES
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////
////	TEXT EDITOR
// cascade from User to Sys Default
$editor = '';
if(!isset($sugar_config['email_default_editor'])) { $current_user->setDefaultsInConfig(); }
$userEditor = $current_user->getPreference('email_editor_option');
$systemEditor = $sugar_config['email_default_editor'];
if($userEditor != '') {
	$editor = $userEditor;	
} else {
	$editor = $systemEditor;
}
if($editor != 'plain') {
	// this box is checked by Javascript on-load.
	$xtpl->assign('EMAIL_EDITOR_OPTION', 'CHECKED');
}
$description_html = $focus->description_html;
$description = $focus->description;

/////////////////////////////////////////////////
// signatures
if($sig = $current_user->getDefaultSignature()) {
	// Bug 7754: add signature if we not detected (coming from a draft or something)
	$htmlSig = (strpos($description_html, $sig['signature_html']) !== false) ? true : false;
	$textSig = (strpos($description, $sig['signature']) !== false) ? true : false;
	
	if($htmlSig == false && $textSig == false) {
		if($current_user->getPreference('signature_prepend')) {
			$description_html = '<br />'.$sig['signature_html'].'<br /><br />'.$description_html;
			$description = "\n".$sig['signature']."\n\n".$description;	
		} else {
			$description_html .= '<br /><br />'.$sig['signature_html'];
			$description = $description."\n\n".$sig['signature'];
		}
	}
}
$xtpl->assign('DESCRIPTION', $description);
// sigs
/////////////////////////////////////////////////

if(file_exists('include/FCKeditor/fckeditor.php')) {
	include('include/FCKeditor_Sugar/FCKeditor_Sugar.php') ;
	ob_start();
		$instancename = 'description_html';
		$oFCKeditor = new FCKeditor_Sugar($instancename) ;
		if(!empty($description_html)) {
			$oFCKeditor->Value = $description_html;
		}
		$oFCKeditor->Create() ;
		$htmlarea_src = ob_get_contents();
		$xtpl->assign('HTML_EDITOR', $htmlarea_src);
		$xtpl->parse('main.htmlarea');
	ob_end_clean();
} else {
  $xtpl->parse('main.textarea');
}
////	END TEXT EDITOR
///////////////////////////////////////
///////////////////////////////////////
////	SPECIAL INBOUND LANDING SCREEN ASSIGNS
if(!empty($_REQUEST['inbound_email_id'])) {
	if(!empty($_REQUEST['start'])) {
		$parts = $focus->getStartPage(base64_decode($_REQUEST['start']));
		$xtpl->assign('RETURN_ACTION', $parts['action']);
		$xtpl->assign('RETURN_MODULE', $parts['module']);
		$xtpl->assign('GROUP', $parts['group']);
	}
		$xtpl->assign('ASSIGNED_USER_ID', $current_user->id);
		$xtpl->assign('MYINBOX', 'this.form.type.value=\'inbound\';');
}
////	END SPECIAL INBOUND LANDING SCREEN ASSIGNS
///////////////////////////////////////

echo '<script>var disabledModules='. $json->encode($disabled_parent_types) . ';</script>';
$jsVars = 'var lbl_send_anyways = "'.$mod_strings['LBL_SEND_ANYWAYS'].'";';
$xtpl->assign('JS_VARS', $jsVars);
$xtpl->parse("main");
$xtpl->out("main");
echo '<script>checkParentType(document.EditView.parent_type.value, document.EditView.change_parent);</script>';
////	END XTEMPLATE ASSIGNMENT
///////////////////////////////////////////////////////////////////////////////

require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$skip_fields = array();
if($email_type == 'out') {
	$skip_fields['name'] = 1;
	$skip_fields['date_start'] = 1;
}
$javascript->addAllFields('',$skip_fields);
$javascript->addToValidateBinaryDependency('parent_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_MEMBER_OF'], 'false', '', 'parent_id');




$javascript->addToValidateBinaryDependency('user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
if($email_type == 'archived') {
	$javascript->addFieldGeneric('date_start', 'alpha', $mod_strings['ERR_DATE_START'], true);
	$javascript->addFieldGeneric('time_start', 'alpha', $mod_strings['ERR_TIME_START'], true);
}
echo $javascript->getScript();
?>
