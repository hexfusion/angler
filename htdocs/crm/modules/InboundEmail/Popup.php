<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
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
 * $Id$
 * Description:
 ********************************************************************************/
// cn: bug 6078: zlib breaks test-settings
$iniError = '';
if(ini_get('zlib.output_compression') == 1) { // ini_get() returns 1/0, not value
	if(!ini_set('zlib.output_compression', 'Off')) { // returns False on failure
		$iniError = $mod_strings['ERR_INI_ZLIB'];
	}
}
 
// hack to allow "&", "%" and "+" through a $_GET var
// set by ie_test_open_popup() javascript call
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
foreach($_REQUEST as $k => $v) {
	$v = str_replace('::amp::', '&', $v);
	$v = str_replace('::plus::', '+', $v);
	$v = str_replace('::percent::', '%', $v);
	$_REQUEST[$k] = $v;
}

if(ob_get_level() > 0) {
	ob_end_clean();
}

if(ob_get_level() < 1) {
	ob_start();
}

require_once('modules/InboundEmail/InboundEmail.php');
require_once('modules/InboundEmail/language/en_us.lang.php');
global $theme;

$title				= '';
$msg				= '';
$tls				= '';
$cert				= '';
$ssl				= '';
$notls				= '';
$novalidate_cert	= '';
$useSsl				= false;

///////////////////////////////////////////////////////////////////////////////
////	TITLES
if($_REQUEST['target'] == 'Popup') {
	$title = $mod_strings['LBL_POPUP_TITLE'];
	$msg = $mod_strings['LBL_TEST_WAIT_MESSAGE'];
}

if(isset($_REQUEST['ssl']) && ($_REQUEST['ssl'] == "true" || $_REQUEST['ssl'] == 1)) {
	$msg .= $mod_strings['LBL_FIND_SSL_WARN'];
	$useSsl = true;
} 

////	END TITLES
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	COMMON CODE
echo '
<HTML>
	<HEAD>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>SugarCRM - Commercial Open Source CRM</title>
		<style type="text/css">@import url("themes/'.$theme.'/style.css?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"); </style>
		<script type="text/javascript">
				function setMailbox(box) {
					var mb = opener.document.getElementById("mailbox");
					mb.value = box;
				}
		</script>

	</HEAD>
	<body style="margin: 10px">
	<p>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>
					<img src="themes/'.$theme.'/images/h3Arrow.gif" width="11" height="11" border="0" alt="'.$mod_strings['LBL_POPUP_TITLE'].'">
				</td>
				<td>
					<h3>&nbsp;'.$title.'</h3>
				</td>
			</tr>
			<tr>
				<td></td>
				<td valign="top">
					<div id="msg">
					'.$msg.'
					</div>
					<div id="tic"></div>
					<div id="err">'.$iniError.'</div>
				</td>
			</tr>';
			
if($_REQUEST['target'] == 'Popup') {
	echo '	<tr>
				<td></td>
				<td>
					<form name="form">
					<input name="close" type="button" title="'.$mod_strings['LBL_CLOSE_POPUP'].'"  value="    '.$mod_strings['LBL_CLOSE_POPUP'].'    " onClick="window.close()">
					</form>
				</td>
			</tr>';
}

echo '	</table>';

sleep(1);
ob_flush();
flush();
ob_end_flush();
ob_start();

ob_flush();
flush();
ob_end_flush();
ob_start();


$ie					= new InboundEmail();
$ie->email_user		= $_REQUEST['email_user'];
$ie->server_url		= $_REQUEST['server_url'];
$ie->port			= $_REQUEST['port'];
$ie->protocol		= $_REQUEST['protocol'];
$ie->email_password	= str_rot13($_REQUEST['email_password']);
$ie->mailbox		= $_REQUEST['mailbox'];

if($_REQUEST['target'] == 'Popup') {
	$msg = $ie->connectMailserver(true);
}
////	END COMMON CODE
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	COMPLETE RENDERING OF THE POPUP
echo '
<script type="text/javascript">
	function switchMsg() {
		if(typeof(document.getElementById("msg")) != "undefined") {
			document.getElementById("msg").innerHTML = "'.$msg.'";
		}
	}

	switchMsg();
</script>
	</body>
</html>';
ob_end_flush();
?>
