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

require_once('include/utils.php');

global $current_user;

if(!empty($_REQUEST['layout'])) {
//    sleep (2);
//  _ppd($_REQUEST['layout']);
    $newColumns = array();
    
    $newLayout = explode('|', $_REQUEST['layout']);
    foreach($newLayout as $col => $ids) { 
        $newColumns[$col]['dashlets'] = explode(',', $ids); 
    }
    $newColumns[0]['width'] = '60%';
    $newColumns[1]['width'] = '40%';
    
//    _ppd($newColumns);
    $current_user->setPreference('columns', $newColumns, 0, 'home');
    
//    require_once($dashlets[$_REQUEST['id']]['fileLocation']);
//    $dashlet = new $dashlets[$_REQUEST['id']]['className']();
//    echo $dashlet->display($_REQUEST['id']);
    echo '1';
}
else {
    echo '0';
}

?>
