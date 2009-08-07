<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/********************************************************************************
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
* $Id: SugarBean.php,v 1.451 2006/09/07 00:28:46 wayne Exp $
* Description:  Defines the base class for all data entities used throughout the
* application.  The base class including its methods and variables is designed to
* be overloaded with module-specific methods and variables particular to the
* module's base entity class.
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
*******************************************************************************/


include_once('sugar_version.php');
require_once('data/Tracker.php');
require_once('include/utils.php');
require_once('modules/DynamicFields/DynamicField.php');
require_once('modules/CustomFields/CustomFields.php');
require_once('include/database/PearDatabase.php');
require_once('modules/ACLActions/ACLAction.php');
require_once('include/TimeDate.php');
include_once('include/database/DBManagerFactory.php');
require_once('include/CacheHandler.php');
require_once('modules/Currencies/Currency.php');


//TODO remove save2, deleterelationship, subpanelviewer from each module.  Use generic include instead

/**
 * SugarBean is the base class for all business objects in Sugar.  It implements
 * the primary functionality needed for manipulating business objects: create, 
 * retrieve, update, delete.  It allows for seraching and retriving list of records.
 * It allows for retrieving related objects (e.g. contacts related to a specific account). 
 * 
 * In the current implementation, there can only be one bean per folder.
 * Naming convention has the bean name be the same as the module and folder name.
 * All bean names should be singular (e.g. Contact).  The primary table name for 
 * a bean should be plural (e.g. contacts).
 * 
 */
class SugarBean
{
    /**
     * A pointer to the database helper object DBHelper
     *
     * @var DBHelper
     */
	var $db;

	/**
	 * Does this bean participate in the new schema.  All modules should be new schema at this point.
	 * New schema means that you have a GUID in the id field.
	 *
	 * @var BOOL - default false.
	 */
	var $new_schema = false;
	
	/**
	 * When createing a bean, you can specify a value in the id column as 
	 * long as that value is unique.  During save, if the system finds an
	 * id, it assumes it is an update.  Setting new_with_id to true will
	 * make sure the system performs an insert instead of an update.
	 *
	 * @var BOOL -- default false
	 */
	var $new_with_id = false;
	











    /**
     * holds the full name of the user that an item is assigned to.  Only used if notifications
     * are turned on and are going to be sent out.
     *
     * @var String
     */
	var $new_assigned_user_name;
	
	/**
	 * An array of booleans.  This array is cleared out when data is loaded.
	 * As date/times are converted, a 1 is placed under the key, the field is converted.
	 *
	 * @var Array of booleans
	 */
	var $processed_dates_times = array();

	/**
	 * Whether to process date/time fields for storage in the database in GMT
	 *
	 * @var BOOL
	 */
	var $process_save_dates =true;

    /**
     * This signals to the bean that it is being saved in a mass mode.  
     * Examples of this kind of save are import and mass update.
     * We turn off notificaitons of this is the case to make things more efficient.
     *
     * @var BOOL
     */
	var $save_from_post = true;

	/**
	 * When running a query on releated items using the method: retrieve_by_string_fields 
	 * this value will be set to true if more than one item matches the search criterea.
	 *
	 * @var BOOL
	 */
	var $duplicates_found = false;

	/**
	 * The DBManager instance that was used to load this bean and should be used for 
	 * future database interactions
	 *
	 * @var DBManager
	 */
	var $dbManager;
	
	/**
	 * true if this bean has been deleted, false otherwise.
	 *
	 * @var BOOL
	 */
    var $deleted = 0;

    /**
     * Should the date modified column of the bean be updated during save?
     * This is used for admin level functionality that should not be updating
     * the date modified.  This is only used by sync to allow for updates to be
     * replicated in a way that will not cause them to be replicated back.
     *
     * @var BOOL
     */
    var $update_date_modified = true;

    /**
     * Should the modified by column of the bean be updated during save?
     * This is used for admin level functionality that should not be updating
     * the modified by column.  This is only used by sync to allow for updates to be
     * replicated in a way that will not cause them to be replicated back.
     *
     * @var BOOL
     */
    var $update_modified_by = true;

    /**
     * Setting this to true allows for updates to overwrite the date_entered
     * @var BOOL
     */
    var $update_date_entered = false;

    /**
     * This allows for seed data to be created without using the current uesr to set the id.
     * This should be replaced by altering the current user before the call to save.
     *
     * @var unknown_type
     */
    //TODO This should be replaced by altering the current user before the call to save.
    var $set_created_by = true;

    /**
     * The database table where records of this Bean are stored.
     *
     * @var String
     */
	var $table_name = '';
	
	/**
	 * This is the singular name of the bean.  (i.e. Contact).
	 *
	 * @var String
	 */
	var $object_name = '';

	/**
	 * The name of the module folder for this type of bean.
	 *
	 * @var String
	 */
	var $module_dir = '';
	var $field_name_map;
	var $field_defs;
	var $custom_fields;
	var $column_fields = array();
	var $list_fields = array();
	var $additional_column_fields = array();
	var $current_notify_user;
	var $fetched_row=false;
	var $layout_def;
	var $force_load_details = false;
	var $optimistic_lock = false;

	var $number_formatting_done = false;







	function SugarBean(){
		global  $dictionary, $current_user;
		static $loaded_defs = array();
		$this->db = & PearDatabase::getInstance();

        $this->dbManager = & DBManagerFactory::getInstance();






		if(empty($loaded_defs[$this->object_name])){








		if(isset($this->module_dir) && isset($this->object_name) && !isset($dictionary[$this->object_name])){
			if(file_exists('modules/'. $this->module_dir . '/vardefs.php')){
				include_once('modules/'. $this->module_dir . '/vardefs.php');
			}





			if(file_exists('custom/modules/'. $this->module_dir . '/Ext/Vardefs/vardefs.ext.php')){
				include_once('custom/modules/'. $this->module_dir . '/Ext/Vardefs/vardefs.ext.php');
			}

		}


        //load up field_arrays from CacheHandler;
        if(empty($this->list_fields)) $this->list_fields = LoadCachedArray($this->module_dir, $this->object_name, 'list_fields');
        if(empty($this->column_fields)) $this->column_fields = LoadCachedArray($this->module_dir, $this->object_name, 'column_fields');
        if(empty($this->required_fields)) $this->required_fields = LoadCachedArray($this->module_dir, $this->object_name, 'required_fields');

		if(isset($dictionary[$this->object_name])){
				$this->field_name_map = $dictionary[$this->object_name]['fields'];
				$this->field_defs =	$dictionary[$this->object_name]['fields'];

			if(isset($dictionary[$this->object_name]['optimistic_locking']) && $dictionary[$this->object_name]['optimistic_locking']){
				$this->optimistic_lock=true;
			}
		}

		//setup custom fields
		if(!isset($this->custom_fields))$this->setupCustomFields($this->module_dir);
		$loaded_defs[$this->object_name]['column_fields'] =& $this->column_fields;
		$loaded_defs[$this->object_name]['list_fields'] =& $this->list_fields;
		$loaded_defs[$this->object_name]['required_fields'] =& $this->required_fields;
		$loaded_defs[$this->object_name]['field_name_map'] =& $this->field_name_map;
		$loaded_defs[$this->object_name]['field_defs'] =& $this->field_defs;

		}else{

			$this->column_fields =& $loaded_defs[$this->object_name]['column_fields'] ;
			$this->list_fields =& $loaded_defs[$this->object_name]['list_fields'];
			$this->required_fields =& $loaded_defs[$this->object_name]['required_fields'];
			$this->field_name_map =& $loaded_defs[$this->object_name]['field_name_map'];
			$this->field_defs =& $loaded_defs[$this->object_name]['field_defs'];
			$this->bean->added_custom_field_defs = true;
			if(!isset($this->custom_fields)){
				 $this->setupCustomFields($this->module_dir, false);
			}
			if(!empty($dictionary[$this->object_name]['optimistic_locking'])){
				$this->optimistic_lock=true;
			}
		}

	}

    function getObjectName(){
        if ($this->object_name)
        	return $this->object_name;

        // This is a quick way out. The generated metadata files have the table name
        // as the key. The correct way to do this is to override this function
        // in bean and return the object name. That requires changing all the beans
        // as well as put the object name in the generator.
        return $this->table_name;
    }

    /* returns the field definitions for fields that have the
     * audited property and is set to true. before calling this function check whether audit
     * has been enabled for the table/module or not. use is_AuditEnabled() function for that
     * purpose.
     */
    function getAuditEnabledFieldDefinitions() {

      	if (!isset($this->audit_enabled_fields)) {

			$this->audit_enabled_fields=array();
			foreach ($this->field_defs as $field => $properties) {

				if (



					(isset($properties['Audited']) and $properties['Audited'] == true) or
					(isset($properties['audited']) and $properties['audited'] == true))  {
					$this->audit_enabled_fields[$field]=$properties;
				}

			}
			//add custom fields to array.
			if(isset($this->custom_fields)) {
			   $custom_fields=$this->custom_fields->getAvailableFields();
			   foreach ($custom_fields as $field=>$properties) {
					if ((isset($properties['Audited']) and $properties['Audited'] == 1) or
						(isset($properties['audited']) and $properties['audited'] == 1))  {
						$this->audit_enabled_fields[$field]=$properties;
					}
			   }
			}

      	}
		return $this->audit_enabled_fields;
    }

 	function is_AuditEnabled(){
        global $dictionary;
        if (isset($dictionary[$this->getObjectName()]['audited'])) {
        	return $dictionary[$this->getObjectName()]['audited'];
        } else {
        	return false;
        }
    }
    function get_audit_table_name() {
    	return $this->getTableName().'_audit';
    }

	function create_audit_table() {
		global $dictionary;
		$table_name=$this->get_audit_table_name();

		require('metadata/audit_templateMetaData.php');

		$fieldDefs = $dictionary['audit']['fields'];

		$sql=$this->dbManager->helper->createTableSQLParams($table_name, $fieldDefs, array());

		$msg = "Error creating table: ".$table_name. ":";
		$this->dbManager->executeQuery($sql, $msg);
	}

    function getTableName(){
        global $dictionary;
		if(isset($this->table_name)){
			return $this->table_name;
		}
        return $dictionary[$this->getObjectName()]['table'];
    }

    function getFieldDefinitions(){
       return $this->field_defs;
    }

    function getIndices(){
        global $dictionary;
        if(isset($dictionary[$this->getObjectName()]['indices'])){
        	return $dictionary[$this->getObjectName()]['indices'];
        }
        return array();
    }

    function getFieldDefinition($name){

        return $this->field_defs[$name];
    }

    function getPrimaryFieldDefinition(){
    	$def = $this->getFieldDefinition("id");
    	if (!$def) $def = $this->getFieldDefinition(0);
    	return $def;
    }

    function getFieldValue($name){
        if (!isset($this->$name)) {
           return FALSE;
        }

        return $this->$name;
    }


	function removeRelationshipMeta($key,$db,$tablename,$dictionary,$module_dir) {
		//load the module dictionary if not supplied.
		if ((!isset($dictionary) or empty($dictionary)) && !empty($module_dir)) {
			if ($key == 'tracker') {
	    		$filename='metadata/trackerMetaData.php';
			} else {
				$filename='modules/'. $module_dir . '/vardefs.php';
			}
			if(file_exists($filename)){
				include($filename);
			}
		}
        if (!is_array($dictionary) or !array_key_exists($key, $dictionary)){
           $GLOBALS['log']->fatal("removeRelationshipMeta: Metadata for table ".$tablename. " does not exist");
           display_notice("meta data absent for table ".$tablename." keyed to $key ");
        }
        else {
			if (isset($dictionary[$key]['relationships'])) {
				$RelationshipDefs = $dictionary[$key]['relationships'];

			 	foreach ($RelationshipDefs as $rel_name) {
					Relationship::delete($rel_name,$db);
			 	}
			}
        }
	}

	/* This method has been deprecated. Use:
	 * removeRelationshipMeta()
	 */
    function remove_relationship_meta($key,$db,$log,$tablename,$dictionary,$module_dir) {
    	SugarBean::removeRelationshipMeta($key,$db,$tablename,$dictionary,$module_dir);
    }


    /* This method populates the relationship meta for the current bean. This method is called during setup.
     * This method is used statically to create relationship meta data for many-to-many tables.
     * Parameters:
     * 	key: name of the object.
     * 	dictionary; vardef dictionary for the object.
     * 	db: DB reference to save data.
     *  log: reference to log object.
     *  tablename: table meta being populated for.
     *  dictionary:vardef for the module.
     *  module_dir:module's directiory name, used to load the vardef file, required when dictionary is null.
     */
	function createRelationshipMeta($key,$db,$tablename,$dictionary,$module_dir,$iscustom=false) {
		//load the module dictionary if not supplied.
		if (empty($dictionary) && !empty($module_dir)) {
			if ($key == 'tracker') {
				$filename='metadata/trackerMetaData.php';
			} else {
				if($iscustom) {
					$filename='custom/modules/' . $module_dir . '/Ext/Vardefs/vardefs.ext.php';
				} else {
					if ($key == 'User') {
						// a very special case for the Employees module
						// this must be done because the Employees/vardefs.php does an include_once on
						// Users/vardefs.php
						$filename='modules/Users/vardefs.php';
					} else {
						$filename='modules/'. $module_dir . '/vardefs.php';
					}
				}
			}
			
			if(file_exists($filename)){
				include($filename);
				// cn: bug 7679 - dictionary entries defined as $GLOBALS['name'] not found
				if(empty($dictionary)) {
					$dictionary = $GLOBALS['dictionary'];
				}
			} else {
				$GLOBALS['log']->debug("createRelationshipMeta: no metadata file found");
				return;
			}
		}

        if (!is_array($dictionary) or !array_key_exists($key, $dictionary)){
           $GLOBALS['log']->fatal("createRelationshipMeta: Metadata for table ".$tablename. " does not exist");
           display_notice("meta data absent for table ".$tablename." keyed to $key ");
        }
        else {
			if (isset($dictionary[$key]['relationships'])) {
				$RelationshipDefs = $dictionary[$key]['relationships'];

				$delimiter=',';
			 	foreach ($RelationshipDefs as $rel_name=>$rel_def) {

					//check whether relationship exists or not first.
					if (Relationship::exists($rel_name,$db)) {
						$GLOBALS['log']->debug('Skipping, reltionship already exists '.$rel_name);
					} else {
						//	add Id to the insert statement.
						$column_list='id';
						$value_list="'".create_guid()."'";

				 		//add relationship name to the insert statement.
						$column_list .= $delimiter.'relationship_name';
						$value_list .= $delimiter."'".$rel_name."'";

			 			//todo check whether $rel_def is an array or not.
			 			//for now make that assumption.
				 		//todo specify defaults if meta not defined.
				 		foreach ($rel_def as $key=>$value) {
					 		$column_list.= $delimiter.$key;
					 		$value_list.= $delimiter."'".$value."'";
					 	}

				 		//create the record. todo add error check.
				 		$insert_string = "INSERT into relationships (" .$column_list. ") values (".$value_list.")";
    			 		$db->query($insert_string, true);
					}
			}
        } else {
			//todo
			//log informational message stating no relationships meta was set for this bean.
			}
        }
	}

