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

// $Id: UnifiedSearchAdvancedResults.tpl,v 1.3 2006/08/23 00:13:44 awu Exp $

*}


{if $overlib}
	<script type="text/javascript" src="include/javascript/overlibmws.js"></script>
	<script type="text/javascript" src="include/javascript/overlibmws_iframe.js"></script>
	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
{/if}

<table cellpadding="0" cellspacing="0" width="100%" border="0" class="listView">
	<tr>
		<td colspan="{$colCount}" align="right">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="left" class="listViewPaginationTdS1">{$exportLink}{$mergeLink}{$selectedObjectsSpan}&nbsp;</td>
					<td class="listViewPaginationTdS1" align="right" nowrap="nowrap">
						{if $pageData.urls.startPage}
							<a href="{$pageData.urls.startPage}" {if $prerow}onclick="javascript:return sListView.save_checks(0, '{$moduleString}')"{/if} class="listViewPaginationLinkS1"><img src="{$imagePath}start.gif" alt="Start" align="absmiddle" border="0" height="10" width="11">&nbsp;Start</a>&nbsp;&nbsp;
						{else}
							<img src="{$imagePath}start_off.gif" alt="Start" align="absmiddle" border="0" height="10" width="11">&nbsp;Start&nbsp;&nbsp;
						{/if}
						{if $pageData.urls.prevPage}
							<a href="{$pageData.urls.prevPage}" {if $prerow}onclick="javascript:return sListView.save_checks(0, '{$moduleString}')"{/if} class="listViewPaginationLinkS1"><img src="{$imagePath}previous.gif" alt="Previous" align="absmiddle" border="0" height="10" width="6">&nbsp;Previous</a>&nbsp;&nbsp;
						{else}
							<img src="{$imagePath}previous_off.gif" alt="Previous" align="absmiddle" border="0" height="10" width="6">&nbsp;Previous&nbsp;&nbsp;
						{/if}
							<span class="pageNumbers">({$pageData.offsets.current+1} - {$pageData.offsets.next} of {if $pageData.offsets.totalCounted}{$pageData.offsets.total}{else}{$rowCount+1}}+{/if})</span>&nbsp;&nbsp;
						{if $pageData.urls.nextPage}
							<a href="{$pageData.urls.nextPage}" {if $prerow}onclick="javascript:return sListView.save_checks(40, '{$moduleString}')"{/if} class="listViewPaginationLinkS1">Next&nbsp;<img src="{$imagePath}next.gif" alt="Next" align="absmiddle" border="0" height="10" width="6"></a>&nbsp;&nbsp;
						{else}
							&nbsp;&nbsp;Next&nbsp;<img src="{$imagePath}next_off.gif" alt="Next" align="absmiddle" border="0" height="10" width="6">
						{/if}
						{if $pageData.urls.endPage}
							<a href="{$pageData.urls.endPage}" {if $prerow}onclick="javascript:return sListView.save_checks(980, '{$moduleString}')"{/if} class="listViewPaginationLinkS1">End&nbsp;<img src="{$imagePath}end.gif" alt="End" align="absmiddle" border="0" height="10" width="11"></a></td>
						{else}
							&nbsp;&nbsp;Next&nbsp;<img src="{$imagePath}next_off.gif" alt="Next" align="absmiddle" border="0" height="10" width="6">
						{/if}
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr height="20">
		{if $prerow}
			<td scope="col" class="listViewThS1" NOWRAP>{$checkall}</td>
		{/if}
		{foreach from=$displayColumns key=colHeader item=params}
			<td scope="col" width="{$params.width}" align="{$params.align}" class="listViewThS1" nowrap>
				<slot><a href="{$pageData.urls.orderBy}{$params.orderBy}" class="listViewThLinkS1">{$params.label}&nbsp;<img src="{$imagePath}arrow.gif" alt="Sort" align="absmiddle" border="0"></a></slot>
			</td>
		{/foreach}
	</tr>
		
	{counter start=0 name=rowCounter print=false}
	{foreach from=$data key=id item=rowData}
		{if $rowCounter is even}
			{assign var="_bgColor" value=$bgColor[0]}
			{assign var="_rowColor" value=$rowColor[0]}
		{else}
			{assign var="_bgColor" value=$bgColor[1]}
			{assign var="_rowColor" value=$rowColor[1]}
		{/if}
		<tr height="20" onmouseover="setPointer(this, '{$id}', 'over', '{$_bgColor}', '{$bgHilite}', '{$bgClick}');" onmouseout="setPointer(this, '{$id}', 'out', '{cycle values=$bgColor}', '{$bgHilite}', '{$bgClick}');" onmousedown="setPointer(this, '{$id}', 'click', '{$_bgColor}', '{$bgHilite}', '{$bgClick}');">
			{if $prerow}
				<td class="{$_rowColor}S1" bgcolor="{$_bgColor}"><input onclick='sListView.check_item(this, document.MassUpdate)' type='checkbox' class='checkbox' name='mass[]' value='{$id}'></td>
			{/if}
			{foreach from=$displayColumns key=col item=params}
				<td scope='row' align="{$params.align|default:"left"}" valign=top class="{$_rowColor}S1" bgcolor="{$_bgColor}"><slot>
					{if $params.link}
						{if $params.customCode}
							{sugar_evalcolumn var=$params.customCode rowData=$rowData}
						{else}
							<{$pageData.tag.$id.MAIN} href="index.php?action={$params.action|default:"DetailView"}&module={$params.module|default:$pageData.bean.moduleDir}&record={$rowData[$params.id]|default:$id}&offset={$pageData.offsets.current}&stamp={$pageData.stamp}" class="listViewTdLinkS1">{$rowData.$col}</{$pageData.tag.$id.MAIN}>
						{/if}
					{else}
						{$rowData.$col}
					{/if}
				</slot></td>
			{/foreach}
	    	</tr>
	 	<tr><td colspan="20" class="listViewHRS1"></td></tr>
	 	{counter print=false}
	{/foreach}
	<tr>
		<td colspan="{$colCount}" align="right">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="left" class="listViewPaginationTdS1">{$exportLink}{$mergeLink}{$selectedObjectsSpan}&nbsp;</td>
					<td class="listViewPaginationTdS1" align="right" nowrap="nowrap">
						{if $pageData.urls.startPage}
							<a href="{$pageData.urls.startPage}" onclick="javascript:return sListView.save_checks(0, '{$moduleString}')" class="listViewPaginationLinkS1"><img src="{$imagePath}start.gif" alt="Start" align="absmiddle" border="0" height="10" width="11">&nbsp;Start</a>&nbsp;&nbsp;
						{else}
							<img src="{$imagePath}start_off.gif" alt="Start" align="absmiddle" border="0" height="10" width="11">&nbsp;Start&nbsp;&nbsp;
						{/if}
						{if $pageData.urls.prevPage}
							<a href="{$pageData.urls.prevPage}" onclick="javascript:return sListView.save_checks(0, '{$moduleString}')" class="listViewPaginationLinkS1"><img src="{$imagePath}previous.gif" alt="Previous" align="absmiddle" border="0" height="10" width="6">&nbsp;Previous</a>&nbsp;&nbsp;
						{else}
							<img src="{$imagePath}previous_off.gif" alt="Previous" align="absmiddle" border="0" height="10" width="6">&nbsp;Previous&nbsp;&nbsp;
						{/if}
							<span class="pageNumbers">({$pageData.offsets.current+1} - {$pageData.offsets.next} of {if $pageData.offsets.totalCounted}{$pageData.offsets.end}{else}{$rowCount+1}}+{/if})</span>&nbsp;&nbsp;
						{if $pageData.urls.nextPage}
							<a href="{$pageData.urls.nextPage}" onclick="javascript:return sListView.save_checks(40, '{$moduleString}')" class="listViewPaginationLinkS1">Next&nbsp;<img src="{$imagePath}next.gif" alt="Next" align="absmiddle" border="0" height="10" width="6"></a>&nbsp;&nbsp;
						{else}
							&nbsp;&nbsp;Next&nbsp;<img src="{$imagePath}next_off.gif" alt="Next" align="absmiddle" border="0" height="10" width="6">
						{/if}
						{if $pageData.urls.endPage}
							<a href="{$pageData.urls.endPage}" onclick="javascript:return sListView.save_checks(980, '{$moduleString}')" class="listViewPaginationLinkS1">End&nbsp;<img src="{$imagePath}end.gif" alt="End" align="absmiddle" border="0" height="10" width="11"></a></td>
						{else}
							&nbsp;&nbsp;Next&nbsp;<img src="{$imagePath}next_off.gif" alt="Next" align="absmiddle" border="0" height="10" width="6">
						{/if}
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
