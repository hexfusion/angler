<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Forms
 *
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

// $Id: Forms.php,v 1.21 2006/06/06 17:58:32 majed Exp $

global $theme;
require_once('themes/'.$theme.'/layout_utils.php');

require_once('XTemplate/xtpl.php');
require_once('include/utils.php');

$image_path = 'themes/'.$theme.'/images/';

function get_new_record_form()
{
	if(!ACLController::checkAccess('Project', 'edit', true))return '';
	global $app_strings;
	global $mod_strings;
	global $currentModule;
	global $current_user;

	$the_form = get_left_form_header($mod_strings['LBL_QUICK_NEW_PROJECT']);
	$form = new XTemplate ('modules/Project/Forms.html');

	$module_select = empty($_REQUEST['module_select']) ? ''
		: $_REQUEST['module_select'];
	$form->assign('mod', $mod_strings);
	$form->assign('app', $app_strings);
	$form->assign('module', $currentModule);

	$json = getJSONobj();
	$popup_request_data = array(
		'call_back_function' => 'set_return',
		'form_name' => 'quick_save',
		'field_to_name_array' => array(
			'id' => 'assigned_user_id',
			'user_name' => 'assigned_user_name',
			),
		);
	$form->assign('encoded_users_popup_request_data', $json->encode($popup_request_data));
	
	$form->parse('main');
	$the_form .= $form->text('main');
	
	require_once('modules/Project/Project.php');
   $focus = new Project();
   
   require_once('include/QuickSearchDefaults.php');
	$qsd = new QuickSearchDefaults();
	$sqs_objects = array('assigned_user_name' => $qsd->getQSUser());
	$quicksearch_js = $qsd->getQSScripts();
	$quicksearch_js .= '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';
	echo $quicksearch_js;



   require_once('include/javascript/javascript.php');
   $javascript = new javascript();
   $javascript->setFormName('quick_save');
   $javascript->setSugarBean($focus);
   $javascript->addRequiredFields('');
   $javascript->addToValidateBinaryDependency('assigned_user_name', 'alpha', $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'], 'false', '', 'assigned_user_id');
   $jscript = $javascript->getScript();

   $the_form .= $jscript . get_left_form_footer();

	return $the_form;
}

/**
 * Create javascript to validate the data entered into a record.
 */
function get_validate_record_js ()
{
	return '';
}

?>
