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

// $Id: InboundEmail.js,v 1.2 2006/08/22 22:07:56 awu Exp $
Rot13 = {
    map: null,

    convert: function(a) {
        Rot13.init();

        var s = "";
        for (i=0; i < a.length; i++) {
            var b = a.charAt(i);
            s += ((b>='A' && b<='Z') || (b>='a' && b<='z') ? Rot13.map[b] : b);
        }
        return s;
    },

    init: function() {
        if (Rot13.map != null)
            return;
              
        var map = new Array();
        var s   = "abcdefghijklmnopqrstuvwxyz";

        for (i=0; i<s.length; i++)
            map[s.charAt(i)] = s.charAt((i+13)%26);
        for (i=0; i<s.length; i++)
            map[s.charAt(i).toUpperCase()] = s.charAt((i+13)%26).toUpperCase();

        Rot13.map = map;
    },

    write: function(a) {
        return Rot13.convert(a);
    }
}


function ie_test_open_popup(module_name, action, target, width, height, mail_server, protocol, port, login, password, mailbox, ssl)
{
	var words = new Array(login, password, mailbox);
	for(i=0; i<3; i++) {
		word = words[i];
		if(word.indexOf('&') > 0) {
			fragment1 = word.substr(0, word.indexOf('&'));
			fragment2 = word.substr(word.indexOf('&') + 1, word.length);
			
			newWord = fragment1 + '::amp::' + fragment2;
			words[i] = newWord;
			word = newWord; // setting it locally to pass on to next IF
			fragment1 = '';
			fragment2 = '';
		}
		if(word.indexOf('+') > 0) {
			fragment1 = word.substr(0, word.indexOf('+'));
			fragment2 = word.substr(word.indexOf('+') + 1, word.length);
			
			newWord = fragment1 + '::plus::' + fragment2;
			words[i] = newWord;
			word = newWord; // setting it locally to pass on to next IF
			fragment1 = '';
			fragment2 = '';
		}
		if(word.indexOf('%') > 0) {
			fragment1 = word.substr(0, word.indexOf('%'));
			fragment2 = word.substr(word.indexOf('%') + 1, word.length);
			
			newWord = fragment1 + '::percent::' + fragment2;
			words[i] = newWord;
			word = newWord; // setting it locally to pass on to next IF
			fragment1 = '';
			fragment2 = '';
		}
	}


	// lanch the popup
	URL = 'index.php?'
		+ 'module=' + module_name
		+ '&action=' + action
		+ '&target=' + target
		+ '&server_url=' + mail_server
		+ '&email_user=' + words[0]
		+ '&protocol=' + protocol
		+ '&port=' + port
		+ '&email_password=' + words[1]
		+ '&mailbox=' + words[2]
		+ '&ssl=' + ssl;
	windowName = 'popup_window';
	
	windowFeatures = 'width=' + width
		+ ',height=' + height
		+ ',resizable=1,scrollbars=1';

	win = window.open(URL, windowName, windowFeatures);

	if(window.focus)
	{
		// put the focus on the popup if the browser supports the focus() method
		win.focus();
	}

	return win;
}

function setPortDefault() {
	var prot	= document.getElementById('protocol');
	var ssl		= document.getElementById('ssl');
	var port	= document.getElementById('port');
	var stdPorts= new Array("110", "143", "993", "995");
	var stdBool	= new Boolean(false);
	
	if(port.value == '') {
		stdBool.value = true;
	} else {
		for(i=0; i<stdPorts.length; i++) {
			if(stdPorts[i] == port.value) {
				stdBool.value = true;
			}
		}
	}
	
	if(stdBool.value == true) {
		if(prot.value == 'imap' && ssl.checked == false) { // IMAP
			port.value = "143";
		} else if(prot.value == 'imap' && ssl.checked == true) { // IMAP-SSL
			port.value = '993';
		} else if(prot.value == 'pop3' && ssl.checked == false) { // POP3
			port.value = '110';
		} else if(prot.value == 'pop3' && ssl.checked == true) { // POP3-SSL
			port.value = '995';
		}
	}
}

function toggle_monitored_folder(field) {

	var field1=document.getElementById('protocol');
	var target=document.getElementById('pop3_warn');
	var mark_read = document.getElementById('mark_read');
	var mailbox = document.getElementById('mailbox');
	var inbox = document.getElementById('inbox');
	var label_inbox = document.getElementById('label_inbox');
	
	if (field1.value == 'imap') {
		target.style.display="none";
		mailbox.disabled=false;
        // This is not supported in IE
        try {
          mailbox.style.display = "block";
		  mailbox.type='text';
        } catch(e) {};
		inbox.style.display='';
		label_inbox.style.display='';
	}
	else {
		target.style.display="";
		mailbox.value = "INBOX";
        mailbox.disabled=false; // cannot disable, else the value is not passed
        // This is not supported in IE
        try {
          mailbox.style.display = "none";
		  mailbox.type='hidden';
        } catch(e) {};
       
		inbox.style.display = "";
		label_inbox.style.display = "none";
	}
}
