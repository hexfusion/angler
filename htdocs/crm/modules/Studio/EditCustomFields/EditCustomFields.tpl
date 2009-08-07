<!--
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
-->
<!-- $Id: EditCustomFields.tpl,v 1.6 2006/09/05 19:44:49 jenny Exp $ -->
<html>
<head><title>Custom Field</title></head>
<body>
{$header}


<!-- BEGIN: body -->
{if empty($popup)}
<script src='modules/Studio/JSTransaction.js'></script>

<script>
			var jstransaction = new JSTransaction();
			</script>

<script src='modules/Studio/studio.js'></script>
{/if}
<form action="index.php" method="post" name="popup_form" onsubmit='check_form()'>
<table  cellpadding="0" cellspacing="0" border="0" id= 'custom_field_table' >
	<tr><td>
	<table  cellpadding="0" cellspacing="0" border="0">
<tr>
<td align="left" style="padding-bottom: 2px;">
<input type="hidden" name="id" value="{$cf->id}" />
<input type="hidden" name="record" value="{$cf->id}" />
<input type="hidden" name="module" value="Studio" />
<input type="hidden" name="action" value="wizard"/>
<input type="hidden" name="wizard" value="EditCustomFieldsWizard"/>
<input type="hidden" name="option" value="SaveCustomField"/>
<input type="hidden" name="duplicate" value=""/>
<input type="hidden" name="form" value="{$form}" />
<input type="hidden" name="popup" value="{$popup}"/>
<input type="hidden" name="module_name" value="{$custom_module}"/>
<input type="hidden" name="file_type" value="{$FILE_TYPE}" />
<input type="hidden" name="field_count" value="{$FIELD_COUNT}" />

</td>
</tr>
</table>
<script>
var app_list_strings = {$app_list_strings};
function handle_cancel(){literal}{{/literal}
    document.location = 'index.php?module={$RETURN_MODULE}&action={$RETURN_ACTION}&module_name={$custom_module}';
{literal}}{/literal}
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tabForm">
<tr><td nowrap="nowrap">{$MOD.COLUMN_TITLE_DATA_TYPE}:</td><td>{html_options name="data_type" id="data_type" onchange="changeTypeData(document.getElementById('data_type').value);" options=$custom_field_types selected=$cf->data_type }{if !empty($NOEDIT)}<input type='hidden' name='data_type' value={$cf->data_type}>{/if}</td></tr>
<tr><td colspan='2'><hr></td></tr>
<tr><td colspan='2'>

<div id='customfieldbody'>
{$body}
</div>
</td></tr>


	<tr><td align='right' colspan='4'><br>
	{if !empty($cf->id)}
	<input type="button" name="button" value="{$APP.LBL_DELETE_BUTTON_LABEL}"
	title="{$APP.LBL_DELETE_BUTTON_LABEL}" accesskey="{$APP.LBL_DELETE_BUTTON_KEY}"
	class="button" onclick="deleteCustomFieldForm({$popup})"/>&nbsp;
	{/if}
	<input type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}"
	title="{$APP.LBL_SAVE_BUTTON_TITLE}" accesskey="{$APP.LBL_SAVE_BUTTON_KEY}"
	class="button" onclick="submitCustomFieldForm({$popup})"/>
	</td><tr>

</table></td></tr>
	
</table>



<script>
{if empty($body)}
changeTypeData(document.getElementById('data_type').value);

{else}
{literal}
document.getElementById('data_type').disabled='disabled';
if(typeof(document.getElementById('ext1').tagName) != 'undefined' && document.getElementById('ext1').tagName.toLowerCase() == 'select'){
	
	dropdownChanged(document.getElementById('ext1').value);
}
{/literal}
{/if}

</script>

</form>
<!-- END: body -->

