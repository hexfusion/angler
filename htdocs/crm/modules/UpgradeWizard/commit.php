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
 * $Id: commit.php,v 1.36 2006/09/02 00:16:38 chris Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/
$_SESSION['upgrade_complete'] = '';

logThis('[At commit.php]');
$stop = true; // flag to show "next"

set_time_limit(0);
/*
 * [unzip_dir] => /Users/curisu/www/head/cache/upload//upgrades/temp/QSugp3
 * [zip_from_dir]  => SugarEnt-Upgrade-4.0.1-to-4.2.1
 * rest_dir: /Users/curisu/www/head/cache/upload/SugarEnt-Upgrade-4.0.1-to-4.2.1-restore
 */

// flag upgradeSql script run method
$_SESSION['schema_change'] = $_REQUEST['schema_change'];

// prevent "REFRESH" double commits
if(!isset($_SESSION['committed'])) {
	$_SESSION['committed'] = true; // flag to prevent refresh double-commit
	unset($_SESSION['rebuild_relationships']);
	unset($_SESSION['rebuild_extensions']);
	
	//$version		= $_REQUEST['version'];
	$unzip_dir		= $_SESSION['unzip_dir'];
	$zip_from_dir	= $_SESSION['zip_from_dir'];
	$install_file	= urldecode( $_SESSION['install_file'] );
	$file_action	= "";
	$uh_status		= "";
	$errors			= array();
	$out			= '';
	$backupFilesExist = false;
	
	$rest_dir = clean_path(remove_file_extension($install_file)."-restore");
	mkdir_recursive($rest_dir);
	
	///////////////////////////////////////////////////////////////////////////////
	////	MAKE BACKUPS OF TARGET FILES
	logThis('backing up files to be overwritten...');
	$newFiles = findAllFiles(clean_path($unzip_dir.'/'.$zip_from_dir), array());
	
	// keep this around for canceling
	$_SESSION['uw_restore_dir'] = clean_path($rest_dir);
	
	foreach($newFiles as $file) {
		if(strpos($file, 'md5'))
			continue;
		
		// get name of current file to place in restore directory
		$cleanFile = str_replace(clean_path($unzip_dir.'/'.$zip_from_dir),'',$file);
	
		// make sure the directory exists
		$cleanDir = $rest_dir.'/'.dirname( $cleanFile );
		if(!is_dir($cleanDir)) {
			mkdir_recursive($cleanDir);
		}
		
		$oldFile = clean_path(getcwd().'/'.$cleanFile);
	
		// only copy restore files for replacements - ignore new files from patch
		if(is_file($oldFile)) {
			if(is_writable($rest_dir)) {
				logThis('Backing up file: '.$oldFile);
				if(!copy($oldFile, $rest_dir.'/'.$cleanFile)) {
					logThis('*** ERROR: could not backup file: '.$oldFile);
					$errors[] = "{$mod_strings['LBL_UW_BACKUP']}::{$mod_strings['ERR_UW_FILE_NOT_COPIED']}: {$oldFile}";
				} else {
					$backupFilesExist = true;
				}
				
			} else {
				logThis('*** ERROR: directory not writable: '.$rest_dir);
				$errors[] = "{$mod_strings['LBL_UW_BACKUP']}::{$mod_strings['ERR_UW_DIR_NOT_WRITABLE']}: {$oldFile}";
			}
		}
	}
	logThis('file backup done.');
	////	END MAKE BACKUPS OF TARGET FILES
	///////////////////////////////////////////////////////////////////////////////
	
	///////////////////////////////////////////////////////////////////////////////
	////	HANDLE PREINSTALL SCRIPTS
	if(empty($errors)) {
		$file = "$unzip_dir/" . constant('SUGARCRM_PRE_INSTALL_FILE');
		if(is_file($file)) {
			$out .= "{$mod_strings['LBL_UW_INCLUDING']}: {$file} <br>\n";
			include($file);
			
			logThis('Running pre_install()...');
			pre_install();
			logThis('pre_install() done.');
		}
	}
	////	HANDLE PREINSTALL SCRIPTS
	///////////////////////////////////////////////////////////////////////////////
	
	///////////////////////////////////////////////////////////////////////////////
	////	COPY NEW FILES INTO TARGET INSTANCE
	logThis('Starting file copy process...');
	$newFiles = findAllFiles(clean_path($unzip_dir.'/'.$zip_from_dir), array());
	$zipPath = clean_path($unzip_dir.'/'.$zip_from_dir);
	$doNotOverwrite = explode('::', $_REQUEST['overwrite_files_serial']);
	
	$copiedFiles = array();
	$skippedFiles = array();
	foreach($newFiles as $file) {
		$cleanFile	= str_replace($zipPath,'',$file);
		$srcFile	= $zipPath.$cleanFile;
		$targetFile = clean_path(getcwd().'/'.$cleanFile);
		
		if(!is_dir(dirname($targetFile))) {
			mkdir_recursive( dirname( $targetFile ) ); // make sure the directory exists
		}
		
		if(	(!file_exists($targetFile)) ||				/* brand new file */
			(!in_array($targetFile, $doNotOverwrite))	/* manual diff file */
		) {
			// handle sugar_version.php
			if(strpos($targetFile, 'sugar_version.php') !== false) {
				logThis('Skipping "sugar_version.php" - file copy will occur at end of successful upgrade');
				$_SESSION['sugar_version_file'] = $srcFile;
				continue;
			}
	
			logThis('Copying file to destination: '.$targetFile);
			if(!copy($srcFile, $targetFile)) {
				logThis('*** ERROR: could not copy file: '.$targetFile);
			} else {
				$copiedFiles[] = $targetFile;
			}
		} else {
			logThis('Skipping file: '.$targetFile);
			$skippedFiles[] = $targetFile;
		}
	}
	logThis('File copy done.');
	////	END COPY NEW FILES INTO TARGET INSTANCE
	///////////////////////////////////////////////////////////////////////////////
	

	
	///////////////////////////////////////////////////////////////////////////////
	////	HANDLE POSTINSTALL SCRIPTS
	logThis('Starting post_install()...');
	if(empty($errors)) {
		$file = "$unzip_dir/" . constant('SUGARCRM_POST_INSTALL_FILE');
		if(is_file($file)) {
			include($file);
			post_install();
			
			// cn: only run conversion if admin selects "Sugar runs SQL"
			if(!empty($_SESSION['allTables']) && $_SESSION['schema_change'] == 'sugar')
				executeConvertTablesSql($db->dbType, $_SESSION['allTables']);
		}
		logThis('Performing UWrebuild()...');
		UWrebuild();
		logThis('UWrebuild() done.');
	
		require( "sugar_version.php" );
		
		if (!rebuildConfigFile($sugar_config, $sugar_version)) {
			logThis('*** ERROR: could not write config.php! - upgrade will fail!');
			$errors[] = $mod_strings['ERR_UW_CONFIG_WRITE'];
		}
	}
	logThis('post_install() done.');
	//// END POSTINSTALL SCRIPTS
	///////////////////////////////////////////////////////////////////////////////
	
	///////////////////////////////////////////////////////////////////////////////
	////	REGISTER UPGRADE
	logThis('Registering upgrade with UpgradeHistory');
	if(empty($errors)) {
	    $file_action = "copied";
	    // if error was encountered, script should have died before now
	    $new_upgrade = new UpgradeHistory();
	    $new_upgrade->filename      = $install_file;
	    $new_upgrade->md5sum        = md5_file( $install_file );
	    $new_upgrade->type          = 'patch';
	    $new_upgrade->version       = $sugar_version;
	    $new_upgrade->status        = "installed";
	    $new_upgrade->save();
	}
	
	// update versions info
	updateVersions($sugar_version);
	////	REGISTER UPGRADE
	///////////////////////////////////////////////////////////////////////////////
}
// flag to prvent double-commits via refresh
$_SESSION['committed'] = true;

