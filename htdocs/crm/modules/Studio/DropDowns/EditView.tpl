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

// $Id: EditView.tpl,v 1.11 2006/08/23 00:15:55 awu Exp $

*}


{literal}
<br>	
<style type='text/css'>
.slot {
	border-width:1px;border-color:#999999;border-style:solid;padding:0px 1px 0px 1px;margin:2px;cursor:move;

}

.slotB {
	border-width:0;cursor:move;

}
</style>
{/literal}
{$buttons}
<table class='tabForm'>
<tr><td>
<span class='error'>{$error}</span>
<table >
<tr><td colspan='2'>
{if empty($newDropDown)}
<form method='post' action='index.php' name='dropdownsform'>
<input type='hidden' name='action' value='wizard'>
<input type='hidden' name='wizard' value='EditDropDownWizard'>
<input type='hidden' name='option' value='EditDropdown'>
<input type='hidden' name='module' value='Studio'>
{html_options name='dropdown_module' options=$dropdown_modules selected=$dropdown_module onchange="document.dropdownsform.submit();"}
{html_options name='dropdown_name' output=$dropdowns values=$dropdowns selected=$dropdown_name onchange="document.dropdownsform.submit();"}
{html_options name='dropdown_lang' options=$dropdown_languages selected=$dropdown_lang onchange="document.dropdownsform.submit();"}
</form>
{/if}
</td></tr>
</table>
</td></tr><tr><td>
{if !empty($dropdown) || !empty($newDropDown)}
<form method='post' action='index.php' name='editdropdown'>
<input type='hidden' name='action' value='wizard'>
<input type='hidden' name='wizard' value='EditDropDownWizard'>
<input type='hidden' name='option' value='SaveDropDown'>
<input type='hidden' name='module' value='Studio'>
<input type='hidden' name='dropdown_module' value='{$dropdown_module}'>
<input type='hidden' name='dropdown_lang' value='{$dropdown_lang}'>
{if empty($newDropDown)}
<input type='hidden' name='dropdown_name' value='{$dropdown_name}'>
{else}
<table><tr><td>
Dropdown Name:
</td><td><input type='text' name='dropdown_name' value='{$dropdown_name}'>
</td></tr><tr><td>
Dropdown Language:</td><td>{html_options name='dropdown_lang' options=$dropdown_languages select=$dropdown_lang}
</td></tr></table>
{/if}
<table name='tabDropdown' id='tabDropdown'>
<tr><td>{$MOD.LBL_DD_DATABASEVALUE}<hr></td><td>{$MOD.LBL_DD_DISPALYVALUE}<hr></td></tr>
{counter start=0 name="rowCounter" print=false assign="rowCounter"}
{foreach from=$dropdown item="value" key="key"}

<tr><td>
<span id='slot{$rowCounter}' class='slot' style='cursor: move'>
    <span id='slot{$rowCounter}_key'>{$key}</span>
</span>
</td><td>
<span id='slot{$rowCounter}b' >
    <span onclick='deleteDropDownValue({$rowCounter}, document.getElementById("delete_{$rowCounter}"), true);'>
        {$deleteImage}
    </span>
    <span onclick='prepChangeDropDownValue({$rowCounter}, document.getElementById("slot{$rowCounter}_value"));'>{$editImage}</span>
        &nbsp;
    <span id ='slot{$rowCounter}_value' onclick='prepChangeDropDownValue({$rowCounter}, this);'>{$value.lang}</span>
    <span id='slot{$rowCounter}_textspan' style='display:none'>
        <input id='slot{$rowCounter}_text' value='' type='text'  onchange='setDropDownValue({$rowCounter}, this.value, true)' >
        {$value.user_lang}
    </span>
     <input name='slot_{$rowCounter}' id='slot_{$rowCounter}' value='{$rowCounter}' type = 'hidden'> 
    <input name='value_{$rowCounter}' id='value_{$rowCounter}' value='{$value.lang}' type = 'hidden'>
    <input type='hidden' name='key_{$rowCounter}' id='key_{$rowCounter}' value='{$key|default:"BLANK"}'>
    <input type='hidden' id='delete_{$rowCounter}' name='delete_{$rowCounter}' value='0'>
 </span>

    </td></tr>
{counter name="rowCounter"}
{/foreach}
<tr><td><input type='text' name='addKey' id='addKey' value=''></td><td><input type='text' name='addValue' id='addValue' value=''><input type='button' onclick='addDropDown();' value='+' class='button'></td>

