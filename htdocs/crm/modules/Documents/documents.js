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

// $Id: documents.js,v 1.3 2006/08/22 22:07:24 awu Exp $

var rhandle=new RevisionListHandler();
var from_popup_return  = false;
function document_set_return(popup_reply_data)
{
	from_popup_return = true;
	var form_name = popup_reply_data.form_name;
	var name_to_value_array = popup_reply_data.name_to_value_array;
	related_doc_id='EMPTY';
	for (var the_key in name_to_value_array)
	{
		if(the_key == 'toJSON')
		{
			/* just ignore */
		}
		else
		{
			var displayValue=name_to_value_array[the_key];
			displayValue=displayValue.replace('&#039;',"'");  //restore escaped single quote.
			displayValue=displayValue.replace( '&amp;',"&");  //restore escaped &.
			displayValue=displayValue.replace( '&gt;',">");  //restore escaped >.
			displayValue=displayValue.replace( '&lt;',"<");  //restore escaped <.
			displayValue=displayValue.replace( '&quot; ',"\"");  //restore escaped ".
			if (the_key == 'related_doc_id') {
				related_doc_id =displayValue;
			}
			window.document.forms[form_name].elements[the_key].value = displayValue;
		}
	}
	related_doc_id=JSON.stringify(related_doc_id);
	//make request for document revisions data.
	var conditions  = new Array();
    conditions[conditions.length] = {"name":"document_id","op":"starts_with","value":related_doc_id};
 	var query = new Array();
 	var query = {"module":"DocumentRevisions","field_list":['id','revision','date_entered'],"conditions":conditions,"order":'date_entered desc'};
    
    //make the call call synchronous for now...
    //todo: convert to async, test on mozilla..
    result = global_rpcClient.call_method('query',query,true);
    rhandle.display(result);
    
	//req_id = global_rpcClient.call_method('query',query);
    //register the callback mathod.
	//global_request_registry[req_id] = [rhandle, 'display'];
}


function RevisionListHandler() { }

RevisionListHandler.prototype.display = function(result) {
 	var names = result['list'];
 	var rev_tag=document.getElementById('related_doc_rev_id');
 	rev_tag.options.length=0;
	
	for(i=0; i < names.length; i++) { 
		rev_tag.options[i] = new Option(names[i].fields['revision'],names[i].fields['id'],false,false);
	}
 	rev_tag.disabled=false;
}
