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

require_once( 'include/utils/file_utils.php' );
require_once('XTemplate/xtpl.php');

global $mod_strings;

// the initial settings for the template variables to fill
$config_check           = '';
$config_file_ready      = false;
$lbl_rebuild_config     = $mod_strings['LBL_REBUILD_CONFIG'];
$btn_rebuild_config     = $mod_strings['BTN_REBUILD_CONFIG'];
$disable_config_rebuild = 'disabled="disabled"';

// check the status of the config file
if( is_writable('config.php') ){
    $config_check = $mod_strings['MSG_CONFIG_FILE_READY_FOR_REBUILD'];
    $disable_config_rebuild = '';
    $config_file_ready = true;
}
else {
    $config_check = $mod_strings['MSG_MAKE_CONFIG_FILE_WRITABLE'];
}

// only do the rebuild if config file checks out and user has posted back
if( !empty($_POST['perform_rebuild']) && $config_file_ready ){

    if ( rebuildConfigFile($sugar_config, $sugar_version) ) {
    	$config_check = $mod_strings['MSG_CONFIG_FILE_REBUILD_SUCCESS'];
        $disable_config_rebuild = 'disabled="disabled"';
    }
    else {
        $config_check = $mod_strings['MSG_CONFIG_FILE_REBUILD_FAILED'];
    }	

}

/////////////////////////////////////////////////////////////////////
// TEMPLATE ASSIGNING
$xtpl = new XTemplate('modules/Administration/RebuildConfig.html');
$xtpl->assign('LBL_CONFIG_CHECK', $mod_strings['LBL_CONFIG_CHECK']);
$xtpl->assign('CONFIG_CHECK', $config_check);
$xtpl->assign('LBL_PERFORM_REBUILD', $lbl_rebuild_config);
$xtpl->assign('DISABLE_CONFIG_REBUILD', $disable_config_rebuild);
$xtpl->assign('BTN_PERFORM_REBUILD', $btn_rebuild_config);
$xtpl->parse('main');
$xtpl->out('main');
?>