	/* This method has been deprecated. Use:
	 * createRelationshipMeta() instead
	 */
    function create_relationship_meta($key,&$db,&$log,$tablename,$dictionary,$module_dir) {
		SugarBean::createRelationshipMeta($key,$db,$tablename,$dictionary,$module_dir);
    }


    /* This method searches the fielddefs arrary to find all fields of type link.
     * and foreach field, creates a local variable of type data/Link.php.
     * Implemented interface can be used to fetch or save relationships.
     */
    function load_relationship($rel_name) {

    	$GLOBALS['log']->debug("SugarBean.load_relationships, Loading relationship (".$rel_name.").");

    	if (empty($rel_name)) {
    		$GLOBALS['log']->error("SugarBean.load_relationships, Null relationship name passed.");
    		return false;
    	}
    	$fieldDefs = $this->getFieldDefinitions();

    	//find all definitions of type link.
    	if (!empty($fieldDefs)) {

 			//if rel_name is provided, search the fieldef array keys by name.
			if (array_key_exists($rel_name, $fieldDefs)){

				if (array_search('link',$fieldDefs[$rel_name]) === 'type') {
					//initialize a variable of type Link
    				require_once('data/Link.php');
					$this->$rel_name=new Link($fieldDefs[$rel_name]['relationship'], $this, $fieldDefs[$rel_name]);
					return true;
				}
			} else {
				$GLOBALS['log']->debug("SugarBean.load_relationships, Error Loading relationship (".$rel_name.").");
				return false;
   			}
   		}

   		return false;
    }

    function load_relationships() {

    	$GLOBALS['log']->debug("SugarBean.load_relationships, Loading all relationships of type link.");

    	$linked_fields=$this->get_linked_fields();
    	foreach($linked_fields as $name=>$properties) {
			$this->$name=new Link($properties['relationship'], $this, $properties);
    	}
    }

    /*
     *
     * deleted: 0  adds deleted=0 filter.
     * 		    1  adds deleted=1 filter.
     * 			(anything else) deleted filter is ignored.
     */
    function get_linked_beans($field_name,$bean_name, $sort_array = array(), $begin_index = 0, $end_index = -1, $deleted=0, $optional_where="") {

    	//if bean_name is Case then use aCase
    	if($bean_name=="Case") $bean_name = "aCase";

		//add a references to bean_name if it doe not exist aleady.
		if (!(class_exists($bean_name))) {

			if (isset($GLOBALS['beanList']) && isset($GLOBALS['beanFiles'])) {
				global $beanFiles;
			} else {
				require_once('include/modules.php');
			}
			$bean_file=$beanFiles[$bean_name];
			include_once($bean_file);
		}

    	$this->load_relationship($field_name);
		return $this->$field_name->getBeans(new $bean_name(), $sort_array, $begin_index, $end_index, $deleted, $optional_where);
    }

    function get_linked_fields() {

		$linked_fields=array();

    	require_once('data/Link.php');

    	$fieldDefs = $this->getFieldDefinitions();

    	//find all definitions of type link.
    	if (!empty($fieldDefs)) {
	   		foreach ($fieldDefs as $name=>$properties) {
	   			if (array_search('link',$properties) === 'type') {
	   				$linked_fields[$name]=$properties;
	   			}
   			}
    	}

    	return $linked_fields;
    }

    /** iterates thru all the relationships and deletes relationship for each record.. */
    function delete_linked($id) {

    	$linked_fields=$this->get_linked_fields();

    	foreach ($linked_fields as $name => $value) {
	    	if ($this->load_relationship($name)) {
				$GLOBALS['log']->debug('relationship loaded');
				$this->$name->delete($id);
	    	} else {
	    		$GLOBALS['log']->error('error loading relationship');
	    	}
    	}
    }

    /** create the appropriate database tables for this bean */
    function create_tables()
    {
        global $dictionary;

        $key = $this->getObjectName();
        if (!array_key_exists($key, $dictionary)){
           $GLOBALS['log']->fatal("create_tables: Metadata for table ".$this->table_name. " does not exist");
           display_notice("meta data absent for table ".$this->table_name." keyed to $key ");
        }
        else {

        	if(!$this->db->tableExists($this->table_name)){
            	$this->dbManager->createTable($this);
            	if($this->bean_implements('ACL')){
            		ACLAction::addActions($this->module_dir);
            	}
        	}else{
        		echo "Table Already Exists : $this->table_name<br>";
        	}

        }
    }

    /** delete the database tables for this bean */
    function drop_tables()
    {
        global $dictionary;
        $key = $this->getObjectName();
        if (!array_key_exists($key, $dictionary)){
           $GLOBALS['log']->fatal("drop_tables: Metadata for table ".$this->table_name. " does not exist");
           echo "meta data absent for table ".$this->table_name."<br>\n";
        } else {

        	if ($this->db->tableExists($this->table_name))
	        	$this->dbManager->dropTable($this);
	        	if ($this->db->tableExists($this->table_name. '_cstm')){
	        	    $this->dbManager->dropTableName($this->table_name. '_cstm');
	        	    DynamicField::deleteCache();
	        	}
        }
    }


		function setupCustomFields($module_name, $clean_load=true){
			$this->custom_fields =& new DynamicField($module_name);
			$this->custom_fields->bean =& $this;
			$this->custom_fields->setup(null, $clean_load && empty($this->bean->added_custom_field_defs));

	}

