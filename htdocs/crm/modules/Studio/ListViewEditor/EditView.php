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

 // $Id: EditView.php,v 1.3 2006/08/22 23:50:15 majed Exp $

global $app_list_strings, $app_strings;
require_once('include/Sugar_Smarty.php');
require_once('modules/Studio/parsers/StudioParser.php');
require_once('modules/Studio/parsers/ListViewParser.php');
$lv = new ListViewParser();
$the_module = $_SESSION['studio']['module'];
require_once('modules/Studio/config.php');
require_once('modules/Studio/ajax/relatedfiles.php');
$lv->loadModule($the_module);


$smarty = new Sugar_Smarty();



$smarty->assign('MOD', $GLOBALS['mod_strings']);
$smarty->assign('title', $mod_strings['LBL_LISTVIEW_EDIT']. ':&nbsp;' . $app_list_strings['moduleList'][$the_module]);
$groups = array();

$groups[$mod_strings['LBL_DEFAULT']] = $lv->getDefaultFields();
$groups[$mod_strings['LBL_ADDITIONAL']] = $lv->getAdditionalFields();
$groups[$mod_strings['LBL_AVAILABLE']] = $lv->getAvailableFields();
$smarty->assign('translate',true);
$smarty->assign('module',$the_module);
$smarty->assign('groups',$groups);
$smarty->assign('description',  $mod_strings['LBL_LISTVIEW_DESCRIPTION']);
 global $image_path;
$imageSave = get_image($image_path. 'studio_save', '');
$imageHelp = get_image($image_path. 'help', '');
$buttons = array();
$buttons[] = array('image'=>$imageSave,'text'=>$GLOBALS['mod_strings']['LBL_BTN_SAVEPUBLISH'],'actionScript'=>"onclick='studiotabs.generateGroupForm(\"edittabs\");document.edittabs.submit()'");
//$buttons[] = array('image'=>$imageHelp,'text'=>'Help','actionScript'=>'title="' . $mod_strings['LBL_LISTVIEW_DESCRIPTION']. '"');
$buttonTxt = StudioParser::buildImageButtons($buttons);
$smarty->assign('buttons', $buttonTxt);
global $image_path;
$editImage = get_image($image_path . 'edit_inline', '');
$smarty->assign('editImage',$editImage);	
$deleteImage = get_image($image_path . 'delete_inline', '');
$smarty->assign('deleteImage',$deleteImage);

$smarty->display("modules/Studio/ListViewEditor/EditViewTop.tpl");
$smarty->display("modules/Studio/ListViewEditor/EditView.tpl");
?>
