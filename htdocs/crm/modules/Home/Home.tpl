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

// $Id: Home.tpl,v 1.21 2006/08/23 00:13:44 awu Exp $

*}


<script type="text/javascript" src="include/javascript/overlibmws.js?s={$sugarVersion}&c={$jsCustomVersion}"></script>
<script type="text/javascript" src="include/javascript/overlibmws_iframe.js?s={$sugarVersion}&c={$jsCustomVersion}"></script>
<script type="text/javascript" src="include/javascript/dashlets.js?s={$sugarVersion}&c={$jsCustomVersion}"></script>
<script type="text/javascript" src="include/javascript/yui/container.js?s={$sugarVersion}&c={$jsCustomVersion}"></script>
<script type="text/javascript" src="include/javascript/yui/PanelEffect.js?s={$sugarVersion}&c={$jsCustomVersion}"></script>
<script type="text/javascript" src='include/ytree/TreeView/TreeView.js?s={$sugarVersion}&c={$jsCustomVersion}'></script>
<script type="text/javascript" src='include/ytree/TreeView/Node.js?s={$sugarVersion}&c={$jsCustomVersion}'></script>
<script type="text/javascript" src='include/ytree/TreeView/TextNode.js?s={$sugarVersion}&c={$jsCustomVersion}'></script>
<script type="text/javascript" src='include/ytree/TreeView/RootNode.js?s={$sugarVersion}&c={$jsCustomVersion}'></script>
<script type="text/javascript" src='include/ytree/treeutil.js?s={$sugarVersion}&c={$jsCustomVersion}'></script>
<script type="text/javascript" src='include/JSON.js?s={$sugarVersion}&c={$jsCustomVersion}'></script>
<script type='text/javascript' src='modules/Home/home.js?s={$sugarVersion}&c={$jsCustomVersion}'></script>
<link rel='stylesheet' href='include/ytree/TreeView/css/folders/tree.css?s={$sugarVersion}&c={$jsCustomVersion}'>
<link rel='stylesheet' href='include/javascript/yui/assets/container.css?s={$sugarVersion}&c={$jsCustomVersion}'>

<table cellspacing='5' cellpadding='0' border='0' valign='top' width='100%'>
	<tr><td align='left'>
	{if !$lock_homepage}
		<input type='button' class='button'' id='add_dashlets' onclick='return SUGAR.sugarHome.showDashletsTree();' value='{$lblAddDashlets}'>
	{/if}
	&nbsp;
	</td>
	<td align='right'>
		<a href='#' onclick="window.open('index.php?module=Administration&action=SupportPortal&view=documentation&version={$sugarVersion}&edition={$sugarFlavor}&lang={$currentLanguage}&help_module=Home&help_action=index&key={$serverUniqueKey}','helpwin','width=600,height=600,status=0,resizable=1,scrollbars=1,toolbar=0,location=1'); return false" class='utilsLink'>
			<img src='{$imagePath}help.gif' width='13' height='13' alt={$lblLnkHelp}' border='0' align='absmiddle'>
			{$lblLnkHelp}
		</a>
	</td>
	</tr>
	<tr>
		{counter assign=hiddenCounter start=0 print=false}
		{foreach from=$columns key=colNum item=data}
		<td valign='top' width={$data.width}>
			<ul class='noBullet' id='col{$colNum}'>
				<li id='hidden{$hiddenCounter}b' style='height: 5px' class='noBullet'>&nbsp;&nbsp;&nbsp;</li>
		        {foreach from=$data.dashlets key=id item=dashlet}		
				<li class='noBullet' id='dashlet_{$id}'>
					<div id='dashlet_entire_{$id}'>
						{$dashlet.script}
						{$dashlet.display}
					</div>
				</li>
				{/foreach}
				<li id='hidden{$hiddenCounter}' style='height: 5px' class='noBullet'>&nbsp;&nbsp;&nbsp;</li>
			</ul>
		</td>
		{counter}
		{/foreach}
	</tr>
</table>
{if !$lock_homepage}
{literal}
<script type="text/javascript">
SUGAR.sugarHome.maxCount = 	{/literal}{$maxCount}{literal};
SUGAR.sugarHome.init = function () {
	homepage_dd = new Array();
	j = 0;
	{/literal}
	dashletIds = {$dashletIds};
	{literal}
	for(i in dashletIds) {
		homepage_dd[j] = new ygDDList('dashlet_' + dashletIds[i]);
		homepage_dd[j].setHandleElId('dashlet_header_' + dashletIds[i]);
		homepage_dd[j].onMouseDown = SUGAR.sugarHome.onDrag;  
		homepage_dd[j].afterEndDrag = SUGAR.sugarHome.onDrop;
		j++;
	}
	for(var wp = 0; wp <= {/literal}{$hiddenCounter}{literal}; wp++) {
	    homepage_dd[j++] = new ygDDListBoundary('hidden' + wp);
	}

	YAHOO.util.DDM.mode = 1;
}

YAHOO.util.Event.addListener(window, 'load', SUGAR.sugarHome.init);  

</script>
{/literal}
{/if}