///////////////////////////////////////////////////////////////////////////////
////	FINISH AND OUTPUT
if(empty($errors)) {
	$stop = false;
}

$backupDesc = '';
if($backupFilesExist) {
	$backupDesc .= "<b>{$mod_strings['LBL_UW_BACKUP_FILES_EXIST_TITLE']}</b><br />";
	$backupDesc .= $mod_strings['LBL_UW_BACKUP_FILES_EXIST'].': '.$rest_dir;
}
$copiedDesc = '';
if(count($copiedFiles) > 0) {
	$copiedDesc .= "<b>{$mod_strings['LBL_UW_COPIED_FILES_TITLE']}</b><br />";
	$copiedDesc .= "<a href='javascript:void(0); toggleNwFiles(\"copiedFiles\");'>{$mod_strings['LBL_UW_SHOW']}</a>";
	$copiedDesc .= "<div id='copiedFiles' style='display:none;'>";
	
	foreach($copiedFiles as $file) {
		$copiedDesc .= $file."<br />";
	}
	$copiedDesc .= "</div>";
}
$skippedDesc = '';
if(count($skippedFiles) > 0) {
	$skippedDesc .= "<b>{$mod_strings['LBL_UW_SKIPPED_FILES_TITLE']}</b><br />";
	$skippedDesc .= "<a href='javascript:void(0); toggleNwFiles(\"skippedFiles\");'>{$mod_strings['LBL_UW_SHOW']}</a>";
	$skippedDesc .= "<div id='skippedFiles' style='display:none;'>";
	
	foreach($skippedFiles as $file) {
		$skippedDesc .= $file."<br />";
	}
	$skippedDesc .= "</div>";
}

