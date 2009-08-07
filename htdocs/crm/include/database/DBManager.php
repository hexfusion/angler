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
* $Id: DBManager.php,v 1.46 2006/08/26 01:01:41 roger Exp $
* Description: This file handles the Data base functionality for the application.
* It acts as the DB abstraction layer for the application. It depends on helper classes
* which generate the necessary SQL. This sql is then passed to PEAR DB classes.
* The helper class is chosen in DBManagerFactory, which is driven by 'db_type' in 'dbconfig' under config.php.
*
* All the functions in this class will work with any bean which implements the meta interface.
* The passed bean is passed to helper class which uses these functions to generate correct sql.
*
* The meta interface has the following functions:
* getTableName()	        	Returns table name of the object.
* getFieldDefinitions()	    	Returns a collection of field definitions in order.
* getFieldDefintion(name)		Return field definition for the field.
* getFieldValue(name)	    	Returns the value of the field identified by name.
*                           	If the field is not set, the function will return boolean FALSE.
* getPrimaryFieldDefinition()	Returns the field definition for primary key
*
* The field definition is an array with the following keys:
*
* name 		This represents name of the field. This is a required field.
* type 		This represents type of the field. This is a required field and valid values are:
*      		�	int
*      		�	long
*      		�	varchar
*      		�	text
*      		�	date
*      		�	datetime
*      		�	double
*      		�	float
*      		�	uint
*      		�	ulong
*      		�	time
*      		�	short
*      		�	enum
* length	This is used only when the type is varchar and denotes the length of the string.
*  			The max value is 255.
* enumvals  This is a list of valid values for an enum separated by "|".
*			It is used only if the type is �enum�;
* required	This field dictates whether it is a required value.
*			The default value is �FALSE�.
* isPrimary	This field identifies the primary key of the table.
*			If none of the fields have this flag set to �TRUE�, 
*			the first field definition is assume to be the primary key.
*			Default value for this field is �FALSE�.
* default	This field sets the default value for the field definition.
*
*
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/

require_once('include/database/DBManagerFactory.php');

class DBManager
{
    var $helper;
    var $tableName;
    var $database = null;
    var $dieOnError = false;
    var $encode = true;
    var $query_time = 0;
    var $lastmysqlrow = -1;
    var $last_error = '';
    var $lastResult = array();
    function DBManager(){

        global $sugar_config;


        $my_db_helper = 'MysqlHelper';
        if( $sugar_config['dbconfig']['db_type'] == "oci8" ){



        }
        if( $sugar_config['dbconfig']['db_type'] == "mssql" )
		{
           $my_db_helper = 'MssqlHelper';
		}
        static $helper;
        if(empty($helper)){
            $helper = 	new $my_db_helper();
        }
        $this->helper = $helper;
    }

    function &getInstance($instanceName = ''){
        global $sugar_config;
        static $count;
        static $old_count;
        global $dbinstances;




        $instanceName = 'db';
        $config = $sugar_config['dbconfig'];



        if(!isset($dbinstances)){
            $dbinstances = array();
        }
        
        if(!isset($dbinstances[$instanceName])){





















            $my_db_manager = 'MysqlManager';
            if( $config['db_type'] == "oci8" ){



            }else  if( $config['db_type'] == "mssql" ){
                $my_db_manager = 'MssqlManager';
            }
            if(!empty($config['db_manager'])){
                $my_db_manager = $config['db_manager'];
            }









 				DBManagerFactory::load_db_manager_class($my_db_manager);
                $dbinstances[$instanceName] = new $my_db_manager();
                $dbinstances[$instanceName]->connect($config, true);
                $dbinstances[$instanceName]->count_id = $count;
                $dbinstances[$instanceName]->references = 0;
                $dbinstances[$instanceName]->helper->db = $dbinstances[$instanceName];




        }else{
            $old_count++;
            $dbinstances[$instanceName]->references = $old_count;



        }

        return $dbinstances[$instanceName];
    }