</table>

{literal}
<script type="text/javascript" src="modules/Studio/JSTransaction.js" ></script>
			<script>
			var jstransaction = new JSTransaction();
			</script>
<script src = "include/javascript/yui/dragdrop.js" ></script>
				 	
			<script type="text/javascript" src="modules/Studio/studiodd.js" ></script>	
			<script type="text/javascript" src="modules/Studio/studio.js" ></script>	
			<script>
			var lastField = '';
			var lastRowCount = -1;
			var undoDeleteDropDown = function(transaction){
			    deleteDropDownValue(transaction['row'], document.getElementById(transaction['id']), false);
			}
			jstransaction.register('deleteDropDown', undoDeleteDropDown, undoDeleteDropDown);
			function deleteDropDownValue(rowCount, field, record){
			    if(record){
			        jstransaction.record('deleteDropDown',{'row':rowCount, 'id': field.id });
			    }
			    //We are deleting if the value is 0
			    if(field.value == '0'){
			        field.value = '1';
			        document.getElementById('slot' + rowCount + '_key').style.textDecoration = 'line-through';
			        document.getElementById('slot' + rowCount + '_value').style.textDecoration = 'line-through';
			    }else{
			        field.value = '0';
			        document.getElementById('slot' + rowCount + '_key').style.textDecoration = 'none';
			        document.getElementById('slot' + rowCount + '_value').style.textDecoration = 'none';
			    }
			    
			   
			}
			function prepChangeDropDownValue(rowCount, field){
			  
			    var tempLastField = lastField;
			     if(lastRowCount != -1){
			        setDropDownValue(lastRowCount, lastField.innerHTML, true);
			    }
			     if(tempLastField == field)return;
			    lastField = field;
			    lastRowCount = rowCount;
			    
			    field.style.display="none";
			    
			    var textspan =  document.getElementById('slot' + rowCount + '_textspan');
			    var text = document.getElementById("slot" + rowCount + "_text");
			    text.value=field.innerHTML;
			    textspan.style.display='inline'
			    text.focus();
			}
			var undoDropDownChange = function(transaction){
			    setDropDownValue(transaction['row'], transaction['old'], false);
			}
			var redoDropDownChange = function(transaction){
			    setDropDownValue(transaction['row'], transaction['new'], false);
			}
			jstransaction.register('changeDropDownValue', undoDropDownChange, redoDropDownChange);
			function setDropDownValue(rowCount, val, record){
			  
			    var key = document.getElementById('slot' + rowCount + '_key').innerHTML;
			    if(key == ''){
			        key = 'BLANK';
			    }
			    if(record){
			        jstransaction.record('changeDropDownValue', {'row':rowCount, 'new':val, 'old':document.getElementById('value_'+ rowCount).value});
			    }
			    document.getElementById('value_' + rowCount).value = val;
			    var text =  document.getElementById('slot' + rowCount + '_text');
			    var textspan =  document.getElementById('slot' + rowCount + '_textspan');
			    var span = document.getElementById('slot' + rowCount + '_value');
			    span.innerHTML  = val;
			    textspan.style.display = 'none';
			    text.value = '';
			    span.style.display = 'inline';
			    lastField = '';
			    lastRowCount = -1;
			    
			}

		  function addDropDown(){
		      var addKey =  document.getElementById('addKey');
		      var keyValue = addKey.value;
		      if(trim(keyValue) == ''){
		          keyValue = 'BLANK';
		      }
		      var addValue =  document.getElementById('addValue')
		      for(var i = 0; i < slotCount ; i++){
		          if(typeof(document.getElementById('key_' + i)) != 'undefined'){
		              if(document.getElementById('key_' + i).value == keyValue){
		                  alert('key already exists');
		                  return;
		              }
		          }
		      }
		      var table = document.getElementById('tabDropdown');
		      var row = table.insertRow(table.rows.length - 1);
			  var cell = row.insertCell(0);
			  var cell2 = row.insertCell(1);
			  
			 
			 
			  var span1 = document.createElement('span');
			  span1.id = 'slot' + slotCount;
			  span1.className = 'slot';
			  var keyspan = document.createElement('span');
			  keyspan.id = 'slot' + slotCount + '_key'
			  keyspan.innerHTML  = addKey.value;
			  span1.appendChild(keyspan);
			  var span2 = document.createElement('span');
			  span2.id = 'slot' + slotCount + 'b';
			  var delimage = document.createElement('span');
			  delimage.innerHTML = "{/literal}{$deleteImage}{literal}&nbsp;";
			  delimage.slotCount = slotCount
			  delimage.recordKey = keyValue;
			  delimage.onclick = function(){
			      deleteDropDownValue(this.slotCount, document.getElementById( 'delete_' + this.recordKey), true);
			  };
			  var span2image = document.createElement('span');
			  span2image.innerHTML = "{/literal}{$editImage}{literal}&nbsp;";
			  span2image.slotCount = slotCount
			  span2image.onclick = function(){
			      prepChangeDropDownValue(this.slotCount, document.getElementById('slot' + this.slotCount + '_value'));
			  };
			  var span2inner = document.createElement('span');
			  span2inner.innerHTML = addValue.value;
			  span2inner.id = 'slot' + slotCount + '_value';
			  span2inner.slotCount = slotCount
			  span2inner.onclick = function(){
			      prepChangeDropDownValue(this.slotCount, this);
			  };
			  var text2span = document.createElement('span');
			  text2span.id = 'slot' + slotCount + '_textspan'
			  text2span.style.display = 'none';
			  
			  
			  var text2 = document.createElement('input');
			  text2.type = 'text';
			  text2.id = 'slot' + slotCount + '_text'
			  
			  text2.slotCount = slotCount;
			  text2.onchange = function(){
			      setDropDownValue(this.slotCount, this.value, true);
			  }
			   
			  
			  var text3 = document.createElement('input');
			  text3.type = 'hidden';
			  text3.id = 'value_' + slotCount;
			  text3.name = 'value_' + slotCount;
			  text3.value = addValue.value;
			  
			  var text4 = document.createElement('input');
			  text4.type = 'hidden';
			  text4.id = 'key_' + slotCount;
			  text4.name = 'key_' + slotCount;
			  text4.value = keyValue;
               var text5 = document.createElement('input');
			  text5.type = 'hidden';
			  text5.id = 'delete_' + slotCount  ;
			  text5.name = 'delete_' + slotCount  ;
			  text5.value = '0';
			  var text6 = document.createElement('input');
			  text6.type = 'hidden';
			  text6.id = 'slot_' + slotCount;
			  text6.name = 'slot_' + slotCount;
			  text6.value = slotCount;
			  cell.appendChild(span1);
			  span2.appendChild(delimage);
			  span2.appendChild(span2image);
			  span2.appendChild(span2inner);
			  cell2.appendChild(span2);
			  text2span.appendChild(text2);
			  cell2.appendChild(text2span);
			  cell2.appendChild(text3);
			  cell2.appendChild(text4);
			  cell2.appendChild(text5);
			  cell2.appendChild(text6);
			  addKey.value = '';
			  addValue.value = '';
			  yahooSlots["slot" + slotCount] = new ygDDSlot("slot" + slotCount, "studio");
			   slotCount++;
		  }
			var gLogger = new ygLogger("Studio");
		  var slotCount = {/literal}{$rowCounter}{literal};

			 var yahooSlots = [];
			function dragDropInit(){
					if (typeof(ygLogger) != "undefined") {
				ygLogger.init(document.getElementById("logDiv"));
			}
				YAHOO.util.DDM.mode = YAHOO.util.DDM.POINT;
				
				 gLogger.debug("point mode");
				for(mj = 0; mj <= slotCount; mj++){
					yahooSlots["slot" + mj] = new ygDDSlot("slot" + mj, "studio");
				}
				  // initPointMode();
			}
			YAHOO.util.Event.addListener(window, "load", dragDropInit);
			
			
</script>	
{/literal}


<div id='logDiv' style='display:none'> 
</div>

{/if}
</form>
</td></tr>
</table>
