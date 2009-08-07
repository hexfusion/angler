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

// $Id: JotPadDashletScript.tpl,v 1.6 2006/08/23 00:13:44 awu Exp $

*}


{literal}<script>
if(typeof JotPad == 'undefined') { // since the dashlet can be included multiple times a page, don't redefine these functions
	JotPad = function() {
	    return {
	    	/**
	    	 * Called when the textarea is blurred
	    	 */
	        blur: function(ta, id) {
	        	ajaxStatus.showStatus('{/literal}{$saving}{literal}'); // show that AJAX call is happening
	        	// what data to post to the dashlet
    	    	postData = 'to_pdf=1&module=Home&action=CallMethodDashlet&method=saveText&id=' + id + '&savedText=' + ta.value;
				var cObj = YAHOO.util.Connect.asyncRequest('POST','index.php', 
								  {success: JotPad.saved, failure: JotPad.saved}, postData);
	        },
		    /**
	    	 * Called when the textarea is double clicked on
	    	 */
			edit: function(divObj, id) {
				ta = document.getElementById('jotpad_textarea_' + id);
				if(isIE) ta.value = divObj.innerHTML.replace(/<br>/gi, "\n");
				else ta.value = divObj.innerHTML.replace(/<br>/gi, '');
				
				divObj.style.display = 'none';
				ta.style.display = '';
				ta.focus();
			},
		    /**
	    	 * handle the response of the saveText method
	    	 */
	        saved: function(data) {
	        	eval(data.responseText);
	           	ajaxStatus.showStatus('{/literal}{$saved}{literal}');
	           	if(typeof result != 'undefined') {
					ta = document.getElementById('jotpad_textarea_' + result['id']);
					theDiv = document.getElementById('jotpad_' + result['id']);
					theDiv.innerHTML = result['savedText'];
				}
				ta.style.display = 'none';
				theDiv.style.display = '';
	           	window.setTimeout('ajaxStatus.hideStatus()', 2000);
	        }
	    };
	}();
}
</script>{/literal}
