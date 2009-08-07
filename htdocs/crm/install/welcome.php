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

// $Id: welcome.php,v 1.7 2006/08/10 23:35:02 awu Exp $

if( !isset( $install_script ) || !$install_script ){
    die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}
// $mod_strings come from calling page.

$langDropDown = get_select_options_with_id($supportedLanguages, $current_language);

///////////////////////////////////////////////////////////////////////////////
////	START OUTPUT


$out = <<<EOQ
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>{$mod_strings['LBL_TITLE_WELCOME']}</title>
   <link REL="SHORTCUT ICON" HREF="include/images/sugar_icon.ico">
   <link rel="stylesheet" href="install/install.css" type="text/css">
</head>

<body onload="javascript:document.getElementById('defaultFocus').focus();">
	<form action="install.php" method="post" name="form" id="form">
  <table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
    <tr>
      <th width="400">{$mod_strings['LBL_TITLE_WELCOME']} {$setup_sugar_version}<br>
      {$mod_strings['LBL_WELCOME_SETUP_WIZARD']}</th>

      <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
    </tr>

    <tr>
      <td colspan="2" width="600" style="background-image : url(include/images/cube_bg.gif); background-position : right; background-repeat : no-repeat;">
		<p><img src="include/images/sugar_md.png" alt="SugarCRM" width="300" height="25" border="0"></p>
        <p>{$mod_strings['LBL_WELCOME_1']}</p>
        <p>{$mod_strings['LBL_WELCOME_2']}</p>
      </td>
    </tr>

    <tr>
      <td colspan="2" width="600" style="background-image : url(include/images/cube_bg.gif); background-position : right; background-repeat : no-repeat;">
        <p>{$mod_strings['LBL_WELCOME_CHOOSE_LANGUAGE']}: <select name="language">{$langDropDown}</select></p>
      </td>
    </tr>

    <tr>
      <td align="right" colspan="2" height="20">
        <hr>
        <input type="hidden" name="current_step" value="0">
        <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
          <tr>
            <td><input class="button" type="submit" name="goto" value="{$mod_strings['LBL_START']}" id="defaultFocus" /></td>
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
