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

// $Id: backupmanager.tpl,v 1.5 2006/09/01 04:18:11 majed Exp $

*}

{literal}
<script>
var populatePreview = function(response){
	var div = document.getElementById('preview'+ response.argument);
	
	if(response.status = 0){
		div.innerHTML = 'Server Connection Failed';
	}else{
		div.innerHTML = response.responseText; 
	}
	
	if(response.argument == 1){
		document.getElementById('preview2').innerHTML = '&nbsp';
		if(document.getElementById('comparespan').style.display='none'){
			document.getElementById('comparespan').style.display='inline';
		}
	}
	
	
};
var previewCallback = {
	success: populatePreview ,
  	failure: populatePreview,
  	argument: 1
};
var COBJ = false;
function previewFile(file, id){
	document.getElementById('preview'+ id).innerHTML = '<h2>Loading...</h2>';
	previewCallback['argument'] = id;
	COBJ = YAHOO.util.Connect.asyncRequest('GET','index.php?module=Studio&action=previewfile&to_pdf=true&preview_file=' + file,previewCallback,null);
	
}
</script>
{/literal}

<form name='StudioWizard'>
<input type='hidden' name='action' value='wizard'>
<input type='hidden' name='module' value='Studio'>
<input type='hidden' name='wizard' value='{$wizard}'>
<table class='tabform' width='100%'>
<tr><td>{$status}</td></tr>
<tr><td colspan='2'>{$welcome}</td></tr>
<tr><td width='1%'><select name='option' size=10 id='option'>{html_options  options=$options}</select></td>
<td>
<input type='button' class='button' name='preview' value='{$MOD.LBL_MB_PREVIEW}' onclick='previewFile(document.getElementById("option").value, 1)'>
<span id='comparespan' style='display:none'>
<br><br>
<input type='button' class='button' name='compare' value='{$MOD.LBL_MB_COMPARE}' onclick='previewFile(document.getElementById("option").value, 2)'>
</span>
<br><br>
<input type='submit' class='button' name='restore' value='{$MOD.LBL_MB_RESTORE}'>
<br><br>
<input type='submit' class='button' name='delete' value='{$MOD.LBL_MB_DELETE}'>
</td></tr>
</table>
</form>

<table width='100%'><tr><td>
<span id='preview1'>&nbsp;</span>
</td>
<td>
<span id='preview2'>&nbsp;</span>
</td>
</tr></table>
