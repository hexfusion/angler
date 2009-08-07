<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Subpanel definition classes to ease the use of layout_defs.php
 *
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

// $Id: SubPanelDefinitions.php,v 1.30 2006/08/31 00:51:15 majed Exp $

//input
//	module directory
//constructor
//	open the layout_definitions file.
//
class aSubPanel {
	
	var $name;
	var $_instance_properties;
	
	var $mod_strings;
	var $panel_definition;
	var $sub_subpanels;
	var $parent_bean;
	
	//module's table name and column fields.
	var $table_name;
	var $db_fields;
	var $bean_name;
	var $template_instance; 

	function aSubPanel($name,$instance_properties, $parent_bean, $reload = false) {

		global $current_language;
		$this->_instance_properties=$instance_properties;
		$this->name=$name;
		$this->parent_bean=$parent_bean;

		//set language
		global $current_language;
		$mod_strings = return_module_language($current_language, $parent_bean->module_dir);
		$this->mod_strings=$mod_strings;

		if ($this->isCollection()) { 
			$this->load_sub_subpanels(); //load sub-panel definition.
		} else {
			$def_path = 'modules/'.$this->_instance_properties['module'].'/subpanels/'.$this->_instance_properties['subpanel_name'].'.php';
			if(!$reload){
				require($def_path);
			}else{
				require($def_path);	
			}
			if(isset($this->_instance_properties['override_subpanel_name']) && file_exists('custom/modules/'.$this->_instance_properties['module'].'/subpanels/'.$this->_instance_properties['override_subpanel_name'].'.php')){
				$cust_def_path = 'custom/modules/'.$this->_instance_properties['module'].'/subpanels/'.$this->_instance_properties['override_subpanel_name'].'.php';
				if(!$reload){
					require($cust_def_path);
				}else{
					require($cust_def_path);	
				}
			}
			$this->panel_definition=$subpanel_layout;
			$this->load_module_info();   //load module info from the module's bean file.
		}


	}
	
	function distinct_query() {
		if (isset($this->_instance_properties['get_distinct_data'])) {
			
			if (!empty($this->_instance_properties['get_distinct_data']))
				return true;
			else 
				return false;
		}
		return false;
	}
	
	//return the translated header value.
	function get_title() {
		return $this->mod_strings[$this->_instance_properties['title_key']];		
	}
	
	//return the definition of buttons. looks for buttons in 2 locations.
	function get_buttons() {
		$buttons=array();
		if (isset($this->_instance_properties['top_buttons'])) {
			//this will happen only in the case of sub-panels with multiple sources(activities).
			$buttons=$this->_instance_properties['top_buttons'];
		}
		else {
			$buttons=$this->panel_definition['top_buttons'];
		}

		// permissions. hide SubPanelTopComposeEmailButton from activities if email module is disabled.
		//only email is  being tested becuase other submodules in activites/history such as notes, tasks, meetings and calls cannot be disabled.
		//as of today these are the only 2 sub-panels that use the union clause. 
		$mod_name=$this->get_module_name();
		if ($mod_name=='Activities' or $mod_name='History') {
			global $modListHeader;
			global $modules_exempt_from_availability_check;
			if (!(array_key_exists('Emails',$modListHeader) or  array_key_exists('Emails',$modules_exempt_from_availability_check))) {
				foreach($buttons as $key=>$button) {
					foreach ($button as $property=>$value) { 
						if ($value == 'SubPanelTopComposeEmailButton' or $value=='SubPanelTopArchiveEmailButton') {
							//remove this button from the array.
							unset($buttons[$key]);
						}
					}	
				}				
			}
		}

		return $buttons;
	}

	//call this function for sub-panels that have unions.
	function load_sub_subpanels() {
		global $modListHeader;
		global $modules_exempt_from_availability_check;
		
		if (empty($this->sub_subpanels)) {
			$panels=$this->get_inst_prop_value('collection_list');
			foreach ($panels as $panel=>$properties) {								
				if (array_key_exists($properties['module'],$modListHeader) or  array_key_exists($properties['module'],$modules_exempt_from_availability_check)) {
					$this->sub_subpanels[$panel]=new aSubPanel($panel,$properties,$this->parent_bean);
				}
			}
		}
	}
	
