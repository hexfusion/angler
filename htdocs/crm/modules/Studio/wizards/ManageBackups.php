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

 // $Id: ManageBackups.php,v 1.7 2006/09/01 04:23:33 majed Exp $

require_once('modules/Studio/config.php');
require_once('modules/Studio/SugarBackup.php');
require_once('modules/Studio/parsers/StudioParser.php');
class ManageBackups extends StudioWizard {
	var $tplfile = 'modules/Studio/wizards/tpls/backupmanager.tpl';
	var $wizard = 'ManageBackups';
	function ManageBackups(){
		$this->sb = new SugarBackup();
	}
	function welcome(){
		return $GLOBALS['mod_strings']['LBL_MB_WELCOME'];
	}
	function back(){
	    ob_clean();
	    header('LOCATION:index.php?action=wizard&module=Studio&wizard=SelectModuleAction');
	    sugar_cleanup(true);
	}
	
	function options(){
		
		$fileDef = StudioParser::getFiles($_SESSION['studio']['module'], $_SESSION['studio']['selectedFileId']);
		$list= $this->sb->backupList($fileDef['template_file']);
		$_SESSION['studio']['backupLastFile'] = $_REQUEST['setFile'];
		$options = array();
		foreach($list as $file){
			$info = $this->sb->getBackupInfo($file);
			$options[$info['timestamp']]=  $info['date'];
		}
	
		
		return $options;
	}
	
	
	function process($option){
		$_REQUEST['setFile'] = $_SESSION['studio']['backupLastFile'];
		$the_module = $_SESSION['studio']['module'];
		$files = StudioParser::getFiles($the_module);
			$file = $files[$_SESSION['studio']['selectedFileId']]['template_file'];
			$preview_file = 'custom/backup/'.$file.'['.$option.']';
		if(!empty($_GET['preview'])){
		
			$this->display();
		}else if(!file_exists($preview_file)){
				$this->display();
				return false;	
		} else if (!empty($_GET['delete'])){
			
			
			$this->sb->deleteBackup($preview_file);
			$this->display();
		}else if (!empty($_GET['restore'])){
			if($this->sb->restoreBackup($preview_file)){
				$this->status = 'Restored';
			}else{
				$this->status = ' Restore Failed';
			}
			$this->display();
			
		}
		
	}
	function display(){
		$_SESSION['studio']['lastWizard']  = 'ManageBackups';
		if(empty($_REQUEST['setFile'])){
			
				require_once('modules/Studio/wizards/SelectModuleLayout.php');
				$newWiz = new SelectModuleLayout();
				$newWiz->fileuse ='backupmanager';
				$newWiz->display();
		}else {
			
			parent::display();
		}
		
	}
	
}
?>
