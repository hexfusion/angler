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
if(is_admin($current_user)){
	require_once('ModuleInstall/ModuleInstaller.php');
	$mi = new ModuleInstaller();
	$mi->rebuild_all();

	//////////////////////////////////////////////////////////////////////////////
	// Remove the "Rebuild Extensions" red text message on admin logins
	
	echo "Updating the admin warning message...<BR>";
	
	// clear the database row if it exists (just to be sure)
	$query = "DELETE FROM versions WHERE name='Rebuild Extensions'";
	$log->info($query);
	$db->query($query);
	
	// insert a new database row to show the rebuild extensions is done
	$id = create_guid();
	$gmdate = gmdate('Y-m-d H:i:s');
	$date_entered = db_convert("'$gmdate'", 'datetime');
	$query = 'INSERT INTO versions (id, deleted, date_entered, date_modified, modified_user_id, created_by, name, file_version, db_version) '
		. "VALUES ('$id', '0', $date_entered, $date_entered, '1', '1', 'Rebuild Extensions', '4.0.0', '4.0.0')"; 
	$log->info($query);
	$db->query($query);
	
	// unset the session variable so it is not picked up in DisplayWarnings.php
	if(isset($_SESSION['rebuild_extensions'])) {
	    unset($_SESSION['rebuild_extensions']);
	}

	echo 'done';
}else{
	die('Admin Only Section');	
}
?>
