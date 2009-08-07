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
 * Description:
 * Created On: Sep 28, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/
require_once('XTemplate/xtpl.php');
require_once('include/ListView/ListView.php');
require_once('modules/Schedulers/Scheduler.php');

$header_text = '';
global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Schedulers');

$focus = new Scheduler();
$focus->checkCurl();

$where = '';
$limit = '0';
$varName = $focus->object_name; //'Scheduler'
if(!empty($_REQUEST['Schedulers_SCHEDULER_ORDER_BY'])) {
	$orderBy = $_REQUEST['Schedulers_SCHEDULER_ORDER_BY'];
} else {
	$orderBy = $focus->order_by;
}
$allowByOverride = true;

$listView = new ListView();
$listView->show_mass_update = false;  // don't want to mass delete all Schedules
$listView->initNewXTemplate('modules/Schedulers/ListView.html', $current_module_strings);
$listView->setHeaderTitle($current_module_strings['LBL_LIST_TITLE']);
$listView->setQuery($where, $limit, $orderBy, $varName, $allowByOverride);
$listView->xTemplateAssign("REMOVE_INLINE_PNG", get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_REMOVE'].'" border="0"'));
$listView->processListView($focus, "main", "SCHEDULER");

$focus->displayCronInstructions(); 
?>
