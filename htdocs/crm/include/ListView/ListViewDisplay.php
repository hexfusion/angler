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

// $Id: ListViewDisplay.php,v 1.25 2006/08/22 17:33:19 awu Exp $

require_once('include/ListView/ListViewData.php');
require_once('include/MassUpdate.php');

class ListViewDisplay {
    
	var $show_mass_update_form = false;
	var $rowCount;
	var $mass = null;
	var $seed;
	var $multi_select_popup;
	var $lvd;
	var $moduleString;
	var $export = true;
	var $multiSelect = true;
	var $mailMerge = true;
	var $should_process = true;
	/**
	 * Constructor
	 * @return null
	 */
	function ListViewDisplay() {
		$this->lvd = new ListViewData();
	}
	function shouldProcess($moduleDir){
		if(!empty($GLOBALS['sugar_config']['save_query']) && $GLOBALS['sugar_config']['save_query'] == 'populate_only'){
			if(empty($GLOBALS['displayListView']) && (!empty($_REQUEST['clear_query']) || $_REQUEST['module'] == $moduleDir && ((empty($_REQUEST['query']) || $_REQUEST['query'] == 'MSI' )&& (empty($_SESSION['last_search_mod']) || $_SESSION['last_search_mod'] != $moduleDir ) ))){
				$_SESSION['last_search_mod'] = $_REQUEST['module'] ;
				$this->should_process = false;
				return false;
			}
		}	
		$this->should_process = true;
		return true;
	}
	
	/**
	 * Setup the class 
	 * @param seed SugarBean Seed SugarBean to use
	 * @param file File Template file to use
	 * @param string $where
	 * @param offset:0 int offset to start at
	 * @param int:-1 $limit
	 * @param string[]:array() $filter_fields
	 * @param array:array() $params
	 * 	Potential $params are 
		$params['distinct'] = use distinct key word
		$params['include_custom_fields'] = (on by default)
		$params['massupdate'] = true by default; 
	 * @param string:'id' $id_field
	 */	
	function setup($seed, $file, $where, $params = array(), $offset = 0, $limit = -1,  $filter_fields = array(), $id_field = 'id') {
        $this->should_process = true;
        if(isset($seed->module_dir) && !$this->shouldProcess($seed->module_dir)){
        		return false;
        }
        if(!empty($params['export'])) {
          $this->export = $params['export'];
        }
        if(!empty($params['multiSelectPopup'])) {
		  $this->multi_select_popup = $params['multiSelectPopup'];
        }
		if(!empty($params['massupdate']) && $params['massupdate'] != false) {
			$this->show_mass_update_form = true;
			$this->mass = new MassUpdate();
			$this->mass->setSugarBean($seed);
			$this->mass->handleMassUpdate();
		}
		$this->seed = $seed;
        
        // create filter fields based off of display columns
        if(empty($filter_fields)) {
            foreach($this->displayColumns as $columnName => $def) {
                $filter_fields[strtolower($columnName)] = true;
                if(!empty($def['related_fields'])) {
                    foreach($def['related_fields'] as $field) {
                        $filter_fields[$field] = true;
                    }
                }
              
            }
        }
        $data = $this->lvd->getListViewData($seed, $where, $offset, $limit, $filter_fields, $params, $id_field);
         foreach($this->displayColumns as $columnName => $def) {
         $seedName =  strtolower($columnName);
         
         
               if(empty($this->displayColumns[$columnName]['type'])){
              
	               if(!empty($this->lvd->seed->field_defs[$seedName]['type'])){
	               		$seedDef = $this->lvd->seed->field_defs[$seedName];
	                	$this->displayColumns[$columnName]['type'] = (!empty($seedDef['custom_type']))?$seedDef['custom_type']:$seedDef['type'];
	               }else{
	               		$this->displayColumns[$columnName]['type'] = '';
	               }
               }
         }
        	
		
		$this->process($file, $data, $seed->object_name);
		return true;
	}

	/**
	 * Any additional processing
	 * @param file File template file to use
	 * @param data array row data
	 * @param html_var string html string to be passed back and forth
	 */	
	function process($file, $data, $htmlVar) {
		$this->rowCount = count($data['data']);
		$this->moduleString = $data['pageData']['bean']['moduleDir'] . '2_' . strtoupper($htmlVar) . '_offset';
	}

