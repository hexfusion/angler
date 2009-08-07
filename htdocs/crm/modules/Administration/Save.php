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
/*********************************************************************************
 * $Id: Save.php,v 1.27 2006/06/06 17:57:55 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once("modules/Administration/Administration.php");
global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");

$focus = new Administration();

// filter for relevant POST data and update config table
foreach ($_POST as $key => $val) {
	$prefix = $focus->get_config_prefix($key);
	if (in_array($prefix[0], $focus->config_categories)) {
        if( $prefix[0] == "license" && $prefix[1] == "expire_date" ){

			global $timedate;
            $val = $timedate->swap_formats( $val, $timedate->get_date_format(), $timedate->dbDayFormat );

        }
        $focus->saveSetting($prefix[0], $prefix[1], $val); 
	}
}

















header("Location: index.php?action={$_POST['return_action']}&module={$_POST['return_module']}");
?>
