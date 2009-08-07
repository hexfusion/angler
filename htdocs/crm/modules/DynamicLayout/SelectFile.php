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
require_once('include/SubPanel/SubPanel.php');
if(!is_admin($current_user)){

	sugar_die('Only admins may edit layouts');
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$base_path = 'modules';
$blocked_modules = array('CustomQueries', 'iFrames', 'DataSets', 'Dropdown', 'Feeds', 'QueryBuilder', 'ReportMaker', 'Reports', 'ACLRoles');
	$can_display = array('EditView.html' => 1, 'DetailView.html'=> 1, 'ListView.html'=> 1);
$display_files = array();
function get_display_files($path){
	global $display_files, $can_display, $blocked_modules;

	$d = dir($path);
	while($entry = $d->read()){	
		if($entry != '..' && $entry != '.'){
			if(is_dir($path. '/'. $entry)){
				get_display_files($path. '/'. $entry);	
			}else{
				if(key_exists($entry, $can_display)){
					$can_add = true;
					foreach($blocked_modules as $mod){
						if(substr_count($path, $mod) > 0){
							$can_add = false;	
						}	
					}
					if($can_add){
					$display_files[create_guid()] = $path. '/'. $entry;
					}
				}	
			}
		}
	}

}

if(!isset($_SESSION['dyn_layout_files'])){
	get_display_files($base_path);
	asort($display_files);
	reset($display_files);
	$_SESSION['dyn_layout_files'] = $display_files;
}else{
	$display_files = $_SESSION['dyn_layout_files'];
}
echo get_form_header($mod_strings['DESC_USING_LAYOUT_SELECT_FILE'],'',false);
echo <<<EOQ
<form method='post' action='index.php'>
<input type='hidden' name='action' value='index'>
<input type='hidden' name='module' value='DynamicLayout'>
<select name='select_file_id'>
EOQ;
echo get_select_options_with_id($display_files,'');
echo '</select>';
echo '<input type="submit" class="button" name="Submit" value="' . $mod_strings['LBL_SELECT_FILE'] . '"></form>';
$edit_in_place = '';
if(!empty($_SESSION['editinplace'])){
	$edit_in_place = 'checked';	
}
echo '</form>';

echo <<<EOQ
<form name='editinplace' method='post' action='index.php'  >
<input type='hidden' name='action' value='index'>
<input type='hidden' name='module' value='DynamicLayout'>
<input type='hidden' name='in_place' value='true'>
{$mod_strings['LBL_EDIT_IN_PLACE']} <input type="checkbox" name="edit_in_place" class="checkbox" onChange='document.editinplace.submit();' value="Edit In Place" $edit_in_place>
</form>
EOQ;
$subpanelmodules = SubPanel::getModulesWithSubpanels();
echo '<BR><BR>';
echo get_form_header($mod_strings['LBL_SELECT_A_SUBPANEL'],'',false);
echo <<<EOQ
<script>
var last_subpanel = '';
function swap_subpanels(){
	subpanel_module = document.getElementById('select_subpanel_module');
	selected_subpanel = subpanel_module.options[subpanel_module.selectedIndex].value;
	
	if(last_subpanel != ''){
		document.getElementById(last_subpanel).style.display = 'none';
	}
	last_subpanel = selected_subpanel;
	document.getElementById(last_subpanel).style.display = 'inline';
}
function get_subpanel_value(){
	subpanel_module = document.getElementById('select_subpanel_module');
	selected_subpanel = subpanel_module.options[subpanel_module.selectedIndex].value;
	subpanel = document.getElementById(selected_subpanel + "subpanel");
	subpanel_value = subpanel.options[subpanel.selectedIndex].value;
	document.getElementById('subpanel').value = subpanel_value;

	return true;
}
</script>
<form method='post' action='index.php'  onsubmit="return get_subpanel_value()">
<input type='hidden' name='action' value='index'>
<input type='hidden' name='module' value='DynamicLayout'>
<input type='hidden' name='edit_subpanel_MSI' value='1'>
<input type='hidden' name='subpanel' id='subpanel' value = ''>
<select id= 'select_subpanel_module' name='select_subpanel_module' onChange='swap_subpanels()'>

EOQ;
echo get_select_options_with_id($subpanelmodules,'');
echo '</select>';




foreach($subpanelmodules as $mod){
	echo "<div style='display:none' id='$mod'><select id='{$mod}subpanel' name='{$mod}subpanel'>";
	$layout_def = SubPanel::getModuleSubpanels($mod);
	foreach($layout_def as $sub){
		echo "<option value='$sub'>$sub</option>";
	}	

	echo '</select></div>';
}

echo '<input type="submit" class="button" name="Submit" value="' . $mod_strings['LBL_SELECT_SUBPANEL'] . '"  ></form>';
echo <<<EOQ
<form name='edit' method='post' action='index.php'>
<input type='hidden' name='action' value='index'>
<input type='hidden' name='module' value='DynamicLayout'>

</form>
EOQ;

echo '<script>swap_subpanels();</script><BR><BR>';
// How To Use Layout Text Block Instructions

$slot_image = "<img src='$image_path". "slot.gif' alt='Slot' border='0'>";

echo "<br>";
echo get_form_header($mod_strings['DESC_USING_LAYOUT_TITLE'],'',false);
$using_layout_shortcuts = $mod_strings['DESC_USING_LAYOUT_SHORTCUTS'];
$using_layout_toolbar = $mod_strings['DESC_USING_LAYOUT_TOOLBAR'];
$using_layout_select_file = $mod_strings['DESC_USING_LAYOUT_SELECT_FILE'];
$using_layout_edit_fields = $mod_strings['DESC_USING_LAYOUT_EDIT_FIELDS'];
$using_layout_edit_rows = $mod_strings['DESC_USING_LAYOUT_EDIT_ROWS'];
$using_layout_add_field = $mod_strings['DESC_USING_LAYOUT_ADD_FIELD'];
$using_layout_remove_item = $mod_strings['DESC_USING_LAYOUT_REMOVE_ITEM'];
$using_layout_display_html = $mod_strings['DESC_USING_LAYOUT_DISPLAY_HTML'];
$using_layout_blk1 = $mod_strings['DESC_USING_LAYOUT_BLK1'];
$using_layout_blk2 = $mod_strings['DESC_USING_LAYOUT_BLK2'];
$using_layout_blk3 = $mod_strings['DESC_USING_LAYOUT_BLK3'];
$using_layout_blk4 = $mod_strings['DESC_USING_LAYOUT_BLK4'];
$using_layout_blk5 = $mod_strings['DESC_USING_LAYOUT_BLK5'];
$using_layout_blk6 = $mod_strings['DESC_USING_LAYOUT_BLK6'];
$using_layout_blk7 = $mod_strings['DESC_USING_LAYOUT_BLK7'];
$using_layout_blk8 = $mod_strings['DESC_USING_LAYOUT_BLK8'];
$using_layout_blk9 = $mod_strings['DESC_USING_LAYOUT_BLK9'];
$using_layout_blk10 = $mod_strings['DESC_USING_LAYOUT_BLK10'];
echo "<br>";
echo $using_layout_blk1, "<br><br>";
echo "<b>",$using_layout_shortcuts,"</b><br>";
echo "<u>", $using_layout_select_file, "</u>", $using_layout_blk2, "<br>";
echo "<u>", $using_layout_edit_fields, "</u>",$using_layout_blk3, $slot_image, $using_layout_blk4, "<br>";
echo "<u>", $using_layout_edit_rows, "</u>",$using_layout_blk5, "<br><br>";
echo "<b>",$using_layout_toolbar,"</b><br>";
echo $using_layout_blk6, "<br>";
echo "<u>", $using_layout_add_field, "</u>",$using_layout_blk7, "<br>";
echo "<u>", $using_layout_remove_item, "</u>",$using_layout_blk8, "<br>";
echo "<u>", $using_layout_display_html, "</u>",$using_layout_blk9, "<br><br>";
echo $using_layout_blk10;

?>
