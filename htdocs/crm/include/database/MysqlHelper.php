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
* $Id: MysqlHelper.php,v 1.30 2006/08/24 17:27:12 ajay Exp $
* Description: This file handles the Data base functionality for the application specific
* to oracle database. It is called by the DBManager class to generate various sql statements.
*
* All the functions in this class will work with any bean which implements the meta interface.
* Please refer the DBManager documentation for the details.
*
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/
require_once('DBHelper.php');

class MysqlHelper extends DBHelper
{

	function MysqlHelper(){
        parent::DBHelper();
	}

	/**
	* This is a private (php does not support it as of 4.x) method.
	* It outputs a correct string for the sql statement according to value
	* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	* All Rights Reserved..
	* Contributor(s): ______________________________________..
	*/
	function massageValue($val, $fieldDef){
        if(!$val && $val !== '0') {
			return "''";
		}

        $type = $this->getFieldType($fieldDef);

		switch ($type){
		  case 'int':
		  case 'double':
		  case 'float':
		  case 'uint':
		  case 'ulong':
		  case 'long':
		  case 'short':
          case 'tinyint':
		    return $val;
		    break;
		}

		$qval = $this->quote($val);

		switch ($type){
		  case 'varchar':
          case 'char':
		  case 'text':
		  case 'enum':
          case 'blob':
          case 'clob':
          case 'id':
		  	return $qval;
		  	break;
		  case 'date':
		    return "$qval";
		    break;
		  case 'datetime':
		    return $qval;
		    break;
		  case 'time':
		    return "$qval";
		    break;
		}
		return $val;
	}

    /** returns the valid type for a column given the type in fieldDef
    */

    function getColumnType($type){
        $map = array( 'int'      => 'int'
                    , 'double'   => 'double'
                    , 'float'    => 'float'
                    , 'uint'     => 'int unsigned'
                    , 'ulong'    => 'bigint unsigned'
                    , 'long'     => 'bigint'
                    , 'short'    => 'smallint'
                    , 'varchar'  => 'varchar'
                    , 'text'     => 'text'
                    , 'date'     => 'date'
                    , 'enum'     => 'varchar'
                    , 'datetime' => 'datetime'
                    , 'time'     => 'time'
                    , 'bool'     => 'bool'
                    , 'tinyint'  => 'tinyint'
                    , 'char'  => 'char'
                    , 'blob'  => 'blob'
                    , 'decimal' => 'decimal'
                    , 'decimal2' => 'decimal'
                    , 'id' => 'char(36)'

                    );
        return $map[$type];
    }

    /** private function to get sql for a column
    */
    function oneColumnSQLRep($fieldDef, $ignoreRequired = false){
        $rep = parent::oneColumnSQLRep($fieldDef, $ignoreRequired);
        return $rep;
    }

    /**
    * A private function which generates the SQL for changing columns
    */
    function changeColumnSQL($tablename, $fieldDefs, $action, $ignoreRequired = false){

        if ($this->isFieldArray($fieldDefs)){
          foreach ($fieldDefs as $def) $columns[] = $this->oneColumnSQLRep($def, $ignoreRequired);
        } else {
          $columns[] = $this->oneColumnSQLRep($fieldDefs);
        }

        $columns = implode(",$action column ", $columns);
        $sql = "alter table $tablename $action column $columns";
        return $sql;
    }

    /**
    * This method generates sql that deletes a column identified by fieldDef.
    * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
    * All Rights Reserved.
    * Contributor(s): ______________________________________..
    */
    function deleteColumnSQL($bean, $fieldDefs){
        if ($this->isFieldArray($fieldDefs)) foreach ($fieldDefs as $fieldDef) $columns[] = $fieldDef['name'];
        else $columns[] = $fieldDefs['name'];
        $columns = implode(", drop column ", $columns);
        $sql = "alter table ".$bean->getTableName()." drop column $columns";
        return $sql;
    }

