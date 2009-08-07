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

// $Id$

*}


<form name="projectQuickCreate" id="projectQuickCreate" method="POST" action="index.php">
<input type="hidden" name="module" value="Project">
<input type="hidden" name="quote_id" value="{$REQUEST.quote_id}">
<input type="hidden" name="contact_id" value="{$REQUEST.contact_id}">
<input type="hidden" name="email_id" value="{$REQUEST.email_id}">
<input type="hidden" name="account_id" value="{$REQUEST.account_id}">
<input type="hidden" name="return_action" value="{$REQUEST.return_action}">
<input type="hidden" name="opportunity_id" value="{$REQUEST.opportunity_id}">
<input type="hidden" name="return_module" value="{$REQUEST.return_module}">
<input type="hidden" name="return_id" value="{$REQUEST.return_id}">
<input type="hidden" name="action" value='Save'>
<input type="hidden" name="duplicate_parent_id" value="{$REQUEST.duplicate_parent_id}">
<input id='assigned_user_id' name='assigned_user_id' type="hidden" value="{$ASSIGNED_USER_ID}" />
<input type="hidden" name="to_pdf" value='1'>



<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" style="padding-bottom: 2px;">
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" type="submit" name="button" {$saveOnclick|default:"onclick=\"return check_form('ProjectQuickCreate');\""} value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " >
		<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" type="submit" name="button" {$cancelOnclick|default:"onclick=\"this.form.action.value='$RETURN_ACTION'; this.form.module.value='$RETURN_MODULE'; this.form.record.value='$RETURN_ID'\""} value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  ">
		<input title="{$APP.LBL_FULL_FORM_BUTTON_TITLE}" accessKey="{$APP.LBL_FULL_FORM_BUTTON_KEY}" class="button" type="submit" name="button" onclick="this.form.to_pdf.value='0';this.form.action.value='EditView'; this.form.module.value='Project';" value="  {$APP.LBL_FULL_FORM_BUTTON_LABEL}  "></td>
	<td align="right" nowrap><span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td valign="top" class="dataLabel"><slot>{$MOD.LBL_NAME} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
	<td><slot><textarea name='name' cols="50" tabindex='2' rows="1">{$NAME}</textarea></slot></td>
	</tr>
	<tr>
	<td valign="top" class="dataLabel"><slot>{$MOD.LBL_DESCRIPTION}</slot></td>
	<td><slot><textarea name='description' tabindex='3' cols="50" rows="4">{$DESCRIPTION}</textarea></slot></td>
	</tr>
	</table>
	</form>
<script>
	{$additionalScripts}
</script>