	/**
	 * Save the bean.  All changes to this bean will be recorded in the data store.
	 */
	/**
	* This method implements a generic insert and update logic for any SugarBean
	* This method only works for subclasses that implement the same variable names.
	* This method uses the presence of an id field that is not null to signify and update.
	* The id field should not be set otherwise.
	* todo - Add support for field type validation and encoding of parameters.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/

	function save($check_notify = FALSE)
	{
		global $timedate;
		global $current_user, $action;
		$isUpdate = true;
		if(empty($this->id))
		{
			$isUpdate = false;
		}

		if ( $this->new_with_id == true )
		{
			$isUpdate = false;
		}
		if(empty($this->date_modified) || $this->update_date_modified){
			$this->date_modified = gmdate("Y-m-d H:i:s");
		}


		if($this->optimistic_lock && !isset($_SESSION['o_lock_fs'])){

			if(isset($_SESSION['o_lock_id']) && $_SESSION['o_lock_id'] == $this->id && $_SESSION['o_lock_on'] == $this->object_name){

				 if($action == 'Save' && $isUpdate && isset($this->modified_user_id) && $this->has_been_modified_since($_SESSION['o_lock_dm'], $this->modified_user_id)){

			 		$_SESSION['o_lock_class'] = get_class($this);
			 		$_SESSION['o_lock_module'] = $this->module_dir;
			 		$_SESSION['o_lock_object'] = $this->toArray();
			 		$saveform = "<form name='save' id='save' method='POST'>";
			 		foreach($_POST as $key=>$arg){
			 			$saveform .= "<input type='hidden' name='". addslashes($key) ."' value='". addslashes($arg) ."'>";
			 		}
			 		$saveform .= "</form><script>document.getElementById('save').submit();</script>";
			 		$_SESSION['o_lock_save'] = $saveform;
			 		header('Location: index.php?module=OptimisticLock&action=LockResolve');
					die();
				 }	else{
							unset ($_SESSION['o_lock_object']);
							unset ($_SESSION['o_lock_id']);
							unset ($_SESSION['o_lock_dm']);
					 }
			}
		}else{
			if(isset($_SESSION['o_lock_object']))	{ unset ($_SESSION['o_lock_object']); }
			if(isset($_SESSION['o_lock_id']))		{ unset ($_SESSION['o_lock_id']); }
			if(isset($_SESSION['o_lock_dm']))		{ unset ($_SESSION['o_lock_dm']); }
			if(isset($_SESSION['o_lock_fs']))		{ unset ($_SESSION['o_lock_fs']); }
			if(isset($_SESSION['o_lock_save']))		{ unset ($_SESSION['o_lock_save']); }
		}

		if($this->update_modified_by)
		{
			$this->modified_user_id = 1;

			if (!empty($current_user))
			{
				$this->modified_user_id = $current_user->id;
			}
		}

		if ($this->deleted != 1) $this->deleted = 0;

		if($isUpdate)
		{
			$query = "Update ";
		}
		else
		{
			if (empty($this->date_entered))
			{
				$this->date_entered = $this->date_modified;
			}




			if($this->set_created_by == true){
            	// created by should always be this user
				$this->created_by = (isset($current_user)) ? $current_user->id : "";
			}


			if($this->new_schema &&
			$this->new_with_id == false)
			{
				$this->id = create_guid();
			}

			$query = "INSERT into ";
		}









		if($isUpdate && !$this->update_date_entered){
			unset($this->date_entered);
		}



		// call the custom business logic
		$custom_logic_arguments['check_notify'] = $check_notify;
		$this->call_custom_logic("before_save", $custom_logic_arguments);
		unset($custom_logic_arguments);

		// use the db independent query generator

        $this->check_date_relationships_save();



        //construct the SQL to create the audit record if auditing is enabled.
		$dataChanges=array();
        if ($this->is_AuditEnabled()) {
        	if ($isUpdate && !isset($this->fetched_row)) {
        		$GLOBALS['log']->debug('Auditing: Retrieve was not called, audit record will not be created.');
        	} else {
        		$dataChanges=$this->dbManager->helper->getDataChanges($this);
        	}
        }


		// send assignment notifications AND invites for activities
		if($check_notify) { // cn: bug 5795 - no invites sent to Contacts
			require_once("modules/Administration/Administration.php");
			$admin = new Administration();
			$admin->retrieveSettings();
			$sendNotifications = false;

			if ($admin->settings['notify_on']) {
				$GLOBALS['log']->info("Notifications: user assignment has changed, checking if user receives notifications");
				$sendNotifications = true;
			} elseif(isset($_REQUEST['send_invites']) && $_REQUEST['send_invites'] == 1) {
				// cn: bug 5795 Send Invites failing for Contacts
				$sendNotifications = true;
			} else {
				$GLOBALS['log']->info("Notifications: not sending e-mail, notify_on is set to OFF");
			}


			if($sendNotifications == true) {
				$notify_list = $this->get_notification_recipients();
				foreach ($notify_list as $notify_user) {
					$this->send_assignment_notifications($notify_user, $admin);
				}
			}
		}

       	if(isset($this->custom_fields)){
   			 $this->custom_fields->bean =& $this;
   			 $this->custom_fields->save($isUpdate);
       	}
        if ($this->db->dbType == "oci8"){








        }
        if ($this->db->dbType == 'mysql')
        {
    		// write out the SQL statement.
	       	$query .= $this->table_name." set ";

    		$firstPass = 0;

    		foreach($this->field_defs as $field=>$value) {
	    		if(!isset($value['source']) || $value['source'] == 'db') {
	    			// Do not write out the id field on the update statement.
	    			// We are not allowed to change ids.
	    			if($isUpdate && ('id' == $field)) continue;
	    			//custom fields handle there save seperatley
	    			if(isset($this->field_name_map) && !empty($this->field_name_map[$field]['custom_type']))
	    				continue;

	    			// Only assign variables that have been set.
	    			if(isset($this->$field)) {
	    				if(strlen($this->$field) <= 0) {
	    					if(!$isUpdate && isset($value['default']) && (strlen($value['default']) > 0)) {
	    						$this->$field = $value['default'];
	    					}
	    					else {
	    						$this->$field = null;
	    					}
	    				}
	    				// Try comparing this element with the head element.
	    				if(0 == $firstPass) $firstPass = 1;
	    				else $query .= ", ";

						if(is_null($this->$field)) {
							$query .= $field."=null";
						}
						else {
	    					$query .= $field."='".PearDatabase::quote(from_html($this->$field))."'";
	    				}
	    			}
    			}
    		}

    		if($isUpdate)
    		{
    			$query = $query." WHERE ID = '$this->id'";
    			$GLOBALS['log']->info("Update $this->object_name: ".$query);
    		} else  {
    			$GLOBALS['log']->info("Insert: ".$query);
    		}

            $GLOBALS['log']->info("Save: $query");
    		$this->db->query($query, true);
        }

        //process if type is set to mssql
		if ($this->db->dbType == 'mssql')
		{

			if($isUpdate)
			{
				// build out the SQL UPDATE statement.
				$query = "UPDATE " . $this->table_name." SET ";
				$firstPass = 0;

				foreach($this->field_defs as $field=>$value) {
					if(!isset($value['source']) || $value['source'] == 'db') {
						// Do not write out the id field on the update statement.
						// We are not allowed to change ids.
						if($isUpdate && ('id' == $field)) continue;
                        
                        // If the field is an auto_increment field, then we shouldn't be setting it.  This was added
                        // specially for Bugs and Cases which have a number associated with them. 
                        if ($isUpdate && isset($this->field_name_map[$field]['auto_increment']) && $this->field_name_map[$field]['auto_increment'] == true)
                            continue;
                        
                        //custom fields handle their save seperatley
						if(isset($this->field_name_map) && !empty($this->field_name_map[$field]['custom_type']))
							continue;

						// Only assign variables that have been set.
						if(isset($this->$field)) {
							if(strlen($this->$field) <= 0) {
								if(!$isUpdate && isset($value['default']) && (strlen($value['default']) > 0)) {
									$this->$field = $value['default'];
								}
								else {
									$this->$field = null;
								}
							}
							// Try comparing this element with the head element.
							if(0 == $firstPass) $firstPass = 1;
							else $query .= ", ";

							if(is_null($this->$field)) {
								$query .= $field."=null";
							}
							else {
								$query .= $field."='".PearDatabase::quote(from_html($this->$field))."'";
							}
						}
					}
				}
				$query = $query." WHERE ID = '$this->id'";
    			$GLOBALS['log']->info("Update $this->object_name: ".$query);
			}
			else
			{
	              $colums = array();
                  $values = array();
				foreach($this->field_defs as $field=>$value)
				{

						if(!isset($value['source']) || $value['source'] == 'db')
						{
						// Do not write out the id field on the update statement.
						// We are not allowed to change ids.
						//if($isUpdate && ('id' == $field)) continue;
						//custom fields handle there save seperatley

						if(isset($this->field_name_map) && !empty($this->field_name_map[$field]['custom_type']))
							continue;


						// Only assign variables that have been set.
						if(isset($this->$field))
						{
                            //trim the value in case empty space is passed in.
                            //this will allow default values set in db to take effect, otherwise
                            //will insert blanks into db
                                $trimmed_field = trim($this->$field);
                                //if this value is empty, do not include the field value in statement
                                if($trimmed_field ==''){
                                    continue;
                                }
							$values[] = "'".PearDatabase::quote(from_html($this->$field))."'";
                            $columns[] = $field;
						}
					}
				}
                // build out the SQL INSERT statement.
                $query = "INSERT INTO $this->table_name (" .implode("," , $columns). " ) VALUES ( ". implode("," , $values). ')';
    			$GLOBALS['log']->info("Insert: ".$query);
			}

            $GLOBALS['log']->info("Save: $query");
    		$this->db->query($query, true);
        }


        if (!empty($dataChanges) && is_array($dataChanges)) {
        	foreach ($dataChanges as $change) {
       			$this->dbManager->helper->save_audit_records($this,$change);
        	}
        }
		// let subclasses save related field changes
		$this->save_relationship_changes($isUpdate);

		//if track_on_save is set ot true create the track record.
		if (isset($this->track_on_save) && $this->track_on_save == true && isset($this->module_dir)) {
			$this->track_view($current_user->id, $this->module_dir);
		}
		return $this->id;
	}

	function has_been_modified_since($date, $modified_user_id){
		global $current_user;
		if (isset($current_user)){
			$query = "SELECT date_modified FROM $this->table_name WHERE id='$this->id' AND  modified_user_id != '$current_user->id' AND (modified_user_id != '$modified_user_id' OR date_modified > " . db_convert("'".$date."'", 'datetime') . ')';
			$result = $this->db->query($query);

			if($this->db->fetchByAssoc($result)){

				return true;
			}
		}

		return false;
	}
	/**
	* This function determines which users received a notification.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function get_notification_recipients()
	{
		$notify_user = new User();
		$notify_user->retrieve($this->assigned_user_id);
		$this->new_assigned_user_name = $notify_user->first_name.' '.$notify_user->last_name;

		$GLOBALS['log']->info("Notifications: recipient is $this->new_assigned_user_name");

		$user_list = array($notify_user);
		return $user_list;
	}

	/**
	* This function handles sending out email notifications when items are first assigned to users.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function send_assignment_notifications($notify_user, $admin)
	{
		global $current_user;
		if (( $this->object_name == 'Meeting' || $this->object_name == 'Call' ) || $notify_user->receive_notifications )
		{
			if (empty($notify_user->email1) && empty($notify_user->email2)) {
				$GLOBALS['log']->warn("Notifications: no e-mail address set for user {$notify_user->user_name}, cancelling send");
			}
			else {
				$notify_mail = $this->create_notification_email($notify_user);
				if ($admin->settings['mail_sendtype'] == "SMTP") {
					$notify_mail->Mailer = "smtp";
					$notify_mail->Host = $admin->settings['mail_smtpserver'];
					$notify_mail->Port = $admin->settings['mail_smtpport'];
					if ($admin->settings['mail_smtpauth_req']) {
						$notify_mail->SMTPAuth = TRUE;
						$notify_mail->Username = $admin->settings['mail_smtpuser'];
						$notify_mail->Password = $admin->settings['mail_smtppass'];
					}
				}

				if (empty($admin->settings['notify_send_from_assigning_user'])) {
					$notify_mail->From = $admin->settings['notify_fromaddress'];
					$notify_mail->FromName = (empty($admin->settings['notify_fromname'])) ? "" : $admin->settings['notify_fromname'];
				}
				else {
					// Send notifications from the current user's e-mail (if set)
					$from_address = !empty($current_user->email1) ? $current_user->email1 : $admin->settings['notify_fromaddress'];
					$notify_mail->From = $from_address;
					$from_name = !empty($admin->settings['notify_fromname']) ? $admin->settings['notify_fromname'] : "";
					if($current_user->getPreference('mail_fromname') != '') {
						$from_name = $current_user->getPreference('mail_fromname');
					}
					$notify_mail->FromName = $from_name;
				}

				if(!$notify_mail->Send()) {
					$GLOBALS['log']->warn("Notifications: error sending e-mail (method: {$notify_mail->Mailer}), (error: {$notify_mail->ErrorInfo})");
				}
				else {
					$GLOBALS['log']->info("Notifications: e-mail successfully sent");
				}
			}
		}
	}

	/**
	* This function handles sending out email notifications when items are first assigned to users.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function create_notification_email($notify_user) {
		global $sugar_version;
		global $sugar_config;
		global $app_list_strings;
		global $current_user;
		global $locale;

		require_once("XTemplate/xtpl.php");
		require_once("include/SugarPHPMailer.php");

		$notify_address = (empty($notify_user->email1)) ? from_html($notify_user->email2) : from_html($notify_user->email1);
		$notify_name = (empty($notify_user->first_name)) ? from_html($notify_user->user_name) : from_html($notify_user->first_name . " " . $notify_user->last_name);
		$GLOBALS['log']->debug("Notifications: user has e-mail defined");

		$notify_mail = new SugarPHPMailer();
		$notify_mail->AddAddress($notify_address, $notify_name);

		if (empty($_SESSION['authenticated_user_language'])) {
			$current_language = $sugar_config['default_language'];
		} else {
			$current_language = $_SESSION['authenticated_user_language'];
		}

		$xtpl = new XTemplate("include/language/{$current_language}.notify_template.html");

		$template_name = $this->object_name;

		$this->current_notify_user = $notify_user;

		if(in_array('set_notification_body', get_class_methods($this))) {
			$xtpl = $this->set_notification_body($xtpl, $this);
		} else {
			$xtpl->assign("OBJECT", $this->object_name);
			$template_name = "Default";
		}

		$xtpl->assign("ASSIGNED_USER", $this->new_assigned_user_name);
		$xtpl->assign("ASSIGNER", $current_user->name);
		$port = '';
		if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
			$port = $_SERVER['SERVER_PORT'];
		}
		
		$httpHost = $_SERVER['HTTP_HOST'];
        if($colon = strpos($httpHost, ':')) {
			$httpHost       = substr($httpHost, 0, $colon);
		}

        $parsedSiteUrl  = parse_url($sugar_config['site_url']);
		$host                   = ($parsedSiteUrl['host'] != $httpHost) ? $httpHost : $parsedSiteUrl['host'];
		if(!isset($parsedSiteUrl['port'])){
			$parsedSiteUrl['port'] = 80;
		}
		$port                   = ($parsedSiteUrl['port'] != 80) ? ":".$parsedSiteUrl['port'] : '';
		$path                   = $parsedSiteUrl['path'];
		$cleanUrl               = "{$parsedSiteUrl['scheme']}://{$host}{$port}{$path}";		
		
		$xtpl->assign("URL", $cleanUrl."/index.php?module={$this->module_dir}&action=DetailView&record={$this->id}");		
		$xtpl->assign("SUGAR", "Sugar Suite v{$sugar_version}");
		$xtpl->parse($template_name);
		$xtpl->parse($template_name . "_Subject");

		$notify_mail->Body = from_html(trim($xtpl->text($template_name)));
		$notify_mail->Subject = from_html($xtpl->text($template_name . "_Subject"));

		// cn: bug 8568 encode notify email in User's outbound email encoding
		$notify_mail->prepForOutbound();
		
		return $notify_mail;
	}

	/**
	* This function is a good location to save changes that have been made to a relationship.
	* This should be overriden in subclasses that have something to save.
	* param $is_update true if this save is an update.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
	function save_relationship_changes($is_update)
	{
    	if (isset($this->relationship_fields) && is_array($this->relationship_fields)) {
    		foreach ($this->relationship_fields as $id=>$rel_name) {

	    		if(!empty($this->$id)) {
					$this->load_relationship($rel_name);
					$this->$rel_name->add($this->$id);
		    	}
		    	else {
					//if before value is not empty the attempt to delete relationship.
		    		if(!empty($this->rel_fields_before_value[$id])) {
		    			$GLOBALS['log']->debug('Attempting to remove the relationship record, using relationship attribute'.$rel_name);
						$this->load_relationship($rel_name);
						$this->$rel_name->delete($this->id,$this->rel_fields_before_value[$id]);
		    		}
		    	}
    		}
    	}

	}

	/**
	* This function retrieves a record of the appropriate type from the DB.
	* It fills in all of the fields from the DB into the object it was called on.
	* param $id - If ID is specified, it overrides the current value of $this->id.  If not specified the current value of $this->id will be used.
	* returns this - The object that it was called apon or null if exactly 1 record was not found.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/

	function check_date_relationships_load(){
		global $disable_date_format;
		if(!empty($disable_date_format)){
			return;
		}
		global $timedate;
		if (empty($timedate)) $timedate=new TimeDate();

		if(empty($this->field_defs)) {
			return;
		}
		foreach($this->field_defs as $fieldDef) {
			$field = $fieldDef['name'];
			if(!key_exists($field, $this->processed_dates_times)) {
				$this->processed_dates_times[$field] = '1';

				if($field == 'date_modified' || $field == 'date_entered') {
					if(!empty($this->$field)) {
						$this->$field = $timedate->to_display_date_time($this->$field);
					}
      			} elseif(!empty($this->$field) && isset($this->field_name_map[$field]['type'])) {
					$type = $this->field_name_map[$field]['type'];

					if($type == 'relate'  && isset($this->field_name_map[$field]['custom_type'])) {
						$type = $this->field_name_map[$field]['custom_type'];
					}

					if($type == 'date') {
						$this->$field = from_db_convert($this->$field, 'date');

						if($this->$field == '0000-00-00') {
							$this->$field = '';
						} elseif(!empty($this->field_name_map[$field]['rel_field'])) {
							$rel_field = $this->field_name_map[$field]['rel_field'];

							if(!empty($this->$rel_field)) {
								$this->$rel_field=from_db_convert($this->$rel_field, 'time');
								$mergetime = $timedate->merge_date_time($this->$field,$this->$rel_field);
								$this->$field = $timedate->to_display_date($mergetime);
								$this->$rel_field = $timedate->to_display_time($mergetime);
							}
						} else {
							$this->$field = $timedate->to_display_date($this->$field, false);
						}
					} elseif($type == 'datetime') {
						if($this->$field == '0000-00-00 00:00:00') {
							$this->$field = '';
						} else {
							$this->$field = $timedate->to_display_date_time($this->$field);
						}
					} elseif($type == 'time') {
						if($this->$field == '00:00:00') {
							$this->$field = '';
						} else {
							//$this->$field = from_db_convert($this->$field, 'time');
							if(empty($this->field_name_map[$field]['rel_field'])) {
								$this->$field = $timedate->to_display_time($this->$field,true, false);
							}
						}
					}
				}
			}
		}
	}

	function check_date_relationships_save(){
		global $disable_date_format, $timedate;
		if(!empty($disable_date_format)){
			return;
		}

		if($this->process_save_dates) {
			if(empty($this->field_defs)) {
				return;
			}

			foreach($this->field_defs as $fieldDef) {
				if ( !isset($this->$fieldDef['name']) || $fieldDef == 'date_modified' ||  $fieldDef == 'date_entered' ) {
					continue;
				}

				$field = $fieldDef['name'];
				if($field == 'date_modified' || $field == 'date_entered'){
					//do nothing we need to do this so we can keep seconds on the time
				} else if(!empty($this->$field)) {
					if(empty($this->field_name_map[$field]['type'])){
						$type = 'varchar';
					}else{
						$type = $this->field_name_map[$field]['type'];
					}

					if($type == 'relate'  && isset($this->field_name_map[$field]['custom_type'])) {
						$type = $this->field_name_map[$field]['custom_type'];
					}

					if($type == 'date') {
						if(!empty($this->field_name_map[$field]['rel_field'])) {
							$rel_field = $this->field_name_map[$field]['rel_field'];

							if(empty($this->$rel_field)) {
								$this->$rel_field = $timedate->to_display_time('12:00:00', true, false);
							}

							$mergetime = $timedate->merge_date_time($this->$field,$this->$rel_field);
							$this->$field = $timedate->to_db_date($mergetime);
							$this->$rel_field = $timedate->to_db_time($mergetime);
						} else {
							$this->$field = $timedate->to_db_date($this->$field, false);
						}
					} elseif($type == 'datetime') {
						$this->$field = $timedate->to_db($this->$field);
					} elseif($type == 'time') {
						if(empty($this->field_name_map[$field]['rel_field'])) {
							$this->$field = $timedate->to_db_time($this->$field, false);
						}
					}
				}
			} // end foreach()
		}
	}

	/*
	 * unformat all fields for vardefs, currently only for numbers
	 */
	function unformat_all_fields() {
		global $disable_num_format, $current_user;
		if((!empty($disable_num_format) && $disable_num_format) || empty($current_user)) return;
		// turned off at bean level?
		if((!empty($this->disable_num_format) && $this->disable_num_format) || empty($this->field_defs)) return;
		foreach($this->field_defs as $fieldDef) {
			$type = (empty($fieldDef['type']) ? $fieldDef['dbType'] : $fieldDef['type']);
			if(in_array($type, array('int', 'float', 'double', 'uint', 'ulong', 'long', 'short', 'tinyint', 'currency', 'decimal'))) {  // is number?
				if (!empty($fieldDef['disable_num_format']) && $fieldDef['disable_num_format']) continue; // turned off at field level?
				$field = $fieldDef['name'];
				if(!empty($this->$field)) {
					$this->$field = unformat_number($this->$field);
				}
			}
		}

	}

