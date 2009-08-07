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

 // $Id: html.php,v 1.3 2006/08/22 19:31:20 awu Exp $

require_once('modules/DynamicFields/templates/Fields/Forms/setupform.php');
$edit_mod_strings = return_module_language($current_language, 'EditCustomFields');
$smartyForm->assign('MOD', $edit_mod_strings);
if(!empty($cf))$smartyForm->assign('cf', $cf);
if(file_exists('include/FCKeditor/fckeditor.php')) {
	include('include/FCKeditor_Sugar/FCKeditor_Sugar.php') ;
	$oldcontents = ob_get_contents();
	ob_clean();
		$instancename = 'htmlcode';
		$oFCKeditor = new FCKeditor_Sugar($instancename) ;
		if(!empty($cf->ext4)) {
			$oFCKeditor->Value = $cf->ext4;
		}
		$oFCKeditor->ToolbarSet = 'Light';
		$oFCKeditor->Height = 200;
		$oFCKeditor->Width =300;
		$oFCKeditor->Create() ;
		$htmlarea_src = ob_get_contents();
		$htmlarea_src = str_replace(array("\r\n", "\n"), " ",$htmlarea_src);
		$smartyForm->assign('HTML_EDITOR', $htmlarea_src);
	ob_clean();
	echo $oldcontents;
}
$smartyForm->display('modules/DynamicFields/templates/Fields/Forms/html.tpl')

?>
