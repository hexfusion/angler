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
 * $Id: EditView.php,v 1.84 2006/08/03 00:01:13 wayne Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Accounts/Forms.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $sugar_version, $sugar_config;

$focus = new Account();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$prefillArray = array('website'	 	 => 'website',
					  'name' 	 	 => 'name',
					  'phone_office' => 'phone_office',
					  'email1' 		 => 'email1',					  
 					);
foreach($prefillArray as $requestKey => $focusVar) {
    if (isset($_REQUEST[$requestKey]) && is_null($focus->$focusVar)) {
        $focus->$focusVar = urldecode($_REQUEST[$requestKey]);
    }
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Account detail view");

$xtpl=new XTemplate ('modules/Accounts/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);


///////////////////////////////////////
///
/// SETUP PARENT POPUP
/// Account Popup
$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'parent_id',
		'name' => 'parent_name',
		),
	);


$json = getJSONobj();

$encoded_popup_request_data = $json->encode($popup_request_data);
$xtpl->assign('encoded_popup_request_data', $encoded_popup_request_data);

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


if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
if(isset($_REQUEST['bug_id'])) $xtpl->assign("BUG_ID", $_REQUEST['bug_id']);
if(isset($_REQUEST['email_id'])) $xtpl->assign("EMAIL_ID", $_REQUEST['email_id']);

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('parent_name' => $qsd->getQSParent(), 
					'assigned_user_name' => $qsd->getQSUser(),



					);
$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js().$quicksearch_js);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ANNUAL_REVENUE", $focus->annual_revenue);
$xtpl->assign("BILLING_ADDRESS_STREET", $focus->billing_address_street);
$xtpl->assign("BILLING_ADDRESS_CITY", $focus->billing_address_city);
$xtpl->assign("BILLING_ADDRESS_STATE", $focus->billing_address_state);
$xtpl->assign("BILLING_ADDRESS_POSTALCODE", $focus->billing_address_postalcode);
$xtpl->assign("BILLING_ADDRESS_COUNTRY", $focus->billing_address_country);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("EMPLOYEES", $focus->employees);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("INDUSTRY", $focus->industry);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
$xtpl->assign("OWNERSHIP", $focus->ownership);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("PHONE_ALTERNATE", $focus->phone_alternate);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("PHONE_OFFICE", $focus->phone_office);
$xtpl->assign("RATING", $focus->rating);
$xtpl->assign("SHIPPING_ADDRESS_STREET", $focus->shipping_address_street);
$xtpl->assign("SHIPPING_ADDRESS_CITY", $focus->shipping_address_city);
$xtpl->assign("SHIPPING_ADDRESS_STATE", $focus->shipping_address_state);
$xtpl->assign("SHIPPING_ADDRESS_COUNTRY", $focus->shipping_address_country);
$xtpl->assign("SHIPPING_ADDRESS_POSTALCODE", $focus->shipping_address_postalcode);
$xtpl->assign("SIC_CODE", $focus->sic_code);
$xtpl->assign("TICKER_SYMBOL", $focus->ticker_symbol);
$xtpl->assign("ACCOUNT_TYPE", $focus->account_type);
$xtpl->assign("WEBSITE", $focus->website);
$xtpl->assign("SUGAR_VERSION", $sugar_version);
$xtpl->assign("JS_CUSTOM_VERSION", $sugar_config['js_custom_version']);

if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");	
}

if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id = $current_user->id;
if (empty($focus->assigned_name) && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );
$xtpl->assign("ACCOUNT_TYPE_OPTIONS", get_select_options_with_id($app_list_strings['account_type_dom'], $focus->account_type));
$xtpl->assign("INDUSTRY_OPTIONS", get_select_options_with_id($app_list_strings['industry_dom'], $focus->industry));
//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');
















if(isset($_REQUEST['parent_id'])) $xtpl->assign("PARENT_ID", $_REQUEST['parent_id']);
if(isset($_REQUEST['parent_name'])) $xtpl->assign("PARENT_NAME", urldecode($_REQUEST['parent_name']));
if(isset($_REQUEST['billing_address_street'])) $xtpl->assign("BILLING_ADDRESS_STREET", urldecode($_REQUEST['billing_address_street']));
if(isset($_REQUEST['billing_address_city'])) $xtpl->assign("BILLING_ADDRESS_CITY", urldecode($_REQUEST['billing_address_city']));
if(isset($_REQUEST['billing_address_state'])) $xtpl->assign("BILLING_ADDRESS_STATE", urldecode($_REQUEST['billing_address_state']));
if(isset($_REQUEST['billing_address_postalcode'])) $xtpl->assign("BILLING_ADDRESS_POSTALCODE", urldecode($_REQUEST['billing_address_postalcode']));
if(isset($_REQUEST['billing_address_country'])) $xtpl->assign("BILLING_ADDRESS_COUNTRY", urldecode($_REQUEST['billing_address_country']));
if(isset($_REQUEST['ownership'])) $xtpl->assign("OWNERSHIP", urldecode($_REQUEST['ownership']));
if(isset($_REQUEST['website'])) $xtpl->assign("WEBSITE", urldecode($_REQUEST['website']));
if(isset($_REQUEST['industry'])) $xtpl->assign("INDUSTRY_OPTIONS", get_select_options_with_id($app_list_strings['industry_dom'], urldecode($_REQUEST['industry'])));
if(isset($_REQUEST['account_type'])) $xtpl->assign("ACCOUNT_TYPE_OPTIONS", get_select_options_with_id($app_list_strings['account_type_dom'], urldecode($_REQUEST['account_type'])));


$xtpl->parse("main");

$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
$javascript->addToValidateBinaryDependency('parent_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_MEMBER_OF'], 'false', '', 'parent_id');




$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
echo $javascript->getScript();

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Accounts')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;

?>
