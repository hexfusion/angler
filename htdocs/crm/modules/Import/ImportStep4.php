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
 * $Id: ImportStep4.php,v 1.63 2006/07/26 18:23:22 jenny Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $sugar_config;
if (isset($sugar_config['import_max_execution_time']))
{
    ini_set("max_execution_time", $sugar_config['import_max_execution_time']);
}
else
{
    ini_set("max_execution_time", 3600);
}
require_once ('data/Tracker.php');
require_once ('modules/Import/ImportMap.php');
require_once ('modules/Import/UsersLastImport.php');
require_once ('modules/Import/parse_utils.php');
require_once ('include/ListView/ListView.php');
require_once ('modules/Import/config.php');
require_once ('include/utils.php');

global $mod_strings, $app_list_strings, $app_strings, $current_user, $import_bean_map;
global $import_file_name;
global $theme;
$theme_path = "themes/".$theme."/";
$image_path = $theme_path."images/";
require_once ($theme_path.'layout_utils.php');

function implode_assoc($inner_delim, $outer_delim, $array) {
	$output = array ();
	foreach ($array as $key => $item) {
		$output[] = $key.$inner_delim.$item;
	}
	return implode($outer_delim, $output);
}

//Begin logging.
$GLOBALS['log']->info("Upload Step 4");
//Initialize
if (isset($_REQUEST['custom_delimiter']) && $_REQUEST['custom_delimiter'] != "")
{
    $delimiter = $_REQUEST['custom_delimiter'];
}
//set the default delimiter. //<-- delimeter
else
{
    $delimiter = ',';
}
$count = 0;
$error = "";
$col_pos_to_field = array ();
$header_to_field = array ();
$field_to_pos = array ();
$focus = 0;
$id_exists_count = 0;
$broken_ids = 0;
$has_header = 0;

if (isset ($_REQUEST['has_header']) && $_REQUEST['has_header'] == 'on') {
	$has_header = 1;
}

if (isset ($import_bean_map[$_REQUEST['module']])) {
	$currentModule = $_REQUEST['module'];
	$bean = $import_bean_map[$_REQUEST['module']];
	require_once ("modules/Import/$bean.php");
	$focus = new $bean ();
} else {
	echo "Imports aren't set up for this module type\n";
	exit;
}

//name of duplicate import log file, append it with module and date stamp to insure unique name
$today = getdate();
$timeOfDay = $today['mon'] . $today['mday'] .$today['hours'] . $today['seconds'];
$myFile = "cache/import/ImportErrorFile_" . $focus->module_dir ."_". $timeOfDay . ".csv";


//setup the importable fields array.
$importable_fields = array ();
$translated_column_fields = array ();
get_importable_fields($focus, $importable_fields, $translated_column_fields);

global $current_language;
$mod_strings = return_module_language($current_language, $currentModule);
// loop through all request variables
foreach ($_REQUEST as $name => $value) {
	// only look for var names that start with "colnum"
	if (strncasecmp($name, "colnum", 6) != 0) {
		continue;
	}
	if ($value == "-1") {
		continue;
	}

	// this value is a user defined field name
	$user_field = $value;

	// pull out the column position for this field name
	$pos = substr($name, 6);

	// make sure we haven't seen this field defined yet
	if (isset ($field_to_pos[$user_field])) {
		show_error_import($mod_strings['LBL_ERROR_MULTIPLE']);
		exit;
	}
	// match up the "official" field to the user 
	// defined one, and map to columm position: 
	//$translated_column_fields = $mod_list_strings[$list_string_key];

	$module_custom_fields_def = $focus->custom_fields->avail_fields;
	foreach ($module_custom_fields_def as $name => $field_def) {
		if ($name != 'id_c')
			$importable_fields[$field_def['name']] = 1;
	}

	if (isset ($importable_fields[$user_field])) {
		// now mark that we've seen this field
		$field_to_pos[$user_field] = $pos;
		$col_pos_to_field[$pos] = $user_field;
	}
}

$max_lines = -1;
$ret_value = 0;

if ($_REQUEST['source'] == 'act') {
	$ret_value = parse_import_act($_REQUEST['tmp_file'], $delimiter, $max_lines, $has_header);
} else
	if ($_REQUEST['source'] == 'other_tab') {
		$ret_value = parse_import_split($_REQUEST['tmp_file'], "\t", $max_lines, $has_header);
	}
    else if ($_REQUEST['source'] == 'custom_delimeted')
    {
        $ret_value = parse_import_split($_REQUEST['tmp_file'],$delimiter,$max_lines,$has_header);
    }  
    else {
		$ret_value = parse_import($_REQUEST['tmp_file'], $delimiter, $max_lines, $has_header);
	}

