<?php
/**
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

 // $Id: db_utils.php,v 1.31 2006/08/22 18:48:14 awu Exp $

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*
 * The last parmeter should be used to specify parameters for oracle. it also acts has a complete override
 * for the additional_parameters array.
 */
function db_convert($string, $type, $additional_parameters=array(),$additional_parameters_oracle_only=array()){
	global $sugar_config;
	
	//converts the paramters array into a comma delimited string.
	$additional_parameters_string='';
	foreach ($additional_parameters as $value) {
		$additional_parameters_string.=",".$value;
	}
	$additional_parameters_string_oracle_only='';
	foreach ($additional_parameters_oracle_only as $value) {
		$additional_parameters_string_oracle_only.=",".$value;
	}
	
	if($sugar_config['dbconfig']['db_type']== "mysql"){
		switch($type){
			case 'today': return "CURDATE()";	
			case 'left': return "LEFT($string".$additional_parameters_string.")";
			case 'date_format': return "DATE_FORMAT($string".$additional_parameters_string.")";
			case 'datetime': return "DATE_FORMAT($string, '%Y-%m-%d %H:%i:%s')";
			case 'IFNULL': return "IFNULL($string".$additional_parameters_string.")";
			
		}
		return "$string";
	}else if($sugar_config['dbconfig']['db_type']== "oci8"){















	}elseif($sugar_config['dbconfig']['db_type']== "mssql")
	{
		switch($type){
			case 'today': return "GETDATE()";	
			case 'left': return "LEFT($string".$additional_parameters_string.")";			
			case 'date_format': return "CONVERT(varchar(10)," . $string . ",120)";
			case 'datetime': return "CONVERT(varchar(20)," . $string . ",120)";			
			case 'IFNULL': return "ISNULL($string".$additional_parameters_string.")";		
		}
		return "$string";
	}
	
	return "$string";
}

function db_concat($table, $fields){
	global $sugar_config;
	$ret = '';
	if($sugar_config['dbconfig']['db_type']== "mysql"){
		foreach($fields as $index=>$field){
			if(empty($ret))$ret = "CONCAT(". db_convert($table.".".$field,'IFNULL', array("''"));	
			else $ret.=	",' ',".db_convert($table.".".$field,'IFNULL', array("''"));
		}	
		if (!empty($ret)) $ret.=')';

	} else if($sugar_config['dbconfig']['db_type']== "oci8"){






	}else if($sugar_config['dbconfig']['db_type']== "mssql")
	{
		foreach($fields as $index=>$field)
		{
			if(empty($ret))$ret =  db_convert($table.".".$field,'IFNULL', array("''"));	
			else $ret.=	" + ' ' + ".db_convert($table.".".$field,'IFNULL', array("''"));
		}	
		if (!empty($ret)) $ret.='';

	}
	return $ret;
}
	

function from_db_convert($string, $type){

	global $sugar_config;
	if($sugar_config['dbconfig']['db_type']== "mysql"){
		return $string;
	}else if($sugar_config['dbconfig']['db_type']== "oci8"){






	}
	else if($sugar_config['dbconfig']['db_type']== "mssql")
	{
			switch($type){
			case 'date': return substr($string, 0,11);
			case 'time': return substr($string, 11);
		}
		return $string;
	}
	return $string;
	
	
}

$toHTML = array(
	'"' => '&quot;',
	'<' => '&lt;',
	'>' => '&gt;',
	'& ' => '&amp; ',
	"'" =>  '&#039;',

);

function to_html($string, $encode=true){
	global $toHTML;
	
	if($encode && is_string($string)){//$string = htmlentities($string, ENT_QUOTES);
		if(is_array($toHTML)) { // cn: causing errors in i18n test suite ($toHTML is non-array)
			$string = str_replace(array_keys($toHTML), array_values($toHTML), $string);
		}
	}
	return $string;
}


function from_html($string, $encode=true){
	global $toHTML;
	//if($encode && is_string($string))$string = html_entity_decode($string, ENT_QUOTES);
	if($encode && is_string($string)){
		$string = str_replace(array_values($toHTML), array_keys($toHTML), $string);
	}
	return $string;
}

function run_sql_file( $filename ){
    if( !is_file( $filename ) ){
        print( "Could not find file: $filename <br>" );
        return( false );
    }

    

    $fh         = fopen( $filename,'r' );
    $contents   = fread( $fh, filesize($filename) );
    fclose( $fh );

    $lastsemi   = strrpos( $contents, ';') ;
    $contents   = substr( $contents, 0, $lastsemi );
    $queries    = split( ';', $contents );
    $db         = & PearDatabase::getInstance();

    foreach( $queries as $query ){
        if( !empty($query) ){
            print( "Sending query: $query ;<br>" );
			if($db->dbType == 'oci8')
			{



			}
			else
			{
				$db->query( $query.';', true, "An error has occured while running.<br>" );
			}
        }
    }
    return( true );
}

function isTypeBoolean($type) {

	switch ($type){
  		case 'bool':
			return true;
			break;
	}
	return false;
}

function getBooleanValue($val) {
	
	if (empty($val) or $val=='off') {
		return false;
	}
	return true;
}
function isTypeNumber($type) {

	switch ($type){
  		case 'decimal':
  		case 'int':
  		case 'double':
  		case 'float':
  		case 'uint':
  		case 'ulong':
  		case 'long':
  		case 'short':
			return true;
			break;
	}
	return false;
}

/* return true if the value if empty*/
function emptyValue($val, $type){


	if (empty($val)) return true;

	switch ($type){

  		case 'decimal':
  		case 'int':
  		case 'double':
  		case 'float':
  		case 'uint':
  		case 'ulong':
  		case 'long':
  		case 'short':

			if ($val == 0) {		
				return true;
			} else {
				return false;
			}		  
			break;
        case 'date':
        	if ($val == '0000-00-00')
				return true;
			else
				return false;
			break;

	}	
	
	return false;
	
	/* other dbtypes
	  	  case 'bool':
		  case 'varchar':
		  case 'enum':
          case 'char':
          case 'id':
          case 'date':
          case 'text':        
          case 'blob':
          case 'clob':
          case 'date':
		  case 'datetime':
		  case 'time':
		*/
}	

?>
