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

/**
 * Retrieve field data for a provided SugarBean.
 *
 * @param SugarBean $value -- The bean to retrieve the field information for.
 * @return Array -- 'field'=>   'name' -- the name of the field
 *                              'type' -- the data type of the field
 *                              'label' -- the translation key for the label of the field
 *                              'required' -- Is the field required?
 *                              'options' -- Possible values for a drop down field
 */
function get_field_list(&$value){
	$list = array();
	if(!empty($value->field_defs)){
		foreach($value->field_defs as $var){
			if(isset($var['source']) && $var['source'] != 'db' && (!isset($var['type'])|| $var['type'] != 'relate'))continue;
			$required = 0;
			$options_dom = array();
			$options_ret = array();
			// Apparently the only purpose of this check is to make sure we only return fields
			//   when we've read a record.  Otherwise this function is identical to get_module_field_list
			if(isset($value->required_fields) && key_exists($var['name'], $value->required_fields)){
				$required = 1;
			}
			if(isset($var['options'])){
				$options_dom = translate($var['options'], $value->module_dir);
				if(!is_array($options_dom)) $options_dom = array();
				foreach($options_dom as $key=>$oneOption)
					$options_ret[] = get_name_value($key,$oneOption);
			}
			$list[$var['name']] = array('name'=>$var['name'],
										'type'=>$var['type'],
										'label'=>translate($var['vname'], $value->module_dir),
										'required'=>$required,
										'options'=>$options_ret );
		}
	}
		if($value->module_dir == 'Bugs'){
			require_once('modules/Releases/Release.php');
			$seedRelease = new Release();
			$options = $seedRelease->get_releases(TRUE, "Active");
			$options_ret = array();
			foreach($options as $name=>$value){
				$options_ret[] =  array('name'=> $name , 'value'=>$value);
			}
			if(isset($list['fixed_in_release'])){
				$list['fixed_in_release']['type'] = 'enum';
				$list['fixed_in_release']['options'] = $options_ret;
			}
			if(isset($list['release'])){
				$list['release']['type'] = 'enum';
				$list['release']['options'] = $options_ret;
			}
			if(isset($list['release_name'])){
				$list['release_name']['type'] = 'enum';
				$list['release_name']['options'] = $options_ret;
			}
		}
		if(isset($value->assigned_user_name) && isset($list['assigned_user_id'])) {
			$list['assigned_user_name'] = $list['assigned_user_id'];
			$list['assigned_user_name']['name'] = 'assigned_user_name';
		}






		if(isset($list['modified_user_id'])) {
			$list['modified_by_name'] = $list['modified_user_id'];
			$list['modified_by_name']['name'] = 'modified_by_name';
		}
		if(isset($list['created_by'])) {
			$list['created_by_name'] = $list['created_by'];
			$list['created_by_name']['name'] = 'created_by_name';
		}
	return $list;

}


function get_name_value($field,$value){
	return array('name'=>$field, 'value'=>$value);
}

function get_user_module_list($user){
	global $app_list_strings;

	$app_list_strings = return_app_list_strings_language($current_language);
	$modules = query_module_access_list($user);
	ACLController :: filterModuleList($modules, false);
	global $modInvisList, $modInvisListActivities;

	foreach($modInvisList as $invis){
		$modules[$invis] = 'read_only';
	}

	if(isset($modules['Calendar']) || $modules['Activities']){
		foreach($modInvisListActivities as $invis){
				$modules[$invis] = $invis;
		}
	}

	return $modules;



}

function check_modules_access($user, $module_name, $action='write'){
	if(!isset($_SESSION['avail_modules'])){
		$_SESSION['avail_modules'] = get_user_module_list($user);
	}
	if(isset($_SESSION['avail_modules'][$module_name])){
		if($action == 'write' && $_SESSION['avail_modules'][$module_name] == 'read_only'){
			if(is_admin($user))return true;
			return false;
		}
		return true;
	}
	return false;

}

function get_name_value_list(&$value){
	$list = array();
	if(!empty($value->field_defs)){
		if(isset($value->assigned_user_name)) {
			$list['assigned_user_name'] = get_name_value('assigned_user_name', $value->assigned_user_name);
		}





		if(isset($value->modified_by_name)) {
			$list['modified_by_name'] = get_name_value('modified_by_name', $value->modified_by_name);
		}
		if(isset($value->created_by_name)) {
			$list['created_by_name'] = get_name_value('created_by_name', $value->created_by_name);
		}
		foreach($value->field_defs as $var){
			if(isset($var['source']) && $var['source'] != 'db' && (!isset($var['type'])|| $var['type'] != 'relate'))continue;
			if(isset($value->$var['name'])){
				$list[$var['name']] = get_name_value($var['name'], $value->$var['name']);
			}
		}
	}
	return $list;

}

