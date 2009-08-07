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
/*********************************************************************************
 * $Id: SavedSearch.php,v 1.15 2006/07/30 19:12:30 wayne Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
include_once('config.php');
require_once('log4php/LoggerManager.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('include/templates/TemplateGroupChooser.php');
require_once('include/Sugar_Smarty.php');
        
class SavedSearch extends SugarBean {
	var $db;
    var $field_name_map;

	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $assigned_user_name;
	var $modified_by_name;




	var $name;
	var $description;
	var $content;
	var $search_module;
	
	var $object_name = 'SavedSearch';
	var $table_name = 'saved_search';

	var $module_dir = 'SavedSearch';
	var $field_defs = array();
	var $field_defs_map = array();

    var $columns;
    
	function SavedSearch($columns = array(), $orderBy = null, $sortOrder = 'DESC') {
		parent::SugarBean();
        $this->columns = $columns;
        $this->orderBy = $orderBy;
        $this->sortOrder = $sortOrder;
		$this->setupCustomFields('SavedSearch');
		foreach ($this->field_defs as $field) {
			$this->field_name_map[$field['name']] = $field;
		}




	}

	// Saved Search Form
	function getForm($module, $inline = true) {
	    global $db, $current_user, $currentModule, $current_lang, $app_strings, $image_path;
        $json = getJSONobj();
        
		$saved_search_mod_strings = return_module_language($current_lang, 'SavedSearch');
                
		$query = 'SELECT id, name FROM saved_search 
				  WHERE 
					deleted = \'0\' AND
				  	assigned_user_id = \'' . $current_user->id . '\' AND 
					search_module =  \'' . $module . '\'
				  ORDER BY name';
	    $result = $db->query($query, true, "Error filling in saved search list: ");
		
		$savedSearchArray['_none'] = $app_strings['LBL_NONE'];
	    while ($row = $db->fetchByAssoc($result)) {
	        $savedSearchArray[$row['id']] = $row['name'];
	    }

		$sugarSmarty = new Sugar_Smarty();
		$sugarSmarty->assign('SEARCH_MODULE', $module);
		$sugarSmarty->assign('MOD', $saved_search_mod_strings);
		$sugarSmarty->assign('DELETE', $app_strings['LBL_DELETE_BUTTON_LABEL']);
		$sugarSmarty->assign('SAVE', $app_strings['LBL_SAVE_BUTTON_LABEL']);
	    $sugarSmarty->assign('imagePath', $image_path);
        
        // Column Chooser
        $chooser = new TemplateGroupChooser();
        
        $chooser->args['id'] = 'edit_tabs';
        $chooser->args['left_size'] = 5;
        $chooser->args['right_size'] = 5;
        $chooser->args['values_array'][0] = array();
        $chooser->args['values_array'][1] = array();
        
        if(!empty($_REQUEST['displayColumns']) && $_REQUEST['displayColumns'] != 'undefined') {
             // columns to display
             foreach(explode('|', $_REQUEST['displayColumns']) as $num => $name) {  
                    $chooser->args['values_array'][0][$name] = trim(translate($this->columns[$name]['label'], $module), ':');
             }
             // columns not displayed
             foreach(array_diff(array_keys($this->columns), array_values(explode('|', $_REQUEST['displayColumns']))) as $num => $name) {
                    $chooser->args['values_array'][1][$name] = trim(translate($this->columns[$name]['label'], $module), ':');
             }
        }
        else {
             foreach($this->columns as $name => $val) {
                if(!empty($val['default']) && $val['default'])  
                    $chooser->args['values_array'][0][$name] = trim(translate($val['label'], $module), ':');
                else 
                    $chooser->args['values_array'][1][$name] = trim(translate($val['label'], $module), ':');
            }
        }
        
        if(!empty($_REQUEST['sortOrder'])) $this->sortOrder = $_REQUEST['sortOrder'];
        if(!empty($_REQUEST['orderBy'])) $this->orderBy = $_REQUEST['orderBy']; 
        
        $chooser->args['left_name'] = 'display_tabs';
        $chooser->args['right_name'] = 'hide_tabs';
        
        $chooser->args['left_label'] =  $app_strings['LBL_DISPLAY_COLUMNS'];
        $chooser->args['right_label'] =  $app_strings['LBL_HIDE_COLUMNS'];
        $chooser->args['title'] =  '';
        $sugarSmarty->assign('columnChooser', $chooser->display());

        $sugarSmarty->assign('selectedOrderBy', $this->orderBy);
        if(empty($this->sortOrder)) $this->sortOrder = 'ASC';
        $sugarSmarty->assign('selectedSortOrder', $this->sortOrder);
         
        $lastSavedView = (empty($_SESSION['LastSavedView'][$module]) ? '' : $_SESSION['LastSavedView'][$module]);
        $sugarSmarty->assign('columnsMeta', $json->encode($this->columns));
        $sugarSmarty->assign('lastSavedView', $lastSavedView);
        $sugarSmarty->assign('SAVED_SEARCHES_OPTIONS', get_select_options_with_id($savedSearchArray, $lastSavedView));

        $json = getJSONobj();
        
        return $sugarSmarty->fetch('modules/SavedSearch/SavedSearchForm.tpl');
	}	
	
    function getSelect($module) {
        require_once('include/Sugar_Smarty.php');
        
        global $db, $current_user, $currentModule, $current_lang, $app_strings;
        $saved_search_mod_strings = return_module_language($current_lang, 'SavedSearch');

        $query = 'SELECT id, name FROM saved_search 
                  WHERE 
                    deleted = \'0\' AND
                    assigned_user_id = \'' . $current_user->id . '\' AND 
                    search_module =  \'' . $module . '\'
                  ORDER BY name';
        $result = $db->query($query, true, "Error filling in saved search list: ");
        
        $savedSearchArray['_none'] = $app_strings['LBL_NONE'];
        while ($row = $db->fetchByAssoc($result)) {
            $savedSearchArray[$row['id']] = $row['name'];
        }

        $sugarSmarty = new Sugar_Smarty();
        $sugarSmarty->assign('SEARCH_MODULE', $module);
        $sugarSmarty->assign('MOD', $saved_search_mod_strings);
        
        if(!empty($_SESSION['LastSavedView'][$module]) && (($_REQUEST['action'] == 'ListView') || ($_REQUEST['action'] == 'index')))
            $selectedSearch = $_SESSION['LastSavedView'][$module];
        else 
            $selectedSearch = '';

        $sugarSmarty->assign('SAVED_SEARCHES_OPTIONS', get_select_options_with_id($savedSearchArray, $selectedSearch));
        
        return $sugarSmarty->fetch('modules/SavedSearch/SavedSearchSelects.tpl');
    }
	
    function returnSavedSearch($id, $searchFormTab = 'advanced_search') {
		require_once('include/database/PearDatabase.php');
	    global $db, $current_user, $currentModule;
		$query = 'SELECT id, name, contents, search_module FROM saved_search 
				  WHERE 
				  	id = \'' . $id . '\'';
	    $result = $db->query($query, true, "Error filling in saved search list: ");
	    
	    $header = 'Location: index.php?action=index&module=';
	    
	    $saved_search_name = '';
	    while ($row = $db->fetchByAssoc($result, -1, false)) {
	        $header .= $row['search_module'];
            if(empty($_SESSION['LastSavedView'])) $_SESSION['LastSavedView'] = array();
            $_SESSION['LastSavedView'][$row['search_module']] = $row['id'];
	        $contents = unserialize(base64_decode($row['contents']));
	        $saved_search_id = $row['id'];
            $saved_search_name = $row['name'];
	    }
	    
		$search_query = '';
		foreach($contents as $input => $value) {
			if(is_array($value)) { // handle multiselects
				foreach($value as $v) {
					$search_query .= $input . '[]=' . $v . '&';
				}
			}
			else $search_query .= $input . '=' . $value . '&';
		}
        
		header($header . '&' . $search_query . 'saved_search_select=' . $saved_search_id . '&saved_search_select_name=' . $saved_search_name . '&searchFormTab=' . $searchFormTab);
	}
	
	function handleDelete($id) {
		$this->mark_deleted($id);
		header("Location: index.php?action=index&module={$_REQUEST['search_module']}&advanced={$_REQUEST['advanced']}&query=true&clear_query=true");
	}
    
	function handleSave($prefix, $redirect = true, $useRequired = false, $id = null) { 
		require_once('log4php/LoggerManager.php');

		global $current_user;
		$focus = new SavedSearch();
		if($id) $focus->retrieve($id);
        
		if($useRequired && !checkRequired($prefix, array_keys($focus->required_fields))) {
			return null;
		}
		
		$ignored_inputs = array('PHPSESSID', 'module', 'action', 'saved_search_name', 'saved_search_select', 'advanced'); 
		$contents = array_merge($_POST, $_GET);

		if($id == null) $focus->name = $contents['saved_search_name'];
		$focus->search_module = $contents['search_module'];
		  
		foreach($contents as $input => $value) {
			if(in_array($input, $ignored_inputs)) unset($contents[$input]);
		}
		$contents['advanced'] = true;
        
		$focus->contents = base64_encode(serialize($contents));

		$focus->assigned_user_id = $current_user->id;
		$focus->new_schema = true;
		$focus->search_module = $focus->search_module; 
		
		$saved_search_id = $focus->save();
		
		$GLOBALS['log']->debug("Saved record with id of " . $focus->id);		
		
		$search_query = '';
		foreach($contents as $input => $value) {
			if(is_array($value)) { // handle multiselects
				foreach($value as $v) {
					$search_query .= $input . '[]=' . $v . '&';
				}
			}
			else $search_query .= $input . '=' . $value . '&';
		}
		$this->handleRedirect($focus->search_module, $search_query, $saved_search_id, 'true');
	}
	
	function handleRedirect($return_module, $search_query, $saved_search_id, $advanced = 'false') {
        $_SESSION['LastSavedView'][$return_module] = $saved_search_id;
		$return_action = 'index';

		header("Location: index.php?action=$return_action&module=$return_module&{$search_query}saved_search_select=$saved_search_id&advanced={$advanced}");
		die();
	}
	
	function fill_in_additional_list_fields() {
		global $app_list_strings;
		// Fill in the assigned_user_name
		$this->search_module = $app_list_strings['moduleList'][$this->search_module];
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id);
	



	}
	
}

?>
