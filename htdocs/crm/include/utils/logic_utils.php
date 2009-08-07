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

 // $Id: logic_utils.php,v 1.4 2006/08/22 18:56:15 awu Exp $
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('include/utils/file_utils.php');
function get_hook_array($module_name){
	
			$hook_array = null;
			// This will load an array of the hooks to process
			include("custom/modules/$module_name/logic_hooks.php");	
			return $hook_array;

//end function return_hook_array
}

function check_existing_element($hook_array, $event, $action_array){

	if(isset($hook_array[$event])){
		foreach($hook_array[$event] as $action){
	
			if($action[1] == $action_array[1]){
				return true;	
			}	
		}
	}
		return false;
	
//end function check_existing_element
}	
	
function replace_or_add_logic_type($hook_array){
	

	
	$new_entry = build_logic_file($hook_array);

   	$new_contents = "<?php\n$new_entry\n?>";
   	
	return $new_contents;
}



function write_logic_file($module_name, $contents){
	
		$file = "modules/".$module_name . '/logic_hooks.php';
		$file = create_custom_directory($file);
		$fp = fopen($file, 'wb');
		fwrite($fp,$contents);
		fclose($fp);
	
//end function write_logic_file
}	

function build_logic_file($hook_array){
	
	$hook_contents = "";

	$hook_contents .= "// Do not store anything in this file that is not part of the array or the hook version.  This file will	\n";
	$hook_contents .= "// be automatically rebuilt in the future. \n ";
	$hook_contents .= "\$hook_version = 1; \n";
	$hook_contents .= "\$hook_array = Array(); \n";
	$hook_contents .= "// position, file, function \n";
	
	foreach($hook_array as $event_array => $event){
		
	$hook_contents .= "\$hook_array['".$event_array."'] = Array(); \n";
		
		foreach($event as $second_key => $elements){
		
			$hook_contents .= "\$hook_array['".$event_array."'][] = ";
			$hook_contents .= "Array(".$elements[0].", '".$elements[1]."', '".$elements[2]."','".$elements[3]."', '".$elements[4]."'); \n";

		}
		
	//end foreach hook_array as event => action_array
	}		
	
	$hook_contents .= "\n\n";
	
	return $hook_contents;
	
//end function build_logic_file	
}

?>