    function getHelper(){
        return $this->helper;
    }

    function checkError($msg='', $dieOnError=false){
        if(!isset($this->database)){
            $GLOBALS['log']->error("Database Is Not Connected");
            return true;
        }
        return false;
    }

    /**
	* @desc This method is called by every method that runs a query.
	*	If slow query dumping is turned on and the query time is beyond
	*	the time limit, we will log the query. This function may do
	*	additional reporting or log in a different area in the future.
	*/
    function dump_slow_queries($query)
    {
        global $sugar_config;

        $do_the_dump = isset($sugar_config['dump_slow_queries']) ? $sugar_config['dump_slow_queries'] : 0;
        $slow_query_time_msec = isset($sugar_config['slow_query_time_msec']) ? $sugar_config['slow_query_time_msec'] : 5000;

        if($do_the_dump)
        {
            if($slow_query_time_msec < ($this->query_time * 1000))
            {
                // Then log both the query and the query time
                $GLOBALS['log']->fatal('Slow Query (time:'.$this->query_time."\n".$query);
            }
        }
    }
    
     /**
     * This function will scan order by to ensure that any field being ordered by is. It will throw a warning error to the log file - fatal if slow query logging is enabled
     *
     * @param STRING $sql - Query to be run
     * @param unknown_type $object_name
     * @return Boolean  true if an index is found false otherwise
     */
    function checkQuery($sql, $object_name=false){

        preg_match_all("'.* FROM ([^ ]*).* ORDER BY (.*)'is", $sql, $match);
        $indices = false;
        if(!empty($match[1][0])){
            $table = $match[1][0];
        }else{
            return false;
        }
        if(!empty($object_name) && !empty($GLOBALS['dictionary'][$object_name])){
            $indices = $GLOBALS['dictionary'][$object_name]['indices'];
        }
        if(empty($indices)){
            reset($GLOBALS['dictionary']);
            $current = current($GLOBALS['dictionary']);
            while($current && !$indices){
                if($current['table'] == $table){
                    $indices = $current['indices'];
                    break;
                }
                $current = next($GLOBALS['dictionary']);
            }
        }
        if(empty($indices)){
            $GLOBALS['log']->warn('CHECK QUERY: Could not find index definitions for table ' . $table);
            return false;
        }
        if(!empty($match[2][0])){
          
            $orderBys = explode(' ', $match[2][0]);
            foreach($orderBys as $orderBy){
                $orderBy = trim($orderBy);
                if(empty($orderBy))continue;
                $orderBy=  strtolower($orderBy);
                if($orderBy == 'asc' ||$orderBy == 'desc')continue;
               
                $orderBy = str_replace(array($table . '.', ','), '', $orderBy);
               
                foreach($indices as $index){
                    if(empty($index['db']) || $index['db'] == $this->dbType){
                        foreach($index['fields'] as $field){
                            if($field == $orderBy){
                                return true;
                            }
                        }

                    }
                }
                $warning = 'Missing Index For Order By Table: ' . $table . ' Order By:' . $orderBy ;
                if(!empty($GLOBALS['sugar_config']['dump_slow_queries'])){
                     $GLOBALS['log']->fatal('CHECK QUERY:' .$warning);
                }else{
                    $GLOBALS['log']->warn('CHECK QUERY:' .$warning);
                }



            }
        }
       
    }
    
    
    function getQueryTime(){
        return $this->query_time;
    }

    function checkConnection(){
        $this->last_error = '';
        if(!isset($this->database)) $this->connect();
    }

    function setDieOnError($value){
        $this->dieOnError = $value;
    }


