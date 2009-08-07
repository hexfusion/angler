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
/*********************************************************************************
 * $Id: licensePrint.php,v 1.10 2006/08/25 22:48:08 awu Exp $
 * Description:  printable license page.
 ********************************************************************************/
require_once("install/language/{$_GET['language']}.lang.php");
require_once("install/install_utils.php");

$license_file = wordwrap(getLicenseContents("LICENSE.txt"),75);

$out =<<<EOQ
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Style-Type" content="text/css">   
   <title>{$mod_strings['LBL_LICENSE_TITLE_2']}</title>
   <link REL="SHORTCUT ICON" HREF="include/images/sugar_icon.ico">
   <link rel="stylesheet" href="install/install.css" type="text/css">   
</head>

<body>
  <table cellspacing="0" cellpadding="0" border="0" align="center" class="shell" width="90%">
    <tr>
      <td>
        <input type="button" name="close_windows" value=" {$mod_strings['LBL_CLOSE']} " onClick='window.close();' />
        <input type="button" name="print_license" value=" {$mod_strings['LBL_PRINT']} " onClick='window.print();' />
      </td>
    </tr>
    <tr>
      <td>
        <pre>
            {$license_file}
        </pre>
      </td>
    </tr>
    <tr>
      <td>
        <input type="button" name="close_windows" value=" {$mod_strings['LBL_CLOSE']} " onClick='window.close();' />
        <input type="button" name="print_license" value=" {$mod_strings['LBL_PRINT']} " onClick='window.print();' />
      </td>
    </tr>
  </table>
</body>
</html>
EOQ;
echo $out;
?>
