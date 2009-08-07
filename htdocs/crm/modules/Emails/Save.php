<?php
//_ppd($_REQUEST);
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
// $Id : Save.php, v 1.47 2005 / 08 / 13 00: 03:34 andrew Exp $
require_once('modules/Emails/Email.php');

global $mod_strings;


///////////////////////////////////////////////////////////////////////////////
////	EMAIL SEND/SAVE SETUP
$focus = new Email();
global $timedate;

if(!isset($prefix)) {
	$prefix = '';
}
if(isset($_POST[$prefix.'meridiem']) && !empty($_POST[$prefix.'meridiem'])) {
	$_POST[$prefix.'time_start'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_start'], $timedate->get_time_format(true), $_POST[$prefix.'meridiem']);
}

//retrieve the record
if(isset($_POST['record'])) {
	$focus->retrieve($_POST['record']);

}
if(isset($_REQUEST['user_id'])) {
	$focus->assigned_user_id = $_REQUEST['user_id'];	
}
if(!$focus->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
}
if(!empty($_POST['assigned_user_id']) && ($focus->assigned_user_id != $_POST['assigned_user_id']) && ($_POST['assigned_user_id'] != $current_user->id)) {
	$check_notify = TRUE;
}
//populate the fields of this Email
$allfields = array_merge($focus->column_fields, $focus->additional_column_fields);
foreach($allfields as $field) {
	if(isset($_POST[$field])) {
		$value = $_POST[$field];
		$focus->$field = $value;
	}
}
//compare the 3 fields and return list of contact_ids to link:
$focus->to_addrs_arr = $focus->parse_addrs($_REQUEST['to_addrs'], $_REQUEST['to_addrs_ids'], $_REQUEST['to_addrs_names'], $_REQUEST['to_addrs_emails']);
$focus->cc_addrs_arr = $focus->parse_addrs($_REQUEST['cc_addrs'], $_REQUEST['cc_addrs_ids'], $_REQUEST['cc_addrs_names'], $_REQUEST['cc_addrs_emails']);
$focus->bcc_addrs_arr = $focus->parse_addrs($_REQUEST['bcc_addrs'], $_REQUEST['bcc_addrs_ids'], $_REQUEST['to_addrs_names'], $_REQUEST['bcc_addrs_emails']);


if(!empty($_REQUEST['type'])) {
	$focus->type = $_REQUEST['type'];
} elseif(empty($focus->type)) { // cn: from drafts/quotes
	$focus->type = 'archived';
}

$object_arr = array();
if(!empty($focus->parent_id)) {
	$object_arr[$focus->parent_type] = $focus->parent_id;
}
if(isset($focus->to_addrs_arr[0]['contact_id'])) {
	$object_arr['Contacts'] = $focus->to_addrs_arr[0]['contact_id'];
}

///////////////////////////////////////////////////////////////////////////////
////	FORMAT HTML EMAIL CORRECTLY
$html = trim($focus->description_html);
if(!empty($html)) {
	if(false === stristr($html, '&lt;html')) {
		$focus->description_html = '&lt;html&gt;&lt;body&gt;'.$html.'&lt;/body&gt;&lt;/html&gt;';
	}
}
////	END FORMAT HTML EMAIL CORRECTLY
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	TEMPLATE PARSING
// do not parse email templates if the email is being saved as draft....
if($focus->type != 'draft' && count($object_arr) > 0) {
	require_once($beanFiles['EmailTemplate']);
	$focus->name = EmailTemplate::parse_template($focus->name, $object_arr);
	$focus->description = EmailTemplate::parse_template($focus->description, $object_arr);
	$focus->description_html = EmailTemplate::parse_template($focus->description_html, $object_arr);
}
////	END TEMPLATE PARSING
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	PREP FOR ATTACHMENTS
if(empty($focus->id)){
    $focus->id = create_guid();
    $focus->new_with_id = true;
}
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	ATTACHMENT HANDLING
$focus->handleAttachments();
////	END ATTACHMENT HANDLING
///////////////////////////////////////////////////////////////////////////////

$focus->status = 'draft';
if($focus->type == 'archived' ) {
	$focus->status= 'archived';
} elseif(($focus->type == 'out' || $focus->type == 'forward') && isset($_REQUEST['send']) && $_REQUEST['send'] == '1') {
	///////////////////////////////////////////////////////////////////////////
	////	REPLY PROCESSING
	$old = array('&lt;','&gt;');
	$new = array('<','>');
	if($_REQUEST['from_addr'] != $_REQUEST['from_addr_name'].' &lt;'.$_REQUEST['from_addr_email'].'&gt;') {
		if(false === strpos($_REQUEST['from_addr'], '&lt;')) { // we have an email only?
			$focus->from_addr = $_REQUEST['from_addr'];
			$focus->from_name = '';
		} else { // we have a compound string
			$newFromAddr =  str_replace($old, $new, $_REQUEST['from_addr']);
			$focus->from_addr = substr($newFromAddr, (1 + strpos($newFromAddr, '<')), (strpos($newFromAddr, '>') - strpos($newFromAddr, '<')) -1 );
			$focus->from_name = substr($newFromAddr, 0, (strpos($newFromAddr, '<') -1));
		}
	} elseif(!empty($_REQUEST['from_addr_email']) && isset($_REQUEST['from_addr_email'])) {
		$focus->from_addr = $_REQUEST['from_addr_email'];
		$focus->from_name = $_REQUEST['from_addr_name'];
	} else {
		$focus->from_addr = $focus->getSystemDefaultEmail();
	}
	////	REPLY PROCESSING
	///////////////////////////////////////////////////////////////////////////

	if($focus->send()) {
        $focus->status = 'sent';
        $today = gmdate('Y-m-d H:i:s');
        $focus->date_start = $timedate->to_display_date($today);
        $focus->time_start = $timedate->to_display_time($today, true);
	} else {
		$focus->status = 'send_error';
	}
}
$focus->to_addrs = $_REQUEST['to_addrs'];
$focus->save(FALSE);
////	END EMAIL SAVE/SEND SETUP
///////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////
////	RELATIONSHIP LINKING
$focus->load_relationship('users');
$focus->users->add($current_user->id);

if(!empty($_REQUEST['to_addrs_ids'])) {
	$focus->load_relationship('contacts');
	$exContactIds = explode(';', $_REQUEST['to_addrs_ids']);
	foreach($exContactIds as $contactId) {
		$contactId = trim($contactId);
		$focus->contacts->add($contactId);
	}
}

if(isset($_REQUEST['object_type']) && !empty($_REQUEST['object_type']) && isset($_REQUEST['object_id']) && !empty($_REQUEST['object_id'])) {
	//run linking code only if the object_id has not been linked as part of the contacts above
	if(!in_array($_REQUEST['object_id'],$exContactIds)){
		$rel = strtolower($_REQUEST['object_type']);
		$focus->load_relationship($rel);
		$focus->$rel->add($_REQUEST['object_id']);
	}
}
//// handle legacy parent_id/parent_type relationship calls
elseif(isset($_REQUEST['parent_type']) && !empty($_REQUEST['parent_type']) && isset($_REQUEST['parent_id']) && !empty($_REQUEST['parent_id'])) {
	//run linking code only if the object_id has not been linked as part of the contacts above
	if(!in_array($_REQUEST['parent_id'],$exContactIds)){
		$rel = strtolower($_REQUEST['parent_type']);
		$focus->load_relationship($rel);
		$focus->$rel->add($_REQUEST['parent_id']);
	}
}
////	END RELATIONSHIP LINKING
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	PAGE REDIRECTION
///////////////////////////////////////////////////////////////////////////////
$return_id = $focus->id;

if(empty($_POST['return_module'])) {
	$return_module = "Emails";
} else {
	$return_module = $_POST['return_module'];
}
if(empty($_POST['return_action'])) {
	$return_action = "DetailView";
} else {
	$return_action = $_POST['return_action'];
}
$GLOBALS['log']->debug("Saved record with id of ".$return_id);
require_once('include/formbase.php');
if($focus->type == 'draft') {
	if($return_module == 'Emails') {
		header("Location: index.php?module=$return_module&action=ListViewDrafts");
	} else {
		handleRedirect($return_id, 'Emails');
	}
} elseif($focus->type == 'out') {
	if($return_module == 'Home') {
		header('Location: index.php?module='.$return_module.'&action=index');
	}
	if(!empty($_REQUEST['return_id'])) {
		$return_id = $_REQUEST['return_id'];
	}
	header('Location: index.php?action='.$return_action.'&module='.$return_module.'&record='.$return_id.'&assigned_user_id='.$current_user->id.'&type=inbound');
} elseif(isset($_POST['return_id']) && $_POST['return_id'] != "") {
	$return_id = $_POST['return_id'];
}
	header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
