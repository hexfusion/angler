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

 // $Id: EditSubpanel.php,v 1.4 2006/09/05 18:11:19 majed Exp $

require_once('modules/Studio/config.php');
$the_module = $_SESSION['studio']['module'];
require_once('modules/Studio/ajax/relatedfiles.php');
require_once('modules/Studio/parsers/SubpanelParser.php');
require_once('modules/Studio/StudioFields.php');

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_TITLE'], $mod_strings['LBL_MODULE_TITLE'], true); 
echo "\n</p>\n";

$GLOBALS['layout_edit_mode'] = true;
$parser = 'SubpanelParser';
if(!empty($_REQUEST['parser'])){
	$parser = $_REQUEST['parser'];
	require_once($GLOBALS['studio']['parsers'][$_REQUEST['parser']]);
}else{
	require_once('modules/Studio/parsers/'.$parser . '.php');
}
$sp = new $parser();
$sp->loadSubpanel($the_module, $_REQUEST['subpanel']);
$sp->parse($sp->curText);
$view =  $sp->prepSlots();
echo $sp->yahooJS();
echo $sp->getFormButtons();
echo $view;
echo $sp->getForm();
$submodulename = $sp->panel->_instance_properties['module'];
$submoduleclass = $beanList[$submodulename];
require_once($beanFiles[$submoduleclass]);
$subbean = new $submoduleclass();
if($sp->fieldEditor){
    $sf = new StudioFields();
    $sf->module =& $subbean;
    $sf->getExistingFields($view);
    echo $sf->addFields('detail');
}




?>
