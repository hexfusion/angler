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

 // $Id: EditView.php,v 1.7 2006/09/05 19:07:11 jenny Exp $

require_once('modules/EditCustomFields/FieldsMetaData.php');

global $app_list_strings, $app_strings, $current_language;
if(empty($_REQUEST['custom_module']))$_REQUEST['custom_module'] = $_SESSION['studio']['module'];
require_once('include/Sugar_Smarty.php');
$custom_field_types = array('varchar' => 'Text', 'text' => 'Text Area','int' => 'Integer', 'float' => 'Decimal',
	 'bool' => 'Checkbox','email'=>'Email', 'enum' => 'Dropdown', 'multienum'=>'Multiple Select', 'radioenum'=>'Radio Buttons', 'date' => 'Date', 'url'=>'Web Link', 'html'=>'HTML');
$smarty = new Sugar_Smarty();
$cf = new FieldsMetaData();
if(!empty($_REQUEST['record'])){
    $cf->retrieve($_REQUEST['record']);
    $custom_module = $cf->custom_module;
    $smarty->assign('NOEDIT','diabled' );
    $old = ob_get_contents();
    ob_clean();
    
require_once($GLOBALS['studio']['dynamicFields'][$cf->data_type]);
    $body = ob_get_contents();
    ob_clean();
    echo $old;
    $smarty->assign('body',$body );
   
}else{
  $custom_module = $_REQUEST['custom_module'];  
}
$smarty->assign('custom_module', $custom_module);

if(empty($_REQUEST['popup'])){
    $smarty->assign('inline', 'inline'); 
    $smarty->assign('popup', '0');  
}else{
    $smarty->assign('popup', '1');
}
$smarty->assign('module_name', $custom_module);
$smarty->assign('cf', $cf );
$smarty->assign('APP', $app_strings);
$smarty->assign('custom_field_types', $custom_field_types);
$edit_mod_strings = return_module_language($current_language, 'EditCustomFields');
$smarty->assign('MOD', $edit_mod_strings);
$my_list_strings = $app_list_strings;
foreach($my_list_strings as $key=>$value){
	if(is_string($value)){
		unset($my_list_strings[$key]);
	}
}
$dropdowns = array_keys($my_list_strings);
sort($dropdowns);
require_once('include/JSON.php');
$json = new JSON(JSON_LOOSE_TYPE);


$smarty->assign('app_list_strings', $json->encode($my_list_strings));
$smarty->display('modules/Studio/EditCustomFields/EditCustomFields.tpl');


?>
