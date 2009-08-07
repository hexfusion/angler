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

// $Id: checkSystem.php,v 1.47 2006/08/24 23:27:04 awu Exp $

if( !isset( $install_script ) || !$install_script ){
    die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}

if(!defined('SUGARCRM_MIN_MEM')) {
	define('SUGARCRM_MIN_MEM', 32);
}

// for keeping track of whether to enable/disable the 'Next' button
$error_found = false;

//
// Check to see if session variables are working properly
//

$_SESSION['test_session'] = 'sessions are available';
session_write_close();
unset($_SESSION['test_session']);
session_start();

if(!isset($_SESSION['test_session']))
{
   die("<p><b>{$mod_strings['LBL_CHECKSYS_NO_SESSIONS']}</b></p>\n");
}


// PHP VERSION
$php_version = constant('PHP_VERSION');
$check_php_version_result = check_php_version($php_version);

switch($check_php_version_result) {
	case -1:
		$phpVersion = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_PHP_INVALID_VER']} {$php_version} )</span></b>";
		$error_found = true;
		break;
	case 0:
		$phpVersion = "<b><span class=go>{$mod_strings['ERR_CHECKSYS_PHP_UNSUPPORTED']} {$php_version} )</span></b>";
		break;
	case 1:
		$phpVersion = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_PHP_OK']} {$php_version} )</span></b>";
		break;
}
// database and connect




$fh = fopen( "LICENSE.txt", 'r' );
$lic_line = fgets($fh);

if ( strpos($lic_line, 'SPL') ) {
    $_SESSION['setup_db_type'] = 'mysql';
}
else {
    $_SESSION['setup_db_type'] = 'mssql';
}








$mssqlStatus = '';
$dbVersion = '';
switch($_SESSION['setup_db_type']) {
    case 'mysql':
        $db_name        = "MySQL Database";
        $function_name  = "mysql_connect";
      
        break;
	case 'mssql': 
        $db_name        = "SQL Server Database";
        $function_name  = "mssql_connect";
        break;
    case 'oci8':




        break;
}

if( function_exists( $function_name ) ){
	$dbStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
} else {
	$dbStatus = "<b><span class=stop>{$mod_strings['LBL_CHECKSYS_NOT_AVAILABLE']}</span></b>";
	$error_found = true;
}

// XML Parsing
if(function_exists('xml_parser_create')) {
	$xmlStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
} else {
	$xmlStatus = "<b><span class=stop>{$mod_strings['LBL_CHECKSYS_NOT_AVAILABLE']}</span></b>";
	$error_found = true;
}

// cURL
if(function_exists('curl_init')) {
	$curlStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</font></b>";
} else {
	$curlStatus = "<b><span class=go>{$mod_strings['ERR_CHECKSYS_CURL']}</font></b>";
	$error_found = false;
}

// mbstrings
if(function_exists('mb_strlen')) {
	$mbstringStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</font></b>";
} else {
	$mbstringStatus = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_MBSTRING']}</font></b>";
	$error_found = false;
}

// zlib
if(function_exists('gzclose')) {
	$zlibStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
} else {
	$zlibStatus = "<b><span class=go>{$mod_strings['ERR_CHECKSYS_ZLIB']}</span></b>";
}

// php-json
if(function_exists('json_encode')) {
	$jsonPhpStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	
	if(returnPhpJsonStatus()) {
		$phpInfo = getPhpInfo(8);
		$jsonPhpStatus = "<b><span class='go'>{$mod_strings['ERR_CHECKSYS_PHP_JSON_VERSION']}<br>".$mod_strings['LBL_CHECKSYS_VER'].$phpInfo['json']['json version']." )</span></b>";
	}
} else {
	$jsonPhpStatus = "<b><span class='go'>{$mod_strings['ERR_CHECKSYS_PHP_JSON']}</span></b>";
}

// imap
if(function_exists('imap_open')) {
	$imapStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
} else {
	$imapStatus = "<b><span class=go>{$mod_strings['ERR_CHECKSYS_IMAP']}</span></b>";
}

// config.php
if(make_writable('./config.php')) {
	$configStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
} elseif(is_writable('.')) {
	$configStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
} else {
	$configStatus = "<b><span class='stop'>{$mod_strings['ERR_CHECKSYS_NOT_WRITABLE']}</span></b>";
}

// custom dir
if(make_writable('./custom')) {
	$customStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</font></b>";
} else {
	$customStatus = "<b><span class='stop'>{$mod_strings['ERR_CHECKSYS_NOT_WRITABLE']}</font></b>";
	$error_found = true;
}

// modules dir
if(recursive_make_writable('./modules')) {
	$moduleStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
} else {
	$moduleStatus = "<b><span class='stop'>{$mod_strings['ERR_CHECKSYS_NOT_WRITABLE']}</span></b>";
	$error_found = true;
}

