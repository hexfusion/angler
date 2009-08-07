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
 * $Id: index.php,v 1.32 2006/08/26 03:57:26 chris Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

if(!is_admin($current_user)) {
	sugar_die($app_strings['ERR_NOT_ADMIN']);
}

require_once('include/dir_inc.php');
require_once('include/utils/db_utils.php');
require_once('include/utils/file_utils.php');
require_once('include/utils/zip_utils.php');
require_once('include/Sugar_Smarty.php');
require_once('modules/UpgradeWizard/uw_utils.php');
require_once('modules/Administration/Administration.php');
require_once('modules/Administration/UpgradeHistory.php');
if(!isset($locale) || empty($locale)) {
	require_once('include/Localization/Localization.php');
	$locale = new Localization();
}

///////////////////////////////////////////////////////////////////////////////
////	SYSTEM PREP
$base_upgrade_dir       = getcwd().'/'.$sugar_config['upload_dir'] . "upgrades";
$base_tmp_upgrade_dir   = "$base_upgrade_dir/temp";
$subdirs = array('full', 'langpack', 'module', 'patch', 'theme', 'temp');

prepSystemForUpgrade();

$uwMain = '';
$steps = array(); 
$step = 0;
$showNext = '';
$showCancel = '';
$showBack = '';
$showRecheck = '';
$stepNext = '';
$stepCancel = '';
$stepBack = '';
$stepRecheck = '';
////	END SYSTEM PREP
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	LOGIC
$uh = new UpgradeHistory();
$smarty = new Sugar_Smarty();

// this flag set in pre_install.php->UWUpgrade();
if(isset($_SESSION['UpgradedUpgradeWizard']) && $_SESSION['UpgradedUpgradeWizard'] == true) {
	// coming from 4.2.x or 4.0.1x
	$steps = array(
		'files' => array(
	            'start',
	            'systemCheck',
	            'preflight',
	            'commit',
	            'end',
	            'cancel',
	    ),
	    'desc' => array (
	            $mod_strings['LBL_UW_TITLE_START'],
	            $mod_strings['LBL_UW_TITLE_SYSTEM_CHECK'],
	            $mod_strings['LBL_UW_TITLE_PREFLIGHT'],
	            $mod_strings['LBL_UW_TITLE_COMMIT'],
	            $mod_strings['LBL_UW_TITLE_END'],
	            $mod_strings['LBL_UW_TITLE_CANCEL'],
	    ),
	);
} else {
	// 4.5.0+
	$steps = array(
        'files' => array(
    	    'start',
            'systemCheck',
            'upload',
            'preflight',
            'commit',
            'end',
            'cancel',
    	),
        'desc' => array (
            $mod_strings['LBL_UW_TITLE_START'],
            $mod_strings['LBL_UW_TITLE_SYSTEM_CHECK'],
            $mod_strings['LBL_UW_TITLE_UPLOAD'],
            $mod_strings['LBL_UW_TITLE_PREFLIGHT'],
            $mod_strings['LBL_UW_TITLE_COMMIT'],
            $mod_strings['LBL_UW_TITLE_END'],
            $mod_strings['LBL_UW_TITLE_CANCEL'],
        ),
	);
}

if(isset($_REQUEST['step'])) {
    if($_REQUEST['step'] == -1) {
            $_REQUEST['step'] = count($steps['files']) - 1;
    } elseif($_REQUEST['step'] >= count($steps['files'])) {
            $_REQUEST['step'] = 0;
    }
} else {
    // first time through - kill off old sessions
    unset($_SESSION['step']);
    $_REQUEST['step'] = 0;
}
$file = $steps['files'][$_REQUEST['step']];
require('modules/UpgradeWizard/'.$file.'.php');

////	END LOGIC
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	UPGRADE HISTORY
// display installed pieces and versions
$installeds = $uh->getAll();
$upgrades_installed = 0;

$uwHistory  = $mod_strings['LBL_UW_DESC_MODULES_INSTALLED']."<br>\n"; 
$uwHistory .= "<ul>\n";
$uwHistory .= "<table>\n";
$uwHistory .= <<<eoq
	<tr>
		<th></th> 
		<th align=left>
			{$mod_strings['LBL_ML_NAME']}
		</th>
		<th align=left>
			{$mod_strings['LBL_ML_TYPE']}
		</th>
		<th align=left>
			{$mod_strings['LBL_ML_VERSION']}
		</th>
		<th align=left>
			{$mod_strings['LBL_ML_INSTALLED']}
		</th>
		<th>
			{$mod_strings['LBL_ML_DESCRIPTION']}
		</th>
		<th>
			{$mod_strings['LBL_ML_ACTION']}
		</th>
	</tr>
eoq;

