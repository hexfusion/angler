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
 * $Id: Save.php,v 1.18 2006/06/06 17:58:19 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('modules/Campaigns/Campaign.php');
require_once('modules/ProspectLists/ProspectList.php');
require_once('modules/EmailMarketing/EmailMarketing.php');
require_once('modules/EmailMan/EmailMan.php');

require_once('include/utils.php');
global $timedate;
global $current_user;
if(!empty($_POST['meridiem'])){
	$_POST['time_start'] = $timedate->merge_time_meridiem($_POST['time_start'],$timedate->get_time_format(true), $_POST['meridiem']);
}
$marketing = new EmailMarketing();
if (isset($_POST['record']) && !empty($_POST['record'])) {
	$marketing->retrieve($_POST['record']);
}
if(!$marketing->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
}

if (!empty($_POST['assigned_user_id']) && ($marketing->assigned_user_id != $_POST['assigned_user_id']) && ($_POST['assigned_user_id'] != $current_user->id)) {
	$check_notify = TRUE;
}
else {
	$check_notify = FALSE;
}
foreach($marketing->column_fields as $field)
{
	if ($field == 'all_prospect_lists') {
		if(isset($_POST[$field]) && $_POST[$field]='on' )
		{
			$marketing->$field = 1;
		} else {
			$marketing->$field = 0;			
		}
	}else {
		if(isset($_POST[$field]))
		{
			$value = $_POST[$field];
			$marketing->$field = $value;
		}
	}
}

foreach($marketing->additional_column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$value = $_POST[$field];
		$marketing->$field = $value;

	}
}

$marketing->campaign_id = $_REQUEST['campaign_id'];
$marketing->save($check_notify);

//add prospect lists to campaign.
$marketing->load_relationship('prospectlists');
$prospectlists=$marketing->prospectlists->get();
if ($marketing->all_prospect_lists==1) {
	//remove all related prospect lists.
	if (!empty($prospectlists)) {
		$marketing->prospectlists->delete($marketing->id);
	}
} else {
	if (is_array($_REQUEST['message_for'])) {
		foreach ($_REQUEST['message_for'] as $prospect_list_id) {
			
			$key=array_search($prospect_list_id,$prospectlists);
			if ($key === null or $key === false) {
				$marketing->prospectlists->add($prospect_list_id);			
			} else {
				unset($prospectlists[$key]);
			}
		}
		if (count($prospectlists) != 0) {
			foreach ($prospectlists as $key=>$list_id) {
				$marketing->prospectlists->delete($marketing->id,$list_id);				
			}	
		}
	}
}
$header_URL = "Location: index.php?action=DetailView&module=Campaigns&record={$_REQUEST['campaign_id']}";
$GLOBALS['log']->debug("about to post header URL of: $header_URL");
header($header_URL);
?>
