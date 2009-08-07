<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
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
 ********************************************************************************/
require_once('modules/DynamicLayout/DynamicLayoutUtils.php');
//for users 
function user_get_validate_record_js (){}
function user_get_chooser_js(){}
function user_get_confsettings_js() {};
//end for users


function get_chooser_js() {
	// added here for compatibility
}

function get_validate_record_js() {

}
function get_new_record_form () {
require_once('modules/DynamicLayout/AddField.php');
global $image_path, $mod_strings;
global $sugar_version, $sugar_config;

if(!empty($_REQUEST['edit_label_MSI']) && !empty($_SESSION['dyn_layout_module'])){
$module_name = $_SESSION['dyn_layout_module'];
$html =  get_left_form_header($mod_strings['LBL_TOOLBOX']);
 $html .=<<<EOQ
 	<table class="contentBox" cellpadding="0" cellspacing="0" border="0" width="100%" id="sugar_labels_MSI">Sugar Labels <br><iframe name="labeleditor"  height='400' id="labeleditor" frameborder="0"  width="280" marginwidth="0" marginheight="0" style="border: 1px solid #444444;" src="index.php?module=LabelEditor&action=LabelList&module_name={$module_name}&sugar_body_only=1" ></iframe></td></tr></table>
EOQ;
$html .= get_left_form_footer();
return $html;

} else if(!empty($_REQUEST['edit_subpanel_MSI']) || (empty($_REQUEST['edit_row_MSI'])&& empty($_REQUEST['edit_col_MSI']) && $_REQUEST['action'] != 'SelectFile' && !empty($_SESSION['dyn_layout_file']))){
$addfield = new AddField();
if(isset($_SESSION['dyn_layout_file']))
$the_module = get_module($_SESSION['dyn_layout_file']);
$font_slot = '<IMG src="'.$image_path.'slot.gif" alt="Slot" border="0" >';
$slot_path =$image_path ."slot.gif";
$html =  get_left_form_header($mod_strings['LBL_TOOLBOX']);
$add_field_icon = get_image($image_path."plus_inline",'style="margin-left:4px;margin-right:4px;" alt="Add Field" border="0" align="absmiddle"');
$minus_field_icon = get_image($image_path."minus_inline",'style="margin-left:4px;margin-right:4px;" alt="Add Field" border="0" align="absmiddle"');
$edit_field_icon = get_image($image_path."edit_inline",'style="margin-left:4px;margin-right:4px;" alt="Add Field" border="0" align="absmiddle"');
$delete = get_image($image_path."delete_inline","border='0' alt='Delete' style='margin-left:4px;margin-right:4px;'");
$show_bin = true;
if(isset($_REQUEST['edit_subpanel_MSI']))$show_bin = false;
$delete_items = $addfield->get_html(true,$show_bin);

$html .= "
	<script>
	var slot_path = '{$slot_path}';
	var font_slot = '{$font_slot}';
	</script>
	<script type=\"text/javascript\" src=\"modules/DynamicLayout/DynamicLayout_3.js?s=" . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . "\">
	</script>
	<p>
";

if(isset($_REQUEST['edit_col_MSI'])) {
	// do nothing
} else {
	if(!isset($_REQUEST['edit_subpanel_MSI'])){
	$html .= "
	<input type='checkbox' class=\"checkbox\" style='vertical-align: middle;' id='display_html_MSI' name='display_html_MSI' > {$mod_strings['LBL_DISPLAY_HTML']} <br>
	<a href='#' onclick='addFieldPOPUP()' class='leftColumnModuleS3Link'>$add_field_icon</a> <a href='#' onclick='addFieldPOPUP()' class='leftColumnModuleS3Link'>{$mod_strings['LBL_ADD_FIELDS']}</a>
	<br>";
	}
	$html .= "
	<a href=\"#\" onclick=\"editCustomFields();\" class='leftColumnModuleS3Link'>$edit_field_icon</a>
	<a href='#' onclick=\"editCustomFields();\" class='leftColumnModuleS3Link'>{$mod_strings['LBL_EDIT_FIELDS']}</a><br>
	
	<p>$delete_items</p>";
}

$html .= get_left_form_footer();
return $html;
}


}

?>
