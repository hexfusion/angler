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
// $Id: DynamicField.php,v 1.73 2006/09/06 15:58:10 majed Exp $
require_once('include/utils/file_utils.php');
require_once('include/dir_inc.php');
require_once('modules/DynamicFields/FieldCases.php');

class DynamicField {
    var $db;
    var $bean;
    var $avail_fields = array();
    var $module;
    var $modules = array();
    var $cached_field_types = array();
    
    function DynamicField($module=''){
        $this->db = & PearDatabase::getInstance();
        $this->module = $module;
        if(empty($this->module) && !empty($_REQUEST['module'])){
            $this->module = $_REQUEST['module'];
        }

    }
    var $table_name = 'fields_meta_data';

    function setup($bean=null, $clean_setup=true){
        if($bean){
            $this->bean =& $bean;
        }
        if(isset( $this->bean->module_dir)){
            $this->module = $this->bean->module_dir;
        }
        $this->loadCustomModulesList();
        $this->getAvailableFields();
        if($clean_setup && empty($this->bean->added_custom_field_defs)){
            $this->populateBean();
        }




    }


    /*
    THIS CREATES CUSTOM DATA FIELDS FOR MODULES
    */
    function createCustomTable(){

        if (!$this->db->tableExists($this->bean->table_name."_cstm")) {

            $query = 'CREATE TABLE '.$this->bean->table_name.'_cstm ( ';
            $query .='id_c char(36) NOT NULL';
            $query .=', PRIMARY KEY ( id_c ) )';

            $this->db->query($query);
            $this->add_existing_custom_fields();
            //$this->populate_existing_ids();
        }

    }

    function getFields(){
        static $fields = array();
        if(isset($fields[$this->module])){
            return 	$fields[$this->module];
        }
        $fields[$this->module] = array();
        $this->getAvailableFields(false);
        foreach(array_keys($this->avail_fields) as $name){
            $fields[$this->module][$name] = $this->getField($name);
        }
        return 	$fields[$this->module];
    }
    function add_existing_custom_fields(){
        $this->avail_fields = array();
        $this->getAvailableFields(true);
        foreach($this->avail_fields as $name=>$data){
            $field = $this->getField($name);
            $query = $field->get_db_add_alter_table($this->bean->table_name . '_cstm');
            $this->db->query($query);
        }
    }


    function populate_existing_ids(){
        $result = $this->db->query("SELECT id FROM " . $this->bean->table_name);
        while($row = $this->db->fetchByAssoc($result)){
            $this->db->query("INSERT INTO ". $this->bean->table_name . "_cstm (id_c) VALUES ('". $row['id'] . "')");
        }
    }

    /*
    * get the join for joining the custom table
    *
    * */

    function getJOIN(){

        if(!array_key_exists($this->module, $this->modules)){

            return false;
        }
      	return array('select'=>" , ". $this->bean->table_name. "_cstm.*", 'join'=> " LEFT JOIN " .$this->bean->table_name. "_cstm ON " .$this->bean->table_name. ".id = ". $this->bean->table_name. "_cstm.id_c ");
       
    }

    /**
     * 
     * DEPRICATED
    loads fields into the bean
    This used to be called during the retrieve process now it is done through a join
    */

    function retrieve(){

        if(!array_key_exists($this->module, $this->modules)){

            return false;
        }

        $query = "SELECT * FROM ".$this->bean->table_name."_cstm WHERE id_c='".$this->bean->id."'";
        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result);

