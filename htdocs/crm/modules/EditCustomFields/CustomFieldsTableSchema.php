<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Database manipulation for custom field tables
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

// $Id: CustomFieldsTableSchema.php,v 1.14 2006/06/06 17:58:01 majed Exp $


require_once('include/modules.php');

class CustomFieldsTableSchema
{
	var $db;
	var $table_name;

	function CustomFieldsTableSchema($tbl_name = '')
	{
		global $db;
		$this->db = $db;
		$this->table_name = $tbl_name;
	}

	function _get_column_definition($col_name, $type, $required, $default_value)
	{
		$ret_val = "$col_name $type";
		if($required)
		{
			$ret_val .= ' NOT NULL';
		}

		if(!empty($default_value))
		{
			$ret_val .= " DEFAULT '$default_value'";
		}

		return $ret_val;
	}

	function create_table()
	{
		$column_definition = $this->_get_column_definition('id', 'varchar(100)',
			true, '');
		$query = "CREATE TABLE {$this->table_name} ($column_definition);";

		$result = $this->db->query($query, true,
			'CustomFieldsTableSchema::create_table');

		return $result;
	}

	function add_column($column_name, $data_type, $required, $default_value)
	{
		$column_definition = $this->_get_column_definition($column_name,
			$data_type,
			$required, $default_value);

		$query = "ALTER TABLE {$this->table_name} "
			. "ADD COLUMN $column_definition;";

		$result = $this->db->query($query, true,
			'CustomFieldsTableSchema::add_column');

		return $result;
	}

	function modify_column($column_name, $data_type, $required, $default_value)
	{
		$column_definition = $this->_get_column_definition($column_name,
			$data_type, $required, $default_value);

		$query = "ALTER TABLE {$this->table_name} "
			. "MODIFY COLUMN $column_definition;";

		$result = $this->db->query($query, true,
			'CustomFieldsTableSchema::modify_column');

		return $result;
	}

	function drop_column($column_name)
	{
		$query = "ALTER TABLE $this->table_name "
			. "DROP COLUMN $column_name;";

		$result = $this->db->query($query, true,
			'CustomFieldsTableSchema::drop_column');

		return $result;
	}

	function _get_custom_tables()
	{
		$pattern = '%' . CUSTOMFIELDSTABLE_CUSTOM_TABLE_SUFFIX;
		
        if ($this->db){
            if ($this->db->dbType == 'mysql'){
                $result = $this->db->query("SHOW TABLES LIKE '".$pattern."'");
                $rows=$this->db->fetchByAssoc($result);
                return $rows;                
            }else if ($this->dbType == 'oci8') {






            }
        }
        return false;
	}

	/**
	 * @static
	 */
	function custom_table_exists($tbl_name)
	{
		$db = & PearDatabase::getInstance();
		return 	$db->tableExists($tbl_name);		
	}
}

?>
