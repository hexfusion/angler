<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Save functionality for ProjectTask
 *
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
 */

// $Id: Save.php,v 1.18 2006/09/05 23:03:50 awu Exp $
require_once('modules/ProjectTask/ProjectTask.php');

$project = new ProjectTask();
if(!empty($_POST['record']))
{
	$project->retrieve($_POST['record']);
}
////
//// save the fields to the ProjectTask object
////

if(isset($_REQUEST['email_id'])) $project->email_id = $_REQUEST['email_id'];

if($_POST['order_number'] == '') $_POST['order_number'] = '1';

foreach($project->column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$project->$field = $_REQUEST[$field];
	}

	if(!isset($_REQUEST['milestone_flag']))
	{
		$project->milestone_flag = 'off';
	}
}

//$project->time_start = str_replace('.',':',$_REQUEST['time_start']);
//$project->time_due = str_replace('.',':',$_REQUEST['time_due']);
// Get GMT clean values

if(!empty($_REQUEST['date_start']) && !empty($_REQUEST['time_start'])){
	$time_start_meridiem = "";
	if(isset($_REQUEST['time_start_meridiem'])){
		$time_start_meridiem = $_REQUEST['time_start_meridiem'];
	}
	
	$project->date_start = $_REQUEST['date_start'];
	$project->time_start = $_REQUEST['time_start'].$time_start_meridiem;
}
if(!empty($_REQUEST['date_due']) && !empty($_REQUEST['time_due'])){
	$time_due_meridiem = "";
	if(isset($_REQUEST['time_due_meridiem'])){
		$time_due_meridiem = $_REQUEST['time_due_meridiem'];
	}

	$project->date_due = $_REQUEST['date_due'];
	$project->time_due =  $_REQUEST['time_due'].$time_due_meridiem;
}

// lets SugarBean handle date processing
$project->process_save_dates = true;

$GLOBALS['check_notify'] = false;
if (!empty($_POST['assigned_user_id']) && ($focus->assigned_user_id != $_POST['assigned_user_id']) && ($_POST['assigned_user_id'] != $current_user->id)) {
	$GLOBALS['check_notify'] = true;
}

	if(!$project->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
	}
$project->save($GLOBALS['check_notify']);
if(isset($_REQUEST['form']))
{
	// we are doing the save from a popup window
	echo '<script>opener.window.location.reload();self.close();</script>';
	die();
}
else
{
	// need to refresh the page properly

	$return_module = empty($_REQUEST['return_module']) ? 'ProjectTask'
		: $_REQUEST['return_module'];

	$return_action = empty($_REQUEST['return_action']) ? 'index'
		: $_REQUEST['return_action'];

	$return_id = empty($_REQUEST['return_id']) ? $project->id
		: $_REQUEST['return_id'];
header("Location: index.php?module=$return_module&action=$return_action&record=$return_id");

}
?>
