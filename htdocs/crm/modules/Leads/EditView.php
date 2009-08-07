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
 * $Id: EditView.php,v 1.57 2006/08/03 00:09:49 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Leads/Forms.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
global $sugar_version, $sugar_config;

$focus = new Lead();
if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$convertingprospect=false;
$prospect_id=null;
$campaign=null;
if (isset($_REQUEST['prospect_id']) &&  !empty($_REQUEST['prospect_id'])) {
	$prospect_id=$_REQUEST['prospect_id'];
	$convertingprospect=true;
	if (!class_exists('Prospect')) {
		require_once('modules/Prospects/Prospect.php');
	}
	$prospect=new Prospect();
	$prospect->retrieve($_REQUEST['prospect_id']);

	foreach($prospect->field_defs as $key=>$value)
	{
		//exceptions.
		if ($key == 'id' or $key=='deleted' ) {
			continue;
		}
		if (isset($focus->field_defs[$key])) {
			$focus->$key = $prospect->$key;
		} 
	}
	//additional assignments.



	$focus->assigned_user_name=get_assigned_user_name($prospect->assigned_user_id);
	
	//add campaign selector.
	require_once('include/JSON.php');

	$lbl_select_label = $mod_strings['LBL_TARGET_BUTTON_LABEL'];
	$lbl_select_title = $mod_strings['LBL_TARGET_BUTTON_TITLE'];
	$lbl_select_key = $mod_strings['LBL_TARGET_BUTTON_KEY'];
	$lbl_campaigns = $mod_strings['LBL_TARGET_OF_CAMPAIGNS'];

	$popup_request_data = array(
		'call_back_function' => 'set_return',
		'form_name' => 'EditView',
		'field_to_name_array' => array(
			'campaign_id' => 'campaign_id',
			'campaign_name' => 'campaign_name',
		),
	);

	$json = getJSONobj();
	$encoded_users_popup_request_data = $json->encode($popup_request_data);
	$initial_filter="&target_id=$prospect_id";	

	$campaign=<<<EOQ
		<tr>
			<td width="15%" class="dataLabel">$lbl_campaigns</td>
			<td  width="35%"class="dataField">
				<input id="campaign_id" name='campaign_id' type="hidden" value="">					
				<input id="campaign_name" name='campaign_name' type="text" value="">					
				<input title="$lbl_select_label" accessKey="$lbl_select_key" type="button" tabindex='1' class="button" value='$lbl_select_title' name=btn1
					onclick='open_popup("CampaignLog", 600, 400, "$initial_filter", true, false, $encoded_users_popup_request_data);' />			
			</td>
			<td width="15%" class="dataLabel"><slot>&nbsp;</slot></td>
			<td  width="35%"class="dataField"><slot>&nbsp;</slot></td>
		</tr>
		<tr><td colspan=4>&nbsp;</td></tr>
EOQ;
	//end campaign selector .
} else {
	if (is_null($focus->id)) {
		foreach ($_POST as $name=>$value){
			if (empty($focus->$name)) {
				$focus->$name = $value;
			}
		}
	}
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->first_name." ".$focus->last_name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Lead detail view");

$xtpl=new XTemplate ('modules/Leads/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

/// Users Popup
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'assigned_user_id',
		'user_name' => 'assigned_user_name',
		),
	);
$json = getJSONobj();
$xtpl->assign('encoded_users_popup_request_data', $json->encode($popup_request_data));













// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", "en");$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

