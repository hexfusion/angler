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

// $Id: studiotabgroups.js,v 1.4 2006/08/22 22:14:21 awu Exp $
var subtabCount = [];
var subtabModules = [];
var tabLabelToValue = [];
StudioTabGroup = function(){
	this.lastEditTabGroupLabel = -1;
};


StudioTabGroup.prototype.editTabGroupLabel = function (id, done){
	if(!done){
		if(this.lastEditTabGroupLabel != -1)editTabGroupLabel(this.lastEditTabGroupLabel, true);
		document.getElementById('tabname_'+id).style.display = 'none';
		document.getElementById('tablabel_'+id).style.display = '';
		document.getElementById('tabother_'+id).style.display = 'none';
		document.getElementById('tablabel_'+id).focus();
		this.lastEditTabGroupLabel = id;
		YAHOO.util.DragDropMgr.lock();
	}else{
		this.lastEditTabGroupLabel = -1;
		document.getElementById('tabname_'+id).innerHTML =document.getElementById('tablabel_'+id).value; 
		document.getElementById('tabname_'+id).style.display = '';
		document.getElementById('tablabel_'+id).style.display = 'none';
		document.getElementById('tabother_'+id).style.display = '';
		YAHOO.util.DragDropMgr.unlock();
	}
}

 StudioTabGroup.prototype.generateForm = function(formname){
		  	var form = document.getElementById(formname);
		  	for(j = 0; j < slotCount; j++){
				var ul = document.getElementById('ul' + j);
				items = ul.getElementsByTagName('li');
				for(i = 0; i < items.length; i++) {
				
				if(typeof(subtabModules[items[i].id]) != 'undefined'){
				
					var input = document.createElement('input');
					input.type='text'
					input.name= j + '_'+ i;
					input.value = tabLabelToValue[subtabModules[items[i].id]];
					form.appendChild(input);
				}
				}
		  }
		  };

 StudioTabGroup.prototype.generateGroupForm = function(formname){
		  	var form = document.getElementById(formname);
		  	for(j = 0; j < slotCount; j++){
				var ul = document.getElementById('ul' + j);
				items = ul.getElementsByTagName('li');
				for(i = 0; i < items.length; i++) {
				if(typeof(subtabModules[items[i].id]) != 'undefined'){
					var input = document.createElement('input');
					input.type='hidden'
					input.name= 'group_'+ j + '[]';
					input.value = tabLabelToValue[subtabModules[items[i].id]];
					form.appendChild(input);
				}
				}
		  }
		  };

StudioTabGroup.prototype.deleteTabGroup = function(id){
		if(document.getElementById('delete_' + id).value == 0){
			document.getElementById('ul' + id).style.display = 'none';
			document.getElementById('tabname_'+id).style.textDecoration = 'line-through'
			document.getElementById('delete_' + id).value = 1;
		}else{
			document.getElementById('ul' + id).style.display = '';
			document.getElementById('tabname_'+id).style.textDecoration = 'none'
			document.getElementById('delete_' + id).value = 0;
		}
	}	


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
			        document.getElementById('slot' + rowCount + '_value').style.textDecoration = 'line-through';
			    }else{
			        field.value = '0';
			        document.getElementById('slot' + rowCount + '_value').style.textDecoration = 'none';
			    }
			    
			   
			}
var studiotabs = new StudioTabGroup();
