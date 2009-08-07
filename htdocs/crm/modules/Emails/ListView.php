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
 * $Id: ListView.php,v 1.93 2006/07/12 22:53:10 jenny Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
// to prevent StoreQuery() from hijacking all values.
$_REQUEST = $_REQUEST;
/* default to Inbox view: this does belong in the OUTPUT section, but we need these set for StoreQuery */
if(!empty($_REQUEST['action']) && $_REQUEST['action'] == 'index') {
	global $current_user;
	$_REQUEST['type'] = 'inbound';
	$_REQUEST['assigned_user_id'] = $current_user->id;
}
//_ppd($current_user->user_preferences);
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Emails/Email.php');
require_once('themes/'.$theme.'/layout_utils.php');

require_once('include/ListView/ListView.php');
require_once('include/utils.php');
require_once('modules/MySettings/StoreQuery.php');
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $urlPrefix;
global $currentModule;
global $image_path;
global $theme;
global $focus_list; // focus_list is the means of passing data to a ListView.

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
echo $qsd->GetQSScripts();

$focus				= new Email();
$header_text		= '';
$where				= '';
$type				= '';
$assigned_user_id	= '';
$group				= '';
$search_adv			= '';
$whereClauses		= array();
$error				= '';

///////////////////////////////////////////////////////////////////////////////
////
////	SEARCH FORM FUNCTIONALITY
////	SEARCH QUERY GENERATION
$storeQuery = new StoreQuery();

// this allows My Inbox, Group Inbox, etc. to have separate stored queries
// for the same ListView.php
if(isset($_REQUEST['type'])) $Qtype = $_REQUEST['type'];
else $Qtype = '';
if(isset($_REQUEST['assigned_user_id']) && $_REQUEST['assigned_user_id'] == $current_user->id) {
	$Qassigned_user_id = $_REQUEST['assigned_user_id'];
} else {
	$Qassigned_user_id = '';
}

if(!isset($_REQUEST['query'])){
	//_pp('loading: '.$currentModule.$Qtype.$Qgroup);
	//_pp($current_user->user_preferences[$currentModule.$Qtype.'Q']);
	$storeQuery->loadQuery($currentModule.$Qtype);
	$storeQuery->populateRequest();
} else {
	//_pp($current_user->user_preferences[$currentModule.$Qtype.'Q']);
	//_pp('saving: '.$currentModule.$Qtype);
	$storeQuery->saveFromGet($currentModule.$Qtype);
}


if(isset($_REQUEST['query'])) {
	// we have a query
	if (isset($_REQUEST['email_type']))		$email_type = $_REQUEST['email_type'];
	if (isset($_REQUEST['assigned_to']))	$assigned_to = $_REQUEST['assigned_to'];
	if (isset($_REQUEST['status']))			$status = $_REQUEST['status'];
	if (isset($_REQUEST['name']))			$name = $_REQUEST['name'];
	if (isset($_REQUEST['contact_name']))	$contact_name = $_REQUEST['contact_name'];
	
	if(isset($email_type) && $email_type != "")		$whereClauses['emails.type'] = "emails.type = '".PearDatabase::quote($email_type)."'";
	if(isset($assigned_to) && $assigned_to != "")	$whereClauses['emails.assigned_user_id'] = "emails.assigned_user_id = '".PearDatabase::quote($assigned_to)."'";
	if(isset($status) && $status != "")				$whereClauses['emails.status'] = "emails.status = '".PearDatabase::quote($status)."'";
	if(isset($name) && $name != "")					$whereClauses['emails.name'] = "emails.name like '".PearDatabase::quote($name)."%'";
	if(isset($contact_name) && $contact_name != '') {
		$contact_names = explode(" ", $contact_name);
		foreach ($contact_names as $name) {
			$whereClauses['contacts.name'] = "(contacts.first_name like '".PearDatabase::quote($name)."%' OR contacts.last_name like '".PearDatabase::quote($name)."%')";
		}
	}

	$focus->custom_fields->setWhereClauses($whereClauses);
	$GLOBALS['log']->info("Here is the where clause for the list view: $where");
} // end isset($_REQUEST['query'])



