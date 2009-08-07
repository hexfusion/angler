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
 * $Id: DashletCacheBuilder.php,v 1.10 2006/07/30 21:08:15 majed Exp $
 * Description: Handles Generic Widgets 
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/utils/file_utils.php');

class DashletCacheBuilder {
    
    /**
     * Builds the cache of Dashlets by scanning the system
     */
    function buildCache() {
        global $beanList;
        $dashletFiles = array();
        $dashletFilesCustom = array();
        
        getFiles($dashletFiles, './modules', '/^.*\/Dashlets\/[^\.]*\.php$/');
        getFiles($dashletFilesCustom, './custom/modules', '/^.*\/Dashlets\/[^\.]*\.php$/');
        $cacheDir = create_cache_directory('dashlets/');
        $allDashlets = array_merge($dashletFiles, $dashletFilesCustom);
        $dashletFiles = array();
        foreach($allDashlets as $num => $file) {
            if(substr_count($file, '.meta') == 0) { // ignore meta data files
                $class = substr($file, strrpos($file, '/') + 1, -4);
                $dashletFiles[$class] = array();
                $dashletFiles[$class]['file'] = $file;
                $dashletFiles[$class]['class'] = $class;
                if(is_file(preg_replace('/(.*\/.*)(\.php)/Uis', '$1.meta$2', $file))) { // is there an associated meta data file?
                    $dashletFiles[$class]['meta'] = preg_replace('/(.*\/.*)(\.php)/Uis', '$1.meta$2', $file);
                }
                
                $filesInDirectory = array();
                getFiles($filesInDirectory, substr($file, 0, strrpos($file, '/')), '/^.*\/Dashlets\/[^\.]*\.icon\.(jpg|jpeg|gif|png)$/i');
                if(!empty($filesInDirectory)) {
                    $dashletFiles[$class]['icon'] = $filesInDirectory[0]; // take the first icon we see
                }
            }
        }
        
        write_array_to_file('dashletsFiles', $dashletFiles, $cacheDir . 'dashlets.php');
    }
}
?>
