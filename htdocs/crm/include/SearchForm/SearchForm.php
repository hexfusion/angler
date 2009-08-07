<?php

require_once('include/tabs.php');

class SearchForm {
   /**
     * SearchForm Template to use (xtpl)
     * @var string
     */
    var $tpl;
    /**
     * SearchField meta data array to use. Populated from moduleDir/metadata/SearchFields
     * @var array
     */
    var $searchFields;
    /**
     * Seed bean to use
     * @var bean
     */
    var $bean;
    /**
     * Module the search from is for
     * @var string
     */
    var $module;
    /**
     * meta data for the tabs to display
     * @var array
     */
    var $tabs;
    /**
     * XTPL object
     * @var object 
     */
    var $xtpl;
     
    /**
     * loads SearchFields MetaData, sets member variables
     * 
     * @param string $module moduleDir
     * @param bean $seedBean seed bean to use
     * @param string $tpl template to use, defaults to moduleDir/SearchForm.html
     * 
     */
    function SearchForm($module, &$seedBean, $tpl = null) {
        global $app_strings;
        
        $this->module = $module;
        require_once('modules/' . $module . '/metadata/SearchFields.php');
        if(file_exists('custom/modules/' . $module . '/metadata/SearchFields.php')){
            require_once('custom/modules/' . $module . '/metadata/SearchFields.php');
        }
        
        
        //require_once('modules/' . $module . '/metadata/SearchFields.php');
        $this->searchFields = $searchFields[$module];
        if(empty($tpl)) {
            $this->tpl = 'modules/' . $module . '/SearchForm.html';
            if(!empty($GLOBALS['layout_edit_mode'])){
            	 $this->tpl = 'cache/studio/custom/working/modules/' . $module . '/SearchForm.html';
            }
        }
        else {
            $this->tpl = $tpl;
        } 
            
        $this->bean = $seedBean;
        $this->tabs = array(array('title'  => $app_strings['LNK_BASIC_SEARCH'],
                                  'link'   => $module . '|basic_search',
                                  'key'    => $module . '|basic_search'),
                            array('title'  => $app_strings['LNK_ADVANCED_SEARCH'],
                                  'link'   => $module . '|advanced_search',
                                  'key'    => $module . '|advanced_search'),
                            array('title'  => $app_strings['LNK_SAVED_VIEWS'],
                                  'link'   => $module . '|saved_views',
                                  'key'    => $module . '|saved_views'));
    }
    
    /**
     * Populate the searchFields from $_REQUEST
     */
    function populateFromRequest() {
        if(!empty($_REQUEST['searchFormTab'])) {
            $requestKeys = array_keys($_REQUEST);
            $searchFieldsKeys = array_keys($this->searchFields);
            switch($_REQUEST['searchFormTab']) {
                case 'basic_search':
                    foreach($this->searchFields as $name => $params) {
                        if(isset($_REQUEST[$name . '_basic'])) {  
                            $this->searchFields[$name]['value'] = $_REQUEST[$name . '_basic'];
                        }
                    }
                    foreach($this->bean->field_name_map as $key => $params) {
                        if(in_array($key . '_basic' , $requestKeys) && !in_array($key, $searchFieldsKeys)) {
                            
                            $this->searchFields[$key] = array('query_type' => 'default', 
                                                              'value'      => $_REQUEST[$key . '_basic']);
                        }
                    }
                    break;
                case 'advanced_search':
                   foreach($this->searchFields as $name => $params) {
                        if(isset($_REQUEST[$name])) {  
                            $this->searchFields[$name]['value'] = $_REQUEST[$name];
                        }
                    }
                   foreach($this->bean->field_name_map as $key => $params) {
                        if(in_array($key, $requestKeys) && !in_array($key, $searchFieldsKeys)) {
                            $this->searchFields[$key] = array('query_type' => 'default', 
                                                              'value'      => $_REQUEST[$key]);
                        }
                    }
                    break;
                case 'saved_views':
                    foreach($this->searchFields as $name => $params) {
                        if(isset($_REQUEST[$name . '_basic'])) {  // save basic first
                            $this->searchFields[$name]['value'] = $_REQUEST[$name . '_basic'];
                        }
                        if(isset($_REQUEST[$name])) {  // overwrite by advanced if available
                            $this->searchFields[$name]['value'] = $_REQUEST[$name];
                        }
                    }
                   foreach($this->bean->field_name_map as $key => $params) {
                        if(!in_array($key, $searchFieldsKeys)) {
                            if(in_array($key . '_basic', $requestKeys) ) {
                                $this->searchFields[$key] = array('query_type' => 'default', 
                                                                  'value'      => $_REQUEST[$key . '_basic']);
                            }
                            if(in_array($key, $requestKeys)) {
                                $this->searchFields[$key] = array('query_type' => 'default', 
                                                                  'value'      => $_REQUEST[$key]);
                            }
                        }
                    }
            }
        }
    }
    

