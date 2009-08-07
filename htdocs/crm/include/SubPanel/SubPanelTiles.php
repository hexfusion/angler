<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SubPanelTiles
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
// $Id: SubPanelTiles.php,v 1.76 2006/08/23 01:23:52 jbenterou Exp $

require_once('include/SubPanel/SubPanel.php');
require_once('include/SubPanel/SubPanelTilesTabs.php');
require_once('include/SubPanel/SubPanelDefinitions.php');

class SubPanelTiles
{
	var $id;
	var $module;
	var $focus;
	var $start_on_field;
	var $layout_manager;
	var $layout_def_key;
	var $show_tabs = false;

	var $subpanel_definitions;
	
	var $hidden_tabs=array(); //consumer of this class can array of tabs that should be hidden. the tab name
							//should be the array.
	
	function SubPanelTiles(&$focus, $layout_def_key='', $layout_def_override = '')
	{
		$this->focus = $focus;
		$this->id = $focus->id;
		$this->module = $focus->module_dir;
		$this->layout_def_key = $layout_def_key;
		$this->subpanel_definitions=new SubPanelDefinitions($focus, $layout_def_key, $layout_def_override);
	}
    
    function getSelectedGroup()
    {
        global $current_user;
        
        if(isset($_REQUEST['subpanelTabs'])){
            $_SESSION['subpanelTabs'] = $_REQUEST['subpanelTabs'];
        }
        
        require_once('include/tabConfig.php');
        
        $subpanelTabsPref = $current_user->getPreference('subpanel_tabs');
        if(!isset($subpanelTabsPref)) $subpanelTabsPref = $GLOBALS['sugar_config']['default_subpanel_tabs'];
        
        if(!empty($GLOBALS['tabStructure']) && (!empty($_SESSION['subpanelTabs']) || !empty($sugar_config['subpanelTabs']) || !empty($subpanelTabsPref)))
        {
            // Determine selected group
            if(!empty($_REQUEST['subpanel']))
            {
                $selected_group = $_REQUEST['subpanel'];
            }
            elseif(!empty($_COOKIE[$this->module.'_sp_tab']))
            {
                $selected_group = $_COOKIE[$this->module.'_sp_tab'];
            }
            elseif(!empty($_SESSION['parentTab']) && !empty($GLOBALS['tabStructure'][$_SESSION['parentTab']]) && in_array($this->module, $GLOBALS['tabStructure'][$_SESSION['parentTab']]['modules']))
            {
                $selected_group = $_SESSION['parentTab'];
            }
            else
            {
                $selected_group = '';
                foreach($GLOBALS['tabStructure'] as $mainTab => $group)
                {
                    if(in_array($this->module, $group['modules']))
                    {
                        $selected_group = $mainTab;
                        break;
                    }
                }
                if(!$selected_group)
                {
                    $selected_group = 'All';
                }
            }
        }
        else
        {
        	$selected_group = '';
        }
        return $selected_group;
    }

