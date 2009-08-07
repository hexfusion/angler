<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

 // $Id: Forms.php,v 1.5 2006/08/22 19:59:34 awu Exp $

echo "
<script type=\"text/javascript\" src=\"include/javascript/overlibmws.js\"></script><script type=\"text/javascript\" src=\"include/javascript/overlibmws_iframe.js\"></script>
<script>
	if(typeof(document.getElementById(\"overDiv\")) == 'undefined'){
	document.write('<div id=\"overDiv\" style=\"position:absolute; visibility:hidden; z-index:1000;\"></div>');
	};
	</script>
	";

//for users 
function user_get_validate_record_js() {
}
function user_get_chooser_js() {
}
function user_get_confsettings_js() {
};
//end for users
function get_chooser_js() {
	// added here for compatibility
}
function get_validate_record_js() {
}
function get_new_record_form() {
    
	if(empty($_SESSION['studio']['module']))return '';
	
	global $mod_strings;
	$module_name = $_SESSION['studio']['module'];
	$debug = true;
	$html = "";
	
	 global $image_path;
	$html = get_left_form_header($mod_strings['LBL_TOOLBOX']);
	$add_field_icon = get_image($image_path."plus_inline", 'style="margin-left:4px;margin-right:4px;" alt="Add Field" border="0" align="absmiddle"');
	$minus_field_icon = get_image($image_path."minus_inline", 'style="margin-left:4px;margin-right:4px;" alt="Add Field" border="0" align="absmiddle"');
	$edit_field_icon = get_image($image_path."edit_inline", 'style="margin-left:4px;margin-right:4px;" alt="Add Field" border="0" align="absmiddle"');
	$delete = get_image($image_path."delete_inline", "border='0' alt='Delete' style='margin-left:4px;margin-right:4px;'");
	$show_bin = true;
	if (isset ($_REQUEST['edit_subpanel_MSI']))
	global $sugar_version, $sugar_config;
		$show_bin = false;

	$html .= "
           
			<script type=\"text/javascript\" src=\"modules/DynamicLayout/DynamicLayout_3.js\">
			</script>
			<p>
		";

	if (isset ($_REQUEST['edit_col_MSI'])) {
		// do nothing
	} else {
		$html .= <<<EOQ
		
	   
	   <link rel="stylesheet" type="text/css" href="include/javascript/yui/assets/container.css" />
		            	<script type="text/javascript" src="include/javascript/yui/container.js"></script> 
					<script type="text/javascript" src="include/javascript/yui/PanelEffect.js"></script>
					
					
	
EOQ;
		
		$field_style = '';
		$bin_style = '';

		$add_icon = get_image($image_path."plus_inline", 'style="margin-left:4px;margin-right:4px;" alt="Maximize" border="0" align="absmiddle"');
		$min_icon = get_image($image_path."minus_inline", 'style="margin-left:4px;margin-right:4px;" alt="Minimize" border="0" align="absmiddle"');
	   $del_icon = get_image($image_path."delete_inline", 'style="margin-left:4px;margin-right:4px;" alt="Minimize" border="0" align="absmiddle"');
		$html .=<<<EOQ
		              <br><br><table  cellpadding="0" cellspacing="0" border="1" width="100%"   id='s_field_delete'>
							<tr><td colspan='2' align='center'>
					       $del_icon <br>Drag Fields Here To Delete
						</td></tr></table>
					<div id="s_fields_MSIlink" style="display:none">
						<a href="#" onclick="toggleDisplay('s_fields_MSI');">
							 $add_icon {$mod_strings['LBL_VIEW_SUGAR_FIELDS']}
						</a>
					</div>
					<div id="s_fields_MSI" style="display:inline">
		
						<table  cellpadding="0" cellspacing="0" border="0" width="100%" id="studio_fields">
							<tr><td colspan='2'>
							         
									<a href="#" onclick="toggleDisplay('s_fields_MSI');">$min_icon</a>{$mod_strings['LBL_SUGAR_FIELDS_STAGE']}
								    <br><select id='studio_display_type' onChange='filterStudioFields(this.value)'><option value='all'>All<option value='custom'>Custom</select>
									</td>
							</tr>
						</table>
					</div>

EOQ;
	
	}
	$html .= get_left_form_footer();
	if (!$debug)
		return $html;
	return $html.<<<EOQ


<script>
function toggleygLogger(el) {
		if (el.value == "Disable Logger") {
			ygLogger.disable();
			el.value = "Enable Logger";
		} else {
			ygLogger.enable();
			el.value = "Disable Logger";
		}
}
</script>

EOQ;
}
?>