	function isDatasourceFunction() {
		if (strpos($this->get_inst_prop_value('get_subpanel_data'), 'function') === false) {
			return false;
		}	
		return true;
	}	
	function isCollection() {
		if ($this->get_inst_prop_value('type')== 'collection') return true;
		else return false;	
	}
	
	//get value of a property defined at the panel instance level.
	function get_inst_prop_value($name) {
		if (isset($this->_instance_properties[$name]))
			return $this->_instance_properties[$name];
		else
			return null;	
	}
	//get value of a property defined at the panel definition level.
	function get_def_prop_value($name) {
		if (isset($this->panel_definition[$name])) { 
			return $this->panel_definition[$name];
		} else {
			return null;
		}	
	}
	
	//if datasource is of the type function then return the function name
	//else return the value as is.
	function get_function_parameters() {
		$parameters=array();
		if ($this->isDatasourceFunction()) {
			$parameters=$this->get_inst_prop_value('function_parameters');
		}
		return $parameters;
	} 

	function get_data_source_name($check_set_subpanel_data=false) {
		$prop_value=null;
		if ($check_set_subpanel_data) {
			$prop_value=$this->get_inst_prop_value('set_subpanel_data');
		}
		if (!empty($prop_value)) {
			return $prop_value;
		}
		else  {
			//fall back to default behavior.
		}
		if ($this->isDatasourceFunction()) {
			return (substr_replace($this->get_inst_prop_value('get_subpanel_data'),'',0,9));
		} else {
			return $this->get_inst_prop_value('get_subpanel_data');
		}
	} 

	//returns the where clause for the query.
	function get_where() {
		return $this->get_def_prop_value('where');
	}
	
	function is_fill_in_additional_fields(){
		return 	$this->get_inst_prop_value('fill_in_additional_fields');
	}
	
	function get_list_fields() {
		if (isset($this->panel_definition['list_fields'])) {
			return $this->panel_definition['list_fields'];
		} else {
			return array();
		}
	}
	
	function get_module_name() {
		return $this->get_inst_prop_value('module');			
	}
	
	//load subpanel mdoule's table name and column fields.
	function load_module_info() {
		global $beanList;
		global $beanFiles;
		
		$module_name=$this->get_module_name();
		if (!empty($module_name)) {
			
			$bean_name=$beanList[$this->get_module_name()];

			$this->bean_name=$bean_name;
		
			include_once ($beanFiles[$bean_name]);
			$this->template_instance=new $bean_name;
			$this->template_instance->force_load_details = true;
			$this->table_name=$this->template_instance->table_name;
			$this->db_fields=$this->template_instance->column_fields;
		}
	}
	//this function is to be used only with sub-panels that are based 
	//on collections.
	function get_header_panel_def() {
		if (!empty($this->sub_subpanels)) {
			if (!empty($this->_instance_properties['header_definition_from_subpanel']) && !empty($this->sub_subpanels[$this->_instance_properties['header_definition_from_subpanel']])) {
					return $this->sub_subpanels[$this->_instance_properties['header_definition_from_subpanel']];
			} else {
				reset($this->sub_subpanels);
				return current($this->sub_subpanels);
			}
		}
		return null;		
	}

	/**
	 * Returns an array of current properties of the class.
	 * It will simply give the class name for instances of classes.
	 */
	function _to_array()
	{
		return array(
			'_instance_properties' => $this->_instance_properties,
			'db_fields' => $this->db_fields,
			'mod_strings' => $this->mod_strings,
			'name' => $this->name,
			'panel_definition' => $this->panel_definition,
			'parent_bean' => get_class($this->parent_bean),
			'sub_subpanels' => $this->sub_subpanels,
			'table_name' => $this->table_name,
			'template_instance' => get_class($this->template_instance),
		);
	}
};


