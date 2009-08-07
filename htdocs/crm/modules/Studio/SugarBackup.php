<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
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

 // $Id: SugarBackup.php,v 1.3 2006/08/27 10:12:27 majed Exp $

require_once('include/utils/file_utils.php');
class SugarBackup{
	/**
	 * Saves a backup of the given file
	 *
	 * @param unknown_type $filename
	 */
	function backup($file){
		static $maxbackups = 10;
		//also creates the folder if it does not exists
		$existingBackups = SugarBackup::backupList($file);
		//if we exceed the max backups lets clear out the oldest
		if(count($existingBackups) > $maxbackups){
			SugarBackup::deleteOldestBackup($file, $existingBackups);
		}
	
		return copy($file , 'custom/backup/'.dirname($file).'/'. basename($file). '['. time() . ']');
	}
	
	function getBackupInfo($backupfile){
		$matches = array();
		preg_match_all("'custom\/backup\/([^.]+\.[a-zA-Z]+)\[([0-9]+)\]'", $backupfile, $matches);
		$result = array( 'path'=>dirname($backupfile), 'file'=>basename($backupfile), 'date'=>'','timestamp'=>0,'original_file'=>$backupfile );
		if(!empty($matches[1][0])){
			$result['original_file'] = $matches[1][0];
		}
		if(!empty($matches[2][0])){
			$result['date'] = $GLOBALS['timedate']->to_display_date_time(gmdate('Y-m-d H:i:s', $matches[2][0]));
			$result['timestamp'] = $matches[2][0];
		}
		return $result;
	}
	
	/**
	 * Retrieves a list of files that are backups to a given file
	 *
	 * @param unknown_type $file
	 * @return unknown
	 */
	function backupList($file){
		$newPath = create_custom_directory('backup/'.$file);
		$path = dirname($newPath);
		$d = dir($path);
		$files = array();
		while($entry = $d->read()){
			if(is_file($path . '/'. $entry) && substr_count($path. '/'. $entry, $file) ==1){
				$files[] = $path . '/'. $entry;
			}
		}
		arsort($files);
		return $files;
	}
	/**
	 * Flags a backup (this will make it non-deletable)
	 *
	 * @param unknown_type $file
	 * @param unknown_type $flag
	 */
	function flagBackup($file, $flag='Original'){
		
	}
	
	/**
	 * Restores a backup overwriting the current working copy
	 *
	 * @param unknown_type $file
	 * @param unknown_type $backupid
	 */
	function restoreBackup($backupfile){
		$info = $this->getBackupInfo($backupfile);
		$status = copy($backupfile, $info['original_file']);
		if($status){
			$wf = StudioParser::getWorkingFile($info['original_file'], true);
		}
		return $status;
		
	}
	
	
	/**
	 * reads the contents of a backup to a string and returns that string
	 *
	 * @param unknown_type $file
	 * @param unknown_type $backupid
	 */
	function readBackup($file, $backupid){
		
	}
	
	/**
	 * Deletes the oldest given backup
	 *
	 * @param unknown_type $file
	 */
	function deleteOldestBackup($file, $backups = false){
		
	}
	
	/**
	 * Deletes a backup
	 *
	 * @param unknown_type $file
	 * @param unknown_type $backupid
	 */
	function deleteBackup($backupfile){
		unlink($backupfile);
	}
	
	/**
	 * Purges all backups
	 *
	 * @param unknown_type $file
	 */
	function deleteAllBackups($file, $include_flagged=false){

	}
	
	
}

?>
