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

 // $Id: EditView.php,v 1.6 2006/08/22 19:53:00 awu Exp $

global $app_list_strings, $app_strings;
require_once('include/Sugar_Smarty.php');
require_once('modules/Studio/DropDowns/DropDownHelper.php');
require_once('modules/Studio/parsers/StudioParser.php');
$dh = new DropDownHelper();
$dh->getDropDownModules();
$smarty = new Sugar_Smarty();
$smarty->assign('MOD', $GLOBALS['mod_strings']);
$selected_lang = (!empty($_REQUEST['dropdown_lang'])?$_REQUEST['dropdown_lang']:$_SESSION['authenticated_user_language']);
if(empty($selected_lang)){

    $selected_lang = $GLOBALS['sugar_config']['default_language'];
}
if($selected_lang == $GLOBALS['current_language']){
	$my_list_strings = $GLOBALS['app_list_strings'];
}else{
	$my_list_strings = return_app_list_strings_language($selected_lang);
}
foreach($my_list_strings as $key=>$value){
	if(!is_array($value)){
		unset($my_list_strings[$key]);
	}
}
$modules = array_keys($dh->modules);
$dropdown_modules = array(''=>$GLOBALS['mod_strings']['LBL_DD_ALL']);
foreach($modules as $module){
    $dropdown_modules[$module] = (!empty($app_list_strings['moduleList'][$module]))?$app_list_strings['moduleList'][$module]: $module;
}
$smarty->assign('dropdown_modules',$dropdown_modules);
if(!empty($_REQUEST['dropdown_module']) &&  !empty($dropdown_modules[$_REQUEST['dropdown_module']]) ){
   
    $smarty->assign('dropdown_module',$_REQUEST['dropdown_module']);
    $dropdowns = (!empty($dh->modules[$_REQUEST['dropdown_module']]))?$dh->modules[$_REQUEST['dropdown_module']]: array();
    foreach($dropdowns as $ok=>$dk){
        if(!isset($my_list_strings[$dk]) || !is_array($my_list_strings[$dk])){
            unset($dropdowns[$ok]);
 
        }
       
    }
  
   
}else{
     if(!empty($_REQUEST['dropdown_module'])){
        $smarty->assign('error', 'Module does not have any known dropdowns');
    }
    $dropdowns = array_keys($my_list_strings);
}
asort($dropdowns);
if(!empty($_REQUEST['newDropdown'])){
    $smarty->assign('newDropDown',true);
}else{
$keys = array_keys($dropdowns);
$first_string = $dropdowns[$keys[0]];
$smarty->assign('dropdowns',$dropdowns);
if(empty($_REQUEST['dropdown_name']) || !in_array($_REQUEST['dropdown_name'], $dropdowns)){
    $_REQUEST['dropdown_name'] = $first_string;
}
$selected_dropdown = $my_list_strings[$_REQUEST['dropdown_name']];

foreach($selected_dropdown as $key=>$value){
   if($selected_lang != $_SESSION['authenticated_user_language'] && !empty($app_list_strings[$_REQUEST['dropdown_name']]) && isset($app_list_strings[$_REQUEST['dropdown_name']][$key])){
        $selected_dropdown[$key]=array('lang'=>$value, 'user_lang'=> '['.$app_list_strings[$_REQUEST['dropdown_name']][$key] . ']');
   }else{
       $selected_dropdown[$key]=array('lang'=>$value);
   }
}
$smarty->assign('dropdown', $selected_dropdown);
$smarty->assign('dropdown_name',$_REQUEST['dropdown_name']);

}
$smarty->assign('dropdown_languages', unserialize($_SESSION['avail_languages']));

 global $image_path;
$imageSave = get_image($image_path. 'studio_save', '');
$imageUndo = get_image($image_path.'studio_undo', '');
$imageRedo = get_image($image_path.'studio_redo', '');
$buttons = array();
$buttons[] = array('image'=>$imageUndo,'text'=>'Undo','actionScript'=>"onclick='jstransaction.undo()'" );
$buttons[] = array('image'=>$imageRedo,'text'=>'Redo','actionScript'=>"onclick='jstransaction.redo()'" );
$buttons[] = array('image'=>$imageSave,'text'=>'Save','actionScript'=>"onclick='document.editdropdown.submit()'");
$buttonTxt = StudioParser::buildImageButtons($buttons);
$smarty->assign('buttons', $buttonTxt);
$smarty->assign('dropdown_lang', $selected_lang);
global $image_path;
$editImage = get_image($image_path . 'edit_inline', '');
$smarty->assign('editImage',$editImage);	
$deleteImage = get_image($image_path . 'delete_inline', '');
$smarty->assign('deleteImage',$deleteImage);	
$smarty->display("modules/Studio/DropDowns/EditView.tpl");
?>
