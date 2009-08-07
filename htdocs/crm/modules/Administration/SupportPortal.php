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

require_once('XTemplate/xtpl.php');
require_once('include/Sugar_Smarty.php');
require_once('include/utils.php');
require_once('data/Tracker.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $theme;
global $image_path;
global $current_user;
global $currentModule;

switch ($_REQUEST['view']) {
	case "support_portal":
		if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");
		$GLOBALS['log']->info("Administration SupportPortal");

		$iframe_url = add_http("www.sugarcrm.com/network/redirect.php?tmpl=network");
        $mod_title = $mod_strings['LBL_SUPPORT_TITLE'];

        $theme_path = "themes/{$theme}/";
        $image_path = "{$theme_path}images/";
        require_once("{$theme_path}layout_utils.php");


        echo "\n<p>\n";
        echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_title, true);
        echo "\n</p>\n";

        $sugar_smarty = new Sugar_Smarty();
        $sugar_smarty->assign('iframeURL', $iframe_url);
        echo $sugar_smarty->fetch('modules/Administration/SupportPortal.tpl');

		break;
	default:

		$send_version = isset($_REQUEST['version']) ? $_REQUEST['version'] : "";
		$send_edition = isset($_REQUEST['edition']) ? $_REQUEST['edition'] : "";
		$send_lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : "";
		$send_module = isset($_REQUEST['help_module']) ? $_REQUEST['help_module'] : "";
		$send_action = isset($_REQUEST['help_action']) ? $_REQUEST['help_action'] : "";
		$send_key = isset($_REQUEST['key']) ? $_REQUEST['key'] : "";

		// awu: Fixes the ProjectTasks pluralization issue -- must fix in later versions.
		if ($send_module == 'ProjectTasks')
			$send_module = 'ProjectTask';

		$helpPath = 'modules/'.$send_module.'/language/'.$send_lang.'.help.'.$send_action.'.html';

		$sugar_smarty = new Sugar_Smarty();

		$styleSheet = "themes/" . $theme . "/style.css";

        require_once('themes/'.$theme.'/layout_utils.php');

        if(function_exists('get_style_color'))
        {
        	$colorTheme = get_style_color($theme);
            $sugar_smarty->assign('styleColor', 'themes/'.$theme.'/colors.'.$colorTheme.'.css');
        }

		if (file_exists($helpPath))
		{
			$sugar_smarty->assign('helpFileExists', TRUE);
			$sugar_smarty->assign('helpPath', $helpPath);
			$sugar_smarty->assign('helpBar', getHelpBar($send_module));
			$sugar_smarty->assign('bookmarkScript', bookmarkJS());
		}
		else
		{
			$sugar_smarty->assign('helpFileExists', FALSE);
			$iframe_url = add_http("www.sugarcrm.com/network/help.php?version={$send_version}&edition={$send_edition}&lang={$send_lang}&module={$send_module}&action={$send_action}&key={$send_key}");
			$sugar_smarty->assign('iframeURL', $iframe_url);
		}

		$sugar_smarty->assign('title', $mod_strings['LBL_SUGARCRM_HELP'] . " - " . $send_module);
		$sugar_smarty->assign('styleSheet', $styleSheet);
		$sugar_smarty->assign('table', getTable());
		$sugar_smarty->assign('endtable', endTable());

		$sugar_smarty->assign('charset', $app_strings['LBL_CHARSET']);

		echo $sugar_smarty->fetch('modules/Administration/SupportPortal.tpl');

		break;
}


function getHelpBar($moduleName)
{
	global $mod_strings;

	$helpBar = "<table width='100%'><tr><td align='right'>" .
			"<a href='javascript:window.print()'>" . $mod_strings['LBL_HELP_PRINT'] . "</a> - " .
			"<a href='mailto:?subject=" . $mod_strings['LBL_SUGARCRM_HELP'] . "&body=" . rawurlencode(getCurrentURL()) . "'>" . $mod_strings['LBL_HELP_EMAIL'] . "</a> - " .
			"<a href='#' onmousedown=\"createBookmarkLink('" . $mod_strings['LBL_SUGARCRM_HELP'] . " - " . $moduleName . "', '" . getCurrentURL() . "'" .")\">" . $mod_strings['LBL_HELP_BOOKMARK'] . "</a>" .
			"</td></tr></table>";

	return $helpBar;
}

function getTable()
{
	$table = "<table class='tabForm'><tr><td>";

	return $table;
}

function endTable()
{
	$endtable = "</td></tr></table>";

	return $endtable;
}

function bookmarkJS() {

$script =
<<<EOQ
<script type="text/javascript" language="JavaScript">
<!-- Begin
function createBookmarkLink(title, url){
	if (document.all)
		window.external.AddFavorite(url, title);
	else if (window.sidebar)
		window.sidebar.addPanel(title, url, "")
}
//  End -->
</script>
EOQ;

return $script;
}

?>
