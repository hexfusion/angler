<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Sugar widget for fieldnames
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

// $Id: SugarWidgetFieldname.php,v 1.21 2006/08/15 01:46:04 wayne Exp $

require_once('include/generic/SugarWidgets/SugarWidgetFieldvarchar.php');

class SugarWidgetFieldName extends SugarWidgetFieldVarchar
{
    
    function SugarWidgetFieldName(&$layout_manager) {
        parent::SugarWidgetFieldVarchar($layout_manager);
        $this->reporter = $this->layout_manager->getAttribute('reporter');  
    }
    
	function displayList(&$layout_def)
	{
		if(empty($layout_def['column_key']))
		{
			return $this->displayListPlain($layout_def);
		}
		
		$module = $this->reporter->all_fields[$layout_def['column_key']]['module'];
		$name = $layout_def['name'];
		$layout_def['name'] = 'id';
		$key = $this->_get_column_alias($layout_def);
		$key = strtoupper($key);
		
		if(empty($layout_def['fields'][$key]))
		{
		  $layout_def['name'] = $name;
			return $this->displayListPlain($layout_def);	
		}
		
		$record = $layout_def['fields'][$key];
		$layout_def['name'] = $name;
		
		$str = "<a class=\"listViewTdLinkS1\" href=\"index.php?action=DetailView&module=$module&record=$record\">";
		$str .= $this->displayListPlain($layout_def);
		$str .= "</a>";	
		return $str;
	}

	function _get_column_select($layout_def)
	{
		global $sugar_config;
		// if $this->db->dbytpe is empty, then grab dbtype value from global array "$sugar_config[dbconfig]"
		if(empty($this->db->dbType)){
			$this->db->dbType = $sugar_config['dbconfig']['db_type'];
		}
		$field_def = $this->reporter->all_fields[$layout_def['column_key']];
		
		if (empty($field_def['fields']) || empty($field_def['fields'][0]) || empty($field_def['fields'][1]))
		{
			return parent::_get_column_select($layout_def);
		}
		
		//	 'fields' are the two fields to concat to create the name
		$alias = '';
		$endalias = '';
		if ( ! empty($layout_def['table_alias']))
		{
			if ( ( $this->db->dbType == 'mysql' ) or ( $this->db->dbType == 'oci8' ) )
			{
				$alias .= "CONCAT(CONCAT("
					.$layout_def['table_alias']."."
					.$field_def['fields'][0].",' '),"
					.$layout_def['table_alias']."."
					.$field_def['fields'][1].")";
			}
			elseif ( $this->db->dbType == 'mssql' )
			{
				$alias .= $layout_def['table_alias'] . '.' . $field_def['fields'][0] . " + ' ' + "
				. $layout_def['table_alias'] . '.' . $field_def['fields'][1]."";
			}
		}
		elseif (! empty($layout_def['name']))
		{
			$alias = $layout_def['name'];
		}
		else
		{
			$alias .= "*";
		}
		
		$alias .= $endalias;
		return $alias;
	}

	function queryFilterIs($layout_def)
	{
		require_once('include/generic/SugarWidgets/SugarWidgetFieldid.php');
		$layout_def['name'] = 'id';
		$layout_def['type'] = 'id';
		$input_name0 = $layout_def['input_name0'];
		
		if ( is_array($layout_def['input_name0']))
		{
			$input_name0 = $layout_def['input_name0'][0];
		}
		
		return SugarWidgetFieldid::_get_column_select($layout_def)."='"
			.PearDatabase::quote($input_name0)."'\n";
	}

    // $rename_columns, if true then you're coming from reports
	function queryFilterone_of(&$layout_def, $rename_columns = true)
	{
		require_once('include/generic/SugarWidgets/SugarWidgetFieldid.php');
        if($rename_columns) { // this was a hack to get reports working, sugarwidgets should not be renaming $name! 
    		$layout_def['name'] = 'id';
    		$layout_def['type'] = 'id';
        }
		$arr = array();
		
		foreach($layout_def['input_name0'] as $value)
		{
			array_push($arr,"'".PearDatabase::quote($value)."'");
		}
		
		$str = implode(",",$arr);
        
		return SugarWidgetFieldid::_get_column_select($layout_def)." IN (".$str.")\n";
	}
	
	function &queryGroupBy($layout_def)
	{
    if( $this->reporter->db->dbType == 'mysql')
		{
		 require_once('include/generic/SugarWidgets/SugarWidgetFieldid.php');
		 $layout_def['name'] = 'id';
		 $layout_def['type'] = 'id';
		 $group_by =  SugarWidgetFieldid::_get_column_select($layout_def)."\n";
		 return $group_by;
		}  else {
		  $group_by =  SugarWidgetFieldvarchar::_get_column_select($layout_def)."\n";
			return $group_by;
		}
	}
}

?>
