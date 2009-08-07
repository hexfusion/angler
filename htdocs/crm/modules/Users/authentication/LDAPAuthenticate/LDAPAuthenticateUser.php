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

 // $Id: LDAPAuthenticateUser.php,v 1.3 2006/08/22 20:56:11 awu Exp $

/**
 * This file is where the user authentication occurs. No redirection should happen in this file.
 *
 */
require_once('modules/Users/authentication/LDAPAuthenticate/LDAPConfigs/default.php');
require_once('modules/Users/authentication/SugarAuthenticate/SugarAuthenticateUser.php');
class LDAPAuthenticateUser extends SugarAuthenticateUser{
	
	/**
	 * Does the actual authentication of the user and returns an id that will be used 
	 * to load the current user (loadUserOnSession)
	 *
	 * @param STRING $name
	 * @param STRING $password
	 * @return STRING id - used for loading the user
	 * 
	 * Contributions by Erik Mitchell erikm@logicpd.com
	 */
	function authenticateUser($name, $password) {
		
		$server = $GLOBALS['ldap_config']->settings['ldap_hostname'];
		$GLOBALS['log']->debug("ldapauth: Connecting to LDAP server: $server");
		$ldapconn = ldap_connect($server);
		 $error = ldap_errno($ldapconn);
		if($this->loginError($error)){
        		return '';
		}
		@ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		@ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0); // required for AD
		
		
		$bind_user = $this->ldap_rdn_lookup($name, $password);
		$GLOBALS['log']->debug("ldapauth.ldap_authenticate_user: ldap_rdn_lookup returned bind_user=" . $bind_user);
		if (!$bind_user) {
			$GLOBALS['log']->fatal("SECURITY: ldapauth: failed LDAP bind (login) by " .
									$name . ", could not construct bind_user");
			return '';
		}
		
		

		$bind_password = $password;
		$GLOBALS['log']->info("ldapauth: Binding user " . $bind_user);
		$bind = ldap_bind($ldapconn, $bind_user, $bind_password);
		 $error = ldap_errno($ldapconn);
        if($this->loginError($error)){
        
        		return '';
			}
		
		$GLOBALS['log']->info("ldapauth: Bind attempt complete.");
    		
