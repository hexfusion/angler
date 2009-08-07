<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

 // $Id: SugarAuthenticateUser.php,v 1.3 2006/08/22 20:56:11 awu Exp $

/**
 * This file is where the user authentication occurs. No redirection should happen in this file.
 *
 */
class SugarAuthenticateUser{
	
	/**
	 * Does the actual authentication of the user and returns an id that will be used 
	 * to load the current user (loadUserOnSession)
	 *
	 * @param STRING $name
	 * @param STRING $password
	 * @return STRING id - used for loading the user
	 */
	function authenticateUser($name, $password) {
		
		$query = "SELECT * from users where user_name='$name' AND user_hash='$password' AND (portal_only IS NULL OR portal_only !='1') AND (is_group IS NULL OR is_group !='1') AND status !='Inactive'";
		$result =$GLOBALS['db']->limitQuery($query,0,1,false);
		$row = $GLOBALS['db']->fetchByAssoc($result);
		// set the ID in the seed user.  This can be used for retrieving the full user record later
		if (empty ($row)) {
			return '';
		} else {
			return $row['id'];
		}
	}
	/**
	 * Checks if a user is a sugarLogin user 
	 * which implies they should use the sugar authentication to login
	 *
	 * @param STRING $name
	 * @param STRIUNG $password
	 * @return boolean
	 */
	function isSugarLogin($name, $password){
		$password = SugarAuthenticate::encodePassword($password);
		$query = "SELECT * from users where user_name='$name' AND user_hash='$password' AND (portal_only IS NULL OR portal_only !='1') AND (is_group IS NULL OR is_group !='1') AND status !='Inactive' AND sugar_login=1";
		$result =$GLOBALS['db']->limitQuery($query,0,1,false);
		$row = $GLOBALS['db']->fetchByAssoc($result);
		if($row)return true;
		return false;
	}
	
	/**
	 * this is called when a user logs in 
	 *
	 * @param STRING $name
	 * @param STRING $password
	 * @return boolean
	 */
	function loadUserOnLogin($name, $password) {
		global $login_error;

		$GLOBALS['log']->debug("Starting user load for ". $name);
		if(empty($name) || empty($password)) return false;
		$user_hash = SugarAuthenticate::encodePassword($password);
		$user_id = $this->authenticateUser($name, $user_hash);
		if(empty($user_id)) {
			$GLOBALS['log']->fatal('SECURITY: User authentication for '.$name.' failed');
			return false;
		}
		$this->loadUserOnSession($user_id);
		return true;
	}
	/**
	 * Loads the current user bassed on the given user_id 
	 *
	 * @param STRING $user_id
	 * @return boolean
	 */
	function loadUserOnSession($user_id=''){
		if(!empty($user_id)){
			$_SESSION['authenticated_user_id'] = $user_id;
		}
		
		if(!empty($_SESSION['authenticated_user_id']) || !empty($user_id)){
			$GLOBALS['current_user'] = new User();
			if($GLOBALS['current_user']->retrieve($_SESSION['authenticated_user_id'])){
				
				return true;
			}
		}
		return false;
		
	}

}

?>
