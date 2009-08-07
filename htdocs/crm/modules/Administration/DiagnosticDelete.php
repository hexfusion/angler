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

echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_DIAGNOSTIC_TITLE'], true);
echo "\n</p>\n";


if(!isset($_REQUEST['file']) || !isset($_REQUEST['guid']))
{
	echo "Did not receive a filename or guid path to delete the file<BR><BR>";
}
else
{
	//Making sure someone doesn't pass a variable name as a false reference
	//  to delete a file
	if(strcmp(substr($_REQUEST['file'], 0, 10), "diagnostic") != 0)
	{
		die('You are trying to delete a non diagnostic file.');
	}

	if(file_exists("cache/diagnostic/".$_REQUEST['guid']."/".$_REQUEST['file'].".zip"))
	{
  	  unlink("cache/diagnostic/".$_REQUEST['guid']."/".$_REQUEST['file'].".zip");
  	  rmdir("cache/diagnostic/".$_REQUEST['guid']);
	  echo $mod_strings['LBL_DIAGNOSTIC_DELETED']."<br><br>";
	}
	else
	  echo "File ".$_REQUEST['file'].".zip doesn't exist.<BR><BR>";
}

print "<a href=\"index.php?module=Administration&action=index\">Return to Administration page</a><br>";

?>
