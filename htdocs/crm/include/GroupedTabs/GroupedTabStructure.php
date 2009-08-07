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
// $Id: GroupedTabStructure.php,v 1.5 2006/08/20 19:51:35 majed Exp $

require_once('include/utils.php');
require_once('include/tabConfig.php');

class GroupedTabStructure
{
	/** 
     * Prepare the tabs structure.
     * Uses 'Other' tab functionality.
     * If $modList is not specified, $modListHeader is used as default.
     * 
     * @param   array   optional list of modules considered valid
     * @param   array   optional array to temporarily union into the root of the tab structure 
     * 
     * @return  array   the complete tab-group structure
	 */
    function get_tab_structure($modList = '', $patch = '')
    {
    	global $modListHeader, $app_strings;
        
        /* Use default if not provided */
        if(!$modList)
        {
        	$modList =& $modListHeader;
        }
        
        /* Apply patch, use a reference if we can */
        if($patch)
        {
        	$tabStructure = $GLOBALS['tabStructure'];
        	
            foreach($patch as $mainTab => $subModules)
            {
                $tabStructure[$mainTab]['modules'] = array_merge($tabStructure[$mainTab]['modules'], $subModules);
            }
        }
        else
        {
        	$tabStructure =& $GLOBALS['tabStructure'];
        }
        
        $retStruct = array();
        $mlhUsed = array();
        
        /* Only return modules which exists in the modList */
        foreach($tabStructure as $mainTab => $subModules)
        {
            foreach($subModules['modules'] as $key => $subModule)
            {
               /* Perform a case-insensitive in_array check
                * and mark whichever module matched as used.
                */ 
                foreach($modList as $module)
                {
                    if(strcasecmp($subModule, $module) === 0)
                    {
                        $retStruct[$app_strings[$subModules['label']]]['modules'][$key] = $subModule;
                        $mlhUsed[$module] = true;
                        break;
                    }
                }
            }
        }
        
        /* Put all the unused modules in modList
         * into the 'Other' tab.
         */
        foreach($modList as $module)
        {
            if(!isset($mlhUsed[$module]))
            {
            	$retStruct[$app_strings['LBL_TABGROUP_OTHER']]['modules'] []= $module;
            }
        }
        
        return $retStruct;
    }
}

?>
