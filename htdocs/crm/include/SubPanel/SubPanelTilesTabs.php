<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SubPanelTiles
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
// $Id: SubPanelTilesTabs.php,v 1.7 2006/08/21 23:28:22 jbenterou Exp $

require_once('include/SubPanel/SubPanel.php');
require_once('include/SubPanel/SubPanelDefinitions.php');
require_once('include/SubPanel/SubPanelTiles.php');
class SubPanelTilesTabs extends SubPanelTiles
{
	
	function SubPanelTiles(&$focus, $layout_def_key='', $layout_def_override = '')
	{
	    
		$this->focus = $focus;
		$this->id = $focus->id;
		$this->module = $focus->module_dir;
		$this->layout_def_key = $layout_def_key;
		$this->subpanel_definitions = new SubPanelDefinitions($focus, $layout_def_key, $layout_def_override);
	}
    
    function getSubpanelGroupLayout($selectedGroup)
    {
        global $current_user;
        
        $layoutParams = $this->module;
        if($selectedGroup != 'All')
        {
            $layoutParams .= ':'.$selectedGroup;
        }
        
        // see if user current user has custom subpanel layout
        return $current_user->getPreference('subpanelLayout', $layoutParams);
    }
    
    function applyUserCustomLayoutToTabs($tabs, $key='All')
    {
        $usersCustomLayout = SubPanelTilesTabs::getSubpanelGroupLayout($key);
        if(!empty($usersCustomLayout))
        {
            /* Return elements of the custom layout
             * which occur in $tabs in unchanged order.
             * Then append elements of $tabs which are
             * not included in the layout. */
            $diff = array_diff($tabs, $usersCustomLayout);
            $tabs = array_intersect($usersCustomLayout, $tabs);
            foreach($diff as $subpanel)
            {
            	$tabs []= $subpanel;
            }
        }
        
        return $tabs;
    }
    
	function getTabs($tabs, $showTabs = true, $selectedGroup='All')
    {
	    //$groups = array('Tasks'=> array('activities','history', 'projects' ), 'People'=>array('contacts','leads', 'campaigns'),'Sales'=>array('opportunities', 'quotes', 'campaigns'), 'Support'=>array('cases','bugs' ), );

        require_once('include/GroupedTabs/GroupedTabStructure.php');
        
        $groupedTabsClass = new GroupedTabStructure();
        
        $groups = $groupedTabsClass->get_tab_structure($tabs);
        
        
        /* Move history to same tab as activities */
        if(in_array('history', $tabs) && in_array('activities', $tabs))
        {
            foreach($groups as $mainTab => $group)
            {
            	if(in_array('activities', array_map('strtolower', $group['modules'])))
                {
                	if(!in_array('history', array_map('strtolower', $group['modules'])))
                    {
                    	/* Move hist from there to here */
                        $groups[$mainTab]['modules'] []= 'history';
                    }
                }
                else if(false !== ($i = array_search('history', array_map('strtolower', $group['modules']))))
                {
                    unset($groups[$mainTab]['modules'][$i]);
                    if(empty($groups[$mainTab]['modules']))
                    {
                    	unset($groups[$mainTab]);
                    }
                }                
            }
        }
        
        /* Add the 'All' group.
         * Note that if a tab group already exists with the name 'All',
         * it will be overwritten in this union operation.
         */
        if(count($groups) <= 1)
        {
        	$groups = array('All' => array('label' => 'LBL_TABGROUP_ALL', 'modules' => $tabs));
        }
        else
        {
            $groups = array('All' => array('label' => 'LBL_TABGROUP_ALL', 'modules' => $tabs)) + $groups;
        }
		
        /* Note - all $display checking and array_intersects with $tabs
         * are now redundant (thanks to GroupedTabStructure), and could
         * be removed for performance, but for now can stay to help ensure
         * that the tabs get renedered correctly. 
         */
        
        $retTabs = array();
        if($showTabs)
        {
        	require_once('include/SubPanel/SugarTab.php');
        	$sugarTab = new SugarTab();
            
            $displayTabs = array();
            $otherTabs = array();
            
    	    foreach ($groups as $key=>$tab) 
    		{
                $display = false;
                foreach($tab['modules'] as $subkey=>$subtab)
                {
                    if(in_array(strtolower($subtab), $tabs))
                    {
                        $display = true;
                        break;
                    }
                }
                
                $selected = 'other';
                
                if($selectedGroup == $key)
                {
                    $selected = 'current';
                }
               
                if($display)
                {
                    $relevantTabs = SubPanelTilesTabs::applyUserCustomLayoutToTabs($tabs, $key);
                    
                    $sugarTabs[$key] = array(//'url'=>'index.php?module=' . $_REQUEST['module'] . '&record=' . $_REQUEST['record'] . '&action=' . $_REQUEST['action']. '&subpanel=' . $key.'#tabs',
                                         //'url'=>"javascript:SUGAR.util.retrieveAndFill('index.php?to_pdf=1&module=MySettings&action=LoadTabSubpanels&loadModule={$_REQUEST['module']}&record={$_REQUEST['record']}&subpanel=$key','subpanel_list',null,null,null);",
                                         'label'=>$key,
                                         'type'=>$selected);
                    
                    $otherTabs[$key] = array('key'=>$key, 'tabs'=>array());
                    
                    $orderedTabs = array_intersect($relevantTabs, array_map('strtolower', $groups[$key]['modules']));
                    
                    foreach($orderedTabs as $subkey => $subtab)
                    {
                        $otherTabs[$key]['tabs'][$subkey] = array('key'=>$subtab, 'label'=>translate($this->subpanel_definitions->layout_defs['subpanel_setup'][$subtab]['title_key']));
                    }
                    
                    if($selectedGroup == $key)
                    {
                        $displayTabs = $otherTabs[$key]['tabs'];
                        $retTabs = $orderedTabs;
                    }
                }
    		}
            
            if(empty($displayTabs))
            {
            	$selectedGroup = 'All';
                $displayTabs = $otherTabs[$selectedGroup]['tabs'];
                $sugarTabs[$selectedGroup]['type'] = 'current';
                $retTabs = array_intersect($tabs, array_map('strtolower', $groups[$selectedGroup]['modules']));
            }
            
            $sugarTab->setup($sugarTabs, $otherTabs, $displayTabs, $selectedGroup);
            $sugarTab->display();
            
        }
        else
        {
            $tabs = SubPanelTilesTabs::applyUserCustomLayoutToTabs($tabs, $selectedGroup);
            
            $retTabs = array_intersect($tabs, array_map('strtolower', $groups[$selectedGroup]['modules']));
        }
		
		return $retTabs;
	}
}
?>
