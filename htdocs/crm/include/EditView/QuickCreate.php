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
 
require_once('include/EditView/EditView.php');

class QuickCreate extends EditView {
    /**
     * True if the create being populated via an AJAX call?
     */
    var $viaAJAX = false;
    
    function process() {
        global $current_user, $timedate;
        
        parent::process();
        
        $this->ss->assign('ASSIGNED_USER_ID', $current_user->id);



        
        $this->ss->assign('REQUEST', array_merge($_GET, $_POST));
        
        $this->ss->assign('CALENDAR_LANG', "en");
        $this->ss->assign('USER_DATEFORMAT', '('. $timedate->get_user_date_format().')');
        $this->ss->assign('CALENDAR_DATEFORMAT', $timedate->get_cal_date_format());
    }
}
?>
