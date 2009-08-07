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

// $Id: QuickCreate.tpl,v 1.5 2006/08/26 00:53:03 wayne Exp $

*}


<form name="leadsQuickCreate" id="leadsQuickCreate" method="POST" action="index.php">
<input type="hidden" name="module" value="Leads">
<input type="hidden" name="record" value="">
<input type="hidden" name="contact_id" value="{$REQUEST.contact_id}">
<input type="hidden" name="contact_name" value="{$REQUEST.contact_name}">
<input type="hidden" name="email_id" value="{$REQUEST.email_id}">
<input type="hidden" name="prospect_id" value="{$REQUEST.prospect_id}">			
<input type="hidden" name="account_id" value="{$REQUEST.account_id}">			
<input type="hidden" name="opportunity_id" value="{$REQUEST.opportunity_id}">
<input type="hidden" name="return_action" value="{$REQUEST.return_action}">
<input type="hidden" name="return_module" value="{$REQUEST.return_module}">
<input type="hidden" name="return_id" value="{$REQUEST.return_id}">
<input type="hidden" name="action" value='Save'>
<input type="hidden" name="duplicate_parent_id" value="{$REQUEST.duplicate_parent_id}">
<input id='assigned_user_id' name='assigned_user_id' type="hidden" value="{$ASSIGNED_USER_ID}" />
<input type="hidden" name="to_pdf" value='1'>



<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td align="left" style="padding-bottom: 2px;">
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" type="submit" name="button" {$saveOnclick|default:"onclick=\"return check_form('LeadsQuickCreate');\""} value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " >
		<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" type="submit" name="button" {$cancelOnclick|default:"onclick=\"this.form.action.value='$RETURN_ACTION'; this.form.module.value='$RETURN_MODULE'; this.form.record.value='$RETURN_ID'\""} value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  ">
		<input title="{$APP.LBL_FULL_FORM_BUTTON_TITLE}" accessKey="{$APP.LBL_FULL_FORM_BUTTON_KEY}" class="button" type="submit" name="button" onclick="this.form.to_pdf.value='0';this.form.action.value='EditView'; this.form.module.value='Leads';" value="  {$APP.LBL_FULL_FORM_BUTTON_LABEL}  "></td>
	<td align="right" nowrap><span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel"><slot>{$MOD.LBL_CONTACT_INFORMATION}</slot></h4></th>
	</tr>
	<tr>
	<td  class="dataLabel"><slot>{$MOD.LBL_FIRST_NAME}</slot></td>
	<td class="dataField" nowrap><slot><select  tabindex='1' name='salutation'>{$SALUTATION_OPTIONS}</select>&nbsp;<input name='first_name' tabindex='1' size='11' maxlength='25' type="text" value=""></slot></td>
	<td  class="dataLabel"><slot>{$MOD.LBL_OFFICE_PHONE}</slot></td>
	<td class="dataField"><slot><input name='phone_work' type="text" tabindex='2' size='20' maxlength='25' value=''></slot></td>
	</tr><tr>
	<td class="dataLabel"><slot>{$MOD.LBL_LAST_NAME} <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></slot></td>
	<td class="dataField"><slot><input name='last_name' type="text" tabindex='1' size='25' maxlength='25' value=""></slot></td>
	<td class="dataLabel"><slot>{$MOD.LBL_EMAIL_ADDRESS}</slot></td>
	<td class="dataField"><slot><input name='email1' type="text" tabindex='2' size='35' maxlength='100' value=''></slot></td>
	</tr><tr>
	<td width="15%" class="dataLabel"><slot>{$MOD.LBL_LEAD_SOURCE}</slot></td>
	<td width="35%" class="dataField"><slot><select name='lead_source' tabindex='1'>{$LEAD_SOURCE_OPTIONS}</select></slot></td>
	<td></td><td></td>
	</tr>
	</table>
	</form>
<script>
	{$additionalScripts}
</script>
