<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Generic sub-panel class
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

// $Id: SubPanel.php,v 1.46 2006/08/09 21:34:37 liliya Exp $

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('include/ListView/ListView.php');
require_once('include/dir_inc.php');
require_once('include/utils/file_utils.php');
require_once('include/SubPanel/registered_layout_defs.php');

//AG todo remove subpanedata and subpanel id  from this class...
class SubPanel
{
	var $hideNewButton = false;
	var $subpanel_id;
	var $parent_record_id;
	var $parent_module;  // the name of the parent module
	var $parent_bean;  // the instantiated bean of the parent
	var $template_file;
	var $linked_fields;
	var $action = 'DetailView';
	var $show_select_button = true;
	var $subpanel_define = null;  // contains the layout_def.php
	var $subpane_defs; 
	var $subpanel_query=null;
	function SubPanel($module, $record_id, $subpanel_id, $subpanelDef, $layout_def_key='')
	{
		global $theme, $beanList, $beanFiles, $focus, $app_strings;
	
		$this->subpanel_defs=$subpanelDef;
		$this->subpanel_id = $subpanel_id;
		$this->parent_record_id = $record_id; 
		$this->parent_module = $module; 

		$this->parent_bean = $focus;
		$result = $focus;

		if(empty($result))
		{
			$parent_bean_name = $beanList[$module];
			$parent_bean_file = $beanFiles[$parent_bean_name];
			require_once($parent_bean_file);
			$this->parent_bean = new $parent_bean_name();
			$result = $this->parent_bean->retrieve($this->parent_record_id);
		}
		
		if($record_id!='fab4' && $result == null)
		{
			sugar_die($app_strings['ERROR_NO_RECORD']);
		}

		if (empty($subpanelDef)) {
			//load the subpanel by name.
			if (!class_exists('MyClass')) {
				require('include/SubPanel/SubPanelDefinitions.php');
			}
			$panelsdef=new SubPanelDefinitions($result,$layout_def_key);
			$subpanelDef=$panelsdef->load_subpanel($subpanel_id);
			$this->subpanel_defs=$subpanelDef;
			
		}
		
	}

	function setTemplateFile($template_file)
	{
		$this->template_file = $template_file;
	}
	
	function setBeanList(&$value){
		$this->bean_list =$value;
	}
	
	function setHideNewButton($value){
		$this->hideNewButton = $value;
	}
	
	
	function getHeaderText( $currentModule){
	}
	
	function get_buttons( $panel_query=null)
	{
		$thisPanel =& $this->subpanel_defs;
		$subpanel_def = $thisPanel->get_buttons();
		if(!isset($this->listview)){
			$this->listview = new ListView();
		}
		$layout_manager = $this->listview->getLayoutManager();
		$widget_contents = '<div class="listViewButtons"><table cellpadding="0" cellspacing="0"><tr>';
		foreach($subpanel_def as $widget_data)
		{
			$widget_data['query']=urlencode($panel_query);
			$widget_data['action'] = $_REQUEST['action'];
			$widget_data['module'] =  $thisPanel->get_inst_prop_value('module');
			$widget_data['focus'] = $this->parent_bean;
			$widget_data['subpanel_definition'] = $thisPanel;
			$widget_contents .= '<td style="padding-right: 2px; padding-bottom: 2px;">' . "\n";

			if(empty($widget_data['widget_class']))
			{
				$widget_contents .= "widget_class not defined for top subpanel buttons";
			}
			else
			{
				$widget_contents .= $layout_manager->widgetDisplay($widget_data);
			}
			
			$widget_contents .= '</td>';
		}
		
		$widget_contents .= '</tr></table></div>';
		return $widget_contents;
	}