	/**
	 * Display the listview
	 * @return string ListView contents 
	 */	
	function display() {
		if(!$this->should_process) return '';
		$str = '';
		if($this->multiSelect == true && $this->show_mass_update_form)
			$str = $this->mass->getDisplayMassUpdateForm(true, $this->multi_select_popup).$this->mass->getMassUpdateFormHeader($this->multi_select_popup);
		
        return $str;
	}

	/**
	 * Display the export link
     * @return string export link html
	 * @param echo Bool set true if you want it echo'd, set false to have contents returned 
	 */	
	function buildExportLink($id = 'export_link') {
		global $app_strings, $image_path; 
		$script = "<script> 
			function export_overlib() {
				return overlib('<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' onclick=\'return sListView.send_form(true, \"{$this->seed->module_dir}\", \"export.php\", \"{$app_strings['LBL_LISTVIEW_NO_SELECTED']}\")\' href=\'#\'>{$app_strings['LBL_LISTVIEW_OPTION_SELECTED']}</a>"  
			. "<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' onclick=\'return sListView.send_form(false, \"{$_REQUEST['module']}\", \"export.php\", \"{$app_strings['LBL_LISTVIEW_NO_SELECTED']}\")\' href=\'#\'>{$app_strings['LBL_LISTVIEW_OPTION_CURRENT']}</a>"
			. "<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' href=\'export.php?module={$this->seed->module_dir}\'>{$app_strings['LBL_LISTVIEW_OPTION_ENTIRE']}</a>"			
			. "', CAPTION, '" . $app_strings['LBL_EXPORT']
			. "', STICKY, MOUSEOFF, 3000, CLOSETEXT, '<img border=0 src=" . $image_path 
			. "close_inline.gif>', WIDTH, 150, CLOSETITLE, '" . $app_strings['LBL_ADDITIONAL_DETAILS_CLOSE_TITLE'] . "', CLOSECLICK, FGCLASS, 'olOptionsFgClass', "
			. "CGCLASS, 'olOptionsCgClass', BGCLASS, 'olBgClass', TEXTFONTCLASS, 'olFontClass', CAPTIONFONTCLASS, 'olOptionsCapFontClass', CLOSEFONTCLASS, 'olOptionsCloseFontClass');
			}
			</script>";
		$script .= "<a id='$id' onclick='return export_overlib();' href=\"#\" class=\"listViewPaginationLinkS1\">".get_image($image_path."export","alt='".$app_strings['LBL_EXPORT']."'  border='0' align='absmiddle'")."&nbsp;".$app_strings['LBL_EXPORT']."</a>";
		return $script;
	}
	
	/**
	 * Display the selected object span object
	 * 
     * @return string select object span 
	 */	
	function buildSelectedObjectsSpan($echo = true) {
		global $app_strings;
		
		$selectedObjectSpan = "&nbsp;|&nbsp;{$app_strings['LBL_LISTVIEW_SELECTED_OBJECTS']}<input class='listViewPaginationTdS1' style='border: 0px; background: transparent; font-size: inherit; color: inherit' type='text' readonly name='selectCount[]' value='0' />"; 
		
        return $selectedObjectSpan;						
	}
	
    /**
     * Display merge duplicate links. The link can be disabled by setting module level duplicate_merge property to false
     * in the moudle's vardef file.
     */
     function buildMergeDuplicatesLink() {
        global $app_strings, $dictionary;
        $return_string='';
        $return_string.= isset($_REQUEST['module']) ? "&return_module={$_REQUEST['module']}" : "";
        $return_string.= isset($_REQUEST['action']) ? "&return_action={$_REQUEST['action']}" : "";
        $return_string.= isset($_REQUEST['record']) ? "&return_id={$_REQUEST['record']}" : "";

        if (isset($dictionary[$this->seed->object_name]['duplicate_merge']) && $dictionary[$this->seed->object_name]['duplicate_merge']==true ) {
            return ($str = "&nbsp;|&nbsp;<a id='mergeduplicates_link' onclick='if (sugarListView.get_checks_count()> 1) {sListView.send_form(true, \"MergeRecords\", \"index.php\", \"{$app_strings['LBL_LISTVIEW_NO_SELECTED']}\", \"{$this->seed->module_dir}\",\"$return_string\");} else {alert(\"{$app_strings['LBL_LISTVIEW_TWO_REQUIRED']}\");return false;}' href=\"#\" class=\"listViewPaginationLinkS1\">".$app_strings['LBL_MERGE_DUPLICATES']."</a>");
        } else return '';

     }
	/**
	 * Display the mail merge link
	 * @param echo Bool set true if you want it echo'd, set false to have contents returned 
	 */	
	function buildMergeLink() {
        require_once("modules/Administration/Administration.php");
        global $current_user, $app_strings, $image_path;
        
        $admin = new Administration();
        $admin->retrieveSettings('system');
        $user_merge = $current_user->getPreference('mailmerge_on');
        
        $str = '';
        if ($user_merge == 'on' && isset($admin->settings['system_mailmerge_on']) && $admin->settings['system_mailmerge_on']) {
            $str = "<script>
                function mailmerge_overlib() {
                    return overlib('<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' onclick=\'return sListView.send_form(true, \"MailMerge\", \"index.php\", \"{$app_strings['LBL_LISTVIEW_NO_SELECTED']}\")\' href=\'#\'>{$app_strings['LBL_LISTVIEW_OPTION_SELECTED']}</a>"  
                        . "<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' onclick=\'return sListView.send_form(false, \"MailMerge\", \"index.php\", \"{$app_strings['LBL_LISTVIEW_NO_SELECTED']}\")\' href=\'#\'>{$app_strings['LBL_LISTVIEW_OPTION_CURRENT']}</a>"
                        . "<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,\"yes\");\' onmouseout=\'unhiliteItem(this);\' href=\'index.php?action=index&module=MailMerge&entire=true\'>{$app_strings['LBL_LISTVIEW_OPTION_ENTIRE']}</a>"         
                        . "', CAPTION, '" . $app_strings['LBL_MAILMERGE']
                        . "', STICKY, MOUSEOFF, 3000, CLOSETEXT, '<img border=0 src=" . $image_path 
                        . "close_inline.gif>', WIDTH, 150, CLOSETITLE, '" . $app_strings['LBL_ADDITIONAL_DETAILS_CLOSE_TITLE'] . "', CLOSECLICK, FGCLASS, 'olOptionsFgClass', "
                        . "CGCLASS, 'olOptionsCgClass', BGCLASS, 'olBgClass', TEXTFONTCLASS, 'olFontClass', CAPTIONFONTCLASS, 'olOptionsCapFontClass', CLOSEFONTCLASS, 'olCloseFontClass');
                }               
            </script>";
            $str .= "&nbsp;|&nbsp;<a id='mailmerge_link' onclick='return mailmerge_overlib()'; href=\"#\" class=\"listViewPaginationLinkS1\">".$app_strings['LBL_MAILMERGE']."</a>";
        }
        
        return $str;	
	}

	/**
	 * Display the bottom of the ListView (ie MassUpdate
	 * @return string contents 
	 */	
	function displayEnd() {
		$str = '';
		if($this->show_mass_update_form) {
			$str .= $this->mass->getMassUpdateForm();
			$str .= $this->mass->endMassUpdateForm();
		}
		
		return $str;
	}
	
    /**
     * Display the multi select data box etc.
     * @return string contents 
     */
	function getMultiSelectData() {
		$str = '<script>YAHOO.util.Event.addListener(window, "load", sListView.check_boxes);</script>';				
		
        if(!empty($_REQUEST['uid']) && (!empty($_REQUEST['massupdate']) && $_REQUEST['massupdate'] == 'false')) {
            $uids = $_REQUEST['uid'];
        }
        else {
            $uids = '';
        }
		
		$str .= "<textarea style='display: none' name='uid'>{$uids}</textarea>
				 <input type='hidden' name='{$this->moduleString}' value='0'>";
		return $str;
	}
}
?>
