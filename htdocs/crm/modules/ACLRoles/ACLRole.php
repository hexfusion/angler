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
require_once('data/SugarBean.php');
class ACLRole extends SugarBean{
	var $module_dir = 'ACLRoles';
	var $object_name = 'ACLRole';
	var $table_name = 'acl_roles';
	var $new_schema = true;
	var $disable_row_level_security = true;
	var $relationship_fields = array(
									'user_id'=>'users'
								);
	function ACLRole(){
		parent::SugarBean();



	}
	
	


/**
 * function setAction($role_id, $action_id, $access)
 * 
 * Sets the relationship between a role and an action and sets the access level of that relationship
 *
 * @param GUID $role_id - the role id
 * @param GUID $action_id - the ACL Action id
 * @param int $access - the access level ACL_ALLOW_ALL ACL_ALLOW_NONE ACL_ALLOW_OWNER...
 */
function setAction($role_id, $action_id, $access){
	$relationship_data = array('role_id'=>$role_id, 'action_id'=>$action_id,);
	$additional_data = array('access_override'=>$access);
	$this->set_relationship('acl_roles_actions',$relationship_data,true, true,$additional_data);
}

/**
 * static  getUserRoles($user_id)
 * returns a list of ACLRoles for a given user id
 *
 * @param GUID $user_id
 * @return a list of ACLRole objects
 */
function getUserRoles($user_id){
		
		//if we don't have it loaded then lets check against the db
		$additional_where = '';
		$db =& PearDatabase::getInstance();
		$query = "SELECT acl_roles.* 
					FROM acl_roles 
					INNER JOIN acl_roles_users ON acl_roles_users.user_id = '$user_id' AND  acl_roles_users.deleted = 0
					WHERE acl_roles.name";  
	
		$result = $db->query($query);
		$user_roles = array();
		
		while($row = $db->fetchByAssoc($result) ){
			$role = new ACLRole();
			$role->populateFromRow($row);
			$user_roles[] = $role;
		
		}
		return $user_roles;
	
	}
	
/**
 * static getAllRoles($returnAsArray = false)
 *
 * @param boolean $returnAsArray - should it return the results as an array of arrays or as an array of ACLRoles
 * @return either an array of array representations of acl roles or an array of ACLRoles
 */
function getAllRoles($returnAsArray = false){
		$db =& PearDatabase::getInstance();
		$query = "SELECT acl_roles.* FROM acl_roles
					WHERE acl_roles.deleted=0 ORDER BY name";
	
		$result = $db->query($query);
		$roles = array();
		
		while($row = $db->fetchByAssoc($result) ){
			$role = new ACLRole();
			$role->populateFromRow($row);
			if($returnAsArray){
				$roles[] = $role->toArray();
			}else{
				$roles[] = $role;
			}
		
		}
		return $roles;
		

}
	
/**
 * static getRoleActions($role_id)
 * 
 * gets the actions of a given role
 *
 * @param GUID $role_id
 * @return array of actions 
 */
function getRoleActions($role_id, $type='module'){
		
		//if we don't have it loaded then lets check against the db
		$additional_where = '';
		$db =& PearDatabase::getInstance();
		$query = "SELECT acl_actions.*";
		//only if we have a role id do we need to join the table otherwise lets use the ones defined in acl_actions as the defaults
		if(!empty($role_id)){
				$query .=" ,acl_roles_actions.access_override ";
		}
		$query .=" FROM acl_actions ";
		
		if(!empty($role_id)){
			$query .=		" LEFT JOIN acl_roles_actions ON acl_roles_actions.role_id = '$role_id' AND  acl_roles_actions.action_id = acl_actions.id AND acl_roles_actions.deleted = 0";
		}
		$query .= " WHERE acl_actions.deleted=0 ORDER BY acl_actions.category, acl_actions.name";
		$result = $db->query($query);
		$role_actions = array();
		
		while($row = $db->fetchByAssoc($result) ){
			$action = new ACLAction();
			$action->populateFromRow($row);
			if(!empty($row['access_override'])){
				$action->aclaccess = $row['access_override'];
			}else{
				$action->aclaccess = ACL_ALLOW_DEFAULT;	
				
			}
			if(!isset($role_actions[$action->category])){
				$role_actions[$action->category] = array();
			}
	
			$role_actions[$action->category][$action->acltype][$action->name] = $action->toArray();	
			
		
		}
		return $role_actions;
	
	}
/**
 * function mark_relationships_deleted($id)
 * 
 * special case to delete acl_roles_actions relationship 
 *
 * @param ACLRole GUID $id
 */
function mark_relationships_deleted($id){
		//we need to delete the actions relationship by hand (special case)
		$date_modified = db_convert("'".gmdate("Y-m-d H:i:s")."'", 'datetime');
		$query =  "UPDATE acl_roles_actions SET deleted=1 , date_modified=$date_modified WHERE role_id = '$id' AND deleted=0";
		$this->db->query($query);
		parent::mark_relationships_deleted($id);
}

/**
 *  toArray()
	 * returns this role as an array
	 *
	 * @return array of fields with id, name, description
	 */
	function toArray(){
		$array_fields = array('id', 'name', 'description');
		$arr = array();
		foreach($array_fields as $field){
			if(isset($this->$field)){
				$arr[$field] = $this->$field;
			}else{
				$arr[$field] = '';
			}
		}
		return $arr;
	}
	
	/**
	 * fromArray($arr)
	 * converts an array into an role mapping name value pairs into files
	 *
	 * @param Array $arr
	 */
	function fromArray($arr){
		foreach($arr as $name=>$value){
			$this->$name = $value;
		}
	}
}

?>
