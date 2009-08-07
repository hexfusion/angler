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
 * $Id: ListView.php,v 1.3 2006/07/30 03:16:09 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/SavedSearch/SavedSearch.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('log4php/LoggerManager.php');
require_once('include/ListView/ListViewSmarty.php');
require_once('include/QuickSearchDefaults.php');

global $app_strings, $app_list_strings, $current_language, $currentModule, $mod_strings;

$qsd = new QuickSearchDefaults();
echo $qsd->GetQSScripts();

echo get_module_title('SavedSearch', $mod_strings['LBL_MODULE_TITLE'], false);
echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], '', false);

$search_form = new XTemplate ('modules/SavedSearch/SearchForm.html');
$search_form->assign('MOD', $mod_strings);
$search_form->assign('APP', $app_strings);
$search_form->assign('JAVASCRIPT', get_clear_form_js());

if (isset($_REQUEST['name'])) $search_form->assign('name', to_html($_REQUEST['name']));
if (isset($_REQUEST['search_module'])) $search_form->assign('search_module', to_html($_REQUEST['search_module']));
	
$search_form->parse('main');
$search_form->out('main');

if (!isset($where)) $where = "assigned_user_id = {$current_user->id}";


echo '<br />' .get_form_header($mod_strings['LBL_LIST_FORM_TITLE'], '', false);

$savedSearch = new SavedSearch();
$lv = new ListViewSmarty();
if(file_exists('custom/modules/SavedSearch/metadata/listviewdefs.php')){
	require_once('custom/modules/SavedSearch/metadata/listviewdefs.php');	
}else{
	require_once('modules/SavedSearch/metadata/listviewdefs.php');
}

$lv->displayColumns = $listViewDefs['SavedSearch'];
$lv->setup($savedSearch, 'include/ListView/ListViewGeneric.tpl', $where);
$lv->display(true);
?>
