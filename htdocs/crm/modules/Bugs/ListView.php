<?php
if(!defined('sugarEntry') || !sugarEntry)
	die('Not A Valid Entry Point');
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
 * $Id: ListView.php,v 1.45 2006/08/04 01:04:54 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once ('XTemplate/xtpl.php');
require_once ("data/Tracker.php");
require_once ('modules/Bugs/Bug.php');
require_once ('themes/'.$theme.'/layout_utils.php');
require_once ('log4php/LoggerManager.php');
require_once('include/ListView/ListViewSmarty.php');
require_once ("modules/Releases/Release.php");
require_once('modules/Currencies/Currency.php');
if(file_exists('custom/modules/Bugs/metadata/listviewdefs.php')){
	require_once('custom/modules/Bugs/metadata/listviewdefs.php');	
}else{
	require_once('modules/Bugs/metadata/listviewdefs.php');
}
require_once('modules/SavedSearch/SavedSearch.php');
require_once('include/SearchForm/SearchForm.php');


$header_text = '';

global $app_strings, $mod_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Bugs');

global $urlPrefix;
global $currentModule;

global $theme;
global $current_user;
// focus_list is the means of passing data to a ListView.
global $focus_list;

// setup quicksearch
require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();

// clear the display columns back to default when clear query is called
if(!empty($_REQUEST['clear_query']) && $_REQUEST['clear_query'] == 'true')  
    $current_user->setPreference('ListViewDisplayColumns', array(), 0, $currentModule);

$savedDisplayColumns = $current_user->getPreference('ListViewDisplayColumns', $currentModule); // get user defined display columns

$json = getJSONobj();

$seedRelease = new Release();
$bug_release_dom = $seedRelease->get_releases(false, "Active");
$GLOBALS['app_list_strings']['bug_release_dom'] = $bug_release_dom;

$seedBug = new Bug(); // seed bean
$searchForm = new SearchForm('Bugs', $seedBug); // new searchform instance

// setup listview smarty
$lv = new ListViewSmarty();

$displayColumns = array();
// check $_REQUEST if new display columns from post
if(!empty($_REQUEST['displayColumns'])) {
    foreach(explode('|', $_REQUEST['displayColumns']) as $num => $col) {
        if(!empty($listViewDefs['Bugs'][$col])) 
            $displayColumns[$col] = $listViewDefs['Bugs'][$col];
    }    
}
elseif(!empty($savedDisplayColumns)) { // use user defined display columns from preferences 
    $displayColumns = $savedDisplayColumns;
}
else { // use columns defined in listviewdefs for default display columns
    foreach($listViewDefs['Bugs'] as $col => $params) {
        if(!empty($params['default']) && $params['default'])
            $displayColumns[$col] = $params;
    }
} 
$params = array('massupdate' => true); // setup ListViewSmarty params
if(!empty($_REQUEST['orderBy'])) { // order by coming from $_REQUEST
    $params['orderBy'] = $_REQUEST['orderBy'];
    $params['overrideOrder'] = true;
    if(!empty($_REQUEST['sortOrder'])) $params['sortOrder'] = $_REQUEST['sortOrder'];
}

$lv->displayColumns = $displayColumns;

if(!empty($_REQUEST['search_form_only']) && $_REQUEST['search_form_only']) { // handle ajax requests for search forms only
    switch($_REQUEST['search_form_view']) {
        case 'basic_search':
            $searchForm->setup();
            $searchForm->displayBasic(false);
            break;
        case 'advanced_search':
            $searchForm->setup();
            $searchForm->displayAdvanced(false);
            break;
        case 'saved_views':
            echo $searchForm->displaySavedViews($listViewDefs, $lv, false);
            break;
    }
    return;
}

// use the stored query if there is one
if (!isset($where)) $where = "";
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if(!isset($_REQUEST['query'])){
    $storeQuery->loadQuery($currentModule);
    $storeQuery->populateRequest();
}else{
    $storeQuery->saveFromGet($currentModule);   
}
if(isset($_REQUEST['query']))
{
    // we have a query
    // first save columns 
    $current_user->setPreference('ListViewDisplayColumns', $displayColumns, 0, $currentModule); 
    $searchForm->populateFromRequest(); // gathers search field inputs from $_REQUEST
    $where_clauses = $searchForm->generateSearchWhere(true, "Bugs"); // builds the where clause from search field inputs

    if (count($where_clauses) > 0 )$where = implode(' and ', $where_clauses);
    $GLOBALS['log']->info("Here is the where clause for the list view: $where");
}

// start display
// which tab of search form to display
if(!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
    $searchForm->setup();
    if(isset($_REQUEST['searchFormTab']) && $_REQUEST['searchFormTab'] == 'advanced_search') {
        $searchForm->displayAdvanced();
    }
    elseif(isset($_REQUEST['searchFormTab']) && $_REQUEST['searchFormTab'] == 'saved_views'){
        $searchForm->displaySavedViews($listViewDefs, $lv);
    }
    else {
        $searchForm->displayBasic();
    }
}

echo $qsd->GetQSScripts();

$lv->setup($seedBug, 'include/ListView/ListViewGeneric.tpl', $where, $params);

$savedSearchName = empty($_REQUEST['saved_search_select_name']) ? '' : (' - ' . $_REQUEST['saved_search_select_name']);
echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'] . $savedSearchName, '', false);
echo $lv->display();

$savedSearch = new SavedSearch();
$json = getJSONobj();
// fills in saved views select box on shortcut menu
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Bugs')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;
?>

