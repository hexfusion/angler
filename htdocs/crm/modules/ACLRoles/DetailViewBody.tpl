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

// $Id: DetailViewBody.tpl,v 1.8 2006/08/23 00:05:37 awu Exp $

*}


<TABLE width='100%' class='tabDetailView' border='0' cellpadding=0 cellspacing = 1  >
<TR>
<td></td>
{foreach from=$ACTION_NAMES item="ACTION_NAME" }
	<td nowrap class="tabDetailViewDL" style="text-align: center;"><b>{$ACTION_NAME}</b></td>
{foreachelse}

          <td colspan="2">&nbsp;</td>

{/foreach}
</TR>

{foreach from=$CATEGORIES item="TYPES" key="CATEGORY_NAME"}

<TR>
<td nowrap width='1%' class="tabDetailViewDL" ><b>{$APP_LIST.moduleList[$CATEGORY_NAME]}</b></td>
{foreach from=$TYPES item="ACTIONS" key="TYPE_NAME"}
	{foreach from=$ACTIONS item="ACTION"}


	<td  class="tabDetailViewDF" width='{$TDWIDTH}%' align='center'><div align='center' class="acl{$ACTION.accessName}"><b>{$ACTION.accessName}</b></div></td>
	{/foreach}
{/foreach}

</TR>
	{foreachelse}

         <tr> <td colspan="2">No Actions</td></tr>

{/foreach}
</TABLE>
