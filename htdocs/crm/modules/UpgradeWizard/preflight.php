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
 * $Id: preflight.php,v 1.33 2006/08/26 03:57:26 chris Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

// LEGACY for old versions - emulating upload.php 
// aw: make this better for later versions.
if (version_compare(substr($sugar_version, 0, 5), '4.5.0', '<')) {
	logThis('emulating upload.php');
	getValidPatchName(false);
}

logThis('at preflight.php');

testCleanUp($db->dbType);

$stop = true; // flag to show "next"
if(isset($_SESSION['files'])) {
	unset($_SESSION['files']);
}

$errors = preflightCheck();
$diffs = '';
$schema = '';

if((count($errors) == 1)) { // only diffs
	logThis('file preflight check passed successfully.');
	$stop = false;
	$out  = $mod_strings['LBL_UW_PREFLIGHT_TESTS_PASSED'];
	$stop = false;
	
	$disableEmail = (empty($current_user->email1)) ? 'DISABLED' : 'CHECKED';
	
	if(count($errors['manual']) > 0) {
		$preserveFiles = array();
		
		$diffs =<<<eoq
			<script type="text/javascript" language="Javascript">
				function preflightToggleAll(cb) {
					var checkAll = false;
					var form = document.getElementById('diffs');
					
					if(cb.checked == true) {
						checkAll = true;
					}
					
					for(i=0; i<form.elements.length; i++) {
						if(form.elements[i].type == 'checkbox') {
							form.elements[i].checked = checkAll;
						}
					}
					return;
				}
			</script>
			
			<table cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td valign='top'>
						<input type='checkbox' name='addTask' id='addTask' CHECKED>
					</td>
					<td valign='top'>
						{$mod_strings['LBL_UW_PREFLIGHT_ADD_TASK']}
					</td>
				</tr>
				<tr>
					<td valign='top'>
						<input type='checkbox' name='addEmail' id='addEmail' $disableEmail>
					</td>
					<td valign='top'>
						{$mod_strings['LBL_UW_PREFLIGHT_EMAIL_REMINDER']}
					</td>
				</tr>
			</table>
			
			<form name='diffs' id='diffs'>
			<p><a href='javascript:void(0); toggleNwFiles("diffsHide");'>{$mod_strings['LBL_UW_SHOW_DIFFS']}</a></p>
			<div id='diffsHide' style='display:none'>
				<table cellpadding='0' cellspacing='0' border='0'>
					<tr>
						<td valign='top' colspan='2'>
							{$mod_strings['LBL_UW_PREFLIGHT_FILES_DESC']}
							<br />&nbsp;
						</td>
					</tr>
					<tr>
						<td valign='top' colspan='2'>
							<input type='checkbox' onchange='preflightToggleAll(this);'>&nbsp;<i><b>{$mod_strings['LBL_UW_PREFLIGHT_TOGGLE_ALL']}</b></i>
							<br />&nbsp;
						</td>
					</tr>
eoq;
		foreach($errors['manual'] as $diff) {
			$diff = clean_path($diff);
			$_SESSION['files']['manual'][] = $diff;

			$checked = (isAutoOverwriteFile($diff)) ? 'CHECKED' : '';
			
			if(empty($checked)) {
				$preserveFiles[] = $diff;
			}
			
			$diffs .= "<tr><td valign='top'>";
			$diffs .= "<input type='checkbox' name='diff_files[]' value='{$diff}' $checked>";
			$diffs .= "</td><td valign='top'>";
			$diffs .= str_replace(getcwd(), '.', $diff);
			$diffs .= "</td></tr>";
		}
		$diffs .= "</table>";
		$diffs .= "</div></p>";
		$diffs .= "</form>";
		
		// list preserved files (templates, etc.)
		$preserve = '';
		foreach($preserveFiles as $pf) {
			if(empty($preserve)) {
				$preserve .= "<table cellpadding='0' cellspacing='0' border='0'><tr><td><b>";
				$preserve .= $mod_strings['LBL_UW_PREFLIGHT_PRESERVE_FILES'];
				$preserve .= "</b></td></tr>";
			}
			$preserve .= "<tr><td valign='top'><i>".str_replace(getcwd(), '.', $pf)."</i></td></tr>";
		}
		if(!empty($preserve)) {
			$preserve .= '</table><br>';
		}
		$diffs = $preserve.$diffs;
	} else { // NO FILE DIFFS REQUIRED
		$diffs = $mod_strings['LBL_UW_PREFLIGHT_NO_DIFFS'];
	}
} else {
	logThis('*** ERROR: found too many preflight errors - displaying errors and stopping execution.');
	$out = "<b>{$mod_strings['ERR_UW_PREFLIGHT_ERRORS']}:</b><hr />";
	$out .= "<span class='error'>";
	
	foreach($errors as $error) {
		if(is_array($error)) { // manual diff files
			continue;
		} else {
			$out .= "{$error}<br />";
		}
	}
	$out .= "</span><br />";
}


