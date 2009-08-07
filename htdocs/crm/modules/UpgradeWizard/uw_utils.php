<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * UpgradeWizardCommon
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

// $Id: uw_utils.php,v 1.66 2006/08/22 20:09:48 chris Exp $


/**
 * gets valid patch file names that exist in upload/upgrade/patch/
 */
function getValidPatchName($returnFull = true) {
	global $base_upgrade_dir;
	global $mod_strings;
	global $uh;
	
	$return = array();
	
	// scan for new files (that are not installed)
	logThis('finding new files for upgrade');
	$upgrade_content = '';
	$upgrade_contents = findAllFiles($base_upgrade_dir, array(), false);
	$upgrades_available = 0;
	$ready = "<ul>\n";
	$ready .= "
		<table>
			<tr>
				<th></th>
				<th align=left>
					{$mod_strings['LBL_ML_NAME']}
				</th>
				<th>
					{$mod_strings['LBL_ML_TYPE']}
				</th>
				<th>
					{$mod_strings['LBL_ML_VERSION']}
				</th>
				<th>
					{$mod_strings['LBL_ML_PUBLISHED']}
				</th>
				<th>
					{$mod_strings['LBL_ML_UNINSTALLABLE']}
				</th>
				<th>
					{$mod_strings['LBL_ML_DESCRIPTION']}
				</th>
			</tr>";
	$disabled = '';
	foreach($upgrade_contents as $upgrade_content) {
		if(!preg_match("#.*\.zip\$#", $upgrade_content)) {
			continue;
		}
		
		$upgrade_content = clean_path($upgrade_content);
		$the_base = basename($upgrade_content);
		$the_md5 = md5_file($upgrade_content);
		$md5_matches = $uh->findByMd5($the_md5);
	
		if(0 == sizeof($md5_matches)) {
			$target_manifest = remove_file_extension( $upgrade_content ) . '-manifest.php';
			require_once($target_manifest);
			$name = empty($manifest['name']) ? $upgrade_content : $manifest['name'];
			$version = empty($manifest['version']) ? '' : $manifest['version'];
			$published_date = empty($manifest['published_date']) ? '' : $manifest['published_date'];
			$icon = '';
			$description = empty($manifest['description']) ? 'None' : $manifest['description'];
			$uninstallable = empty($manifest['is_uninstallable']) ? 'No' : 'Yes';
			$type = getUITextForType( $manifest['type'] );
			$manifest_type = $manifest['type'];
	
			if($manifest_type != 'patch') {
				continue;
			}
	
			if(empty($manifest['icon'])) {
				$icon = getImageForType( $manifest['type'] );
			} else {
				$path_parts = pathinfo( $manifest['icon'] );
				$icon = "<img src=\"" . remove_file_extension( $upgrade_content ) . "-icon." . $path_parts['extension'] . "\">";
			}
	
			$upgrades_available++;
			if($upgrades_available > 1) {
				logThis('ERROR: found more than 1 qualified upgrade file! Stopping upgrade.');
				$stop = true; // more than 1 upgrade?!?
			} else {
				logThis('found a valid upgrade file: '.$upgrade_content);
				$_SESSION['install_file'] = $upgrade_content; // in-case it was there from a prior.
				$stop = false;
			}
			$ready .= "<tr><td>$icon</td><td>$name</td><td>$type</td><td>$version</td><td>$published_date</td><td>$uninstallable</td><td>$description</td>\n";
			$cleanUpgradeContent = urlencode($upgrade_content);
			$ready .=<<<eoq
	            <td>
					<form action="index.php" method="post">
						<input type="hidden" name="module" value="UpgradeWizard">
						<input type="hidden" name="action" value="index">
						<input type="hidden" name="step" value="{$_REQUEST['step']}">
						<input type="hidden" name="run" value="delete">
	            		<input type=hidden name="install_file" value="{$cleanUpgradeContent}" />
	            		<input type=submit value="{$mod_strings['LBL_BUTTON_DELETE']}" />
					</form>
				</td>
eoq;
			$disabled = "DISABLED";
	    }
	}
	$ready .= "</table>\n";
	
	if( $upgrades_available == 0 ){
	    $ready .= "<i>None</i><br>\n";
	}
	$ready .= "</ul>\n";
	
	$return['ready'] = $ready;
	$return['disabled'] = $disabled;
	
	if($returnFull) {
		return $return;
	}
}


/**
 * finalizes upgrade by setting upgrade versions in DB (config table) and sugar_version.php
 * @return bool true on success
 */
function updateVersions($version) {
	global $db;
	global $sugar_config;
	
	logThis('At updateVersions()... updating config table and sugar_version.php.');
	
	// handle file copy
	if(isset($_SESSION['sugar_version_file']) && !empty($_SESSION['sugar_version_file'])) {
		if(!copy($_SESSION['sugar_version_file'], clean_path(getcwd().'/sugar_version.php'))) {
			logThis('*** ERROR: sugar_version.php could not be copied to destination! Cannot complete upgrade');
			return false;
		} else {
			logThis('sugar_version.php successfully updated!');
		}
	} else {
		logThis('*** ERROR: no sugar_version.php file location found! - cannot complete upgrade...');
		return false;	
	}
	
	// handle config table
	if($db->dbType == 'mysql') {
		$q1 = "DELETE FROM `config` WHERE `category` = 'info' AND `name` = 'sugar_version'";
		$q2 = "INSERT INTO `config` (`category`, `name`, `value`) VALUES ('info', 'sugar_version', '{$version}')";
	} elseif($db->dbType == 'oci8' || $db->dbType == 'oracle') {




	} elseif($db->dbType == 'mssql') {
		$q1 = "DELETE FROM 'config' WHERE 'category' = 'info' AND 'name' = 'sugar_version'";
		$q2 = "INSERT INTO 'config' ('category', 'name', 'value') VALUES ('info', 'sugar_version', '{$version}')";
	}
	
	logThis('Deleting old DB version info from config table.');
	$db->query($q1);
	
	logThis('Inserting updated version info into config table.');
	$db->query($q2);

	logThis('updateVersions() complete.');
	return true;
}



/**
 * gets a module's lang pack - does not need to be a SugarModule
 * @param lang string Language
 * @param module string Path to language folder
 * @return array mod_strings
 */
function getModuleLanguagePack($lang, $module) {
	$mod_strings = array();
	
	if(!empty($lang) && !empty($module)) {
		$langPack = clean_path(getcwd().'/'.$module.'/language/'.$lang.'.lang.php');
		$langPackEn = clean_path(getcwd().'/'.$module.'/language/en_us.lang.php');
		
		if(file_exists($langPack))
			include_once($langPack);
		elseif(file_exists($langPackEn))
			include_once($langPackEn);
	}
	
	return $mod_strings;
}
/** 
 * checks system compliance for 4.5+ codebase
 * @return array Mixed values
 */
