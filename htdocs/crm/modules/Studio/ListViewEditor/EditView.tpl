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

// $Id: EditView.tpl,v 1.4 2006/08/23 00:15:55 awu Exp $

*}


{literal}
<br>


<script type="text/javascript" src="modules/Studio/JSTransaction.js" ></script>
<script>
	var jstransaction = new JSTransaction();
</script>
<script src = "include/javascript/yui/dragdrop.js" ></script>
<script src='modules/Studio/studiotabgroups.js'></script>
<script src = "modules/Studio/ygDDListStudio.js" ></script>				 	
<script type="text/javascript" src="modules/Studio/studiodd.js" ></script>	
<script type="text/javascript" src="modules/Studio/studio.js" ></script>	
<style type='text/css'>
.slot {
	border-width:1px;border-color:#999999;border-style:solid;padding:0px 1px 0px 1px;margin:2px;cursor:move;

}


.slotSub {
	border-width:1px;border-color:#006600;border-style:solid;padding:0px 1px 0px 1px;margin:2px;cursor:move;

}
.slotB {
	border-width:0;cursor:move;

}
.listContainer
{
	margin-left: 4;
	padding-left: 4;
	margin-right: 4;
	padding-right: 4;
	list-style-type: none;
}

.tableContainer
{
	
}
.tdContainer{
	border: thin solid gray;
	padding: 10;
}
.fieldValue{
	color: #999;
	font-size: 75%;
	cursor:move;
}


	
}

</style>
{/literal}




<table>
<tr><td colspan='100'><h2>{$title}</h2></td></tr>
<tr><td colspan='100'>
{$description}
</td></tr><tr><td><br></td></tr><tr><td colspan='100'>{$buttons}</td></tr><tr>
{counter start=0 name="slotCounter" print=false assign="slotCounter"}
{counter start=0 name="modCounter" print=false assign="modCounter"}
{foreach from=$groups key='label' item='list'}
<td valign='top' class='tabForm' nowrap>
<h3>{$label}</h3>
<ul class='listContainer' id='ul{$slotCounter}'>

{foreach from=$list key='key' item='value'}


<li  id='subslot{$modCounter}'><span class='slotB'>{if !empty($translate)}{sugar_translate label=$value.label module=$module}{else}{$value.label}{/if}</span>{if empty($hideKeys)} <br><span class='fieldValue'>[{$key}]{/if}</span>

</li>
<script>
tabLabelToValue['{$value.label}|{$key}'] = '{$key}';
if(typeof(subtabModules['subslot{$modCounter}']) == 'undefined')subtabModules['subslot{$modCounter}'] = '{$value.label}|{$key}';
</script>
{counter name="modCounter"}
{/foreach}
<li  id='topslot{$slotCounter}' class='noBullet'>&nbsp;</span>
</ul>
</td>
{counter name="slotCounter"}
{/foreach}
<td width='100%'>&nbsp;</td>
</tr></table>


<span class='error'>{$error}</span>



{literal}


			<script>
		 
			var gLogger = new ygLogger("Studio");
		  var slotCount = {/literal}{$slotCounter}{literal};
		  var modCount = {/literal}{$modCounter}{literal};
			var subSlots = [];
			 var yahooSlots = [];
			function dragDropInit(){
					if (typeof(ygLogger) != "undefined") {
				ygLogger.init(document.getElementById("logDiv"));
			}
				YAHOO.util.DDM.mode = YAHOO.util.DDM.POINT;
				gLogger.loggerEnabled = false;
				gLogger.debug("point mode");
				for(msi = 0; msi <= slotCount ; msi++){
					yahooSlots["topslot"+ msi] = new ygDDListStudio("topslot" + msi, "subTabs", true);
						
				
				}
				for(msi = 0; msi <= modCount ; msi++){
						yahooSlots["subslot"+ msi] = new ygDDListStudio("subslot" + msi, "subTabs", false);
						
				}
				
				yahooSlots["subslot"+ (msi - 1) ].updateTabs();
				  // initPointMode();
			}
			YAHOO.util.DDM.mode = YAHOO.util.DDM.INTERSECT; 
			YAHOO.util.Event.addListener(window, "load", dragDropInit);
			
			
</script>	
{/literal}


<div id='logDiv' style='display:none'> 
</div>

{$additionalFormData}
	
</form>


