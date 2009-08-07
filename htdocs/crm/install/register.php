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

// $Id: register.php,v 1.8 2006/06/06 17:57:54 majed Exp $

$suicide = true;
if(isset($install_script)) {
	if($install_script) {
		$suicide = false;
	}
}

if($suicide) {
   // mysterious suicide note
   die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}

if (!isset($_POST['confirm']) || !$_POST['confirm']) {
	$reg_java = file_get_contents( 'http://www.sugarcrm.com/product-registration/registration_java.txt' );
	$reg_html = file_get_contents( 'http://www.sugarcrm.com/product-registration/registration_html.txt' );

	$notConfirmed =<<<CONF
		<p>{$mod_strings['LBL_REG_CONF_1']}</p>
		<p>{$mod_strings['LBL_REG_CONF_2']}</p>
		<!-- begin registration -->
		{$reg_java}
		{$reg_html}
		<!-- end registration -->
CONF;
} else {
	$notConfirmed = $mod_strings['LBL_REG_CONF_3'];
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
   <link rel="stylesheet" href="install/install.css" type="text/css" />
   <script type="text/javascript" src="install/installCommon.js"></script>
</head>
<body onload="javascript:document.getElementById('defaultFocus').focus();">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
<tr>
    <th width="400">{$mod_strings['LBL_STEP']} {$next_step}: {$mod_strings['LBL_REG_TITLE']}</th>
	<th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target="_blank"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
</tr>
<tr>
    <td colspan="2" width="600">{$notConfirmed}</td>
</tr>
<tr>
	<td align="right" colspan="2" height="20">
	<hr>
	<table cellspacing="0" cellpadding="0" border="0" class="stdTable">
		<tr>
		<td>
			<input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="{$mod_strings['LBL_HELP']}" /></td>
		    <td>
				<form action="index.php" method="post" name="appform" id="appform">
                    <input type="hidden" name="default_user_name" value="admin">
                    <input class="button" type="submit" name="next" value="{$mod_strings['LBL_PERFORM_FINISH']}" />
		    	</form>
			</td>
		</tr>
	</table>
	</td>
</tr>
</table>
<br>
</body>
</html>
EOQ;

echo $out;
?>
