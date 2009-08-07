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

if(!empty($_REQUEST['id'])) {
    $columns = $current_user->getPreference('columns', 'home'); 
    $dashlets = $current_user->getPreference('dashlets', 'home');
    
    foreach($columns as $colNum => $column) {
        foreach($column['dashlets'] as $num => $dashletId) {
            if($dashletId == $_REQUEST['id']) {
                unset($columns[$colNum]['dashlets'][$num]);
            }
        }
    }
    
    foreach($dashlets as $dashletId => $data) {
        if($dashletId == $_REQUEST['id']) {
            unset($dashlets[$dashletId]);
        }
    }
    
    $current_user->setPreference('dashlets', $dashlets, 0, 'home');
    $current_user->setPreference('columns', $columns, 0, 'home');

    echo '1';
}
else {
    echo 'oops';
}

?>
