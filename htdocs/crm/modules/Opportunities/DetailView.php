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
 * $Id: DetailView.php,v 1.97 2006/08/03 00:12:45 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Opportunities/Forms.php');
require_once('include/DetailView/DetailView.php');


global $mod_strings;
global $app_strings;
global $app_list_strings;

$focus = new Opportunity();
$detailView = new DetailView();
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("OPPORTUNITY", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Accounts&action=index");
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Opportunity detail view");

$focus->format_all_fields();
$xtpl=new XTemplate ('modules/Opportunities/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ACCOUNT_NAME", $focus->account_name);
$xtpl->assign("ACCOUNT_ID", $focus->account_id);
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$LS = trim($focus->lead_source);
if(!empty($LS)){
	$xtpl->assign("LEAD_SOURCE", $app_list_strings['lead_source_dom'][$focus->lead_source]);
	}else{
		$xtpl->assign("LEAD_SOURCE", " ");
	}
$xtpl->assign("NAME", $focus->name);
$opp_type = trim($focus->opportunity_type);
if(!empty($opp_type)){
	$xtpl->assign("TYPE", $app_list_strings['opportunity_type_dom'][$focus->opportunity_type]);
	}else{
		$xtpl->assign("TYPE", " ");
	}

if($focus->amount != '') $xtpl->assign("AMOUNT", $focus->amount);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DATE_CLOSED", $focus->date_closed);
$xtpl->assign("NEXT_STEP", $focus->next_step);
$xtpl->assign("SALES_STAGE", $app_list_strings['sales_stage_dom'][$focus->sales_stage]);
$xtpl->assign("PROBABILITY", $focus->probability);
$xtpl->assign("DESCRIPTION", nl2br(url2html($focus->description)));
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);
$xtpl->assign("TAG", $focus->listviewACLHelper());
require_once('modules/Currencies/Currency.php');
$currency  = new Currency();
if(isset($focus->currency_id) && !empty($focus->currency_id))
{
	$currency->retrieve($focus->currency_id);
	if( $currency->deleted != 1){
		$xtpl->assign("CURRENCY", $currency->iso4217 .' '.$currency->symbol );
	}else $xtpl->assign("CURRENCY", $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol() );
}else{

	$xtpl->assign("CURRENCY", $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol() );

}
global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){

	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}
$detailView->processListNavigation($xtpl, "OPPORTUNITY", $offset, $focus->is_AuditEnabled());
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');







$xtpl->parse("main.open_source");













$xtpl->parse("main");
$xtpl->out("main");

$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();
ob_start();
echo $old_contents;

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'Opportunities');
echo $subpanel->display();
echo ACLController::addJavascript($focus->module_dir,'',$focus->assigned_user_name == $current_user->user_name);

require_once('modules/SavedSearch/SavedSearch.php');
$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Opportunities')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>