if (file_exists($_REQUEST['tmp_file'])) {
	unlink($_REQUEST['tmp_file']);
}

$rows = $ret_value['rows'];
$ret_field_count = $ret_value['field_count'];
$saved_ids = array ();
$firstrow = 0;

if (!isset ($rows)) {
	$error = $mod_strings['LBL_FILE_ALREADY_BEEN_OR'];
	$rows = array ();
}

if ($has_header == 1) {
	$firstrow = array_shift($rows);   
}

$seedUsersLastImport = & new UsersLastImport();
$seedUsersLastImport->mark_deleted_by_user_id($current_user->id);

$skip_required_count = 0;

$not_imported_str = '';

$firstline = implode("\t", $firstrow);
$first_line_str = "$firstline\n";
$GLOBALS['log']->info("[IMPORT]".$first_line_str);



$fieldDefs = $focus->getFieldDefinitions();

// go thru each row, process and save()
$dupe_rows = array();
foreach ($rows as $row) {
	//$count = count($row);
	//$not_imported_str = 'id_exists,'.implode(",",$row)."\n";
	$focus = & new $bean ();
	$focus->save_from_post = false;

	$do_save = 1;
	
	for ($field_count = 0; $field_count < $ret_field_count; $field_count ++) {
		if (isset ($col_pos_to_field[$field_count])) {
			if (!isset ($row[$field_count])) {
				continue;
			}

			// TODO: add check for user input
			// addslashes, striptags, etc..
			$field = $col_pos_to_field[$field_count];
			
			// handle _dom based values
			if($fieldDefs[$field]['type'] == 'enum') {
				// we found a _dom type value - compare and assign, or drop if not found
				foreach($app_list_strings[ $fieldDefs[$field]['options'] ] as $key => $value) {
					if( (strtolower($row[$field_count]) == strtolower($value)) && ($value != "") ) {
						$row[$field_count] = $value;
					}
				}
			}
			
			$focus-> $field = str_replace('"', "", $row[$field_count]);
		}
	}


	
	$var_def_indexes = $dictionary[$focus->object_name]['indices'];

    // check to see that the indexes being entered are unique.
	$isUnique = checkForDupes($focus, $var_def_indexes, $row);
	if(!$isUnique){
		//if row is not unique (searched on by index), then push onto array, break out and continue with original loop
		array_push($dupe_rows, $row);
		continue;
	}
	
	// if the id was specified	
	if (isset ($focus->id)) {
		$focus->id = convert_id($focus->id);

		// check if it already exists
		$check_bean = & new $bean ();

		$query = "select * from {$check_bean->table_name} WHERE id='{$focus->id}'";

		$GLOBALS['log']->info($query);

		$result = $check_bean->db->query($query) or sugar_die("Error selecting sugarbean: ");

		$dbrow = $check_bean->db->fetchByAssoc($result);

		if (isset ($dbrow['id']) && $dbrow['id'] != -1) {
			// if it exists but was deleted, just remove it
			if (isset ($dbrow['deleted']) && $dbrow['deleted'] == 1) {
				$query2 = "delete from {$check_bean->table_name} WHERE id='{$focus->id}'";
				$GLOBALS['log']->info($query2);
				$result2 = $check_bean->db->query($query2) or sugar_die("Error deleting existing sugarbean: ");
			} else {
				$id_exists_count ++;
				$do_save = 0;
				$badline = implode("\t", $row);
				$not_imported_str = "$badline\n";
				$GLOBALS['log']->info("[IMPORT][ID EXISTS ALREADY]:[".$not_imported_str."]");
				continue;
			}
		}
		// check if the id is too long
		else
			if (strlen($focus->id) > 36) {
				$broken_ids ++;
				$do_save = 0;
				$badline = implode("\t", $row);
				$not_imported_str = "$badline\n";
				$GLOBALS['log']->info("[IMPORT][ID TOO LONG]:[".$not_imported_str."]");
				continue;
			}

		if ($do_save != 0) {
			// set the flag to force an insert
			$focus->new_with_id = true;
		}
	}

	// now do any special processing
	$focus->process_special_fields();
	$no_required = 0;
	foreach ($focus->required_fields as $field => $notused) {
		if (!isset ($focus-> $field) || $focus-> $field == '') {
			$do_save = 0;
			$skip_required_count ++;
			$badline = implode("\t", $row);
			$not_imported_str = "$badline\n";
			$GLOBALS['log']->info("[IMPORT][NOT IMPORTED]:[".$not_imported_str."]");
			$no_required = 1;
			break;
		}
	}

	if ($no_required == 1) {
		continue;
	}

	if ($do_save) {
		if (!isset ($focus->assigned_user_id) || $focus->assigned_user_id == '') {
			$focus->assigned_user_id = $current_user->id;
		}
		if (!isset ($focus->modified_user_id) || $focus->modified_user_id == '') {
			$focus->modified_user_id = $current_user->id;
		}






		$focus->save();
		$last_import = & new UsersLastImport();
		$last_import->assigned_user_id = $current_user->id;
		$last_import->bean_type = $_REQUEST['module'];
		$last_import->bean_id = $focus->id;
		$last_import->save();
		array_push($saved_ids, $focus->id);
		$count ++;
	}

}
//write out duplicate entries to file system.  Function will return number of duplicates
$dup_count = write_out_dupes($dupe_rows, $myFile, $firstrow);
$dup_link = '';
//if duplicates exist, then set dup_link parameter to file path and name
if($dup_count > 0){
	$dup_link = $myFile;
}

