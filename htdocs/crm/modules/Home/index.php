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

global $current_user, $sugar_version, $sugar_config, $image_path;

require_once('include/Sugar_Smarty.php');

// build dashlet cache file if not found
if(!is_file('cache/dashlets/dashlets.php')) {
    require_once('include/Dashlets/DashletCacheBuilder.php');
    
    $dc = new DashletCacheBuilder();
    $dc->buildCache();
}
require_once('cache/dashlets/dashlets.php');

$columns = $current_user->getPreference('columns', 'home'); 
$dashlets = $current_user->getPreference('dashlets', 'home');

// fill in with default dashlet selection
if(!isset($columns) || !isset($dashlets)) {
    $dashlets = array();

    $defaultDashlets = array('MyCallsDashlet', 'JotPadDashlet', 'MyMeetingsDashlet', 'MyCasesDashlet', 'MyLeadsDashlet',



        'MyOpportunitiesDashlet', 'MyPipelineBySalesStageDashlet', 'MyAccountsDashlet');
    
    foreach($defaultDashlets as $dashletName) {
        $dashlets[create_guid()] = array('className' => $dashletName, 
                                         'fileLocation' => $dashletsFiles[$dashletName]['file']);
    }
    
    $count = 0;
    $columns = array();
    $columns[0] = array();
    $columns[0]['width'] = '60%';
    $columns[0]['dashlets'] = array();
    $columns[1] = array();
    $columns[1]['width'] = '40%';
    $columns[1]['dashlets'] = array();
    foreach($dashlets as $guid=>$dashlet) {
        if($count % 2 == 0) array_push($columns[0]['dashlets'], $guid); 
        else array_push($columns[1]['dashlets'], $guid);
        $count++;
    }
    $current_user->setPreference('dashlets', $dashlets, 0, 'home');
    $current_user->setPreference('columns', $columns, 0, 'home');
}

$count = 0;
//$columns = array(0 => array('dashlets' => array(), 'width' => '60%'), 1 => array('dashlets' => array(), 'width' => '40%'));
$dashletIds = array(); // collect ids to pass to javascript
$display = array();

foreach($columns as $colNum => $column) {
    $display[$colNum]['width'] = $column['width'];
    $display[$colNum]['dashlets'] = array(); 
    foreach($column['dashlets'] as $num => $id) {
        if(!empty($id) && is_file($dashlets[$id]['fileLocation'])) {
            require_once($dashlets[$id]['fileLocation']);
            $dashlet = new $dashlets[$id]['className']($id, (isset($dashlets[$id]['options']) ? $dashlets[$id]['options'] : array()));
        
            array_push($dashletIds, $id);

            $dashlet->process();
            $display[$colNum]['dashlets'][$id]['display'] = $dashlet->display();
            if($dashlet->hasScript) {
                $display[$colNum]['dashlets'][$id]['script'] = $dashlet->displayScript();
            }
        }
    }
}

$sugar_smarty = new Sugar_Smarty();
if(!empty($sugar_config['lock_homepage']) && $sugar_config['lock_homepage'] == true) $sugar_smarty->assign('lock_homepage', true);  
$sugar_smarty->assign('sugarVersion', $sugar_version);
$sugar_smarty->assign('sugarFlavor', $sugar_flavor);
$sugar_smarty->assign('currentLanguage', $GLOBALS['current_language']);
$sugar_smarty->assign('serverUniqueKey', $GLOBALS['server_unique_key']);
$sugar_smarty->assign('imagePath', $GLOBALS['image_path']);

$sugar_smarty->assign('jsCustomVersion', $sugar_config['js_custom_version']);
$sugar_smarty->assign('maxCount', empty($sugar_config['max_dashlets_homepage']) ? 15 : $sugar_config['max_dashlets_homepage']);
$sugar_smarty->assign('dashletCount', $count);
$sugar_smarty->assign('dashletIds', '["' . implode('","', $dashletIds) . '"]');
$sugar_smarty->assign('columns', $display);
$sugar_smarty->assign('lblAddDashlets', $mod_strings['LBL_ADD_DASHLETS']);
$sugar_smarty->assign('lblLnkHelp', $GLOBALS['app_strings']['LNK_HELP']);
echo $sugar_smarty->fetch('modules/Home/Home.tpl');

?>