	/*
	 * format all fields for vardefs, currently only for numbers
	 */
	function format_all_fields() {
		global $disable_num_format;
		global $current_user;
		global $locale;
		
		if((!empty($disable_num_format) && $disable_num_format) || empty($current_user)) 
			return;

		foreach($this->field_defs as $fieldDef) {
			$type = (empty($fieldDef['type']) ? $fieldDef['dbType'] : $fieldDef['type']);

			if(in_array($type, array('int', 'float', 'double', 'uint', 'ulong', 'long', 'short', 'tinyint', 'currency', 'decimal'))) {  // is number?
				if (!empty($fieldDef['disable_num_format']) && $fieldDef['disable_num_format']) 
					continue; // turned off at field level?
				$field = $fieldDef['name'];
				if(!empty($this->$field)) {
					if(in_array($type, array('int', 'uint', 'ulong', 'long', 'short', 'tinyint')))
						$this->$field = format_number($this->$field, 0, 0);
					else {
						$this->$field = format_number($this->$field, $locale->getPrecedentPreference('default_currency_significant_digits'), $locale->getPrecedentPreference('default_currency_significant_digits'));
					}
				}
			}
		}
		$this->number_formatting_done = true;
	}

	function retrieve($id = -1, $encode=true) {

		if ($id == -1) {
			$id = $this->id;
		}

		if(isset($this->custom_fields))
		{

			$custom_join = $this->custom_fields->getJOIN();

		}else $custom_join = false;

		if($custom_join){
			$query = "SELECT $this->table_name.*". $custom_join['select']. " FROM $this->table_name ";
		}else{
			$query = "SELECT $this->table_name.* FROM $this->table_name ";
		}









		if($custom_join){
			$query .= ' ' . $custom_join['join'];
		}
		$query .= " WHERE $this->table_name.id = '$id' ";
		$GLOBALS['log']->debug("Retrieve $this->object_name : ".$query);
        //requireSingleResult has beeen deprecated.
		//$result = $this->db->requireSingleResult($query, true, "Retrieving record by id $this->table_name:$id found ");
		$result = $this->db->limitQuery($query,0,1,true, "Retrieving record by id $this->table_name:$id found ");
		if(empty($result)) {
			return null;
		}

		$row = $this->db->fetchByAssoc($result, -1, $encode);
		if(empty($row)){
			return null;
		}

		//make copy of the fetched row for construction of audit record and for business logic/workflow
		$this->fetched_row=$row;

		$this->populateFromRow($row);

		global $module, $action;
		//Just to get optimistic locking working for this release
		if($this->optimistic_lock && $module == $this->module_dir && $action =='EditView' ){
			$_SESSION['o_lock_id']= $id;
			$_SESSION['o_lock_dm']= $this->date_modified;
			$_SESSION['o_lock_on'] = $this->object_name;
		}

		$this->processed_dates_times = array();
		$this->check_date_relationships_load();

		if($custom_join){
			$this->custom_fields->fill_relationships();
		}
		$this->fill_in_additional_detail_fields();

		//make a copy of fields in the relatiosnhip_fields array. these field values will be used to
		//clear relatioship.
    	if (isset($this->relationship_fields) && is_array($this->relationship_fields)) {
    		foreach ($this->relationship_fields as $rel_id=>$rel_name) {
    			if (isset($this->$rel_id))
					$this->rel_fields_before_value[$rel_id]=$this->$rel_id;
				else
					$this->rel_fields_before_value[$rel_id]=null;
    		}
    	}

		// call the custom business logic
		$custom_logic_arguments['id'] = $id;
		$custom_logic_arguments['encode'] = $encode;
		$this->call_custom_logic("after_retrieve", $custom_logic_arguments);
		unset($custom_logic_arguments);

		return $this;
	}

	function populateFromRow($row){
//TODO loop through vardefs instead
//runs into an issue when populating from field_defs for users - corrupts user prefs

		foreach($this->field_defs as $field=>$field_value)
		{
			if($field == 'user_preferences' && $this->module_dir == 'Users')continue;




			$rfield = $field; // fetch returns it in lowercase only
			if(isset($row[$rfield]))
			{
				$this->$field = $row[$rfield];
			}else{
				$this->$field = '';
			}
		}
	}



	/**
	* Add any required joins to the list count query.  The joins are required if there
	* is a field in the $where clause that needs to be joined.
	*/
	function add_list_count_joins(&$query, $where)
	{
		$custom_join = $this->custom_fields->getJOIN();
		if($custom_join){
  				$query .= $custom_join['join'];
		}

	}

	/**
	 * Changes the select expression of the given query to be 'count(*)' so you
	 * can get the number of items the query will return.  This is used to
	 * populate the upper limit on ListViews.
	 */
	function create_list_count_query($query)
	{
		// change the select expression to 'count(*)'
		$pattern = '/SELECT(.*?)(\s){1}FROM(\s){1}/is';  // ignores the case
		$replacement = 'SELECT count(*) c FROM ';
		$modified_select_query = preg_replace($pattern, $replacement, $query);

		// remove the 'order by' clause which is expected to be at the end of the query
		$pattern = '/\sORDER BY.*/is';  // ignores the case
		$replacement = '';
		$modified_order_by_query = preg_replace($pattern, $replacement, $modified_select_query);

		return $modified_order_by_query;
	}

	/**
	* This function returns a paged list of the current object type.  It is intended to allow for
	* hopping back and forth through pages of data.  It only retrieves what is on the current page.
	* This method must be called on a new instance.  It trashes the values of all the fields in the current one.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function get_list($order_by = "", $where = "", $row_offset = 0, $limit=-1, $max=-1, $show_deleted = 0) {
		$GLOBALS['log']->debug("get_list:  order_by = '$order_by' and where = '$where' and limit = '$limit'");
		if(isset($_SESSION['show_deleted'])){
			$show_deleted = 1;
		}
		$order_by=$this->process_order_by($order_by, null);

		if($this->bean_implements('ACL') && ACLController::requireOwner($this->module_dir, 'list') ){
			global $current_user;
			$owner_where = $this->getOwnerWhere($current_user->id);
			if(empty($where)){
				$where = $owner_where;
			}else{
				$where .= ' AND '.  $owner_where;
			}
		}
		$query = $this->create_list_query($order_by, $where, $show_deleted);

		return $this->process_list_query($query, $row_offset, $limit, $max, $where);
	}

	/**
	* This function prefixes column names with this bean's table name.
	* This call can be ignored for  mysql since it does a better job than Oracle in resolving ambiguity.
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
 	function process_order_by ($order_by, $submodule) {
 		if (empty($order_by)) return $order_by;
 		$bean_queried = "";
 		//submodule is empty,this is for list object in focus
 		if (empty($submodule)){
 			$bean_queried = &$this;
 		}else{
 			//submodule is set, so this is for subpanel, use submodule
 			$bean_queried = $submodule;
 		}
 		$elements = explode(',',$order_by);
 		foreach ($elements as $key=>$value) {

 			if (strchr($value,'.') === false) {
				//value might have ascending and descending decorations
				$list_column = explode(' ',trim($value));
				if (isset($list_column[0])) {
					$list_column_name=trim($list_column[0]);
 					if (isset($bean_queried->field_defs[$list_column_name])) {
 						$source=isset($bean_queried->field_defs[$list_column_name]['source']) ? $bean_queried->field_defs[$list_column_name]['source']:'db';
 						if (empty($bean_queried->field_defs[$list_column_name]['table']) && $source=='db') {
	 						$list_column[0] = $bean_queried->table_name .".".$list_column[0] ;
 						}
 						$value = implode($list_column,' ');
 					} else {
 						$GLOBALS['log']->debug("process_order_by: ($list_column[0]) does not have a vardef entry.");
 					}
				}
 			}
 			$elements[$key]=$value;
 		}
 		return implode($elements,',');

 	}


	/**
	* This function returns a detail object just like retrieve of the current object type.  It is intended for use in navigation buttons on the DetailView.  It will pass an offset and limit argument to the sql query.
	* This method must be called on a new instance.  It trashes the values of all the fields in the current one.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function get_detail($order_by = "", $where = "",  $offset = 0, $row_offset = 0, $limit=-1, $max=-1, $show_deleted = 0) {
		$GLOBALS['log']->debug("get_detail:  order_by = '$order_by' and where = '$where' and limit = '$limit' and offset = '$offset'");
		if(isset($_SESSION['show_deleted'])){
			$show_deleted = 1;
		}

		if($this->bean_implements('ACL') && ACLController::requireOwner($this->module_dir, 'list') ){
			global $current_user;
			$owner_where = $this->getOwnerWhere($current_user->id);

			if(empty($where)){
				$where = $owner_where;
			}else{
				$where .= ' AND '.  $owner_where;
			}
		}
		$query = $this->create_list_query($order_by, $where, $show_deleted, $offset);

		//Add Limit and Offset to query
		//$query .= " LIMIT 1 OFFSET $offset";

		return $this->process_detail_query($query, $row_offset, $limit, $max, $where, $offset);
	}

	function get_related_list($child_seed,$related_field_name, $order_by = "", $where = "",
		$row_offset = 0, $limit=-1, $max=-1, $show_deleted = 0) {
		global $layout_edit_mode;
		if(isset($layout_edit_mode) && $layout_edit_mode){
			$response = array();
			$child_seed->assign_display_fields($child_seed->module_dir);
			$response['list'] = array($child_seed);
			$response['row_count'] = 1;
			$response['next_offset'] = 0;
			$response['previous_offset'] = 0;

		return $response;
		}
		$GLOBALS['log']->debug("get_related_list:  order_by = '$order_by' and where = '$where' and limit = '$limit'");
		if(isset($_SESSION['show_deleted'])){
			$show_deleted = 1;
		}

		$this->load_relationship($related_field_name);
		$query_array = $this->$related_field_name->getQuery(true);
		$entire_where = $query_array['where'];
		if(!empty($where))
		{
			if(empty($entire_where))
			{
				$entire_where = ' WHERE ' . $where;
			}
			else
			{
				$entire_where .= ' AND ' . $where;
			}
		}

		$query = 'SELECT '.$child_seed->table_name.'.* ' . $query_array['from'] . ' ' . $entire_where;
		if(!empty($order_by))
		{
			$query .= " ORDER BY " . $order_by;
		}

		return $child_seed->process_list_query($query, $row_offset, $limit, $max, $where);
	}

	/**
	 * static function that should return a result in the same format as 'get_related_list'
	 */
	function get_union_related_list($parentbean, $order_by = "", $sort_order='', $where = "",
		$row_offset = 0, $limit=-1, $max=-1, $show_deleted = 0, $subpanel_def)
	{
		$secondary_queries = array();

		global $layout_edit_mode, $beanFiles, $beanList;



		if(isset($_SESSION['show_deleted'])){
			$show_deleted = 1;
		}

		$final_query = '';
		$final_query_rows = '';
		$subpanel_list=array();
		if ($subpanel_def->isCollection()) {
			$subpanel_def->load_sub_subpanels();
			$subpanel_list=$subpanel_def->sub_subpanels;
		} else {
			$subpanel_list[]=$subpanel_def;
		}

		foreach($subpanel_list as $this_subpanel)
		{
			if(!$subpanel_def->isDatasourceFunction())
			{
				if(!empty($final_query))
				{
					$final_query .= " UNION ALL ";
					$final_query_rows .= " UNION ALL ";
				}

				$related_field_name = $this_subpanel->get_data_source_name();
				$parentbean->load_relationship($related_field_name);
				$query_array = $parentbean->$related_field_name->getQuery(true,array(),0,'',true);

				//$ids = $parentbean->$related_field_name->get();
				$table_where = $this_subpanel->get_where();
				$where_definition = $query_array['where'];

				if(!empty($table_where))
				{
					if(empty($where_definition))
					{
						$where_definition = $table_where;
					}
					else
					{
						$where_definition .= ' AND ' . $table_where;
					}
				}

				$submodulename = $this_subpanel->_instance_properties['module'];
				$submoduleclass = $beanList[$submodulename];
				require_once($beanFiles[$submoduleclass]);
				$submodule = new $submoduleclass();
				$subwhere = $where_definition;



				$subwhere = str_replace('WHERE', '', $subwhere);
				$list_fields = $this_subpanel->get_list_fields();
				foreach($list_fields as $list_key=>$list_field){
					if(isset($list_field['usage']) && $list_field['usage'] == 'display_only'){
						unset($list_fields[$list_key]);
					}


				}



				if(!$subpanel_def->isCollection() && isset($list_fields[$order_by]) && isset($submodule->field_defs[$order_by])&& (!isset($submodule->field_defs[$order_by]['source']) || $submodule->field_defs[$order_by]['source'] == 'db')){

					$order_by = $submodule->table_name .'.'. $order_by;

				}
				$table_name = $this_subpanel->table_name;
				$panel_name=$this_subpanel->name;
				$params = array();
				$params['distinct'] = $this_subpanel->distinct_query();

				$params['joined_tables'] = $query_array['join_tables'];
				$params['include_custom_fields'] = !$subpanel_def->isCollection();
				$subquery = $submodule->create_new_list_query('',$subwhere ,$list_fields,$params, 0,'', true,$parentbean);



				$query =  $subquery['select']." , '$panel_name' panel_name ".  $subquery['from'].$query_array['join']. $subquery['where'];
				if(sizeof($subpanel_list) > 1)
				{
					$query = '( '.$query . ' )';
				}
				$select_position=strpos($query_array['select'],"SELECT");
				$distinct_position=strpos($query_array['select'],"DISTINCT");
				if ($select_position !== false && $distinct_position!= false) {
					$query_rows = "( ".substr_replace($query_array['select'],"SELECT count(",$select_position,6). ")" .  $subquery['from_min'].$query_array['join']. $subquery['where'].' )';
				} else {
				  //resort to default behavior.
					$query_rows = "( SELECT count(*)".  $subquery['from_min'].$query_array['join']. $subquery['where'].' )';

				}
				if(!empty($subquery['secondary_select'])){
					 $subquerystring= $subquery['secondary_select'] . $subquery['secondary_from'].$query_array['join']. $subquery['where'];
					if (!empty($subquery['secondary_where'])) {
						if (empty($subquery['where'])) {
							 $subquerystring.=" WHERE " .$subquery['secondary_where'];
						} else {
							 $subquerystring.=" AND " .$subquery['secondary_where'];
						}
					}
					$secondary_queries[]=$subquerystring;
				}
				$final_query .= $query;
				$final_query_rows .= $query_rows;
			}
			else
			{

				$shortcut_function_name = $this_subpanel->get_data_source_name();
				$parameters=$this_subpanel->get_function_parameters();
				if (!empty($parameters)) {
					$final_query = $parentbean->$shortcut_function_name($parameters);
				} else {
					$final_query = $parentbean->$shortcut_function_name();
				}
				$final_query_rows = $final_query;
			}
		}

		if(!empty($order_by))
		{

		    if(!$subpanel_def->isCollection() && !empty($submodule->table_name)){
			     $final_query .= " ORDER BY " .$parentbean->process_order_by($order_by, $submodule);

		    }else{
		        $final_query .= " ORDER BY ". $order_by . ' ';
		    }
			if(!empty($sort_order))
			{
				$final_query .= ' ' .$sort_order;
			}
		}


			if(isset($layout_edit_mode) && $layout_edit_mode)
		{
			$response = array();

			if(!empty($submodule)){
				$submodule->assign_display_fields($submodule->module_dir);
				$response['list'] = array($submodule);
			}else{
				$response['list'] = array();
			}
			$response['parent_data'] = array();
			$response['row_count'] = 1;
			$response['next_offset'] = 0;
			$response['previous_offset'] = 0;

			return $response;
		}

		return $parentbean->process_union_list_query($parentbean, $final_query, $row_offset, $limit, $max, '',$subpanel_def, $final_query_rows, $secondary_queries);
	}