foreach($installeds as $installed) {
	$form_action = '';
	$filename = from_html($installed->filename);
	$date_entered = $installed->date_entered;
	$type = $installed->type;
	$version = $installed->version;
	$upgrades_installed++;
	$link = "";
	$view = 'default';

	$target_manifest = remove_file_extension( $filename ) . "-manifest.php";
	require_once( "$target_manifest" );
	$name = empty($manifest['name']) ? $filename : $manifest['name'];
	$description = empty($manifest['description']) ? $mod_strings['LBL_UW_NONE'] : $manifest['description'];

	if(isset($manifest['icon']) && $manifest['icon'] != "") {
		$manifest_copy_files_to_dir = isset($manifest['copy_files']['to_dir']) ? clean_path($manifest['copy_files']['to_dir']) : "";
		$manifest_copy_files_from_dir = isset($manifest['copy_files']['from_dir']) ? clean_path($manifest['copy_files']['from_dir']) : "";
		$manifest_icon = clean_path($manifest['icon']);
		$icon = "<img src=\"" . $manifest_copy_files_to_dir . ($manifest_copy_files_from_dir != "" ? substr($manifest_icon, strlen($manifest_copy_files_from_dir)+1) : $manifest_icon ) . "\">";
	} else {
		$icon = getImageForType( $manifest['type'] );
	}
	
	$uwHistory .= "<form action=\"" . $form_action . "_prepare\" method=\"post\">\n".
		"<tr><td>$icon</td><td>$name</td><td>$type</td><td>$version</td><td>$date_entered</td><td>$description</td><td>$link</td></tr>\n".
		"</form>\n";
}


if($upgrades_installed == 0) {
	$uwHistory .= "<td class='tabDetailViewDF' colspan='6'>";
	$uwHistory .= $mod_strings['LBL_UW_NO_INSTALLED_UPGRADES'];
	$uwHistory .= "</td></tr>";
}

$uwHistory .= "</table>\n";
$uwHistory .= "</ul>\n";
////	END UPGRADE HISTORY
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	PAGE OUTPUT
$js=<<<eoq
<script type="text/javascript" language="Javascript">
	function toggleNwFiles(target) {
		var div = document.getElementById(target);

		if(div.style.display == "none") {
			div.style.display = "";
		} else {
			div.style.display = "none";
		}
	}
	
	function handlePreflight(step) {
		if(step == 'preflight') {
			document.getElementById('schema').value = document.getElementById('select_schema_change').value;

			if(document.getElementById('diffs') != null) {
				/* preset the hidden var for defaults */
				checkSqlStatus(false);

				theForm = document.getElementById('diffs');
				var serial = '';
				for(i=0; i<theForm.elements.length; i++) {
					if(theForm.elements[i].type == 'checkbox' && theForm.elements[i].checked == false) {
						// we only want "DON'T OVERWRITE" files
						if(serial != '') {
							serial += "::";
						}
						serial += theForm.elements[i].value;
					}
				}				document.getElementById('overwrite_files_serial').value = serial;
				
				if(document.getElementById('addTask').checked == true) {
					document.getElementById('addTaskReminder').value = 'remind';
				}
				if(document.getElementById('addEmail').checked == true) {
					document.getElementById('addEmailReminder').value = 'remind';
				}
			}
		}
		
		return;
	}
</script>
eoq;

$smarty->assign('UW_MAIN', $uwMain);
$smarty->assign('UW_JS', $js);
$smarty->assign('CHECKLIST', getChecklist($steps, $step));
$smarty->assign('UW_TITLE', get_module_title($mod_strings['LBL_UW_TITLE'], $mod_strings['LBL_UW_TITLE'].": ".$steps['desc'][$_REQUEST['step']], true));
$smarty->assign('MOD', $mod_strings);
$smarty->assign('APP', $app_strings);
$smarty->assign('GRIDLINE', $current_user->getPreference('gridline'));
$smarty->assign('showNext', $showNext);
$smarty->assign('showCancel', $showCancel);
$smarty->assign('showBack', $showBack);
$smarty->assign('showRecheck', $showRecheck);
$smarty->assign('STEP_NEXT', $stepNext);
$smarty->assign('STEP_CANCEL', $stepCancel);
$smarty->assign('STEP_BACK', $stepBack);
$smarty->assign('STEP_RECHECK', $stepRecheck);
$smarty->assign('step', $steps['files'][$_REQUEST['step']]);
$smarty->assign('UW_HISTORY', $uwHistory);
if(isset($stop) && $stop == true) {
	$frozen = (isset($frozen)) ? "<br />".$frozen : '';
	$smarty->assign('frozen', $mod_strings['LBL_UW_FROZEN'].$frozen);
}
$smarty->display('modules/UpgradeWizard/uw_main.tpl');
////	END PAGE OUTPUT
///////////////////////////////////////////////////////////////////////////////

?>