    /**
     * The fuction will returns an array of filter conditions. 
     * 
     */
    function generateSearchWhere($add_custom_fields = false, $module='') {
        $values = $this->searchFields;
        
        $where_clauses = array();
        $like_char = '%';
        $table_name = $this->bean->object_name;
        foreach($this->searchFields as $field=>$parms) {
			$customField = false;
            // Jenny - Bug 7462: We need a type check here to avoid database errors
            // when searching for numeric fields. This is a temporary fix until we have 
            // a generic search form validation mechanism.
            $type = (!empty($this->bean->field_name_map[$field]['type']))?$this->bean->field_name_map[$field]['type']:'';
             if(!empty($this->bean->field_name_map[$field]['custom_type'])){
                $customField = true;	
                $type = $this->bean->field_name_map[$field]['custom_type'];
              }
           
            if ($type == 'int') {
                if (!empty($parms['value'])) {
                    $tempVal = explode(',', $parms['value']);
                    $newVal = '';
                    foreach($tempVal as $key => $val) {
                        if (!empty($newVal))    
                            $newVal .= ',';
                        if(!empty($val) && !(is_numeric($val))) 
                            $newVal .= -1;
                        else 
                            $newVal .= $val;
                    }
                    $parms['value'] = $newVal;
                }
            }
            // do not include where clause for custom fields with checkboxes that are unchecked
            elseif($type == 'bool' && empty($parms['value']) && $customField) { 
                continue; 
            }
			
            if(isset($parms['value']) && $parms['value'] != "") {
                $operator = 'like';
                if(!empty($parms['operator'])) {
                    $operator = $parms['operator'];
                }
                if(is_array($parms['value'])) {
                    $operator = 'in';
                    $field_value = '';
                    foreach($parms['value'] as $key => $val) {
                        if($val != ' ' and $val != '') {
                            if (!empty($field_value)) {
                                $field_value .= ',';
                            }
                            $field_value .= "'" . $GLOBALS['db']->quote($val) . "'";
                        }
                    }
                }
                else {
                    $field_value = $GLOBALS['db']->quote($parms['value']);
                }
                
                //set db_fields array.
                if(!isset($parms['db_field'])) {
                    $parms['db_field'] = array($field);
                }
                
                if(isset($parms['my_items']) and $parms['my_items'] == true) {
                    global $current_user;
                    $field_value = $GLOBALS['db']->quote($current_user->id);
                    $operator = '=';
                }
                    
                $where = '';
                $itr = 0;
                if($field_value != '') {
                    foreach ($parms['db_field'] as $db_field) {
                    	
                        if (strstr($db_field, '.') === false) {
                        	if(!$customField){
                            	$db_field = $this->bean->table_name .  "." . $db_field;
                        	}else{
                        		$db_field = $this->bean->table_name .  "_cstm." . $db_field;	
                        	}
                           
                        }
                        if ($GLOBALS['db']->dbType == 'oci8' && isset($parms['query_type']) && $parms['query_type'] == 'case_insensitive') {
                            $db_field = 'upper(' . $db_field . ")";
                            $field_value = strtoupper($field_value);
                        }
    
                        $itr++;
                        if(!empty($where)) {
                            $where .= " OR ";
                        }
                        switch(strtolower($operator)) {
                            case 'like':
                                $where .=  $db_field . " like '".$field_value.$like_char."'";
                                break;
                            case 'in':
                                $where .=  $db_field . " in (".$field_value.')';
                                break;
                            case '=':
                                $where .=  $db_field . " = '".$field_value ."'";
                                break;                      
                        }
                    }
                }
                if(!empty($where)) {
                    if($itr > 1) {
                        array_push($where_clauses, '( '.$where.' )');
                    } 
                    else {
                        array_push($where_clauses, $where);
                    }                   
                }
            }
        }
       

        return $where_clauses;
    }
    
