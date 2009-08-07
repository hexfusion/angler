<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
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
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Users/User.php');
require_once('modules/Users/UserSignature.php');
global $app_strings;
global $app_list_strings;
global $curent_language;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$mod_strings= return_module_language($current_language, $currentModule);
require_once($theme_path.'layout_utils.php');
$focus = new UserSignature();

if(isset($_REQUEST['record']) && !empty($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}


if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}
$GLOBALS['log']->info('EmailTemplate detail view');
///////////////////////////////////////////////////////////////////////////////
////	OUTPUT 
echo insert_popup_header($theme);
echo '<p>';
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_SIGNATURE'].' '.$focus->name, true); 
echo '</p>';

$xtpl = new XTemplate ('modules/Users/UserSignatureEditView.html');
$xtpl->assign('MOD', $mod_strings);
$xtpl->assign('APP', $app_strings);
	
$xtpl->assign('CANCEL_SCRIPT', 'window.close()');

if(isset($_REQUEST['return_module'])) $xtpl->assign('RETURN_MODULE', $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign('RETURN_ACTION', $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign('RETURN_ID', $_REQUEST['return_id']);
// handle Create $module then Cancel
if(empty($_REQUEST['return_id'])) {
	$xtpl->assign('RETURN_ACTION', 'index');
}
$xtpl->assign('INPOPUPWINDOW','true');
$xtpl->assign('THEME', $theme);
$xtpl->assign('IMAGE_PATH', $image_path);
$xtpl->assign('PRINT_URL', 'index.php?'.$GLOBALS['request_string']);
$xtpl->assign('JAVASCRIPT', get_set_focus_js());
$xtpl->assign('ID', $focus->id);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign('SIGNATURE_TEXT', $focus->signature);

if(file_exists('include/FCKeditor/fckeditor.php')) {
	include('include/FCKeditor_Sugar/FCKeditor_Sugar.php') ;
	ob_start();
	$instancename='body_html';
	$oFCKeditor = new FCKeditor_Sugar($instancename) ;
	if(!empty($focus->signature_html)) {
		$oFCKeditor->Value = $focus->signature_html ;
	}
	$oFCKeditor->Height = '220';
	$oFCKeditor->Create() ;
	$htmlarea_src =  ob_get_contents();
	$xtpl->assign('HTML_EDITOR',$htmlarea_src);
	$xtpl->parse('main.htmlarea');
	ob_end_clean();
	
	echo <<<EOQ
	  <SCRIPT>
	  function insert_variable_html(text) {
	  	var oEditor = FCKeditorAPI.GetInstance('{$instancename}') ;
	  	oEditor.InsertHtml(text);
	  }
	  </SCRIPT>
EOQ;
	$xtpl->assign('INSERT_VARIABLE_ONCLICK', 'insert_variable_html(document.EditView.variable_text.value)');
	$xtpl->parse('main.varibale_button');
} else {
	$xtpl->parse('main.textarea');
}


//Add Custom Fields
$xtpl->parse('main');
$xtpl->out('main');
?>
