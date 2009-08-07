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
 *********************************************************************************/
 

function additionalDetailsEmail($fields) {
	global $current_language;
	$mod_strings = return_module_language($current_language, 'Emails');
	$newLines = array("\r", "\R", "\n", "\N");
		
	$overlib_string = '';
	// From Name
	if(!empty($fields['FROM_NAME'])) {
		$overlib_string .= '<b>' . $mod_strings['LBL_FROM'] . '</b>&nbsp;';
		$overlib_string .= $fields['FROM_NAME'];
	}

	// email text
	if(!empty($fields['DESCRIPTION_HTML'])) {
		if(!empty($overlib_string)) $overlib_string .= '<br>';
		$overlib_string .= '<b>'.$mod_strings['LBL_BODY'].'</b><br>';
		$descH = strip_tags($fields['DESCRIPTION_HTML'], '<a>');
		$desc = str_replace($newLines, ' ', $descH);
		$overlib_string .= substr($desc, 0, 300);
		if(strlen($descH) > 300) $overlib_string .= '...';
	} elseif (!empty($fields['DESCRIPTION'])) {
		if(!empty($overlib_string)) $overlib_string .= '<br>';
		$overlib_string .= '<b>'.$mod_strings['LBL_BODY'].'</b><br>';
		$descH = strip_tags(nl2br($fields['DESCRIPTION']));
		$desc = str_replace($newLines, ' ', $descH);
		$overlib_string .= substr($desc, 0, 300);
		if(strlen($descH) > 300) $overlib_string .= '...';
	}
	
	$editLink = "index.php?action=EditView&module=Emails&record={$fields['ID']}"; 
	$viewLink = "index.php?action=DetailView&module=Emails&record={$fields['ID']}";	

	$return_module = empty($_REQUEST['module']) ? 'Meetings' : $_REQUEST['module'];
	$return_action = empty($_REQUEST['action']) ? 'ListView' : $_REQUEST['action'];
	$type = empty($_REQUEST['type']) ? '' : $_REQUEST['type'];
	$user_id = empty($_REQUEST['assigned_user_id']) ? '' : $_REQUEST['assigned_user_id'];
	
	$additional_params = "&return_module=$return_module&return_action=$return_action&type=$type&assigned_user_id=$user_id"; 
	
	$editLink .= $additional_params;
	$viewLink .= $additional_params;
	
	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => $editLink, 
				 'viewLink' => $viewLink);
}
 
 ?>
 
