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
if(is_admin($current_user)){
    require_once('include/utils/file_utils.php');
    global $mod_strings, $sugar_config;
    echo $mod_strings['LBL_REBUILD_JAVASCRIPT_LANG_DESC'];
        
    $jsFiles = array();
    getFiles($jsFiles, $sugar_config['cache_dir'] . 'jsLanguage');
    foreach($jsFiles as $file) {
        unlink($file);
    }
    
    if(empty($sugar_config['js_lang_version'])) $sugar_config['js_lang_version'] = 1;
    else $sugar_config['js_lang_version'] += 1;
    
    write_array_to_file( "sugar_config", $sugar_config, "config.php");   
}
else{
	die('Admin Only Section');	
}
?>
