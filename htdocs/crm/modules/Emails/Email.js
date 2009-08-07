/**
 * EditView javascript for Email
 *
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

// $Id: Email.js,v 1.12 2006/09/01 01:12:41 eddy Exp $

var uploads_arr=new Array();
var uploads_count_map=new Object();
var uploads_count = -1;
var current_uploads_id = -1;
var append = false; // FCKEd has method InsertHTML which inserts at cursor point - plain does not
//following varaibles store references to input fields grouped with the clicked email selection button (select).
var current_contact = '';
var current_contact_id = '';
var current_contact_email = '';
var current_contact_name = '';

function toggleRawEmail() {
	var raw = document.getElementById('rawEmail');
	var button = document.getElementById('rawButton');
	
	if(raw.style.display == '') {
		raw.style.display = 'none';
		button.value = showRaw;
	} else {
		raw.style.display = '';
		button.value = hideRaw;
	}
}

///////////////////////////////////////////////////////////////////////////////
////	DOCUMENT HANDLING HELPERS
function setDocument(target, documentId, documentName) {
	var docId = eval("window.opener.document.EditView.documentId" + target);
	var docName = eval("window.opener.document.EditView.documentName" + target);
	
	docId.value = documentId;
	docName.value = documentName;
	
	window.close();
}

function selectDocument(target) {
	URL="index.php?module=Emails&action=PopupDocuments&target=" + target;
	windowName = 'selectDocument';
	windowFeatures = 'width=800' + ',height=600' + ',resizable=1,scrollbars=1';

	win = window.open(URL, windowName, windowFeatures);
	if(window.focus) {
		// put the focus on the popup if the browser supports the focus() method
		win.focus();
	}
}

function addDocument() {
	for(var i=0;i<10;i++) {
		var elem = document.getElementById('document'+i);
		if(elem.style.display == 'none') {
		  	elem.style.display='block';
			break;
		}
	}
}

function deleteDocument(index) {
	var elem = document.getElementById('document'+index);
	document.getElementById('documentId'+index).value = "";
	document.getElementById('documentName'+index).value = "";
	elem.style.display='none';
}

// attachment functions below
function deleteFile(index) {
	//get div element
	var elem = document.getElementById('file'+index);
	//get upload widget
	var ea_input = document.getElementById('email_attachment'+index);

	//get the parent node
	var Parent = ea_input.parentNode;

	//create replacement node
	var ea = document.createElement('input');
    ea.setAttribute('id', 'email_attachment' + index);
    ea.setAttribute('name', 'email_attachment' + index);
    ea.setAttribute('tabindex', '120');
    ea.setAttribute('size', '40');    
    ea.setAttribute('type', 'file');

	//replace the old node with the new one
    Parent.replaceChild(ea, ea_input);

	//hide the div element
	elem.style.display='none';

}

function addFile() {
	for(var i=0;i<10;i++) {
		var elem = document.getElementById('file'+i);
		if(elem.style.display == 'none') {
		  	elem.style.display='block';
			break;
		}
	}
}
////	END DOCUMENT HANDLING HELPERS
///////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////
////	HTML/PLAIN EDITOR FUNCTIONS
function setEditor() {
	if(document.getElementById('setEditor').checked == false) {
		toggle_textonly();
	}
}

function toggle_textonly() {
	var altText = document.getElementById('alt_text_div');
	var plain = document.getElementById('text_div');
	var html = document.getElementById('html_div');
	
	if(html.style.display == 'none') {
		html.style.display = 'block';
		if(document.getElementById('toggle_textarea_elem').checked == false) {
			plain.style.display = 'none';
		}
		altText.style.display = 'block';
	} else {
		html.style.display = 'none';
		plain.style.display = 'block';
		altText.style.display = 'none';
	}
}

function toggle_textarea() {
	var checkbox = document.getElementById('toggle_textarea_elem');
	var plain = document.getElementById('text_div');

	if (checkbox.checked == false) {
		plain.style.display = 'none';
	} else {
		plain.style.display = 'block';
	}
}
////	END HTML/PLAIN EDITOR FUNCTIONS
///////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////
////	EMAIL TEMPLATE CODE
function fill_email(id) {
	var where = "parent_id='" + id + "'";
	var order = '';
	
	if(id == '') {
		// query based on template, contact_id0,related_to
		if(! append) {
			document.EditView.name.value  = '';
			document.EditView.description.value = '';
			document.EditView.description_html.value = '';
		}
		return;
	}
	call_json_method('EmailTemplates','retrieve','record='+id,'email_template_object', appendEmailTemplateJSON);
	args = {"module":"Notes","where":where, "order":order};
	req_id = global_rpcClient.call_method('get_full_list', args);
	global_request_registry[req_id] = [ejo, 'display'];
}

function appendEmailTemplateJSON() {
//	if(typeof document.EditView.description_html != 'undefined') {
//		var html_textarea = document.EditView.description_html;
//	} else {
//		var html_textarea = document.EditView.body_html
//	}

	// query based on template, contact_id0,related_to
	if(document.EditView.name.value == '') { // cn: bug 7743, don't stomp populated Subject Line
		document.EditView.name.value = decodeURI(encodeURI(json_objects['email_template_object']['fields']['subject']));
	}

	document.EditView.description.value += decodeURI(encodeURI(json_objects['email_template_object']['fields']['body'])).replace(/<BR>/ig, '\n');
	var oEditor = FCKeditorAPI.GetInstance('description_html');
	oEditor.InsertHtml(decodeURI(encodeURI(json_objects['email_template_object']['fields']['body_html'])).replace(/<BR>/ig, '\n'));

	var htmlDiv = document.getElementById('html_div');
	// hide the HTML editor if this is Plain-text only
	if(oEditor.EditorDocument.body.textContent == '' && htmlDiv.style.display == '') {
		// cn: bug 6212
		// if the template is plain-text, then uncheck "Send HTML Email"
		document.getElementById('setEditor').checked = false;
		setEditor();
	}
}

if(typeof SugarClass == "object") {
	SugarClass.inherit("EmailJsonObj","SugarClass");
}
function EmailJsonObj() {
}
EmailJsonObj.prototype.display = function(result) {
	var bean;
	var block = document.getElementById('template_attachments');
	var target = block.innerHTML;
	var full_file_path;

	for(i in result) {
		if(typeof result[i] == 'object') {
			bean = result[i];
			full_file_path = file_path + bean['id']+bean['filename'];
			target += '\n<input type="hidden" name="template_attachment[]" value="' + bean['id'] + '">';
			target += '\n<input type="checkbox" name="temp_remove_attachment[]" value="' + bean['id'] + '"> '+ lnk_remove + '&nbsp;&nbsp;';
			target += '<a href="' + full_file_path + '"target="_blank">' + bean['filename'] + '</a><br>';
		}
	}
	block.innerHTML = target;
}

ejo = new EmailJsonObj();
////	END EMAIL TEMPLATE CODE
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	SIGNATURE CODE
function insertSignature(text) {
	if(typeof FCKeditor == undefined) {
	 	var oEditor = FCKeditorAPI.GetInstance('body_html') ;
	  	oEditor.InsertHtml(text);
	} else if (typeof html_editor != undefined) {
   		html_editor.focusEditor();
    
    	if(HTMLArea.is_ie) {
	        html_editor.insertHTML(text + " " );
	    } else {
    	    html_editor.insertNodeAtSelection(document.createTextNode(text + " "));
    	}
	}    
}
////	END SIGNATURE CODE
///////////////////////////////////////////////////////////////////////////////



function fill_form(type, error_text) {
	if(document.getElementById('subjectfield').value == '') {
		var sendAnyways = confirm(lbl_send_anyways);
		if(sendAnyways == false) { return false; }
	}

	if(type == 'out' && document.EditView.to_addrs.value  == '' &&
		document.EditView.cc_addrs.value  == '' &&
		document.EditView.bcc_addrs.value  == '') {
		
		alert(error_text);
		return false;
	}

	var the_form = document.EditView;
	var inputs = the_form.getElementsByTagName('input');
	
	//  this detects if browser needs the following hack or not..
	if(inputs.length > 0) {
		// no need to appendChild to EditView to get file uploads to work
		return check_form('EditView');
	}
	
	if(! check_form('EditView')) {
		return false;
	}
	return true;
}

function setLabels(uploads_arr) {
}



//this function appends the selected email address to the aggregated email address fields.
function set_current_parent(id,email,name,value) {
	current_contact_id.value += id+";";
	current_contact_email.value += email+";";
	current_contact_name.value += name+";";

	if(current_contact.value != '') {
		current_contact.value += "; ";
	}
	
	current_contact.value += name + " <" + email + ">";
//	current_contact.value += value;
}

function set_email_return(popup_reply_data) {
	var form_name = popup_reply_data.form_name;
	var name_to_value_array = popup_reply_data.name_to_value_array;
	for(var i in name_to_value_array) {
		for(var the_key in name_to_value_array[i]) {
			if(the_key == 'toJSON') {
				/* just ignore */
			} else {
				var displayValue = name_to_value_array[i][the_key];
				displayValue=displayValue.replace('&#039;',"'");  //restore escaped single quote.
				displayValue=displayValue.replace('&amp;',"&");  //restore escaped &.
				displayValue=displayValue.replace('&gt;',">");  //restore escaped >.
				displayValue=displayValue.replace('&lt;',"<");  //restore escaped <.
				displayValue=displayValue.replace('&quot; ',"\"");  //restore escaped ".
				
				window.document.forms[form_name].elements[the_key].value += displayValue + '; ';
			}
		}
	}
}

