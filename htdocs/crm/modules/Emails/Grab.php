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
// $Id: Grab.php,v 1.12 2006/06/06 17:58:20 majed Exp $
require_once('modules/Emails/Email.php');
global $current_user;


$focus = new Email();
// Get Group User IDs
$groupUserQuery = 'SELECT name, group_id FROM inbound_email ie INNER JOIN users u ON (ie.group_id = u.id AND u.is_group = 1)';






_pp($groupUserQuery);
$r = $focus->db->query($groupUserQuery);
$groupIds = '';
while($a = $focus->db->fetchByAssoc($r)) {
	$groupIds .= "'".$a['group_id']."', ";
}
$groupIds = substr($groupIds, 0, (strlen($groupIds) - 2));

$query = 'SELECT emails.id AS id FROM emails';




$query .= " WHERE emails.deleted = 0 AND emails.status = 'unread' AND emails.assigned_user_id IN ($groupIds)";  





//$query .= ' LIMIT 1';

//_ppd($query);
$r2 = $focus->db->query($query); 
$count = 0;
$a2 = $focus->db->fetchByAssoc($r2);

$focus->retrieve($a2['id']);
$focus->assigned_user_id = $current_user->id;
$focus->save();

if(!empty($a2['id'])) {
	header('Location: index.php?module=Emails&action=ListView&type=inbound&assigned_user_id='.$current_user->id);
} else {
	header('Location: index.php?module=Emails&action=ListView&show_error=true&type=inbound&assigned_user_id='.$current_user->id);
}

?>