    function getTabs($showTabs = true, $selectedGroup='')
    {
        global $theme, $current_user;
        
        //get all the tabs
        $tabs = $this->subpanel_definitions->get_available_tabs();
        
        if(!empty($selectedGroup))
        {
	        return SubPanelTilesTabs::getTabs($tabs, $showTabs, $selectedGroup);
	    }
        else
        {
            // see if user current user has custom subpanel layout
            $tabs = SubPanelTilesTabs::applyUserCustomLayoutToTabs($tabs);
            
            /* Check if the preference is set now,
             * because there's no point in executing this code if
             * we aren't going to render anything. 
             */
            $subpanelLinksPref = $current_user->getPreference('subpanel_links');
            if(!isset($subpanelLinksPref)) $subpanelLinksPref = $GLOBALS['sugar_config']['default_subpanel_links'];
            
            if($showTabs && $subpanelLinksPref){
               require_once('include/SubPanel/SugarTab.php');
               $sugarTab = new SugarTab();
               
               $displayTabs = array();
               
               foreach($tabs as $tab){
    	           $displayTabs []= array('key'=>$tab, 'label'=>translate($this->subpanel_definitions->layout_defs['subpanel_setup'][$tab]['title_key']));
    	           //echo '<td nowrap="nowrap"><a class="subTabLink" href="#' . $tab . '">' .  translate($this->subpanel_definitions->layout_defs['subpanel_setup'][$tab]['title_key']) .  '</a></td><td> | </td>';
    	       }
               $sugarTab->setup(array(),array(),$displayTabs);
               $sugarTab->display();
            }
            //echo '<td width="100%">&nbsp;</td></tr></table>';
        }
	    return $tabs;
	    
	}
	function display($showContainer = true, $forceTabless = false)
	{
		global $layout_edit_mode, $sugar_version, $sugar_config, $current_user, $app_strings;
		if(isset($layout_edit_mode) && $layout_edit_mode){
			return;	
		}

		global $modListHeader;
		
		ob_start();
    echo '<script type="text/javascript" src="include/javascript/popup_parent_helper.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>';
    echo '<script type="text/javascript" src="include/SubPanel/SubPanelTiles.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>';
?>
<script>
if(document.DetailView != null && 
   document.DetailView.elements != null && 
   document.DetailView.elements.layout_def_key != null && 
   typeof document.DetailView.elements['layout_def_key'] != 'undefined'){
    document.DetailView.elements['layout_def_key'].value = '<?php echo $this->layout_def_key; ?>';
}
</script>
<?php 
		
		$tabs = array();
		$default_div_display = 'inline';
		if(!empty($sugar_config['hide_subpanels_on_login'])){
			if(!isset($_SESSION['visited_details'][$this->focus->module_dir])){
				setcookie($this->focus->module_dir . '_divs', '');
				unset($_COOKIE[$this->focus->module_dir . '_divs']);
				$_SESSION['visited_details'][$this->focus->module_dir] = true;
				
			}
			$default_div_display = 'none';
		}
		$div_cookies = get_sub_cookies($this->focus->module_dir . '_divs');
        
        
		//Display the group header. this section is executed only if the tabbed interface is being used.
		$current_key = '';
		if (! empty($this->show_tabs))
		{
			require_once('include/tabs.php');
    		$tab_panel = new SugarWidgetTabs($tabs, $current_key, 'showSubPanel');
			echo get_form_header('Related', '', false);
			echo "<br />" . $tab_panel->display();
		}
		
        if(empty($_REQUEST['subpanels']))
        {
            $selected_group = $forceTabless?'':$this->getSelectedGroup();
            
            $tabs = $this->getTabs($showContainer, $selected_group);
        }
        else
        {
        	$tabs = explode(',', $_REQUEST['subpanels']);
        }
        
        $tab_names = array();
        
        if($showContainer)
        {
            echo '<ul class="noBullet" id="subpanel_list">';
        }
        //echo "<li id='hidden_0' style='height: 5px' class='noBullet'>&nbsp;&nbsp;&nbsp;</li>";
		foreach ($tabs as $tab) 
		{	 
			echo '<li class="noBullet" id="whole_subpanel_' . $tab . '">';
			//load meta definition of the sub-panel.
			$thisPanel=$this->subpanel_definitions->load_subpanel($tab);
			
			$display= 'none';
			$div_display = $default_div_display;
			$cookie_name =   $tab . '_v';
			
			if(isset($div_cookies[$cookie_name])){
				$div_display = 	$div_cookies[$cookie_name];	
			}
			if(!empty($sugar_config['hide_subpanels'])){
				$div_display = 'none';
			}
			if($div_display == 'none'){
				$opp_display  = 'inline';
			}else{
				$opp_display  = 'none';
			}
			
			if (empty($this->show_tabs))
			{
				global $theme;
				$theme_path = "themes/" . $theme . "/";
				$image_path = $theme_path . "images/";
				$show_icon_html = get_image($image_path . 'advanced_search', 'alt="' . translate('LBL_SHOW') . '" border="0 align="absmiddle""');
				$hide_icon_html = get_image($image_path . 'basic_search', 'alt="' . translate('LBL_HIDE') . '" border="0" align="absmiddle"');
 		 		$max_min = "<a name=\"$tab\"> </a><span id=\"show_link_".$tab."\" style=\"display: $opp_display\"><a href='#' class='utilsLink' onclick=\"current_child_field = '".$tab."';showSubPanel('".$tab."');document.getElementById('show_link_".$tab."').style.display='none';document.getElementById('hide_link_".$tab."').style.display='';return false;\">"
 		 			. "" . $show_icon_html . "</a></span>";
				$max_min .= "<span id=\"hide_link_".$tab."\" style=\"display: $div_display\"><a href='#' class='utilsLink' onclick=\"hideSubPanel('".$tab."');document.getElementById('hide_link_".$tab."').style.display='none';document.getElementById('show_link_".$tab."').style.display='';return false;\">"
				 . "" . $hide_icon_html . "</a></span>";
				echo '<div id="subpanel_title_' . $tab . '"';
                if(empty($sugar_config['lock_subpanels']) || $sugar_config['lock_subpanels'] == false) echo ' onmouseover="this.style.cursor = \'move\';"';
                echo '>' . get_form_header( $thisPanel->get_title(), $max_min, false) . '</div>';
			}
 	
            echo <<<EOQ
<div cookie_name="$cookie_name" id="subpanel_$tab" style="display:$div_display">
    <script>document.getElementById("subpanel_$tab" ).cookie_name="$cookie_name";</script>
EOQ;
            $display_spd = '';
            if($div_display != 'none'){
            	echo "<script>markSubPanelLoaded('$tab');</script>";
            	$old_contents = ob_get_contents();
            	@ob_end_clean();
            	
            	ob_start();
            	include_once('include/SubPanel/SubPanel.php');
            	$subpanel_object = new SubPanel($this->module, $_REQUEST['record'], $tab,$thisPanel);
            	$subpanel_object->setTemplateFile('include/SubPanel/SubPanelDynamic.html');
            	$subpanel_object->display();
            	$subpanel_data = ob_get_contents();
            	@ob_end_clean();
            	
            	ob_start();
            	echo $this->get_buttons($thisPanel,$subpanel_object->subpanel_query); 
            	$buttons = ob_get_contents();
            	@ob_end_clean();
            
            	ob_start();
            	echo $old_contents;
            	echo $buttons;
                $display_spd = $subpanel_data;
            }
            echo <<<EOQ
    <div id="list_subpanel_$tab">$display_spd</div>
</div>
EOQ;
        	array_push($tab_names, $tab);
        	echo '</li>';
        } // end $tabs foreach
        echo '<li style="height: 5px" class="noBullet">&nbsp;&nbsp;&nbsp;</li>';
        if($showContainer)
        {
        	echo '</ul>';
            
            
            if(!empty($selected_group))
            {
                // closing table from tpls/singletabmenu.tpl
                echo '</td></tr></table>';
            }
        }
        // drag/drop code
        $tab_names = '["' . join($tab_names, '","') . '"]';
        global $sugar_config;
    
        if(empty($sugar_config['lock_subpanels']) || $sugar_config['lock_subpanels'] == false) {    
            echo <<<EOQ
    <script>
    	var SubpanelInit = function() {
    		SubpanelInitTabNames({$tab_names});
    	}
        var SubpanelInitTabNames = function(tabNames) {
    		subpanel_dd = new Array();
    		j = 0;
    		for(i in tabNames) {
    			subpanel_dd[j] = new ygDDList('whole_subpanel_' + tabNames[i]);
    			subpanel_dd[j].setHandleElId('subpanel_title_' + tabNames[i]);
    			subpanel_dd[j].onMouseDown = SUGAR.subpanelUtils.onDrag;  
    			subpanel_dd[j].afterEndDrag = SUGAR.subpanelUtils.onDrop;
    			j++;
    		}
    	
    		YAHOO.util.DDM.mode = 1;
    	}
    	currentModule = '{$this->module}'; 
    	YAHOO.util.Event.addListener(window, 'load', SubpanelInit);
    </script>  
EOQ;
        }
        
		$ob_contents = ob_get_contents();
		ob_end_clean();
		return $ob_contents;
	}


	function getLayoutManager()
	{
		require_once('include/generic/LayoutManager.php');
	  	if ( $this->layout_manager == null) {
	    	$this->layout_manager = new LayoutManager();
	  	}
	  	return $this->layout_manager;
	}

	function get_buttons($thisPanel,$panel_query=null)
	{
		$subpanel_def = $thisPanel->get_buttons();
		$layout_manager = $this->getLayoutManager();
		$widget_contents = '<div class="listViewButtons"><table cellpadding="0" cellspacing="0"><tr>';
		foreach($subpanel_def as $widget_data)
		{
			$widget_data['query']=urlencode($panel_query);
			$widget_data['action'] = $_REQUEST['action'];
			$widget_data['module'] =  $thisPanel->get_inst_prop_value('module');
			$widget_data['focus'] = $this->focus;
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
}
?>
