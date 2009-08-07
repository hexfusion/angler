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

// $Id: DetailView.tpl,v 1.6 2006/08/23 00:05:37 awu Exp $

*}


<form action="index.php" method="post" name="DetailView" id="form">

			<input type="hidden" name="module" value="ACLRoles">
			<input type="hidden" name="user_id" value="">
			<input type="hidden" name="record" value="{$ROLE.id}">
			<input type="hidden" name="isDuplicate" value=''>
			<input type='hidden' name='return_record' value='{$RETURN.record}'>
			<input type='hidden' name='return_action' value='{$RETURN.action}'>
			<input type='hidden' name='return_module' value='{$RETURN.module}'>
			<input type="hidden" name="action">

			
		<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="button" onclick="this.form.action.value='EditView'" type="submit" name="button" value="  {$APP.LBL_EDIT_BUTTON} "> <input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="button" onclick="this.form.return_module.value='ACLRoles'; this.form.return_action.value='index'; this.form.isDuplicate.value='1'; this.form.action.value='EditView'" type="submit" name="button" value=" {$APP.LBL_DUPLICATE_BUTTON} "> <input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="button" onclick="this.form.return_module.value='ACLRoles'; this.form.return_action.value='index'; this.form.action.value='Delete'; return confirm('{$APP.NTC_DELETE_CONFIRMATION}')" type="submit" name="button" value=" {$APP.LBL_DELETE_BUTTON} ">
		</form>
		</p>
		<p>
		<TABLE width='100%' class='tabDetailView' border='0' cellpadding=0 cellspacing = 1  >
		<TR>
<td valign='top' width='15%' align='right' class="tabDetailViewDL"><b>{$MOD.LBL_NAME}:</b></td><td class="tabDetailViewDF" width='85%' colspan='3'>{$ROLE.name}</td>
</tr
><TR>
<td valign='top'  width='15%' align='right' class="tabDetailViewDL"><b>{$MOD.LBL_DESCRIPTION}:</b></td><td colspan='3' valign='top'  width='85%' align='left' class="tabDetailViewDF">{$ROLE.description | nl2br}</td>
</tr></table>
</p>
		<p>
		
{include file="modules/ACLRoles/DetailViewBody.tpl" }
