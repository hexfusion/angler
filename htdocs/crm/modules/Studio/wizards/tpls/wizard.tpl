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

// $Id: wizard.tpl,v 1.7 2006/08/23 00:15:55 awu Exp $

*}

<form name='StudioWizard' >
<input type='hidden' name='action' value='wizard'>
<input type='hidden' name='module' value='Studio'>
<input type='hidden' name='wizard' value='{$wizard}'>
<input type='hidden' name='option' value=''>
<table class='tabform' width='100%' cellpadding=4>
<tr><td colspan=16>{$welcome}</td></tr>
{*<tr><td colspan=2>{html_options  name='option' options=$options selected=$option onchange='document.StudioWizard.submit()'}</td></tr>*}
<tr>
{counter name='optionCounter' assign='optionCounter' start=0}
{foreach from=$options item='display' key='key'}
{if $optionCounter> 0 && $optionCounter % 8 == 0}
</tr><tr>
{else}
{if $optionCounter != 0 }
	<td nowrap width='1'>|</td>
{/if}
{/if}
<td nowrap>
	<a href='#' onclick='document.StudioWizard.option.value="{$key}";document.StudioWizard.submit()'>{$display}</a>
</td>
{counter name='optionCounter'}
{/foreach}
</tr>
<tr><td>{if $wizard != 'StudioWizard'}<input type='submit' class='button' name='back' value='{$MOD.LBL_BTN_BACK}'>{/if}</td><td colspan='16'></td><td width='100%' >&nbsp;</td></tr>
</table>
</form> 