function array_get_name_value_list($array){
	$list = array();
	foreach($array as $name=>$value){

				$list[$name] = get_name_value($name, $value);
	}
	return $list;

}

function array_get_return_value($array, $module){

	return Array('id'=>$array['id'],
				'module_name'=> $module,
				'name_value_list'=>array_get_name_value_list($array)
				);
}

function get_return_value(&$value, $module){
	global $module_name, $current_user;
	$module_name = $module;
	if($module == 'Users' && $value->id != $current_user->id){
		$value->user_hash = '';
	}
	return Array('id'=>$value->id,
				'module_name'=> $module,
				'name_value_list'=>get_name_value_list($value)
				);
}

function get_name_value_xml($val, $module_name){
	$xml = '<item>';
			$xml .= '<id>'.$val['id'].'</id>';
			$xml .= '<module>'.$module_name.'</module>';
			$xml .= '<name_value_list>';
			foreach($val['name_value_list'] as $name=>$nv){
				$xml .= '<name_value>';
				$xml .= '<name>'.htmlspecialchars($nv['name']).'</name>';
				$xml .= '<value>'.htmlspecialchars($nv['value']).'</value>';	
				$xml .= '</name_value>';
			}
			$xml .= '</name_value_list>';
			$xml .= '</item>';
			return $xml;		
}

/*
function get_module_field_list(&$value){
	$list = array();
	if(!empty($value->field_defs)){
		foreach($value->field_defs as $var){
			$required = 0;
			$options_dom = array();
			$translateOptions = false;

				if(isset($value->required_fields) && key_exists($var['name'], $value->required_fields)){
					$required = 1;
				}
				if(isset($var['options'])){
					$options_dom = $var['options'];
					$translateOptions = true;
				}
				if($value->module_dir == 'Bugs'){
					require_once('modules/Releases/Release.php');
					$seedRelease = new Release();
					$options = $seedRelease->get_releases(TRUE, "Active");
					if($var['name'] == 'fixed_in_release'){
						$var['type'] = 'enum';
						$translateOptions = false;
						foreach($options as $name=>$avalue){
							$options_dom[$avalue] =  $name;
						}
					}
					if($var['name'] == 'release'){
						$var['type'] = 'enum';
						$translateOptions = false;
						foreach($options as $name=>$avalue){
							$options_dom[$avalue] =  $name;
						}
					}
				}
				$list[$var['name']] = array('name'=>$var['name'],
											'type'=>$var['type'],
											'label'=>translate($var['vname'], $value->module_dir),
											'required'=>$required,
											'options'=>get_field_options($options_dom, $translateOptions) );

		}
		}

	return $list;
}
*/

function get_return_module_fields(&$value, $module, &$error){
	global $module_name;
	$module_name = $module;
	return Array('module_name'=>$module,
				//'module_fields'=> get_module_field_list($value),
				'module_fields'=> get_field_list($value),
				'error'=>get_name_value_list($value)
				);
}

function get_return_error_value($error_num, $error_name, $error_description){
	return Array('number'=>$error_num,
				'name'=> $error_name,
				'description'=>	$error_description
				);
}

function filter_field_list(&$field_list, &$select_fields, $module_name){
	return filter_return_list($field_list, $select_fields, $module_name);
}


/**
 * Filter the results of a list query.  Limit the fields returned.
 *
 * @param Array $output_list -- The array of list data
 * @param Array $select_fields -- The list of fields that should be returned.  If this array is specfied, only the fields in the array will be returned.
 * @param String $module_name -- The name of the module this being worked on
 * @return The filtered array of list data.
 */
function filter_return_list(&$output_list, $select_fields, $module_name){

	for($sug = 0; $sug < sizeof($output_list) ; $sug++){
		if($module_name == 'Contacts'){
			global $invalid_contact_fields;
			if(is_array($invalid_contact_fields)){
				foreach($invalid_contact_fields as $name=>$val){
					unset($output_list[$sug]['field_list'][$name]);
					unset($output_list[$sug]['name_value_list'][$name]);
					
				}
			}
		}

		if( is_array($output_list[$sug]['name_value_list']) && !empty($select_fields) && is_array($select_fields)){
			foreach($output_list[$sug]['name_value_list'] as $name=>$value)
					if(!in_array($value['name'], $select_fields)){
						unset($output_list[$sug]['name_value_list'][$name]);
						unset($output_list[$sug]['field_list'][$name]);
					}
		}
	}

	return $output_list;

}

function login_success(){
	global $current_language, $sugar_config, $app_strings, $app_list_strings;
	$current_language = $sugar_config['default_language'];
	$app_strings = return_application_language($current_language);
	$app_list_strings = return_app_list_strings_language($current_language);
}


/*
 *	Given an account_name, either create the account or assign to a contact.
 */