class SubPanelDefinitions {

	var $_focus;
	var $_visible_tabs_array;
	var $panels;
	var $layout_defs;

	/**
	 * Enter description here...
	 *
	 * @param BEAN $focus - this is the bean you want to get the data from
	 * @param STRING $layout_def_key - if you wish to use a layout_def defined in the default layout_defs.php that is not keyed off of $bean->module_dir pass in the key here
	 * @param ARRAY $layout_def_override - if you wish to override the default loaded layout defs you pass them in here.
	 * @return SubPanelDefinitions
	 */
	function SubPanelDefinitions($focus, $layout_def_key='', $layout_def_override='') {
		$this->_focus=$focus;
		if(!empty($layout_def_override)){
			$this->layout_defs = $layout_def_override;
			
		}else{
			$this->open_layout_defs(false, $layout_def_key);
		}
	}	

	/**
	 * This function returns an ordered list of the tabs.
	 */
	function get_available_tabs() {
		global $modListHeader;
		global $modules_exempt_from_availability_check;
		
		if (isset($this->_visible_tabs_array)) return $this->_visible_tabs_array;
	
		foreach ($this->layout_defs['subpanel_setup'] as $key=>$values_array) {
			//check permissions.
			if(array_key_exists($values_array['module'], $modules_exempt_from_availability_check) 
				or array_key_exists($values_array['module'], $modListHeader) 
				&& 
				(!ACLController::moduleSupportsACL($values_array['module']) || ACLController::checkAccess($values_array['module'],'list', true) )) {
				$this->_visible_tabs_array[$values_array['order']]=$key;
			} 
		}
		ksort($this->_visible_tabs_array);
		return $this->_visible_tabs_array;
	}
	
	/**
	 * Load the definition of the a sub-panel.
	 * Also the sub-panel is added to an array of sub-panels.
	 * use of reload has been deprecated, since the subpanel is initialized every time.
	 */
	function load_subpanel($name, $reload=false) {
		$panel=new aSubPanel($name,$this->layout_defs['subpanel_setup'][strtolower($name)],$this->_focus, $reload);
		return $panel;
	}
	
	/**
	 * Load the layout def file and associate the definition with a variable in the file.
	 */
	function open_layout_defs($reload=false, $layout_def_key='') {
	$layout_defs[$this->_focus->module_dir] = array();
	$layout_defs[$layout_def_key] = array();
		if (empty($this->layout_defs) || $reload ||( !empty($layout_def_key) && !isset($layout_defs[$layout_def_key])) ) {
			if( file_exists('modules/'.$this->_focus->module_dir.'/layout_defs.php')){
				require('modules/'.$this->_focus->module_dir.'/layout_defs.php');
			}
			if(file_exists('custom/modules/'.$this->_focus->module_dir . '/Ext/Layoutdefs/layoutdefs.ext.php')){
  				
  				require('custom/modules/'.$this->_focus->module_dir . '/Ext/Layoutdefs/layoutdefs.ext.php');
  				
  			}

  			if(!empty($layout_def_key)){
  				$this->layout_defs=$layout_defs[$layout_def_key];
  			}else{
  				$this->layout_defs=$layout_defs[$this->_focus->module_dir];
  			}
			
		
		}
	}
	
	/**
	 * Removes a tab from the list of loaded tabs.
	 * Returns true if successful, false otherwise.
	 * Hint: Used by Campaign's DetailView.
	 */
	function exclude_tab($tab_name)
	{
		$result = false;
		//unset layout definition
		if(!empty($this->layout_defs['subpanel_setup'][$tab_name]))
		{
			unset($this->layout_defs['subpanel_setup'][$tab_name]);
		}
		//unset instance from _visible_tab_array
		if (!empty($this->_visible_tabs_array)) {
			$key=array_search($tab_name,$this->_visible_tabs_array);
			if ($key !== false) {
				unset($this->_visible_tabs_array[$key]);				
			}
		}
		return $result;
	}
}
?>
