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

 // $Id: ListViewParser.php,v 1.2 2006/08/22 19:59:34 awu Exp $


class ListViewParser{
	var $listViewDefs = false;
	var $defaults = array();
	var $additional = array();
	var $available = array();
	function loadModule($module_name){
		global $app_strings, $app_list_strings;
		$this->mod_strings = return_module_language($GLOBALS['current_language'], $module_name);
		$mod_strings = $this->mod_strings;
		$class = $GLOBALS['beanList'][$module_name]; 
		require_once($GLOBALS['beanFiles'][$class]);
		$this->module = new $class();
		include('modules/' . $module_name . '/metadata/listviewdefs.php');	
		$this->originalListViewDefs = $listViewDefs[$module_name];
		if(file_exists('custom/modules/' . $module_name . '/metadata/listviewdefs.php')){
			include('custom/modules/' . $module_name . '/metadata/listviewdefs.php');	
			$this->listViewDefs = $listViewDefs[$module_name];
			
		}else{
			$this->listViewDefs =& $this->originalListViewDefs;
		}
		
	}
	
	/**
	 * returns the default fields for a listview
	 */
	function getDefaultFields(){
		$this->defaults = array();
		
		foreach($this->listViewDefs as $key=>$def){
			if(!empty($def['default'])){
				$this->defaults[$key]= $def;
			}	
		}	
		return $this->defaults;
	}
	/**
	 * returns additional fields available for users to create fields
	 */
	function getAdditionalFields(){
		$this->additional = array();
		foreach($this->listViewDefs as $key=>$def){
			if(empty($def['default'])){
				$this->additional[$key]= $def;
			}	
		}
		return $this->additional;	
	}
	
	/**
	 * returns unused fields that are available for using in either default or additional list views
	 */
	function getAvailableFields(){
		$this->availableFields = array();
		foreach($this->originalListViewDefs as $key=>$def){
			if(!isset($this->listViewDefs[$key])){
				$this->availableFields[$key] = $def;	
			}
		}
		foreach($this->module->field_defs as $key=>$def){
			if((empty($def['source']) || $def['source'] == 'db' || !empty($def['custom_type'])) && empty($this->listViewDefs[strtoupper($key)])){
				$this->availableFields[$key] = array('width' => '25', 'label'=> $def['vname'] );	
			}	
		}
		return $this->availableFields;	
	}	
	
	function handleSave(){
		global $mod_strings;
		$module_name = $this->module->module_dir;
		$fields = array();
	
		for($i= 0; isset($_POST['group_' . $i]) && $i < 2; $i++){
			foreach($_POST['group_' . $i] as $field){
				
				$field = strtoupper($field);
				
				if(isset($this->originalListViewDefs[$field])){
						$fields[$field] = $this->originalListViewDefs[$field];
				}else{
					
					$fields[$field] = array('width'=>10, 'label'=>$this->module->field_defs[strtolower($field)]['vname']);	
				}
				$default = false;
				if($i == 0){
					$default = true;	
				}
				$fields[$field]['default'] = $default;
			}	
		} 
		
		$newFile = create_custom_directory('modules/'. $module_name . '/metadata/listviewdefs.php' );
     	
     	write_array_to_file("listViewDefs['$module_name']", $fields, $newFile);
		$GLOBALS['listViewDefs'][$module_name] = $fields;
	}
	
	
}




?>