function add_create_account(&$seed)
{
	global $current_user;
	$account_name = $seed->account_name;
	$account_id = $seed->account_id;
	$assigned_user_id = $current_user->id;
	
	$class = get_class($seed);
	$temp = new $class();
	$temp->retrieve($seed->id);
	if ((! isset($account_name) || $account_name == ''))
	{
		return;
	}
	if (!isset($seed->accounts)){
	    	$seed->load_relationship('accounts');
	}
	
	if($seed->account_name = '' && isset($temp->account_id)){
		$seed->accounts->delete($seed->id, $temp->account_id);
		return;
	}

    $arr = array();

	// check if it already exists
    $focus = new Account();

    $query = "select id, deleted from {$focus->table_name} WHERE name='". PearDatabase::quote($account_name)."'";
    $result = $seed->db->query($query) or sugar_die("Error selecting sugarbean: ".mysql_error());

    $row = $seed->db->fetchByAssoc($result, -1, false);

	// we found a row with that id
    if (isset($row['id']) && $row['id'] != -1)
    {
    	// if it exists but was deleted, just remove it entirely
        if ( isset($row['deleted']) && $row['deleted'] == 1)
        {
            $query2 = "delete from {$focus->table_name} WHERE id='". PearDatabase::quote($row['id'])."'";
            $result2 = $seed->db->query($query2) or sugar_die("Error deleting existing sugarbean: ".mysql_error());
		}
		// else just use this id to link the contact to the account
        else
        {
        	$focus->id = $row['id'];
        }
    }

	// if we didnt find the account, so create it
    if (! isset($focus->id) || $focus->id == '')
    {
    	$focus->name = $account_name;
    	
		if ( isset($assigned_user_id))
		{
           $focus->assigned_user_id = $assigned_user_id;
           $focus->modified_user_id = $assigned_user_id;
		}
        $focus->save();
    }

    if($temp->account_id != null && $temp->account_id != $focus->id){
    	$seed->accounts->delete($seed->id, $temp->account_id);
    }

    if(isset($focus->id) && $focus->id != ''){
		$seed->account_id = $focus->id;
	}
}

function check_for_duplicate_contacts(&$seed){
	require_once('modules/Contacts/Contact.php');
	$query = '';
	$baseQuery = 'select id, first_name, last_name, email1, email2  from contacts where deleted!=1 and (';

	if(isset($seed->id)){
		return null;
	}

	if(!empty($seed->email1)){
		$query = $baseQuery. "  (email1='". $seed->email1 . "' OR email2 = '". $seed->email1 ."') AND ";
	}
	if(empty($query)){
		$query = $baseQuery;	
	}
	if(!empty($seed->first_name) && !empty($seed->first_name)){
		$query .="  (first_name like '". $seed->first_name . "%' and last_name = '". $seed->last_name ."')";
	}else{
		$query .="  last_name = '". $seed->last_name ."'";
	}

	if(!empty($query)){
		$rows = array();
		global $db;
		$result = $seed->db->query($query.')');
		if(empty($result)){
			return null;
		}
		$row = $seed->db->fetchByAssoc($result, 0);
		if (sizeof($row) == 0){
			return null;
		}
		else{
			$contact = new Contact();
			$contact->populateFromRow($row);
			return $contact->id;
		}
	}
	return null;
}

/*
 * Given a client version and a server version, determine if the right hand side(server version) is greater
 * 
 * @param left           the client sugar version
 * @param right          the server version
 *
 * return               true if the server version is greater or they are equal
 *                      false if the client version is greater
 */
function is_server_version_greater($left, $right){
    if(count($left) == 0 && count($right) == 0){
        return false;   
    }
    else if(count($left) == 0 || count($right) == 0){
        return true;
    }
    else if($left[0] == $right[0]){
        array_shift($left);
        array_shift($right);
        return is_server_version_greater($left, $right);
    }
    else if($left[0] < $right[0]){
       return true;
    }
    else
        return false;
}

function getFile( $zip_file, $file_in_zip ){
    global $sugar_config;
    $base_upgrade_dir = $sugar_config['upload_dir'] . "/upgrades";
    $base_tmp_upgrade_dir   = "$base_upgrade_dir/temp";
    $my_zip_dir = mk_temp_dir( $base_tmp_upgrade_dir );
    unzip_file( $zip_file, $file_in_zip, $my_zip_dir );
    return( "$my_zip_dir/$file_in_zip" );
}

function getManifest( $zip_file ){
    ini_set("max_execution_time", "3600");
    return( getFile( $zip_file, "manifest.php" ) );
}

if(!function_exists("get_encoded")){
/*HELPER FUNCTIONS*/
function get_encoded($object){
		return  base64_encode(serialize($object));
}
function get_decoded($object){
		return  unserialize(base64_decode($object));
		
}}
/*END HELPER*/
?>
