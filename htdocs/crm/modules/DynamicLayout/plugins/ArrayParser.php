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

class ArrayParser extends SlotParser{
	function ArrayParser(){
		parent::SlotParser();	
	}
	function set_array($array, $indent=0){
		$slot_start = '<slot>';
		$slot_end = '</slot>';
		if(empty($this->contents)){
			$this->contents = 'Array(<br>';
		}else{
			$slot_start = '';
			$slot_end = '';
			$this->contents .= ' Array(<br>';
		}

			
		foreach($array as $key=>$arr){
			for($i = 0 ; $i  < $indent; $i++){
				$this->contents .= '&nbsp; &nbsp; &nbsp; ';
			}
			$this->contents .='&nbsp;&nbsp;&nbsp;'. $slot_start . var_export_helper($key) . $slot_end .'&nbsp;=>&nbsp;';
			if(is_array($arr)){
				$this->contents .= $slot_start;
				$this->set_array($arr, $indent + 1);
				$this->contents .= $slot_end;
			}else{$this->contents .= $slot_start . var_export_helper($arr, true) . $slot_end .'<br>';}
				
		}	
		for($i = 0 ; $i  < $indent; $i++){
				$this->contents .= '&nbsp; &nbsp; &nbsp; ';
		}
		$this->contents .= ');<br>';
	}
	function save_layout($file){
		print_r($this->handle_save_swaps());
		die();
	}
	
	function handle_save_swaps(){
		$final_array = array();
		$view = $this->contents;
		$counter = 0;
		$return_view = '';
		for($i = 0; $i < sizeof($this->slots); $i++){
			$slot = $this->slots[$i];
			
			$explode = explode($slot[0], $view, 2);
			$explode[0] .= '<slot>';
			$explode[1] = '</slot>' . $explode[1];
			if(!empty($_REQUEST['add_slot_'. $i] )){
				$return_view .= $explode[0] . from_html($_REQUEST['add_slot_'. $i]);
			}else if($_REQUEST['form_slot_'. $i] == '-33' || $_REQUEST['form_slot_'. $i] == '-1'){
				//this is a delete row
				$return_view .=  $explode[0] . '&nbsp;';
				$logger->swap_fields($i, $_REQUEST['form_slot_'. $i]);
				$df->delete_field( $this->slots[$i][1]);
			}else {
				if($_REQUEST['form_slot_'. $i] < 0)
					$_REQUEST['form_slot_'. $i] = $i;
				$logger->swap_fields($i, $_REQUEST['form_slot_'. $i]);
				$return_view .=  $explode[0] . $this->slots[$_REQUEST['form_slot_'. $i]][1];
			}
			
			$view = $explode[1];
			$counter++;
			
		}
		$logger->close();
		$df->save_deleted_fields();
		if(empty($return_view))
			return $this->contents;	
		return $return_view. $view;
	}
	
	function get_edit_view(){
		$view = '';
		$counter = 0;
		$fields = get_register_values('dyn_layout_fields');
		$contents = explode('<slot>', $this->contents);
		foreach($contents as $content){
			$subcontents = explode('</slot>', $content);
			if($counter > 0){
				$view .= "<div id='slot_$counter' style='display:inline;cursor:pointer;cursor:hand;' onMouseDown=\"swap_div('slot_$counter');\" onMouseUp=\"if(last_id != ''){swap_div('slot_$counter');}\"  onmouseover=\"swap_text(this.innerHTML);\" onmouseout=\"swap_text('&nbsp;')\" > {$this->font_slot}&nbsp;\n";
			}
		
			for($i = 0; $i < sizeof($subcontents); $i++){
				$subcontent = $subcontents[$i];
				$view .= $subcontent . "\n" ;
		
				if($counter > 0 && sizeof($subcontents) > 0  && $i < sizeof($subcontents) - 1){
					$view .= "</div>\n";	
				}
			
				
				if($fields){
					foreach($fields as $field=>$field_code){
				
						if(trim($slot[1])== trim($field_code[1]) || preg_match("'name[\ ]*=[\ ]*([\'\"])". $field_code[0] . "\\1'si", $subcontent)){
							$this->remove_from_add_fields($field);	
						} 	
					}
				}
			}
			
			$counter++;
			$this->add_to_form($counter);
		}
		return $view;
		
	}
	
	function get_form(){
		global $mod_strings;
		return $this->form . '<br><input type="submit" class="button" name="save_layout_MSI" value=value="'.$mod_strings['LBL_SAVE_LAYOUT'].'">&nbsp;<b>('.$this->file . ')</b></form>';	
	}
	
	function get_javascript_swap(){
		$image = substr_replace($this->font_slot, "onMouseDown='cancel_swap_div();' ", strlen($this->font_slot) -1) . '>';
		return <<<EOQ

				<div class='dataField' id='hotswapcontainter' style='display:inline;position:absolute;z-index:10;background-color:#FFFFFF;border:1px solid #ff0000;padding:2px;' ><input type='hidden' id='form_hotswap' name='form_hotswap' value='-1'><input type='hidden' id='add_hotswap' name='add_hotswap' value=''><div id='hotswap' style='display:inline'>{$this->font_slot}&nbsp;</div></div>
				<div id='textcontainter' style='display:inline;position:absolute;z-index:11;background-color:#FFFFC2;border:1px solid #222222;font-color:#000000;padding:2px' >&nbsp;</div>
				<script>registerMouseMove(); font_slot = "$image";</script>
EOQ;
		
	}

	
}




?>
