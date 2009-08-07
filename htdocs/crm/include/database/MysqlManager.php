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
* $Id: MysqlManager.php,v 1.25 2006/07/30 02:15:42 jacob Exp $
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

//Technically we can port all the functions in the latest bean to this file
// that is what PEAR is doing anyways.

class MysqlManager extends DBManager
{
    var $dbType = 'mysql';
    function MysqlManager(){
        parent::DBManager();
    }
    /**
     * checks for errors and will either log or die depending on the dieOnError
     *
     * @param STRING $msg - message to log
     * @param BOOLEAN $dieOnError - should die on error
     * @return BOOLEAN - error occured returns true
     */
    function checkError($msg='', $dieOnError=false){
        if(parent::checkError($msg, $dieOnError)){
            return true;
        }
        if (mysql_errno()){
            if($this->dieOnError || $dieOnError){
                $GLOBALS['log']->fatal("MySQL error ".mysql_errno().": ".mysql_error());
                sugar_die ($msg."MySQL error ".mysql_errno().": ".mysql_error());
            }else{
                $this->last_error = $msg."MySQL error ".mysql_errno().": ".mysql_error();
                $GLOBALS['log']->error("MySQL error ".mysql_errno().": ".mysql_error());

            }
            return true;
        }
        return false;
    }

    /**
     * Performs database query given an sql string and returns a mysql result
     *
     * @param STRING $sql -  query to be handled 
     * @param boolean $dieOnError - exit if an error occurs
     * @param string $msg - string to log if an error occurs
     * @param boolean  $suppress - suppress error reporting 
     * @return mysql_result
     */
    function query($sql, $dieOnError=false, $msg='', $suppress=false, $autofree=false){

        global $sql_queries;
        $sql_queries++;
        $GLOBALS['log']->info('Query:' . $sql);
        $this->checkConnection();
        //$this->freeResult();
        $this->query_time = microtime();
        $this->lastsql = $sql;
        if($suppress==true){








        } else {
            $result = mysql_query($sql);
        }

        $this->lastmysqlrow = -1;

        $this->query_time = microtime_diff($this->query_time, microtime());
        $GLOBALS['log']->info('Query Execution Time:'.$this->query_time);
        $this->dump_slow_queries($sql);
        $this->checkError($msg.' Query Failed:' . $sql . '::', $dieOnError);
        if($autofree){
            $this->lastResult[] =& $result;
        }
        return $result;
    }

    /**
	 * Return the results of the query with limits applied
	 *
	 * @param STRING $sql
	 * @param INT $start
	 * @param INT $count
	 * @param BOOLEAN $dieOnError
	 * @param STRING $msg
	 * @return mysql_result
	 *
	 */
    function limitQuery($sql,$start,$count, $dieOnError=false, $msg=''){
        if ($start < 0) $start=0;
        $GLOBALS['log']->debug('Limit Query:' . $sql. ' Start: ' .$start . ' count: ' . $count);
        $sql = "$sql LIMIT $start,$count";
        $this->lastsql = $sql;
			
        if(!empty($GLOBALS['sugar_config']['check_query'])){
            $this->checkQuery($sql);
        }

        return $this->query($sql, $dieOnError, $msg);
    }