	/**
	* This function returns a full (ie non-paged) list of the current object type.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function get_full_list($order_by = "", $where = "", $check_dates=false, $show_deleted = 0) {
		$GLOBALS['log']->debug("get_full_list:  order_by = '$order_by' and where = '$where'");
		if(isset($_SESSION['show_deleted'])){
			$show_deleted = 1;
		}
		$query = $this->create_list_query($order_by, $where, $show_deleted);
		return $this->process_full_list_query($query, $check_dates);
	}

	function create_list_query($order_by, $where, $show_deleted = 0)
	{

		$custom_join = false;

		if(isset($this->custom_fields))
			$custom_join = $this->custom_fields->getJOIN();
			$query = "SELECT ";

		if($custom_join){
			$query .= " $this->table_name.*". $custom_join['select']. " FROM $this->table_name " . $custom_join['join'];
		}else{
			$query .= " $this->table_name.* FROM $this->table_name ";
		}





		 $where_auto = '1=1';
				if($show_deleted == 0){
                	$where_auto = "$this->table_name.deleted=0";
				}else if($show_deleted == 1){
                	$where_auto = "$this->table_name.deleted=1";
				}
		if($where != "")
		$query .= "where ($where) AND $where_auto";
		else
		$query .= "where $where_auto";

		if(!empty($order_by))
		$query .= " ORDER BY $order_by";


		return $query;
	}

	function create_new_list_query($order_by, $where,$filter=array(),$params=array(), $show_deleted = 0,$join_type='', $return_array = false,$parentbean, $singleSelect = false){
		global $beanFiles, $beanList;
		$selectedFields = array();
        $secondarySelectedFields = array();
		$ret_array = array();
		$distinct = '';
		if($this->bean_implements('ACL') && ACLController::requireOwner($this->module_dir, 'list') ){
			global $current_user;
			$owner_where = $this->getOwnerWhere($current_user->id);
			if(empty($where)){
				$where = $owner_where;
			}else{
				$where .= ' AND '.  $owner_where;
			}
		}
		if(!empty($params['distinct'])){
			$distinct = ' DISTINCT ';
		}
		if(empty($filter)){
			$ret_array['select'] = " SELECT $distinct $this->table_name.* ";
		}else{
			$ret_array['select'] = " SELECT $distinct $this->table_name.id ";
		}
		$ret_array['from'] = " FROM $this->table_name ";
		$ret_array['from_min'] = $ret_array['from'];
		$ret_array['secondary_from'] = $ret_array['from'] ;
		$ret_array['where'] = '';
		$ret_array['order_by'] = '';
		//secondary selects are selects that need to be run after the primarty query to retrieve additional info on main
		if($singleSelect){
		   $ret_array['secondary_select']=& $ret_array['select'];
		   $ret_array['secondary_from'] = & $ret_array['from'];
		}else{
		  $ret_array['secondary_select'] = '';
		}
		$custom_join = false;
		if((!isset($params['include_custom_fields']) || $params['include_custom_fields']) &&  isset($this->custom_fields)){

			$custom_join = $this->custom_fields->getJOIN();
			if($custom_join){
				$ret_array['select'] .= ' ' .$custom_join['select'];
			}
		}








		if($custom_join){
			$ret_array['from'] .= ' ' . $custom_join['join'];
		}
		$jtcount = 0;
		//LOOP AROUND FOR FIXIN VARDEF ISSUES
		require('include/VarDefHandler/listvardefoverride.php');
		$joined_tables = array();
		if(isset($params['joined_tables'])){
			foreach($params['joined_tables'] as $table){
				$joined_tables[$table] = 1;
			}
		}
		if(!empty($filter)){

			$filterKeys = array_keys($filter);
			if(is_numeric($filterKeys[0])){
			    $fields = array();
			    foreach($filter as $field){
			        $field = strtolower($field);
			        if(isset($this->field_defs[$field])){
			            $fields[$field]= $this->field_defs[$field];
			        }else{
			            $fields[$field] = array('force_exists'=>true);
			        }
			    }
			}else{
			    $fields = 	$filter;
			}
		}else{
			$fields = 	$this->field_defs;
		}


		foreach($fields as $field=>$value){

			//alias is used to alias field names
			$alias='';
			if 	(isset($value['alias'])) {
				$alias =' as ' . $value['alias'] . ' ';
			}

			if(empty($this->field_defs[$field]) ){
				if(!empty($filter) && isset($filter[$field]['force_exists']) && $filter[$field]['force_exists']){
						//spaces are a fix for length issue problem with unions.  The union only returns the maximum number of characters from the first select statemtn.
						$ret_array['select'] .= ", '                                                                                                                                                                                                                                                              ' $field ";
				}
				continue;
			}else{
				$data = $this->field_defs[$field];
			}

            //ignore fields that are a part of the collection and a field has been removed as a result of
            //layout customization.. this happens in subpanel customizations, use case, from the contacts subpanel
            //in opportunities module remove the contact_role/opportunity_role field.
            $process_field=true;
            if (isset($data['relationship_fields']) and !empty($data['relationship_fields'])) {
                foreach ($data['relationship_fields'] as $field_name) {
                    if (!isset($fields[$field_name])) {
                        $process_field=false;
                    }
                }
            }
            if (!$process_field) {
                continue;
            }

        	if(  (!isset($data['source']) || $data['source'] == 'db') && (!empty($alias) || !empty($filter) ))
			{
				$ret_array['select'] .= ", $this->table_name.$field $alias";
			}


			if($data['type'] != 'relate' && isset($data['db_concat_fields']))
			{
				$ret_array['select'] .= ", " . db_concat($this->table_name, $data['db_concat_fields']) . " as $field";
			}
			if($data['type'] == 'relate' && isset($data['link']))
			{


				$this->load_relationship($data['link']);
                if(!empty($this->$data['link'])){
				$params = array();
				if(empty($join_type)){
					$params['join_type'] = ' LEFT JOIN ';
				}else{
					$params['join_type'] = $join_type;
				}
				if(isset($data['join_name'])){
					$params['join_table_alias'] = $data['join_name'];
				}else{
					$params['join_table_alias']	= 'jt' . $jtcount;

				}
				if(isset($data['join_link_name'])){
					$params['join_table_link_alias'] = $data['join_link_name'];
				}else{
					$params['join_table_link_alias'] = 'jtl' . $jtcount;
				}


					$join = $this->$data['link']->getJoin($params, true);
					$rel_module = $this->$data['link']->getRelatedModuleName();

					$table_joined = !empty($joined_tables[$params['join_table_alias']]) || (!empty($joined_tables[$params['join_table_link_alias']]) && isset($data['link_type']) && $data['link_type'] == 'relationship_info');
					if($join['type'] == 'many-to-many'){
						if(empty($ret_array['secondary_select'])){
							$ret_array['secondary_select'] = " SELECT $this->table_name.id ref_id  ";

							if(!empty($beanFiles[$beanList[$rel_module]])){
								require_once($beanFiles[$beanList[$rel_module]]);
								$rel_mod = new $beanList[$rel_module]();
								if(isset($rel_mod->field_defs['assigned_user_id'])){
								$ret_array['secondary_select'].= " , ".	$params['join_table_alias'] . ".assigned_user_id {$field}_owner, '$rel_module' {$field}_mod";
								}else{
									if(isset($rel_mod->field_defs['created_by'])){
										$ret_array['secondary_select'].= " , ".	$params['join_table_alias'] . ".created_by {$field}_owner , '$rel_module' {$field}_mod";

									}
								}


					}
						}


						if(isset($data['db_concat_fields'])){
							$ret_array['secondary_select'] .= ' , ' . db_concat($params['join_table_alias'], $data['db_concat_fields']) . ' ' . $field;
						}else{

								if(!isset($data['relationship_fields'])){
									$ret_array['secondary_select'] .= ' , ' . $params['join_table_alias'] . '.' . $data['rname'] . ' ' . $field;
								}
						}
                        if(!$singleSelect){
    						$ret_array['select'] .= ", '                                                                                                                                                                                                                                                              ' $field ";
    						$ret_array['select'] .= ", '                                    '  " . $join['rel_key'] . ' ';
                        }
						$ret_array['secondary_select'] .= ', ' . $params['join_table_link_alias'].'.'. $join['rel_key'] .' ' . $join['rel_key'];
						if(isset($data['relationship_fields'])){
							foreach($data['relationship_fields'] as $r_name=>$alias_name){
							    if(!empty( $secondarySelectedFields[$alias_name]))continue;
								$ret_array['secondary_select'] .= ', ' . $params['join_table_link_alias'].'.'. $r_name .' ' . $alias_name;
                                $secondarySelectedFields[$alias_name] = true;
							}
						}
						if(!$table_joined){
							$ret_array['secondary_from'] .= ' ' . $join['join']. ' AND ' . $params['join_table_alias'].'.deleted=0';
							if (isset($data['link_type']) && $data['link_type'] == 'relationship_info') {
								$ret_array['secondary_where'] = $params['join_table_link_alias'] . '.' . $join['rel_key']. "='" .$parentbean->id . "'";
							}
						}
					}else{
						if(isset($data['db_concat_fields'])){
							$ret_array['select'] .= ' , ' . db_concat($params['join_table_alias'], $data['db_concat_fields']) . ' ' . $field;
						}else{
							$ret_array['select'] .= ' , ' . $params['join_table_alias'] . '.' . $data['rname'] . ' ' . $field;
						}
						if(!$table_joined){
							$ret_array['from'] .= ' ' . $join['join']. ' AND ' . $params['join_table_alias'].'.deleted=0';
							if(!empty($beanFiles[$beanList[$rel_module]])){
								require_once($beanFiles[$beanList[$rel_module]]);
								$rel_mod = new $beanList[$rel_module]();
								if(isset($value['target_record_key']) && !empty($filter)){
								    $selectedFields[$this->table_name.'.'.$value['target_record_key']] = true;
									$ret_array['select'] .= " , $this->table_name.{$value['target_record_key']} ";

								}
							if(isset($rel_mod->field_defs['assigned_user_id'])){
								$ret_array['select'] .= ' , ' .$params['join_table_alias'] . '.assigned_user_id ' .  $field . '_owner';
							}else{
								$ret_array['select'] .= ' , ' .$params['join_table_alias'] . '.created_by ' .  $field . '_owner';

							}
							$ret_array['select'] .= "  , '".$rel_module  ."' " .  $field . '_mod';

							}
						}
					}
					if(!$table_joined){
						$joined_tables[$params['join_table_alias']]=1;
						$joined_tables[$params['join_table_link_alias']]=1;
					}

				$jtcount++;
			}
            }
		}
		if(!empty($filter)){
    		if(isset($this->field_defs['assigned_user_id']) && empty($selectedFields[$this->table_name.'.assigned_user_id'])){
    			$ret_array['select'] .= ", $this->table_name.assigned_user_id ";
    		}else if(isset($this->field_defs['created_by']) &&  empty($selectedFields[$this->table_name.'.created_by'])){
    			$ret_array['select'] .= ", $this->table_name.created_by ";
    		}
            if(isset($this->field_defs['system_id']) && empty($selectedFields[$this->table_name.'.system_id'])){
                $ret_array['select'] .= ", $this->table_name.system_id ";
            }
		}
		 $where_auto = '1=1';
				if($show_deleted == 0){
                	$where_auto = "$this->table_name.deleted=0";
				}else if($show_deleted == 1){
                	$where_auto = "$this->table_name.deleted=1";
				}
		if($where != "")
		$ret_array['where'] = " where ($where) AND $where_auto";
		else
		$ret_array['where'] = " where $where_auto";
		if(!empty($order_by)){
			//make call to process the order by clause
			$ret_array['order_by'] = " ORDER BY ". $this->process_order_by($order_by, null);
		}
		if($singleSelect){
		   unset($ret_array['secondary_where']);
		   unset($ret_array['secondary_from']);
		   unset($ret_array['secondary_select']);
		}
		if($return_array){
		return $ret_array;
		}

		return  $ret_array['select'] . $ret_array['from'] . $ret_array['where']. $ret_array['order_by'];




	}

	function retrieve_parent_fields($type_info){

		$queries = array();
		global $beanList, $beanFiles;
		$templates = array();
		$parent_child_map = array();
		foreach($type_info as $children_info){
			foreach($children_info as $child_info){

			if($child_info['type'] == 'parent'){

				if(empty($templates[$child_info['parent_type']])){

					$class = $beanList[$child_info['parent_type']];

					require_once($beanFiles[$class]);
					$templates[$child_info['parent_type']] = new $class();
				}

				if(empty($queries[$child_info['parent_type']])){
					$queries[$child_info['parent_type']] = "SELECT id ";
					$field_def = $templates[$child_info['parent_type']]->field_defs['name'];
					if(isset($field_def['db_concat_fields'])){
						$queries[$child_info['parent_type']] .= ' , ' . db_concat($templates[$child_info['parent_type']]->table_name, $field_def['db_concat_fields']) . ' parent_name';
					}else{
						$queries[$child_info['parent_type']] .= ' , name parent_name';
					}
					if(isset($templates[$child_info['parent_type']]->field_defs['assigned_user_id'])){
						$queries[$child_info['parent_type']] .= ", assigned_user_id parent_name_owner , '{$child_info['parent_type']}' parent_name_mod";;
					}else if(isset($templates[$child_info['parent_type']]->field_defs['created_by'])){
						$queries[$child_info['parent_type']] .= ", created_by parent_name_owner, '{$child_info['parent_type']}' parent_name_mod";

					}
					$queries[$child_info['parent_type']] .= " FROM " . $templates[$child_info['parent_type']]->table_name ." WHERE id IN ('{$child_info['parent_id']}'";
				}else{
					if(empty($parent_child_map[$child_info['parent_id']]))
						$queries[$child_info['parent_type']] .= " ,'{$child_info['parent_id']}'";
				}
				$parent_child_map[$child_info['parent_id']][] = $child_info['child_id'];
			}
			}
		}

		$results = array();


		foreach($queries as $query){
			$result = $this->db->query($query . ')');
			while($row = $this->db->fetchByAssoc($result)) {
				$results[$row['id']] = $row;
			}

		}

		$child_results = array();
		foreach($parent_child_map as $parent_key=>$parent_child){
			foreach($parent_child as $child){
				if(isset( $results[$parent_key])){
					$child_results[$child] = $results[$parent_key];
				}
			}
		}






		return $child_results;


	}

	function process_list_query($query, $row_offset, $limit= -1, $max_per_page = -1, $where = '')
	{
		global $sugar_config;
		$arg_list = func_get_args();
		$toEnd = $row_offset == -100;
		$GLOBALS['log']->debug("process_list_query: ".$query);
		if($max_per_page == -1){
			$max_per_page 	= $sugar_config['list_max_entries_per_page'];
		}
		// Check to see if we have a count query available.
		if(empty($sugar_config['disable_count_query']) || $toEnd){
			$count_query = $this->create_list_count_query($query);
			if(!empty($count_query) && (empty($limit) || $limit == -1))
			{
				// We have a count query.  Run it and get the results.
				$result = $this->db->query($count_query, true, "Error running count query for $this->object_name List: ");
				$assoc = $this->db->fetchByAssoc($result);
				if(!empty($assoc['c']))
				{
					$rows_found = $assoc['c'];
					$limit = $sugar_config['list_max_entries_per_page'];
				}
				if( $toEnd){

					$row_offset = (floor(($rows_found -1) / $limit)) * $limit;

				}
			}

		}else{
			if((empty($limit) || $limit == -1)){
				$limit = $max_per_page + 1;
				$max_per_page = $limit;
			}

		}

		if(empty($row_offset))
		{
			$row_offset = 0;
		}
		if(!empty($limit) && $limit != -1 && $limit != -99){

			$result = $this->db->limitQuery($query, $row_offset, $limit,true,"Error retrieving $this->object_name list: ");
		}else{
			$result = $this->db->query($query,true,"Error retrieving $this->object_name list: ");
		}

		$list = Array();

		if(empty($rows_found))
		{
  			$rows_found =  $this->db->getRowCount($result);
		}

		$GLOBALS['log']->debug("Found $rows_found ".$this->object_name."s");

		$previous_offset = $row_offset - $max_per_page;
		$next_offset = $row_offset + $max_per_page;

		$class = get_class($this);
		if($rows_found != 0 or $this->db->dbType != 'mysql')
		{
//todo Bug? we should remove the magic number -99
	//use -99 to return all
			$index = $row_offset;
			while ($max_per_page == -99 || ($index < $row_offset + $max_per_page)) {

				if(!empty($sugar_config['disable_count_query'])){
					$row = $this->db->fetchByAssoc($result);
				}else{
					$row = $this->db->fetchByAssoc($result, $index);
				}
					if (empty($row)) {
						break;
					}

				    //instantiate a new class each time. This is because php5 passes
			    	//by reference by default so if we continually update $this, we will
				    //at the end have a list of all the same objects
					$temp = new $class();

					foreach($this->field_defs as $field=>$value)
					{
						if (isset($row[$field])) {
							$temp->$field = $row[$field];
							$owner_field = $field . '_owner';
							if(isset($row[$owner_field])){
								$temp->$owner_field = $row[$owner_field];
							}

							$GLOBALS['log']->debug("$temp->object_name({$row['id']}): ".$field." = ".$temp->$field);
						}else if (isset($row[$this->table_name .'.'.$field])) {
							$temp->$field = $row[$this->table_name .'.'.$field];
						}
						else
						{
							$temp->$field = "";
						}
					}

					$temp->fill_in_additional_list_fields();
					$list[] = $temp;

					$index++;
			}
		}
		if(!empty($sugar_config['disable_count_query']) && !empty($limit)){

			$rows_found = $row_offset + count($list);

			unset($list[$limit - 1]);
			if(!$toEnd){
				$next_offset--;
				$previous_offset++;
			}
		}
		$response = Array();
		$response['list'] = $list;
		$response['row_count'] = $rows_found;
		$response['next_offset'] = $next_offset;
		$response['previous_offset'] = $previous_offset;
		$response['current_offset'] = $row_offset ;
		return $response;
	}

	/**
	 * This will return the number of rows that the given SQL query should produce.
	 */
	function _get_num_rows_in_query($query, $is_count_query=false)
	{
		$num_rows_in_query = 0;
		if (!$is_count_query) {
			$count_query = SugarBean::create_list_count_query($query);
		} else $count_query=$query;

		$result = $this->db->query($count_query, true, "Error running count query for $this->object_name List: ");
		$row_num = 0;
		$row = $this->db->fetchByAssoc($result, $row_num);
		while($row)
		{
			$num_rows_in_query += current($row);
			$row_num++;
			$row = $this->db->fetchByAssoc($result, $row_num);
		}

		return $num_rows_in_query;
	}
	
