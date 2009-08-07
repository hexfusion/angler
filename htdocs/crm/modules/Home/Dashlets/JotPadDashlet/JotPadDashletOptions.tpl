{*

/**
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
 */

// $Id: JotPadDashletOptions.tpl,v 1.4 2006/08/23 00:13:44 awu Exp $

*}


<div style='width: 500px'>
<form name='configure_{$id}' action="index.php" method="post" onSubmit='return SUGAR.dashlets.postForm("configure_{$id}", SUGAR.sugarHome.uncoverPage);'>
<input type='hidden' name='id' value='{$id}'>
<input type='hidden' name='module' value='Home'>
<input type='hidden' name='action' value='ConfigureDashlet'>
<input type='hidden' name='to_pdf' value='true'>
<input type='hidden' name='configure' value='true'>
<table width="400" cellpadding="0" cellspacing="0" border="0" class="tabForm" align="center">
<tr>
    <td valign='top' nowrap class='dataLabel'>{$titleLbl}</td>
    <td valign='top' class='dataField'>
    	<input class="text" name="title" size='20' value='{$title}'>
    </td>
</tr>
<tr>
    <td valign='top' nowrap class='dataLabel'>{$heightLbl}</td>
    <td valign='top' class='dataField'>
    	<input class="text" name="height" size='3' value='{$height}'>
    </td>
</tr>
<tr>
    <td align="right" colspan="2">
        <input type='submit' class='button' value='{$saveLbl}'>
   	</td>
</tr>
</table>
</form>
</div>