// SAVE MAPPING IF REQUESTED
if (isset ($_REQUEST['save_map']) && $_REQUEST['save_map'] == 'on' && isset ($_REQUEST['save_map_as']) && $_REQUEST['save_map_as'] != '') {
	$serialized_mapping = '';

	if ($has_header) {
		foreach ($col_pos_to_field as $pos => $field_name) {

			if (isset ($firstrow[$pos]) && isset ($field_name)) {
				$header_to_field[$firstrow[$pos]] = $field_name;
			}
		}

		$serialized_mapping = implode_assoc("=", "&", $header_to_field);
	} else {
		$serialized_mapping = implode_assoc("=", "&", $col_pos_to_field);
	}

	$mapping_file_name = $_REQUEST['save_map_as'];

	$mapping_file = & new ImportMap();

	$query_arr = array ('assigned_user_id' => $current_user->id, 'name' => $mapping_file_name);

	$mapping_file->retrieve_by_string_fields($query_arr, false);

	$result = $mapping_file->save_map($current_user->id, $mapping_file_name, $_REQUEST['module'], $_REQUEST['source'], $has_header, $serialized_mapping);
}

$mod_strings = return_module_language($current_language, "Import");
$currentModule = "Import";

if ($error != "") {
	show_error_import($mod_strings['LBL_ERROR']." ".$error);
	exit;
} else {
	$message = urlencode($mod_strings['LBL_SUCCESS']."<BR><b>$count</b>  ".$mod_strings['LBL_SUCCESSFULLY']."<br><b>". ($broken_ids + $id_exists_count)."</b> ".$mod_strings['LBL_IDS_EXISTED_OR_LONGER']."<br><b>$skip_required_count</b> ".$mod_strings['LBL_RECORDS_SKIPPED']);
	//_if duplicates exist, then add informational string to message 
	if($dup_count>0){
		$message .=urlencode("<BR><b>$dup_count</b>  ".$mod_strings['LBL_DUPLICATES']);
	}

	if (empty ($_REQUEST['return_action'])) {
		$_REQUEST['return_action'] = 'index';
	}
    
    $json = getJSONobj();
    echo 'result = ' . $json->encode(array('module' => $_REQUEST['module'], 
                                    'return_action' => $_REQUEST['return_action'],
                                    'message' => $message,
                                    'dup_link' => $dup_link,
                                    'return_module' => $_REQUEST['return_module']));           
    
    //header("Location: index.php?module={$_REQUEST['module']}&action=Import&step=last&return_module={$_REQUEST['return_module']}&return_action={$_REQUEST['return_action']}&message=$message&duplink=$dup_link");
	exit;
}

/*
 * This function will take list of duplicates and write them out to a file.  It will also return the count of duplicate entries
 * */
