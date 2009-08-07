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
/*********************************************************************************
 * $Id: commitJson.php,v 1.3 2006/08/12 00:22:49 chris Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

if(ob_get_level() < 1)
	ob_start();
ob_implicit_flush(1);

if(!function_exists('getFilesForPermsCheck')) {
	require_once('modules/UpgradeWizard/uw_utils.php');	
}
if(!isset($sugar_config) || empty($sugar_config)) {
	require_once('config.php');	
}
// persistence
$persistence = getPersistence();

switch($_REQUEST['commitStep']) {
	case 'run_sql':
		ob_end_flush();
		logThis('commitJson->runSql() called.');
		$persistence = commitAjaxRunSql($persistence);
	break;

	case 'get_errors':
		logThis('commitJson->getErrors() called.');
		commitAjaxGetSqlErrors($persistence);
	break;
	
	case 'post_install':
		logThis('commitJson->postInstall() called.');
		commitAjaxPostInstall($persistence);
	break;
	
	case 'final_touches':
		logThis('commitJson->finalTouches() called.');
		$persistence = commitAjaxFinalTouches($persistence);
	break;	
}

savePersistence($persistence);
?>
