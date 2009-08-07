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
	require_once('modules/DynamicLayout/plugins/SubPanelParser.php');
	require_once('include/SubPanel/SubPanel.php');

class SubPanelColParser extends SubPanelParser{
	function SubPanelColParser(){
		parent::SubPanelParser();	
	}
	
	function get_edit_view(){
		global $image_path;
		$view = $this->contents;
		$counter = 0;
		$fields = get_register_values('dyn_layout_fields');
		
		
		for($i = 0 ; $i < sizeof($this->slots); $i++){
			$slot =$this->slots[$i];
			$explode = explode($slot[0], $view, 2);
			$view =  $explode[0];
			if($i > 0 && $i < sizeof($this->slots)/2)$view.="<a href='#' onclick='add_col_to_view($counter)' >". get_image($image_path."plus_inline","border='0' alt='Add Column ->'")."</a><a href='#' onclick='delete_col_from_view($counter)' >".get_image($image_path."minus_inline","border='0' alt='<- Delete Column '")."</a></td><td  class='listViewThS1'>";
			else if($i > 0 && $i > sizeof($this->slots)/2 )$view .="</td><td >";
			$view .= $slot[1] . "</div>" . $explode[1];
			if($fields){
			foreach($fields as $field=>$field_code){
				
				if(trim($slot[1])== trim($field_code[1]) || preg_match("'name[\ ]*=[\ ]*([\'\"])". $field_code[0] . "\\1'si", $slot[1])){
					$this->remove_from_add_fields($field);	
				} 	
			}
			}
			
			$this->add_to_form($counter);
			$counter++;
		}
		return $view;
	}
	
	
	function handle_save_swaps(){
		global $layout_defs, $beanList;
		$final_array = array();
		$view = $this->contents;
		$counter = 0;
		$return_view = '';
		$fields = $this->panel->panel_definition['list_fields'];
		$ret_fields = array();
		foreach($fields as $name => $field){
			if(!isset($field['usage'])|| $field['usage'] != 'query_only'){
				$new_fields[] = $field; 
				$new_names[] = $name;
				
			}else{
				$ret_fields[$name] = $field; 	
			}
			
		}
	
		if(isset($_REQUEST['add_col_MSI']) && $_REQUEST['add_col_MSI'] > -1){
			array_splice($new_fields, $_REQUEST['add_col_MSI'], 0, array(array('usage'=>'display_only', 'sortable'=>false, 'display_label'=>false)));
			array_splice($new_names, $_REQUEST['add_col_MSI'], 0, array(time()));
		}
		if(isset($_REQUEST['delete_col_MSI']) && $_REQUEST['delete_col_MSI'] > -1){
			array_splice($new_fields, $_REQUEST['delete_col_MSI'],  -1 * (count($new_fields) - $_REQUEST['delete_col_MSI'] -1 ));
			array_splice($new_names, $_REQUEST['delete_col_MSI'],  -1 * (count($new_names) - $_REQUEST['delete_col_MSI'] -1 ));
		}
		foreach($new_fields as $index=>$value){
			$ret_fields[$new_names[$index]] = $value;
		}
		return $ret_fields;

	}
	
	
	
	function get_form(){
		return <<<EOQ
				
				<form name='add_col_form'>
				<input type="hidden" name="action" value="index">
			<input type="hidden" name="module" value="DynamicLayout">
			<input type='hidden' id='add_col_MSI' name='add_col_MSI' value='-1'>
			<input type='hidden' id='edit_col_MSI' name='edit_col_MSI' value='1'>
			<input type='hidden' id='edit_subpanel_MSI' name='edit_subpanel_MSI' value='1'>
			<input type='hidden' id='subpanel' name='subpanel' value='{$this->child_module}'>
			<input type='hidden' id='select_subpanel_module' name='select_subpanel_module' value='{$this->parent_module}'>
			<input type='hidden' id='delete_col_MSI' name='delete_col_MSI' value='-1'>
			<input type="hidden"  name="save_subpanel_MSI" value="Save Layout">
		</form>
		<script>
			function delete_col_from_view(i){
				document.getElementById('delete_col_MSI').value = i;
				document.add_col_form.submit();
			}
			function add_col_to_view(i){
				document.getElementById('add_col_MSI').value = i;
				document.add_col_form.submit();
			}
		</script>	
EOQ;
	}
	
	function get_javascript_swap(){
		$image = substr_replace($this->font_slot, "onMouseDown='cancel_swap_div();' ", strlen($this->font_slot) -1) . '>';
		return <<<EOQ


				<div class='dataField' id='hotswapcontainter' style='display:none;position:absolute;z-index:10;background-color:#FFFFFF;border:1px solid #ff0000;padding:2px;' ><input type='hidden' id='form_hotswap' name='form_hotswap' value='-1'><input type='hidden' id='add_hotswap' name='add_hotswap' value=''><div id='hotswap' style='display:inline'>{$this->font_slot}&nbsp;</div></div>
				<div id='textcontainter' style='display:none;position:absolute;z-index:11;background-color:#FFFFC2;border:1px solid #222222;font-color:#000000;padding:2px' >&nbsp;</div>
				<script>registerMouseMove(); font_slot = "$image"; setFileType('subpanel');</script>
EOQ;
		
	}
	
	



	
}




?>
