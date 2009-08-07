<?php
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

 // $Id: VarDefHandler.php,v 1.21 2006/08/22 18:56:15 awu Exp $
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

//Vardef Handler Object



class VarDefHandler {

	var $meta_array_name;
	var $target_meta_array = false;
	var $start_none = false;
	var $extra_array = array();					//used to add custom items
	var $options_array = array();
	var $module_object;
	var $start_none_lbl = null;
	
	function VarDefHandler(& $module, $meta_array_name=null){
		$this->module_object = $module;
		if($meta_array_name!=null){
			global $vardef_meta_array;
			include("include/VarDefHandler/vardef_meta_arrays.php");





			$this->target_meta_array = $vardef_meta_array[$meta_array_name];
		}
		
	//end function setup
	}

	function get_vardef_array($use_singular=false){
		global $dictionary;
		global $current_language;
		global $app_strings;
		
		$temp_module_strings = return_module_language($current_language, $this->module_object->module_dir);
		
		$base_array = $this->module_object->field_defs;
		//$base_array = $dictionary[$this->module_object->object_name]['fields'];
		
		///Inclue empty none set or not
		if($this->start_none==true){
			if(!empty($this->start_none_lbl)){
				$this->options_array[''] = $this->start_none_lbl;
			} else {	
				$this->options_array[''] = $app_strings['LBL_NONE'];
			}
		}
		
	///used for special one off items added to filter array	 ex. would be href link for alert templates
		if(!empty($this->extra_array)){
		
			foreach($this->extra_array as $key => $value){
				$this->options_array[$key] = $value;
			}
		}	
	/////////end special one off//////////////////////////////////	
		
	
		foreach($base_array as $key => $value_array){
			$compare_results = $this->compare_type($value_array);
			
			if($compare_results == true){
				 $label_name = '';
                 if($value_array['type'] == 'link'){
                    $this->module_object->load_relationship($value_array['name']);
                    $label_name = $this->module_object->$value_array['name']->getRelatedModuleName();
                }
				else if(!empty($value_array['vname'])){
					$label_name = $value_array['vname'];
				} else {
					$label_name = $value_array['name'];	
				}	
				            
                
			$label_name = get_label($label_name, $temp_module_strings);

				if(!empty($value_array['table'])){	
					//Custom Field
					$column_table = $value_array['table'];
				} else {
					//Non-Custom Field
					$column_table = $this->module_object->table_name;
				}	

                if($value_array['type'] == 'link' && !empty($label_name)){
                    $index = $key;   
                }else{
					$index = $key;
                }

				$value = trim($label_name, ':');

				$this->options_array[$index] = $value;
			
			//end if field is included
			}
				
		//end foreach
		}
		if($use_singular == true){
			return convert_module_to_singular($this->options_array);
		} else {
			return $this->options_array;		
		}			
	
	//end get_vardef_array
	}	
		
	
	function compare_type($value_array){

		//Filter nothing?
		if(!is_array($this->target_meta_array)){
			return true;	
		}	

		////////Use the $target_meta_array;
		if(isset($this->target_meta_array['inc_override'])){
			foreach($this->target_meta_array['inc_override'] as $attribute => $value){
				
					foreach($value as $actual_value){
						if(isset($value_array[$attribute]) && $value_array[$attribute] == $actual_value) return true;	
					}	
					if(isset($value_array[$attribute]) && $value_array[$attribute] == $value) return true;

			}
		}
		if(isset($this->target_meta_array['ex_override'])){
			foreach($this->target_meta_array['ex_override'] as $attribute => $value){
		
				
					foreach($value as $actual_value){
					if(isset($value_array[$attribute]) && $value_array[$attribute] == $actual_value) return false;	

						if(isset($value_array[$attribute]) && $value_array[$attribute] == $value) return false;
					}	

			//end foreach inclusion array
			}
		}		

		if(isset($this->target_meta_array['inclusion'])){
			foreach($this->target_meta_array['inclusion'] as $attribute => $value){
				
				if($attribute=="type"){
					foreach($value as $actual_value){
					if(isset($value_array[$attribute]) && $value_array[$attribute] != $actual_value) return false;	
					}	
				} else {			
					if(isset($value_array[$attribute]) && $value_array[$attribute] != $value) return false;
				}
			//end foreach inclusion array
			}
		}

		if(isset($this->target_meta_array['exclusion'])){
			foreach($this->target_meta_array['exclusion'] as $attribute => $value){

				foreach($value as $actual_value){
					if(isset($value_array[$attribute]) && $value_array[$attribute] == $actual_value) return false;	
				}	
	
			//end foreach inclusion array
			}
		}
		
		
		return true;

	//end function compare_type
	}		
	
//end class VarDefHandler
}


?>