    /**
	* This method implements a generic insert for any bean.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
    function insert($bean){
        $sql = $this->helper->insertSQL($bean);
        $this->tableName = $bean->getTableName();
        $this->insertSQL($sql);
    }

    /**
	* This method implements a generic update for any bean.
	* The where is an array of values with the keys as names of fields.
	* If we want to pass multiple values for a name, pass it as an array
	* If where is not passed, it defaults to id of table
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function update($bean, $where = array()){
        $sql = $this->helper->updateSQL($bean, $where);
        $this->tableName = $bean->getTableName();
        $this->updateSQL($sql);
    }

    /**
	* This method implements a generic delete for any bean idnetified by id.
	* The where is an array of values with the keys as names of fields.
	* If we want to pass multiple values for a name, pass it as an array
	* If where is not passed, it defaults to id of table
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function delete($bean, $where = array()){
        $sql = $this->helper->deleteSQL($bean, $where);
        $this->tableName = $bean->getTableName();
        $this->deleteSQL($sql);
    }

    /**
	* This method implements a generic retrieve for any bean identified by id.
	* The where is an array of values with the keys as names of fields.
	* If we want to pass multiple values for a name, pass it as an array
	* If where is not passed, it defaults to id of table
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function retrieve($bean, $where = array()){
        $sql = $this->helper->retrieveSQL($bean, $where);
        $this->tableName = $bean->getTableName();
        return $this->retrieveSQL($sql);
    }

    /**
    * This method implements a generic retrieve for a collection of beans.
    * These beans will be joined in the sql by the key attribute of field defs.
    * 
    * Cols is an array of columns to be returned with the keys as names of bean as identified by 
    * get_class of bean. Values of this array is the array of fieldDefs to be returned for a bean.
    * If an empty array is passed, all columns are selected.
    *  
    * Where is an array of values with the keys as names of bean as identified by get_class of bean
    * Each value at the first level is an array of values for that bean identified by name of fields.
    * If we want to pass multiple values for a name, pass it as an array
    * If where is not passed, all the rows will be returned.
    * 
    * Currently, this function does support outer joins.
    * 
    * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
    * All Rights Reserved.
    * Contributor(s): ______________________________________..
    */
    function retrieveView($beans, $cols = array(), $where = array()){
        $sql = $this->helper->retrieveViewSQL($beans, $cols, $where);

        $this->tableName = "View Collection"; // just use this string for msg
        return $this->retrieveSQL($sql);
    }


    /**
	* This method implements creation of a db table for a bean.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function createTable($bean){
        $sql = $this->helper->createTableSQL($bean);
        $this->tableName = $bean->getTableName();
        $this->createTableSQL($sql);
    }

    function createTableParams($tablename, $fieldDefs, $indices){
        if(!empty($fieldDefs)){

            $sql = $this->helper->createTableSQLParams($tablename, $fieldDefs, $indices);
            $this->tableName = $tablename;
            if($sql){
                $this->createTableSQL($sql);
            }
        }
    }

    /**
	* This method implements creation of a db table for a bean.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function repairTable($bean, $execute = true){
        $indices = $bean->getIndices();
        $fielddefs = $bean->getFieldDefinitions();
        $tablename = $bean->getTableName();
        return $this->repairTableParams($tablename, $fielddefs,$indices, $execute);
    }
    function repairTableParams($tablename,  $fielddefs,$indices, $execute = true){

        global $table_descriptions;
        //if the table does not exist create it and we are done
        $sql = "/* Table : $tablename */\n";
        if(!$this->tableExists($tablename)){

            $createtablesql = $this->helper->createTableSQLParams($tablename, $fielddefs, $indices);
            if($execute &&$createtablesql){
                $this->createTableParams($tablename, $fielddefs, $indices);
            }
            return $createtablesql;
        }

        //first create a table to compare to



        if(empty($table_descriptions)){
            $table_descriptions = array();
        }
        unset($table_descriptions['repair_table']);
        if($this->tableExists('repair_table')){
            $this->dropTableName('repair_table');
        }


