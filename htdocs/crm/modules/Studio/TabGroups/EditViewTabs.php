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

 // $Id: EditViewTabs.php,v 1.4 2006/08/22 23:50:15 majed Exp $

global $app_list_strings, $app_strings;
require_once('include/Sugar_Smarty.php');
require_once('modules/Studio/TabGroups/TabGroupHelper.php');
require_once('modules/Studio/parsers/StudioParser.php');

$tg = new TabGroupHelper();
$smarty = new Sugar_Smarty();
if(empty($GLOBALS['tabStructure'])){
	require_once('include/tabConfig.php');	
}

$smarty->assign('tabs', $GLOBALS['tabStructure']);
$smarty->assign('MOD', $GLOBALS['mod_strings']);
$selected_lang = (!empty($_REQUEST['dropdown_lang'])?$_REQUEST['dropdown_lang']:$_SESSION['authenticated_user_language']);
if(empty($selected_lang)){
    $selected_lang = $GLOBALS['sugar_config']['default_language'];
}

$availableModules = $tg->getAvailableModules();
$smarty->assign('availableModuleList',$availableModules);

$smarty->assign('dropdown_languages', unserialize($_SESSION['avail_languages']));

 global $image_path;
$imageSave = get_image($image_path. 'studio_save', '');

$buttons = array();
$buttons[] = array('image'=>$imageSave,'text'=>$GLOBALS['mod_strings']['LBL_BTN_SAVEPUBLISH'],'actionScript'=>"onclick='studiotabs.generateForm(\"edittabs\");document.edittabs.submit()'");
$buttonTxt = StudioParser::buildImageButtons($buttons);
$smarty->assign('buttons', $buttonTxt);
$smarty->assign('dropdown_lang', $selected_lang);
global $image_path;
$editImage = get_image($image_path . 'edit_inline', '');
$smarty->assign('editImage',$editImage);	
$deleteImage = get_image($image_path . 'delete_inline', '');
$smarty->assign('deleteImage',$deleteImage);	
$smarty->display("modules/Studio/TabGroups/EditViewTabs.tpl");
?>
