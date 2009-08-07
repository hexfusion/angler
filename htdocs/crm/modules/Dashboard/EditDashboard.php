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
 * $Id: EditDashboard.php,v 1.5 2006/06/06 17:57:57 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Dashboard/Dashboard.php');
global $mod_strings;



$focus = new Dashboard();

if(!isset($_REQUEST['chart_index']))
	sugar_die('no index is requested to move');

if(!isset($_REQUEST['record']))
	sugar_die('no index is requested to move');

global $current_user;

$focus->retrieve($_REQUEST['record']);

if ( empty($focus->id) || $focus->id == -1)
{
	sugar_die("there is no dashboard associated to this id:".$_REQUEST['record']);
}

if ( $current_user->id != $focus->assigned_user_id)
{
	sugar_die("why are you trying to edit someone else's dashboard?");
}

if ( $_REQUEST['dashboard_action'] == 'move_up')
{
	$focus->move('up',$_REQUEST['chart_index']);
} else if ($_REQUEST['dashboard_action'] == 'move_down')
{
  $focus->move('down',$_REQUEST['chart_index']);
} else if ($_REQUEST['dashboard_action'] == 'delete')
{   
	$focus->delete($_REQUEST['chart_index']);
} else if ($_REQUEST['dashboard_action'] == 'add')
{   
	$focus->add($_REQUEST['chart_type'],$_REQUEST['chart_id'],$_REQUEST['chart_index']);
}
else if ($_REQUEST['dashboard_action'] == 'arrange')
{   
	$focus->arrange(split('-',$_REQUEST['chartorder']));
}
header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);

exit;
?>
