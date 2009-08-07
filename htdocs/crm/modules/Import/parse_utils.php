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

// takes a string and parses it into one record per line,
// one field per delimiter, to a maximum number of lines
// some files have a header, some dont.
// keeps track of which fields are used
ini_set('auto_detect_line_endings','1');

function parse_import($file_name,$delimiter,$max_lines,$has_header)
{
	global $locale;
	if(empty($locale))
	{
		require_once('include/Localization/Localization.php');
		$locale = new Localization();
	}
	
	$line_count = 0;

	$field_count = 0;

	$rows = array();

	if (! file_exists($file_name))
	{
		return -1;
	}

	$fh = fopen($file_name,"r");

	if (! $fh)
	{
		return -1;
	}

	while ( (( $fields = fgetcsv($fh, 4096, $delimiter) ) !== FALSE) 
		&& ( $max_lines == -1 || $line_count < $max_lines)) 
	{

		if ( count($fields) == 1 && isset($fields[0]) && $fields[0] == '')
		{
			continue;
		}
		$this_field_count = count($fields);

		if ( $this_field_count > $field_count)
		{
			$field_count = $this_field_count;
		}

		array_push($rows,$fields);

		$line_count++;

	}

	// got no rows
	if ( count($rows) == 0)
	{
		return -3;
	}
	else
	{
		//// cn: bug 6712 - need to translate to UTF-8
		foreach($rows as $rowKey => $row)
		{
			foreach($row as $k => $v) {
				$row[$k] = $locale->translateCharset($v, $locale->getExportCharset());
			}
			$rows[$rowKey] = $row;
		}
	}

	$ret_array = array(
		"rows"=>&$rows,
		"field_count"=>$field_count
	);

	return $ret_array;

}


// this parser just splits the string by the delimiter and that's it..
function parse_import_split($file_name,$delimiter,$max_lines,$has_header)
{
	global $locale;
	if(empty($locale))
	{
		require_once('include/Localization/Localization.php');
		$locale = new Localization();
	}

	$line_count = 0;

	$field_count = 0;

	$rows = array();

	if (! file_exists($file_name))
	{
		return -1;
	}

	$fh = fopen($file_name,"r");

	if (! $fh)
	{
		return -1;
	}

	while ( ($line = fgets($fh, 4096))
                && ( $max_lines == -1 || $line_count < $max_lines) )

	{
		
		$line = trim($line);
		$fields = explode($delimiter,$line);

		$this_field_count = count($fields);

		if ( $this_field_count > $field_count)
		{
			$field_count = $this_field_count;
		}

		array_push($rows,$fields);

		$line_count++;

	}

	// got no rows
	if ( count($rows) == 0)
	{
		return -3;
	}
	else
	{
		//// cn: bug 6712 - need to translate to UTF-8
		foreach($rows as $rowKey => $row)
		{
			foreach($row as $k => $v) {
				$row[$k] = $locale->translateCharset($v, $locale->getExportCharset());
			}
			$rows[$rowKey] = $row;
		}
	}
	$ret_array = array(
		"rows"=>&$rows,
		"field_count"=>$field_count
	);

	return $ret_array;

}

function parse_import_act($file_name,$delimiter,$max_lines,$has_header)
{
	global $locale;
	if(empty($locale))
	{
		require_once('include/Localization/Localization.php');
		$locale = new Localization();
	}

	$line_count = 0;

	$field_count = 0;

	$rows = array();

	if (! file_exists($file_name))
	{
		return -1;
	}

	$fh = fopen($file_name,"r");

	if (! $fh)
	{
		return -1;
	}

	while ( ($line = fgets($fh, 4096))
                && ( $max_lines == -1 || $line_count < $max_lines) )

	{
		
		$line = trim($line);
		$line = substr_replace($line,"",0,1);
		$line = substr_replace($line,"",-1);
		$fields = explode("\",\"",$line);

		$this_field_count = count($fields);

		if ( $this_field_count > $field_count)
		{
			$field_count = $this_field_count;
		}

		array_push($rows,$fields);

		$line_count++;

	}

	// got no rows
	if ( count($rows) == 0)
	{
		return -3;
	}
	else
	{
		//// cn: bug 6712 - need to translate to UTF-8
		foreach($rows as $rowKey => $row)
		{
			foreach($row as $k => $v) {
				$row[$k] = $locale->translateCharset($v, $locale->getExportCharset());
			}
			$rows[$rowKey] = $row;
		}
	}
	$ret_array = array(
		"rows"=>&$rows,
		"field_count"=>$field_count
	);

	return $ret_array;

}

//This function will return a list of import enabled fields, and another array of importable fields label translated into logged in 
//user's locale specific string.
//all fields in a module's vardefs file are importable , unless they meet this criteria, 
//             Importable is set to false or type is link.
function get_importable_fields(&$bean, &$importable_fields, &$labels) {
	$my_fielddefs= $bean->getFieldDefinitions();		
	foreach ($my_fielddefs as $key=>$value_array) {		
		if ((array_key_exists('Importable',$value_array) && $value_array['Importable'] == false )
			or ((array_key_exists('type',$value_array) && $value_array['type'] == 'link' ) or $key == 'team_name')
			) {
				//do not allow import.
			} else {
				$importable_fields[$key]=1; 
				$labels[$key]= translate($value_array['vname'] ,$bean->module_dir);
		}
	}	
}
	
?>