//create references to input fields associated with the select email address button. 
//Clicked button is passed as the parameter to the function. 
function button_change_onclick(obj) {
	var prefix = 'to_';
	if(obj.name.match(/^cc_/i)) {
	    prefix = 'cc_';
	} else if(obj.name.match(/^bcc_/i)) {
		prefix = 'bcc_';
	}
	
	for(var i = 0; i < obj.form.length;i++) {
		child = obj.form[i];
		if(child.name.indexOf(prefix) != 0) {
			continue;
		}

		if(child.name.match(/addrs_emails$/i)) {
			current_contact_email = child;
		} else if(child.name.match(/addrs_ids$/i)) {
			current_contact_id = child;
		} else if(child.name.match(/addrs_names$/i)) {
			current_contact_name = child;
		} else if(child.name.match(/addrs$/i)) {
			current_contact = child;
		}
	}

	var filter = '';
//	if(document.EditView.parent_type.value  == 'Accounts' &&
//		typeof(document.EditView.parent_name.value) != 'undefined' &&
//		document.EditView.parent_name.value != '') {
//
//		filter = "&form_submit=false&query=true&account_name=" + escape(document.EditView.parent_name.value);
//	}

	var popup_request_data =
	{
		"call_back_function" : "set_email_return",
		"form_name" : "EditView",
		"field_to_name_array" :
		{
			"id" : prefix + "addrs_ids",
			"email1" : prefix + "addrs_emails",
			"name" : prefix + "addrs_names",
			"email_and_name1" : prefix + "addrs_field"
		}
	};

	return open_popup("Contacts",600,400,filter,true,false,popup_request_data,'MultiSelect',false,'popupdefsEmail');
}