	function process_union_list_query($parent_bean, $query,
		$row_offset, $limit= -1, $max_per_page = -1, $where = '', $subpanel_def, $query_row_count='', $secondary_queries = array())

	{
		/**
		 * if the row_offset is set to -100 go to the end of the list
		 */
		$toEnd = $row_offset == -100;
		global $sugar_config;
		$use_count_query=false;
		$processing_collection=$subpanel_def->isCollection();

		$GLOBALS['log']->debug("process_list_query: ".$query);
		if($max_per_page == -1){
			$max_per_page 	= $sugar_config['list_max_entries_per_subpanel'];
		}
		if(empty($query_row_count)){
			$query_row_count = $query;
		}
		$distinct_position=strpos($query,"DISTINCT");
		if ($distinct_position!= false) {
			$use_count_query=true;
		}
		$performSecondQuery = true;
		if(empty($sugar_config['disable_count_query']) || $toEnd){
				$rows_found = $this->_get_num_rows_in_query($query_row_count,$use_count_query);
				if($rows_found < 1){
					$performSecondQuery = false;
				}
			if(!empty($rows_found) && (empty($limit) || $limit == -1))
			{
				$limit = $sugar_config['list_max_entries_per_subpanel'];
			}
			if( $toEnd){

					$row_offset = (floor(($rows_found -1) / $limit)) * $limit;

			}
		}else{
			if((empty($limit) || $limit == -1)){
				$limit = $max_per_page + 1;
				$max_per_page = $limit;
			}


		}

		if(empty($row_offset))
		{
			$row_offset = 0;
		}
		$list = array();
		$previous_offset = $row_offset - $max_per_page;
		$next_offset = $row_offset + $max_per_page;
		
		if($performSecondQuery){
			if(!empty($limit) && $limit != -1 && $limit != -99){
				$result = $parent_bean->db->limitQuery($query, $row_offset, $limit,true,"Error retrieving $parent_bean->object_name list: ");
			}else{
				$result = $parent_bean->db->query($query,true,"Error retrieving $this->object_name list: ");
			}

			
			if(empty($rows_found))
			{
	  			$rows_found =  $parent_bean->db->getRowCount($result);
			}

			$GLOBALS['log']->debug("Found $rows_found ".$parent_bean->object_name."s");
	
			

		if($rows_found != 0 or $parent_bean->db->dbType != 'mysql')
		{
			//use -99 to return all

			// get the current row
			$index = $row_offset;
			if(!empty($sugar_config['disable_count_query'])){
				$row = $parent_bean->db->fetchByAssoc($result);
			}else{
				$row = $parent_bean->db->fetchByAssoc($result, $index);
			}
			$post_retrieve = array();
			$isFirstTime = true;
			while($row)
			{
				$function_fields = array();
				if(($index < $row_offset + $max_per_page || $max_per_page == -99) or ($parent_bean->db->dbType != 'mysql'))
				{
					if ($processing_collection) {
						$current_bean =$subpanel_def->sub_subpanels[$row['panel_name']]->template_instance;
						if(!$isFirstTime){
							$class = get_class($subpanel_def->sub_subpanels[$row['panel_name']]->template_instance);
							$current_bean = new $class();
						}
					} else {
						$current_bean=$subpanel_def->template_instance;
						if(!$isFirstTime){
							$class = get_class($subpanel_def->template_instance);
							$current_bean = new $class();
						}
					}
					$isFirstTime = false;
					//set the panel name in the bean instance.
					if (isset($row['panel_name'])) {
						$current_bean->panel_name=$row['panel_name'];
					}
					foreach($current_bean->field_defs as $field=>$value)
					{

						if (!empty($row[$field])) {
							$current_bean->$field = $row[$field];

							unset($row[$field]);
							//$GLOBALS['log']->debug("$current_bean->object_name({$row['id']}): ".$field." = ".$current_bean->$field);
						}else if (!empty($row[$this->table_name .'.'.$field])) {
							$current_bean->$field = $row[$current_bean->table_name .'.'.$field];
							unset($row[$current_bean->table_name .'.'.$field]);
						}
						else
						{
							$current_bean->$field = "";
							unset($row[$field]);
						}
						if(isset($value['source']) && $value['source'] == 'function'){
							$function_fields[]=$field;
						}
					}
					foreach($row as $key=>$value){
						$current_bean->$key = $value;
					}
					foreach($function_fields as $function_field){
						$value = $current_bean->field_defs[$function_field];
						$can_execute = true;
						$execute_params = array();
						$execute_function = array();
						if(!empty($value['function_class'])){
							$execute_function[] = 	$value['function_class'];
							$execute_function[] = 	$value['function_name'];
						}else{
							$execute_function	= $value['function_name'];
						}
						foreach($value['function_params'] as $param ){
								if (empty($value['function_params_source']) or $value['function_params_source']=='parent') {
									if(empty($this->$param)){
										$can_execute = false;
									}else{
										$execute_params[] = $this->$param;
									}
								} else if ($value['function_params_source']=='this') {
									if(empty($current_bean->$param)){
										$can_execute = false;
									}else{
										$execute_params[] = $current_bean->$param;
									}
								} else {
									$can_execute = false;
								}

						}
						if($can_execute){
							if(!empty($value['function_require'])){
								require_once($value['function_require']);
							}
							$current_bean->$function_field = call_user_func_array($execute_function, $execute_params);
						}
					}


					if(!empty($current_bean->parent_type) && !empty($current_bean->parent_id)){
						if(!isset($post_retrieve[$current_bean->parent_type])){

							$post_retrieve[$current_bean->parent_type] = array();
						}

						$post_retrieve[$current_bean->parent_type][] = array('child_id'=>$current_bean->id, 'parent_id'=> $current_bean->parent_id, 'parent_type'=>$current_bean->parent_type, 'type'=>'parent');
					}



					 //$current_bean->fill_in_additional_list_fields();
					$list[$current_bean->id] = $current_bean;


				}


				// go to the next row
				$index++;
				$row = $parent_bean->db->fetchByAssoc($result, $index);
			}
		}
		
		//now handle retrieving many-to-many relationships
		if(!empty($list)){
			foreach($secondary_queries as $query2){
				$result2 = $this->db->query($query2);

				$row2 = $this->db->fetchByAssoc($result2);

				while($row2){
					$id_ref = $row2['ref_id'];

					if(isset($list[$id_ref])){

						foreach($row2 as $r2key=>$r2value){

							if($r2key != 'ref_id'){
								$list[$id_ref]->$r2key = $r2value;
							}


						}

					}

					$row2 = $this->db->fetchByAssoc($result2);
				}

			}
		}

		if(isset($post_retrieve)){

			$parent_fields = $this->retrieve_parent_fields($post_retrieve);
		}else{
			$parent_fields = array();
		}
		if(!empty($sugar_config['disable_count_query']) && !empty($limit)){

			$rows_found = $row_offset + count($list);
			if(count($list) >= $limit){
		 		array_pop($list);
			}
			if(!$toEnd){
				$next_offset--;
				$previous_offset++;
			}
		}
		}else{
			$row_found 	= 0;
			$parent_fields = array();
		}
		$response = array();
		$response['list'] = $list;
		$response['parent_data'] = $parent_fields;
		$response['row_count'] = $rows_found;
		$response['next_offset'] = $next_offset;
		$response['previous_offset'] = $previous_offset;
		$response['current_offset'] = $row_offset ;
		$response['query'] = $query;

		return $response;
	}

