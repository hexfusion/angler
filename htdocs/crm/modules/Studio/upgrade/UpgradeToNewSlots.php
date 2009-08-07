<pre>
<?php
if(!defined('sugarEntry')) define('sugarEntry', true);
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

 // $Id: UpgradeToNewSlots.php,v 1.8 2006/09/05 22:27:37 majed Exp $


require_once('include/dir_inc.php');
require_once('modules/Studio/parsers/StudioParser.php');
require_once('modules/Studio/parsers/StudioUpgradeParser.php');
$studio = new StudioUpgradeParser();
function upgradeHTML($file){
	
	global $studio;
	$studio->loadFile($file);
	$studio->curText = $studio->replaceH4Slots($studio->curText);
	$studio->parseSlots($studio->curText);
	$studio->cleanUpSlots();
	$newSlots = $studio->upgradeSlots();
		copy($file, $file . '.sbk');
	$studio->saveFile($file, $newSlots);
}

$htmlFiles = array ();
function findHTMLFiles($path) {
	$dir = dir($path);
	while ($entry = $dir->read()) {
		if(file_exists('modules/' . $entry . '/metadata/studio.php')){
			require_once('modules/' . $entry . '/metadata/studio.php');
			echo "Upgrading $entry for studio ... \n";
			foreach($GLOBALS['studioDefs'][$entry] as $label=>$def){
				if(!empty($def['template_file'])&& substr_count($def['template_file'],'.html') > 0){
						upgradeHTML($def['template_file']);
				}
			}
		}
	
	}
}

findHTMLFiles('modules');
StudioParser::clearWorkingDirectory();
require_once('modules/Versions/Version.php');
Version::mark_upgraded('Studio Files', '4.5.0', '4.5.0');
?>
