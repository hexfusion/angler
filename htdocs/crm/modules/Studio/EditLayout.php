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

 // $Id: EditLayout.php,v 1.11 2006/09/05 18:11:19 majed Exp $

require_once('modules/Studio/config.php');

/////HANDLE AJAX 
if(!empty($_REQUEST['ajax'])){
	ob_clean();
	require_once($GLOBALS['studio']['ajax'][$_REQUEST['ajax']]);
	sugar_cleanup(true);
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_TITLE'], $mod_strings['LBL_MODULE_TITLE'], true); 
echo "\n</p>\n";

$the_module = $_SESSION['studio']['module'];
require_once('modules/Studio/ajax/relatedfiles.php');
require_once('modules/Studio/parsers/StudioParser.php');
require_once('modules/Studio/StudioFields.php');
unset($_SESSION['studio']['lastWizard']);
$parsers = StudioParser::getParsers('');
$parser = $parsers['default'];
if(!empty($_REQUEST['parser'])){
	$parser = $_REQUEST['parser'];
	require_once($GLOBALS['studio']['parsers'][$_REQUEST['parser']]);
}else{
	require_once('modules/Studio/parsers/'.$parser . '.php');
}
$sp = new $parser();

require_once('modules/Studio/SugarBackup.php');
$files = $sp->getFiles($the_module);
if(empty( $_SESSION['studio']['selectedFileId'])){
    $keys = array_keys($files);
    $_SESSION['studio']['selectedFileId'] = $keys[0];
}
$studioDef = $files[$_SESSION['studio']['selectedFileId']];
$file = $studioDef['template_file'];
$file = StudioParser::getWorkingFile($file);
$the_class = $beanList[$the_module];
require_once($beanFiles[$the_class]);
$the_focus = new $the_class();

$type = $sp->getFileType($studioDef['type']);
$GLOBALS['layout_edit_mode'] = true;

$sp->loadFile($file);
$sp->parse($sp->curText);
$sp->workingModule = $the_module;
$sp->focus =& $the_focus;
$view =  $sp->prepSlots();

$xtpl = $sp->writeToCache($file, $view);

echo $sp->yahooJS();
echo $sp->getFormButtons();

//change mod strings to module 
$prev_mod = $mod_strings;
$mod_strings = return_module_language($current_language, $the_module);
include_once($xtpl);

echo $sp->getForm();
if($sp->fieldEditor){
    $sf = new StudioFields();
    $sf->module =& $the_focus;
    $sf->getExistingFields($view);
    echo $sf->addFields($type);
}
$my_list_strings = $GLOBALS['app_list_strings'];
foreach($my_list_strings as $key=>$value){
	if(is_string($value)){
		unset($my_list_strings[$key]);
	}
}

require_once('include/JSON.php');
$json = new JSON(JSON_LOOSE_TYPE);
echo '<script>var app_list_strings = ' . $json->encode($my_list_strings) . '</script>';




?>
