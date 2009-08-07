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

// $Id: siteConfig.php,v 1.13 2006/08/10 23:35:02 awu Exp $

if( !isset( $install_script ) || !$install_script ){
    die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}

if( !isset($_SESSION['siteConfig_submitted']) || !$_SESSION['siteConfig_submitted'] ){
    $web_root = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $web_root = str_replace("/install.php", "", $web_root); // cn: bug 6898 add port if not 80
    $web_root = str_replace(":80/", "/", $web_root); // cn: bug 6898 keep old behavior
    $web_root = "http://$web_root";
    $current_dir = str_replace('\install',"", dirname(__FILE__));
    $current_dir = str_replace('/install',"", $current_dir);
    $current_dir = trim($current_dir);

	if( is_readable('config.php') ){
        
	}

    // set the form's php var to the loaded config's var else default to sane settings
    $_SESSION['setup_site_url'] = (empty($sugar_config['site_url']) || $sugar_config['site_url'] == '' ) ? $web_root : $sugar_config['site_url'];
    $_SESSION['setup_site_sugarbeet']           = true;
    $_SESSION['setup_site_defaults']            = true;
    $_SESSION['setup_site_custom_session_path'] = false;
    $_SESSION['setup_site_session_path']    = (isset($sugar_config['session_dir'])) ? $sugar_config['session_dir']  : '';
    $_SESSION['setup_site_custom_log_dir']  = false;
    $_SESSION['setup_site_log_dir']         = (isset($sugar_config['log_dir']))     ? $sugar_config['log_dir']      : '.';
    $_SESSION['setup_site_specify_guid']    = false;
    $_SESSION['setup_site_guid']            = (isset($sugar_config['unique_key']))  ? $sugar_config['unique_key']   : '';
    $_SESSION['setup_site_admin_password']          = '';
    $_SESSION['setup_site_admin_password_retype']   = '';
}

// should this be moved to install.php?
if( is_file("config.php") ){
    require_once("config.php");

	if(!empty($sugar_config['default_theme']))
      $_SESSION['site_default_theme'] = $sugar_config['default_theme'];

	if(!empty($sugar_config['disable_persistent_connections']))
		$_SESSION['disable_persistent_connections'] =
		$sugar_config['disable_persistent_connections'];
	if(!empty($sugar_config['default_language']))
		$_SESSION['default_language'] = $sugar_config['default_language'];
	if(!empty($sugar_config['translation_string_prefix']))
		$_SESSION['translation_string_prefix'] = $sugar_config['translation_string_prefix'];
	if(!empty($sugar_config['default_charset']))
		$_SESSION['default_charset'] = $sugar_config['default_charset'];

	if(!empty($sugar_config['default_currency_name']))
		$_SESSION['default_currency_name'] = $sugar_config['default_currency_name'];
	if(!empty($sugar_config['default_currency_symbol']))
		$_SESSION['default_currency_symbol'] = $sugar_config['default_currency_symbol'];
	if(!empty($sugar_config['default_currency_iso4217']))
		$_SESSION['default_currency_iso4217'] = $sugar_config['default_currency_iso4217'];

	if(!empty($sugar_config['rss_cache_time']))
		$_SESSION['rss_cache_time'] = $sugar_config['rss_cache_time'];
	if(!empty($sugar_config['languages']))
	{
		// We need to encode the languages in a way that can be retrieved later.
		$language_keys = Array();
		$language_values = Array();

		foreach($sugar_config['languages'] as $key=>$value)
		{
			$language_keys[] = $key;
			$language_values[] = $value;
		}

		$_SESSION['language_keys'] = urlencode(implode(",",$language_keys));
		$_SESSION['language_values'] = urlencode(implode(",",$language_values));
	}
}

////	errors
$errors = '';
if( isset($validation_errors) ){
    if( count($validation_errors) > 0 ){
        $errors  = '<div id="errorMsgs">';
        $errors .= '<p>'.$mod_strings['LBL_SITECFG_FIX_ERRORS'].'</p><ul>';
        foreach( $validation_errors as $error ){
			$errors .= '<li>' . $error . '</li>';
        }
		$errors .= '</ul></div>';
    }
}


