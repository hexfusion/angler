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
require_once('soap/SoapHelperFunctions.php');
require_once('soap/SoapTypes.php');
require_once('modules/Accounts/Account.php');
/*************************************************************************************

THIS IS FOR SUGARCRM USERS


*************************************************************************************/
$disable_date_format = true;
$server->register(
        'login',
        array('user_auth'=>'tns:user_auth', 'application_name'=>'xsd:string'),
        array('return'=>'tns:set_entry_result'),
        $NAMESPACE); 

/**
 * Log the user into the application
 *
 * @param UserAuth array $user_auth -- Set user_name and password (password needs to be 
 *      in the right encoding for the type of authentication the user is setup for.  For Base 
 *      sugar validation, password is the MD5 sum of the plain text password.
 * @param String $application -- The name of the application you are logging in from.  (Currently unused).
 * @return Array(session_id, error) -- session_id is the id of the session that was 
 *      created.  Error is set if there was any error during creation.
 */
function login($user_auth, $application){
	$error = new SoapError();
	$user = new User();
	$user = $user->retrieve_by_string_fields(array('user_name'=>$user_auth['user_name'],'user_hash'=>$user_auth['password'], 'deleted'=>0, 'status'=>'Active', 'portal_only'=>0) );
	if(!empty($user) && !empty($user->id)){
		session_start();
		global $current_user;
		$current_user = $user;
		$user->loadPreferences();
		$_SESSION['is_valid_session']= true;
		$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['user_id'] = $user->id;
		$_SESSION['type'] = 'user';
		$_SESSION['avail_modules']= get_user_module_list($user);
		login_success();
		return array('id'=>session_id(), 'error'=>$error);	
	}
	$error->set_error('invalid_login');
	return array('id'=>-1, 'error'=>$error);
	
}

//checks if the soap server and client are running on the same machine
$server->register(
        'is_loopback',
        array(),
        array('return'=>'xsd:int'),
        $NAMESPACE); 
        
/**
 * Check to see if the soap server and client are on the same machine.
 * We don't allow a server to sync to itself.
 *
 * @return true -- if the SOAP server and client are on the same machine
 * @return false -- if the SOAP server and client are not on the same machine.
 */
function is_loopback(){
	if($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR'])
		return 1;
	return 0;
}

/**
 * Validate the provided session information is correct and current.  Load the session.
 *
 * @param String $session_id -- The session ID that was returned by a call to login.
 * @return true -- If the session is valid and loaded.
 * @return false -- if the session is not valid.
 */
function validate_authenticated($session_id){
	session_id($session_id);
	session_start();
	
	if(!empty($_SESSION['is_valid_session']) && $_SESSION['ip_address'] == $_SERVER['REMOTE_ADDR'] && $_SESSION['type'] == 'user'){
		
		global $current_user;
		require_once('modules/Users/User.php');
		$current_user = new User();
		$current_user->retrieve($_SESSION['user_id']);
		login_success();
		return true;	
	}
	
	session_destroy();
	return false;
}

$server->register(
    'seamless_login',
    array('session'=>'xsd:string'),
    array('return'=>'xsd:int'),
    $NAMESPACE);

/**
 * Perform a seamless login.  This is used internally during the sync process.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @return true -- if the session was authenticated
 * @return false -- if the session could not be authenticated
 */
function seamless_login($session){
		if(!validate_authenticated($session)){
			return 0;
		}
		$_SESSION['seamless_login'] = true;
		return 1;
}

$server->register(
    'get_entry_list',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'query'=>'xsd:string', 'order_by'=>'xsd:string','offset'=>'xsd:int', 'select_fields'=>'tns:select_fields', 'max_results'=>'xsd:int', 'deleted'=>'xsd:int'),
    array('return'=>'tns:get_entry_list_result'),
    $NAMESPACE);

/**
 * Retrieve a list of beans.  This is the primary method for getting list of SugarBeans from Sugar using the SOAP API.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param String $module_name -- The name of the module to return records from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 * @param String $query -- SQL where clause without the word 'where'
 * @param String $order_by -- SQL order by clause without the phrase 'order by'
 * @param String $offset -- The record offset to start from.
 * @param Array  $select_fields -- A list of the fields to be included in the results. This optional parameter allows for only needed fields to be retrieved.
 * @param String $max_results -- The maximum number of records to return.  The default is the sugar configuration value for 'list_max_entries_per_page'
 * @param Number $deleted -- false if deleted records should not be include, true if deleted records should be included.
 * @return Array 'result_count' -- The number of records returned
 *               'next_offset' -- The start of the next page (This will always be the previous offset plus the number of rows returned.  It does not indicate if there is additional data unless you calculate that the next_offset happens to be closer than it should be.
 *               'field_list' -- The vardef information on the selected fields.
 *                      Array -- 'field'=>  'name' -- the name of the field
 *                                          'type' -- the data type of the field
 *                                          'label' -- the translation key for the label of the field
 *                                          'required' -- Is the field required?
 *                                          'options' -- Possible values for a drop down field
 *               'entry_list' -- The records that were retrieved
 *               'error' -- The SOAP error, if any
 */
