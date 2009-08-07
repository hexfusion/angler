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
	require_once('modules/DynamicLayout/SlotParser.php');
	
	require_once('include/SubPanel/SubPanel.php');

class SubPanelParser extends SlotParser{
	var $subpanel;
	var $parent_module;
	var $child_module = '';
	
	function SubPanelParser(){
		if(!isset($_REQUEST['record'])){
				$_REQUEST['record'] = -1;
		}
		parent::SlotParser();	
		
	}
	function set_subpanel($module_name,$child_module){
		$this->parent_module = $module_name;
		$this->child_module = $child_module;
		global $beanList, $beanFiles;
		$class = $beanList[$module_name];
		
		require_once($beanFiles[$class]);
		
		require_once('include/SubPanel/SubPanelDefinitions.php');
		$mod = new $class();
		$spd = new SubPanelDefinitions($mod);
		$spd->open_layout_defs(true);
		$panel = $spd->load_subpanel($child_module, true);
		$this->panel = $panel;
		
		$subpanel = new SubPanel($module_name, 'fab4', $child_module, $panel);
		$subpanel->setTemplateFile('include/SubPanel/SubPanelDynamic.html');
		ob_flush();
		ob_clean();
		
		$subpanel->display();
		$this->contents= ob_get_contents();
		ob_clean();
		$this->subpanel = $subpanel;
	
		
	}
	