///////////////////////////////////////////////////////////////////////////////
////	SCHEMA SCRIPT HANDLING
logThis('starting schema preflight check...');

if(!isset($sugar_db_version) || empty($sugar_db_version)) {
	include('./sugar_version.php');
}

if(!isset($manifest['version']) || empty($manifest['version'])) {
	include($_SESSION['unzip_dir'].'/manifest.php');
}

$current_version = substr(preg_replace("#[^0-9]#", "", $sugar_db_version),0,3);
$targetVersion =  substr(preg_replace("#[^0-9]#", "", $manifest['version']),0,3);
$sqlScript = $_SESSION['unzip_dir'].'/scripts/'.$current_version.'_to_'.$targetVersion.'_'.$db->dbType.'.sql';

$newTables = array();


if($db->dbType == 'oci8') {
	if(!is_file($sqlScript)) {
		$sqlScript = $_SESSION['unzip_dir'].'/scripts/'.$current_version.'_to_'.$targetVersion.'_oracle.sql';
	}
}

$alterTableSchema = '';
logThis('looking for schema script at: '.$sqlScript);
if(is_file($sqlScript)) {
	logThis('found schema upgrade script: '.$sqlScript);

	logThis('schema preflight using MySQL');
	$fp = fopen($sqlScript, 'r');
	$contents = fread($fp, filesize($sqlScript));
	
	if(rewind($fp)) {
		$completeLine = '';
		while($line = fgets($fp)) {
			if(strpos($line, '--') === false) {
				$completeLine .= " ".trim($line);
				if(strpos($line, ';') !== false) {
					$completeLine = str_replace(';','',$completeLine);
					
					// populate newTables array to prevent "getting sample data" from non-existent tables
					if(strtoupper(substr($completeLine,1,5)) == 'CREAT')
						$newTables[] = getTableFromQuery($completeLine);
					
					$bad = verifySqlStatement(trim($completeLine), $db->dbType, $newTables);
					if(!empty($bad)) {
						logThis('*** ERROR: schema change script has errors - stopping execution');
						$sqlErrors[] = $bad;
					}
					// reset for next SQL query
					$completeLine = '';
				}
			}
		}
	} else {
		logThis('*** ERROR: could not read schema script: '.$sqlScript);
		$sqlErrors[] = $mod_strings['ERR_UW_FILE_NOT_READABLE'].'::'.$sqlScript;
	}
	
	// remove __uw_temp tables
	testCleanUp($db->dbType);
	fclose($fp);
	
	$customTables = getCustomTables($db->dbType);
	if ( !empty($customTables) ) {
		$_SESSION['alterCustomTableQueries'] = alterCustomTables($db->dbType, $customTables);
	} else {
		$_SESSION['alterCustomTableQueries'] = false;
	}
	
	$_SESSION['allTables'] = getAllTables($db->dbType);

	
	
	$schema  = "<p><a href='javascript:void(0); toggleNwFiles(\"schemashow\");'>{$mod_strings['LBL_UW_SHOW_SCHEMA']}</a>";
	$schema .= "<div id='schemashow' style='display:none;'>";
	$schema .= "<textarea readonly cols='80' rows='10'>{$contents}</textarea>";
	$schema .= "</div></p>";
	
	if(version_compare($current_version, '450', "<")) {
		if(isset($_SESSION['allTables']) && !empty($_SESSION['allTables'])) {
			$alterTableContents = printAlterTableSql($_SESSION['allTables']);
			$alterTableSchema  = "<p><a href='javascript:void(0); toggleNwFiles(\"alterTableSchemashow\");'>{$mod_strings['LBL_UW_CHARSET_SCHEMA_CHANGE']}</a>";
			$alterTableSchema .= "<div id='alterTableSchemashow' style='display:none;'>";
			$alterTableSchema .= "<textarea readonly cols='80' rows='10'>{$alterTableContents}</textarea>";
			$alterTableSchema .= "</div></p>";
		}
	} else {
		$alterTableSchema = '<i>'.$mod_strings['LBL_UW_PREFLIGHT_NOT_NEEDED'].'</i>';
	}
		
	if(!empty($sqlErrors)) {
		$stop = true;
		$out = "<b class='error'>{$mod_strings['ERR_UW_PREFLIGHT_ERRORS']}:</b> ";
		$out .= "<a href='javascript:void(0);toggleNwFiles(\"sqlErrors\");'>{$mod_strings['LBL_UW_SHOW_SQL_ERRORS']}</a><div id='sqlErrors' style='display:none'>";
		foreach($sqlErrors as $sqlError) {
			$out .= "<br><span class='error'>{$sqlError}</span>";
		}
		$out .= "</div><hr />";
	}
} else {
	$customTableSchema = '';
	logThis('no schema script found - all schema preflight skipped');
}
logThis('schema preflight done.');
////	END SCHEMA SCRIPT HANDLING
///////////////////////////////////////////////////////////////////////////////



