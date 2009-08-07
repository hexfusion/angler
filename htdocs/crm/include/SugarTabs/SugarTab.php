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
 
// $Id: SugarTab.php,v 1.3 2006/08/22 18:48:14 awu Exp $

require_once('include/Sugar_Smarty.php');
if(empty($GLOBALS['sugar_smarty']))$GLOBALS['sugar_smarty'] = new Sugar_Smarty();
class SugarTab{
    
    function SugarTab($type='singletabmenu'){
        $this->type = $type;
        
    }
    
    function setup($mainTabs, $otherTabs=array(), $subTabs=array(), $selected_group='All'){
    	// TODO - prefs here
        //_pp($subTabs);_pp($otherTabs);
        $max_subtabs = $GLOBALS['current_user']->getPreference('max_subtabs');
        if($max_subtabs <= 0) $max_subtabs = 12;
        $max_tabs = $GLOBALS['current_user']->getPreference('max_tabs');
        if($max_tabs <= 0) $max_tabs = 12;
        $GLOBALS['sugar_smarty']->assign('sugartabs', array_slice($mainTabs, 0, $max_tabs));
        $GLOBALS['sugar_smarty']->assign('subtabs', array_slice($subTabs, 0, $max_subtabs));
        $GLOBALS['sugar_smarty']->assign('moreMenu', array_slice($mainTabs, $max_tabs));
        $GLOBALS['sugar_smarty']->assign('moreSubMenuName', $selected_group);
        $GLOBALS['sugar_smarty']->assign('moreSubMenu', array_slice($subTabs, $max_subtabs));
        $otherMoreTabs = array();
        if(!empty($otherTabs))
        {
            foreach($otherTabs as $key => $ot)
            {
            	$otherMoreTabs[$key] = array('key' => $key,
                                             'tabs' => array_slice($ot['tabs'], $max_subtabs));
                $otherTabs[$key]['tabs'] = array_slice($ot['tabs'], 0, $max_subtabs);
            }
        }
        else
        {
            $otherMoreTabs[$selected_group] = array('key' => $selected_group,
                                                    'tabs' => array_slice($subTabs, $max_subtabs));
            $otherTabs[$selected_group]['tabs'] = array_slice($subTabs, 0, $max_subtabs);
        }
        //_pp($otherMoreTabs);
        $GLOBALS['sugar_smarty']->assign('othertabs', $otherTabs);
        $GLOBALS['sugar_smarty']->assign('otherMoreSubMenu', $otherMoreTabs);
        $GLOBALS['sugar_smarty']->assign('startSubPanel', $selected_group);
        if(!empty($mainTabs))
        {
            $mtak = array_keys($mainTabs);
            $GLOBALS['sugar_smarty']->assign('moreTab', $mainTabs[$mtak[min(count($mtak)-1, $max_tabs-1)]]['label']);
        }
    }
    
    function fetch(){
        return $GLOBALS['sugar_smarty']->fetch('include/SugarTabs/tpls/' . $this->type . '.tpl');
    }
    function display(){
       $GLOBALS['sugar_smarty']->display('include/SugarTabs/tpls/' . $this->type . '.tpl');
    }
    
    
}



?>
