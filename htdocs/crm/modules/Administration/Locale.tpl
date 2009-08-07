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

// $Id: Locale.tpl,v 1.5 2006/09/02 01:25:29 chris Exp $

*}


<script type="text/javascript">
	var ERR_NO_SINGLE_QUOTE = '{$APP.ERR_NO_SINGLE_QUOTE}';
{literal}
	function verify_data(formName) {
		var f = document.getElementById(formName);
		
		for(i=0; i<f.elements.length; i++) {
			if(f.elements[i].value == "'") {
				alert(ERR_NO_SINGLE_QUOTE + " " + f.elements[i].name);
				return false;
			}
		}
		
		return true;
	}
</script>
{/literal}

<BR>
<form id="ConfigureSettings" name="ConfigureSettings" enctype='multipart/form-data' method="POST" 
	action="index.php?module=Administration&action=Locale&process=true">

<span class='error'>{$error.main}</span>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td style="padding-bottom: 2px;">
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" 
			accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" 
			class="button"  
			type="submit" 
			name="save" 
			onclick="return verify_data('ConfigureSettings');"
			value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " >
		&nbsp;<input title="{$MOD.LBL_CANCEL_BUTTON_TITLE}"  onclick="document.location.href='index.php?module=Administration&action=index'" class="button"  type="button" name="cancel" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " > </td>	
	</tr>
</table>

<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel">{$MOD.LBL_LOCALE_DEFAULT_SYSTEM_SETTINGS}</h4></th>
	</tr><tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td  class="dataLabel" width="200">{$MOD.LBL_LOCALE_DEFAULT_DATE_FORMAT}: </td>
		<td  class="dataField">
			{html_options name='default_date_format' selected=$config.default_date_format options=$config.date_formats}
		</td>
		<td  class="dataLabel" width="200">{$MOD.LBL_LOCALE_DEFAULT_TIME_FORMAT}: </td>
		<td  class="dataField">
			{html_options name='default_time_format' selected=$config.default_time_format options=$config.time_formats}
		</td>
	</tr><tr>
		<td  class="dataLabel">{$MOD.LBL_LOCALE_DEFAULT_LANGUAGE}: </td>
		<td  class="dataField">
			{html_options name='default_language' selected=$config.default_language options=$LANGUAGES}
		</td>
	</tr>
	</tr><tr>
		<td  class="dataLabel" valign="top">{$MOD.LBL_LOCALE_DEFAULT_NAME_FORMAT}: </td>
		<td  class="dataField">
			<input onkeyup="setPreview();" onkeydown="setPreview();" id="default_locale_name_format" type="text" name="default_locale_name_format" value="{$config.default_locale_name_format}">
			<br>
			{$MOD.LBL_LOCALE_NAME_FORMAT_DESC}
		</td>
		<td  class="dataLabel" valign="top">{$MOD.LBL_LOCALE_EXAMPLE_NAME_FORMAT}: </td>
		<td  class="dataField" valign="top"><input name="no_value" id="nameTarget" value="" disabled></td>		
	</tr>

	</table>
</td></tr>
</table>

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
	<tr>
		<th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel">{$MOD.LBL_LOCALE_DEFAULT_CURRENCY}</h4></th>
	</tr><tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td  class="dataLabel" width="200">{$MOD.LBL_LOCALE_DEFAULT_CURRENCY_NAME}: </td>
		<td  class="dataField">
			<input type='text' size='25' name='default_currency_name' value='{$config.default_currency_name}'
		</td>
		<td  class="dataLabel" width="200">{$MOD.LBL_LOCALE_DEFAULT_CURRENCY_SYMBOL}: </td>
		<td  class="dataField">
			<input type='text' size='4' name='default_currency_symbol'  value='{$config.default_currency_symbol}' >
		</td>
	</tr><tr>
		<td  class="dataLabel" width="200">{$MOD.LBL_LOCALE_DEFAULT_CURRENCY_ISO4217}: </td>
		<td  class="dataField">
			<input type='text' size='4' name='default_currency_iso4217' value='{$config.default_currency_iso4217}'>
		</td>
		<td  class="dataLabel">{$MOD.LBL_LOCALE_DEFAULT_NUMBER_GROUPING_SEP}: </td>
		<td  class="dataField">
			<input type='text' size='3' maxlength='1' name='default_number_grouping_seperator' value='{$config.default_number_grouping_seperator}'>
		</td>
	</tr><tr>
		<td  class="dataLabel">{$MOD.LBL_LOCALE_DEFAULT_DECIMAL_SEP}: </td>
		<td  class="dataField">
			<input type='text' size='3' maxlength='1' name='default_decimal_seperator'  value='{$config.default_decimal_seperator}'>
		</td>
		<td  class="dataLabel"></td>
		<td  class="dataField"></td>		
	</tr>
</table>
</td></tr>
</table>

<br />

<div style="padding-top: 2px;">
<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button"  type="submit" name="save" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " />
		&nbsp;<input title="{$MOD.LBL_CANCEL_BUTTON_TITLE}"  onclick="document.location.href='index.php?module=Administration&action=index'" class="button"  type="button" name="cancel" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " />
</div>
{$JAVASCRIPT}
</form>

<script language="Javascript" type="text/javascript">
{$getNameJs}
</script>
