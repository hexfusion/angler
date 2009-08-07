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
// $Id: confirmSettings.php,v 1.32 2006/09/06 01:27:29 awu Exp $

global $sugar_config;

if( !isset( $install_script ) || !$install_script ){
    die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}

$dbType = '';
$oci8 = '';









$dbCreate = "({$mod_strings['LBL_CONFIRM_WILL']} ";
if( $_SESSION['setup_db_create_database'] != 1 ){
	$dbCreate .= $mod_strings['LBL_CONFIRM_NOT'];
}
$dbCreate .= " {$mod_strings['LBL_CONFIRM_BE_CREATED']})";

$dbUser = "{$_SESSION['setup_db_sugarsales_user']} ({$mod_strings['LBL_CONFIRM_WILL']} ";
if( $_SESSION['setup_db_create_sugarsales_user'] != 1 ){
	$dbUser .= $mod_strings['LBL_CONFIRM_NOT'];
}
$dbUser .= " {$mod_strings['LBL_CONFIRM_BE_CREATED']})";

$yesNoDropCreate = ($_SESSION['setup_db_drop_tables'] == 1) ? $mod_strings['LBL_YES'] : $mod_strings['LBL_NO'];
$yesNoDemoData = ($_SESSION['setup_db_pop_demo_data'] == 1) ? $mod_strings['LBL_YES'] : $mod_strings['LBL_NO'];
$yesNoSugarUpdates = ($_SESSION['setup_site_sugarbeet'] == 1) ? $mod_strings['LBL_YES'] : $mod_strings['LBL_NO'];
$yesNoCustomSession = ($_SESSION['setup_site_custom_session_path'] == 1) ? $mod_strings['LBL_YES'] : $mod_strings['LBL_NO'];
$yesNoCustomLog = ($_SESSION['setup_site_custom_log_dir'] == 1) ? $mod_strings['LBL_YES'] : $mod_strings['LBL_NO'];
$yesNoCustomId = ($_SESSION['setup_site_specify_guid'] == 1) ? $mod_strings['LBL_YES'] : $mod_strings['LBL_NO'];
$nameFormat = $locale->getLocaleFormattedName($mod_strings['LBL_LOCALE_NAME_FIRST'], $mod_strings['LBL_LOCALE_NAME_LAST'], $mod_strings['LBL_LOCALE_NAME_SALUTATION'], $_SESSION['default_locale_name_format']);
$languagePacks = getInstalledLangPacks(false);

// Populate the default date format, time format, and language for the system
$defaultDateFormat = "";
$defaultTimeFormat = "";
$defaultLanguages = "";

// Fixes bug 7810 (Offline Client install)
if(isset($sugar_config)){
	if(isset($sugar_config['date_formats'])){
		$defaultDateFormat = $sugar_config['date_formats'][$_SESSION["default_date_format"]];
	}
	if(isset($sugar_config['time_formats'])){
		$defaultTimeFormat = $sugar_config['time_formats'][$_SESSION["default_time_format"]];
	}
	if(isset($sugar_config['languages'])){
		$defaultLanguages = $sugar_config['languages'][$_SESSION["default_language"]];
	}
}
// Fixes Bug 6585
else{
	$sugar_config_defaults = get_sugar_config_defaults();
	// sets the string to have the correct value based on the sugar_config array
	if(isset($_REQUEST['default_date_format'])){
		$defaultDateFormat = $sugar_config_defaults['date_formats'][$_REQUEST['default_date_format']];
	}
	if(isset($_REQUEST['default_time_format'])){
		$defaultTimeFormat = $sugar_config_defaults['time_formats'][$_REQUEST['default_time_format']];
	}
	if(isset($_REQUEST['default_language'])){
		$defaultLanguages = $sugar_config_defaults['languages'][$_REQUEST['default_language']];
	}	
}

///////////////////////////////////////////////////////////////////////////////
////	START OUTPUT

$out =<<<EOQ
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Script-Type" content="text/javascript">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>{$mod_strings['LBL_WIZARD_TITLE']} {$next_step}</title>
   <link REL="SHORTCUT ICON" HREF="include/images/sugar_icon.ico">
   <link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body onload="javascript:document.getElementById('defaultFocus').focus();">
