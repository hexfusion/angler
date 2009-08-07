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

 
require_once('log4php/LoggerManager.php');
require_once('config.php');
require_once('include/modules.php');
require_once('modules/Users/User.php');
require_once('modules/Teams/Team.php');
require_once('modules/Administration/Administration.php');

set_time_limit(3600);

global $current_user;
$GLOBALS['log'] = LoggerManager :: getLogger('SugarCRM');


$admin_id=$current_user->id;
 
$user = new User();

$query="delete from team_memberships where explicit_assign=0";
$user->db->query($query); 

$query="update team_memberships set implicit_assign=0";
$user->db->query($query); 


$query="delete from team_memberships where implicit_assign=0 and explicit_assign=0";
$user->db->query($query); 


//delete all records for membership into global team.
$query="delete from team_memberships where team_id='1'" ;
$user->db->query($query); 

//delete all memebership records for a users personal private team. 1 per user.
$query="delete from team_memberships where team_id like 'private.%' and explicit_assign=1" ;
$user->db->query($query); 

$query="delete from teams where private=1";
$user->db->query($query); 

$team = new Team();

$query="select id, reports_to_id from users where deleted=0 ";
$result=$user->db->query($query);
$reporting=array();
while (($row=$user->db->fetchByAssoc($result)) != null) 
{
	$reporting[$row['id']]=$row['reports_to_id'];	
}	

foreach ($reporting as $user_id=>$reports_to_id) {
	echo "<BR> Processing user=" . $user_id . ' reports to=' . $reports_to_id;
	$user = new User();
	$user->retrieve($user_id);
	if (empty($user->id)) {
		echo "<BR> Skipping user=" . $user_id . " reports to=" . $reports_to_id;
	} else {	
		$team->new_user_created($user);
	}
}
echo "<BR><BR>done..."
?>