	function get_edit_view(){
		$view = $this->contents;
		$counter = 0;
		$fields = get_register_values('dyn_layout_fields');
		
		
		for($i = 0 ; $i < sizeof($this->slots); $i++){
			$slot =$this->slots[$i];
			$explode = explode($slot[0], $view, 2);
			$view =  $explode[0] . "<div id='slot_$counter' style='display:inline;cursor:pointer;cursor:hand;'";
			if($i < sizeof($this->slots)/2)$view.="  onMouseDown=\"swap_div('slot_$counter');\"  onMouseUp=\"if(last_id != ''){swap_div('slot_$counter');}\" onmouseover=\"swap_text(this.innerHTML);\" onmouseout=\"swap_text('&nbsp;')\" > {$this->font_slot}&nbsp;";
			else $view .=">";
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
	
	function save_layout(){
		global  $beanList;
		$override = $this->handle_save_swaps();
		
		
		$this->subpanel->saveSubPanelDefOverride( $this->panel,'list_fields', $override); 
	}
	
	function handle_save_swaps(){
		
		global $layout_defs, $beanList;
		$final_array = array();
		$view = $this->contents;
		$counter = 0;
		$return_view = '';
		
		
		$ret_fields = array();	
		$fields = $this->panel->panel_definition['list_fields'];
		foreach($fields as $name => $field){
			if(!isset($field['usage'])|| $field['usage'] != 'query_only'){
				$new_fields[] = $field; 
				$old_fields[] = $field;
				$old_names[] = $name;
				$new_names[] = $name;
				
			}else{
				$ret_fields[$name] = $field; 	
			}
			
			
		}
		
		for($i = 0; $i < sizeof($this->slots)/2; $i++){
			$slot = $this->slots[$i];
			
			$explode = explode($slot[0], $view, 2);
			$explode[0] .= '<slot>';
			$explode[1] = '</slot>' . $explode[1];
			
			if(!empty($_REQUEST['add_slot_'. $i] )){
				$vname = '';
			
				if(isset($this->subpanel->subpanel_defs->template_instance->field_defs[$_REQUEST['add_slot_'. $i]])){
					$vname = $this->subpanel->subpanel_defs->template_instance->field_defs[$_REQUEST['add_slot_'. $i]]['vname'];
				}
				
				$new_fields[$i] = array('name'=>from_html($_REQUEST['add_slot_'. $i]),'vname'=>$vname);
				$new_names[$i] = from_html($_REQUEST['add_slot_'. $i]);
			}else if($_REQUEST['form_slot_'. $i] == '-33' || $_REQUEST['form_slot_'. $i] == '-1'){
				//this is a delete row
				$new_fields[$i] = array('name'=>'', 'usage'=>'display_only','sortable'=>false, 'display_label'=>false);
				$new_names[$i] = time();
			}else {
				if($_REQUEST['form_slot_'. $i] < 0)
					$_REQUEST['form_slot_'. $i] = $i;
				$new_fields[$i] = $old_fields[$_REQUEST['form_slot_'. $i]];
				$new_names[$i] = $old_names[$_REQUEST['form_slot_'. $i]];
				
			}
			
			$view = $explode[1];
			$counter++;
			
		}
		
		foreach($new_fields as $index=>$value){
			$ret_fields[$new_names[$index]] = $value;
		}
		return $ret_fields;
	}
	
	
	
	function get_form(){
		global $mod_strings;
		return $this->form . "<input type='hidden' name='subpanel' value='$this->child_module'><input type='hidden' name='select_subpanel_module' value='$this->parent_module'>".'<br><input type="hidden" name="edit_subpanel_MSI" value="true"><input type="submit" class="button" name="save_subpanel_MSI" value="'.$mod_strings['LBL_SAVE_LAYOUT'].'">&nbsp;<b>('.$this->parent_module  . ' subpanel - ' . $this->child_module.')</b></form>';	
	}
	
	function get_javascript_swap(){
		$image = substr_replace($this->font_slot, "onMouseDown='cancel_swap_div();' ", strlen($this->font_slot) -1) . '>';
		return <<<EOQ

				<div class='dataField' id='hotswapcontainter' style='display:none;position:absolute;z-index:10;background-color:#FFFFFF;border:1px solid #ff0000;padding:2px;' ><input type='hidden' id='form_hotswap' name='form_hotswap' value='-1'><input type='hidden' id='add_hotswap' name='add_hotswap' value=''><div id='hotswap' style='display:inline'>{$this->font_slot}&nbsp;</div></div>
				<div id='textcontainter' style='display:none;position:absolute;z-index:11;background-color:#FFFFC2;border:1px solid #222222;font-color:#000000;padding:2px' >&nbsp;</div>
				<script>registerMouseMove(); font_slot = "$image"; setFileType('subpanel');</script>
EOQ;
		
	}
	
	function indexPage(){
		if(isset($_REQUEST['edit_col_MSI'])){
			require_once('modules/DynamicLayout/plugins/SubPanelColParser.php');
			$sp = new SubPanelColParser();
		}else{
			$sp = new SubPanelParser();	
		}
			
		global $beanList, $beanFiles, $mod_strings;
		
		
	
	//if the last request was a save lets do that
	$parent_module = $_REQUEST['select_subpanel_module'];
	$subpanel = $_REQUEST['subpanel'];
	//$layout_def = SubPanel::getSubPanelDefine($parent_module, $subpanel);
	
		
	if(!empty($_REQUEST['save_subpanel_MSI'])){
		$sp->set_subpanel($parent_module,$subpanel);
		$sp->parse_text();
		$file = $sp->save_layout();
	}
		
		$sp->set_subpanel($parent_module,$subpanel);
		$sp->parse_text();
		echo $sp->get_javascript_swap();
		$view =$sp->get_edit_view();
		$prev_mod = $mod_strings;
		echo $sp->get_form();
		echo $view;
		$mod_strings = $prev_mod;
		
		$prev_mod = $mod_strings;
		$slotCount = sizeof($sp->slots);
		
		echo "<script> setModuleName('$sp->child_module'); setFileType('subpanel'); setSlotCount($slotCount); </script>";
		require_once('modules/DynamicLayout/AddField.php');
		$addfield = new AddField();
		
		
		require_once('modules/DynamicFields/DynamicField.php');
		$submodulename = $sp->panel->_instance_properties['module'];
		$submoduleclass = $beanList[$submodulename];
		require_once($beanFiles[$submoduleclass]);
		$child_module = new $submoduleclass();
		$customFields = new DynamicField($child_module);	

		$customFields->setup($child_module);
		$result = $customFields->getAllBeanFieldsView('list', 'html');
		foreach($result as $f_name=>$f_field){
			if(isset($child_module->field_defs[$f_name]['vname']))
			$addfield->add_field_no_label($f_name, translate($child_module->field_defs[$f_name]['vname'], 'Contacts'), '', 'sugar_fields_MSI', true);	
		}
		echo $addfield->get_script();	
		
	}

	
}




?>
