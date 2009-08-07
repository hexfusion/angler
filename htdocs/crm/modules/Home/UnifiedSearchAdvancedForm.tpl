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

// $Id: UnifiedSearchAdvancedForm.tpl,v 1.6 2006/08/23 00:13:44 awu Exp $

*}


<br />
<form name='UnifiedSearchAdvancedMain' action='index.php' method='get'>
<input type='hidden' name='module' value='Home'>
<input type='hidden' name='query_string' value='test'>
<input type='hidden' name='advanced' value='true'>
<input type='hidden' name='action' value='UnifiedSearch'>
<input type='hidden' name='search_form' value='false'>
	<table width='600' class='tabForm' border='0' cellspacing='1'>
	<tr style='padding-bottom: 10px'>
		<td colspan='8' nowrap>
			<input id='searchFieldMain' class='searchField' type='text' size='80' name='query_string' value='{$query_string}'>
			{if $USE_SEARCH_GIF}
				&nbsp;<input type="image" value="{$LBL_SEARCH_BUTTON_LABEL}" src="{$IMAGE_PATH}searchButton.gif" align="top" width="25" height="17" class="searchButton">
			{else}
				&nbsp;<input type="submit" class="button" value="{$LBL_SEARCH_BUTTON_LABEL}">				
			{/if}
		</td>
	</tr>
	<tr height='5'><td></td></tr>
	<tr style='padding-top: 10px;'>
	{foreach from=$MODULES_TO_SEARCH name=m key=module item=info}
		<td width='20' style='padding: 0px 10px 0px 0px;' >
			<input class='checkbox' id='cb_{$module}_f' type='checkbox' name='search_mod_{$module}' value='true' {if $info.checked}checked{/if}>
		</td>
		<td width='130' style='padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px; cursor: hand; cursor: pointer' onclick="document.getElementById('cb_{$module}_f').checked = !document.getElementById('cb_{$module}_f').checked;">
			{$info.translated}
		</td>
	{if $smarty.foreach.m.index % 4  == 3} 
		</tr><tr style='padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px'>
	{/if}
	{/foreach}
	</tr>
	</table>
</form>