function get_entry_list($session, $module_name, $query, $order_by,$offset, $select_fields, $max_results, $deleted ){
	global  $beanList, $beanFiles; 
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	global $current_user;
	if(!check_modules_access($current_user, $module_name, 'read')){
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	
	// If the maximum number of entries per page was specified, override the configuration value.
	if($max_results > 0){
		global $sugar_config;
		$sugar_config['list_max_entries_per_page'] = $max_results;	
	}
	

	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	if(! $seed->ACLAccess('ListView'))
	{
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if($query == ''){
		$where = '';
	}
	if($offset == '' || $offset == -1){
		$offset = 0;
	}
	$response = $seed->get_list($order_by, $query, $offset,-1,-1,$deleted);
	
	$list = $response['list'];
	
	
	$output_list = array();

	// retrieve the vardef information on the bean's fields.
	$field_list = array();
	foreach($list as $value)
	{
		$output_list[] = get_return_value($value, $module_name);
		if(empty($field_list)){
			$field_list = get_field_list($value);	
		}
	}
	
	// Filter the search results to only include the requested fields.
	$output_list = filter_return_list($output_list, $select_fields, $module_name);

	// Filter the list of fields to only include information on the requested fields.
	$field_list = filter_return_list($field_list,$select_fields, $module_name);

	// Calculate the offset for the start of the next page 
	$next_offset = $offset + sizeof($output_list);

	return array('result_count'=>sizeof($output_list), 'next_offset'=>$next_offset,'field_list'=>$field_list, 'entry_list'=>$output_list, 'error'=>$error->get_soap_array());
}

$server->register(
    'get_entry',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'id'=>'xsd:string', 'select_fields'=>'tns:select_fields'),
    array('return'=>'tns:get_entry_result'),
    $NAMESPACE);

/**
 * Retrieve a single SugarBean based on ID.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param String $module_name -- The name of the module to return records from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 * @param String $id -- The SugarBean's ID value.
 * @param Array  $select_fields -- A list of the fields to be included in the results. This optional parameter allows for only needed fields to be retrieved.
 * @return unknown
 */
function get_entry($session, $module_name, $id,$select_fields ){
	return get_entries($session, $module_name, array($id), $select_fields);
}

$server->register(
    'get_entries',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'ids'=>'tns:select_fields', 'select_fields'=>'tns:select_fields'),
    array('return'=>'tns:get_entry_result'),
    $NAMESPACE);

/**
 * Retrieve a list of SugarBean's based on provided IDs.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param String $module_name -- The name of the module to return records from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 * @param Array $ids -- An array of SugarBean IDs.
 * @param Array $select_fields -- A list of the fields to be included in the results. This optional parameter allows for only needed fields to be retrieved.
 * @return Array 'field_list' -- Var def information about the returned fields
 *               'entry_list' -- The records that were retrieved
 *               'error' -- The SOAP error, if any
 */