<form action="install.php" method="post" name="setConfig" id="form">
<input type="hidden" name="current_step" value="{$next_step}">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
    <tr>
        <th width="400">{$mod_strings['LBL_STEP']} {$next_step}: {$mod_strings['LBL_CONFIRM_TITLE']}</th>
        <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target="_blank"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
    </tr>
    <tr>
        <td colspan="2" width="600">
            <p>{$mod_strings['LBL_CONFIRM_DIRECTIONS']}</p>
        <table width="100%" cellpadding="0" cellpadding="0" border="0" class="StyleDottedHr">
            <tr><th colspan="3" align="left">{$mod_strings['LBL_DBCONF_TITLE']}</th></tr>
            {$dbType}
            {$oci8}
            <tr>
                <td></td>
                <td><b>{$mod_strings['LBL_DBCONF_DB_NAME']}</b></td>
                <td>
					{$_SESSION['setup_db_database_name']} {$dbCreate}
                </td>
            </tr>
            <tr>
                <td></td>
                <td><b>{$mod_strings['LBL_DBCONF_DB_USER']}</b></td>
                <td>{$dbUser}</td>
            </tr>
            <tr>
                <td></td>
                <td><b>{$mod_strings['LBL_DBCONF_DB_DROP_CREATE']}</b></td>
                <td>{$yesNoDropCreate}</td>
            </tr>
            <tr>
                <td></td>
                <td><b>{$mod_strings['LBL_DBCONF_DEMO_DATA']}</b></td>
                <td>{$yesNoDemoData}</td>
            </tr>
            <tr>
                <td></td>
                <td><b>{$mod_strings['LBL_DBCONF_PRIV_USER']}</b></td>
                <td>{$_SESSION['setup_db_admin_user_name']}</td>
            </tr>
            <tr>
            	<th colspan="3" align="left">{$mod_strings['LBL_SITECFG_TITLE']}</th>
            </tr>
            <tr>
                <td></td>
                <td><b>{$mod_strings['LBL_SITECFG_URL']}</b></td>
                <td>{$_SESSION['setup_site_url']}</td>
            </tr>
            <tr>
            	<th colspan="3" align="left">{$mod_strings['LBL_SITECFG_SUGAR_UPDATES']}</th>
            </tr>
            <tr>
                <td></td>
                <td><b>{$mod_strings['LBL_SITECFG_SUGAR_UP']}</b></td>
                <td>{$yesNoSugarUpdates}</td>
            </tr>
            <tr>
            	<th colspan="3" align="left">{$mod_strings['LBL_SITECFG_SITE_SECURITY']}</th>
            </tr>
            <tr>
                <td></td>
                <td><b>{$mod_strings['LBL_SITECFG_CUSTOM_SESSION']}?</b></td>
                <td>{$yesNoCustomSession}</td>
            </tr>
            <tr>
                <td></td>
                <td><b>{$mod_strings['LBL_SITECFG_CUSTOM_LOG']}?</b></td>
                <td>{$yesNoCustomLog}</td>
            </tr>
            <tr>
                <td></td>
                <td><b>{$mod_strings['LBL_SITECFG_CUSTOM_ID']}?</b></td>
                <td>{$yesNoCustomId}</td>
            </tr>

<!--



























-->
            <tr>
            	<th colspan="3" align="left">{$mod_strings['LBL_LOCALE_TITLE']} & {$mod_strings['LBL_LANG_TITLE']}</th>
            </tr>
				<tr>	
					<td></td>
					<td>
						<b>{$mod_strings['LBL_LOCALE_DATEF']}:</b>
					</td>
					<td>
						{$defaultDateFormat}
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<b>{$mod_strings['LBL_LOCALE_TIMEF']}:</b>
					</td>
					<td>
						{$defaultTimeFormat}
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<b>{$mod_strings['LBL_LOCALE_LANG']}:</b>
					</td>
					<td>
						{$defaultLanguages}
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<b>{$mod_strings['LBL_LOCALE_NAMEF']}:</b>
					</td>
					<td>
						{$nameFormat}
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<b>{$mod_strings['LBL_LOCALE_EXPORT']}:</b>
					</td>
					<td>
						{$_SESSION["default_export_charset"]}
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<b>{$mod_strings['LBL_LOCALE_EXPORT_DELIMITER']}:</b>
					</td>
					<td>
						{$_SESSION["export_delimiter"]}
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<b>{$mod_strings['LBL_LOCALE_CURR_DEFAULT']}:</b>
					</td>
					<td>
						{$_SESSION["default_currency_name"]}
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<b>{$mod_strings['LBL_LOCALE_CURR_SYMBOL']}:</b>
					</td>
					<td>
						{$_SESSION["default_currency_symbol"]}
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<b>{$mod_strings['LBL_LOCALE_CURR_ISO']}:</b>
					</td>
					<td>
						{$_SESSION["default_currency_iso4217"]}
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<b>{$mod_strings['LBL_LOCALE_CURR_1000S']}:</b>
					</td>
					<td>
						{$_SESSION["default_number_grouping_seperator"]}
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<b>{$mod_strings['LBL_LOCALE_CURR_DECIMAL']}:</b>
					</td>
					<td>
						{$_SESSION["default_decimal_seperator"]}
					</td>
				</tr>








            <tr>
                <td></td>
                <td colspan=2>
                	<hr>
                	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="stdTable">
					{$languagePacks}
					</table>
				</td>
            </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td align="right" colspan="2">
        <hr>
        <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
            <tr>
                <td>
                	<input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="{$mod_strings['LBL_HELP']}" />
                </td>
                <td>
                	<input type="hidden" name="goto" id="goto">
                    <input class="button" type="button" value="{$mod_strings['LBL_BACK']}" onclick="document.getElementById('goto').value='{$mod_strings['LBL_BACK']}';document.getElementById('form').submit();" />
                </td>
                <td>
                	<input class="button" type="submit" value="{$mod_strings['LBL_NEXT']}" onclick="document.getElementById('goto').value='{$mod_strings['LBL_NEXT']}';document.getElementById('form').submit();" id="defaultFocus"/>
                </td>
            </tr>
        </table>
        </td>
    </tr>
</table>
</form>
<br>

</body>
</html>


EOQ;
echo $out;

?>