////	ternaries
$sugarUpdates = (isset($_SESSION['setup_site_sugarbeet']) && !empty($_SESSION['setup_site_sugarbeet'])) ? 'checked="checked"' : '';
$siteSecurity = (isset($_SESSION['setup_site_defaults']) && !empty($_SESSION['setup_site_defaults'])) ? 'checked="checked"' : '';
$customSession = (isset($_SESSION['setup_site_custom_session_path']) && !empty($_SESSION['setup_site_custom_session_path'])) ? 'checked="checked"' : '';
$customLog = (isset($_SESSION['setup_site_custom_log_dir']) && !empty($_SESSION['setup_site_custom_log_dir'])) ? 'checked="checked"' : '';
$customId = (isset($_SESSION['setup_site_specify_guid']) && !empty($_SESSION['setup_site_specify_guid'])) ? 'checked="checked"' : '';

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
   <script type="text/javascript" src="install/installCommon.js"></script>
   <script type="text/javascript" src="install/siteConfig.js"></script>
</head>
<body onload="javascript:toggleGUID();toggleSession();toggleSiteDefaults();document.getElementById('defaultFocus').focus();">
<form action="install.php" method="post" name="setConfig" id="form">
<input type="hidden" name="current_step" value="{$next_step}">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
<tr>
   <th width="400">{$mod_strings['LBL_STEP']} {$next_step}: {$mod_strings['LBL_SITECFG_TITLE']}</th>
   <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target="_blank">
		<IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
   </tr>
<tr>
    <td colspan="2" width="600">
    <p>{$mod_strings['LBL_SITECFG_DIRECTIONS']}</p>
    {$errors}
   <div class="required">{$mod_strings['LBL_REQUIRED']}</div>
   <table width="100%" cellpadding="0" cellpadding="0" border="0" class="StyleDottedHr">
   <tr><th colspan="3" align="left">{$mod_strings['LBL_SITECFG_TITLE']}</td></tr>
   <tr><td><span class="required">*</span></td>
       <td><b>{$mod_strings['LBL_SITECFG_URL']}</td>
       <td align="left"><input type="text" name="setup_site_url" id="defaultFocus" value="{$_SESSION['setup_site_url']}" size="40" /></td></tr>
   <tr><td><span class="required">*</span></td>
       <td><b>{$mod_strings['LBL_SITECFG_ADMIN_PASS']}</b><br>
       		<i>{$mod_strings['LBL_SITECFG_ADMIN_PASS_WARN']}</i></td>
       <td align="left"><input type="password" name="setup_site_admin_password" value="{$_SESSION['setup_site_admin_password']}" size="20" /></td></tr>
   <tr><td><span class="required">*</span></td>
       <td><b>{$mod_strings['LBL_SITECFG_ADMIN_PASS_2']}</td>
       <td align="left"><input type="password" name="setup_site_admin_password_retype" value="{$_SESSION['setup_site_admin_password_retype']}" size="20" /></td></tr>
   <tr><th colspan="3" align="left">{$mod_strings['LBL_SITECFG_SUGAR_UPDATES']}</td></tr>
EOQ;



$checked = '';
if(!empty($_SESSION['setup_site_sugarbeet_anonymous_stats'])) $checked = 'checked="checked"'; 
$out .= "
   <tr><td></td>
       <td><b>{$mod_strings['LBL_SITECFG_ANONSTATS']}</b><br><i>{$mod_strings['LBL_SITECFG_ANONSTATS_DIRECTIONS']}</i></td>
       <td><input type='checkbox' class='checkbox' name='setup_site_sugarbeet_anonymous_stats' value='yes' $checked /></td></tr>
";