// data dir
if(make_writable('./data') && make_writable('./data/upload')) {
	$dataStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
} else {
	$dataStatus = "<b><span class='stop'>{$mod_strings['ERR_CHECKSYS_NOT_WRITABLE']}</span></b>";
	$error_found = true;
}

// cache dir
if(make_writable('./cache/custom_fields') &&
	make_writable('./cache/dyn_lay') &&
	make_writable('./cache/images') &&
	make_writable('./cache/import') &&
	make_writable('./cache/layout') &&
	make_writable('./cache/pdf') &&
	make_writable('./cache/upload') &&
	make_writable('./cache/xml')) {
	$cacheStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
} else {
	$cacheStatus = "<b><span class='stop'>{$mod_strings['ERR_CHECKSYS_NOT_WRITABLE']}</span></b>";
	$error_found = true;
}
// session save dir
$temp_dir = (isset($_ENV['TEMP'])) ? $_ENV['TEMP'] : "";
$session_save_path = (session_save_path() === "") ? $temp_dir : session_save_path();
if (strpos ($session_save_path, ";") !== FALSE) {
	$session_save_path = substr ($session_save_path, strpos ($session_save_path, ";")+1);
}
if(is_dir($session_save_path)) {
	if(is_writable($session_save_path)) {
		$sessionStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
	} else {
		$sessionStatus = "<b><span class='stop'>{$mod_strings['ERR_CHECKSYS_NOT_WRITABLE']}</span></b>";
		$error_found = true;
	}
} else {
	$sessionStatus = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_NOT_VALID_DIR']}</span></b>";
	$error_found = true;
}
// safe mode
if('1' == ini_get('safe_mode')) {
	$safeModeStatus = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_SAFE_MODE']}</span></b>";
	$error_found = true;
} else {
	$safeModeStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
}


// call time pass by ref
if('0' == ini_get('allow_call_time_pass_reference')) {
	$callTimeStatus = "<b><span class=stop>{$mod_strings['ERR_CHECKSYS_CALL_TIME']}</span></b>";
	$error_found = true;
} else {
	$callTimeStatus = "<b><span class=go>{$mod_strings['LBL_CHECKSYS_OK']}</span></b>";
}

// memory limit
$memory_msg     = "";
$memory_limit   = "-1";//ini_get('memory_limit');
$sugarMinMem = constant('SUGARCRM_MIN_MEM');
// logic based on: http://us2.php.net/manual/en/ini.core.php#ini.memory-limit
if( $memory_limit == "" ){          // memory_limit disabled at compile time, no memory limit
    $memory_msg = "<b><span class=\"go\">{$mod_strings['LBL_CHECKSYS_MEM_OK']}</span></b>";
} elseif( $memory_limit == "-1" ){   // memory_limit enabled, but set to unlimited
    $memory_msg = "<b><span class=\"go\">{$mod_strings['LBL_CHECKSYS_MEM_UNLIMITED']}</span></b>";
} else {
    rtrim($memory_limit, 'M');
    $memory_limit_int = (int) $memory_limit;
    if( $memory_limit_int < constant('SUGARCRM_MIN_MEM') ){
        $memory_msg = "<b><span class=\"stop\">{$mod_strings['ERR_CHECKSYS_MEM_LIMIT_1']}" . constant('SUGARCRM_MIN_MEM') . "{$mod_strings['ERR_CHECKSYS_MEM_LIMIT_2']}</span></b>";
    } else {
		$memory_msg = "<b><span class=\"go\">{$mod_strings['LBL_CHECKSYS_OK']} ({$memory_limit})</span></b>";
    }
}


// PHP.ini
$phpIniLocation = get_cfg_var("cfg_file_path");

// disable form if error found
$disabled = $error_found ? 'disabled="disabled"' : '';

///////////////////////////////////////////////////////////////////////////////
////	BEGIN PAGE OUTPUT
$out =<<<EOQ
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Script-Type" content="text/javascript">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>{$mod_strings['LBL_WIZARD_TITLE']} {$next_step}</title>
   <link REL="SHORTCUT ICON" HREF="include/images/sugar_icon.ico">
   <link rel="stylesheet" href="install/install.css" type="text/css">
   <script type="text/javascript" src="install/installCommon.js"></script>
</head>