$xtpl->assign("CONTACT_ID", $focus->contact_id);
$xtpl->assign("ACCOUNT_ID", $focus->account_id);
$xtpl->assign("OPPORTUNITY_ID", $focus->opportunity_id);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}if (isset($_REQUEST['opportunity_id'])) $xtpl->assign("OPPORTUNITY_ID", $_REQUEST['opportunity_id']);
if (isset($_REQUEST['case_id'])) $xtpl->assign("CASE_ID", $_REQUEST['case_id']);
if (isset($_REQUEST['email_id'])) $xtpl->assign("EMAIL_ID", $_REQUEST['email_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);


require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('assigned_user_name' => $qsd->getQSUser(),



					);
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js() . $quicksearch_js);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("OPPORTUNITY_NAME", $focus->opportunity_name);
$xtpl->assign("OPPORTUNITY_AMOUNT", $focus->opportunity_amount);
$xtpl->assign("HEADER", get_module_title("Contacts", "{MOD.LBL_CONTACT}  ".$focus->first_name." ".$focus->last_name, true));
if (isset($focus->first_name)) $xtpl->assign("FIRST_NAME", $focus->first_name);
else $xtpl->assign("FIRST_NAME", "");
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);
if ($focus->do_not_call == 'on') $xtpl->assign("DO_NOT_CALL", "checked");
if ($focus->invalid_email == '1') $xtpl->assign("INVALID_EMAIL", "checked");
$xtpl->assign("DEFAULT_SEARCH", "&query=true&account_name=".urlencode($focus->account_name));
$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
$xtpl->assign("PHONE_WORK", $focus->phone_work);
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
if ($focus->email_opt_out == 'on') $xtpl->assign("EMAIL_OPT_OUT", "checked");
$xtpl->assign("PRIMARY_ADDRESS_STREET", $focus->primary_address_street);
$xtpl->assign("PRIMARY_ADDRESS_CITY", $focus->primary_address_city);
$xtpl->assign("PRIMARY_ADDRESS_STATE", $focus->primary_address_state);
$xtpl->assign("PRIMARY_ADDRESS_POSTALCODE", $focus->primary_address_postalcode);
$xtpl->assign("PRIMARY_ADDRESS_COUNTRY", $focus->primary_address_country);
$xtpl->assign("REFEED_BY", $focus->refered_by);
$xtpl->assign("ALT_ADDRESS_STREET", $focus->alt_address_street);
$xtpl->assign("ALT_ADDRESS_CITY", $focus->alt_address_city);
$xtpl->assign("ALT_ADDRESS_STATE", $focus->alt_address_state);
$xtpl->assign("ALT_ADDRESS_POSTALCODE", $focus->alt_address_postalcode);
$xtpl->assign("ALT_ADDRESS_COUNTRY", $focus->alt_address_country);
$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("PROSPECT_ID", $prospect_id);


if (!empty($campaign)) {
	$xtpl->assign("CAMPAIGN_SELECTOR", $campaign);
}
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');















if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id = $current_user->id;
if (empty($focus->assigned_name) && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );

$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['lead_status_noblank_dom'], $focus->status));
$xtpl->assign("SALUTATION_OPTIONS", get_select_options_with_id($app_list_strings['salutation_dom'], $focus->salutation));
$xtpl->assign("LEAD_SOURCE_DESCRIPTION", $focus->lead_source_description);
$xtpl->assign("STATUS_DESCRIPTION", $focus->status_description);
$xtpl->assign("REFERED_BY", $focus->refered_by);
///////////////////////////////////////////////////////////////////////////////
////	INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////
if(isset($_REQUEST['inbound_email_id'])) {
	require_once('modules/Emails/Email.php');
	$email = new Email();
	$email->retrieve($_REQUEST['inbound_email_id']);
	$xtpl->assign('FIRST_NAME', $email->getName('first'));
	$xtpl->assign('LAST_NAME', $email->getName('last'));
	$xtpl->assign('DESCRIPTION', $email->getDescription());
	$xtpl->assign('EMAIL1', $email->from_addr);
	$xtpl->assign('INBOUND_EMAIL_ID',$_REQUEST['inbound_email_id']);
	$xtpl->assign('RETURN_ACTION', 'EditView');
	$xtpl->assign('RETURN_MODULE', 'Emails');
	$xtpl->assign('START', base64_encode($_SERVER['HTTP_REFERER']));
	$xtpl->assign('TYPE', 'out');
	$focus->lead_source = 'Email';
}
////	END INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////
// moved this to allow inbound to set the value
$xtpl->assign("LEAD_SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['lead_source_dom'], $focus->lead_source));
$xtpl->parse("main");

$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');




$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
echo $javascript->getScript();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Leads')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
