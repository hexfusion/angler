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
require_once("data/Tracker.php");
require_once('modules/Opportunities/Opportunity.php');
require_once('include/utils.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/ListView/ListViewSmarty.php');
if(file_exists('custom/modules/Opportunities/metadata/listviewdefs.php')){
	require_once('custom/modules/Opportunities/metadata/listviewdefs.php');	
}else{
	require_once('modules/Opportunities/metadata/listviewdefs.php');
}
require_once('modules/SavedSearch/SavedSearch.php');
require_once('include/SearchForm/SearchForm.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Opportunities');

global $urlPrefix;


global $currentModule;

global $theme;

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();

$json = getJSONobj();

$seedOpportunity = new Opportunity();
$searchForm = new SearchForm('Opportunities', $seedOpportunity);

// setup listview smarty
$lv = new ListViewSmarty();

$displayColumns = array();
if(!empty($_REQUEST['displayColumns'])) {
    foreach(explode('|', $_REQUEST['displayColumns']) as $num => $col) {
        if(!empty($listViewDefs['Opportunities'][$col])) 
            $displayColumns[$col] = $listViewDefs['Opportunities'][$col];
    }    
}
else {
    foreach($listViewDefs['Opportunities'] as $col => $params) {
        if(!empty($params['default']) && $params['default'])
            $displayColumns[$col] = $params;
    }
} 
$params = array('massupdate' => true);
if(!empty($_REQUEST['orderBy'])) {
    $params['orderBy'] = $_REQUEST['orderBy'];
    $params['overrideOrder'] = true;
    if(!empty($_REQUEST['sortOrder'])) $params['sortOrder'] = $_REQUEST['sortOrder'];
}

$lv->displayColumns = $displayColumns;

if(!empty($_REQUEST['search_form_only']) && $_REQUEST['search_form_only']) {
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

if (!isset($where)) $where = "";
require_once('modules/MySettings/StoreQuery.php');
$storeQuery = new StoreQuery();
if($_REQUEST['action'] == 'index')
{
	if(!isset($_REQUEST['query'])){
		$storeQuery->loadQuery($currentModule);
		$storeQuery->populateRequest();
	}else{
		$storeQuery->saveFromGet($currentModule);	
	}
}


if(isset($_REQUEST['query']))
{
    // we have a query
    $searchForm->populateFromRequest();
    if(!empty($_REQUEST['date_closed']) && !empty($_REQUEST['date_start'])) { // this is to handle dashboard queries
        $whereAdditional = "opportunities.date_closed >= '".PearDatabase::quote($_REQUEST['date_start'])."' and opportunities.date_closed <= '".PearDatabase::quote($_REQUEST['date_closed'])."'";
        if(isset($searchForm->searchFields['date_closed'])) unset($searchForm->searchFields['date_closed']);
    }
    $where_clauses = $searchForm->generateSearchWhere($_REQUEST, true, "Opportunities");
    if(isset($whereAdditional)) array_push($where_clauses, $whereAdditional);
    $where = "";
    if (count($where_clauses) > 0 ) $where = implode(' and ', $where_clauses);
    $GLOBALS['log']->info("Here is the where clause for the list view: $where");
}

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
$lv->setup($seedOpportunity, 'include/ListView/ListViewGeneric.tpl', $where, $params);


$savedSearchName = empty($_REQUEST['saved_search_select_name']) ? '' : (' - ' . $_REQUEST['saved_search_select_name']);
echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'] . $savedSearchName, '', false);
echo $lv->display();

$savedSearch = new SavedSearch();
$json = getJSONobj();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Opportunities')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
echo $str;


?>
