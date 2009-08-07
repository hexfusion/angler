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

// $Id: ListViewGeneric.tpl,v 1.34 2006/08/25 21:43:01 wayne Exp $

*}


<form action='index.php' method='post' name='MassUpdate'  id="MassUpdate" onsubmit="return check_form('MassUpdate');">
<input type='hidden' name='action' value='index' />
<input type='hidden' name='delete' value='false' />
<input type='hidden' name='merge' value='false' />
<input type='hidden' name='module' value='{$pageData.bean.moduleDir}' />
{if $overlib}
	<script type='text/javascript' src='include/javascript/overlibmws.js'></script>
	<script type='text/javascript' src='include/javascript/overlibmws_iframe.js'></script>
	<div id='overDiv' style='position:absolute; visibility:hidden; z-index:1000;'></div>
{/if}
{if $prerow}
	{$multiSelectData}
{/if}
<table cellpadding='0' cellspacing='0' width='100%' border='0' class='listView'>
	<tr>
		<td colspan='{$colCount+1}' align='right'>
			<table border='0' cellpadding='0' cellspacing='0' width='100%'>
				<tr>
					<td align='left' class='listViewPaginationTdS1'>{$exportLink}{$mergeLink}{$mergedupLink}{$selectedObjectsSpan}&nbsp;</td>
					<td class='listViewPaginationTdS1' align='right' nowrap='nowrap' id='listViewPaginationButtons'>						
						{if $pageData.urls.startPage}
							<a href='{$pageData.urls.startPage}' {if $prerow}onclick="return sListView.save_checks(0, '{$moduleString}')"{/if} class='listViewPaginationLinkS1'><img src='{$imagePath}start.gif' alt='{$navStrings.start}' align='absmiddle' border='0' width='13' height='11'>&nbsp;{$navStrings.start}</a>&nbsp;
						{else}
							<img src='{$imagePath}start_off.gif' alt='{$navStrings.start}' align='absmiddle' border='0' width='13' height='11'>&nbsp;{$navStrings.start}&nbsp;&nbsp;
						{/if}
						{if $pageData.urls.prevPage}
							<a href='{$pageData.urls.prevPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.prev}, '{$moduleString}')"{/if} class='listViewPaginationLinkS1'><img src='{$imagePath}previous.gif' alt='{$navStrings.previous}' align='absmiddle' border='0' width='8' height='11'>&nbsp;{$navStrings.previous}</a>&nbsp;
						{else}
							<img src='{$imagePath}previous_off.gif' alt='{$navStrings.previous}' align='absmiddle' border='0' width='8' height='11'>&nbsp;{$navStrings.previous}&nbsp;
						{/if}
							<span class='pageNumbers'>({if $pageData.offsets.lastOffsetOnPage == 0}0{else}{$pageData.offsets.current+1}{/if} - {$pageData.offsets.lastOffsetOnPage} {$navStrings.of} {if $pageData.offsets.totalCounted}{$pageData.offsets.total}{else}{$pageData.offsets.total}{if $pageData.offsets.lastOffsetOnPage != $pageData.offsets.total}+{/if}{/if})</span>
						{if $pageData.urls.nextPage}
							&nbsp;<a href='{$pageData.urls.nextPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.next}, '{$moduleString}')"{/if} class='listViewPaginationLinkS1'>{$navStrings.next}&nbsp;<img src='{$imagePath}next.gif' alt='{$navStrings.next}' align='absmiddle' border='0' width='8' height='11'></a>&nbsp;
						{else}
							&nbsp;{$navStrings.next}&nbsp;<img src='{$imagePath}next_off.gif' alt='{$navStrings.next}' align='absmiddle' border='0' width='8' height='11'>
						{/if}
						{if $pageData.urls.endPage  && $pageData.offsets.total != $pageData.offsets.lastOffsetOnPage}
							<a href='{$pageData.urls.endPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.end}, '{$moduleString}')"{/if} class='listViewPaginationLinkS1'>{$navStrings.end}&nbsp;<img src='{$imagePath}end.gif' alt='{$navStrings.end}' align='absmiddle' border='0' width='13' height='11'></a></td>
						{elseif !$pageData.offsets.totalCounted || $pageData.offsets.total == $pageData.offsets.lastOffsetOnPage}
							&nbsp;{$navStrings.end}&nbsp;<img src='{$imagePath}end_off.gif' alt='{$navStrings.end}' align='absmiddle' border='0' width='13' height='11'>
						{/if}
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr height='20'>
		{if $prerow}
			<td scope='col' class='listViewThS1' nowrap width='1%'>
				<input type='checkbox' class='checkbox' name='massall' value='' onclick='sListView.check_all(document.MassUpdate, "mass[]", this.checked);' />
			</td>
		{/if}
		{counter start=0 name="colCounter" print=false assign="colCounter"}
		{foreach from=$displayColumns key=colHeader item=params}
			<td scope='col' width='{$params.width}%' class='listViewThS1' nowrap>
				<span sugar="sugar{$colCounter}"><div style='white-space: nowrap;'width='100%' align='{$params.align|default:'left'}'>
                {if $params.sortable|default:true}
	                <a href='{$pageData.urls.orderBy}{$params.orderBy|default:$colHeader|lower}' class='listViewThLinkS1'>{sugar_translate label=$params.label module=$pageData.bean.moduleDir}&nbsp;&nbsp;
					{if $params.orderBy|default:$colHeader|lower == $pageData.ordering.orderBy}
						{if $pageData.ordering.sortOrder == 'ASC'}
							<img border='0' src='{$imagePath}arrow_down.{$arrowExt}' width='{$arrowWidth}' height='{$arrowHeight}' align='absmiddle' alt='{$arrowAlt}'>
						{else}
							<img border='0' src='{$imagePath}arrow_up.{$arrowExt}' width='{$arrowWidth}' height='{$arrowHeight}' align='absmiddle' alt='{$arrowAlt}'>
						{/if}
					{else}
						<img border='0' src='{$imagePath}arrow.{$arrowExt}' width='{$arrowWidth}' height='{$arrowHeight}' align='absmiddle' alt='{$arrowAlt}'>
					{/if}
					</a>
				{else}
					{sugar_translate label=$params.label module=$pageData.bean.moduleDir}
				{/if}
				</div></span sugar='sugar{$colCounter}'>
			</td>
			{counter name="colCounter"}
		{/foreach}
		{if !empty($quickViewLinks)}
		<td scope='col' class='listViewThS1' nowrap width='1%'>&nbsp;</td>
		{/if}
	</tr>
		
	{foreach name=rowIteration from=$data key=id item=rowData}
		{if $smarty.foreach.rowIteration.iteration is odd}
			{assign var='_bgColor' value=$bgColor[0]}
			{assign var='_rowColor' value=$rowColor[0]}
		{else}
			{assign var='_bgColor' value=$bgColor[1]}
			{assign var='_rowColor' value=$rowColor[1]}
		{/if}
		<tr height='20' onmouseover="setPointer(this, '{$id}', 'over', '{$_bgColor}', '{$bgHilite}', '');" onmouseout="setPointer(this, '{$rowData[$params.id]|default:$rowData.ID}', 'out', '{$_bgColor}', '{$bgHilite}', '');" onmousedown="setPointer(this, '{$id}', 'click', '{$_bgColor}', '{$bgHilite}', '');">
			{if $prerow}
			<td width='1%' class='{$_rowColor}S1' bgcolor='{$_bgColor}' nowrap>
					<input onclick='sListView.check_item(this, document.MassUpdate)' type='checkbox' class='checkbox' name='mass[]' value='{$rowData[$params.id]|default:$rowData.ID}'>
			</td>
			{/if}
			{counter start=0 name="colCounter" print=false assign="colCounter"}
			{foreach from=$displayColumns key=col item=params}
				<td scope='row' align='{$params.align|default:'left'}' valign=top class='{$_rowColor}S1' bgcolor='{$_bgColor}'><span sugar="sugar{$colCounter}b">
					{if $params.link && !$params.customCode}				
						<{$pageData.tag.$id[$params.ACLTag]|default:$pageData.tag.$id.MAIN} href='index.php?action={$params.action|default:'DetailView'}&module={if $params.dynamic_module}{$rowData[$params.dynamic_module]}{else}{$params.module|default:$pageData.bean.moduleDir}{/if}&record={$rowData[$params.id]|default:$rowData.ID}&offset={$pageData.offsets.current+$smarty.foreach.rowIteration.iteration}&stamp={$pageData.stamp}' class='listViewTdLinkS1'>{$rowData.$col}</{$pageData.tag.$id[$params.ACLTag]|default:$pageData.tag.$id.MAIN}>
					{elseif $params.customCode}
						{sugar_evalcolumn var=$params.customCode rowData=$rowData}
					{elseif $params.currency_format}
						{sugar_currency_format 
							var=$rowData.$col 
							round=$params.currency_format.round 
							decimals=$params.currency_format.decimals 
							symbol=$params.currency_format.symbol
						}
					{elseif $params.type == 'bool'}
							<input type='checkbox' disabled=disabled class='checkbox'
							{if !empty($rowData[$col])}
								checked=checked
							{/if}
							/>
					
					{else}	
						{$rowData.$col}
					{/if}
				</span sugar='sugar{$colCounter}b'></td>
				{counter name="colCounter"}
			{/foreach}
			{if !empty($quickViewLinks)}
			<td width='1%' class='{$_rowColor}S1' bgcolor='{$_bgColor}' nowrap>
				{if $pageData.access.edit}
					<a title='{$editLinkString}' href='index.php?action=EditView&module={$params.module|default:$pageData.bean.moduleDir}&record={$rowData[$params.id]|default:$rowData.ID}&offset={$pageData.offsets.current+$smarty.foreach.rowIteration.iteration}&stamp={$pageData.stamp}&return_module={$params.module|default:$pageData.bean.moduleDir}'><img border=0 src={$imagePath}edit_inline.gif></a>
				{/if}
				{if $pageData.access.view}
					<a title='{$viewLinkString}' href='index.php?action=DetailView&module={$params.module|default:$pageData.bean.moduleDir}&record={$rowData[$params.id]|default:$rowData.ID}&offset={$pageData.offsets.current+$smarty.foreach.rowIteration.iteration}&stamp={$pageData.stamp}&return_module={$params.module|default:$pageData.bean.moduleDir}'><img border=0 src={$imagePath}view_inline.gif></a>
				{/if}
			</td>
			{/if}
	    	</tr>
	 	<tr><td colspan='20' class='listViewHRS1'></td></tr>
	{/foreach}
	<tr>
		<td colspan='{$colCount+1}' align='right'>
			<table border='0' cellpadding='0' cellspacing='0' width='100%'>
				<tr>
					<td align='left' class='listViewPaginationTdS1'>{$exportLink}{$mergeLink}{$mergedupLink}{$selectedObjectsSpan}&nbsp;</td>
					<td class='listViewPaginationTdS1' align='right' nowrap='nowrap' id='listViewPaginationButtons'>						
						{if $pageData.urls.startPage}
							<a href='{$pageData.urls.startPage}' {if $prerow}onclick="return sListView.save_checks(0, '{$moduleString}')"{/if} class='listViewPaginationLinkS1'><img src='{$imagePath}start.gif' alt='{$navStrings.start}' align='absmiddle' border='0' width='13' height='11'>&nbsp;{$navStrings.start}</a>&nbsp;
						{else}
							<img src='{$imagePath}start_off.gif' alt='{$navStrings.start}' align='absmiddle' border='0' width='13' height='11'>&nbsp;{$navStrings.start}&nbsp;&nbsp;
						{/if}
						{if $pageData.urls.prevPage}
							<a href='{$pageData.urls.prevPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.prev}, '{$moduleString}')"{/if} class='listViewPaginationLinkS1'><img src='{$imagePath}previous.gif' alt='{$navStrings.previous}' align='absmiddle' border='0' width='8' height='11'>&nbsp;{$navStrings.previous}</a>&nbsp;
						{else}
							<img src='{$imagePath}previous_off.gif' alt='{$navStrings.previous}' align='absmiddle' border='0' width='8' height='11'>&nbsp;{$navStrings.previous}&nbsp;
						{/if}
							<span class='pageNumbers'>({if $pageData.offsets.lastOffsetOnPage == 0}0{else}{$pageData.offsets.current+1}{/if} - {$pageData.offsets.lastOffsetOnPage} {$navStrings.of} {if $pageData.offsets.totalCounted}{$pageData.offsets.total}{else}{$pageData.offsets.total}{if $pageData.offsets.lastOffsetOnPage != $pageData.offsets.total}+{/if}{/if})</span>
						{if $pageData.urls.nextPage}
							&nbsp;<a href='{$pageData.urls.nextPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.next}, '{$moduleString}')"{/if} class='listViewPaginationLinkS1'>{$navStrings.next}&nbsp;<img src='{$imagePath}next.gif' alt='{$navStrings.next}' align='absmiddle' border='0' width='8' height='11'></a>&nbsp;
						{else}
							&nbsp;{$navStrings.next}&nbsp;<img src='{$imagePath}next_off.gif' alt='{$navStrings.next}' align='absmiddle' border='0' width='8' height='11'>
						{/if}
						{if $pageData.urls.endPage  && $pageData.offsets.total != $pageData.offsets.lastOffsetOnPage}
							<a href='{$pageData.urls.endPage}' {if $prerow}onclick="return sListView.save_checks({$pageData.offsets.end}, '{$moduleString}')"{/if} class='listViewPaginationLinkS1'>{$navStrings.end}&nbsp;<img src='{$imagePath}end.gif' alt='{$navStrings.end}' align='absmiddle' border='0' width='13' height='11'></a></td>
						{elseif !$pageData.offsets.totalCounted || $pageData.offsets.total == $pageData.offsets.lastOffsetOnPage}
							&nbsp;{$navStrings.end}&nbsp;<img src='{$imagePath}end_off.gif' alt='{$navStrings.end}' align='absmiddle' border='0' width='13' height='11'>
						{/if}
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
{if $prerow}
<a class='listViewCheckLink' href='javascript:sListView.check_all(document.MassUpdate, "mass[]", false);'>{$clearAll}</a>
{/if}