	function process_detail_query($query, $row_offset, $limit= -1, $max_per_page = -1, $where = '', $offset = 0)
	{
		global $sugar_config;
		$GLOBALS['log']->debug("process_list_query: ".$query);
		if($max_per_page == -1){
			$max_per_page 	= $sugar_config['list_max_entries_per_page'];
		}

				// Check to see if we have a count query available.
		$count_query = $this->create_list_count_query($query);

		if(!empty($count_query) && (empty($limit) || $limit == -1))
		{
			// We have a count query.  Run it and get the results.
			$result = $this->db->query($count_query, true, "Error running count query for $this->object_name List: ");
			$assoc = $this->db->fetchByAssoc($result);
			if(!empty($assoc['c']))
			{
				$total_rows = $assoc['c'];
			}
		}

		if(empty($row_offset))
		{
			$row_offset = 0;
		}

		$result = $this->db->limitQuery($query, $offset, 1, true,"Error retrieving $this->object_name list: ");

		$rows_found = $this->db->getRowCount($result);

		$GLOBALS['log']->debug("Found $rows_found ".$this->object_name."s");

		$previous_offset = $row_offset - $max_per_page;
		$next_offset = $row_offset + $max_per_page;

		if($rows_found != 0 or $this->db->dbType != 'mysql')
		{
			$index = 0;
			$row = $this->db->fetchByAssoc($result, $index);
			$this->retrieve($row['id']);
		}

		$response = Array();
		$response['bean'] = $this;
		if (empty($total_rows)) $total_rows=0;
		$response['row_count'] = $total_rows;
		$response['next_offset'] = $next_offset;
		$response['previous_offset'] = $previous_offset;

		return $response;
	}

	function process_full_list_query($query, $check_date=false)
	{

		$GLOBALS['log']->debug("process_full_list_query: query is ".$query);
		$result = $this->db->query($query, false);
		$GLOBALS['log']->debug("process_full_list_query: result is ".$result);
		$class = get_class($this);
		$isFirstTime = true;
		$bean = new $class();

		//if($this->db->getRowCount($result) > 0){


			// We have some data.
			//while ($row = $this->db->fetchByAssoc($result)) {
			while (($row = $bean->db->fetchByAssoc($result)) != null) {
				if(!$isFirstTime){
					$bean = new $class();
				}
				$isFirstTime = false;

				foreach($bean->field_defs as $field=>$value)
				{
					if (isset($row[$field])) {
						$bean->$field = $row[$field];

						$GLOBALS['log']->debug("process_full_list: $bean->object_name({$row['id']}): ".$field." = ".$bean->$field);
					}else {
						$bean->$field = '';
					}
				}
				if($check_date){
					$bean->processed_dates_times = array();
					$bean->check_date_relationships_load();
				}
					$bean->fill_in_additional_list_fields();

				$list[] = $bean;
			}
		//}

		if (isset($list)) return $list;
		else return null;
	}

	/**
	* Track the viewing of a detail record.  This leverages get_summary_text() which is object specific
	* params $user_id - The user that is viewing the record.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function track_view($user_id, $current_module)
	{
		$GLOBALS['log']->debug("About to call tracker (user_id, module_name, item_id)($user_id, $current_module, $this->id)");

		$tracker = new Tracker();
		$tracker->track_view($user_id, $current_module, $this->id, $this->get_summary_text());
	}

	/**
	* return the summary text that should show up in the recent history list for this object.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function get_summary_text()
	{
		return "Base Implementation.  Should be overridden.";
	}

	/**
	* This is designed to be overridden and add specific fields to each record.  This allows the generic query to fill in
	* the major fields, and then targetted queries to get related fields and add them to the record.  The contact's account for instance.
	* This method is only used for populating extra fields in lists
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function fill_in_additional_list_fields()
	{
	}

	/**
	* This is designed to be overridden and add specific fields to each record.  This allows the generic query to fill in
	* the major fields, and then targetted queries to get related fields and add them to the record.  The contact's account for instance.
	* This method is only used for populating extra fields in the detail form
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function fill_in_additional_detail_fields()
	{
	}

	/**
	* This is a helper class that is used to quickly created indexes when createing tables
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function create_index($query)
	{
		$GLOBALS['log']->info($query);

		$result = $this->db->query($query, true, "Error creating index:");
	}

	/** This function should be overridden in each module.  It marks an item as deleted.
	* If it is not overridden, then marking this type of item is not allowed
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function mark_deleted($id)
	{

		$date_modified = gmdate('Y-m-d H:i:s');
		if(isset($_SESSION['show_deleted'])){
			$this->mark_undeleted($id);
		}else{
			// call the custom business logic
			$custom_logic_arguments['id'] = $id;
			$this->call_custom_logic("before_delete", $custom_logic_arguments);

			$query = "UPDATE $this->table_name set deleted=1 , date_modified = '$date_modified' where id='$id'";
			$this->db->query($query, true,"Error marking record deleted: ");
			$this->mark_relationships_deleted($id);

			// Take the item off of the recently viewed lists.
			$tracker = new Tracker();
			$tracker->delete_item_history($id);

			// call the custom business logic
			$this->call_custom_logic("after_delete", $custom_logic_arguments);
		}

	}

	function mark_undeleted($id)
	{
		// call the custom business logic
		$custom_logic_arguments['id'] = $id;
		$this->call_custom_logic("before_restore", $custom_logic_arguments);

		$date_modified = gmdate('Y-m-d H:i:s');
		$query = "UPDATE $this->table_name set deleted=0 , date_modified = '$date_modified' where id='$id'";
		$this->db->query($query, true,"Error marking record undeleted: ");

		// call the custom business logic
		$this->call_custom_logic("after_restore", $custom_logic_arguments);
	}

	/** This function deletes relationships to this object.  It should be overridden to handle the relationships of the specific object.
	* This function is called when the item itself is being deleted.  For instance, it is called on Contact when the contact is being deleted.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function mark_relationships_deleted($id)
	{
		$this->delete_linked($id);
	}

	/**
	* This function is used to execute the query and create an array template objects from the resulting ids from the query.
	* It is currently used for building sub-panel arrays.
	* param $query - the query that should be executed to build the list
	* param $template - The object that should be used to copy the records.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function build_related_list($query, &$template, $row_offset = 0, $limit = -1)
	{
		$GLOBALS['log']->debug("Finding linked records $this->object_name: ".$query);

        if(!empty($row_offset) && $row_offset != 0 && !empty($limit) && $limit != -1){
            $result = $this->db->limitQuery($query, $row_offset, $limit,true,"Error retrieving $template->object_name list: ");   
        }else{
		    $result = $this->db->query($query, true);
        }

		$list = Array();
		$isFirstTime = true;
		$class = get_class($template);



		while($row = $this->db->fetchByAssoc($result))
		{
			if(!$isFirstTime){
				$template = new $class();



			}
			$isFirstTime = false;
			$record = $template->retrieve($row['id']);

			if($record != null)
			{
				// this copies the object into the array
				$list[] = $template;
			}
		}

		return $list;
	}


		/**
	* This function is used to execute the query and create an array template objects from the resulting ids from the query.
	* It is currently used for building sub-panel arrays. It supports an additional where clause that is executed as a filter on the results
	*
	* param $query - the query that should be executed to build the list
	* param $template - The object that should be used to copy the records.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function build_related_list_where($query, &$template, $where='', $in='', $order_by, $limit='', $row_offset = 0)
	{
		// No need to do an additional query
		$GLOBALS['log']->debug("Finding linked records $this->object_name: ".$query);
		if(empty($in) && !empty($query)){

			$idList = $this->build_related_in($query);
			$in = $idList['in'];
		}
		$query = "SELECT id FROM $this->table_name WHERE deleted=0 AND id IN $in";
		if(!empty($where)){
			$query .= " AND $where";
		}
		if(!empty($order_by)){
			$query .= "ORDER BY $order_by";
		}
		if (!empty($limit)) {
			$result = $this->db->limitQuery($query, $row_offset, $limit,true,"Error retrieving $this->object_name list: ");
		}else{
            $result = $this->db->query($query, true);
        }

		$list = Array();
		$isFirstTime = true;
		$class = get_class($template);

        $disable_security_flag = ($template->disable_row_level_security) ? true : false;

		while($row = $this->db->fetchByAssoc($result))
		{
			if(!$isFirstTime){
				$template = new $class();
                $template->disable_row_level_security = $disable_security_flag;
			}
			$isFirstTime = false;
			$record = $template->retrieve($row['id']);

			if($record != null)
			{
				// this copies the object into the array
				$list[] = $template;
			}
		}

		return $list;
	}

	function build_related_in($query)
	{
		$idList = array();
		$result = $this->db->query($query, true);
		$ids = '';
		while($row = $this->db->fetchByAssoc($result))
		{
			$idList[] = $row['id'];
			if(empty($ids)){
				$ids = "('" . $row['id'] . "'";
			}else{
				$ids .= ",'" . $row['id'] . "'";
			}
		}
		if(empty($ids)){
			$ids = '(';
		}
		$ids .= ')';
		return array('list'=>$idList, 'in'=>$ids);
	}

	/**
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function build_related_list2($query, &$template, &$field_list)
	{
		$GLOBALS['log']->debug("Finding linked values $this->object_name: ".$query);

		$result = $this->db->query($query, true);

		$list = Array();
		$isFirstTime = true;
		$class = get_class($template);
		while($row = $this->db->fetchByAssoc($result))
		{
			// Create a blank copy
			$copy = $template;
			if(!$isFirstTime){
				$copy = new $class();
			}
			$isFirstTime = false;
			foreach($field_list as $field)
			{
				// Copy the relevant fields
				$copy->$field = $row[$field];

			}

			// this copies the object into the array
			$list[] = $copy;
		}

		return $list;
	}


	/* This is to allow subclasses to fill in row specific columns of a list view form */
	function list_view_parse_additional_sections(&$list_form)
	{
	}

	/* This function assigns all of the values into the template for the list view */
	function get_list_view_array(){
		$return_array = Array();
		global $app_list_strings;

		foreach($this->field_defs as $field=>$value) {
			if(isset($this->$field)) {
				//cn: if $field is a _dom, detect and return VALUE not KEY
				if ((!empty($value['type']) && $value['type'] == 'enum') || (!empty($value['custom_type']) && $value['custom_type'] == 'enum')) {
					if(!empty($app_list_strings[$value['options']][$this->$field])) {
							$return_array[strtoupper($field)] = $app_list_strings[$value['options']][$this->$field];
					}
					else{
						$return_array[strtoupper($field)] = $this->$field;
					}
				} else {
					$return_array[strtoupper($field)] = $this->$field;
				}
				// handle "Assigned User Name"
				if($field == 'assigned_user_name') {
					$return_array['ASSIGNED_USER_NAME'] = get_assigned_user_name($this->assigned_user_id);
				}
			}

			
			else if(isset($this->custom_fields)) {
			    $type = $this->custom_fields->getType($field);
			    
				if($type == 'bool')
				{
					if($this->$field == '1')
					{
						$return_array[strtoupper($field . '_CHECKED')] = 'checked';
					}
				}
				//cn: if custom field is an array (dropdown key/value pair), return value, not key
				else if ($type == 'enum') {
					if(!empty($app_list_strings[$this->getRealKeyFromCustomFieldAssignedKey($field)][$this->$field]) ) {
						$return_array[strtoupper($field)] = $app_list_strings[$this->getRealKeyFromCustomFieldAssignedKey($field)][$this->$field];
					}
				}
			}
		}

		return $return_array;
	}

	function get_list_view_data()
	{

		return $this->get_list_view_array();
	}

	function get_where(&$fields_array)
	{
		$where_clause = "WHERE ";
		$first = 1;
		foreach ($fields_array as $name=>$value)
		{
			if ($first)
			{
				$first = 0;
			}
			else
			{
				$where_clause .= " AND ";
			}

			$where_clause .= "$name = '".PearDatabase::quote($value,false)."'";
		}

		$where_clause .= " AND deleted=0";
		return $where_clause;
	}


	function retrieve_by_string_fields($fields_array, $encode=true)
	{
		$where_clause = $this->get_where($fields_array);
		if(isset($this->custom_fields))
			$custom_join = $this->custom_fields->getJOIN();
		else $custom_join = false;
		if($custom_join){
			$query = "SELECT $this->table_name.*". $custom_join['select']. " FROM $this->table_name " . $custom_join['join'];
		}else{
			$query = "SELECT $this->table_name.* FROM $this->table_name ";
		}
		$query .= " $where_clause";
		$GLOBALS['log']->debug("Retrieve $this->object_name: ".$query);
        //requireSingleResult has beeen deprecated.
		//$result = $this->db->requireSingleResult($query, true, "Retrieving record $where_clause:");
		$result = $this->db->limitQuery($query,0,1,true, "Retrieving record $where_clause:");


		if( empty($result))
		{
			return null;
		}
		if($this->db->getRowCount($result) > 1){
			$this->duplicates_found = true;
		}

		$row = $this->db->fetchByAssoc($result, -1, $encode);
		if(empty($row)){
			return null;
		}
		foreach($this->column_fields as $field)
		{
			if(isset($row[$field]))
			{
				$this->$field = $row[$field];
			}
		}
		$this->fill_in_additional_detail_fields();
		return $this;
	}


    /**
    * this method is called during an import before inserting a bean
	* define an associative array called $special_fields
	* the keys are user defined, and don't directly map to the bean's fields
	* the value is the method name within that bean that will do extra
	* processing for that field. example: 'full_name'=>'get_names_from_full_name'
    */
	function process_special_fields()
	{
		foreach ($this->special_functions as $func_name)
		{
			if ( method_exists($this,$func_name) )
			{
				$this->$func_name();
			}
		}
	}
	/**
	 * 	builds a generic search based on the query string using or do not reference $this
	 * since this method is used without having created an instance
	*/
	function build_generic_where_clause($value){

	}









































	function &parse_additional_headers(&$list_form, $xTemplateSection) {
		return $list_form;
	}