function checkSystemCompliance() {
	global $sugar_config;
	global $current_language;
	global $db;
	global $mod_strings;

	if(!defined('SUGARCRM_MIN_MEM')) {
		define('SUGARCRM_MIN_MEM', 32);
	}
	
	$installer_mod_strings = getModuleLanguagePack($current_language, './install');
	$ret = array();
	$ret['error_found'] = false;
	
	// PHP version
	$php_version = constant('PHP_VERSION');
	$check_php_version_result = check_php_version($php_version);
	
	switch($check_php_version_result) {
		case -1:
			$ret['phpVersion'] = "<b><span class=stop>{$installer_mod_strings['ERR_CHECKSYS_PHP_INVALID_VER']} {$php_version} )</span></b>";
			$ret['error_found'] = true;
			break;
		case 0:
			$ret['phpVersion'] = "<b><span class=go>{$installer_mod_strings['ERR_CHECKSYS_PHP_UNSUPPORTED']} {$php_version} )</span></b>";
			break;
		case 1:
			$ret['phpVersion'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_PHP_OK']} {$php_version} )</span></b>";
			break;
	}
	
	// database and connect
	switch($sugar_config['dbconfig']['db_type']){
	    case 'mysql':
	        // mysql version
	        $q = "SELECT version();";
	        $r = $db->query($q);
	        $a = $db->fetchByAssoc($r);
	        if(version_compare($a['version()'], '4.1.2') < 0) {
	        	$ret['error_found'] = true;
	        	$ret['mysqlVersion'] = "<b><span class=stop>".$mod_strings['ERR_UW_MYSQL_VERSION'].$a['version()']."</span></b>";
	        }
	        
	        break;
		case 'mssql': 
			// Magic Quotes & SQL Server
			$ret['mssqlStatus'] = '';
			if(ini_get('magic_quotes_gpc') == 1) {
				// cn: this setting will break the connection string to SQL Server Express 2005
				$ret['mssqlMagicQuotes'] = "<b><span class=stop>{$installer_mod_strings['ERR_CHECKSYS_MSSQL_MQGPC']}</span></b>";
				$ret['error_found'] = true;
			}
	        break;
	    case 'oci8':


	        break;
	}
	
	

	
	// XML Parsing
	if(function_exists('xml_parser_create')) {
		$ret['xmlStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	} else {
		$ret['xmlStatus'] = "<b><span class=stop>{$installer_mod_strings['LBL_CHECKSYS_NOT_AVAILABLE']}</span></b>";
		$ret['error_found'] = true;
	}
	
	// cURL
	if(function_exists('curl_init')) {
		$ret['curlStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</font></b>";
	} else {
		$ret['curlStatus'] = "<b><span class=go>{$installer_mod_strings['ERR_CHECKSYS_CURL']}</font></b>";
		$ret['error_found'] = false;
	}
	
	// mbstrings
	if(function_exists('mb_strlen')) {
		$ret['mbstringStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</font></b>";
	} else {
		$ret['mbstringStatus'] = "<b><span class=stop>{$installer_mod_strings['ERR_CHECKSYS_MBSTRING']}</font></b>";
		$ret['error_found'] = true;
	}
			
	// imap
	if(function_exists('imap_open')) {
		$ret['imapStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	} else {
		$ret['imapStatus'] = "<b><span class=go>{$installer_mod_strings['ERR_CHECKSYS_IMAP']}</span></b>";
		$ret['error_found'] = false;
	}
	
	
	// safe mode
	if('1' == ini_get('safe_mode')) {
		$ret['safeModeStatus'] = "<b><span class=stop>{$installer_mod_strings['ERR_CHECKSYS_SAFE_MODE']}</span></b>";
		$ret['error_found'] = true;
	} else {
		$ret['safeModeStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	}
	
	
	// call time pass by ref
	if('0' == ini_get('allow_call_time_pass_reference')) {
		$ret['callTimeStatus'] = "<b><span class=stop>{$installer_mod_strings['ERR_CHECKSYS_CALL_TIME']}</span></b>";
		$ret['error_found'] = true;
	} else {
		$ret['callTimeStatus'] = "<b><span class=go>{$installer_mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	}
	
	// memory limit
	$ret['memory_msg']     = "";
	$memory_limit   = "-1";//ini_get('memory_limit');
	$sugarMinMem = constant('SUGARCRM_MIN_MEM');
	// logic based on: http://us2.php.net/manual/en/ini.core.php#ini.memory-limit
	if( $memory_limit == "" ){          // memory_limit disabled at compile time, no memory limit
	    $ret['memory_msg'] = "<b><span class=\"go\">{$installer_mod_strings['LBL_CHECKSYS_MEM_OK']}</span></b>";
	} elseif( $memory_limit == "-1" ){   // memory_limit enabled, but set to unlimited
	    $ret['memory_msg'] = "<b><span class=\"go\">{$installer_mod_strings['LBL_CHECKSYS_MEM_UNLIMITED']}</span></b>";
	} else {
	    rtrim($memory_limit, 'M');
	    $memory_limit_int = (int) $memory_limit;
	    if( $memory_limit_int < constant('SUGARCRM_MIN_MEM') ){
	        $ret['memory_msg'] = "<b><span class=\"stop\">{$installer_mod_strings['ERR_CHECKSYS_MEM_LIMIT_1']}" . constant('SUGARCRM_MIN_MEM') . "{$installer_mod_strings['ERR_CHECKSYS_MEM_LIMIT_2']}</span></b>";
			$ret['error_found'] = true;
	    } else {
			$ret['memory_msg'] = "<b><span class=\"go\">{$installer_mod_strings['LBL_CHECKSYS_OK']} ({$memory_limit})</span></b>";
	    }
	}
	
	return $ret;
}


/**
 * is a file that we blow away automagically
 */
function isAutoOverwriteFile($file) {
	$overwriteDirs = array(
		'./sugar_version.php',
		'./modules/UpgradeWizard/uw_main.tpl',
	);
	$file = trim('.'.str_replace(clean_path(getcwd()), '', $file));
	
	if(in_array($file, $overwriteDirs)) {
		return true;
	}
	
	$fileExtension = substr(strrchr($file, "."), 1);
	if($fileExtension == 'tpl' || $fileExtension == 'html') {
		return false;
	}
	
	return true;
}

/**
 * flatfile logger
 */
function logThis($entry) {
	global $mod_strings;
	
	$log = clean_path(getcwd().'/upgradeWizard.log');
	// create if not exists
	if(!file_exists($log)) {
		$fp = @fopen($log, 'w+'); // attempts to create file
		if(!is_resource($fp)) {
			$GLOBALS['log']->fatal('UpgradeWizard could not create the upgradeWizard.log file');
			die($mod_strings['ERR_UW_LOG_FILE_UNWRITABLE']);
		}
	} else {
		$fp = @fopen($log, 'a+'); // write pointer at end of file
		if(!is_resource($fp)) {
			$GLOBALS['log']->fatal('UpgradeWizard could not open/lock upgradeWizard.log file');
			die($mod_strings['ERR_UW_LOG_FILE_UNWRITABLE']);
		}
	}
	
	$line = date('r').' [UpgradeWizard] - '.$entry."\n";
	
	if(@fwrite($fp, $line) === false) {
		$GLOBALS['log']->fatal('UpgradeWizard could not write to upgradeWizard.log: '.$entry);
		die($mod_strings['ERR_UW_LOG_FILE_UNWRITABLE']);
	}
	
	if(is_resource($fp)) {
		fclose($fp);
	}
}


/**
 * tries to validate the query based on type
 * @param string query The query to verify
 * @param string dbType The DB type
 * @return string error Non-empty string on error
 */
function verifySqlStatement($query, $dbType, &$newTables) {
	$error = '';
	logThis('verifying SQL statement');
	
	$table	= getTableFromQuery($query);
	
	switch(strtoupper(substr($query, 0, 10))) {
		// ignore DROPs
		case 'ALTER TABL':
			// get ddl
			$error = testQueryAlter($table, $dbType, $query, $newTables);
		break;
		
		case 'CREATE TAB':
			$error = testQueryCreate($table, $dbType, $query, $newTables);
		break;
		
		case 'DELETE FRO':
			$error = testQueryDelete($table, $dbType, $query);
		break;

		case 'DROP TABLE':
			$error = testQueryDrop($table, $dbType, $query);
		break;
		
		case 'INSERT INT':
			$error = testQueryInsert($table, $dbType, $query);
		break;
		
		case (strtoupper(substr($query, 0, 6)) == 'UPDATE'):
			$error = testQueryUpdate($table, $dbType, $query);
		break;
		





	}

	return $error;
}



function cleanQuery($query, $oci8=false) {
	$bad = array(
			"&#039;",
			"&quot;",
			);
	$good = array(
			'"',
			"",
			);
			
	$q = str_replace($bad, $good, $query);
	







	return $q;
}

/**
 * test perms for CREATE queries
 */
function testPermsCreate($type, $out) {
	logThis('Checking CREATE TABLE permissions...');
	global $db;
	global $mod_strings;

	switch($type) {
		case 'mysql':
			$db->query('CREATE TABLE temp (id varchar(36))');
			if($db->checkError()) {
				logThis('cannot CREATE TABLE!');
				$out['db']['dbNoCreate'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_CREATE']}</span></td></tr>";
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':



















		break;	
	}
	
	return $out;
}

/**
 * test perms for INSERT
 */
function testPermsInsert($type, $out, $skip=false) {
	logThis('Checking INSERT INTO permissions...');
	global $db;
	global $mod_strings;

	switch($type) {
		case 'mysql':
			if(!$skip) {
				$db->query("INSERT INTO temp (id) VALUES ('abcdef0123456789abcdef0123456789abcd')");
				if($db->checkError()) {
					logThis('cannot INSERT INTO!');
					$out['db']['dbNoInsert'] = true;
					$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_INSERT']}</span></td></tr>";
				}
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':




















		break;	
	}
	
	return $out;
}


/**
 * test perms for UPDATE TABLE
 */
function testPermsUpdate($type, $out, $skip=false) {
	logThis('Checking UPDATE TABLE permissions...');
	global $db;
	global $mod_strings;

	switch($type) {
		case 'mysql':
			if(!$skip) {
				$db->query("UPDATE temp SET id = '000000000000000000000000000000000000' WHERE id = 'abcdef0123456789abcdef0123456789abcd'");
				if($db->checkError()) {
					logThis('cannot UPDATE TABLE!');
					$out['db']['dbNoUpdate'] = true;
					$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_UPDATE']}</span></td></tr>";
				}
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':



















		break;	
	}
	
	return $out;
}


/**
 * test perms for SELECT
 */
function testPermsSelect($type, $out, $skip=false) {
	logThis('Checking SELECT permissions...');
	global $db;
	global $mod_strings;

	switch($type) {
		case 'mysql':
			$r = $db->query('SELECT id FROM temp');
			if($db->checkError()) {
				logThis('cannot SELECT!');
				$out['db']['dbNoSelect'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_SELECT']}</span></td></tr>";
			}
			logThis('Checking validity of SELECT results');
			while($a = $db->fetchByAssoc($r)) {
				if($a['id'] != '000000000000000000000000000000000000') {
					logThis('results DO NOT MATCH! got: '.$a['id']);
					$out['db'][] = 'selectFailed';
					$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_INSERT_FAILED']}</span></td></tr>";
				}
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':



















		break;	
	}
	
	return $out;
}



/**
 * test perms for DELETE
 */
function testPermsDelete($type, $out, $skip=false) {
	logThis('Checking DELETE FROM permissions...');
	global $db;
	global $mod_strings;

	switch($type) {
		case 'mysql':
			$db->query("DELETE FROM temp WHERE id = '000000000000000000000000000000000000'");
			if($db->checkError()) {
				logThis('cannot DELETE FROM!');
				$out['db']['dbNoDelete'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_DELETE']}</span></td></tr>";
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':



















		break;	
	}
	
	return $out;
}


/**
 * test perms for ALTER TABLE ADD COLUMN
 */
function testPermsAlterTableAdd($type, $out, $skip=false) {
	logThis('Checking ALTER TABLE ADD COLUMN permissions...');
	global $db;
	global $mod_strings;

	switch($type) {
		case 'mysql':
			$db->query('ALTER TABLE temp ADD COLUMN test varchar(100)');
			if($db->checkError()) {
				logThis('cannot ADD COLUMN!');
				$out['db']['dbNoAddColumn'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_ADD_COLUMN']}</span></td></tr>";
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':




















		break;	
	}
	
	return $out;
}




/**
 * test perms for ALTER TABLE ADD COLUMN
 */
function testPermsAlterTableChange($type, $out, $skip=false) {
	logThis('Checking ALTER TABLE CHANGE COLUMN permissions...');
	global $db;
	global $mod_strings;

	switch($type) {
		case 'mysql':
			$db->query('ALTER TABLE temp CHANGE COLUMN test test varchar(100)');
			if($db->checkError()) {
				logThis('cannot CHANGE COLUMN!');
				$out['db']['dbNoChangeColumn'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_CHANGE_COLUMN']}</span></td></tr>";
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':



















		break;	
	}
	
	return $out;
}



/**
 * test perms for ALTER TABLE DROP COLUMN
 */
function testPermsAlterTableDrop($type, $out, $skip=false) {
	logThis('Checking ALTER TABLE DROP COLUMN permissions...');
	global $db;
	global $mod_strings;

	switch($type) {
		case 'mysql':
			$db->query('ALTER TABLE temp DROP COLUMN test');
			if($db->checkError()) {
				logThis('cannot DROP COLUMN!');
				$out['db']['dbNoDropColumn'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_DROP_COLUMN']}</span></td></tr>";
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':



















		break;	
	}
	
	return $out;
}


/**
 * test perms for DROP TABLE
 */
function testPermsDropTable($type, $out, $skip=false) {
	logThis('Checking DROP TABLE permissions...');
	global $db;
	global $mod_strings;

	switch($type) {
		case 'mysql':
			$db->query('DROP TABLE temp');
			if($db->checkError()) {
				logThis('cannot DROP TABLE!');
				$out['db']['dbNoDropTable'] = true;
				$out['dbOut'] .= "<tr><td align='left'><span class='error'>{$mod_strings['LBL_UW_DB_NO_DROP_TABLE']}</span></td></tr>";
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':




















		break;	
	}
	
	return $out;
}



































































































































function getOci8Version() {
	global $db;
	
	$q = 'SELECT BANNER AS B FROM V$VERSION WHERE BANNER LIKE \'%Express%\'';
	$r = $db->query($q);
	
	while($a = $db->fetchByAssoc($r)) {
		return 'express';
	}
	return '';
}







/**
 * Tests an ALTER TABLE query
 * @param string table The table name to get DDL
 * @param string dbType MySQL, MSSQL, etc.
 * @param string query The query to test.
 * @return string Non-empty if error found
 */
function testQueryAlter($table, $dbType, $query, $newTables) {
	logThis('verifying ALTER statement...');
	global $db;
	global $sugar_config;
	
	if(empty($db)) {
		$db = DBManager::getInstance();
	}
	
	mysql_error(); // initialize errors
	$error = '';
	
	if(!in_array($table, $newTables)) {
		switch($dbType) {
			case 'mysql':
				// get DDL
				logThis('creating temp table for ['.$table.']...');
				$q = "SHOW CREATE TABLE {$table}";
				$r = $db->query($q);
				$a = $db->fetchByAssoc($r);
				
				// rewrite DDL with _temp name
				$cleanQuery = cleanQuery($a['Create Table']);
				$tempTableQuery = str_replace("CREATE TABLE `{$table}`", "CREATE TABLE `{$table}__uw_temp`", $cleanQuery);
				$r2 = $db->query($tempTableQuery);
	
				// get sample data into the temp table to test for data/constraint conflicts
				logThis('inserting temp dataset...');
				$q3 = "INSERT INTO `{$table}__uw_temp` SELECT * FROM `{$table}` LIMIT 10";
				$r3 = $db->query($q3, false, "Preflight Failed for: {$query}");
	
				// test the query on the test table
				logThis('testing query: ['.$query.']');
				$tempTableTestQuery = str_replace("ALTER TABLE `{$table}`", "ALTER TABLE `{$table}__uw_temp`", $query);
				if (strpos($tempTableTestQuery, 'idx') === false) {
					if(isRunningAgainstTrueTable($tempTableTestQuery)) {
						$error = getFormattedError('Could not use a temp table to test query!', $query);
						return $error;
					}

					logThis('testing query on temp table: ['.$tempTableTestQuery.']');
					$r4 = $db->query($tempTableTestQuery, false, "Preflight Failed for: {$query}");					
				}
				else {	
					// test insertion of an index on a table			
					$tempTableTestQuery_idx = str_replace("ADD INDEX `idx_", "ADD INDEX `temp_idx_", $tempTableTestQuery);
					logThis('testing query on temp table: ['.$tempTableTestQuery_idx.']');
					$r4 = $db->query($tempTableTestQuery_idx, false, "Preflight Failed for: {$query}");
				}				
				$mysqlError = mysql_error(); // empty on no-errors
				if(!empty($mysqlError)) {
					logThis('*** ERROR: query failed: '.$mysqlError);
					$error = getFormattedError($mysqlError, $query);
				}
	
				// clean up moved to end of preflight
			break;
			
			case 'mssql':
			break;
			
			case 'oci8':









































			break;
		} // end switch()
	} else {
		logThis($table . ' is a new table'); 
	}

	logThis('verification done.');
	return $error;
}

/**
 * Tests an CREATE TABLE query
 * @param string table The table name to get DDL
 * @param string dbType MySQL, MSSQL, etc.
 * @param string query The query to test.
 * @return string Non-empty if error found
 */
function testQueryCreate($table, $dbType, $query, &$newTables) {
	logThis('verifying CREATE statement...');
	global $db;
	if(empty($db)) {
		$db = DBManager::getInstance();
	}
	
	$error = '';
	switch($dbType) {
		case 'mysql':
			// rewrite DDL with _temp name
			logThis('testing query: ['.$query.']');
			$tempTableQuery = str_replace("CREATE TABLE `{$table}`", "CREATE TABLE `{$table}__uw_temp`", $query);

			if(isRunningAgainstTrueTable($tempTableQuery)) {
				$error = getFormattedError('Could not use a temp table to test query!', $query);
				return $error;
			}

			$r4 = $db->query($tempTableQuery, false, "Preflight Failed for: {$query}");
			
			$error = mysql_error(); // empty on no-errors
			if(!empty($error)) {
				logThis('*** ERROR: query failed.');
				$error = getFormattedError($error, $query);
			}

			// check if table exists
			logThis('testing for table: '.$table);
			$q1 = "DESC `{$table}`";
			$r1 = $db->query($q1);
			
			$mysqlError = mysql_error();
			if(empty($mysqlError)) {
				logThis('*** ERROR: table already exists!: '.$table);
				$error = getFormattedError('table exists', $query);
			}
			else {
				logThis('NEW TABLE: '.$query);
				$newTables[] = $table;
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':






































		break;
	}
	return $error;
}

/**
 * Tests an DELETE FROM query
 * @param string table The table name to get DDL
 * @param string dbType MySQL, MSSQL, etc.
 * @param string query The query to test.
 * @return string Non-empty if error found
 */
function testQueryDelete($table, $dbType, $query) {
	logThis('verifying DELETE statements');
	global $db;
	if(empty($db)) {
		$db = DBManager::getInstance();
	}
	
	$error = '';
	
	switch($dbType) {
		case 'mysql':
			// get DDL
			logThis('creating temp table...');
			$q = "SHOW CREATE TABLE {$table}";
			$r = $db->query($q);
			$a = $db->fetchByAssoc($r);
			
			// rewrite DDL with _temp name
			$cleanQuery = cleanQuery($a['Create Table']);
			$tempTableQuery = str_replace("CREATE TABLE `{$table}`", "CREATE TABLE `{$table}__uw_temp`", $cleanQuery);
			$r2 = $db->query($tempTableQuery);
			
			// get sample data into the temp table to test for data/constraint conflicts
			logThis('inserting temp dataset...');
			$q3 = "INSERT INTO `{$table}__uw_temp` SELECT * FROM `{$table}` LIMIT 10";
			$r3 = $db->query($q3);
			
			// test the query on the test table
			logThis('testing query: ['.$query.']');
			$tempTableTestQuery = str_replace("DELETE FROM `{$table}`", "DELETE FROM `{$table}__uw_temp`", $query);

			if(isRunningAgainstTrueTable($tempTableTestQuery)) {
				$error = getFormattedError('Could not use a temp table to test query!', $tempTableTestQuery);
				return $error;
			}

			$r4 = $db->query($tempTableTestQuery, false, "Preflight Failed for: {$query}");
			$error = mysql_error(); // empty on no-errors
			if(!empty($error)) {
				logThis('*** ERROR: query failed.');
				$error = getFormattedError($error, $query);
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':























		break;
	}
	logThis('verification done.');
	return $error;
}

/**
 * Tests a DROP TABLE query
 * 
 */
function testQueryDrop($table, $dbType, $query) {
	logThis('verifying DROP TABLE statement');
	global $db;
	if(empty($db)) {
		$db = DBManager::getInstance();
	}
	
	$error = '';
	
	switch($dbType) {
		case 'mysql':
			// get DDL
			logThis('creating temp table...');
			$q = "SHOW CREATE TABLE {$table}";
			$r = $db->query($q);
			$a = $db->fetchByAssoc($r);
			
			// rewrite DDL with _temp name
			$cleanQuery = cleanQuery($a['Create Table']);
			$tempTableQuery = str_replace("CREATE TABLE `{$table}`", "CREATE TABLE `{$table}__uw_temp`", $cleanQuery);
			$r2 = $db->query($tempTableQuery);
			
			// get sample data into the temp table to test for data/constraint conflicts
			logThis('inserting temp dataset...');
			$query = stripQuotes($query, $table);
			$q3 = "INSERT INTO `{$table}__uw_temp` SELECT * FROM `{$table}` LIMIT 10";
			$r3 = $db->query($q3);
			
			// test the query on the test table
			logThis('testing query: ['.$query.']');
			$tempTableTestQuery = str_replace("DROP TABLE `{$table}`", "DROP TABLE `{$table}__uw_temp`", $query);

			// make sure the test query is running against a temp table
			if(isRunningAgainstTrueTable($tempTableTestQuery)) {
				$error = getFormattedError('Could not use a temp table to test query!', $tempTableTestQuery);
				return $error;
			}

			$r4 = $db->query($tempTableTestQuery, false, "Preflight Failed for: {$query}");
			$error = mysql_error(); // empty on no-errors
			if(!empty($error)) {
				logThis('*** ERROR: query failed.');
				$error = getFormattedError($error, $query);
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':





















		break;
	}
	logThis('verification done.');
	return $error;
}

/**
 * Tests an INSERT INTO query
 * @param string table The table name to get DDL
 * @param string dbType MySQL, MSSQL, etc.
 * @param string query The query to test.
 * @return string Non-empty if error found
 */
function testQueryInsert($table, $dbType, $query) {
	logThis('verifying INSERT statement...');
	global $db;
	if(empty($db)) {
		$db = DBManager::getInstance();
	}
	
	$error = '';
	
	switch($dbType) {
		case 'mysql':
			// get DDL
			$q = "SHOW CREATE TABLE {$table}";
			$r = $db->query($q);
			$a = $db->fetchByAssoc($r);
			
			// rewrite DDL with _temp name
			$cleanQuery = cleanQuery($a['Create Table']);
			$tempTableQuery = str_replace("CREATE TABLE `{$table}`", "CREATE TABLE `{$table}__uw_temp`", $cleanQuery);
			$r2 = $db->query($tempTableQuery);
			
			// test the query on the test table
			logThis('testing query: ['.$query.']');
			$tempTableTestQuery = str_replace("INSERT INTO `{$table}`", "INSERT INTO `{$table}__uw_temp`", $query);

			// make sure the test query is running against a temp table
			if(isRunningAgainstTrueTable($tempTableTestQuery)) {
				$error = getFormattedError('Could not use a temp table to test query!', $tempTableTestQuery);
				return $error;
			}

			$r4 = $db->query($tempTableTestQuery, false, "Preflight Failed for: {$query}");
			$error = mysql_error(); // empty on no-errors
			if(!empty($error)) {
				logThis('*** ERROR: query failed.');
				$error = getFormattedError($error, $query);
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':





















		break;
	}
	logThis('verification done.');
	return $error;
}


/**
 * Tests an UPDATE TABLE query
 * @param string table The table name to get DDL
 * @param string dbType MySQL, MSSQL, etc.
 * @param string query The query to test.
 * @return string Non-empty if error found
 */
function testQueryUpdate($table, $dbType, $query) {
	logThis('verifying UPDATE TABLE statement...');
	global $db;
	if(empty($db)) {
		$db = DBManager::getInstance();
	}
	
	$error = '';
	
	switch($dbType) {
		case 'mysql':
			// get DDL
			$q = "SHOW CREATE TABLE {$table}";
			$r = $db->query($q);
			$a = $db->fetchByAssoc($r);
			
			// rewrite DDL with _temp name
			$cleanQuery = cleanQuery($a['Create Table']);
			$tempTableQuery = str_replace("CREATE TABLE `{$table}`", "CREATE TABLE `{$table}__uw_temp`", $cleanQuery);
			$r2 = $db->query($tempTableQuery);

			// get sample data into the temp table to test for data/constraint conflicts
			logThis('inserting temp dataset...');
			$q3 = "INSERT INTO `{$table}__uw_temp` SELECT * FROM `{$table}` LIMIT 10";
			$r3 = $db->query($q3, false, "Preflight Failed for: {$query}");
			
			// test the query on the test table
			logThis('testing query: ['.$query.']');
			$tempTableTestQuery = str_replace("UPDATE `{$table}`", "UPDATE `{$table}__uw_temp`", $query);

			// make sure the test query is running against a temp table
			if(isRunningAgainstTrueTable($tempTableTestQuery)) {
				$error = getFormattedError('Could not use a temp table to test query!', $tempTableTestQuery);
				return $error;
			}

			$r4 = $db->query($tempTableTestQuery, false, "Preflight Failed for: {$query}");
			$error = mysql_error(); // empty on no-errors
			if(!empty($error)) {
				logThis('*** ERROR: query failed.');
				$error = getFormattedError($error, $query);
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':























		break;
	}
	logThis('verification done.');
	return $error;
}


/**
 * strip queries of single and double quotes
 */
function stripQuotes($query, $table) {
	$queryStrip = '';
	
	$start = strpos($query, $table);
	
	if(substr($query, ($start - 1), 1) != ' ') {
		$queryStrip  = substr($query, 0, ($start-2));
		$queryStrip .= " {$table} ";
		$queryStrip .= substr($query, ($start + strlen($table) + 2), strlen($query));
	}
	
	return (empty($queryStrip)) ? $query : $queryStrip;
}

/**
 * ensures that a __UW_TEMP table test SQL is running against a temp table, not the real thing
 * @param string query
 * @return bool false if it is a good query
 */
function isRunningAgainstTrueTable($query) {
	$query = strtoupper($query);
	if(strpos($query, '__UW_TEMP') === false) {
		logThis('***ERROR: test query is NOT running against a temp table!!!! -> '.$query);
		return true;
	}
	return false;
}









/**
 * cleans up temp tables created during schema test phase
 */
function testCleanUp($dbType) {
	logThis('Cleaning up temporary tables...');
	
	global $db;
	if(empty($db)) {
		$db = DBManager::getInstance();
	}
	
	$error = '';
	switch($dbType) {
		case 'mysql':
			$q = 'SHOW TABLES LIKE "%__uw_temp"';
			$r = $db->query($q, false, "Preflight Failed for: {$q}");
			
			// using raw mysql_command to use integer index
			while($a = mysql_fetch_row($r)) {
				logThis('Dropping table: '.$a[0]);
				$qClean = "DROP TABLE {$a[0]}";
				$rClean = $db->query($qClean);
			}
		break;
		
		case 'mssql':
		break;
		
		case 'oci8':








		break;
	}
	logThis('Done cleaning up temp tables.');
	return $error;
}


function getFormattedError($error, $query) {
	$error = "<div><b>".$error;
	$error .= "</b>::{$query}</div>";

	return $error;	
}

/**
 * parses a query finding the table name
 * @param string query The query
 * @return string table The table
 */
function getTableFromQuery($query) {
	$standardQueries = array('ALTER TABLE', 'DROP TABLE', 'CREATE TABLE', 'INSERT INTO', 'UPDATE', 'DELETE FROM');
	$query = preg_replace("/[^A-Za-z0-9\_\s]/", "", $query);
	$query = trim(str_replace($standardQueries, '', $query));
	
	$firstSpc = strpos($query, " ");
	$end = ($firstSpc > 0) ? $firstSpc : strlen($query);
	$table = substr($query, 0, $end);
	
	return $table;
}

function preflightCheck() {
	require_once('modules/UpgradeWizard/uw_files.php');
	
	global $sugar_config;
	global $mod_strings;
	global $sugar_version;

	if(!isset($sugar_version) || empty($sugar_version)) {
		require_once('./sugar_version.php');
	} 
	
	unset($_SESSION['rebuild_relationships']);
	unset($_SESSION['rebuild_extensions']);
	
	// don't bother if are rechecking
	$manualDiff			= array();
	if(!isset($_SESSION['unzip_dir']) || empty($_SESSION['unzip_dir'])) {
		logThis('unzipping files in upgrade archive...');
		
		$errors					= array();
		$base_upgrade_dir      = $sugar_config['upload_dir'] . "/upgrades";
		$base_tmp_upgrade_dir  = "$base_upgrade_dir/temp";
		$install_file			= urldecode( $_SESSION['install_file'] );
		$show_files				= true;
		$unzip_dir				= mk_temp_dir( $base_tmp_upgrade_dir );
		$zip_from_dir			= ".";
		$zip_to_dir			= ".";
		$zip_force_copy			= array();
		
		unzip( $install_file, $unzip_dir );
		
		// assumption -- already validated manifest.php at time of upload
		require_once( "$unzip_dir/manifest.php" );
	
		if( isset( $manifest['copy_files']['from_dir'] ) && $manifest['copy_files']['from_dir'] != "" ){
		    $zip_from_dir   = $manifest['copy_files']['from_dir'];
		}
		if( isset( $manifest['copy_files']['to_dir'] ) && $manifest['copy_files']['to_dir'] != "" ){
		    $zip_to_dir     = $manifest['copy_files']['to_dir'];
		}
		if( isset( $manifest['copy_files']['force_copy'] ) && $manifest['copy_files']['force_copy'] != "" ){
		    $zip_force_copy     = $manifest['copy_files']['force_copy'];
		}
		if( isset( $manifest['version'] ) ){
		    $version    = $manifest['version'];
		}
		if( !is_writable( "config.php" ) ){
			return $mod_strings['ERR_UW_CONFIG'];
		}
		
		$_SESSION['unzip_dir'] = clean_path($unzip_dir);
		$_SESSION['zip_from_dir'] = clean_path($zip_from_dir);
		
		logThis('unzip done.');
	} else {
		$unzip_dir = $_SESSION['unzip_dir'];
		$zip_from_dir = $_SESSION['zip_from_dir'];
	}
	
	$upgradeFiles = findAllFiles(clean_path("$unzip_dir/$zip_from_dir"), array());
	$cache_html_files = findAllFilesRelative( "cache/layout", array());
	
	// get md5 sums
	$md5_string = array();	
	if(file_exists(clean_path(getcwd().'/files.md5'))){
		require(clean_path(getcwd().'/files.md5'));
	}
	
	// file preflight checks
	logThis('verifying md5 checksums for files...');
	foreach($upgradeFiles as $file) {
		if(in_array(str_replace(clean_path("$unzip_dir/$zip_from_dir") . "/", '', $file), $uw_files))
			continue; // skip already loaded files
							
		if(strpos($file, '.md5'))
			continue; // skip md5 file
		
		// normalize file paths
		$file = clean_path($file);
		
		// check that we can move/delete the upgraded file
		if(!is_writable($file)) {
			$errors[] = $mod_strings['ERR_UW_FILE_NOT_WRITABLE'].": ".$file;
		}
		// check that destination files are writable
		$destFile = getcwd().str_replace(clean_path($unzip_dir.'/'.$zip_from_dir), '', $file);
		
		if(is_file($destFile)) { // of course it needs to exist first...
			if(!is_writable($destFile)) {
				$errors[] = $mod_strings['ERR_UW_FILE_NOT_WRITABLE'].": ".$destFile;
			}
		}
		
		///////////////////////////////////////////////////////////////////////
		////	DIFFS
		// <= 4.0.1, use cache/layout files to compare
		if(version_compare($sugar_version, '4.0.1', '<=')) {
			$relativeFile = str_replace(getcwd(), './', $file);
			if(	substr(strtolower($relativeFile), -5, 5) == '.html' 
				&& in_array($relativeFile, $cache_html_files)) {
				$manualDiff[] = $file;
				logThis('found a cache file template to preserve: ['.$file.']');
			}
		} else {
			// compare md5s and build up a manual merge list
			$targetFile = clean_path(".".str_replace(getcwd(),'',$destFile));
			$targetMd5 = '0';
			if(is_file($destFile)) {
				if(strpos($targetFile, '.php')) {
					// handle PHP files that were hit with the security regex
					$fp = fopen($destFile, 'r');
					$filesize = filesize($destFile);
					if($filesize > 0) {
						$fileContents = fread($fp, $filesize);
						$targetMd5 = md5($fileContents);
					}
				} else {
					$targetMd5 = md5_file($destFile);
				}
			}
			
			if(isset($md5_string[$targetFile]) && $md5_string[$targetFile] != $targetMd5) {
				logThis('found a file with a differing md5: ['.$targetFile.']');
				$manualDiff[] = $destFile;
			}
		}
		////	END DIFFS
		///////////////////////////////////////////////////////////////////////
	}
	logThis('md5 verification done.');
	$errors['manual'] = $manualDiff;

	return $errors;
}

function getChecklist($steps, $step) {
	global $mod_strings;
	
	$skip = array('start', 'cancel', 'uninstall');
	$j=0;
	$i=1;
	$ret  = '<table cellpadding="3" cellspacing="0" border="0">';
	$ret .= '<tr><th colspan="3" align="left">'.$mod_strings['LBL_UW_CHECKLIST'].':</th></tr>';
	foreach($steps['desc'] as $k => $desc) {
		if(in_array($steps['files'][$j], $skip)) {
			$j++;
			continue;
		}

		//$status = "<span class='error'>{$mod_strings['LBL_UW_INCOMPLETE']}</span>";
		$status = '';
		if(isset($_SESSION['step'][$steps['files'][$k]]) && $_SESSION['step'][$steps['files'][$k]] == 'success') {
			$status = $mod_strings['LBL_UW_COMPLETE'];
		}
		
		if($k == $_REQUEST['step']) {
			$status = $mod_strings['LBL_UW_IN_PROGRESS'];
		}
		
		$ret .= "<tr><td>&nbsp;</td><td><b>{$i}: {$desc}</b></td>";
		$ret .= "<td><i>{$status}</i></td></tr>";
		$i++;
		$j++;
	}
	$ret .= "</table>";
	return $ret;
}

function prepSystemForUpgrade() {
	global $mod_strings;
	global $subdirs;
	global $base_upgrade_dir;

	// increase the cuttoff time to 1 hour
	ini_set("max_execution_time", "3600");

	// make sure dirs exist
	foreach($subdirs as $subdir) {
	    mkdir_recursive("$base_upgrade_dir/$subdir");
	}
	
	// array of special scripts that are executed during (un)installation-- key is type of script, value is filename
	if(!defined('SUGARCRM_PRE_INSTALL_FILE')) {
		define('SUGARCRM_PRE_INSTALL_FILE', 'scripts/pre_install.php');
		define('SUGARCRM_POST_INSTALL_FILE', 'scripts/post_install.php');
		define('SUGARCRM_PRE_UNINSTALL_FILE', 'scripts/pre_uninstall.php');
		define('SUGARCRM_POST_UNINSTALL_FILE', 'scripts/post_uninstall.php');
	}
	
	$script_files = array(
		"pre-install" => constant('SUGARCRM_PRE_INSTALL_FILE'),
		"post-install" => constant('SUGARCRM_POST_INSTALL_FILE'),
		"pre-uninstall" => constant('SUGARCRM_PRE_UNINSTALL_FILE'),
		"post-uninstall" => constant('SUGARCRM_POST_UNINSTALL_FILE'),
	);

	// check that the upload limit is set to 6M or greater
	define('SUGARCRM_MIN_UPLOAD_MAX_FILESIZE_BYTES', 6 * 1024 * 1024);  // 6 Megabytes
	$upload_max_filesize = ini_get('upload_max_filesize');
	$upload_max_filesize_bytes = return_bytes($upload_max_filesize);
	
	if($upload_max_filesize_bytes < constant('SUGARCRM_MIN_UPLOAD_MAX_FILESIZE_BYTES')) {
		$GLOBALS['log']->debug("detected upload_max_filesize: $upload_max_filesize");
		
		echo '<p class="error">'.$mod_strings['MSG_INCREASE_UPLOAD_MAX_FILESIZE'].' '.get_cfg_var('cfg_file_path')."</p>\n";
	}
}


function extractFile($zip_file, $file_in_zip) {
    global $base_tmp_upgrade_dir;
    $my_zip_dir = mk_temp_dir($base_tmp_upgrade_dir);
    unzip_file($zip_file, $file_in_zip, $my_zip_dir);
    return("$my_zip_dir/$file_in_zip");
}

function extractManifest($zip_file) {
	logThis('extracting manifest.');
    return(extractFile($zip_file, "manifest.php"));
}

function getInstallType($type_string) {
    // detect file type
    global $subdirs;

    foreach($subdirs as $subdir) {
        if(preg_match("#/$subdir/#", $type_string)) {
            return($subdir);
        }
    }
    // return empty if no match
    return("");
}

function getImageForType($type) {
    global $image_path;
    $icon = "";
    switch($type) {
        case "full":
            $icon = get_image($image_path . "Upgrade", "");
            break;
        case "langpack":
            $icon = get_image($image_path . "LanguagePacks", "");
            break;
        case "module":
            $icon = get_image($image_path . "ModuleLoader", "");
            break;
        case "patch":
            $icon = get_image($image_path . "PatchUpgrades", "");
            break;
        case "theme":
            $icon = get_image($image_path . "Themes", "");
            break;
        default:
            break;
    }
    return($icon);
}

function getLanguagePackName($the_file) {
    require_once("$the_file");
    if(isset($app_list_strings["language_pack_name"])) {
        return($app_list_strings["language_pack_name"]);
    }
    return("");
}

function getUITextForType($type) {
    if($type == "full") {
        return("Full Upgrade");
    }
    if($type == "langpack") {
        return("Language Pack");
    }
    if($type == "module") {
        return("Module");
    }
    if($type == "patch") {
        return("Patch");
    }
    if($type == "theme") {
        return("Theme");
    }
}

function run_upgrade_wizard_sql($script) {
    global $unzip_dir;
    global $sugar_config;

    $db_type = $sugar_config['dbconfig']['db_type'];
    $script = str_replace("%db_type%", $db_type, $script);
    if(!run_sql_file("$unzip_dir/$script")) {
        die("Error running sql file: $unzip_dir/$script");
    }
}

function validate_manifest($manifest) {
	logThis('validating manifest.php file');
    // takes a manifest.php manifest array and validates contents
    global $subdirs;
    global $sugar_version;
    global $sugar_flavor;
	global $mod_strings;

    if(!isset($manifest['type'])) {
        return $mod_strings['ERROR_MANIFEST_TYPE'];
    }
    
    $type = $manifest['type'];
    
    if(getInstallType("/$type/") == "") {
		return $mod_strings['ERROR_PACKAGE_TYPE']. ": '" . $type . "'.";
    }

    if(isset($manifest['acceptable_sugar_versions'])) {
        $version_ok = false;
        $matches_empty = true;
        if(isset($manifest['acceptable_sugar_versions']['exact_matches'])) {
            $matches_empty = false;
            foreach($manifest['acceptable_sugar_versions']['exact_matches'] as $match) {
                if($match == $sugar_version) {
                    $version_ok = true;
                }
            }
        }
        if(!$version_ok && isset($manifest['acceptable_sugar_versions']['regex_matches'])) {
            $matches_empty = false;
            foreach($manifest['acceptable_sugar_versions']['regex_matches'] as $match) {
                if(preg_match("/$match/", $sugar_version)) {
                    $version_ok = true;
                }
            }
        }

        if(!$matches_empty && !$version_ok) {
            return $mod_strings['ERROR_VERSION_INCOMPATIBLE']."<br />".
            $mod_strings['ERR_UW_VERSION'].$sugar_version;
        }
    }

    if(isset($manifest['acceptable_sugar_flavors']) && sizeof($manifest['acceptable_sugar_flavors']) > 0) {
        $flavor_ok = false;
        foreach($manifest['acceptable_sugar_flavors'] as $match) {
            if($match == $sugar_flavor) {
                $flavor_ok = true;
            }
        }
        if(!$flavor_ok) {
            return $mod_strings['ERROR_FLAVOR_INCOMPATIBLE']."<br />".
            $mod_strings['ERR_UW_FLAVOR'].$sugar_flavor."<br />".
            $mod_strings['ERR_UW_FLAVOR_2'].$manifest['acceptable_sugar_flavors'][0];
        }
    }
    
    return '';
}


function unlinkUploadFiles() {
	return;
//	logThis('at unlinkUploadFiles()');
//	
//	if(isset($_SESSION['install_file']) && !empty($_SESSION['install_file'])) {
//		$upload = $_SESSION['install_file'];
//		
//		if(is_file($upload)) {
//			logThis('unlinking ['.$upload.']');
//			@unlink($upload);
//		}
//	}
}

/**
 * deletes files created by unzipping a package
 */
function unlinkTempFiles() {
	global $sugar_config;
	
	logThis('at unlinkTempFiles()');
	
	$tempDir = clean_path(getcwd().'/'.$sugar_config['upload_dir'].'/upgrades/temp');
	$files = findAllFiles($tempDir, array(), false);
	rsort($files);
	
	foreach($files as $file) {
		if(!is_dir($file)) {
			logThis('unlinking ['.$file.']');
			@unlink($file);
		}
	}
	
	// now do dirs
	$files = findAllFiles($tempDir, array(), true);
	foreach($files as $dir) {
		if(is_dir($dir)) {
			logThis('removing dir ['.$dir.']');
			@rmdir($dir);
		}
	}
	
	$cacheFile = "modules/UpgradeWizard/_persistence.php";
	if(is_file($cacheFile)) {
		logThis("Unlinking Upgrade cache file: '_persistence.php'");
		@unlink($cacheFile);
	}
}

/**
 * finds all files in the passed path, but skips select directories
 * @param string dir Relative path
 * @param array the_array Collections of found files/dirs
 * @param bool include_dir True if we want to include directories in the
 * returned collection
 */
function uwFindAllFiles($dir, $the_array, $include_dirs=false, $skip_dirs=array(), $echo=false) {
	// check skips
	foreach($skip_dirs as $skipMe) {
		if(strpos(clean_path($dir), $skipMe) !== false) {
			return $the_array;
		}
	}

	$d = dir($dir);
	
	while($f = $d->read()) {
	    if($f == "." || $f == "..") { // skip *nix self/parent
	        continue;
	    }

		// for AJAX length count
    	if($echo) {
	    	echo '.';
	    	ob_flush();
    	}

	    if(is_dir("$dir/$f")) {
			if($include_dirs) { // add the directory if flagged
				$the_array[] = clean_path("$dir/$f");
			}
			
			// recurse in
	        $the_array = uwFindAllFiles("$dir/$f/", $the_array, $include_dirs, $skip_dirs, $echo);
	    } else {
	        $the_array[] = clean_path("$dir/$f");
	    }
	
	
	}
	rsort($the_array);
	return $the_array;
}



/**
 * unset's UW's Session Vars
 */
function resetUwSession() {
	logThis('resetting $_SESSION');

	if(isset($_SESSION['committed']))
		unset($_SESSION['committed']);
	if(isset($_SESSION['sugar_version_file']))
		unset($_SESSION['sugar_version_file']);
	if(isset($_SESSION['upgrade_complete']))
		unset($_SESSION['upgrade_complete']);
	if(isset($_SESSION['allTables']))
		unset($_SESSION['allTables']);
	if(isset($_SESSION['alterCustomTableQueries']))
		unset($_SESSION['alterCustomTableQueries']);
	if(isset($_SESSION['skip_zip_upload']))
		unset($_SESSION['skip_zip_upload']);
	if(isset($_SESSION['sugar_version_file']))
		unset($_SESSION['sugar_version_file']);
	if(isset($_SESSION['install_file']))
		unset($_SESSION['install_file']);
	if(isset($_SESSION['unzip_dir']))
		unset($_SESSION['unzip_dir']);
	if(isset($_SESSION['zip_from_dir']))
		unset($_SESSION['zip_from_dir']);
	if(isset($_SESSION['overwrite_files']))
		unset($_SESSION['overwrite_files']);
	if(isset($_SESSION['schema_change']))
		unset($_SESSION['schema_change']);
	if(isset($_SESSION['uw_restore_dir']))
		unset($_SESSION['uw_restore_dir']);
	if(isset($_SESSION['step']))
		unset($_SESSION['step']);
	if(isset($_SESSION['files']))
		unset($_SESSION['files']);
}
	
/**
 * runs rebuild scripts
 */
function UWrebuild() {
	global $db;
	logThis('Deleting Relationship Cache. Relationships will automatically refresh.');
	logThis('output AJAX call to rebuild relationships');
	echo "
	<script>
		var rebuildResult = '';
		var xmlhttp=false;
		/*@cc_on @*/
		/*@if (@_jscript_version >= 5)
		// JScript gives us Conditional compilation, we can cope with old IE versions.
		// and security blocked creation of the objects.
		 try {
		  xmlhttp = new ActiveXObject(\"Msxml2.XMLHTTP\");
		 } catch (e) {
		  try {
		   xmlhttp = new ActiveXObject(\"Microsoft.XMLHTTP\");
		  } catch (E) {
		   xmlhttp = false;
		  }
		 }
		@end @*/
		if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			try {
				xmlhttp = new XMLHttpRequest();
			} catch (e) {
				xmlhttp = false;
			}
		}
		if (!xmlhttp && window.createRequest) {
			try {
				xmlhttp = window.createRequest();
			} catch (e) {
				xmlhttp = false;
			}
		}
		xmlhttp.onreadystatechange = function() {
		            if(xmlhttp.readyState == 4) {
		              rebuildResult = xmlhttp.responseText;
		            }
		          }
		xmlhttp.open('GET', 'index.php?module=Administration&action=RebuildRelationship&to_pdf=true', true);
		xmlhttp.send(null);
		</script>";
		 			
	logThis('Rebuilding everything...');
	require_once('ModuleInstall/ModuleInstaller.php');
	$mi = new ModuleInstaller();
	$mi->rebuild_all();
	
	$query = "DELETE FROM versions WHERE name='Rebuild Extensions'";
	$db->query($query);
	logThis('Registering rebuild record: '.$query);
	logThis('Rebuild done.');
	
	// insert a new database row to show the rebuild extensions is done
	$id = create_guid();
	$gmdate = gmdate('Y-m-d H:i:s');
	$date_entered = db_convert("'$gmdate'", 'datetime');
	$query = 'INSERT INTO versions (id, deleted, date_entered, date_modified, modified_user_id, created_by, name, file_version, db_version) '
		. "VALUES ('$id', '0', $date_entered, $date_entered, '1', '1', 'Rebuild Extensions', '4.0.0', '4.0.0')"; 
	$db->query($query);
	logThis('Registering rebuild record in versions table: '.$query);
}

function getCustomTables($dbType) {
	global $db;
	
	$customTables = array();
	
    switch($dbType) {
		case 'mysql':
    		$query = "SHOW tables LIKE '%_cstm'";
        	$result = $db->query($query, true, 'Error getting custom tables');
            while ($row = $db->fetchByAssoc($result)){
            	$customTables[] = array_pop($row);
            }
            break;
	}
    return $customTables;
}

function alterCustomTables($dbType, $customTables)
{
	switch($dbType) {
		case 'mysql':
			$i = 0;
			while( $i < count($customTables) ) {
				$alterCustomTableSql[] = "ALTER TABLE " . $customTables[$i] . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
				$i++;
			}
			break;
		case 'oci8':
			break;
	}
	return $alterCustomTableSql;	 
}
	
function executeAlterCustomTablesSql($dbType, $queries) {
	global $db;
	
	foreach($queries as $query){
		if(!empty($query)){
			logThis("Sending query: ".$query);
	            if($db->dbType == 'oci8') {



     	        } else {
                    $query_result = $db->query($query.';', true, "An error has occured while performing db query.  See log file for details.<br>");
                }
         }
	}
	return true;
}

function getAllTables($dbType) {
	global $db;
	
	$tables = array();
	
    switch($dbType) {
		case 'mysql':
    		$query = "SHOW tables";
        	$result = $db->query($query, true, 'Error getting custom tables');
            while ($row = $db->fetchByAssoc($result)){
            	$tables[] = array_pop($row);
            }
            break;
	}
    return $tables;
}

function printAlterTableSql($tables)
{
	$alterTableSql = '';
	
	foreach($tables as $table)
		$alterTableSql .= "ALTER TABLE " . $table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;" . "\n";
		
	return $alterTableSql;
}

function executeConvertTablesSql($dbType, $tables) {
	global $db;
	
	foreach($tables as $table){
		$query = "ALTER TABLE " . $table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
		if(!empty($table)){
			logThis("Sending query: ".$query);
	            if($db->dbType == 'oci8') {



     	        } else {
                    $query_result = $db->query($query.';', true, "An error has occured while performing db query.  See log file for details.<br>");
                }
         }
	}
	return true;
}

function testThis() {
	$files = uwFindAllFiles(getcwd().'/test', array());
	
	$out = "<table cellpadding='1' cellspacing='0' border='0'>\n";

	$priorPath = '';
	foreach($files as $file) {
		$relativeFile = clean_path(str_replace(getcwd().'/test', '', $file));
		$relativeFile = ($relativeFile{0} == '/') ? substr($relativeFile, 1, strlen($relativeFile)) : $relativeFile;
		
		$relativePath = dirname($relativeFile);
		
		if($relativePath == $priorPath) { // same dir, new file
			$out .= "<tr><td>".basename($relativeFile)."</td></tr>";
			$priorPath = $relativePath;
		} else { // new dir
			
		}
	}
	
	$out .= "</table>";
	
	echo $out;
}




function testThis2($dir, $id=0, $hide=false) {
	$path = $dir;
	$dh = opendir($dir);
	rewinddir($dh);
	
	$doHide = ($hide) ? 'none' : '';
	$out = "<div id='{$id}' style='display:{$doHide};'>";
	$out .= "<table cellpadding='1' cellspacing='0' border='0' style='border:0px solid #ccc'>\n";
	
	while($file = readdir($dh)) {
		if($file == '.' || $file == '..' || $file == 'CVS' || $file == '.cvsignore') 
			continue;
		
		if(is_dir($path.'/'.$file)) {
			$file = $path.'/'.$file;
			$newI = create_guid();
			$out .= "<tr><td valign='top'><a href='javascript:toggleNwFiles(\"{$newI}\");'><img border='0' src='themes/Sugar/images/Workflow.gif'></a></td>\n";
			$out .= "<td valign='top'><b><a href='javascript:toggleNwFiles(\"{$newI}\");'>".basename($file)."</a></b></td></tr>";
			$out .= "<tr><td></td><td valign='top'>".testThis2($file, $newI, true)."</td></tr>";
		} else {
			$out .= "<tr><td valign='top'>&nbsp;</td>\n";
			$out .= "<td valign='top'>".basename($file)."</td></tr>";
		}
	}

	$out .= "</tr></table>";
	$out .= "</div>";
	
	closedir($dh);
	return $out;
}





function testThis3(&$files, $id, $hide, $previousPath = '') {
	if(!is_array($files) || empty($files))
		return '';

_pp($files);
	$out = '';
	
	// expecting full path here
	foreach($files as $k => $file) {
		$file = str_replace(getcwd(), '', $file);
		$path = dirname($file);
		$fileName = basename($file);
		
		if($fileName == 'CVS' || $fileName == '.cvsignore')
			continue;
		
		if($path == $previousPath) { // same directory
			// new row for each file
			$out .= "<tr><td valign='top' align='left'>&nbsp;</td>";
			$out .= "<td valign='top' align='left'>{$fileName}</td></tr>";
		} else { // new directory
			$newI = $k;
			$out .= "<tr><td valign='top'><a href='javascript:toggleNwFiles(\"{$newI}\");'><img border='0' src='themes/Sugar/images/Workflow.gif'></a></td>\n";
			$out .= "<td valign='top'><b><a href='javascript:toggleNwFiles(\"{$newI}\");'>".$fileName."</a></b></td></tr>";
			$recurse = testThis3($files, $newI, true, $previousPath);
			_ppd($recurse);
			$out .= "<tr><td></td><td valign='top'>".$recurse."</td></tr>";
		}
		
		$previousPath = $path;
	}
	$display = ($hide) ? 'none' : '';
	$ret = <<<eoq
	<div id="{$id}" style="display:{$display}">
	<table cellpadding='1' cellspacing='0' border='0' style='border:1px solid #ccc'>
		{$out}
	</table>
	</div>
eoq;
	return $ret;
}


function testThis4($filePath, $fileNodes=array(), $fileName='') {
	$path = dirname($filePath);
	$file = basename($filePath);
	
	$exFile = explode('/', $path);
	
	foreach($exFile as $pathSegment) {
		if(is_array($fileNodes[$pathSegment])) { // path already processed
			
		} else { // newly found path
			$fileNodes[$pathSegment] = array();
		}
		
		if($fileName != '') {
			$fileNodes[$pathSegment][] = $fileName;
		}
	}
	
	return $fileNodes;
}



///////////////////////////////////////////////////////////////////////////////
////	SYSTEM CHECK FUNCTIONS
/**
 * generates an array with all files in the SugarCRM root directory, skipping
 * cache/
 * @return array files Array of files with absolute paths
 */
function getFilesForPermsCheck() {
	global $sugar_config;
	
	logThis('Got JSON call to find all files...');
	$filesNotWritable = array();
	$filesNWPerms = array();
	
	// add directories here that should be skipped when doing file permissions checks (cache/upload is the nasty one)
	$skipDirs = array(
		$sugar_config['upload_dir'], 
	);
	$files = uwFindAllFiles(getcwd(), array(), true, $skipDirs, true);
	return $files;
}

/**
 * checks files for permissions
 * @param array files Array of files with absolute paths
 * @return string result of check
 */
function checkFiles($files, $echo=false) {
	global $mod_strings;
	$filesNotWritable = array();
	$i=0;
	$filesOut = "
		<a href='javascript:void(0); toggleNwFiles(\"filesNw\");'>{$mod_strings['LBL_UW_SHOW_NW_FILES']}</a>
		<div id='filesNw' style='display:none;'>
		<table cellpadding='3' cellspacing='0' border='0'>
		<tr>
			<th align='left'>{$mod_strings['LBL_UW_FILE']}</th>
			<th align='left'>{$mod_strings['LBL_UW_FILE_PERMS']}</th>
			<th align='left'>{$mod_strings['LBL_UW_FILE_OWNER']}</th>
			<th align='left'>{$mod_strings['LBL_UW_FILE_GROUP']}</th>
		</tr>";
	
	$isWindows = is_windows();
	foreach($files as $file) {

		if($isWindows) {
			if(!is_writable_windows($file)) {
				logThis('WINDOWS: File ['.$file.'] not readable - saving for display');
				// don't warn yet - we're going to use this to check against replacement files
	// aw: commented out; it's a hack to allow upgrade wizard to continue on windows... will fix later
				/*$filesNotWritable[$i] = $file;
				$filesNWPerms[$i] = substr(sprintf('%o',fileperms($file)), -4);
				$filesOut .= "<tr>".
								"<td><span class='error'>{$file}</span></td>".
								"<td>{$filesNWPerms[$i]}</td>".
								"<td>".$mod_strings['ERR_UW_CANNOT_DETERMINE_USER']."</td>".
								"<td>".$mod_strings['ERR_UW_CANNOT_DETERMINE_GROUP']."</td>".
							  "</tr>";*/
			}		
		} else {
			if(!is_writable($file)) {
				logThis('File ['.$file.'] not writable - saving for display');
				// don't warn yet - we're going to use this to check against replacement files
				$filesNotWritable[$i] = $file;
				$filesNWPerms[$i] = substr(sprintf('%o',fileperms($file)), -4);
				$owner = posix_getpwuid(fileowner($file));
				$group = posix_getgrgid(filegroup($file));
				$filesOut .= "<tr>".
								"<td><span class='error'>{$file}</span></td>".
								"<td>{$filesNWPerms[$i]}</td>".
								"<td>".$owner['name']."</td>".
								"<td>".$group['name']."</td>".
							  "</tr>";
			}
		}
		$i++;
	}
	
	$filesOut .= '</table></div>';
	// not a stop error
	$errors['files']['filesNotWritable'] = (count($filesNotWritable) > 0) ? true : false;
	if(count($filesNotWritable) < 1) {
		$filesOut = "{$mod_strings['LBL_UW_FILE_NO_ERRORS']}";
	}
	
	return $filesOut;
}

?>