//this function clear the value stored in the aggregated email address fields(nodes). 
//it relies on the references set by the button_change_onclick method
function clear_email_addresses() {
	
	if(current_contact != '') {
		current_contact.value = '';
	}
	if(current_contact_id != '') {
		current_contact_id.value = '';
	}
	if(current_contact_email != '') {
		current_contact_email.value = '';
	}
	if(current_contact_name != '') {
		current_contact_name.value = '';
	}
}

function quick_create_overlib(id, theme) {
    return overlib('<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,"yes");\' onmouseout=\'unhiliteItem(this);\' href=\'index.php?module=Cases&action=EditView&inbound_email_id=' + id + '\'>' +
            "<img border='0' src='themes/" + theme + "/images/Cases.gif' style='margin-right:5px'>" + SUGAR.language.get('Emails', 'LBL_LIST_CASE') + '</a>' +
            "<a style='width: 150px' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' href='index.php?module=Leads&action=EditView&inbound_email_id=" + id + "'>" +
                    "<img border='0' src='themes/" + theme + "/images/Leads.gif' style='margin-right:5px'>"
                    + SUGAR.language.get('Emails', 'LBL_LIST_LEAD') + "</a>" +
             "<a style='width: 150px' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' href='index.php?module=Contacts&action=EditView&inbound_email_id=" + id + "'>" +
                    "<img border='0' src='themes/" + theme + "/images/Contacts.gif' style='margin-right:5px'>"
                    + SUGAR.language.get('Emails', 'LBL_LIST_CONTACT') + "</a>" +
             "<a style='width: 150px' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' href='index.php?module=Bugs&action=EditView&inbound_email_id=" + id + "'>"+
                    "<img border='0' src='themes/" + theme + "/images/Bugs.gif' style='margin-right:5px'>"            
                    + SUGAR.language.get('Emails', 'LBL_LIST_BUG') + "</a>" +
             "<a style='width: 150px' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' href='index.php?module=Tasks&action=EditView&inbound_email_id=" + id + "'>" +
                    "<img border='0' src='themes/" + theme + "/images/Tasks.gif' style='margin-right:5px'>"
                   + SUGAR.language.get('Emails', 'LBL_LIST_TASK') + "</a>"
            , CAPTION, SUGAR.language.get('Emails', 'LBL_QUICK_CREATE')
            , STICKY, MOUSEOFF, 3000, CLOSETEXT, '<img border=0 src="themes/' + theme + '/images/close_inline.gif">', WIDTH, 150, CLOSETITLE, SUGAR.language.get('app_strings', 'LBL_ADDITIONAL_DETAILS_CLOSE_TITLE'), CLOSECLICK, FGCLASS, 'olOptionsFgClass', 
            CGCLASS, 'olOptionsCgClass', BGCLASS, 'olBgClass', TEXTFONTCLASS, 'olFontClass', CAPTIONFONTCLASS, 'olOptionsCapFontClass', CLOSEFONTCLASS, 'olOptionsCloseFontClass');
}