$rebuildResult  = "<b>{$mod_strings['LBL_UW_REBUILD_TITLE']}</b><br />";
$rebuildResult .= "<a href='javascript:void(0); toggleRebuild();'>{$mod_strings['LBL_UW_SHOW']}</a> <div id='rebuildResult'></div>";

unlinkTempFiles();



///////////////////////////////////////////////////////////////////////////////
////	HANDLE REMINDERS
if(count($skippedFiles) > 0) {
	$desc  = $mod_strings['LBL_UW_COMMIT_ADD_TASK_OVERVIEW']."\n\n";
	$desc .= $mod_strings['LBL_UW_COMMIT_ADD_TASK_DESC_1'];
	$desc .= $_SESSION['uw_restore_dir']."\n\n";
	$desc .= $mod_strings['LBL_UW_COMMIT_ADD_TASK_DESC_2']."\n\n";
	
	foreach($skippedFiles as $file) {
		$desc .= $file."\n";	
	}

	$userDTFormat = $current_user->getUserDateTimePreferences();
	$nowDate = date($userDTFormat['date']);
	$nowTime = date($userDTFormat['time']);
	$nowDateTime = $nowDate.' '.$nowTime;

	if($_REQUEST['addTaskReminder'] == 'remind') {
		logThis('Adding Task for admin for manual merge.');
		require_once('modules/Tasks/Task.php');
		$task = new Task();
		$task->name = $mod_strings['LBL_UW_COMMIT_ADD_TASK_NAME'];
		$task->description = $desc;
		$task->date_due = $nowDate;
		$task->time_due = $nowTime;
		$task->priority = 'High';
		$task->status = 'Not Started';
		$task->assigned_user_id = $current_user->id;
		$task->created_by = $current_user->id;
		$task->date_entered = $nowDateTime;
		$task->date_modified = $nowDateTime;

		$task->team_id = '1';

		$task->save();
	}
	
	if($_REQUEST['addEmailReminder'] == 'remind') {
		logThis('Sending Reminder for admin for manual merge.');
		require_once('modules/Emails/Email.php');
		$email = new Email();
		$email->assigned_user_id = $current_user->id;
		$email->name = $mod_strings['LBL_UW_COMMIT_ADD_TASK_NAME'];
		$email->description = $desc;
		$email->description_html = nl2br($desc);
		$email->from_name = $current_user->full_name;
		$email->from_addr = $current_user->email1;
		$email->to_addrs_arr = $email->parse_addrs($current_user->email1,'','','');
		$email->cc_addrs_arr = array();
		$email->bcc_addrs_arr = array();
		$email->date_entered = $nowDateTime;
		$email->date_modified = $nowDateTime;

		$email->team_id = '1';

		$email->send();
		$email->save();
	}
}
////	HANDLE REMINDERS
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	OUTPUT
$uwMain =<<<eoq
<script type="text/javascript" language="javascript">
	function toggleRebuild() {
		var target = document.getElementById('rebuildResult');
		
		if(target.innerHTML == '') {
			target.innerHTML = rebuildResult; // found in UWrebuild()
		} else {
			target.innerHTML = '';
		}
	}
</script>
<table cellpadding="3" cellspacing="0" border="0">
	<tr>
		<th align="left">
			{$mod_strings['LBL_UW_TITLE_COMMIT']}
		</th>
	</tr>
	<tr>
		<td align="left">
			<p>
			{$backupDesc}
			</p>
			<p>
			{$copiedDesc}
			</p>
			<p>
			{$skippedDesc}
			</p>
			<p>
			{$rebuildResult}
			</p>
		</td>
	</tr>
</table>
eoq;

$showBack		= false;
$showCancel		= false;
$showRecheck	= false;
$showNext		= ($stop) ? false : true;

$stepBack		= $_REQUEST['step'] - 1;
$stepNext		= $_REQUEST['step'] + 1;
$stepCancel		= -1;
$stepRecheck	= $_REQUEST['step'];

$_SESSION['step'][$steps['files'][$_REQUEST['step']]] = ($stop) ? 'failed' : 'success';
?>