    /**
    * This method genrates sql for key statement for any bean identified by id.
    * The passes array is an array of field definitions or a field definition
    * itself. The keys generated will be either primary, foreign, unique, index
    * or none at all depending on the setting of the "key" parameter of a field definition
    * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
    * All Rights Reserved.
    * Contributor(s): ______________________________________..
    */
    function keysSQL( $indices, $alter_table = false, $alter_action = ''){
       // check if the passed value is an array of fields.
       // if not, convert it into an array
       if (!$this->isFieldArray($indices)) $indices[] = $indices;

        $columns = array();
        foreach ($indices as $index){
          if(!empty($index['db']) && $index['db'] != 'mysql')continue;
          $type = $index['type'];

          $name = $index['name'];

          if(is_array($index['fields'])) {
          	$fields = implode(", ", $index['fields']);
          }else{
          	$fields = $index['fields'];
          }

          switch ($type){
            case 'unique':
                $columns[] = " UNIQUE $name ($fields)";
                break;
            case 'primary':
                $columns[] = " PRIMARY KEY ($fields)";
                break;
            case 'index':
            case 'foreign':
            case 'alternate_key':
                // to do - here it is assumed that the primary key of the foreign
                // table will always be named 'id'. It must be noted though
                // that this can easily be fixed by referring to db dictionalry
                // to find the correct primary field name
                if($alter_table){
                	$columns[] = " INDEX $name ($fields)";
                }else{
                	$columns[] = " KEY $name ($fields)";
                }
                break;
          }
       }
       $columns =  implode(", $alter_action ", $columns);
       if(!empty($alter_action)){
       	$columns=$alter_action . ' '. $columns;
       }
       return $columns;
    }

    function quote($string){
        $string = from_html($string);
        $string = mysql_escape_string($string);
        return "'$string'";
    }

    function escape_quote($string){
        $string = from_html($string);
        $string = mysql_escape_string($string);
        return $string;
    }

   	function setAutoIncrement($table, $field_name){
		return "auto_increment";
	}
    /**
     * Returns definitions of all indies for passed table. return will is a multi-dimensional array that
     * categorizes the index definition by types, unique, primary and index.
     * return format $indices = array ('index1'=>('name'=>'index1','type'=>'primary','fields'=>array('field1','field2'))
     * This format is similar to how indicies are defined in vardef file.
     */

    function get_indices($tablename) {
        $tablename=$tablename;
        $indices=array();
        //find all unique indexes and primary keys.
        $query="SHOW INDEX FROM $tablename";
        $result=$this->db->query($query);
        while (($row=$this->db->fetchByAssoc($result)) !=null) {
            $index_type='index';
            if ($row['Key_name'] =='PRIMARY') {
                $index_type='primary';  
            }   
            $indices[strtolower($row['Key_name'])]['name']=strtolower($row['Key_name']);
            $indices[strtolower($row['Key_name'])]['type']=$index_type;
            $indices[strtolower($row['Key_name'])]['fields'][]=strtolower($row['Column_name']);
        }
        return $indices;
    }
    /**
     * function generate alter constraint statement given a tabe name and vardef definition.
     * supports both adding and droping a constraint.
     */
    function add_drop_constraint($table,$definition, $drop=false) {        
        $type=$definition['type'];
        $fields=implode(',',$definition['fields']);
        $name=$definition['name'];
        $foreignTable=isset($definition['foreignTable']) ? $definition['foreignTable'] : array();
        switch ($type){
            // generic indices
            case 'index':
            case 'alternate_key':
                    if ($drop) {
                        $sql = "DROP INDEX {$name} ";
                    } else {
                        $sql = "CREATE INDEX {$name} ON {$table} ({$fields})";
                    }
                    break;
            // constraints as indices
            case 'unique':
                    if ($drop){
                        $sql = "ALTER TABLE {$table} DROP INDEX $name";
                    } else {
                        $sql = "ALTER TABLE {$table} ADD CONSTRAINT UNIQUE {$name} ({$fields})";
                    }
                    break;
            case 'primary':
                    if ($drop) {
                        $sql = "ALTER TABLE {$table} DROP PRIMARY KEY";
                    } else {
                        $sql = "ALTER TABLE {$table} ADD CONSTRAINT PRIMARY KEY ({$fields})";
                    }
                    break;
            case 'foreign':
                    if ($drop) {
                        $sql = "ALTER TABLE {$table} DROP FOREIGN KEY ({$fields})";
                    } else {
                        $sql = "ALTER TABLE {$table} ADD CONSTRAINT FORIEGN KEY {$name} ({$fields}) REFERENCES {$foreignTable}({$foreignfields})";
                    }
                    break;
        }
        return $sql;
    }

    function rename_index($old_definition,$new_definition,$table_name) {
        $ret_commands=array();
        $ret_commands[]=$this->add_drop_constraint($table_name,$old_definition,true);
        $ret_commands[]=$this->add_drop_constraint($table_name,$new_definition);
        return $ret_commands;
    }

    /* Function returns a count coulmns in the supplied table name, function is used primarily by 
     * custom module code.
     */
    function number_of_columns($table_name) {
        $result=$GLOBALS['db']->query("DESCRIBE $table_name");
        return (mysql_num_rows($result));
    }    

}

?>