        if($row){
            foreach($row as $name=>$value){
                if(isset($this->avail_fields[$name])){
                    $this->bean->$name = $value;
                }


            }


        }
        //$this->populateBean();

    }

    function fill_relationships(){
        global $beanList, $beanFiles;
        $this->avail_fields = array();
        $this->getAvailableFields(false);
        foreach($this->avail_fields as	$field){
            if($field['data_type'] == 'relate'){
                $related_module =$field['ext2'];
                $name = $field['name'];

                if(isset($beanList[ $related_module])){
                    $class = $beanList[$related_module];

                    if(file_exists($beanFiles[$class]) && isset($this->bean->$name)){
                        require_once($beanFiles[$class]);
                        $mod = new $class();
                        $mod->retrieve($this->bean->$name);
                        $bean_name = $name . '_name';
                        $this->bean->$bean_name = $mod->name;
                    }
                }
            }
        }
    }



    /*
    Save Fields From The Bean
    */
    function save($isUpdate){

        if(array_key_exists($this->module, $this->modules) && isset($this->bean->id)){

            if($isUpdate){
                $query = "UPDATE ". $this->bean->table_name. "_cstm SET ";
            }
            $queryInsert = "INSERT INTO ". $this->bean->table_name. "_cstm (id_c";
            $values = "('".$this->bean->id."'";
            $first = true;
            foreach($this->avail_fields as $name=>$field){
                if($field['data_type'] == 'html')continue;
                if($field['data_type'] == 'multienum'){
                    if(!empty($_POST[$name]) && is_array($_POST[$name])){

                        $this->bean->$name = implode('^,^',$_POST[$name] );
                    }
                }
                if(isset($this->bean->$name)){
                    if($isUpdate){
                        if($first){
                            $query .= " $name='".PearDatabase::quote(from_html($this->bean->$name))."'";
                            
                        }else{
                            $query .= " ,$name='".PearDatabase::quote(from_html($this->bean->$name))."'";
                        }
                    }
					$first = false;
                    $queryInsert .= " ,$name";
                    $values .= " ,'". PearDatabase::quote(from_html($this->bean->$name )). "'";
                }
                $this->clearBean($name);
            }
            if($isUpdate){
                $query.= " WHERE id_c='" . $this->bean->id ."'";
                
            }
           
            $queryInsert .= " ) VALUES $values )";
            
            if(!$first){
                if(!$isUpdate){
                    
                    $this->db->query($queryInsert);
                }else{
    
                    $result = $this->db->query($query);
                    if((($this->db->dbType=='mysql' || $this->db->dbType=='mssql') && $this->db->getAffectedRowCount($result) == 0) || (($this->db->dbType=='oci8' ) && $this->db->getRowCount($result) == 0) ){
                        $this->db->query($queryInsert);
                    }
                }
            }
        }


    }


    function getType($name){
        if(isset($cached_field_types[$name]))
        {
            return $this->cached_field_types[$name];
        }
        else if(!empty($this->avail_fields[$name]))
        {
            $this->cached_field_types[$name] = $this->avail_fields[$name]['data_type'];
        }
        else if(!empty($this->avail_fields[$name.'_c']))
        {
            $this->cached_field_types[$name] = $this->avail_fields[$name.'_c']['data_type'];
        }
        else 
        {       
            $db_name = $this->getDBName($name);
            if(!empty($this->avail_fields[$db_name]))
            {
                $this->cached_field_types[$name] = $this->avail_fields[$db_name]['data_type'];
            }
            else 
            {
                $this->cached_field_types[$name] = '';
            }
        }
        
        return $this->cached_field_types[$name];
    }

    function getField($name, $type='', $refresh= false){

        static $loadedFields = array();
		if($refresh){
			$loadedFields = array();
		}
        if(isset($loadedFields[$this->module][$name])){
            return $loadedFields[$this->module][$name];
        }
        if(!isset($this->avail_fields[$name]) && isset($this->avail_fields[$this->getDBName($name)])){
            $name = $this->getDBName($name);
        }
        if(empty($type)){
            if(isset($this->avail_fields[$name])){
                $type = $this->avail_fields[$name]['data_type'];
                if($type == 'text'){
                    $type = 'textarea';
                }
            }
        }

        $field = get_widget($type);


        if(isset($this->avail_fields[$name])){
            $field->set($this->avail_fields[$name]);
        }else{
            $field->set($this->getFieldSetFromFieldDef($name));
        }
        if(isset($this->bean)){
            $field->bean =& $this->bean;
        }
        $loadedFields[$this->module][$name] = $field;

        return $field;
    }

    function getFieldLabelHTML($name, $view){
        $field = $this->getField($name);
        $field->view = $view;
        return $field->get_html_label();
    }


    function getFieldHTML($name, $view){
        $field = $this->getField($name);
        $field->view = $view;
        return $field->get_html();
    }

    function getFieldXTPL($name, $view){
        $field = $this->getField($name);
        $field->view = $view;
        return $field->get_xtpl($this->bean);
    }

    function getAllFieldsHTML($view){
        return $this->getAllFieldsView($view, 'html');
    }

    function getAllFieldsXTPL($view){
        return $this->getAllFieldsView($view, 'xtpl');
    }

    function getDBName($name){
        static $cached_results = array();
        
        if(!empty($cached_results[$name]))
        {
            return $cached_results[$name];
        }
        
        // Remove any non-db friendly characters
        $return_value = preg_replace("/[^\w]+/","_",$name) . '_c';
        $cached_results[$name] = $return_value;
        
        return $return_value;
    }
    //only custom fields
    function getAllFieldsView($view, $type){
        if(!array_key_exists($this->module, $this->modules)){
            return array();
        }
        $results = array();
        if(empty($this->avail_fields)){
            $this->getAvailableFields();
        }
        $fields = $this->getFields();

        foreach($fields as $name=>$field){
            $field->view = $view;
            $field->bean =& $this->bean;
            switch(strtolower($type)){
                case 'xtpl':
                    $results[$name] = array('xtpl'=>$field->get_xtpl());
                    break;
                case 'html':
                    $results[$name] = array('html'=> $field->get_html(), 'label'=> $field->get_html_label(), 'fieldType'=>$field->data_type, 'isCustom' =>true);
                    break;

            }

        }
        return $results;
    }
    //this includes non-custom fields
    function getAllBeanFieldsView($view, $type){
        static $bad_types = array();
        if(!isset($this->bean)){
            return array();
        }
        $this->avail_fields = array();
        $results = $this->getAllFieldsView($view, $type);

        foreach($this->bean->field_defs as $name=>$value){
            if(!isset($value['source']) || $value['source'] == 'db' || !empty($value['table'])){
                $ftype = $value['type'];
                if(!isset($bad_types[$ftype]) ){



                    if(isset($this->avail_fields[$value['name']])){
                        $ftype = $this->avail_fields[$value['name']]['data_type'];
                    }
                    if($ftype == 'text'){
                        $ftype = 'textarea';
                    }


                    $field = $this->getField($value['name'], $ftype);
                    $field->view = $view;
                    switch(strtolower($type)){
                        case 'xtpl':
                            $results[$name] = array('xtpl'=>$field->get_xtpl($this->bean), 'type'=>$ftype);
                            break;
                        case 'html':
                  
                            $results[$name] = array('html'=> $field->get_html(), 'label'=> $field->get_html_label(), 'fieldType'=>$ftype, 'isCustom' =>(!empty($this->avail_fields[$name]))?true:false);
                            break;

                    }
                }
            }

        }
        return $results;
    }

    function getFieldSetFromFieldDef($name){
        $set = array();
        if(isset($this->bean->field_defs[$name]))
        {
            $set['name'] = $name;
            if(isset($this->bean->field_defs[$name]['vname'])){
                $set['label'] = $this->bean->field_defs[$name]['vname'];
            }else{
                $set['label'] = 'NO_LABEL';
            }
            if(isset($this->bean->field_defs[$name]['len'])){
                $set['max_size'] = $this->bean->field_defs[$name]['len'];
            }
            if(isset($this->bean->field_defs[$name]['required']) && $this->bean->field_defs[$name]['required']){
                $set['required_option'] = 'required';
            }
            if(isset($this->bean->field_defs[$name]['default'])){
                $set['default_value'] = $this->bean->field_defs[$name]['default'];
            }
            if(isset($this->bean->field_defs[$name]['options'])){
                $set['ext1'] = $this->bean->field_defs[$name]['options'];
            }
        }

        return $set;
    }

    function populateXTPL(&$xtpl, $view){

        $results = $this->getAllFieldsView($view, 'xtpl');

        foreach($results as $name=>$value){

            if(is_array($value['xtpl'])){
                foreach($value['xtpl'] as $xName=>$xValue){
                    $xtpl->assign(strtoupper($xName), $xValue);

                }
            }else{
                $xtpl->assign(strtoupper($name), $value['xtpl']);
            }
        }

    }

    function populateAllXTPL(&$xtpl, $view, $html_var_name='', $set_fields = array()){

        $results = $this->getAllBeanFieldsView($view, 'xtpl');
        foreach($results as $name=>$value){

            if(is_array($value['xtpl'])){
                foreach($value['xtpl'] as $xName=>$xValue){
                    if(!empty($html_var_name)){
                        $xtpl->append($html_var_name, strtoupper($xName), $xValue);
                    }else{
                        $xtpl->assign(strtoupper($xName), $xValue);
                    }
                }
            }else{

                if(!empty($html_var_name)){
                    if(!isset($set_fields[strtoupper($name)])){
                        $xtpl->append($html_var_name, strtoupper($name), $value['xtpl']);
                    }
                }else{
                    $xtpl->assign(strtoupper($name), $value['xtpl']);
                }
            }
        }

    }

    function setWhereClauses(&$where_clauses){
        if(!array_key_exists($this->module, $this->modules)){
            return false;
        }
        foreach($this->avail_fields as $name=>$value){
            if(!empty($_REQUEST[$name])){
                array_push($where_clauses, $this->bean->table_name . "_cstm.$name LIKE '". PearDatabase::quote($_REQUEST[$name]). "%'");
            }
        }

    }
    function updateField($id, $values){
        if(empty($values)){
            return;
        }
        $query = "UPDATE fields_meta_data SET id='$id' ";
        if(empty($values['max_size']))unset($values['max_size']);
        foreach($values as $key=>$value){
            $query .= ",$key='$value' ";
        }
        $query .= " WHERE id='$id'";
        $this->db->query($query);
        $this->cleanSaveToCache();
        $this->avail_fields = array();
        $this->getAvailableFields(true);
        $name = str_replace( $this->module, '', $id);
        $field = $this->getField($name, '' , true);
        $field->new_field_definition=$values;  //set updated fields.
        if($field){
            $query = $field->get_db_modify_alter_table($this->bean->table_name . '_cstm');
            if(!empty($query)){
            $this->db->query($query);
        }
        }

    }
    
    function dropFieldById($id){
    	$result = $GLOBALS['db']->query("SELECT custom_module, name FROM fields_meta_data WHERE id='$id'");
    	if($row = $GLOBALS['db']->fetchByAssoc($result)){
    		 $GLOBALS['db']->query("DELETE FROM  fields_meta_data WHERE id='$id'");
    		 $db_name = ' COLUMN ' . $row['name'];   
    		 $module = $row['custom_module'];
    		 if(!empty($GLOBALS['beanList'][$module])){
    		 	$class = $GLOBALS['beanList'][$module];
    		 	require_once($GLOBALS['beanFiles'][$class]);
    		 	$mod = new $class();
    		 	$GLOBALS['db']->query("ALTER TABLE " .$mod->table_name . "_cstm DROP $db_name");	
    		 }
        	
    	}
    	DynamicField::deleteCache();
    	
    }
    function dropField($name){
        $object_name = $this->module;
       
        $db_name = $name;
        $this->db->query("DELETE FROM  fields_meta_data WHERE id='$object_name$db_name'");
        
        $db_name = ' COLUMN ' . $db_name;   
        
        
        $this->db->query("ALTER TABLE " . $this->bean->table_name . "_cstm DROP $db_name");
        
        $this->cleanSaveToCache();
    }


    function addField($name,$label='', $type='Text',$max_size='255',$required_option='optional', $default_value='', $ext1='', $ext2='', $ext3='',$audited=0, $mass_update = 0 , $ext4='', $help='',$duplicate_merge=0){

        if(empty($label)){
            $label = $name;
        }
        $label = $this->addLabel($label);

        $object_name = $this->module;
        $db_name = $this->getDBName($name);
        if(isset($this->avail_fields[$db_name])){
            return;
        }
        if(!array_key_exists($this->module, $this->modules)){
        $this->createCustomTable();
        }
        require_once('modules/EditCustomFields/FieldsMetaData.php');
        $fmd = new FieldsMetaData();
        $fmd->id = $object_name.$db_name;
        $fmd->custom_module= $object_name;
        $fmd->name = $db_name;
        $fmd->label = $label;
        $fmd->data_type = $type;
        $fmd->max_size = $max_size;
        $fmd->required_option = $required_option;
        $fmd->default_value = $default_value;
        $fmd->ext1 = $ext1;
        $fmd->ext2 = $ext2;
        $fmd->ext3 = $ext3;
        $fmd->ext4 = $ext4;
        $fmd->help = $help;
        $fmd->mass_update = $mass_update;
        $fmd->duplicate_merge = $duplicate_merge;
        
        $fmd->audited =$audited;
        $fmd->new_with_id=true;
        
        $fmd->save();

        $this->cleanSaveToCache();
        if(!array_key_exists($this->module, $this->modules)){
            $this->createCustomTable();
            $this->saveCustomModulesList();
        }

        $this->avail_fields = array();
        $this->getAvailableFields(true);
        $field = $this->getField($name);

        if($field){
            $query = $field->get_db_add_alter_table($this->bean->table_name . '_cstm');
            if(!empty($query)){
            $this->db->query($query);
        }
    }
    }

    function add_existing_custom_field($name){
        $this->avail_fields = array();
        $this->getAvailableFields(true);
        $field = $this->getField($name);

        if($field){
            $query = $field->get_db_add_alter_table($this->bean->table_name . '_cstm');
            if(!empty($query)){
            $this->db->query($query);
            }
        }
    }

    function getAvailableFields($clean=false){
        if(!$clean){

            if(!array_key_exists($this->module, $this->modules)){
                $this->avail_fields = array();
                return $this->avail_fields;
            }

            if( $this->loadFromCache()){

                return $this->avail_fields;
            }
        }
        $this->avail_fields = array();
        $query = "SELECT * FROM fields_meta_data WHERE custom_module='$this->module' AND deleted = 0";
        $result = $this->db->query($query);
        while($row = $this->db->fetchByAssoc($result)){
            $this->avail_fields[$row['name']] = $row;
        }

        $this->saveToCache();
        return $this->avail_fields;

    }

    function addLabel($label){
        global $current_language;
        $limit = 10;
        $count = 0;
        $field_key = $this->getDBName($label);
        $curr_field_key = $this->getDBName($label);
        while( ! create_field_label($this->module, $current_language, $curr_field_key, $label) )
        {
            $curr_field_key = $field_key. "_$count";
            if ( $count == $limit)
            {
                return $curr_field_key;
            }
            $count++;
        }
        return $curr_field_key;
    }



    function populateBean(){
        if(isset($this->bean->added_custom_field_defs) && $this->bean->added_custom_field_defs){
            return;
        }
        $fields = $this->getFields();
        foreach($fields as $name=>$field){
            $this->bean->field_name_map[$name] = $field->get_field_def();
            $this->bean->field_defs[$name] = $this->bean->field_name_map[$name];
            $this->bean->column_fields[] = $name;
            if($this->bean->field_name_map[$name]['required']){
                $this->bean->required_fields[$name] = 1;
            }

        }


        $this->bean->added_custom_field_defs= true;

    }



    function clearBean($name){
        unset($this->bean->$name);
    }

    function cleanSaveToCache(){
        $this->deleteCache();
        $this->getAvailableFields();

    }



    function saveToCache(){
        $file = 'dynamic_fields/'. $this->module . '/fields.php';
        $file = create_cache_directory($file);
        $vardump = var_export($this->avail_fields, true);
        $fp = fopen($file, 'wb');
        fwrite($fp,"<?php\n\$avail_fields=".  $vardump . "\n?>");
        fclose($fp);    
    }

    function deleteCache(){
        $file = 'cache/dynamic_fields/';
        if(file_exists($file)){
            rmdir_recursive($file);
        }
        return true;
    }

    function loadFromCache(){
              static $loaded_fields;
        if(isset($loaded_fields[$this->module])){
            $this->avail_fields = $loaded_fields[$this->module];
            return true;
        }
        $file = 'cache/dynamic_fields/'. $this->module . '/fields.php';
        if(file_exists($file)){
            include($file);
            if(!isset($avail_fields)){
                return false;
            }
            $loaded_fields[$this->module] = $avail_fields;
            $this->avail_fields = $avail_fields;

            return true;
        }
        return false;
    }

    function loadCustomModulesList(){
        // leverage a cache to decrease the work as much as possible
        static $modules_array = null;

        if(!is_null($modules_array))
        {
            // Make a copy so that when the bean is destroyed it does not clear the master copy
            $this->modules =& $modules_array;
            return;
        }

        $file = 'cache/dynamic_fields/modules.php';
        $this->modules = array();
        if(file_exists($file)){
            include($file);
            // Make a copy so that when the bean is destroyed it does not clear the master copy
            $this->modules = $custom_modules;
        }
        else{
            $this->saveCustomModulesList();
        }

        // Either the file was included, or the custom modules have been loaded.  Save the custom modules for this round trip.
        // Make a copy so that when the bean is destroyed it does not clear the master copy
        $modules_array = $this->modules;
    }

    function saveCustomModulesList(){
        $modules = array();
        //added check to avoid creation of cache before the table is created.
    	if ($this->db->tableExists('fields_meta_data')) {
	        $query = 'SELECT DISTINCT custom_module FROM fields_meta_data';
	        $result = $this->db->query($query);
	        if($result){
	            while($row = $this->db->fetchByAssoc($result)){
	                $modules[$row['custom_module']] = $row['custom_module'];
	            }
	        }
	        $this->modules = $modules;
	        $file = 'dynamic_fields/modules.php';
	        $file = create_cache_directory($file);
	        $vardump = var_export($modules, true);
	        $fp = fopen($file, 'wb');
	        fwrite($fp,"<?php\n\$custom_modules=".  $vardump . "\n?>");
	        fclose($fp);
    	}
        return $modules;
    }
}
