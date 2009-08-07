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
require_once('modules/Relationships/Relationship.php');
global $current_user,$beanFiles;
set_time_limit(3600);

include_once ('include/database/DBManagerFactory.php');
$db = & DBManagerFactory::getInstance();
if ($db->dbType == 'oci8') {
	echo "<BR>";
	echo "<p>".$mod_strings['ERR_NOT_FOR_ORACLE']."</p>";
	echo "<BR>";
	sugar_die('');	
}
if ($db->dbType == 'mssql') {
    echo "<BR>";
    echo "<p>".$mod_strings['ERR_NOT_FOR_MSSQL']."</p>";
    echo "<BR>";
    sugar_die('');  
}
if(is_admin($current_user) || isset($from_sync_client)){
	
	$execute = false;
	$export = false;
	
	
	if(isset($_REQUEST['do_action'])){
		switch($_REQUEST['do_action']){
			case 'display':
				break;
			case 'execute':
				$execute = true;
				break;
			case 'export':
				header('Location: index.php?module=Administration&action=repairDatabase&do_action=do_export&to_pdf=true');
				die();
			case 'do_export':
				$export = true;
				break;
		}

		if(!$export && empty($_REQUEST['repair_silent'])){
			echo get_module_title($mod_strings['LBL_REPAIR_DATABASE'], $mod_strings['LBL_REPAIR_DATABASE'].':'.$_REQUEST['do_action'], true);	
		}
		
		$sql = '';

		foreach ($beanFiles as $bean => $file) {	
			require_once($file);
			$focus = new $bean();
			$sql .=  $db->repairTable($focus, $execute);
		}

		$olddictionary = $dictionary;
		unset($dictionary);
		include('modules/TableDictionary.php');
		
		foreach($dictionary as $meta){
			$tablename = $meta['table'];
			$fielddefs = $meta['fields'];
			$indices = $meta['indices'];
			$sql.= $db->repairTableParams($tablename, $fielddefs, $indices, $execute);
		}
		
		$dictionary = $olddictionary;
	   
		if($export) {
	   		header("Content-Disposition: attachment; filename=repairSugarDB.sql");
			header("Content-Type: text/sql; charset={$app_strings['LBL_CHARSET']}");
			header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
			header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
			header( "Cache-Control: post-check=0, pre-check=0", false );
			header("Content-Length: ".strlen($sql));
	   		echo $sql;
	   		die();
		} else {
			if(empty($_REQUEST['repair_silent'])) {
				echo nl2br($sql);
			}
		}
	} // end do_action
	
	if(empty($_REQUEST['repair_silent']) && empty($_REQUEST['do_action'])) {
		echo "	<b>{$mod_strings['LBL_REPAIR_ACTION']}</b><br>
				<form name='repairdb'>
					<input type='hidden' name='action' value='repairDatabase'>
					<input type='hidden' name='module' value='Administration'>
					
					<select name='do_action'>
							<option value='display'>".$mod_strings['LBL_REPAIR_DISPLAYSQL']."
							<option value='export'>".$mod_strings['LBL_REPAIR_EXPORTSQL']."
							<option value='execute'>".$mod_strings['LBL_REPAIR_EXECUTESQL']."
					</select><input type='submit' class='button' value='".$mod_strings['LBL_GO']."'>
				</form><br><br>
				".$mod_strings['LBL_REPAIR_DATABASE_TEXT'];
	}	
}else{
	die('Admin Only Section');	
}


?>
