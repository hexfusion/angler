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

// $Id: license.php,v 1.18 2006/08/14 23:34:18 awu Exp $

if( !isset( $install_script ) || !$install_script ){
    die('Unable to process script directly.');
}

// setup session variables (and their defaults) if this page has not yet been submitted
if(!isset($_SESSION['license_submitted']) || !$_SESSION['license_submitted']){
    $_SESSION['setup_license_accept'] = false;
}

$checked = (isset($_SESSION['setup_license_accept']) && !empty($_SESSION['setup_license_accept'])) ? 'checked="on"' : '';

require_once("install/install_utils.php");

$license_file = getLicenseContents("LICENSE.txt");

$out =<<<EOQ
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>{$mod_strings['LBL_WIZARD_TITLE']} {$next_step}</title>
   <link REL="SHORTCUT ICON" HREF="include/images/sugar_icon.ico">
   <link rel="stylesheet" href="install/install.css" type="text/css">
   <script type="text/javascript" src="install/license.js"></script>
</head>

<body onload="javascript:toggleNextButton();document.getElementById('defaultFocus').focus();">
<form action="install.php" method="post" name="setConfig" id="form">
  <table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
    <tr>
      <th width="400">{$mod_strings['LBL_STEP']} {$next_step}: {$mod_strings['LBL_LICENSE_ACCEPTANCE']}</th>
      <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target="_blank">
      	<IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a>
      </th>
    </tr>

    <tr>
      <td colspan="2" width="600" style="background-image : url(include/images/cube_bg.gif); background-position:right; background-repeat : no-repeat;">
	    <p><img src="include/images/sugar_md.png" alt="SugarCRM" width="300" height="25" border="0"></p>
        <textarea cols="80" rows="20" readonly>{$license_file}</textarea>
      </td>
    </tr>
    <tr>
      <td align=left>
        <input type="checkbox" class="checkbox" name="setup_license_accept" id="defaultFocus" onClick='toggleNextButton();' {$checked} />
        <a href='javascript:void(0)' onClick='toggleLicenseAccept();toggleNextButton();'>{$mod_strings['LBL_LICENSE_I_ACCEPT']}</a>
      </td>
      <td align=right>
        <input type="button" class="button" name="print_license" value="{$mod_strings['LBL_LICENSE_PRINTABLE']}" 
        	onClick='window.open("index.php?page=licensePrint&language={$current_language}");' />        	
      </td>
    </tr>

    <tr>
      <td align="right" colspan="2">
        <hr>
        <input type="hidden" name="current_step" value="{$next_step}">
        <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
          <tr>
            <td><input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="{$mod_strings['LBL_HELP']}" /></td>
            <td>
                <input class="button" type="button" value="{$mod_strings['LBL_BACK']}" onclick="document.getElementById('form').submit();" />
	            <input type="hidden" name="goto" value="{$mod_strings['LBL_BACK']}" />
            </td>
            <td><input class="button" type="submit" name="goto" value="{$mod_strings['LBL_NEXT']}" id="button_next" disabled="disabled" /></td>
          </tr>
        </table>
      </td>
    </tr>

  </table>
</form>
</body>
</html>
EOQ;

echo $out;
?>
