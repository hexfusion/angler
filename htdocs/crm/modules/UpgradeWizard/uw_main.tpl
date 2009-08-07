{if false}
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

// $Id: uw_main.tpl,v 1.17 2006/08/24 00:16:59 chris Exp $
{/if}

<script type="text/javascript" language="Javascript" src="modules/UpgradeWizard/upgradeWizard.js"></script>

{$UW_JS}

<div id="title">
{$UW_TITLE}
</div>

<div id="progress" style="display:none;">
{$UW_PROGRESS}
</div>

<div id="message" style="display:none;">
{$UW_MESSAGE}
</div>

<div id="nav">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td style="padding-bottom: 2px;">
<form action="index.php" method="post" name="UpgradeWizardForm" id="form">
	<input type="hidden" name="module" value="UpgradeWizard">
	<input type="hidden" name="action" value="index">
	<input type="hidden" name="step" value="{$UW_STEP}">
	<input type="hidden" name="overwrite_files" id="over">
	<input type="hidden" name="schema_change" id="schema">
	<input type="hidden" name="overwrite_files_serial" id="overwrite_files_serial">
	<input type="hidden" name="addTaskReminder" id="addTaskReminder">
	<input type="hidden" name="addEmailReminder" id="addEmailReminder">
	
		{if $showBack}
			<input	title		= "{$MOD.LBL_BUTTON_BACK}" 
					class		= "button"
					onclick		= "this.form.step.value='{$STEP_BACK}';" 
					type		= "submit"
					value		= "  {$MOD.LBL_BUTTON_BACK}  ">
		{/if}
		{if $showCancel}
			<input	title		= "{$MOD.LBL_BUTTON_CANCEL}" 
					class		= "button"
					onclick		= "this.form.step.value='{$STEP_CANCEL}';" 
					type		= "submit"
					value		= "  {$MOD.LBL_BUTTON_CANCEL}  ">
		{/if}
		{if $showRecheck}
			<input	title		= "{$MOD.LBL_BUTTON_RECHECK}" 
					class		= "button"
					onclick		= "this.form.step.value='{$STEP_RECHECK}';" 
					type		= "submit"
					value		= "  {$MOD.LBL_BUTTON_RECHECK}  ">
		{/if}
		{if $showNext}
			<input	title		= "{$MOD.LBL_BUTTON_NEXT}" 
					class		= "button"
					onclick		= "this.form.step.value='{$STEP_NEXT}'; handlePreflight('{$step}');" 
					type		= "submit"
					value		= "  {$MOD.LBL_BUTTON_NEXT}  "
					id			= "next_button" >
		{/if}
</form>
		</td>
	</tr>
</table>
</div>
<br />
<div id="main">
<table width="100%" border="0" cellpadding="0" cellpadding="0" class="tabDetailView">
{if $frozen}
	<tr>
		<td colspan="2">
			<span class="error"><b>{$frozen}</b></span>
		</td>
	</tr>
{/if}

	<tr>
		<td width="25%" class="tabDetailViewDL" rowspan="2"><slot>
			{$CHECKLIST}
		</slot></td>
		<td width="75%" class="tabDetailViewDF"><slot>
			{$UW_MAIN}&nbsp;
		</slot></td>
	</tr>
	
	<tr>
		<td valign="top" class="tabDetailViewDF">
			&nbsp;<br />
			{$UW_HISTORY}
		</td>
	</tr>
</table>
</div>
