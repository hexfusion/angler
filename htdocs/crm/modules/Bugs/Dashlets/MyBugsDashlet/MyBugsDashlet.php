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

// $Id: MyBugsDashlet.php,v 1.7 2006/08/22 19:09:18 awu Exp $

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Bugs/Bug.php');
require_once('MyBugsDashlet.data.php');

class MyBugsDashlet extends DashletGeneric { 
    function MyBugsDashlet($id, $def = null) {
        global $current_user, $app_strings, $dashletData;
        parent::DashletGeneric($id, $def);
        
        $this->searchFields = $dashletData['MyBugsDashlet']['searchFields'];
        $this->columns = $dashletData['MyBugsDashlet']['columns'];
        
        if(empty($def['title'])) $this->title = translate('LBL_LIST_MY_BUGS', 'Bugs');
        $this->seedBean = new Bug();        
    }
    
    function displayOptions() {
        require_once('modules/Releases/Release.php');
        $this->processDisplayOptions();
        
        $seedRelease = new Release();
        
        $this->currentSearchFields['fixed_in_release']['input'] = '<select multiple="true" size="3" name="fixed_in_release[]">' 
                                                                  . get_select_options_with_id($seedRelease->get_releases(false, "Active"), (empty($this->filters['fixed_in_release']) ? '' : $this->filters['fixed_in_release'])) 
                                                                  . '</select>';
        $this->currentSearchFields['found_in_release']['input'] = '<select multiple="true" size="3" name="found_in_release[]">' 
                                                                  . get_select_options_with_id($seedRelease->get_releases(false, "Active"), (empty($this->filters['found_in_release']) ? '' : $this->filters['found_in_release']))
                                                                  . '</select>'; 
        
        $this->configureSS->assign('searchFields', $this->currentSearchFields);
        return $this->configureSS->fetch($this->configureTpl);
    }
}

?>
