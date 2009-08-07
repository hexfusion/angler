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
require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Tasks/Task.php');
require_once('themes/'.$theme.'/layout_utils.php');

require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $current_language, $current_user;
$current_module_strings = return_module_language($current_language, 'Tasks');

$today = gmdate("Y-m-d H:i:s");
// Break down the date, add a day, and print it back out again
list($date_tmp,$time_tmp) = explode(' ',$today);
$date = explode('-',$date_tmp);
$time = explode(':',$time_tmp);
$tomorrow = gmdate("Y-m-d H:i:s",gmmktime($time[0],$time[1],$time[2],$date[1],($date[2]+1),$date[0]));

$ListView = new ListView();
$seedTasks = new Task();
if ($seedTasks->db->dbType=='mysql') {
	$format=array("'%Y-%m-%d'");
	$where = "tasks.assigned_user_id='". $current_user->id ."' and (tasks.status is NULL or (tasks.status!='Completed' and tasks.status!='Deferred')) ";
	$where .= "and (tasks.date_start is NULL or ";
	$where .=  " CONCAT(".db_convert("tasks.date_start","date_format",$format).", CONCAT(' ',". db_convert("tasks.time_start","time_format",$format)." ))  <=". "'".$tomorrow."')";
} 
else if ($seedTasks->db->dbType=='mssql') 
{	
	$where = "tasks.assigned_user_id='". $current_user->id ."' and (tasks.status is NULL or (tasks.status!='Completed' and tasks.status!='Deferred')) ";
	$where .= "and (tasks.date_start is NULL or ";
	$where .=  db_convert("tasks.date_start","date_format") . ' + ' . db_convert("tasks.time_start","time_format") . " <=". "'".$tomorrow."')";
}

else if ($seedTasks->db->dbType=='oci8') 
{
	$format=array("'YYYY-MM-DD'");
	$where = "tasks.assigned_user_id='". $current_user->id ."' and (tasks.status is NULL or (tasks.status!='Completed' and tasks.status!='Deferred')) ";
	$where .= "and (tasks.date_start is NULL or ";
	$where .=  " CONCAT(".db_convert("tasks.date_start","date_format",$format).", CONCAT(' ',". db_convert("tasks.time_start","time_format",$format)." ))  <=". "'".$tomorrow."')";
}

$ListView->initNewXTemplate( 'modules/Tasks/MyTasks.html',$current_module_strings);
$header_text = '';

if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=MyTasks&from_module=Tasks'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
}
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_MY_TASKS'].$header_text);
$ListView->setQuery($where, "", "date_due,priority desc", "TASK");
$ListView->processListView($seedTasks, "main", "TASK");
?>