$final =<<<eoq
<table cellpadding="3" cellspacing="0" border="0">
	<tr>
		<th colspan="2" align="left">
			<b>{$mod_strings['LBL_UW_PREFLIGHT_COMPLETE']}</b><hr />
		</th>
	</tr>
	<tr>
		<td colspan="2" align="left" valign="top">
			{$out}
		</td>
	</tr>
	<tr>
		<td align="left" valign="top">
			<b>{$mod_strings['LBL_UW_MANUAL_MERGE']}</b>
		</td>
		<td align="left" valign="top">
			{$diffs}
		</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td align="left" valign="top">
			<b>{$mod_strings['LBL_UW_SCHEMA_CHANGE']}</b>
		</td>
		<td align="left" valign="top">
			{$schema}
		</td>
	</tr>

	<tr>
		<td align="left" valign="top">
			<b>{$mod_strings['LBL_UW_CHARSET_SCHEMA_CHANGE']}</b>
		</td>
		<td align="left" valign="top">
			{$alterTableSchema}
		</td>
	</tr>
	
	<tr>
		<td>
		</td>
		<td valign="top">
			<div>
			<b>{$mod_strings['LBL_UW_DB_METHOD']}</b><br />
			<select name="schema_change" id="select_schema_change" onchange="checkSqlStatus(false);">
				<option value="sugar">{$mod_strings['LBL_UW_DB_CHOICE1']}</option>
				<option value="manual">{$mod_strings['LBL_UW_DB_CHOICE2']}</option>
			</select>
			</div>
			<div id='show_sql_run' style='display:none'>
				<input type='checkbox' name='sql_run' id='sql_run' onmousedown='checkSqlStatus(true);'> {$mod_strings['LBL_UW_SQL_RUN']}
			</div>
		</td>
	</tr>
</table>

eoq;

$uwMain = $final;

$showBack		= false;
$showCancel		= true;
$showRecheck	= true;
$showNext		= ($stop) ? false : true;

$stepBack		= $_REQUEST['step'] - 1;
$stepNext		= $_REQUEST['step'] + 1;
$stepCancel		= -1;
$stepRecheck	= $_REQUEST['step'];

$_SESSION['step'][$steps['files'][$_REQUEST['step']]] = ($stop) ? 'failed' : 'success';
?>