    /**
     * This function will check a query and look for any possible issues that may occur with the query interms of 
     *
     * @param STRING $sql - Query to be run
     * @return Boolean  true if an index is found false otherwise
     */
    function checkQuery($sql){
        $sql = 'EXPLAIN ' . $sql;
        $result =  $this->query($sql);
        $badQuery = array();
        while($row = $this->fetchByAssoc($result)){
            if(empty($row['table'])) continue;
            $badQuery[$row['table']] = '';
            if(strtoupper($row['type']) == 'ALL'){
                $badQuery[$row['table']]  .=  ' Full Table Scan;';
            }
            if(empty($row['key'])){
                $badQuery[$row['table']] .= ' No Index Key Used;';
            }else{

            }
            if(!empty($row['Extra']) && substr_count($row['Extra'], 'Using filesort') >0){
                $badQuery[$row['table']] .= ' Using FileSort;';
            }
            if(!empty($row['Extra']) && substr_count($row['Extra'], 'Using temporary') >0){
                $badQuery[$row['table']] .= ' Using Temporary Table;';
            }
        }
        if(!empty($badQuery)){
            foreach($badQuery as $table=>$data ){
                if(!empty($data)){
                    $warning = ' Table:' . $table . ' Data:' . $data;



                    if(!empty($GLOBALS['sugar_config']['check_query_log'])){
                        $GLOBALS['log']->fatal($sql);
                        $GLOBALS['log']->fatal('CHECK QUERY:' .$warning);
                    }else{
                        $GLOBALS['log']->warn('CHECK QUERY:' .$warning);
                    }
                }
            }
        }
    }





    function freeResult($result=false){
        if(!$result && $this->lastResult){
            $result = current($this->lastResult);
            while($result){
                mysql_freeresult($result);
                $result = next($this->lastResult);
            }
            $this->lastResult = array();
        }
        if($result){
            mysql_freeresult($result);
        }
    }


    function getOne($sql, $dieOnError=false, $msg=''){
        $GLOBALS['log']->info("Get One: . |$sql|");
        $this->checkConnection();
        $queryresult = $this->query($sql, $dieOnError, $msg);
        if (!$queryresult) $result = false;
        else $result = mysql_result($queryresult,0);
        $this->checkError($msg.' Get One Failed:' . $sql . '::', $dieOnError);
        return $result;
    }

    /**
     * Returns the description of fields based on the result
     *
     * @param RESULT RESOURCE $result
     * @param boolean $make_lower_case
     * @return ARRAY - field array
     */
    function getFieldsArray($result, $make_lower_case=false)
    {
        $field_array = array();

        if(! isset($result) || empty($result))
        {
            return 0;
        }
        $i = 0;
        while ($i < mysql_num_fields($result))
        {

            $meta = mysql_fetch_field($result, $i);

            if (!$meta)
            {
                return 0;
            }

            array_push($field_array,$meta->name);

            $i++;
        }

        return $field_array;

    }

    /**
     * Returns the number of rows returned by the result
     *
     * @param RESULT RESOURCE $result
     * @return int
     */
    function getRowCount(&$result){
        if(isset($result) && !empty($result)){
            return mysql_numrows($result);
        }
        return 0;



    }

    /**
     * Returns the number of rows affected
     *
     * @return INT
     */
    function getAffectedRowCount(){
        return mysql_affected_rows();
    }

    /**
     * will return the associative array of the row for a query or false if more than one row was returned
     *
     * @param STRING $sql
     * @param BOOLEAN $dieOnError
     * @param STRING $msg
     * @param BOOLEAN $encode
     * @return ARRAY - associative array of the row or false
     */
    function requireSingleRow($sql, $dieOnError=false,$msg='', $encode=true){
        $result = $this->limitQuery($sql,0,2, $dieOnError, $msg);
        $count = 0;
        while($row = $this->fetchByAssoc($result)){
            $count++;
        }
        if($count > 1){
            return false;
        }
        return $row;
    }

    /**
     * fetchs the associative array from a database result
     *
     * @param RESULT RESOURCE $result - database result
     * @param ROW NUMBER $rowNum - row number
     * @param BOOLEAN $encode - convert everything for html display
     * @return ARRAY - associative array
     */
    function fetchByAssoc(&$result, $rowNum = -1, $encode=true){
        if(!$result)return false;
        if($result && $rowNum > -1){
            if($this->getRowCount($result) > $rowNum){
                mysql_data_seek($result, $rowNum);
            }
            $this->lastmysqlrow = $rowNum;
        }

        $row = mysql_fetch_assoc($result);

        if($encode && $this->encode && is_array($row))return array_map('to_html', $row);
        return $row;
    }