    /**
     * displays the tabs (top of the search form)
     * 
     * @param string $currentKey key in $this->tabs to show as the current tab
     * 
     * @return string html
     */
    function displayTabs($currentKey) {
        if(substr_count($currentKey, 'advanced_search') > 0) {
            $this->tabs[2]['hidden'] = false;
        }
        $tabPanel = new SugarWidgetTabs($this->tabs, $currentKey, 'SUGAR.searchForm.searchFormSelect');
        
        $str = $tabPanel->display();
        $str .= '<script>';
        if(!empty($_REQUEST['displayColumns']))
            $str .= 'SUGAR.savedViews.displayColumns = "' . $_REQUEST['displayColumns'] . '";';
        if(!empty($_REQUEST['hideTabs']))
            $str .= 'SUGAR.savedViews.hideTabs = "' . $_REQUEST['hideTabs'] . '";';
        if(!empty($_REQUEST['orderBy']))
            $str .= 'SUGAR.savedViews.selectedOrderBy = "' . $_REQUEST['orderBy'] . '";';
        if(!empty($_REQUEST['sortOrder']))
            $str .= 'SUGAR.savedViews.selectedSortOrder = "' . $_REQUEST['sortOrder'] . '";';
            
        $str .= '</script>';
        
        return $str;
    }
    
