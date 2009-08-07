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

// $Id: EditView.tpl,v 1.13 2006/08/27 12:59:27 majed Exp $

*}


<script>
{literal}
function set_focus(){
	document.getElementById('name').focus();
}
{/literal}
</script>
<form method='POST' name='EditView'>
<input type='hidden' name='record' value='{$ROLE.id}'>
<input type='hidden' name='module' value='ACLRoles'>
<input type='hidden' name='action' value='Save'>
<input type='hidden' name='return_record' value='{$RETURN.record}'>
<input type='hidden' name='return_action' value='{$RETURN.action}'>
<input type='hidden' name='return_module' value='{$RETURN.module}'> &nbsp;
<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button" onclick="this.form.action.value='Save';return check_form('EditView');" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " > &nbsp;
<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}"   class='button' accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" type='submit' name='save' value="  {$APP.LBL_CANCEL_BUTTON_LABEL} " class='button' onclick='document.EditView.action.value="{$RETURN.action}";document.EditView.module.value="{$RETURN.module}";document.EditView.record.value="{$RETURN.record}";document.EditView.submit();'>
</p>
<p>
<TABLE width='100%' class="tabForm"  border='0' cellpadding=0 cellspacing = 0  >
<TR>
<td class="dataLabel" align='right'>{$MOD.LBL_NAME}:</td><td class="dataField">
<input id='name' name='name' type='text' value='{$ROLE.name}'>
</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
<tr>
<td class="dataLabel" align='right'>{$MOD.LBL_DESCRIPTION}:</td>
<td class="dataField"><textarea name='description' cols="80" rows="8">{$ROLE.description}</textarea></td>
</tr>
</table>
</p>
<b>{$MOD.LBL_EDIT_VIEW_DIRECTIONS}</b>
<TABLE width='100%' class='tabDetailView' border='0' cellpadding=0 cellspacing = 1  >
<TR>
<td></td>

{foreach from=$ACTION_NAMES item="ACTION_NAME" }
	<td nowrap align='center' class="tabDetailViewDL"><div align='center'><b>{$ACTION_NAME}</b></div></td>
{foreachelse}

          <td colspan="2">&nbsp;</td>

{/foreach}
</TR>
{literal}

	{/literal}
{foreach from=$CATEGORIES item="TYPES" key="CATEGORY_NAME"}
<TR>
<td nowrap width='1%' class="tabDetailViewDL"><b>{$APP_LIST.moduleList[$CATEGORY_NAME]}</b></td>
	{foreach from=$TYPES item="ACTIONS"}
		{foreach from=$ACTIONS item="ACTION"}
	
	<td  width='{$TDWIDTH}%' class="tabDetailViewDF" style="text-align: center;" ondblclick="toggleDisplay('{$ACTION.id}');//document.getElementById('act_guid{$ACTION.id}').focus()">
	<div  style="display: none" id="{$ACTION.id}">
	<select class="acl{$ACTION.accessName}" name='act_guid{$ACTION.id}' id = 'act_guid{$ACTION.id}' onblur="document.getElementById('{$ACTION.id}link').innerHTML=this.options[this.selectedIndex].text; toggleDisplay('{$ACTION.id}');" onchange="document.getElementById('{$ACTION.id}link').innerHTML=this.options[this.selectedIndex].text; toggleDisplay('{$ACTION.id}');">
   		{html_options options=$ACTION.accessOptions selected=$ACTION.aclaccess }
	</select>
	</div>
	<div class="acl{$ACTION.accessName}" style="display: inline;" id="{$ACTION.id}link">{$ACTION.accessName}</div>
	</td>
	{/foreach}
	{/foreach}


</TR>
	{foreachelse}

         <tr> <td colspan="2">No Actions Defined</td></tr>

{/foreach}
</TABLE>
<div style="padding-top:10px;">
&nbsp;<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button" onclick="this.form.action.value='Save';return check_form('EditView');" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " /> &nbsp;
<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}"   class='button' type='submit' name='save' value="  {$APP.LBL_CANCEL_BUTTON_LABEL} " class='button' onclick='document.EditView.action.value="{$RETURN.action}";document.EditView.module.value="{$RETURN.module}";document.EditView.record.value="{$RETURN.record}";document.EditView.submit();' />
</div>
</form>