$checked = '';
if(!empty($_SESSION['setup_site_sugarbeet_automatic_checks'])) $checked = 'checked="checked"'; 
$out .= <<<EOQ
   <tr><td></td>
       <td><b>{$mod_strings['LBL_SITECFG_SUGAR_UP']}</b><br><i>{$mod_strings['LBL_SITECFG_SUGAR_UP_DIRECTIONS']}</i></td>
       <td><input type="checkbox" class="checkbox" name="setup_site_sugarbeet_automatic_checks" value="yes" $checked /></td></tr>

   
   
   <tr><th colspan="3" align="left">{$mod_strings['LBL_SITECFG_SITE_SECURITY']}</td></tr>
   <tr><td></td>
       <td><b>{$mod_strings['LBL_SITECFG_USE_DEFAULTS']}</b></td>
       <td><input type="checkbox" class="checkbox" name="setup_site_defaults" value="yes" onclick="javascript:toggleSiteDefaults();" {$siteSecurity} /></td></tr>
   <tbody id="setup_site_session_section_pre">
   <tr><td></td>
       <td><b>{$mod_strings['LBL_SITECFG_CUSTOM_SESSION']}</b><br>
			<em>{$mod_strings['LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS']}</em></td>
       <td><input type="checkbox" class="checkbox" name="setup_site_custom_session_path" value="yes" onclick="javascript:toggleSession();" {$customSession} /></td></tr>
   </tbody>
   <tbody id="setup_site_session_section">
   <tr><td><span class="required">*</span></td>
       <td><b>{$mod_strings['LBL_SITECFG_SESSION_PATH']}</td>
       <td align="left"><input type="text" name="setup_site_session_path" size='40' value="{$_SESSION['setup_site_session_path']}" /></td></tr>
   </tbody>
   <tbody id="setup_site_log_dir_pre">
   <tr><td></td>
       <td><b>{$mod_strings['LBL_SITECFG_CUSTOM_LOG']}</b><br>
			<em>{$mod_strings['LBL_SITECFG_CUSTOM_LOG_DIRECTIONS']}</em></td>
       <td><input type="checkbox" class="checkbox" name="setup_site_custom_log_dir" value="yes" onclick="javascript:toggleLogDir();" {$customLog} /></td></tr>
   </tbody>
   <tbody id="setup_site_log_dir">
   <tr><td><span class="required">*</span></td>
       <td><b>{$mod_strings['LBL_SITECFG_LOG_DIR']}</b></td>
       <td align="left"><input type="text" name="setup_site_log_dir" size='30' value="{$_SESSION['setup_site_log_dir']}" /></td></tr>
   </tbody>
   <tbody id="setup_site_guid_section_pre">
   <tr><td></td>
       <td><b>{$mod_strings['LBL_SITECFG_CUSTOM_ID']}</b><br>
			<em>{$mod_strings['LBL_SITECFG_CUSTOM_ID_DIRECTIONS']}</em></td>
       <td><input type="checkbox" class="checkbox" name="setup_site_specify_guid" value="yes" onclick="javascript:toggleGUID();" {$customId} /></td></tr>
   </tbody>
   <tbody id="setup_site_guid_section">
   <tr><td><span class="required">*</span></td>
       <td><b>{$mod_strings['LBL_SITECFG_APP_ID']}</td>
       <td align="left"><input type="text" name="setup_site_guid" size='30' value="{$_SESSION['setup_site_guid']}" /></td></tr>
   </tbody>
</table>
</td>
</tr>
<tr>
   <td align="right" colspan="2">
   <hr>
   <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
   <tr>
   <td><input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="{$mod_strings['LBL_HELP']}" /></td>
    <td>
        <input class="button" type="button" name="goto" value="{$mod_strings['LBL_BACK']}" onclick="document.getElementById('form').submit();" />
        <input type="hidden" name="goto" value="{$mod_strings['LBL_BACK']}" />
    </td>
   <td><input class="button" type="submit" name="goto" value="{$mod_strings['LBL_NEXT']}" /></td>
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
