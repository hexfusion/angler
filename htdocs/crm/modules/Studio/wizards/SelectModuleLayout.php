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

 // $Id: SelectModuleLayout.php,v 1.6 2006/08/27 10:33:09 majed Exp $

class SelectModuleLayout extends StudioWizard {
	var $fileuse = 'edit';
	var $wizard = 'SelectModuleLayout';
	function welcome(){
		if($this->fileuse =='backupmanager'){
			$_SESSION['studio']['lastWizard']='ManageBackups';
			return $GLOBALS['mod_strings']['LBL_SM_WELCOME'];
		}
		return '<h2>Welcome to Studio!</h2>Select the file you would like to edit<br>';
	}
	function back(){
	    ob_clean();
	    header('LOCATION:index.php?action=wizard&module=Studio&wizard=SelectModuleAction');
	    sugar_cleanup(true);
	}
	function options(){
		$the_module = $_SESSION['studio']['module'];
		require_once('modules/Studio/parsers/StudioParser.php');
		$moduleFiles = StudioParser::getFiles($the_module);
		$files = array();
		foreach( $moduleFiles as $key=>$value){
			$files[$key] = translate($key, $the_module);
		}
		return $files;
	}
	
	function process($option){
		if(!empty($_SESSION['studio']['lastWizard']) && $_SESSION['studio']['lastWizard'] == 'ManageBackups'){
			header("LOCATION:index.php?module=Studio&action=wizard&wizard=ManageBackups&setFile=". $option);
			sugar_exit();
		
		}
			$the_module = $_SESSION['studio']['module'];
			require_once('modules/Studio/parsers/StudioParser.php');
			$fileDef  = StudioParser::getFiles($the_module, $option);
			$special = '';
			if($fileDef['type'] == 'ListView'){
				$special = '&listview=true';
			}
			$_SESSION['studio']['selectedFileId'] = $option;
			header("LOCATION:index.php?module=Studio&action=index&setLayout=true$special");
			sugar_exit();
	}
	
}
?>
