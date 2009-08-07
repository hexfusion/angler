<?PHP
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
require_once('data/Tracker.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $theme;
global $image_path;
global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");

global $db;
if(empty($db)) {
	
	$db &= PearDatabase::getInstance();
}

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_DIAGNOSTIC_TITLE'], true);
echo "\n</p>\n";

global $currentModule;
$theme_path = "themes/".$theme."/";
$image_path = $theme_path."images/";

require_once($theme_path.'layout_utils.php');
$GLOBALS['log']->info("Administration Diagnostic");

$xtpl=new XTemplate ('modules/Administration/Diagnostic.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if($db->dbType == 'oci8')
{
	$xtpl->assign("NO_MYSQL_MESSAGE", "<tr><td class=\"dataLabel\"><slot><font color=red>".
										$mod_strings['LBL_DIAGNOSTIC_NO_MYSQL'].
									  "</font></slot></td></tr><tr><td>&nbsp;</td></tr>");
	$xtpl->assign("MYSQL_CAPABLE", "");
	$xtpl->assign("MYSQL_CAPABLE_CHECKBOXES",
				  "<script type=\"text/javascript\" language=\"Javascript\"> ".
				  "document.Diagnostic.mysql_dumps.disabled=true;".
				  "document.Diagnostic.mysql_schema.disabled=true;".
				  "document.Diagnostic.mysql_info.disabled=true;".
				  "</script>"
				  );
}
else 
{
	$xtpl->assign("NO_MYSQL_MESSAGE", "");
	$xtpl->assign("MYSQL_CAPABLE", "checked");
	$xtpl->assign("MYSQL_CAPABLE_CHECKBOXES", "");
}

$xtpl->assign("RETURN_MODULE", "Administration");
$xtpl->assign("RETURN_ACTION", "index");

$xtpl->assign("MODULE", $currentModule);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);


$xtpl->assign("ADVANCED_SEARCH_PNG", get_image($image_path.'advanced_search','alt="'.$app_strings['LNK_ADVANCED_SEARCH'].'"  border="0"'));
$xtpl->assign("BASIC_SEARCH_PNG", get_image($image_path.'basic_search','alt="'.$app_strings['LNK_BASIC_SEARCH'].'"  border="0"'));

$xtpl->parse("main");
$xtpl->out("main");


?>