    /**
     * Returns an array of table for this database
     * @return	$tables		an array of with table names
     * @return	false		if no tables found
     */
    function getTablesArray() {
        global $sugar_config;
        $GLOBALS['log']->debug('PearDatabase fetching table list');

        $this->checkConnection();

        if($this->database) {
            $tables = array();


            $r = $this->query('SHOW TABLES');
            if(is_resource($r)) {
                while($a = $this->fetchByAssoc($r)) {
                    $key = 'Tables_in_'.$sugar_config['dbconfig']['db_name'];
                    $tables[] = $a[$key];
                }
                return $tables;
            }
        }


        return false; // no database available
    }

    /**
     * Returns the database version 
     *
     * @return STRING -Database Version
     */
    function version() {
        $result = $this->query("SELECT version() version");
        $row=$this->fetchByAssoc($result);
        return ($row['version']);
    }


    /**
     * Checks if a table with the name $tableName exists and returns true if it does or false otherwise
     *
     * @param STRING $tableName
     * @return BOOLEAN
     */
    function tableExists($tableName){

        $GLOBALS['log']->info("tableExists: $tableName");

        $this->checkConnection();

        if ($this->database){
            $result = $this->query("SHOW TABLES LIKE '".$tableName."'");
            return ($this->getRowCount($result) == 0) ? false : true;

        }
        return false;
    }

    /**
     * Encodes a string for storing in the database
     *
     * @param STRING $string
     * @param unknown_type $isLike
     * @return STRING
     */
    function quote($string,$isLike=true){
        global $sugar_config;
        $string = from_html($string);
        $string = mysql_escape_string($string);
        return $string;
    }

    /**
     * will quote the strings of the passed in array
     * The array must only contain strings
     *
     * @param ARRAY $array
     * @param unknown_type $isLike
     */
    function arrayQuote(&$array, $isLike=true) {
        for($i = 0; $i < count($array); $i++){
            $array[$i] = MysqlManager::quote($array[$i], $isLike);
        }
    }
    /**
     * Takes in the database settings and opens a database connection based on those
     * will open either a persistent or non-persistent connection.
     * If a persistent connection is desired but not available it will defualt to non-persistent
     * 
     * configOptions must include
     * db_host_name - server ip 
     * db_user_name - database user name
     * db_password - database password
     *
     * @param ARRAY $configOptions - array of options 
     *  
     * @param boolean $dieOnError
     */
    function connect($configOptions, $dieOnError = false){
        global $sugar_config;


        if ($sugar_config['dbconfigoption']['persistent'] == true) {
            $this->database =@mysql_pconnect($configOptions['db_host_name'],$configOptions['db_user_name'],$configOptions['db_password']);
        }

        if(!$this->database){
            $this->database = mysql_connect($configOptions['db_host_name'],$configOptions['db_user_name'],$configOptions['db_password']) or sugar_die("Could not connect to server ".$configOptions['db_host_name']." as ".$configOptions['db_user_name'].".".mysql_error());
            if($this->database  && $sugar_config['dbconfigoption']['persistent'] == true){
                $_SESSION['administrator_error'] = "<B>Severe Performance Degradation: Persistent Database Connections not working.  Please set \$sugar_config['dbconfigoption']['persistent'] to false in your config.php file</B>";
            }
        }
        @mysql_select_db($configOptions['db_name']) or sugar_die( "Unable to select database: " . mysql_error());
        
		// cn: using direct calls to prevent this from spamming the Logs
		mysql_query("SET CHARACTER SET utf8"); // no quotes around "[charset]"
		mysql_query("SET NAMES 'utf8'");

        if($this->checkError('Could Not Connect:', $dieOnError))
        $GLOBALS['log']->info("connected to db");

        $GLOBALS['log']->info("Connect:".$this->database);

    }

    /**
	 * Frees Results and disconnects the database
	 *
	 */
    function disconnect() {
        if(isset($this->database)){
            $this->freeResult();
            mysql_close($this->database);
            unset($this->database);
        }
    }

}

?>
