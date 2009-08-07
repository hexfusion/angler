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
 * $Id: TrackDetailView.php,v 1.12 2006/06/06 17:57:56 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Campaigns/Campaign.php');
require_once('modules/Campaigns/Forms.php');
require_once('include/DetailView/DetailView.php');
require_once('modules/Campaigns/Charts.php');


global $mod_strings;
global $app_strings;
global $app_list_strings;
global $sugar_version, $sugar_config;

$focus = new Campaign();

$detailView = new DetailView();
$offset = 0;
$offset=0;
if (isset($_REQUEST['offset']) or isset($_REQUEST['record'])) {
	$result = $detailView->processSugarBean("CAMPAIGN", $focus, $offset);
	if($result == null) {
	    sugar_die($app_strings['ERROR_NO_RECORD']);
	}
	$focus=$result;
} else {
	header("Location: index.php?module=Accounts&action=index");
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Campaign detail view");

$xtpl=new XTemplate ('modules/Campaigns/TrackDetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("ASSIGNED_TO", $focus->assigned_user_name);
$xtpl->assign("STATUS", $app_list_strings['campaign_status_dom'][$focus->status]);
$xtpl->assign("NAME", $focus->name);
$xtpl->assign("TYPE", $app_list_strings['campaign_type_dom'][$focus->campaign_type]);
$xtpl->assign("START_DATE", $focus->start_date);
$xtpl->assign("END_DATE", $focus->end_date);

$xtpl->assign("BUDGET", $focus->budget);
$xtpl->assign("ACTUAL_COST", $focus->actual_cost);
$xtpl->assign("EXPECTED_COST", $focus->expected_cost);
$xtpl->assign("EXPECTED_REVENUE", $focus->expected_revenue);


$xtpl->assign("OBJECTIVE", nl2br($focus->objective));
$xtpl->assign("CONTENT", nl2br($focus->content));
$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);

$xtpl->assign("CREATED_BY", $focus->created_by_name);
$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);
$xtpl->assign("TRACKER_URL", $sugar_config['site_url'] . '/campaign_tracker.php?track=' . $focus->tracker_key);
$xtpl->assign("TRACKER_COUNT", intval($focus->tracker_count));
$xtpl->assign("TRACKER_TEXT", $focus->tracker_text);
$xtpl->assign("REFER_URL", $focus->refer_url);

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

$detailView->processListNavigation($xtpl, "CAMPAIGN", $offset, $focus->is_AuditEnabled());
// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');






$xtpl->parse("main.open_source");






//add chart
$seps				= array("-", "/");
$dates				= array(date('Y-m-d'), date('Y-m-d'));
$dateFileNameSafe	= str_replace($seps, "_", $dates);
$cache_file_name	= $current_user->getUserPrivGuid()."_campaign_response_by_activity_type_".$dateFileNameSafe[0]."_".$dateFileNameSafe[1].".xml";
$chart= new charts();
$xtpl->assign("MY_CHART", $chart->campaign_response_by_activity_type($app_list_strings['campainglog_activity_type_dom'],$app_list_strings['campainglog_target_type_dom'],$focus->id,$sugar_config['tmp_dir'].$cache_file_name,true));
//end chart

$xtpl->parse("main");
$xtpl->out("main");

require_once('include/SubPanel/SubPanelTiles.php');
$subpanel = new SubPanelTiles($focus, 'Campaigns');
$alltabs=$subpanel->subpanel_definitions->get_available_tabs();
if (!empty($alltabs)) {
		
	foreach ($alltabs as $name) {
		if ($name == 'prospectlists' || $name=='emailmarketing' || $name == 'tracked_urls') {
			$subpanel->subpanel_definitions->exclude_tab($name);			
		}	
	}
}
echo $subpanel->display();
?>