	function assign_display_fields($currentModule){
		global $timedate;
		foreach($this->column_fields as $field){
			if(isset($this->field_name_map[$field]) && empty($this->$field)){
				if($this->field_name_map[$field]['type'] != 'date' && $this->field_name_map[$field]['type'] != 'enum')
				$this->$field = $field;
				if($this->field_name_map[$field]['type'] == 'date'){
					$this->$field = $timedate->to_display_date('1980-07-09');
				}
				if($this->field_name_map[$field]['type'] == 'enum'){
					$dom = $this->field_name_map[$field]['options'];
					global $current_language, $app_list_strings;
					$mod_strings = return_module_language($current_language, $currentModule);

					if(isset($mod_strings[$dom])){
						$options = $mod_strings[$dom];
						foreach($options as $key=>$value){
							if(!empty($key) && empty($this->$field )){
								$this->$field = $key;
							}
						}
					}
					if(isset($app_list_strings[$dom])){
						$options = $app_list_strings[$dom];
						foreach($options as $key=>$value){
							if(!empty($key) && empty($this->$field )){
								$this->$field = $key;
							}
						}
					}


				}
			}

		}









	}


	// called as a special_function from an Import when saving
	function add_created_modified_dates()
	{
		if ( isset ($this->date_entered_only))
		{
			$mysql_date_str = getSQLDate($this->date_entered_only);
			if ( ! empty($mysql_date_str))
			{
				if ( isset ($this->time_entered_only))
				{
					$this->date_entered = $mysql_date_str . " " . $this->time_entered_only;
				}
				else
				{
					$this->date_entered = $mysql_date_str . " 00:00:00";
				}

			}
		}


		if ( isset ($this->date_modified_only))
		{
			$mysql_date_str = getSQLDate($this->date_modified_only);
			if ( ! empty($mysql_date_str))
			{
				if ( isset ($this->time_modified_only))
				{
					$this->date_modified = $mysql_date_str . " " . $this->time_modified_only;
				}
				else
				{
					$this->date_modified = $mysql_date_str . " 00:00:00";
				}

			}
		}

		if ( ! isset ( $this->date_modified) && isset ( $this->date_entered))
		{
			$this->date_modified = $this->date_entered;
		}
		else if ( ! isset ( $this->date_entered) && isset ( $this->date_modified))
		{
			$this->date_entered = $this->date_modified;
		}

	}

	/*
	 * 	RELATIONSHIP HANDLING
	 */

	function set_relationship($table, $relate_values, $check_duplicates = true,$do_update=false,$data_values=null){
		$where = '';

		// make sure there is a date modified
		$date_modified = db_convert("'".gmdate("Y-m-d H:i:s")."'", 'datetime');

		$row=null;
		if($check_duplicates){
			$query = "SELECT * FROM $table ";
			$where = "WHERE deleted = '0'  ";
			foreach($relate_values as $name=>$value){
				$where .= " AND $name = '$value' ";
			}
			$query .= $where;
			$result = $this->db->query($query, false, "Looking For Duplicate Relationship:" . $query);
			$row=$this->db->fetchByAssoc($result);
		}

		if(!$check_duplicates || empty($row) ){
			unset($relate_values['id']);
			if ( isset($data_values))
			{
				$relate_values = array_merge($relate_values,$data_values);
      		}


      $query = "INSERT INTO $table (id, ". implode(',', array_keys($relate_values)) . ", date_modified) VALUES ('" . create_guid() . "', " . "'" . implode("', '", $relate_values) . "', ".$date_modified.")" ;

			$this->db->query($query, false, "Creating Relationship:" . $query);
		}
		else if ($do_update)
		{
			$conds = array();
			foreach($data_values as $key=>$value)
			{
				array_push($conds,$key."='".PearDatabase::quote(from_html($value))."'");
			}
			$query = "UPDATE $table SET ". implode(',', $conds).",date_modified=".$date_modified." ".$where;
			$this->db->query($query, false, "Updating Relationship:" . $query);
		}
	}


	 function retrieve_relationships($table, $values, $select_id){
	 	$query = "SELECT $select_id FROM $table WHERE deleted = 0  ";
	 	foreach($values as $name=>$value){
	 		$query .= " AND $name = '$value' ";
	 	}
                $query .= " ORDER BY $select_id ";
	 	$result = $this->db->query($query, false, "Retrieving Relationship:" . $query);
	 	$ids = array();
	 	while($row = $this->db->fetchByAssoc($result)){
	 			$ids[] = $row;
	 	}
	 	return $ids;
	 }

	// TODO: this function needs adjustment
	function loadLayoutDefs()
	{
    global $layout_defs;
		if(empty( $this->layout_def) && file_exists('modules/'. $this->module_dir . '/layout_defs.php'))
		{
			include_once('modules/'. $this->module_dir . '/layout_defs.php');
			if(file_exists('custom/modules/'. $this->module_dir . '/Ext/Layoutdefs/layoutdefs.ext.php')){
				include_once('custom/modules/'. $this->module_dir . '/Ext/Layoutdefs/layoutdefs.ext.php');
			}
			if ( empty( $layout_defs[get_class($this)])) {
				echo "\$layout_defs[" . get_class($this) . "]; does not exist";
			}

			$this->layout_def = $layout_defs[get_class($this)];
		}
	}
































	/**
	 * Trigger custom logic for this module that is defined for the provided hook
	 * The custom logic file is located under custom/modules/[CURRENT_MODULE]/logic_hooks.php.
	 * That file should define the $hook_version that should be used.
	 * It should also define the $hook_array.  The $hook_array will be a two dimensional array
	 * the first dimension is the name of the event, the second dimension is the information needed
	 * to fire the hook.  Each entry in the top level array should be defined on a single line to make it
	 * easier to automatically replace this file.  There should be no contents of this file that are not replacable.
	 *
	 * $hook_array['before_save'][] = Array(1, testtype, 'custom/modules/Leads/test12.php', 'TestClass', 'lead_before_save_1');
	 * This sample line creates a before_save hook.  The hooks are procesed in the order in which they
	 * are added to the array.  The second dimension is an array of:
	 *		processing index (for sorting before exporting the array)
	 *		A logic type hook
	 *		label/type
	 *		php file to include
	 *		php class the method is in
	 *		php method to call
	 *
	 * The method signature for version 1 hooks is:
	 * function NAME(&$bean, $event, $arguments)
	 * 		$bean - $this bean passed in by reference.
	 *		$event - The string for the current event (i.e. before_save)
	 * 		$arguments - An array of arguments that are specific to the event.
	 */
	function call_custom_logic($event, $arguments = null)
	{
		if(!isset($this->processed) || $this->processed == false)
		{
  			// declare the hook array variable, it will be defined in the included file.
			$hook_array = null;

			// This will load an array of the hooks to process
			if(file_exists("custom/modules/$this->module_dir/logic_hooks.php"))
			{
				$GLOBALS['log']->debug('Including module specific hook file for '.$this->module_dir);
				include("custom/modules/$this->module_dir/logic_hooks.php");
				$this->process_hooks($hook_array, $event, $arguments);
				$hook_array = null;
			}

			// Now load the generic array if it exists.
			if(file_exists('custom/modules/logic_hooks.php'))
			{
				$GLOBALS['log']->debug('Including generic hook file');
				include('custom/modules/logic_hooks.php');
				$this->process_hooks($hook_array, $event, $arguments);
			}
		}
	}

	/**
	 * This is a helper method that actually loops through the hooks and calls each of the hooks in turn
	 * $hook_array - The array of hooks to call
	 * $event - The string for the current event (i.e. before_save)
	 * $arguments - An array of arguments that are specific to the event.
	 */
	function process_hooks($hook_array, $event, $arguments)
	{
		// Now iterate through the array for the appropriate hook
		if(!empty($hook_array[$event]))
		{
			foreach($hook_array[$event] as $hook_details)
			{
				if(!file_exists($hook_details[2]))
				{
					$GLOBALS['log']->error('Unable to load custom logic file: '.$hook_details[2]);
					continue;
				}

				include_once($hook_details[2]);
				$hook_class = $hook_details[3];
				$hook_function = $hook_details[4];

				// Make a static call to the function of the specified class
				//TODO Make a factory for these classes.  Cache instances accross uses
				if($hook_class == $hook_function){
					$GLOBALS['log']->debug('Creating new instance of hook class '.$hook_class.' with parameters');
					$class = new $hook_class($this, $event, $arguments);
				} else {
					$GLOBALS['log']->debug('Creating new instance of hook class '.$hook_class.' without parameters');
					$class = new $hook_class();
					$class->$hook_function($this, $event, $arguments);
				}
			}
		}
	}

	/*	When creating a custom field of type Dropdown, it creates an enum row in the DB.
		A typical get_list_view_array() result will have the *KEY* value from that drop-down.
		Since custom _dom objects are flat-files included in the $app_list_strings variable,
		We need to generate a key-key pair to get the true value like so:
		([module]_cstm->fields_meta_data->$app_list_strings->*VALUE*)*/
	function getRealKeyFromCustomFieldAssignedKey($name) {
		if ($this->custom_fields->avail_fields[$name]['ext1']) {
			$realKey = 'ext1';
		}
		elseif ($this->custom_fields->avail_fields[$name]['ext2']) {
			$realKey = 'ext2';
		}
		elseif ($this->custom_fields->avail_fields[$name]['ext3']) {
			$realKey = 'ext3';
		} else {
			$GLOBALS['log']->fatal("SUGARBEAN: cannot find Real Key for custom field of type dropdown - cannot return Value.");
			return false;
		}

		if(isset($realKey)) {
			return $this->custom_fields->avail_fields[$name][$realKey];
		}
	}

	function bean_implements($interface){
		return false;
	}

	function ACLAccess($view,$is_owner='not_set'){
		global $current_user;
		
		if($is_owner == 'not_set'){
			$is_owner = $this->isOwner($current_user->id);
		}

		//if we don't implent acls return true
        if(!$this->bean_implements('ACL'))
		    return true;
		
		switch ($view){
			case 'list':
			case 'index':
			case 'ListView': 
			    return ACLController::checkAccess($this->module_dir,'list', true);
			case 'edit':
			case 'Save':
			case 'PopupEditView':
			case 'EditView':
                return ACLController::checkAccess($this->module_dir,'edit', $is_owner);
			case 'view':
			case 'DetailView':
			    return ACLController::checkAccess($this->module_dir,'view', $is_owner);
			case 'delete':
			case 'Delete':
			    return ACLController::checkAccess($this->module_dir,'delete', $is_owner);
			case 'export':
			case 'Export':
			    return ACLController::checkAccess($this->module_dir,'export', $is_owner);
			case 'import':
			case 'Import':
			    return ACLController::checkAccess($this->module_dir,'import', true);

		}
		//if it is not one of the above views then it should be implemented on the page level
		return true;
	}
	/**
	 * function isOwner($user_id)
	 *
	 * returns true of false if the user_id passed is the owner
	 *
	 * @param GUID $user_id
	 * @return boolean
	 */
	function isOwner($user_id){
		//if we don't have an id we must be the owner as we are creating it
		if(!isset($this->id)){
			return true;
		}
		//if there is an assigned_user that is the owner
		if(isset($this->assigned_user_id)){

			if($this->assigned_user_id == $user_id) return true;
			return false;
		}else{
			//other wise if there is a created_by that is the owner
			if(isset($this->created_by) && $this->created_by == $user_id){
				return true;
			}
		}



		return false;
	}
	/**
	 * function getOwnerWhere($user_id)
	 * gets there where statement for checking if a user is an owner
	 *
	 * @param GUID $user_id
	 * @return STRING
	 */
	function getOwnerWhere($user_id){
		if(isset($this->field_defs['assigned_user_id'])){
			return " $this->table_name.assigned_user_id ='$user_id' ";
		}
		if(isset($this->field_defs['created_by'])){
			return " $this->table_name.created_by ='$user_id' ";
		}
		return '';


	}

	/**
	 * function listviewACLHelper()
	 *
	 * this function is used in order to manage ListView links and if they should
	 * links or not based on the ACL permissions of the user
	 *
	 * @return ARRAY of STRINGS
	 */
	function listviewACLHelper(){
		$array_assign = array();
		if($this->ACLAccess('DetailView')){
			$array_assign['MAIN'] = 'a';
		}else{
			$array_assign['MAIN'] = 'span';
		}
		return $array_assign;

	}


	/**
	 * function toArray()
	 * returns this bean as an array
	 *
	 * @return array of fields with id, name, access and category
	 */
		function toArray($dbOnly = false, $stringOnly = false, $upperKeys=false){

		$arr = array();

		foreach($this->field_defs as $field=>$data){
			if( !$dbOnly || !isset($data['source']) || $data['source'] == 'db')
				if(!$stringOnly || is_string($this->$field))
					if($upperKeys){
						$arr[strtoupper($field)] = $this->$field;
					}else{

						$arr[$field] = $this->$field;
					}
		}

		return $arr;
	}

	/**
	 * function fromArray($arr)
	 * converts an array into an acl mapping name value pairs into files
	 *
	 * @param Array $arr
	 */
	function fromArray($arr){
		foreach($arr as $name=>$value){
			$this->$name = $value;
		}
	}



	function loadFromRow($arr){
		foreach($arr as $field=>$value){
			$this->$field = $value;
		}
		$this->populateFromRow($arr);
		$this->processed_dates_times = array();
		$this->check_date_relationships_load();
		$this->fill_in_additional_list_fields();

	}


   /*
     * Function to ensure that fields within order by clauses are properly qualified with
     * the tablename or table label.  Added as a result of port to SQL Server
     *
     */
    function create_qualified_order_by( $order_by, $qualify)
    {	// if the column is empty, but the sort order is defined, the value will throw an error, so do not proceed if no order by is given
    	if (empty($order_by)){
    		return $order_by;
    	}
    	$order_by_clause = " ORDER BY ";
		$tmp = explode(",", $order_by);
		$comma = ' ';
		foreach ( $tmp as $stmp) {
			$stmp = (substr_count($stmp, ".") > 0?trim($stmp):"$qualify." . trim($stmp));
			$order_by_clause .= $comma . $stmp;
			$comma = ", ";
		}
		return $order_by_clause;
    }


}

?>