////	OUTPUT GENERATION

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// ASSIGNMENTS pre-processing
	$email_type_sel = '';
	$assigned_to_sel = '';
	$status_sel = '';
	if(isset($_REQUEST['email_type']))		$email_type_sel = $_REQUEST['email_type'];
	if(isset($_REQUEST['assigned_to']))		$assigned_to_sel = $_REQUEST['assigned_to'];
	if(isset($_REQUEST['status']))			$status_sel = $_REQUEST['status'];
	if(isset($_REQUEST['search']))			$search_adv = $_REQUEST['search'];

	// drop-downs values
	$r = $focus->db->query("SELECT id, user_name FROM users WHERE deleted = 0 AND status = 'Active' OR users.is_group = 1 ORDER BY status");
	$users[] = '';
	while($a = $focus->db->fetchByAssoc($r)) {
		$users[$a['id']] = $a['user_name'];
	}
	
	$email_types[] = '';
	$email_types = array_merge($email_types, $app_list_strings['dom_email_types']);
	$email_status[] = '';
	$email_status = array_merge($email_status, $app_list_strings['dom_email_status']);
	$types			= get_select_options_with_id($email_types, $email_type_sel);
	$assigned_to	= get_select_options_with_id($users, $assigned_to_sel);
	$email_status	= get_select_options_with_id($email_status, $status_sel);
	
	// ASSIGNMENTS AND OUTPUT
	if(isset($_REQUEST['type']) && $_REQUEST['type'] != '') $emailType = $_REQUEST['type'];
	else $emailType = '';
	switch($emailType) {
		case 'out':
			$search_form = new XTemplate ('modules/Emails/SearchFormSent.html');
		break;

		case 'draft':
			$search_form = new XTemplate ('modules/Emails/SearchFormSent.html');
		break;

		case 'archived':
		case 'inbound':
			$search_form = new XTemplate ('modules/Emails/SearchFormMyInbox.html');
		break;
		
		default:
			$search_form = new XTemplate ('modules/Emails/SearchFormMyInbox.html');
		break;
	}
	
	$search_form->assign('MOD', $mod_strings);
	$search_form->assign('APP', $app_strings);
	$search_form->assign('IMAGE_PATH', $image_path);
	$search_form->assign('ADVANCED_SEARCH_PNG', get_image($image_path.'advanced_search','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
	$search_form->assign('BASIC_SEARCH_PNG', get_image($image_path.'basic_search','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));
	$search_form->assign('TYPE_OPTIONS', $types);
	$search_form->assign('ASSIGNED_TO_OPTIONS', $assigned_to);
	$search_form->assign('STATUS_OPTIONS', $email_status);
	$search_form->assign('ADV_URL', $_SERVER['REQUEST_URI']);
	$search_form->assign('SEARCH_ADV', $search_adv);
	

	if(isset($_REQUEST['name']))			$search_form->assign('NAME', $_REQUEST['name']);
	if(isset($_REQUEST['contact_name']))	$search_form->assign('CONTACT_NAME', $_REQUEST['contact_name']);
	if(isset($current_user_only))			$search_form->assign('CURRENT_USER_ONLY', "checked");

	// adding custom fields:
	$focus->custom_fields->populateXTPL($search_form, 'search' );
	$search_form->assign('SEARCH_ACTION', 'ListView');
	$search_form->assign('TYPE', $Qtype);
	if(!empty($_REQUEST['assigned_user_id'])) {
		$search_form->assign('ASSIGNED_USER_ID', $_REQUEST['assigned_user_id']);
	}
	$search_form->assign('JAVASCRIPT', $focus->js_set_archived().$focus->u_get_clear_form_js($Qtype, '', $Qassigned_user_id));
}
////	END SEARCH FORM FUNCTIONALITY
////	
///////////////////////////////////////////////////////////////////////////////







///////////////////////////////////////////////////////////////////////////////
////
////	INBOX FUNCTIONALITY
// for Inbox

if(isset($_REQUEST['type']) && !empty($_REQUEST['type'])) {
	$type = $_REQUEST['type'];
	switch ($_REQUEST['type']) {
		case 'archived':
			// disabling since it would break the "INBOUND" thing
			$search_form->assign('TYPE_DISABLED', 'DISABLED');
			
			if( (isset($_REQUEST['assigned_user_id']) && $_REQUEST['assigned_user_id'] != '') ) {
				// would break the user's email thing
				$search_form->assign('ASSIGN_TO_DISABLED', 'DISABLED');
				
				$assigned_user_id = $_REQUEST['assigned_user_id'];

				$whereClauses['emails.assigned_user_id'] = "emails.assigned_user_id = '{$_REQUEST['assigned_user_id']}'";
				$whereClauses['emails.status'] = "emails.status = 'archived'";
				$display_title = $mod_strings['LBL_LIST_TITLE_MY_ARCHIVES'];
			}
		
		break;
		
		case 'inbound':
			// disabling since it would break the "INBOUND" thing
			$search_form->assign('TYPE_DISABLED', 'DISABLED');
			
			if( (isset($_REQUEST['assigned_user_id']) && $_REQUEST['assigned_user_id'] != '') ) {
				// would break the user's email thing
				$search_form->assign('ASSIGN_TO_DISABLED', 'DISABLED');
				
				$assigned_user_id = $_REQUEST['assigned_user_id'];

				$whereClauses['emails.assigned_user_id'] = "emails.assigned_user_id = '{$_REQUEST['assigned_user_id']}'";
				$whereClauses['emails.type'] = "emails.type = '{$_REQUEST['type']}'";
				$whereClauses['emails.status'] = "emails.status != 'archived'";
				if(!empty($_REQUEST['status'])) {
					$whereClauses['emails.status'] = "emails.status != 'archived' AND emails.status = '{$_REQUEST['status']}'";
				}
				
				$display_title = $mod_strings['LBL_LIST_TITLE_MY_INBOX'];
			}
		break;
		
		case 'draft':
			// disabling since it would break the "DRAFT" thing
			$search_form->assign('TYPE_DISABLED', 'DISABLED');

			if( (isset($_REQUEST['assigned_user_id']) && $_REQUEST['assigned_user_id'] != '') ) {
				// would break the user's email thing
				$search_form->assign('ASSIGN_TO_DISABLED', 'DISABLED');

				$whereClauses['emails.assigned_user_id'] = 'emails.assigned_user_id = \''.$_REQUEST['assigned_user_id'].'\' ';
				
				$display_title = $mod_strings['LBL_LIST_TITLE_MY_DRAFTS'];
			} else {
				$display_title = $mod_strings['LNK_DRAFTS_EMAIL_LIST'];
			}

			$whereClauses['emails.type'] = 'emails.type = \''.$_REQUEST['type'].'\'';
			// ListForm title:
			
		break;
		
		case 'out':
			// disabling since it would break the "SENT" thing
			$search_form->assign('TYPE_DISABLED', 'DISABLED');

			if( (isset($_REQUEST['assigned_user_id']) && $_REQUEST['assigned_user_id'] != '') ) {
				// would break the user's email thing
				$search_form->assign('ASSIGN_TO_DISABLED', 'DISABLED');
				$whereClauses['emails.assigned_user_id'] = 'emails.assigned_user_id = \''.$_REQUEST['assigned_user_id'].'\' ';
			}

			$whereClauses['emails.type'] = ' emails.type=\'out\'';
			
			$display_title = $mod_strings['LBL_LIST_TITLE_MY_SENT'];
		break;
		
		default:
		break;	
	}
} else {
	// STANDARD EMAIL BOX FUNCTIONS
	global $email_title;
	$display_title = $mod_strings['LBL_LIST_FORM_TITLE'];
	if($email_title)$display_title = $email_title;
}
////	END INBOX FUNCTIONALITY
////
///////////////////////////////////////////////////////////////////////////////

$ListView = new ListView();
switch($emailType) {
	case 'inbound':
		$ListView->initNewXTemplate( 'modules/Emails/ListViewMyInbox.html',$mod_strings);
	break;
	
	default:
		$ListView->initNewXTemplate( 'modules/Emails/ListView.html',$mod_strings);
	break;
}


///////////////////////////////////////////////////////////////////////////////
////	OUTPUT
///////////////////////////////////////////////////////////////////////////////
echo "\n<p>\n";
echo get_module_title("Emails", $mod_strings['LBL_MODULE_TITLE'].$display_title, true); 
echo "\n</p>\n";
// admin-edit
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
// search form
echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE']. $header_text, "", false);
// ADVANCED SEARCH
if(isset($_REQUEST['search']) && $_REQUEST['search'] == 'advanced') {
	$search_form->parse('adv');
	$search_form->out('adv');

} else {
	$search_form->parse('main');
	$search_form->out('main');
}
echo get_form_footer();

// CONSTRUCT WHERE STRING FROM WHERECLAUSE ARRAY
foreach($whereClauses as $clause) {
	if($where != "")
	$where .= " AND ";
	$where .= $clause;
}

//echo $where;

if( (isset($_REQUEST['assigned_user_id']) && $_REQUEST['assigned_user_id'] != '') && $_REQUEST['type'] == 'inbound') { 
	$ListView->xTemplateAssign('TAKE',$focus->pickOneButton());
	if($current_user->hasPersonalEmail()) {
		$ListView->xTemplateAssign('CHECK_MAIL',$focus->checkInbox('personal'));
	}
}
if( (isset($_REQUEST['show_error']) && $_REQUEST['show_error'] == 'true') && $_REQUEST['type'] == 'inbound') {
	$ListView->xTemplateAssign('TAKE_ERROR', $focus->takeError());
}
//echo $focus->quickCreateJS();
$ListView->setAdditionalDetails();
$ListView->xTemplateAssign('ATTACHMENT_HEADER', get_image('themes/'.$theme.'/images/attachment',"","",""));
$ListView->xTemplateAssign('ERROR', $error);
$ListView->setHeaderTitle($display_title . $header_text );
$ListView->setQuery($where, "", "date_sent, date_entered DESC", "EMAIL");
$ListView->processListView($focus, "main", "EMAIL");
?>