<body onLoad="document.getElementById('defaultFocus').focus();">
  <table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
    <tr>
      <th width="400">{$mod_strings['LBL_STEP']} {$next_step}: {$mod_strings['LBL_CHECKSYS_TITLE']}</th>
	  <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
    </tr>

    <tr>
      <td colspan="2" width="600">
        <p>{$mod_strings['LBL_CHECKSYS_1']}</p>

        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="StyleDottedHr">
          <tr>
            <th align="left">{$mod_strings['LBL_CHECKSYS_COMPONENT']}</th>
            <th style="text-align: right;">{$mod_strings['LBL_CHECKSYS_STATUS']}</th>
          </tr>

          <tr>
            <td><b>{$mod_strings['LBL_CHECKSYS_PHPVER']}</b></td>
            <td align="right">{$phpVersion}</td>
          </tr>
          <tr>
            <td><strong>{$db_name}</strong></td>
            <td align="right">{$dbStatus}</td>
          </tr>
{$mssqlStatus}
          <tr>
            <td><strong>{$mod_strings['LBL_CHECKSYS_MBSTRING']}</strong></td>
            <td align="right">{$mbstringStatus}</td>
          </tr>
          <tr>
            <td><strong>{$mod_strings['LBL_CHECKSYS_XML']}</strong></td>
            <td align="right">{$xmlStatus}</td>
          </tr>
          <tr>
            <td><b>{$mod_strings['LBL_CHECKSYS_SAFE_MODE']}</b></td>
            <td align="right">{$safeModeStatus}</td>
          </tr>
          <tr>
            <td><b>{$mod_strings['LBL_CHECKSYS_CALL_TIME']}</b></td>
            <td align="right">{$callTimeStatus}</td>
          </tr>
          <tr>
            <td><b>{$mod_strings['LBL_CHECKSYS_MEM']} {$sugarMinMem}M</b></td>
            <td align="right">{$memory_msg}</td>
          </tr>
          <tr>
            <td><b>{$mod_strings['LBL_CHECKSYS_CONFIG']}</b></td>
            <td align="right">{$configStatus}</td>
          </tr>
          <tr>
            <td><b>{$mod_strings['LBL_CHECKSYS_CUSTOM']}</b></td>
            <td align="right">{$customStatus}</td>
          </tr>
          <tr>
            <td><b>{$mod_strings['LBL_CHECKSYS_MODULE']}</b></td>
            <td align="right">{$moduleStatus}</td>
          </tr>
          <tr>
            <td><b>{$mod_strings['LBL_CHECKSYS_DATA']}</b></td>
            <td align="right">{$dataStatus}</td>
          </tr>
          <tr>
            <td><b>{$mod_strings['LBL_CHECKSYS_CACHE']}</b></td>
            <td align="right">{$cacheStatus}</td>
          </tr>
          <tr>
            <td><b>{$mod_strings['LBL_CHECKSYS_SESSION']}{$session_save_path})</b></td>
            <td align="right">{$sessionStatus}</td>
          </tr>

<!-- OPTIONAL COMPONENTS -->
          <tr>
            <th align="left">{$mod_strings['LBL_CHECKSYS_COMPONENT_OPTIONAL']}</th>
            <th style="text-align: right;">{$mod_strings['LBL_CHECKSYS_STATUS']}</th>
          </tr>
          <tr>
            <td><strong>{$mod_strings['LBL_CHECKSYS_IMAP']}</strong></td>
            <td align="right">{$imapStatus}</td>
          </tr>
          <tr>
            <td><strong>{$mod_strings['LBL_CHECKSYS_CURL']}</strong></td>
            <td align="right">{$curlStatus}</td>
          </tr>
          <tr>
            <td><strong>{$mod_strings['LBL_CHECKSYS_PHP_JSON']} (v 1.1.1)</strong></td>
            <td align="right">{$jsonPhpStatus}</td>
          </tr>
          <tr>
            <td><strong>{$mod_strings['LBL_CHECKSYS_ZLIB']}</strong></td>
            <td align="right">{$zlibStatus}</td>
          </tr>

        </table>

        <div align="center" style="margin: 5px;">
          <i>{$mod_strings['LBL_CHECKSYS_PHP_INI']}<br>{$phpIniLocation}</i>
        </div>
      </td>
    </tr>

    <tr>
      <td align="right" colspan="2">
        <hr>
        <form action="install.php" method="post" name="theForm" id="theForm">
        <input type="hidden" name="current_step" value="{$next_step}">
        <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
          <tr>
            <td><input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="{$mod_strings['LBL_HELP']}" /></td>
            <td>
                <input class="button" type="button" name="Re-check" value="{$mod_strings['LBL_CHECKSYS_RECHECK']}" onclick="document.getElementById('goto').value='Re-check';document.getElementById('theForm').submit();" />
            </td>
            <td>
                <input class="button" type="button" name="Back" value="{$mod_strings['LBL_BACK']}" onclick="document.getElementById('theForm').submit();" />
	            <input type="hidden" id="goto" name="goto" value="{$mod_strings['LBL_BACK']}" />
            </td>
            <td><input class="button" type="submit" name="goto" value="{$mod_strings['LBL_NEXT']}" id="defaultFocus"
                 {$disabled} /></td>
          </tr>
        </table>
        </form>
      </td>
    </tr>
  </table><br>
</body>
</html>
EOQ;

echo $out;

////	END PAGEOUTPUT
///////////////////////////////////////////////////////////////////////////////
?>