function write_out_dupes($dupe_rows, $myFile, $firstrow){
    $dup_count = count($dupe_rows);
	//proceed only if count of duplicates is more than 0
	if($dup_count>0){
	    $row_array = array();

	    //create string to write file to.  Loop through each duplicate row and process
	    foreach ($dupe_rows as $dup){
	    	//for each duplicate row, get string representation of array and add carriage return
    		$rows_str = implode(",",$dup);
		    $rows_str .= "\n";
		    //push string to an array for further processing 
		    array_push($row_array, $rows_str);
	    }
    	//create string of array that holds all the duplicate row entries
    	//$rows_str = implode(",",$row_array);
    	$processed_rows_str = "";
    	foreach($row_array as $string_row){
    		$processed_rows_str .= $string_row;
    		
    	}
    	//add header row if it exists
    	if(is_array($firstrow)&& !empty($firstrow)){
    		$first_row_string = implode(",",$firstrow);
    	    $processed_rows_str = $first_row_string . $processed_rows_str; 
    	} 
    	//Open file, write out string to it, and close it
    	$fh = fopen($myFile, 'w');
    	fwrite($fh, $processed_rows_str);
    	fclose($fh);
    }
    //return the duplicate entry count
	return $dup_count;
	
}

/*
 * This function will search import file for duplicate rows.  It will search the database based on the 
 * indexes selected by the user.  If no entries are selected, then no search is made and all
 * rows are created.
 * */
function checkForDupes(&$focus, $indexes){
	$dupe_found = false;
    if (!isset($_REQUEST['display_tabs_def']) || $_REQUEST['display_tabs_def'] == ""){
        //since no indexes were selected, return true.  This will treat all rows as unique entries
        return true;
    }
    else // Construct the index array
    {
        $selected_indexes = explode('&', $_REQUEST['display_tabs_def']);
    }
/*    
	//check to see if indexes were selected
	if (!isset($_REQUEST['choose_index'])){
		//since no indexes were higlighted, return true.  This will treat all rows as unique entries
		return true;
	}
*/	
	//loop through var def indexes and compare with selected indexes 
	foreach($indexes as $index){
		if (!$dupe_found){
    		//if vardef index is in selected index array, then assign ithe match to temp array
		    if (in_array($index['name'],$selected_indexes)){
		        $temp_index_array = $index['fields'];
		    
	    	    //call new function to return sql
    	        $dupe_sql = create_dupe_check_SQL($temp_index_array, $focus);
            	
		        //now that the sql has been created, let's run the query
	            $result_count = $focus->db->query($dupe_sql); 

	            $res_count = 0;
            	//While there are entries in the resultset, and while the count is less than 3 (max of 2), then grab the row
            	//we don't need a definite count, just need to know there are more than 1
	            while($focus->db->fetchByAssoc($result_count) && $res_count<3){
	                $dbrow = $focus->db->fetchByAssoc($result_count);
                	//increase the res_count, this tells us there are duplicates
            	    $res_count = $res_count +1;	
    	        }
	
            	//if duplicates exist, set dupe_found boolean.
            	if($res_count>0)
            	{
            		$dupe_found = true;
            	}

            }
	    }
	}
	if ($dupe_found){
        //duplicate found, return false, row is not unique
	    return false;
    }else{
	    //No duplicates, return true, row is unique
	    return true;
	}
	

} 

function create_dupe_check_SQL($temp_index_array, &$focus){
    $GLOBALS['log']->debug("Begin creating dupe check sql");
	$index_fields = array();
	//begin sql string
	$sql = "select * from ".$focus->table_name;
	$GLOBALS['log']->debug("SQL, at this point is: ".$sql);
    //iterate through the matched temp field array and add it's array of fields to index_fields array
    foreach($temp_index_array as $fields){
    	//check to see if field has already been added as part of another index, no need to search for "last_name" 5 times
    	if (in_array($fields,$index_fields)){
    		//already exists as part of another index, no need to add param again
    	}else
    	{	//add field name to array
    		array_push($index_fields,$fields);
    	}
		    
    }

	//add where clause if there are fields to process 
	if(count($index_fields)>0){
		$param_count =0;
		$and_count = 0;
		$sql .= " WHERE ";
		
        //now lets populate the "WHERE" clause of sql.  For each field in the index_fields array,
        //let's add it as a paramater to the sql statement
		foreach($index_fields as $search_param){
			//let's make sure that the field being searched on is populated, if not then skip
			if (!empty($focus ->$search_param)){
			    //Prefix string with "AND" after the second time in this condition, 
			    if($param_count < count($index_fields) && $and_count > 0){
  				    $sql .= " AND ";
				}		
				//(finally) lets add the search param from the bean itself
			    $sql .=" $search_param = '". $focus ->$search_param . "' ";
			    //increase and_count, so we know we have been here already, and need "AND" prefixed to the sql
			    $and_count = $and_count + 1;

			}
			//increase foreach loop count
			$param_count = $param_count +1 ;
			
		}
	}
	$GLOBALS['log']->debug("SQL returned is: ".$sql);

	return $sql;

}
?>

