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

 // $Id: tabConfig.php,v 1.9 2006/08/22 18:48:14 awu Exp $

$GLOBALS['tabStructure'] = array(
    "LBL_TABGROUP_HOME" => array(
        'label' => 'LBL_TABGROUP_HOME',
        'modules' => array(
            "Home",
            "Dashboard",
        )
    ),
    "LBL_TABGROUP_SALES" => array(
        'label' => 'LBL_TABGROUP_SALES',
        'modules' => array(
            "Accounts",
            "Opportunities",
            "Leads",
            "Contracts",
            "Quotes",
            "Products",
            "Contacts",
            "Forecasts",
        )
    ),
    "LBL_TABGROUP_MARKETING" => array(
        'label' => 'LBL_TABGROUP_MARKETING',
        'modules' => array(
            "Campaigns",
            "Contacts",
            "Accounts",
            "Leads",
        )
    ),
    "LBL_TABGROUP_SUPPORT" => array(
        'label' => 'LBL_TABGROUP_SUPPORT',
        'modules' => array(
            "Cases",
            "Bugs",
            "Accounts",
            "Contacts",
            "Products"
        )
    ),
    "LBL_TABGROUP_ACTIVITIES" => array(
        'label' => 'LBL_TABGROUP_ACTIVITIES',
        'modules' => array(
            "Activities",
            "Calendar",
            "Emails",
            "Calls",
            "Meetings",
            "Tasks",
            "Notes",
        )
    ),
    "LBL_TABGROUP_COLLABORATION"=>array(
        'label' => 'LBL_TABGROUP_COLLABORATION',
        'modules' => array(
            "Emails",
            "Project",
            "Documents",
            //"Forums",
        )
    ),
    "LBL_TABGROUP_TOOLS"=>array(
        'label' => 'LBL_TABGROUP_TOOLS',
        'modules' => array(
            "Feeds",
            "iFrames",
        )
    ),
    "LBL_TABGROUP_REPORTS"=>array(
        'label' => 'LBL_TABGROUP_REPORTS',
        'modules' => array(
            "Reports",
            "Dashboard",
        )
    )
);

if(file_exists('custom/include/tabConfig.php')){
	require_once('custom/include/tabConfig.php');
}
?>