function get_entries($session, $module_name, $ids,$select_fields ){
	global  $beanList, $beanFiles;
	$error = new SoapError();
	$field_list = array();
	$output_list = array();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('field_list'=>$field_list, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('field_list'=>$field_list, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	global $current_user;
	if(!check_modules_access($current_user, $module_name, 'read')){
		$error->set_error('no_access');	
		return array('field_list'=>$field_list, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	
	//todo can modify in there to call bean->get_list($order_by, $where, 0, -1, -1, $deleted);
	//that way we do not have to call retrieve for each bean
	//perhaps also add a select_fields to this, so we only get the fields we need
	//and not do a select *
	foreach($ids as $id){
		$seed = new $class_name();
	if(! $seed->ACLAccess('DetailView'))
	{
		$error->set_error('no_access');	
		return array('field_list'=>$field_list, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
		$seed->retrieve($id);
		$output_list[] = get_return_value($seed, $module_name);
		
		if(empty($field_list)){
				$field_list = get_field_list($seed);	
				
		}
	}
		
		$output_list = filter_return_list($output_list, $select_fields, $module_name);
		$field_list = filter_field_list($field_list,$select_fields, $module_name);

	return array( 'field_list'=>$field_list, 'entry_list'=>$output_list, 'error'=>$error->get_soap_array());
}
  
$server->register(
    'set_entry',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string',  'name_value_list'=>'tns:name_value_list'),
    array('return'=>'tns:set_entry_result'),
    $NAMESPACE);

/**
 * Update or create a single SugarBean.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param String $module_name -- The name of the module to return records from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 * @param Array $name_value_list -- The keys of the array are the SugarBean attributes, the values of the array are the values the attributes should have.
 * @return Array    'id' -- the ID of the bean that was written to (-1 on error)
 *                  'error' -- The SOAP error if any.
 */
function set_entry($session,$module_name, $name_value_list){
	global  $beanList, $beanFiles;
	
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('id'=>-1, 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('id'=>-1, 'error'=>$error->get_soap_array());
	}
	global $current_user;
	if(!check_modules_access($current_user, $module_name, 'write')){
		$error->set_error('no_access');	
		return array('id'=>-1, 'error'=>$error->get_soap_array());
	}
	
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	
	foreach($name_value_list as $value){
		if($value['name'] == 'id'){
			$seed->retrieve($value['value']);
			break;	
		}
	}
	foreach($name_value_list as $value){
        $GLOBALS['log']->debug($value['name']." : ".$value['value']);
		$seed->$value['name'] = $value['value'];	
	}
	if(! $seed->ACLAccess('Save') || ($seed->deleted == 1  &&  !$seed->ACLAccess('Delete')))
	{
		$error->set_error('no_access');	
		return array('id'=>-1, 'error'=>$error->get_soap_array());
	}
	$seed->save();
	if($seed->deleted == 1){
			$seed->mark_deleted($seed->id);	
	}
	return array('id'=>$seed->id, 'error'=>$error->get_soap_array());
	
}

$server->register(
    'set_entries',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string',  'name_value_lists'=>'tns:name_value_lists'),
    array('return'=>'tns:set_entries_result'),
    $NAMESPACE);
    
/**
 * Update or create a list of SugarBeans
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param String $module_name -- The name of the module to return records from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 * @param Array $name_value_lists -- Array of Bean specific Arrays where the keys of the array are the SugarBean attributes, the values of the array are the values the attributes should have.
 * @return Array    'ids' -- Array of the IDs of the beans that was written to (-1 on error)
 *                  'error' -- The SOAP error if any.
 */
function set_entries($session,$module_name, $name_value_lists){
	global  $beanList, $beanFiles;
	
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('ids'=>array(), 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('ids'=>array(), 'error'=>$error->get_soap_array());
	}
	global $current_user;
	if(!check_modules_access($current_user, $module_name, 'write')){
		$error->set_error('no_access');	
		return array('ids'=>-1, 'error'=>$error->get_soap_array());
	}
	
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$ids = array();
	$count = 1;
	$total = sizeof($name_value_lists);
	foreach($name_value_lists as $name_value_list){
		$seed = new $class_name();
	
		$seed->update_vcal = false;
		foreach($name_value_list as $value){
			$seed->$value['name'] = $value['value'];
		}
		
		if($count == $total){
			$seed->update_vcal = false;	
		}
		$count++;
		
		//Add the account to a contact
		if($module_name == 'Contacts'){
			$GLOBALS['log']->debug('Creating Contact Account');
			add_create_account($seed);
			$duplicate_id = check_for_duplicate_contacts($seed);
			if($duplicate_id == null){
				if( $seed->ACLAccess('Save')){
					$seed->save();
					if($seed->deleted == 1){
						$seed->mark_deleted($seed->id);	
					}
					$ids[] = $seed->id;
				}
			}
			else{
				//since we found a duplicate we should set the sync flag
				if( $seed->ACLAccess('Save')){
					$seed->id = $duplicate_id;
					$seed->save();
					$ids[] = $duplicate_id;//we have a conflict	
				}
			}
		}
		else if($module_name == 'Meetings' || $module_name == 'Calls'){
			//we are going to check if we have a meeting in the system
			//with the same outlook_id. If we do find one then we will grab that 
			//id and save it
			if( $seed->ACLAccess('Save')){
				if(empty($seed->id) && !isset($seed->id)){
					if(!empty($seed->outlook_id) && isset($seed->outlook_id)){
						//at this point we have an object that does not have
						//the id set, but does have the outlook_id set
						//so we need to query the db to find if we already
						//have an object with this outlook_id, if we do
						//then we can set the id, otherwise this is a new object
						$order_by = "";
						$query = $seed->table_name.".outlook_id = '".$seed->outlook_id."'";
						$response = $seed->get_list($order_by, $query, 0,-1,-1,0);
						$list = $response['list'];
						if(count($list) > 0){
							foreach($list as $value)
							{
								$seed->id = $value->id;
								break;
							}
						}//fi
					}//fi
				}//fi
				$seed->save();
				$ids[] = $seed->id;
			}//fi
		}
		else
		{
			if( $seed->ACLAccess('Save')){
				$seed->save();
				$ids[] = $seed->id;
			}
		}
	}
	return array('ids'=>$ids, 'error'=>$error->get_soap_array());
	
}
/*

NOTE SPECIFIC CODE
*/
$server->register(
        'set_note_attachment',
        array('session'=>'xsd:string','note'=>'tns:note_attachment'),
        array('return'=>'tns:set_entry_result'),
        $NAMESPACE);  

/**
 * Add or replace the attachment on a Note.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param Binary $note -- The flie contents of the attachment.
 * @return Array 'id' -- The ID of the new note or -1 on error
 *               'error' -- The SOAP error if any.
 */
function set_note_attachment($session,$note)
{
	
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('id'=>-1, 'error'=>$error->get_soap_array());
	}
	
	require_once('modules/Notes/NoteSoap.php');
	$ns = new NoteSoap();
	return array('id'=>$ns->saveFile($note), 'error'=>$error->get_soap_array());

}

$server->register(
    'get_note_attachment',
    array('session'=>'xsd:string', 'id'=>'xsd:string'),
    array('return'=>'tns:return_note_attachment'),
    $NAMESPACE);

/**
 * Retrieve an attachment from a note
 * @param String $session -- Session ID returned by a previous call to login.
 * @param Binary $note -- The flie contents of the attachment.
 * @return Array 'id' -- The ID of the new note or -1 on error
 *               'error' -- The SOAP error if any.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param String $id -- The ID of the appropriate Note.
 * @return Array 'note_attachment' -- Array String 'id' -- The ID of the Note containing the attachment
 *                                          String 'filename' -- The file name of the attachment
 *                                          Binary 'file' -- The binary contents of the file.
 *               'error' -- The SOAP error if any.
 */
function get_note_attachment($session,$id)
{
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	require_once('modules/Notes/Note.php');
	$note = new Note();

	$note->retrieve($id);
	if(!$note->ACLAccess('DetailView')){
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	require_once('modules/Notes/NoteSoap.php');
	$ns = new NoteSoap();
	if(!isset($note->filename)){
		$note->filename = '';
	}
	$file= $ns->retrieveFile($id,$note->filename);
	if($file == -1){
		$error->set_error('no_file');
		$file = '';
	}

	return array('note_attachment'=>array('id'=>$id, 'filename'=>$note->filename, 'file'=>$file), 'error'=>$error->get_soap_array());

}
$server->register(
    'relate_note_to_module',
    array('session'=>'xsd:string', 'note_id'=>'xsd:string', 'module_name'=>'xsd:string', 'module_id'=>'xsd:string'),
    array('return'=>'tns:error_value'),
    $NAMESPACE);

/**
 * Attach a note to another bean.  Once you have created a note to store an
 * attachment, the note needs to be related to the bean.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param String $note_id -- The ID of the note that you want to associate with a bean
 * @param String $module_name -- The name of the module to return records from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 * @param String $module_id -- The ID of the bean that you want to associate the note with
 * @return no error for success, error for failure
 */
function relate_note_to_module($session,$note_id, $module_name, $module_id){
	global  $beanList, $beanFiles;
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return $error->get_soap_array();
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return $error->get_soap_array();
	}
	global $current_user;
	if(!check_modules_access($current_user, $module_name, 'read')){
		$error->set_error('no_access');	
		return $error->get_soap_array();
	}
	$class_name = $beanList['Notes'];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	$seed->retrieve($note_id);
	if(!$seed->ACLAccess('ListView')){
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	
	if($module_name != 'Contacts'){
		$seed->parent_type=$module_name;
		$seed->parent_id = $module_id;
		
	}else{
		
		$seed->contact_id=$module_id;

	}
	
	$seed->save();
	
	return $error->get_soap_array();
	
}
$server->register(
    'get_related_notes',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'module_id'=>'xsd:string', 'select_fields'=>'tns:select_fields'),
    array('return'=>'tns:get_entry_result'),
    $NAMESPACE);

/**
 * Retrieve the collection of notes that are related to a bean.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param String $module_name -- The name of the module to return records from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 * @param String $module_id -- The ID of the bean that you want to associate the note with
 * @param Array  $select_fields -- A list of the fields to be included in the results. This optional parameter allows for only needed fields to be retrieved.
 * @return Array    'result_count' -- The number of records returned (-1 on error)
 *                  'next_offset' -- The start of the next page (This will always be the previous offset plus the number of rows returned.  It does not indicate if there is additional data unless you calculate that the next_offset happens to be closer than it should be.
 *                  'field_list' -- The vardef information on the selected fields.
 *                  'entry_list' -- The records that were retrieved
 *                  'error' -- The SOAP error, if any
 */
function get_related_notes($session,$module_name, $module_id, $select_fields){
	global  $beanList, $beanFiles;
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	global $current_user;
	if(!check_modules_access($current_user, $module_name, 'read')){
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	$seed->retrieve($module_id);
	if(!$seed->ACLAccess('DetailView')){
		$error->set_error('no_access');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	$list = $seed->get_linked_beans('notes','Note', array(), 0, -1, 0);
	
	$output_list = Array();
	$field_list = Array();
	foreach($list as $value)
	{
		$output_list[] = get_return_value($value, 'Notes');
    	if(empty($field_list))
    	{
			$field_list = get_field_list($value);	
		}
	}
	$output_list = filter_return_list($output_list, $select_fields, $module_name);
	$field_list = filter_field_list($field_list,$select_fields, $module_name);

	return array('result_count'=>sizeof($output_list), 'next_offset'=>0,'field_list'=>$field_list, 'entry_list'=>$output_list, 'error'=>$error->get_soap_array());
}

$server->register(
        'logout',
        array('session'=>'xsd:string'),
        array('return'=>'tns:error_value'),
        $NAMESPACE); 

/**
 * Log out of the session.  This will destroy the session and prevent other's from using it.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @return Empty error on success, Error on failure
 */
function logout($session){
	$error = new SoapError();
	if(validate_authenticated($session)){
		session_destroy();
		return $error->get_soap_array();
	}
	$error->set_error('no_session');
	return $error->get_soap_array();
}

$server->register(
    'get_module_fields',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string'),
    array('return'=>'tns:module_fields'),
    $NAMESPACE);

/**
 * Retrieve vardef information on the fields of the specified bean.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param String $module_name -- The name of the module to return records from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 * @return Array    'module_fields' -- The vardef information on the selected fields.
 *                  'error' -- The SOAP error, if any
 */
function get_module_fields($session, $module_name){
	global  $beanList, $beanFiles;
	$error = new SoapError();
	$module_fields = array();
	if(! validate_authenticated($session)){
		$error->set_error('invalid_session');	
		return array('module_fields'=>$module_fields, 'error'=>$error->get_soap_array());
	}
	if(empty($beanList[$module_name])){
		$error->set_error('no_module');	
		return array('module_fields'=>$module_fields, 'error'=>$error->get_soap_array());
	}
	global $current_user;
	if(!check_modules_access($current_user, $module_name, 'read')){
		$error->set_error('no_access');	
		return array('module_fields'=>$module_fields, 'error'=>$error->get_soap_array());
	}
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$seed = new $class_name();
	if($seed->ACLAccess('ListView', true) || $seed->ACLAccess('DetailView', true) || 	$seed->ACLAccess('EditView', true) )
    {
    	return get_return_module_fields($seed, $module_name, $error);
    }
    else
    {
    	$error->set_error('no_access');	
    	return array('module_fields'=>$module_fields, 'error'=>$error->get_soap_array());
    }
}

$server->register(
    'get_available_modules',
    array('session'=>'xsd:string'),
    array('return'=>'tns:module_list'),
    $NAMESPACE);

/**
 * Retrieve the list of available modules on the system available to the currently logged in user.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @return Array    'modules' -- An array of module names
 *                  'error' -- The SOAP error, if any
 */
function get_available_modules($session){
	$error = new SoapError();
	$modules = array();
	if(! validate_authenticated($session)){
		$error->set_error('invalid_session');	
		return array('modules'=> $modules, 'error'=>$error->get_soap_array());
	}
	$modules = array_keys($_SESSION['avail_modules']);
	
	return array('modules'=> $modules, 'error'=>$error->get_soap_array());
}


$server->register(
    'update_portal_user',
    array('session'=>'xsd:string', 'portal_name'=>'xsd:string', 'name_value_list'=>'tns:name_value_list'),
    array('return'=>'tns:error_value'),
    $NAMESPACE);

/**
 * Update the properties of a contact that is portal user.  Add the portal user name to the user's properties.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param String $portal_name -- The portal user_name of the contact
 * @param Array $name_value_list -- collection of 'name'=>'value' pairs for finding the contact
 * @return Empty error on success, Error on failure
 */
function update_portal_user($session,$portal_name, $name_value_list){
	global  $beanList, $beanFiles;
	$error = new SoapError();
	if(! validate_authenticated($session)){
		$error->set_error('invalid_session');	
		return $error->get_soap_array();
	}
	$contact = new Contact();
	
	$searchBy = array('deleted'=>0);
	foreach($name_value_list as $name_value){
			$searchBy[$name_value['name']] = $name_value['value'];
	}
	if($contact->retrieve_by_string_fields($searchBy) != null){
		if(!$contact->duplicates_found){
			$contact->portal_name = $portal_name;
			$contact->portal_active = 1;
			if($contact->ACLAccess('Save')){
				$contact->save();
			}else{
				$error->set_error('no_access');
			}
			return $error->get_soap_array();
		}	
		$error->set_error('duplicates');
		return $error->get_soap_array();
	}
	$error->set_error('no_records');
	return $error->get_soap_array();
}

$server->register(
    'test',
    array('string'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);

/**
 * A simple test method that returns the string you pass into it.  It is convenient for
 * verifying connectivity and server availability.
 *
 * @param String $string -- An arbirtray string that will be returned
 * @return String -- The string that you sent in.
 */
function test($string){
	return $string;	
}

$server->register(
    'get_user_id',
    array('session'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
    
/**
 * Return the user_id of the user that is logged into the current session.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @return String -- the User ID of the current session
 *                  -1 on error.
 */
function get_user_id($session){
	if(validate_authenticated($session)){
		global $current_user;
		return $current_user->id;
		
	}else{
		return '-1';	
	}
}

$server->register(
    'get_user_team_id',
    array('session'=>'xsd:string'),
    array('return'=>'xsd:string'),
    $NAMESPACE);
    
/**
 * Return the ID of the default team for the user that is logged into the current session.
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @return String -- the Team ID of the current user's default team
 *                  1 for Open Source
 *                  -1 on error.
 */
function get_user_team_id($session){
	if(validate_authenticated($session))
	{





		 return 1;



	}else{
		return '-1';	
	}
}

$server->register(
    'get_server_time',
    array(),
    array('return'=>'xsd:string'),
    $NAMESPACE);

/**
 * Return the current time on the server in the format 'Y-m-d H:i:s'.  This time is in the server's default timezone.
 *
 * @return String -- The current date/time 'Y-m-d H:i:s'
 */
function get_server_time(){
	return date('Y-m-d H:i:s');
}

$server->register(
    'get_gmt_time',
    array(),
    array('return'=>'xsd:string'),
    $NAMESPACE);
    
/**
 * Return the current time on the server in the format 'Y-m-d H:i:s'.  This time is in GMT.
 *
 * @return String -- The current date/time 'Y-m-d H:i:s'
 */
function get_gmt_time(){
	return gmdate('Y-m-d H:i:s');
}

$server->register(
    'get_sugar_flavor',
    array(),
    array('return'=>'xsd:string'),
    $NAMESPACE);

/**
 * Retrieve the specific flavor of sugar.
 *
 * @return String   'OS' -- For Open Source
 *                  'PRO' -- For Professional
 *                  'ENT' -- For Enterprise
 */
function get_sugar_flavor(){
 global $sugar_flavor;
 require_once('sugar_version.php');
 return $sugar_flavor;   
}
    

$server->register(
    'get_server_version',
    array(),
    array('return'=>'xsd:string'),
    $NAMESPACE);

/**
 * Retrieve the version number of Sugar that the server is running.
 *
 * @return String -- The current sugar version number. 
 *                   '1.0' on error.
 */
function get_server_version(){
	require_once('modules/Administration/Administration.php');
	$admin  = new Administration();
	$admin->retrieveSettings('info');
	if(isset($admin->settings['info_sugar_version'])){
		return $admin->settings['info_sugar_version'];
	}else{
		return '1.0';	
	} 
	
}

$server->register(
    'get_relationships',
    array('session'=>'xsd:string', 'module_name'=>'xsd:string', 'module_id'=>'xsd:string', 'related_module'=>'xsd:string', 'related_module_query'=>'xsd:string', 'deleted'=>'xsd:int'),
    array('return'=>'tns:get_relationships_result'),
    $NAMESPACE);

/**
 * Retrieve a collection of beans tha are related to the specified bean.  
 * Only the listed combinations below are supported.  On the left is the 
 * primary module.  Under each primary module is a list of available related modules.
 *  'Contacts'=>
 *				'Calls'
 *				'Meetings'
 *				'Users'
 *	'Users'=>
 *				'Calls'
 *				'Meetings'
 *				'Contacts'
 *	'Meetings'=>
 *	            'Contacts'
 *	            'Users'
 *  'Calls'=>
 *	            'Contacts'
 *	            'Users'
 *  'Accounts'=>
 *	            'Contacts'
 *	            'Users'
 * 
 *
 * @param String $session -- Session ID returned by a previous call to login.
 * @param String $module_name -- The name of the module that the primary record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 * @param String $module_id -- The ID of the bean in the specified module
 * @param String $related_module -- The name of the related module to return records from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 * @param String $related_module_query -- A portion of the where clause of the SQL statement to find the related items.  The SQL query will already be filtered to only include the beans that are related to the specified bean.
 * @param Number $deleted -- false if deleted records should not be include, true if deleted records should be included.
 * @return unknown
 */
function get_relationships($session, $module_name, $module_id, $related_module, $related_module_query, $deleted){
		$error = new SoapError();
	$ids = array();	
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('ids'=>$ids,'error'=> $error->get_soap_array());
	}
	global  $beanList, $beanFiles;
	$error = new SoapError();
	
	if(empty($beanList[$module_name]) || empty($beanList[$related_module])){
		$error->set_error('no_module');	
		return array('ids'=>$ids, 'error'=>$error->get_soap_array());
	}
	$class_name = $beanList[$module_name];
	require_once($beanFiles[$class_name]);
	$mod = new $class_name();
	$mod->retrieve($module_id);
	if(!$mod->ACLAccess('DetailView')){
		$error->set_error('no_access');	
		return array('ids'=>$ids, 'error'=>$error->get_soap_array());
	}
	$list = array();

	//TODO -- Need to add better default logic.
	switch($module_name){
		case 'Contacts':
			switch($related_module){
				case 'Calls':
					$list = $mod->get_linked_beans('calls','Call', array(),  0, -1, $deleted);
					break;
				case 'Meetings':
					$list = $mod->get_linked_beans('meetings','Meeting',  array(),  0, -1, $deleted);
					break;
				case 'Users':
					$list = $mod->get_linked_beans('user_sync','User',  array(),  0, -1, $deleted); 
                    break;
                default:
                    $error->set_error('no_module'); 
                    return array('ids'=>$ids, 'error'=>$error->get_soap_array());     
			}
			break;
		case 'Users':
			switch($related_module){
				case 'Calls':
					$list = $mod->get_linked_beans('calls','Call',  array(),  0, -1, $deleted);
					break;
				case 'Meetings':
					$list = $mod->get_linked_beans('meetings','Meeting',  array(),  0, -1, $deleted);
					break;
				case 'Contacts':
					$list = $mod->get_linked_beans('contacts_sync','Contact',  array(),  0, -1, $deleted);
                    break;
                default:
                    $error->set_error('no_module'); 
                    return array('ids'=>$ids, 'error'=>$error->get_soap_array());
					
			}
			break;
		case 'Meetings':
			switch($related_module){
				case 'Contacts':
					$list = $mod->get_linked_beans('contacts','Contact',  array(),  0, -1, $deleted);
					break;
				case 'Users':
					$list = $mod->get_linked_beans('users','User',  array(),  0, -1, $deleted);				
					break;
                default:
                    $error->set_error('no_module'); 
                    return array('ids'=>$ids, 'error'=>$error->get_soap_array());
			}
			break;
		case 'Calls':
			switch($related_module){
				case 'Contacts':
					$list = $mod->get_linked_beans('contacts','Contact',  array(),  0, -1, $deleted);
					break;
				case 'Users':
					$list = $mod->get_linked_beans('users','User',  array(),  0, -1, $deleted);				
					break;
                default:
                    $error->set_error('no_module'); 
                    return array('ids'=>$ids, 'error'=>$error->get_soap_array());
			}
		case  'Accounts':
			switch($related_module){
				case 'Contacts':
					$list = $mod->get_linked_beans('contacts','Contact',  array(),  0, -1, $deleted);
					break;
				case 'Users':
					$list = $mod->get_linked_beans('users','User',  array(),  0, -1, $deleted);				
					break;
                default:
                    $error->set_error('no_module'); 
                    return array('ids'=>$ids, 'error'=>$error->get_soap_array());
			}
			break;
        default:
                    $error->set_error('no_module'); 
                    return array('ids'=>$ids, 'error'=>$error->get_soap_array());
	}
	$in = '';
	foreach($list as $item){
		if(empty($in)){
			$in .= "('" . $item->id ."'";	
		}else{
			$in .= ",'" . $item->id ."'";		
		}
		$ids[] = array('id'=>$item->id, 'date_modified'=>$item->date_modified, 'deleted'=>$item->deleted);
	}

	if(!empty($in) && !empty($related_module_query)){
		$in .=")";
		$ids = array();
		$class_name = $beanList[$related_module];
		require_once($beanFiles[$class_name]);
		$r_mod = new $class_name();
		$result = $r_mod->db->query("SELECT id, date_modified FROM " .$r_mod->table_name . " WHERE id IN $in AND ( $related_module_query )");
		while($row = $r_mod->db->fetchByAssoc($result)){
			$ids[] = array('id'=>$row['id'] , 'date_modified'=>$row['date_modified'], 'deleted'=>$row['deleted']);
		}
	}
	

	return array('ids'=>$ids, 'error'=> $error->get_soap_array());
}


$server->register(
    'set_relationship',
    array('session'=>'xsd:string','set_relationship_value'=>'tns:set_relationship_value'),
    array('return'=>'tns:error_value'),
    $NAMESPACE);

/**
 * Set a single relationship between two beans.  The items are related by module name and id.
 *
 * @param String $session -- Session ID returned by a previous call to login. 
 * @param Array $set_relationship_value --
 *      'module1' -- The name of the module that the primary record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 *      'module1_id' -- The ID of the bean in the specified module
 *      'module2' -- The name of the module that the related record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 *      'module2_id' -- The ID of the bean in the specified module
 * @return Empty error on success, Error on failure
 */
function set_relationship($session, $set_relationship_value){
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return $error->get_soap_array();
	}
	return handle_set_relationship($set_relationship_value);
}

$server->register(
    'set_relationships',
    array('session'=>'xsd:string','set_relationship_list'=>'tns:set_relationship_list'),
    array('return'=>'tns:set_relationship_list_result'),
    $NAMESPACE);
    
/**
 * Setup several relationships between pairs of beans.  The items are related by module name and id.
 *
 * @param String $session -- Session ID returned by a previous call to login. 
 * @param Array $set_relationship_list -- One for each relationship to setup.  Each entry is itself an array.
 *      'module1' -- The name of the module that the primary record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 *      'module1_id' -- The ID of the bean in the specified module
 *      'module2' -- The name of the module that the related record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 *      'module2_id' -- The ID of the bean in the specified module
 * @return Empty error on success, Error on failure
 */
function set_relationships($session, $set_relationship_list){
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return -1;
	}
	$count = 0;
	$failed = 0;
	foreach($set_relationship_list as $set_relationship_value){
		$reter = handle_set_relationship($set_relationship_value);
		if($reter['number'] == 0){
			$count++;	
		}else{
			$failed++;
		}
	}
	return array('created'=>$count , 'failed'=>$failed, 'error'=>$error);
}



//INTERNAL FUNCTION NOT EXPOSED THROUGH SOAP
/**
 * (Internal) Create a relationship between two beans. 
 *
 * @param Array $set_relationship_value --
 *      'module1' -- The name of the module that the primary record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 *      'module1_id' -- The ID of the bean in the specified module
 *      'module2' -- The name of the module that the related record is from.  This name should be the name the module was developed under (changing a tab name is studio does not affect the name that should be passed into this method)..
 *      'module2_id' -- The ID of the bean in the specified module
 * @return Empty error on success, Error on failure
 */
function handle_set_relationship($set_relationship_value)
{
    global  $beanList, $beanFiles;
    $error = new SoapError();
    
    $module1 = $set_relationship_value['module1'];
    $module1_id = $set_relationship_value['module1_id'];
    $module2 = $set_relationship_value['module2'];
    $module2_id = $set_relationship_value['module2_id'];    
    
    if(empty($beanList[$module1]) || empty($beanList[$module2]) )
    {
        $error->set_error('no_module');    
        return $error->get_soap_array();
    }
    $class_name = $beanList[$module1];
    require_once($beanFiles[$class_name]);
    $mod = new $class_name();
    $mod->retrieve($module1_id);
	if(!$mod->ACLAccess('DetailView')){
		$error->set_error('no_access');	
		return $error->get_soap_array();
	}
	if($module1 == "Contacts" && $module2 == "Users"){
		$key = 'contacts_users_id';
	}
	else{
    	$key = array_search(strtolower($module2),$mod->relationship_fields);
	}
	
    if(!$key)
    {
        $error->set_error('no_module');    
        return $error->get_soap_array();    
    }
    $mod->$key = $module2_id;    
    $mod->save_relationship_changes(false);
    return $error->get_soap_array();    
}


$server->register(
        'set_document_revision',
        array('session'=>'xsd:string','note'=>'tns:document_revision'),
        array('return'=>'tns:set_entry_result'),
        $NAMESPACE);  

/**
 * Enter description here...
 *
 * @param String $session -- Session ID returned by a previous call to login. 
 * @param unknown_type $document_revision
 * @return unknown
 */
function set_document_revision($session,$document_revision)
{
	
	$error = new SoapError();
	if(!validate_authenticated($session)){
		$error->set_error('invalid_login');	
		return array('id'=>-1, 'error'=>$error->get_soap_array());
	}
	
	require_once('modules/Documents/DocumentSoap.php');
	$dr = new DocumentSoap();
	return array('id'=>$dr->saveFile($document_revision), 'error'=>$error->get_soap_array());

}

$server->register(
        'search_by_module',
        array('user_name'=>'xsd:string','password'=>'xsd:string','search_string'=>'xsd:string', 'modules'=>'tns:select_fields', 'offset'=>'xsd:int', 'max_results'=>'xsd:int'),
        array('return'=>'tns:get_entry_list_result'),
        $NAMESPACE); 

/**
 * Enter description here...
 *
 * @param unknown_type $user_name
 * @param unknown_type $password
 * @param unknown_type $search_string
 * @param unknown_type $modules
 * @param unknown_type $offset
 * @param unknown_type $max_results
 * @return unknown
 */
function search_by_module($user_name, $password, $search_string, $modules, $offset, $max_results){
	global  $beanList, $beanFiles;

	$error = new SoapError();
	if(!validate_user($user_name, $password)){
		$error->set_error('invalid_login');	
		return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
	}
	global $current_user;
	if($max_results > 0){
		global $sugar_config;
		$sugar_config['list_max_entries_per_page'] = $max_results;	
	}
	$query_array = array('Accounts'=>array('where'=>"accounts.name like '{0}%'",'fields'=>"accounts.id id, accounts.name"),
							'Bugs'=>array('where'=>"bugs.name like '{0}%' OR bugs.bug_number = '{0}'",'fields'=>"bugs.id, bugs.name, bugs.bug_number"),
							'Cases'=>array('where'=>"cases.name like '{0}%' OR cases.case_number = '{0}'",'fields'=>"cases.id, cases.name, cases.case_number"),
							'Contacts'=>array('where'=>"contacts.first_name like '{0}%' OR contacts.last_name like '{0}%' OR contacts.email1 like '{0}%' OR contacts.email2 like '{0}%'",'fields'=>"contacts.id, contacts.first_name, contacts.last_name, contacts.email1"),
							'Leads'=>array('where'=>"leads.first_name like '{0}%' OR leads.last_name like '{0}%' OR leads.email1 like '{0}%' OR leads.email2 like '{0}%'", 'fields'=>"leads.id, leads.first_name, leads.last_name, leads.email1, leads.status"),	
							'Opportunities'=>array('where'=>"opportunities.name like '{0}%'", 'fields'=>"opportunities.id, opportunities.name"),
                            'Project'=>array('where'=>"project.name like '{0}%'", 'fields'=>"project.id, project.name"),
                            'ProjectTask'=>array('where'=>"project.id = '{0}'", 'fields'=>"project_task.id, project_task.name"));

	if(!empty($search_string) && isset($search_string)){
		foreach($modules as $module_name){
			$class_name = $beanList[$module_name];
			require_once($beanFiles[$class_name]);
			$seed = new $class_name();
			if(empty($beanList[$module_name])){
				continue;
			}
			if(!check_modules_access($current_user, $module_name, 'read')){
				continue;
			}
			if(! $seed->ACLAccess('ListView'))
			{
				continue;
			}
			if(isset($query_array[$module_name])){
				$query = "SELECT ".$query_array[$module_name]['fields']." FROM $seed->table_name ";
				// We need to confirm that the user is a member of the team of the item.




                if($module_name == 'ProjectTask'){
                    $query .= "INNER JOIN project ON $seed->table_name.parent_id = project.id ";   
                }
				$where = "WHERE (";
				$search_terms = explode(", ", $search_string);
				$termCount = count($search_terms);
				$count = 1;
				foreach($search_terms as $term){
					$where .= string_format($query_array[$module_name]['where'],array($term));
					if($count < $termCount){
						$where .= " OR ";
					}
					$count++;
				}
				$query .= $where;
				$query .= ") AND $seed->table_name.deleted = 0";
				//grab the items from the db
				$result = $seed->db->limitQuery($query, $offset, $max_results);

				$list = Array();
				if(empty($rows_found)){
  						$rows_found =  $seed->db->getRowCount($result);
				}//fi
			
				$row_offset = 0;

				while(($row = $seed->db->fetchByAssoc($result)) != null){
					$list = array();
					$fields = explode(", ", $query_array[$module_name]['fields']);
					foreach($fields as $field){
						$field_names = explode(".", $field);
						$list[$field] = array('name'=>$field_names[1], 'value'=>$row[$field_names[1]]);
					}
			
					$output_list[] = array('id'=>$row['id'],
									   'module_name'=>$module_name,
									   'name_value_list'=>$list);
					if(empty($field_list)){
						$field_list = get_field_list($row);	
					}
				}//end while
			}
		}//end foreach
	}
		
	$next_offset = $offset + sizeof($output_list);

	return array('result_count'=>sizeof($output_list), 'next_offset'=>$next_offset,'field_list'=>$field_list, 'entry_list'=>$output_list, 'error'=>$error->get_soap_array());
	
}//end function


































































































































?>
