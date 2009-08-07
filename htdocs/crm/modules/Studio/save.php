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

 // $Id: save.php,v 1.5 2006/08/27 10:12:27 majed Exp $


$the_module = $_SESSION['studio']['module'];

require_once('modules/Studio/ajax/relatedfiles.php');
require_once('modules/Studio/parsers/StudioParser.php');
require_once('modules/Studio/StudioFields.php');
$fileDef = StudioParser::getFiles($the_module, $_SESSION['studio']['selectedFileId']);
$file = $fileDef['template_file'];
$file = StudioParser::getWorkingFile($file);




//Instantiate Bean
$the_class = $beanList[$the_module];
require_once($beanFiles[$the_class]);
$the_focus = new $the_class();
$parsers = StudioParser::getParsers($file);
$parser = $parsers['default'];

if(!empty($_REQUEST['parser'])){
	$parser = $_REQUEST['parser'];
	
}
require_once('modules/Studio/parsers/'.$parser . '.php');
$sp = new $parser();
$sp->loadFile($file);
$sp->workingModule = $the_module;
$sp->parse($sp->curText);
$sp->focus =& $the_focus;
$sp->handleSave();


//save changes to the labels
if($sp->labelEditor){
	StudioParser::handleSaveLabels($the_module,  $current_language);
}

//redirect
ob_clean();
header('Location:index.php?module=Studio&action=index&setLayout=true');
?>
