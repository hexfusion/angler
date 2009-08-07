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
 * $Id: DetailView.php,v 1.3 2006/06/06 17:57:56 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/CampaignTrackers/CampaignTracker.php');


global $app_strings;
global $mod_strings;

$focus = new CampaignTracker();
$focus->retrieve($_REQUEST['record']);

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->tracker_name, true);
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("campaign tracker detail view");

$xtpl=new XTemplate ('modules/CampaignTrackers/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) {
	$xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
} else {
	$xtpl->assign("RETURN_MODULE", 'Campaigns');
}
if (isset($_REQUEST['return_action'])) {
	$xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
} else {
	$xtpl->assign("RETURN_ACTION", 'DetailView');
}
if (isset($_REQUEST['return_id'])) {
	$xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
} else {
	$xtpl->assign("RETURN_ID", $focus->campaign_id);
}
 
$xtpl->assign("THEME", $theme);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
if (!empty($_REQUEST['campaign_name'])) {
	$xtpl->assign("CAMPAIGN_NAME", $_REQUEST['campaign_name']);
} else  {
	$xtpl->assign("CAMPAIGN_NAME", $focus->campaign_name);
}

if (!empty($_REQUEST['campaign_id'])) {
	$xtpl->assign("CAMPAIGN_ID", $_REQUEST['campaign_id']);
} else {
	$xtpl->assign("CAMPAIGN_ID", $focus->campaign_id);
}
$xtpl->assign("TRACKER_NAME", $focus->tracker_name);
$xtpl->assign("TRACKER_URL", $focus->tracker_url);
$xtpl->assign("MESSAGE_URL", $focus->message_url);
$xtpl->assign("TRACKER_KEY", $focus->tracker_key);

if (!empty($focus->is_optout) && $focus->is_optout == 1) {
	$xtpl->assign("IS_OPTOUT_CHECKED","checked");
}


//$xtpl->assign("CREATED_BY", $focus->created_by_name);
//$xtpl->assign("MODIFIED_BY", $focus->modified_by_name);
//$xtpl->assign("DATE_MODIFIED", $focus->date_modified);
//$xtpl->assign("DATE_ENTERED", $focus->date_entered);

$xtpl->parse("main");
$xtpl->out("main");
?>
