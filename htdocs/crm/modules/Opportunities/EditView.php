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
 * $Id: EditView.php,v 1.96 2006/08/03 00:12:45 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Opportunities/Forms.php');
require_once('modules/Currencies/Currency.php');

global $timedate;
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $sugar_version, $sugar_config;

// Unimplemented until jscalendar language files are fixed
// global $current_language;
// global $default_language;
// global $cal_codes;

$focus = new Opportunity();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
    $focus->format_all_fields();
}


//needed when creating a new opportunity with a default account value passed in
$prefillArray = array('account_name' => 'account_name', 
                      'account_id'   => 'account_id',
                      'contact_id'   => 'contact_id',
                      'name'         => 'name',
                      'amount'       => 'amount', 
                      'date_closed'  => 'date_closed',
                      'sales_stage'  => 'sales_stage',
                      'probability'  => 'probability',
                      'currency_id'  => 'currency_id',
                      'lead_source'  => 'lead_source');
foreach($prefillArray as $requestKey => $focusVar) {
    if (isset($_REQUEST[$requestKey]) && is_null($focus->$focusVar)) {
        $focus->$focusVar = urldecode($_REQUEST[$requestKey]);
    }
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Opportunity detail view");
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";

$xtpl=new XTemplate ('modules/Opportunities/EditView.html');
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$xtpl->assign("DUPLICATE_PARENT_ID", $focus->id);	
	$focus->id = "";
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

//////////////////////////////////////
///
/// SETUP ACCOUNT POPUP

$popup_request_data = array(
	'call_back_function' => 'set_return',
	'form_name' => 'EditView',
	'field_to_name_array' => array(
		'id' => 'account_id',
		'name' => 'account_name',
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
//
///////////////////////////////////////

// Unimplemented until jscalendar language files are fixed
// $xtpl->assign("CALENDAR_LANG", ((empty($cal_codes[$current_language])) ? $cal_codes[$default_language] : $cal_codes[$current_language]));
$xtpl->assign("CALENDAR_LANG", "en");
$xtpl->assign("USER_DATEFORMAT", '('. $timedate->get_user_date_format().')');
$xtpl->assign("CALENDAR_DATEFORMAT", $timedate->get_cal_date_format());

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if (empty($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ACTION", 'index');
}
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('account_name' => $qsd->getQSParent(), 
					'assigned_user_name' => $qsd->getQSUser(),



					);
$sqs_objects['account_name']['populate_list'] = array('account_name', 'account_id');

$quicksearch_js = $qsd->getQSScripts();
$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';

$xtpl->assign("JAVASCRIPT", get_set_focus_js() . $quicksearch_js);
$xtpl->assign("ID", $focus->id);
$seps = get_number_seperators();
$xtpl->assign("NUM_GRP_SEP", $seps[0]);
$xtpl->assign("DEC_SEP", $seps[1]);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("ACCOUNT_ID", $focus->account_id);
$xtpl->assign("CONTACT_ID", $focus->contact_id);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
$xtpl->assign("AMOUNT", $focus->amount);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_CLOSED", $focus->date_closed);
$xtpl->assign("NEXT_STEP", $focus->next_step);
$xtpl->assign("PROBABILITY", $focus->probability);
$xtpl->assign("DESCRIPTION", $focus->description);
if(isset($_REQUEST['email_id'])) {	$xtpl->assign("EMAIL_ID", $_REQUEST['email_id']); }
if (empty($focus->assigned_user_id) && empty($focus->id))  $focus->assigned_user_id = $current_user->id;
if (empty($focus->assigned_name) && empty($focus->id))  $focus->assigned_user_name = $current_user->user_name;
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ASSIGNED_USER_NAME", $focus->assigned_user_name);
$xtpl->assign("ASSIGNED_USER_ID", $focus->assigned_user_id );
$xtpl->assign("LEAD_SOURCE_OPTIONS", get_select_options_with_id($app_list_strings['lead_source_dom'], $focus->lead_source));
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['opportunity_type_dom'], $focus->opportunity_type));
$xtpl->assign("SALES_STAGE_OPTIONS", get_select_options_with_id($app_list_strings['sales_stage_dom'], $focus->sales_stage));
global $current_user;
require_once('modules/Currencies/ListCurrency.php');
$currency = new ListCurrency();
if(isset($focus->currency_id) && !empty($focus->currency_id)){
	$selectCurrency = $currency->getSelectOptions($focus->currency_id);
	$xtpl->assign("CURRENCY", $selectCurrency);
}
else if($current_user->getPreference('currency') && !isset($focus->id))
{
	$selectCurrency = $currency->getSelectOptions($current_user->getPreference('currency'));
	$xtpl->assign("CURRENCY", $selectCurrency);
}else{

	$selectCurrency = $currency->getSelectOptions();
	$xtpl->assign("CURRENCY", $selectCurrency);

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
















$xtpl->parse("main.open_source");




echo $currency->getJavascript();

$xtpl->parse("main");
$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addToValidateBinaryDependency('account_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $mod_strings['LBL_ACCOUNT_NAME'], 'false', '', 'account_id');




$javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
$javascript->addAllFields('');
echo $javascript->getScript();

$prob_array = $json->encode($app_list_strings['sales_probability_dom']);

$prePopProb = '';
if(empty($focus->id)) $prePopProb = 'document.getElementsByName(\'sales_stage\')[0].onchange();';

echo <<<EOQ
	<script>
	prob_array = $prob_array;
	document.getElementsByName('sales_stage')[0].onchange = function() {
			if(typeof(document.getElementsByName('sales_stage')[0].value) != "undefined" && prob_array[document.getElementsByName('sales_stage')[0].value]) {
				document.getElementsByName('probability')[0].value = prob_array[document.getElementsByName('sales_stage')[0].value];
			} 
		};
	$prePopProb
	</script>
EOQ;
//

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Opportunities')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;

?>
