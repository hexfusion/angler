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

 // $Id: previewfile.php,v 1.6 2006/09/01 04:23:33 majed Exp $

//expects the following variables to be set (needs global scope)
//$file, $the_module
require_once('modules/Studio/parsers/StudioParser.php');
if(!isset($the_module))$the_module = $_SESSION['studio']['module'];
$files = StudioParser::getFiles($the_module);
$file = $files[$_SESSION['studio']['selectedFileId']]['template_file'];

$preview_file = false;

if( isset($_REQUEST['preview_file'])){
	$preview_file = 'custom/backup/'.$file.'['.$_REQUEST['preview_file'].']';
	if(!file_exists($preview_file)){
				echo 'no access';
				sugar_cleanup(true);
	}
}
require_once('modules/'. $the_module . '/Forms.php');

if(!isset($sp))$sp = new StudioParser();
if(!$preview_file){
$sp->loadFile($file);
}else{
	$sp->loadFile($preview_file);
}
$sp->workingModule = $the_module;
if(empty($preview_file)){
	$sp->parsePositions($sp->curText);
	$view =  $sp->prepYahooSlots();
	$preview_file = false;
}else{
	$view = $sp->curText;
}
require_once('themes/'.$theme.'/layout_utils.php');
$image_path = 'themes/'.$theme.'/images/';
$prev_mod = $mod_strings;
$mod_strings = return_module_language($current_language, $sp->workingModule);
$xtpl = $sp->writeToCache($file, $view, $preview_file);

include_once($xtpl);
$mod_strings = $prev_mod;




?>