        $this->createTableParams('repair_table', $fielddefs, $indices);
        if(empty($fielddefs)){
            return $sql;
        }
        $take_action = false;
        $sql .=	"/*COLUMNS/*\n";
        foreach($fielddefs as $value){
            $name = $value['name'];
            if(isset($value['source']) && $value['source'] != 'db'){
                continue;
            }
            $result = $this->compareFieldInTables($name , $tablename, 'repair_table');
            switch($result['msg']){
                case 'match': break;//all is good
                case 'not_exists_table2':break; //doesn't exist in the repair table but exists in the table let it slide might be a customizatio
                case 'not_exists_table1':
                    $sql .=	"/*MISSING IN DATABASE - $name -  ROW/*\n";
                    $sql .= $this->helper->addColumnSQL($tablename, $value) .  "\n";
                    if($execute){
                        $this->addColumn($tablename, $value);
                    }
                    $take_action=true;
                    break; //ok we need this field lets create it
                case 'no_match':
                    $sql .=	"/*MISMATCH WITH DATABASE - $name -  ROW ";
                    foreach($result['table1'] as $rKey=>$rValue){
                        $sql .=	"[$rKey] => '$rValue'  ";
                    }
                    $sql .=	"*/\n";
                    $sql .=	"/* VARDEF - $name -  ROW";
                    foreach($result['table2'] as $rKey=>$rValue){
                        $sql .=	"[$rKey] => '$rValue'  ";
                    }
                    $sql .=	"*/\n";
                    $sql .= $this->helper->alterColumnSQL($tablename, $value) .  "\n";
                    if($execute){
                        $this->alterColumn($tablename, $value);
                    }
                    $take_action=true;
                    break; //fields are different lets alter it;
            }

        }
        $sql .=	"/*INDEXES/*\n";
        foreach($indices as $value){
            $name = $value['name'];
            //don't bother checking primary nothing we can do about them
            if(isset($value['type']) && $value['type'] == 'primary'){
                continue;
            }
            $result = $this->compareIndexInTables($name , $tablename, 'repair_table');
            switch($result['msg']){
                case 'match': break;//all is good
                case 'not_exists_table2':break; //doesn't exist in the repair table but exists in the table let it slide might be a customizatio
                case 'not_exists_table1':
                    $sql .=	"/*MISSING INDEX IN DATABASE - $name -{$value['type']}  ROW/*\n";
                    $sql .= $this->addIndexes($tablename,array($value), $execute) .  "\n";
                    $take_action=true;
                    break; //ok we need this field lets create it
                case 'no_match':
                    $sql .=	"/*INDEX MISMATCH WITH DATABASE - $name -  ROW ";
                    foreach($result['table1'] as $n1=>$t1){
                        $sql .=	"<$n1>";
                        foreach($t1 as $rKey=>$rValue){
                            $sql .=	"[$rKey] => '$rValue'  ";
                        }
                    }
                    $sql .=	"*/\n";
                    $sql .=	"/* VARDEF - $name -  ROW";
                    foreach($result['table2'] as $n1=>$t1){
                        $sql .=	"<$n1>";
                        foreach($t1 as $rKey=>$rValue){
                            $sql .=	"[$rKey] => '$rValue'  ";
                        }
                    }
                    $sql .=	"*/\n";
                    $sql .= $this->modifyIndexes($tablename,array($value), $execute) .  "\n";
                    $take_action=true;

                    break; //fields are different lets alter it;
            }

        }
        //cleanup our repair table
        $this->dropTableName('repair_table');
        if($take_action)
        return $sql;
        return '';
    }

    function describeField($name, $tablename){
        //MYSQL implementation
        global $table_descriptions;
        if(isset($table_descriptions[$tablename]) && isset($table_descriptions[$tablename][$name])){
            return 	$table_descriptions[$tablename][$name];
        }
        $table_descriptions[$tablename] = array();
        $sql = "DESCRIBE $tablename";
        $result = $this->query($sql);
        while($row = $this->fetchByAssoc($result) ){
            $table_descriptions[$tablename][$row['Field']] = $row;
        }
        if(isset($table_descriptions[$tablename][$name])){
            return 	$table_descriptions[$tablename][$name];
        }
        return array();
    }

    function compareFieldInTables($name, $table1, $table2){


        $row1 = $this->describeField($name, $table1);
        $row2 = $this->describeField($name, $table2);
        $ignore_filter = array('Key'=>1);
        if($row1){
            //Exists on table1 but not table2
            if(!$row2){
                return array('msg'=>'not_exists_table2', 'table1'=>$row1, 'table2'=>$row2);
            }else{
                if(sizeof($row1) != sizeof($row2)){
                    return array('msg'=>'no_match', 'table1'=>$row1, 'table2'=>$row2);
                }
                foreach($row1 as $key=>$value){
                    //ignore keys when checking we will check them when we do the index check
                    if( !isset($ignore_filter[$key]) && $row1[$key] != $row2[$key]){
                        return array('msg'=>'no_match', 'table1'=>$row1, 'table2'=>$row2);
                    }
                }
                return array('msg'=>'match', 'table1'=>$row1, 'table2'=>$row2);
            }

        }else{
            return array('msg'=> 'not_exists_table1' , 'table1'=>$row1, 'table2'=>$row2);
        }
        return array('msg'=> 'error' , 'table1'=>$row1, 'table2'=>$row2);

    }

    function describeIndex($name, $tablename){
        //MYSQL implementation
        global $table_descriptions;
        if(isset($table_descriptions[$tablename]) && isset($table_descriptions[$tablename]['indexes']) && isset($table_descriptions[$tablename]['indexes'][$name])){
            return 	$table_descriptions[$tablename]['indexes'][$name];
        }

        $table_descriptions[$tablename]['indexes'] = array();
        $sql = "SHOW INDEX FROM $tablename";
        $result = $this->query($sql);

        while($row = $this->fetchByAssoc($result) ){
            if(!isset($table_descriptions[$tablename]['indexes'][$row['Key_name']])){
                $table_descriptions[$tablename]['indexes'][$row['Key_name']] = array();
            }
            $table_descriptions[$tablename]['indexes'][$row['Key_name']]['Column_name'] = $row;
        }
        if(isset($table_descriptions[$tablename]['indexes'][$name])){
            return 	$table_descriptions[$tablename]['indexes'][$name];
        }
        return array();
    }

    function compareIndexInTables($name, $table1, $table2){
        $row1 = $this->describeIndex($name, $table1);
        $row2 = $this->describeIndex($name, $table2);
        $ignore_filter = array('Table'=>1, 'Seq_in_index'=>1,'Cardinality'=>1, 'Sub_part'=>1, 'Packed'=>1, 'Comment'=>1);

        if($row1){
            //Exists on table1 but not table2
            if(!$row2){
                return array('msg'=>'not_exists_table2', 'table1'=>$row1, 'table2'=>$row2);
            }else{
                if(sizeof($row1) != sizeof($row2)){
                    return array('msg'=>'no_match', 'table1'=>$row1, 'table2'=>$row2);
                }
                foreach($row1 as $fname=>$fvalue){
                    if(!isset($row2[$fname])){
                        return array('msg'=>'no_match', 'table1'=>$row1, 'table2'=>$row2);
                    }
                    foreach($fvalue as $key=>$value){
                        //ignore keys when checking we will check them when we do the index check
                        if(!isset($ignore_filter[$key]) && $row1[$fname][$key] != $row2[$fname][$key]){
                            return array('msg'=>'no_match', 'table1'=>$row1, 'table2'=>$row2);
                        }
                    }
                }
                return array('msg'=>'match', 'table1'=>$row1, 'table2'=>$row2);
            }

        }else{
            return array('msg'=> 'not_exists_table1' , 'table1'=>$row1, 'table2'=>$row2);
        }
        return array('msg'=> 'error' , 'table1'=>$row1, 'table2'=>$row2);
    }


    /**
	* This method creates an index identified by name on the given fields.
	* Non Unique index is created if $unique is set to FALSE
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function createIndex($bean, $fieldDefs, $name, $unique=TRUE){
        $sql = $this->helper->createIndexSQL($bean, $fieldDefs, $name, $unique);
        $this->tableName = $bean->getTableName();
        $this->createIndexSQL($sql,$fieldDefs, $name, $unique);
    }

    function addIndexes($tablename, $indexes , $execute = true){
        $alters = $this->helper->keysSQL( $indexes,true, 'ADD' );
        $sql = "ALTER TABLE $tablename $alters";
        if($execute) $this->query($sql);
        return $sql;
    }

    function dropIndexes($tablename, $indexes , $execute = true){
        $sql = '';

        foreach($indexes as $index){
            $name =$index['name'];
            if($index['type'] == 'primary'){
                $name = 'PRIMARY KEY';
            }else{
                $name = "INDEX $name";
            }
            if(empty($sql)){
                $sql .= " DROP $name ";
            }else{
                $sql .= ", DROP $name ";
            }

        }
        if(!empty($sql)  ){
            $sql = "ALTER TABLE $tablename $sql";
            if($execute) $this->query($sql);
        }
        return $sql;
    }

    function modifyIndexes($tablename, $indexes, $execute = true){
        $sql = $this->dropIndexes($tablename, $indexes, $execute);
        $sql .= "\n";
        $sql .= $this->addIndexes($tablename, $indexes, $execute);
        return $sql;
    }

    /**
	* This method adds a column to table identified by field def.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function addColumn($tablename, $fieldDefs){
        $this->tableName = $tablename;
        $sql = $this->helper->addColumnSQL($this->tableName, $fieldDefs);
        $this->addColumnSQL($sql, $fieldDefs);
    }

    /**
	* This method alters old column identified by oldFieldDef to new fieldDef.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function alterColumn($tablename, $newFieldDef){
        $this->tableName = $tablename;
        $sql = $this->helper->alterColumnSQL($this->tableName, $newFieldDef);
        $this->alterColumnSQL($sql, $newFieldDef);
    }

    /**
	* This method drop a table.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function dropTable($bean){
        $this->table_name =  $bean->getTableName();
        $this->dropTableName( $this->table_name);
    }

    function dropTableName($name){
        $sql = $this->helper->dropTableNameSQL($name);
        $this->dropTableSQL($sql);
    }

    /**
	* This method deletes a column identified by fieldDef.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function deleteColumn($bean, $fieldDefs){
        $sql = $this->helper->deleteColumnSQL($bean, $fieldDefs);
        $this->tableName = $bean->getTableName();
        $this->deleteColumnSQL($sql, $fieldDefs);
    }

    /*****************************************************************************
    ** SQL Functions
    */

    /**
	* This method implements a generic insert for any bean.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
    function insertSQL($sql){
        $msg = "Error inserting into table: ".$this->tableName;
        $this->executeQuery($sql, $msg);
    }

    /**
	* This method implements a generic update for any bean.
	* Updates are based for the row identified by primary key only.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function updateSQL($sql){
        $msg = "Error updating table: ".$this->tableName. ":";
        $this->executeQuery($sql, $msg);
    }

    /**
	* This method implements a generic delete for any bean idnetified by id.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function deleteSQL($sql){
        $msg = "Error deleting from table: ".$this->tableName. ":";
        $this->executeQuery($sql, $msg);
    }

    /**
	* This method implements a generic retrieve for any bean identified by id.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function retrieveSQL($sql){
        $msg = "Error retriving values from table:".$this->tableName. ":";
        $result = $this->executeQuery($sql, $msg, true);

        return $result;
    }

    /**
	* This method implements creation of a db table for a bean.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function createTableSQL($sql){
        $msg = "Error creating table: ".$this->tableName. ":";
        $this->executeQuery($sql, $msg);
    }

    /**
	* This method creates an index identified by name on the given fields.
	* Non Unique index is created if $unique is set to FALSE
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function createIndexSQL($sql, $fieldDefs, $name, $unique=TRUE){
        $msg = "Error creating index $name on table: ".$this->tableName. ":";
        $this->executeQuery($sql, $msg);
    }

    /**
	* This method adds a column to table identified by field def.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function addColumnSQL($sql, $fieldDefs){
        if ($this->helper->isFieldArray($fieldDefs)){
            foreach ($fieldDefs as $fieldDef) $columns[] = $fieldDef['name'];
            $columns = implode(",", $columns);

        } else $columns = $fieldDefs['name'];

        $msg = "Error adding column(s) ".$columns." on table: ".$this->tableName. ":";
        $this->executeQuery($sql, $msg);
    }

    /**
	* This method alters old column identified by oldFieldDef to new fieldDef.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function alterColumnSQL($sql, $fieldDefs){
        if ($this->helper->isFieldArray($fieldDefs)){
            foreach ($fieldDefs as $fieldDef) $columns[] = $fieldDef['name'];
            $columns = implode(",", $columns);

        } else $columns = $fieldDefs['name'];

        $msg = "Error altering column(s) ".$columns." on table: ".$this->tableName. ":";
        $this->executeQuery($sql, $msg);
    }

    /**
	* This method drop a table.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function dropTableSQL($sql){
        $msg = "Error dropping table ".$this->tableName. ":";
        $this->executeQuery($sql, $msg);
    }

    /**
	* This method deletes a column(s) identified by fieldDefs.
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved.
	* Contributor(s): ______________________________________..
	*/
    function deleteColumnSQL($sql, $fieldDefs){
        $msg = "Error deleting column(s) ".$columns." on table: ".$this->tableName. ":";
        $this->executeQuery($sql, $msg);
    }

    /**
	* Fetches all the rows for a select query. Returns FALSE if query failed and 
	* DB_OK for all other queries
	*/
    function setResult($result){
        if (PEAR::isError($result) === true){
            $GLOBALS['log']->error($msg);
            $result = FALSE;
        } else if ($result != DB_OK){
            // must be a result
            $GLOBALS['log']->fatal("setResult:".$result);
            $row = array();
            $rows = array();
            while (ocifetchinto($result, $row, OCI_ASSOC|OCI_RETURN_NULLS|OCI_RETURN_LOBS)){
                $err = ocierror($result);
                if ($err == false) $rows[] = $row;
                else print_r($err);
            }
            $result = $rows;
        }
        $GLOBALS['log']->fatal("setResult: returning rows from setResult");
        return $result;
    }

    /** Private function to handle most of the sql statements which go as queries
	*/
    function executeLimitQuery($query, $start,$count, $dieOnError=false, $msg=''){
        $result = $this->limitQuery($query,$start,$count, $dieOnError, $msg);
        return $this->setResult($result);
    }

    /** Priate function to handle most of the sql statements which go as queries
	*/
    function executeQuery($query, $msg, $getRows=false){
        $result = $this->query($query,true,$msg);

        if ($getRows) return $this->setResult($result);
        // dd not get rows. Simply go on.
	}
    
    /*
    * Given a db_type return the correct DBHelper
    * 
    * @param db_type          the type of database being used
    *                        
    * return                   a DBHelper corresponding to the db_type
    */
    function configureHelper($db_type){
        $my_db_helper = 'MysqlHelper';
        
        if($db_type == "oci8" ){




        }else if($db_type == "mssql"){
            require_once('include/database/MssqlHelper.php');
            $my_db_helper = 'MssqlHelper';    
        }
        if($my_db_helper == 'MysqlHelper'){
            require_once('include/database/MysqlHelper.php');    
        }
        return new $my_db_helper();  
    }
	
    /*
    * Generate a set of Insert statements based on the bean given
    * 
    * @param bean          the bean from which table we will generate insert stmts
    * @param select_query  the query which will give us the set of objects we want to 
    *                          place into our insert statement   
    * @param start         the first row to query
    * @param count         the number of rows to query
    * @param table         the table to query from
    * @param db_type       the client db type    
    *                        
    * return                   a string containing the insert statement
    */
	function generateInsertSQL($bean, $select_query, $start, $count = -1, $table, $db_type, $is_related_query = false){
		global $sugar_config;
		$count_query = $bean->create_list_count_query($select_query);
		if(!empty($count_query))
		{
			// We have a count query.  Run it and get the results.
			$result = $this->query($count_query, true, "Error running count query for $this->object_name List: ");
			$assoc = $this->fetchByAssoc($result);
			if(!empty($assoc['count(*)']))
			{
				$rows_found = $assoc['count(*)'];
			}
		}
		if($count == -1){
			$count 	= $sugar_config['list_max_entries_per_page'];
		}
		$next_offset = $start + $count;
		
		$result = $this->limitQuery($select_query, $start, $count);
		$row_count = $this->getRowCount($result);
		// get basic insert
		$sql = "INSERT INTO ".$table;

		// get field definitions
		$fields = $bean->getFieldDefinitions();

		// get column names and values
		$row_array = array();
		$columns = array();
		$built_columns = false;
        //configure client helper
        $dbHelper = $this->configureHelper($db_type);
		while(($row = $this->fetchByAssoc($result)) != null)
		{
			$values = array();
            if(!$is_related_query){
    			foreach ($fields as $fieldDef)
    			{
    				if(isset($fieldDef['source']) && $fieldDef['source'] != 'db') continue;
    				$val = $row[$fieldDef['name']];
    		   			
    		   		//handle auto increment values here only need to do this on insert not create
               		if(isset($fieldDef['auto_increment']) && $fieldDef['auto_increment']){
               			$values[$fieldDef['name']] = $dbHelper->getAutoIncrement($bean->getTableName(), $fieldDef['name']);
               			if(!$built_columns){
               				$columns[] = $fieldDef['name'];
               			}
               		}
               		else if ($fieldDef['name'] == 'deleted'){
    		   			 $values['deleted'] = $val;
    		   			 if(!$built_columns){
               				$columns[] = 'deleted';
               			}
    		   		}
               		else
    		   		{
    		    		 // need to do some thing about types of values
						 if($db_type == 'mysql' && $val == '' && ($fieldDef['type'] == 'datetime' ||  $fieldDef['type'] == 'date' || $fieldDef['type'] == 'int')){
							$values[$fieldDef['name']] = 'null';
						 }else{
    		     			$values[$fieldDef['name']] = $dbHelper->massageValue($val, $fieldDef);
						 }
    		     		if(!$built_columns){
               				$columns[] = $fieldDef['name'];
               			}
    		   		}
    		   		
    			}
            }else{
               foreach ($row as $key=>$val)
               {
                    $values[$key] = "'$val'";
                    if(!$built_columns){
                        $columns[] = $key; 
                    }  
               } 
            }
			$built_columns = true;
			$row_array[] = $values;
		}

		//if (sizeof ($values) == 0) return ""; // no columns set

		// get the entire sql
		$sql .= "(".implode(",", $columns).") ";
		$sql .= "VALUES";
		for($i = 0; $i < count($row_array); $i++){
			$sql .= " (".implode(",", $row_array[$i]).")";
			if($i < (count($row_array) - 1)){
				$sql .= ", ";
			}
		}
		return array('data' => $sql, 'result_count' => $row_count, 'total_count' => $rows_found, 'next_offset' => $next_offset);
	}
	
	  /**
     * Disconnects all instances
     *
     */
    function disconnectAll() {
        global $dbinstances;
        if(!empty($dbinstances)){



        $dbinstances['db']->disconnect();








        }
        
    }


}

?>