	function ProcessSubPanelListView($xTemplatePath, &$mod_strings)
	{
		global $app_strings;
		global $image_path;
		global $current_user;
		global $sugar_config;
		
		if(isset($this->listview)){
			$ListView =& $this->listview;	
		}else{
			$ListView = new ListView();
		}
		$ListView->initNewXTemplate($xTemplatePath,$this->subpanel_defs->mod_strings);
		$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$this->parent_module."&return_action=DetailView&return_id=".$this->parent_bean->id);
		$ListView->xTemplateAssign("RELATED_MODULE", $this->parent_module);  // TODO: what about unions?
		$ListView->xTemplateAssign("RECORD_ID", $this->parent_bean->id);
		$ListView->xTemplateAssign("EDIT_INLINE_PNG", get_image($image_path.'edit_inline','align="absmiddle" alt="'.$app_strings['LNK_EDIT'].'" border="0"'));
		$ListView->xTemplateAssign("DELETE_INLINE_PNG", get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_DELETE'].'" border="0"'));
		$ListView->xTemplateAssign("REMOVE_INLINE_PNG", get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_REMOVE'].'" border="0"'));
		$header_text= '';
	
		if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace']))
		{	
			$exploded = explode('/', $xTemplatePath);
			$file_name = $exploded[sizeof($exploded) - 1];
			$mod_name =  $exploded[sizeof($exploded) - 2];
			$header_text= "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=$file_name&from_module=$mod_name&mod_lang="
				.$_REQUEST['module']."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
		}
		$ListView->setHeaderTitle('');
		$ListView->setHeaderText('');
		
		ob_start();

		$ListView->is_dynamic = true;
		$ListView->records_per_page = $sugar_config['list_max_entries_per_subpanel'] + 0;
		$ListView->start_link_wrapper = "javascript:showSubPanel('".$this->subpanel_id."','";
		$ListView->subpanel_id = $this->subpanel_id;
		$ListView->end_link_wrapper = "',true);";

		$where = '';
		$ListView->setQuery($where, '', '', '');
		$ListView->show_export_button = false;
		
		//function returns the query that was used to populate sub-panel data.
		$query=$ListView->process_dynamic_listview($this->parent_module, $this->parent_bean,$this->subpanel_defs);
		$this->subpanel_query=$query; 
		$ob_contents = ob_get_contents();
		ob_end_clean();
		return $ob_contents;
	}

	function display()
	{	





		global $timedate;
		global $mod_strings;
		global $app_strings;
		global $app_list_strings;
		global $gridline,$theme;
		global $beanList;
		global $beanFiles;
		global $current_language;                                                                                            
		require_once('themes/'.$theme.'/layout_utils.php');
		$image_path = 'themes/'.$theme.'/images/';

		$result_array = array();
		
		$return_string = $this->ProcessSubPanelListView($this->template_file,$result_array);

		print $return_string;












	}
       
	function getModulesWithSubpanels()
	{
		global $beanList;
		$dir = dir('modules');
		$modules = array();
		while($entry = $dir->read())
		{
			if(file_exists('modules/' . $entry . '/layout_defs.php'))
			{
				$modules[$entry] = $entry;	
			}	
		}
		return $modules;
	}
  
  function getModuleSubpanels($module){
  	require_once('include/SubPanel/SubPanelDefinitions.php');
  		global $beanList, $beanFiles;
  		if(!isset($beanList[$module])){
  			return array();
  		}
  			
  		$class = $beanList[$module];
  		require_once($beanFiles[$class]);
  		$mod = new $class();
  		$spd = new SubPanelDefinitions($mod);
  		$tabs = $spd->get_available_tabs();
  		$ret_tabs = array();
  		$reject_tabs = array('history'=>1, 'activities'=>1);
  		foreach($tabs as $key=>$tab){
  				if(!isset($reject_tabs[$tab])){
  					$ret_tabs[$tab] = $tab;
  				}
  		}
 
  		return $ret_tabs;
  
  		
  }
  
  //saves overrides for defs
  function saveSubPanelDefOverride( $panel, $subsection, $override){
  		global $layout_defs, $beanList;
  		
  		//save the new subpanel
  		$name = "subpanel_layout['list_fields']";
  		$path = 'custom/modules/'. $panel->_instance_properties['module'] . '/subpanels';
  		$filename = $panel->parent_bean->object_name . $panel->_instance_properties['subpanel_name'] ;
  		$extname = $panel->parent_bean->object_name .$panel->_instance_properties['module'] . $panel->_instance_properties['subpanel_name'] ;
  		mkdir_recursive('custom/modules/'. $panel->_instance_properties['module'] . '/subpanels', true);
  		write_array_to_file( $name, $override,$path.'/' . $filename .'.php');
  		
  		//save the override for the layoutdef
  		$name = "layout_defs['".  $panel->parent_bean->module_dir. "']['subpanel_setup']['" .$panel->name. "']";
  		$newValue = override_value_to_string($name, 'override_subpanel_name', $filename);
  		
  		mkdir_recursive('custom/Extension/modules/'. $panel->parent_bean->module_dir . '/Ext/Layoutdefs', true);
  		$fp = fopen('custom/Extension/modules/'. $panel->parent_bean->module_dir . "/Ext/Layoutdefs/$extname.php", 'w');
  		fwrite($fp, "<?php\n//auto-generated file DO NOT EDIT\n$newValue\n?>");
  		fclose($fp);
  		require_once('ModuleInstall/ModuleInstaller.php');
  		$moduleInstaller = new ModuleInstaller();
  		$moduleInstaller->rebuild_layoutdefs();
  		include('modules/'.  $panel->parent_bean->module_dir . '/layout_defs.php');	
  		include('custom/modules/'.  $panel->parent_bean->module_dir . '/Ext/Layoutdefs/layoutdefs.ext.php');	
  		
  }
  
	function get_subpanel_setup($module)
	{
		$subpanel_setup = '';
		$layout_defs = get_layout_defs();
		
		if(!empty($layout_defs) && !empty($layout_defs[$module]['subpanel_setup']))
      {
      	$subpanel_setup = $layout_defs[$module]['subpanel_setup'];
      }
      
      return $subpanel_setup;
	}
  
	/**
	 * Retrieve the subpanel definition from the registered layout_defs arrays.
	 */
	function getSubPanelDefine($module, $subpanel_id)
	{
		$default_subpanel_define = SubPanel::_get_default_subpanel_define($module, $subpanel_id);
		$custom_subpanel_define = SubPanel::_get_custom_subpanel_define($module, $subpanel_id);

		$subpanel_define = array_merge($default_subpanel_define, $custom_subpanel_define);	
		
		if(empty($subpanel_define))
		{
			print('Could not load subpanel definition for: ' . $subpanel_id);
		}
		     
		return $subpanel_define;
	}

	function _get_custom_subpanel_define($module, $subpanel_id)
	{
		$ret_val = array();
		
		if($subpanel_id != '')
		{
			$layout_defs = get_layout_defs();
			
			if(!empty($layout_defs[$module]['custom_subpanel_defines'][$subpanel_id]))
			{
				$ret_val = $layout_defs[$module]['custom_subpanel_defines'][$subpanel_id];
			}
		}
		
		return $ret_val;
	}

	function _get_default_subpanel_define($module, $subpanel_id)
	{
		$ret_val = array();
		
		if($subpanel_id != '')
		{
	  		$layout_defs = get_layout_defs();

			if(!empty($layout_defs[$subpanel_id]['default_subpanel_define']))
			{
				$ret_val = $layout_defs[$subpanel_id]['default_subpanel_define'];
			}
		}
		
		return $ret_val;
	}
}
?>
