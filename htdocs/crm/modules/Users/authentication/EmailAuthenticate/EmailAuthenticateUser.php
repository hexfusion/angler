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

 // $Id: EmailAuthenticateUser.php,v 1.2 2006/08/22 20:56:11 awu Exp $

/**
 * This file is where the user authentication occurs. No redirection should happen in this file.
 *
 */
require_once('modules/Users/authentication/SugarAuthenticate/SugarAuthenticateUser.php');
class EmailAuthenticateUser extends SugarAuthenticateUser {
    var $passwordLength = 4;
    

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
       
        if(empty($_SESSION['lastUserId'])){
            $user_hash = SugarAuthenticate::encodePassword($password);
            $user_id = $this->authenticateUser($name, $user_hash);
            if(empty($user_id)) {
                $GLOBALS['log']->fatal('SECURITY: User authentication for '.$name.' failed');
                return false;
            }
        }
        
        if(empty($_SESSION['emailAuthToken'])){
            $_SESSION['lastUserId'] = $user_id;
            $_SESSION['lastUserName'] = $name;
            $_SESSION['emailAuthToken'] = '';
            for($i = 0; $i < $this->passwordLength; $i++){
                $_SESSION['emailAuthToken'] .= chr(mt_rand(48,90));
            }
            $_SESSION['emailAuthToken']  =  str_replace(array('<', '>'), array('#', '@'), $_SESSION['emailAuthToken']);
            $_SESSION['login_error'] = 'Please Enter Your User Name and Emailed Session Token';
            $this->sendEmailPassword($user_id, $_SESSION['emailAuthToken']);
            return false;
        }else{
            if(strcmp($name, $_SESSION['lastUserName']) == 0 && strcmp($password, $_SESSION['emailAuthToken']) == 0){
                $this->loadUserOnSession($_SESSION['lastUserId']);
                session_unregister('lastUserId');
                session_unregister('lastUserName');
                session_unregister('emailAuthToken');
                return true;
            }
             
        }
        
         $_SESSION['login_error'] = 'Please Enter Your User Name and Emailed Session Token';
        return false;
    }
    
    
    /**
     * Sends the users password to the email address or sends 
     *
     * @param unknown_type $user_id
     * @param unknown_type $password
     */
    function sendEmailPassword($user_id, $password){
	
	    $result = $GLOBALS['db']->query("SELECT email1, email2, first_name, last_name FROM users WHERE id='$user_id'");
	    $row = $GLOBALS['db']->fetchByAssoc($result);
	   
	    global $sugar_config;
	    if(empty($row['email1']) && empty($row['email2'])){
	       
	        $_SESSION['login_error'] = 'Please contact an administrator to setup up your email address associated to this account';
	       return;
	    }
	    
	    require_once("include/SugarPHPMailer.php");
		$notify_mail = new SugarPHPMailer();
		$notify_mail->CharSet = $sugar_config['default_charset'];
		$notify_mail->AddAddress(((!empty($row['email1']))?$row['email1']: $row['email2']), $row['first_name'] . ' ' . $row['last_name'] );
    
		if (empty($_SESSION['authenticated_user_language'])) {
			$current_language = $sugar_config['default_language'];
		}
		else {
			$current_language = $_SESSION['authenticated_user_language'];
		}
        $mail_settings = new Administration();
        $mail_settings->retrieveSettings('mail');
	

		        $notify_mail->Subject = 'Sugar Token';
				$notify_mail->Body = 'Your sugar session authentication token  is: ' . $password;
				if ($mail_settings->settings['mail_sendtype'] == "SMTP") {
					$notify_mail->Mailer = "smtp";
					$notify_mail->Host = $mail_settings->settings['mail_smtpserver'];
					$notify_mail->Port = $mail_settings->settings['mail_smtpport'];
					if ($mail_settings->settings['mail_smtpauth_req']) {
						$notify_mail->SMTPAuth = TRUE;
						$notify_mail->Username = $mail_settings->settings['mail_smtpuser'];
						$notify_mail->Password = $mail_settings->settings['mail_smtppass'];
					}
				}

				$notify_mail->From = 'no-reply@sugarcrm.com';
				$notify_mail->FromName = 'Sugar Authentication';

				if(!$notify_mail->Send()) {
					$GLOBALS['log']->warn("Notifications: error sending e-mail (method: {$notify_mail->Mailer}), (error: {$notify_mail->ErrorInfo})");
				}
				else {
					$GLOBALS['log']->info("Notifications: e-mail successfully sent");
				}
			
			
		
	}
    

}

?>