    /**
     * sets up the search forms, populates the preset values
     * 
     */
    function setup() {
        global $mod_strings, $app_strings, $image_path, $app_list_strings;
        
        $this->xtpl = new XTemplate($this->tpl);
        $this->xtpl->assign("MOD", $mod_strings);
        $this->xtpl->assign("APP", $app_strings);
        $this->xtpl->assign("IMAGE_PATH", $image_path);
        
        foreach($this->searchFields as $name => $params) {
            if(isset($params['template_var'])) $templateVar = $params['template_var'];
            else $templateVar = strtoupper($name);
            if(isset($params['value'])) { // populate w/ preselected values
                if(isset($params['options'])) {
                    $options = $app_list_strings[$params['options']];
                    if(isset($params['options_add_blank']) && $params['options_add_blank']) array_unshift($options, '');
                    $this->xtpl->assign($templateVar, get_select_options_with_id($options, $params['value']));                    
                }
                else {
                    if(isset($params['input_type'])) { 
                        switch($params['input_type']) {
                            case 'checkbox': // checkbox input
                                if($params['value'] == 'on' || $params['value'])
                                    $this->xtpl->assign($templateVar, 'checked');
                                break;
                        }
                    }
                    else {// regular text input
                        $this->xtpl->assign($templateVar, to_html($params['value']));
                    }
                }
            }
            else { // populate w/o preselected values
                if(isset($params['options'])) {
                    $options = $app_list_strings[$params['options']];
                    if(isset($params['options_add_blank']) && $params['options_add_blank']) array_unshift($options, '');
                    $this->xtpl->assign($templateVar, get_select_options_with_id($options, ''));
                }
            }
        }
        if (!empty($_REQUEST['assigned_user_id'])) $this->xtpl->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $_REQUEST['assigned_user_id']));
        else $this->xtpl->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));  

        // handle my items only
        if(isset($this->searchFields['current_user_only']) && isset($this->searchFields['current_user_only']['value'])) 
            $this->xtpl->assign("CURRENT_USER_ONLY", "checked");
    }
    
    /**
     * displays the search form header
     * 
     * @param string $view which view is currently being displayed
     * 
     */
    function displayHeader($view) {
        global $current_user; 
        
        $header_text = '';
        if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){  
            $header_text = "<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>";
        }
        
        echo $header_text . $this->displayTabs($this->module . '|' . $view);
        echo "<form name='search_form'>" .
             "<input type='hidden' name='searchFormTab' value='{$view}'/>" .
             "<input type='hidden' name='module' value='{$_REQUEST['module']}'/>" .
             "<input type='hidden' name='action' value='{$_REQUEST['action']}'/>" . 
             "<input type='hidden' name='query' value='true'/>";
    }

    /**
     * displays the search form body, for example if basic_search is being displayed, then the function call would be 
     * displayWithHeaders('basic_search', $htmlForBasicSearchBody) {
     * 
     * @param string $view which view is currently being displayed
     * @param string $basic_search_text body of the basic search tab
     * @param string $advanced_search_text body of the advanced search tab
     * @param string $saved_views_text body of the saved views tab
     * 
     */    
    function displayWithHeaders($view, $basic_search_text = '', $advanced_search_text = '', $saved_views_text = '') {
        $this->displayHeader($view);
        echo "<div id='{$this->module}basic_searchSearchForm' " . (($view == 'basic_search') ? '' : "style='display: none'") . ">" . $basic_search_text . "</div>";
        echo "<div id='{$this->module}advanced_searchSearchForm' " . (($view == 'advanced_search') ? '' : "style='display: none'") . ">" . $advanced_search_text . "</div>";
        echo "<div id='{$this->module}saved_viewsSearchForm' " . (($view == 'saved_views') ? '' : "style='display: none'") . ">" . $saved_views_text . "</div>";
        echo $this->getButtons();
        echo '</form>';
    }
    
    /**
     * displays the basic search form body 
     * 
     * @param bool $header display this with headers
     * @param bool $return echo or return the html
     * 
     * @return string html of contents 
     */    
    function displayBasic($header = true, $return = false) {
        global $current_user, $image_path;
                
        $this->bean->custom_fields->populateXTPL($this->xtpl, 'search' );
        $this->xtpl->parse("main");
        if(!empty($GLOBALS['layout_edit_mode'])){
         $this->xtpl->parse("advanced");
        }
        $text = $this->xtpl->text("main");
        if(!empty($GLOBALS['layout_edit_mode'])){
        	$text .= $this->xtpl->text("advanced");
        }
        if($header && empty($GLOBALS['layout_edit_mode'])) {
            $this->displayWithHeaders('basic_search', $text);
        }
        else {
            if($return) return $text;
            else echo $text;
        } 
    }

    /**
     * displays the advanced search form body 
     * 
     * @param bool $header display this with headers
     * @param bool $return echo or return the html
     * 
     * @return string html of contents 
     */        
    function displayAdvanced($header = true, $return = false) {
        global $current_user, $image_path;
        
        $this->bean->custom_fields->populateXTPL($this->xtpl, 'search' );
        $this->xtpl->parse("advanced");
        $text = $this->xtpl->text("advanced");

        if($header) {
            $this->displayWithHeaders('advanced_search', '', $text);
        }
        else {
            if($return) return $text;
            else echo $text;
        }
    }
    
    /**
     * displays the saved views form body 
     * 
     * @param bool $header display this with headers
     * @param bool $return echo or return the html
     * 
     * @return string html of contents 
     */    
    function displaySavedViews($listViewDefs, $lv, $header = true, $return = false) {
        global $current_user, $image_path;

        $savedSearch = new SavedSearch($listViewDefs[$this->module], $lv->data['pageData']['ordering']['orderBy'], $lv->data['pageData']['ordering']['sortOrder']);

        if($header) {
            $this->displayWithHeaders('saved_views', $this->displayBasic(false, true), $this->displayAdvanced(false, true), $savedSearch->getForm($this->module));
            echo '<script>SUGAR.savedViews.handleForm();</script>';
        }
        else {
            echo $savedSearch->getForm($this->module, false);
        }
    }
    
    /**
     * get the search buttons 
     * 
     * @return string html of contents 
     */    
    function getButtons() {
        global $app_strings;
        $str = "<input tabindex='2' title='{$app_strings['LBL_SEARCH_BUTTON_TITLE']}' accessKey='{$app_strings['LBL_SEARCH_BUTTON_KEY']}' onclick='SUGAR.savedViews.setChooser()' class='button' type='submit' name='button' value='{$app_strings['LBL_SEARCH_BUTTON_LABEL']}'/>&nbsp;";
        $str .= "<input tabindex='2' title='{$app_strings['LBL_CLEAR_BUTTON_TITLE']}' accessKey='{$app_strings['LBL_CLEAR_BUTTON_KEY']}' onclick='SUGAR.searchForm.clear_form(this.form);' class='button' type='button' name='clear' value=' {$app_strings['LBL_CLEAR_BUTTON_LABEL']} '/>";
        
        return $str;
    }
}

?>