		if ($bind) {
			// Authentication succeeded, get info from LDAP directory
			$attrs = array_keys($GLOBALS['ldapConfig']['users']['fields']);
			$base_dn = $GLOBALS['ldap_config']->settings['ldap_base_dn'];
			$name_filter = "(" . $GLOBALS['ldap_config']->settings['ldap_login_attr']. "=" . $name . ")";
	
			$GLOBALS['log']->debug("ldapauth: Fetching user info from Directory.");
			$result = @ldap_search($ldapconn, $base_dn, $name_filter, $attrs);
			 if($this->loginError($error)){
        		return '';
			}
			$GLOBALS['log']->debug("ldapauth: ldap_search complete.");
	
			$info = @ldap_get_entries($ldapconn, $result);
			 $error = ldap_errno($ldapconn);
       		if($this->loginError($error)){
        		return '';
			}
			$GLOBALS['log']->debug("ldapauth: User info from Directory fetched.");
	
			// some of these don't seem to work
			$this->ldapUserInfo = array();
			foreach($GLOBALS['ldapConfig']['users']['fields'] as $key=>$value){
				if(isset($info[0]) && isset($info[0][$key]) && isset($info[0][$key][0])){
					$this->ldapUserInfo[$value] = $info[0][$key][0];
				}
			}
			
			ldap_close($ldapconn);
			$dbresult = $GLOBALS['db']->query("SELECT id FROM users WHERE user_name='" . $name . "' AND deleted = 0");
			
			//user already exists use this one
			if($row = $GLOBALS['db']->fetchByAssoc($dbresult)){
				return $row['id'];
			}
			
			//create a new user and return the user
			if($GLOBALS['ldap_config']->settings['ldap_auto_create_users']){
				return $this->createUser($name);
					
			}
			return '';

		} else {			
			$GLOBALS['log']->fatal("SECURITY: failed LDAP bind (login) by $this->user_name using bind_user=$bind_user");
			$GLOBALS['log']->fatal("ldapauth: failed LDAP bind (login) by $this->user_name using bind_user=$bind_user");
			ldap_close($ldapconn);
			return '';
		}
	}
	
	/**
	 * Creates a user with the given User Name and returns the id of that new user
	 * populates the user with what was set in ldapUserInfo
	 *
	 * @param STRING $name
	 * @return STRING $id
	 */
	function createUser($name){
		
			$user = new User();
			$user->user_name = $name;
			foreach($this->ldapUserInfo as $key=>$value){
				$user->$key = $value;
			}
			$user->employee_status = 'Active';
			$user->status = 'Active';
			$user->is_admin = 0;
			$user->save();
			return $user->id;
		
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
		$GLOBALS['ldap_config']  = new Administration();
		$GLOBALS['ldap_config']->retrieveSettings('ldap');
		$GLOBALS['log']->debug("Starting user load for ". $name);
		if(empty($name) || empty($password)) return false;
		checkAuthUserStatus();
		
		$user_id = $this->authenticateUser($name, $password);
		if(empty($user_id)) {
			//check if the user can login as a normal sugar user
			$GLOBALS['log']->fatal('SECURITY: User authentication for '.$name.' failed');
			return false;
		}
		$this->loadUserOnSession($user_id);
		return true;
	}
	 
	
	/**
	 * Called with the error number of the last call if the error number is 0 
	 * there was no error otherwise it converts the error to a string and logs it as fatal
	 *
	 * @param INT $error
	 * @return boolean
	 */
	function loginError($error){
		if(empty($error)) return false;
		$errorstr = ldap_err2str($error);
		// BEGIN SUGAR INT
		$_SESSION['login_error'] = $errorstr;
		/*
		// END SUGAR INT
		$GLOBALS['login_error'] = translate('LBL_LDAP_ERROR', 'Users');
		// BEGIN SUGAR INT
		*/
		// END SUGAR INT
		$GLOBALS['log']->fatal('[LDAP ERROR]['. $error . ']'.$errorstr);
		return true;
	}
	
	 /**
    * @return string appropriate value for username when binding to directory server.
    * @param string $user_name the value provided in login form
    * @desc Take the login username and return either said username for AD or lookup
     * distinguished name using anonymous credentials for OpenLDAP.
     * Contributions by Erik Mitchell erikm@logicpd.com
    */
    function ldap_rdn_lookup($user_name, $password) {
       
        
        $server = $GLOBALS['ldap_config']->settings['ldap_hostname'];
        $base_dn = $GLOBALS['ldap_config']->settings['ldap_base_dn'];
        $admin_user = $GLOBALS['ldap_config']->settings['ldap_admin_user'];
        $admin_password = $GLOBALS['ldap_config']->settings['ldap_admin_password'];
        $user_attr = $GLOBALS['ldap_config']->settings['ldap_login_attr'];
        $bind_attr = $GLOBALS['ldap_config']->settings['ldap_bind_attr'];
        
        $ldapconn = ldap_connect($server);
        $error = ldap_errno($ldapconn);
        if($this->loginError($error)){
        	return false;
		}
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0); // required for AD
        //if we are going to connect anonymously lets atleast try to connect with the user connecting
        if(empty($admin_user)){
			$bind = @ldap_bind($ldapconn, $user_name, $password);
        	$error = ldap_errno($ldapconn);
        }
        if(empty($bind)){
        	$bind = @ldap_bind($ldapconn, $admin_user, $admin_password);
        	$error = ldap_errno($ldapconn);
        }
       
        if($this->loginError($error)){
        	return false;
		}
        if (!$bind) {
        	   $GLOBALS['log']->warn("ldapauth.ldap_rdn_lookup: Could not bind with admin user, trying to bind anonymously");
            $bind = @ldap_bind($ldapconn);
             $error = ldap_errno($ldapconn);
       		
       		 if($this->loginError($error)){
        		return false;
			}
            if (!$bind) {
            		$GLOBALS['log']->warn("ldapauth.ldap_rdn_lookup: Could not bind anonymously, returning username");
            		return $user_name;
            }
        }

		// If we get here we were able to bind somehow
        $search_filter = "(" . $user_attr."=" . $user_name . ")";
        $GLOBALS['log']->info("ldapauth.ldap_rdn_lookup: Bind succeeded, searching for $user_attr=$user_name");
        $GLOBALS['log']->debug("ldapauth.ldap_rdn_lookup: base_dn:$base_dn , search_filter:$search_filter");
        
        $result = @ldap_search($ldapconn, $base_dn , $search_filter, array("dn", $bind_attr));
         $error = ldap_errno($ldapconn);
       	 if($this->loginError($error)){
        	return false;
		}
        $info = ldap_get_entries($ldapconn, $result);
         if($info['count'] == 0){
        	
        	return false;
        
        }
        ldap_unbind($ldapconn);
        
        $GLOBALS['log']->info("ldapauth.ldap_rdn_lookup: Search result:\nldapauth.ldap_rdn_lookup: " . count($info));
        
        if ($bind_attr == "dn") {
        		$found_bind_user = $info[0]['dn'];
        } else {
            	$found_bind_user = $info[0][strtolower($bind_attr)][0];
        }
        
        $GLOBALS['log']->info("ldapauth.ldap_rdn_lookup: found_bind_user=" . $found_bind_user);
        
        if (!empty($found_bind_user)) {
            return $found_bind_user;
        } elseif ($user_attr == $bind_attr) {
            return $user_name;
        } else {
            return false;
        }
    }
    

    
  
    
    
    
    

}

?>
