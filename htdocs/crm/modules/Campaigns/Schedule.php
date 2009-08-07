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
 * $Id: Schedule.php,v 1.10 2006/06/06 17:57:56 majed Exp $
 * Description: 
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/EmailMarketing/EmailMarketing.php');
require_once('themes/'.$theme.'/layout_utils.php');

require_once('include/ListView/ListView.php');
$test=false;
if (isset($_POST['mode']) && $_POST['mode'] == 'test') {
	$test=true;
}

global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;
global $urlPrefix;
global $currentModule;

$current_module_strings = return_module_language($current_language, 'EmailMarketing');
echo "\n<p>\n";
if ($test)  {
	echo get_module_title('Campaigns', $current_module_strings['LBL_MODULE_SEND_TEST'], false);
} else {
	echo get_module_title('Campaigns', $current_module_strings['LBL_MODULE_SEND_EMAILS'], false);
}
echo "\n</p>\n";
global $theme;

$focus = new EmailMarketing();
if(isset($_REQUEST['record']))
{
	// we have a query
	if (isset($_REQUEST['record'])) $campaign_id = $_REQUEST['record'];

	$where_clauses = Array();

	if(isset($campaign_id) && !empty($campaign_id)) array_push($where_clauses, "campaign_id = '".PearDatabase::quote($campaign_id)."'");

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$GLOBALS['log']->info("Here is the where clause for the list view: $where");
}

$ListView = new ListView();
$ListView->initNewXTemplate('modules/Campaigns/Schedule.html',$current_module_strings);

if ($test)  {
	$ListView->xTemplateAssign("SCHEDULE_MESSAGE_HEADER",$current_module_strings['LBL_SCHEDULE_MESSAGE_TEST']);
} else {
	$ListView->xTemplateAssign("SCHEDULE_MESSAGE_HEADER",$current_module_strings['LBL_SCHEDULE_MESSAGE_EMAILS']);
}

//force multi-select popup
$ListView->process_for_popups=true;
$ListView->multi_select_popup=true;
//end
$ListView->show_export_button=false;
$ListView->setDisplayHeaderAndFooter(false);
$ListView->xTemplateAssign("RETURN_MODULE",$_POST['return_module']);
$ListView->xTemplateAssign("RETURN_ACTION",$_POST['return_action']);
$ListView->xTemplateAssign("RETURN_ID",$_POST['record']);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);
$ListView->setQuery($where, "", "name", "EMAILMARKETING");

if ($test) {
		$ListView->xTemplateAssign("MODE",$_POST['mode']);
		//finds all marketing messages that have an association with prospect list of the test.
		//this query can be siplified using sub-selects.
		$query="select distinct email_marketing.id email_marketing_id from email_marketing ";
		$query.=" inner join email_marketing_prospect_lists empl on empl.email_marketing_id = email_marketing.id ";
		$query.=" inner join prospect_lists on prospect_lists.id = empl.prospect_list_id ";
		$query.=" inner join prospect_list_campaigns plc on plc.prospect_list_id = empl.prospect_list_id ";
		$query.=" where empl.deleted=0  ";
		$query.=" and prospect_lists.deleted=0 ";
		$query.=" and prospect_lists.list_type='test' ";
		$query.=" and plc.deleted=0 ";
		$query.=" and plc.campaign_id='$campaign_id'";
		$query.=" and email_marketing.campaign_id='$campaign_id'";
		$query.=" and email_marketing.deleted=0 ";
		$query.=" and email_marketing.all_prospect_lists=0 ";

		$seed=array();

		$result=$focus->db->query($query);
		while(($row=$focus->db->fetchByAssoc($result)) != null) {
			
			$bean = new EmailMarketing();
			$bean->retrieve($row['email_marketing_id']);
			$bean->mode='test';	
			$seed[]=$bean;
		}
		$query=" select email_marketing.id email_marketing_id from email_marketing ";
		$query.=" WHERE email_marketing.campaign_id='$campaign_id'";
		$query.=" and email_marketing.deleted=0 ";
		$query.=" and email_marketing.all_prospect_lists=1 ";

		$result=$focus->db->query($query);
		while(($row=$focus->db->fetchByAssoc($result)) != null) {
			
			$bean = new EmailMarketing();
			$bean->retrieve($row['email_marketing_id']);
			$bean->mode='test';	
			$seed[]=$bean;
		}

		$ListView->processListView($seed, "main", "EMAILMARKETING");
} else {
	$ListView->processListView($focus, "main", "EMAILMARKETING");
}
?>
