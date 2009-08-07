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

// $Id: upgradeWizard.js,v 1.9 2006/08/26 03:57:26 chris Exp $
var req;
var uw_check_msg = "";
//var uw_check_type = '';
var find_done = false;

function loadXMLDoc(url) {
	req = false;
    // branch for native XMLHttpRequest object
    if(window.XMLHttpRequest) {
    	try {
			req = new XMLHttpRequest();
        } catch(e) {
			req = false;
        }
    // branch for IE/Windows ActiveX version
    } else if(window.ActiveXObject) {
       	try {
        	req = new ActiveXObject("Msxml2.XMLHTTP");
      	} catch(e) {
        	try {
          		req = new ActiveXObject("Microsoft.XMLHTTP");
        	} catch(e) {
          		req = false;
        	}
		}
    }
    
	if(req) {
		req.onreadystatechange = processReqChange;
		req.open("GET", url, true);
		req.send("");
	}
}




///// preflight scripts
function preflightToggleAll(cb) {
	var checkAll = false;
	var form = document.getElementById('diffs');
	
	if(cb.checked == true) {
		checkAll = true;
	}
	
	for(i=0; i<form.elements.length; i++) {
		if(form.elements[i].type == 'checkbox') {
			form.elements[i].checked = checkAll;
		}
	}
	return;
}


function checkSqlStatus(done) {
	var schemaSelect = document.getElementById('select_schema_change');
	var hideShow = document.getElementById('show_sql_run');
	var hideShowCB = document.getElementById('sql_run');
	var nextButton = document.getElementById('next_button');
	var schemaMethod = document.getElementById('schema');
	document.getElementById('sql_run').checked = false;
	
	if(schemaSelect.options[schemaSelect.selectedIndex].value == 'manual' && done == false) {
		hideShow.style.display = 'block';
		nextButton.style.display = 'none';
		hideShowCB.disabled = false;
		schemaMethod.value = 'manual';
	} else {
		if(done == true) {
			hideShowCB.checked = true;
			hideShowCB.disabled = true;
		} else {
			hideShow.style.display = 'none';
		}
		nextButton.style.display = 'inline';
		schemaMethod.value = 'sugar';
	}
}


function toggleDisplay(targ) {
	target = document.getElementById("targ");
	if(target.style.display == 'none') {
		target.style.display = '';
	} else {
		target.style.display = 'none';
	}
}

function verifyMerge(cb) {
	if(cb.value == 'sugar') {
		var challenge = "{$mod_strings['LBL_UW_OVERWRITE_DESC']}";
		var answer = confirm(challenge);
		
		if(!answer) {
			cb.options[0].selected = true;
		}
	}
}
