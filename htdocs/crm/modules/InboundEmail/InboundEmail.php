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
 * $Id: InboundEmail.php,v 1.126 2006/08/30 23:07:50 chris Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

function this_callback($str) {
	foreach($str as $match) {
		$ret .= chr(hexdec(str_replace("%","",$match)));
	}
	return $ret;
}

class InboundEmail extends SugarBean {
	// module specific
	var $conn;
	
	// fields
	var $id;
	var $deleted;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;	
	var $name;
	var $status;
	var $server_url;
	var $email_user;
	var $email_password;
	var $port;
	var $service;
	var $mailbox;
	var $delete_seen;
	var $mailbox_type;
	var $template_id;
	var $stored_options;
	var $group_id;
    // default attributes
    var $transferEncoding              = array( 0 => '7BIT',
                                                1 => '8BIT',
                                                2 => 'BINARY',
                                                3 => 'BASE64',
                                                4 => 'QUOTED-PRINTABLE',
                                                5 => 'OTHER');
	// object attributes
	var $serverConnectString;
	var $disable_row_level_security	= true;
	var $InboundEmailCachePath		= 'cache/modules/InboundEmail';
	var $InboundEmailCacheFile		= 'InboundEmail.cache.php';
	var $object_name				= 'InboundEmail';
	var $module_dir					= 'InboundEmail';
	var $table_name					= 'inbound_email';
	var $new_schema					= true;
	var $process_save_dates 		= true;
	var $order_by;
	var $db;
	var $dbManager;
	var $field_defs;
	var $column_fields;
	var $required_fields			= array('name'			=> 'name',
											'server_url' 	=> 'server_url',
											'mailbox'		=> 'mailbox',
											'user'			=> 'user',
											'port'			=> 'port',);
	// custom ListView attributes
	var $mailbox_type_name;
	// service attributes
	var $tls;
	var $ca;
	var $ssl;
	var $protocol;

	/**
	 * Sole constructor
	 */
	function InboundEmail() {
		parent::SugarBean();
		if(function_exists("imap_timeout")) {
			/*
			 * 1: Open
			 * 2: Read
			 * 3: Write
			 * 4: Close
			 */
			imap_timeout(1, 60); 
			imap_timeout(2, 60);
			imap_timeout(3, 60); 
		}
	}
	
	/**
	 * retrieves I-E bean
	 * @param string id
	 * @return object Bean
	 */
	function retrieve($id) {
		$ret = parent::retrieve($id);
		$this->email_password = blowfishDecode(blowfishGetKey('InboundEmail'), $this->email_password);
		return $ret;
	}
	
	/**
	 * wraps SugarBean->save()
	 * @param string ID of saved bean
	 */
	function save($check_notify=false) {
		$this->email_password = blowfishEncode(blowfishGetKey('InboundEmail'), $this->email_password);
		return parent::save($check_notify);
	}

	/**
	 * soft deletes a User's personal inbox
	 * @param string id I-E id
	 * @param string user_name User name of User in focus, NOT current_user
	 * @return bool True on success
	 */
	function deletePersonalEmailAccount($id, $user_name) {
		$q = "SELECT ie.id FROM inbound_email ie LEFT JOIN users u ON ie.group_id = u.id WHERE u.user_name = '{$user_name}'";
		$r = $this->db->query($q);
		
		while($a = $this->db->fetchByAssoc($r)) {
			if(!empty($a) && $a['id'] == $id) {
				$this->retrieve($id);
				$this->deleted = 1;
				$this->save();
				return true;
			}
		}
		return false;
	}

	/**
	 * Saves Personal Inbox settings for Users
	 * @return boolean true on success, false on fail
	 */
	function savePersonalEmailAccount($userId = '', $userName = '') {
		global $current_user;
		
		if(!empty($userId)) {
			$groupId = $userId;
		} elseif(isset($_REQUEST['group_id'])) {
			$groupId = $_REQUEST['group_id'];
		} else { 
			return false;
		}
		
		if(isset($_REQUEST['ie_id']) && !empty($_REQUEST['ie_id'])) {
			$this->retrieve($_REQUEST['ie_id']);
		}
		if(!empty($_REQUEST['ie_name'])) {
			if(strpos($_REQUEST['ie_name'], 'personal.')) {
				$ie_name = $_REQUEST['ie_name'];
			} else {
				$ie_name = 'personal.'.$_REQUEST['ie_name'];
			}
		} elseif(!empty($userName)) {
			$ie_name = 'personal.'.$userName;				
		}
		$this->name = $ie_name;
		$this->group_id = $groupId;
		$this->status = $_REQUEST['ie_status'];
		$this->server_url = $_REQUEST['server_url'];
		$this->email_user = $_REQUEST['email_user'];
		$this->email_password = $_REQUEST['email_password'];
		$this->port = $_REQUEST['port'];
		$this->protocol = $_REQUEST['protocol'];
		$this->mailbox = $_REQUEST['mailbox'];
		$this->mailbox_type = 'pick'; // forcing this






		if(isset($_REQUEST['ssl']) && $_REQUEST['ssl'] == 1) { $useSsl = true; }
		else $useSsl = false;
		$this->service = '::::::::::';
		$id = $this->save(); // saving here to prevent user from having to re-enter all the info in case of error

		$this->retrieve($id);
		$this->protocol = $_REQUEST['protocol']; // need to set this again since we safe the "service" string to empty explode values
		$opts = $this->findOptimumSettings($useSsl);

		if(isset($opts['serial']) && !empty($opts['serial'])) {
			$this->service = $opts['serial'];
			if(isset($_REQUEST['mark_read']) && $_REQUEST['mark_read'] == 1) {
				$this->delete_seen = 0;
			} else {
				$this->delete_seen = 1;
			}
			// handle stored_options serialization
			if(isset($_REQUEST['only_since']) && $_REQUEST['only_since'] == 1) {
				$onlySince = true;
			} else {
				$onlySince = false;
			}
			$stored_options = array();
			$stored_options['from_name'] = $_REQUEST['mail_fromname'];
			$stored_options['from_addr'] = $_REQUEST['mail_fromaddress'];
			$stored_options['only_since'] = $onlySince;
			$stored_options['filter_domain'] = '';
			$this->stored_options = base64_encode(serialize($stored_options));
	
			$this->save();
			return true;

		} else {
			// could not find opts, no save
			$GLOBALS['log']->debug('-----> InboundEmail could not find optimums for User: '.$ie_name); 
			return false;
		}		
	}
	/** 
	 * Determines if this instance of I-E is for a Group Inbox or Personal Inbox
	 */
	function handleIsPersonal() {
		$qp = 'SELECT users.id, users.user_name FROM users WHERE users.is_group = 0 AND users.deleted = 0 AND users.status = \'active\' AND users.id = \''.$this->group_id.'\'';
		$rp = $this->db->query($qp);
		$personalBox = array();
		while($ap = $this->db->fetchByAssoc($rp)) {
			$personalBox[] = array($ap['id'], $ap['user_name']);
		}
		if(count($personalBox) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function getUserNameFromGroupId() {
		$r = $this->db->query('SELECT users.user_name FROM users WHERE deleted=0 AND id=\''.$this->group_id.'\'');
		while($a = $this->db->fetchByAssoc($r)) {
			return $a['user_name'];
		}
		return '';	
	}
	
	
	/**
	 * Programatically determines best-case settings for imap_open()
	 */
	function findOptimumSettings($useSsl=false, $user='', $pass='', $server='', $port='', $prot='', $mailbox='') {
		global $mod_strings;
		$serviceArr = array();
		$returnService = array();
		$badService = array();
		$goodService = array();
		$errorArr = array();
		$retArray = array(	'good' => $goodService,
							'bad' => $badService,
							'err' => $errorArr);
		
		if(!function_exists('imap_open')) {
			$retArray['err'][0] = $mod_strings['ERR_NO_IMAP'];
			return $retArray;
		}
		
		imap_errors(); // clearing error stack
		error_reporting(0); // turn off notices from IMAP
		
		if(isset($_REQUEST['ssl']) && $_REQUEST['ssl'] == 1) {
			$useSsl = true;
		}
		
		$exServ = explode('::', $this->service);
		$service = '/'.$exServ[1];
		
		$nonSsl = array('none'					=> '', // try default nothing
						'secure'				=> '/secure', // for POP3 servers that force CRAM-MD5
						'notls'					=> '/notls',
						'notls-secure'			=> '/notls/secure',
						'nocert'				=> '/novalidate-cert',
						'nocert-secure'			=> '/novalidate-cert/secure',
						'both'					=> '/notls/novalidate-cert',
						'both-secure'			=> '/notls/novalidate-cert/secure',
					);
		$ssl = array(	'ssl-none'				=> '/ssl',
						'ssl-secure'			=> '/ssl/secure',
						'ssl-notls'				=> '/ssl/notls',
						'ssl-notls-secure'		=> '/ssl/notls/secure',
						'ssl-nocert'			=> '/ssl/novalidate-cert',
						'ssl-nocert-secure'		=> '/ssl/novalidate-cert/secure',
						'ssl-both-off'			=> '/ssl/notls/novalidate-cert',
						'ssl-both-off-secure'	=> '/ssl/notls/novalidate-cert/secure',
						'ssl-tls'				=> '/ssl/tls',
						'ssl-tls-secure'		=> '/ssl/tls/secure',
						'ssl-cert'				=> '/ssl/validate-cert',
						'ssl-cert-secure'		=> '/ssl/validate-cert/secure',
						'ssl-both-on'			=> '/ssl/tls/validate-cert',
						'ssl-both-on-secure'	=> '/ssl/tls/validate-cert/secure',
					);
		
		if(isset($user) && !empty($user) && isset($pass) && !empty($pass)) {
			$this->email_password = $pass;
			$this->email_user = $user;
			$this->server_url = $server;
			$this->port = $port;
			$this->protocol = $prot;
			$this->mailbox = $mailbox;
		}
		
		// in case we flip from IMAP to POP3
		if($this->protocol == 'pop3') $this->mailbox = 'INBOX';

		if($useSsl == true) {
			//$ssl = array_merge($ssl, $nonSsl);
			foreach($ssl as $k => $service) {
				$returnService[$k] = 'foo'.$service;
				$serviceArr[$k] = '{'.$this->server_url.':'.$this->port.'/service='.$this->protocol.$service.'}'.$this->mailbox;
			}
			
		} else {
			foreach($nonSsl as $k => $service) {
				$returnService[$k] = 'foo'.$service;
				$serviceArr[$k] = '{'.$this->server_url.':'.$this->port.'/service='.$this->protocol.$service.'}'.$this->mailbox;
			}
		}
		
		$GLOBALS['log']->debug('---------------STARTING FINDOPTIMUMS LOOP----------------');
		$l = 1;
        
        $login = $this->email_user;
        $passw = $this->email_password;
        
		foreach($serviceArr as $k => $serviceTest) {
			$errors = '';
			$alerts = '';
			$GLOBALS['log']->debug($l.': I-E testing string: '.$serviceTest);
			
			// open the connection and try the test string
			$this->conn = imap_open($serviceTest, $login, $passw);

			if(($errors = imap_last_error()) || ($alerts = imap_alerts())) {
				if($errors == 'Too many login failures' || $errors == '[CLOSED] IMAP connection broken (server response)') { // login failure means don't bother trying the rest
					$GLOBALS['log']->debug($l.': I-E failed using ['.$serviceTest.']');
					$retArray['err'][$k] = $mod_strings['ERR_BAD_LOGIN_PASSWORD'];
					$retArray['bad'][$k] = $serviceTest;
					$GLOBALS['log']->fatal($l.': I-E ERROR: $ie->findOptimums() failed due to bad user credentials for user login: '.$this->email_user);



					return $retArray;
				} elseif($errors == 'Mailbox is empty') { // false positive
					$GLOBALS['log']->debug($l.': I-E found good connect, but empty mailbox using ['.$serviceTest.']');
					$retArray['good'][$k] = $returnService[$k];
				} else {
					$GLOBALS['log']->debug($l.': I-E failed using ['.$serviceTest.'] - error: '.$errors);
					$retArray['err'][$k] = $errors;
					$retArray['bad'][$k] = $serviceTest;
				}
			} else {
				$GLOBALS['log']->debug($l.': I-E found good connect using ['.$serviceTest.']');
				$retArray['good'][$k] = $returnService[$k];
			}
			
			if(is_resource($this->conn)) {
				if(!imap_close($this->conn)) $GLOBALS['log']->fatal('imap_close() failed!'); 
			}
			
			$GLOBALS['log']->debug($l.': I-E clearing error and alert stacks.');
			imap_errors(); // clear stacks
			imap_alerts();
			$l++;
		}
		$GLOBALS['log']->debug('---------------end FINDOPTIMUMS LOOP----------------');
		//_pp($goodService); _pp($errorArr); _pp($badService);

		if(!empty($retArray['good'])) {
			$newTls				= '';
			$newCert			= '';
			$newSsl				= '';
			$newNotls			= '';
			$newNovalidate_cert	= '';
			$good = array_pop($retArray['good']); // get most complete string
			$exGood = explode('/', $good);
			foreach($exGood as $v) {
				switch($v) {
					case 'ssl':
						$newSsl = 'ssl';
					break;	
					case 'tls':
						$newTls = 'tls';
					break;	
					case 'notls':
						$newNotls = 'notls';
					break;	
					case 'cert':
						$newCert = 'validate-cert';
					break;	
					case 'novalidate-cert':
						$newNovalidate_cert = 'novalidate-cert';
					break;	
					case 'secure':
						$secure = 'secure';
					break;
				}	
			}
			// $tls.'::'.$cert.'::'.$ssl.'::'.$protocol.'::'.$novalidate_cert.'::'.$notls;
			$goodStr['serial'] = $newTls.'::'.$newCert.'::'.$newSsl.'::'.$this->protocol.'::'.$newNovalidate_cert.'::'.$newNotls.'::'.$secure;
			$goodStr['service'] = $good;
			
			return $goodStr;
		} else {
			return false;
		}
	}

	
	/**
	 * Checks for duplicate Group User names when creating a new one at save()
	 * @return	GUID		returns GUID of Group User if user_name match is
	 * found
	 * @return	boolean		false if NO DUPE IS FOUND
	 */
	function groupUserDupeCheck() {
		$q = "SELECT u.id FROM users u WHERE u.deleted=0 AND u.is_group=1 AND u.user_name = '".$this->name."'";
		$r = $this->db->query($q);
		$uid = '';
		while($a = $this->db->fetchByAssoc($r)) {
			$uid = $a['id'];
		}
			 
		if(strlen($uid) > 0) {
			return $uid;
		} else {
			return false;
		}
	}
	
	function getGroupsWithSelectOptions() {
		$r = $this->db->query('SELECT id, user_name FROM users WHERE users.is_group = 1 AND deleted = 0');
		if(is_resource($r)) {
			$groupKeys = array();
			$groupVals = array();
			while($a = $this->db->fetchByAssoc($r)) {
				$groupKeys[$a['id']] = $a['user_name'];
			}
			
			$selectOptions = get_select_options_with_id_separate_key($groupKeys, $groupKeys, $this->group_id);
			return $selectOptions;
		} else {
			return false;
		}
	}
	
	/**
	 * handles auto-responses to inbound emails
	 * 
	 * @param object email Email passed as reference
	 */
	function handleAutoresponse(&$email, &$contactAddr) {
		if($this->template_id) {
			$GLOBALS['log']->debug('found auto-reply template id - prefilling and mailing response');
			
			if($this->getAutoreplyStatus($contactAddr) 
			&& $this->checkOutOfOffice($email->name) 
			&& $this->checkFilterDomain($email)) { // if we haven't sent this guy 10 replies in 24hours
			
				if(!empty($this->stored_options)) {
					$storedOptions = unserialize(base64_decode($this->stored_options));
				}
				// get FROM NAME
				if(!empty($storedOptions['from_name'])) {
					$from_name = $storedOptions['from_name'];
					$GLOBALS['log']->debug('got from_name from storedOptions: '.$from_name);
				} else { // use system default
					$rName = $this->db->query('SELECT value FROM config WHERE name = \'fromname\'');
					if(is_resource($rName)) {
						$aName = $this->db->fetchByAssoc($rName);
					}
					if(!empty($aName['value'])) {
						$from_name = $aName['value'];
					} else {
						$from_name = '';
					} 
				}
				// get FROM ADDRESS
				if(!empty($storedOptions['from_addr'])) {
					$from_addr = $storedOptions['from_addr'];
				} else {
					$rAddr = $this->db->query('SELECT value FROM config WHERE name = \'fromaddress\'');
					if(is_resource($rAddr)) {
						$aAddr = $this->db->fetchByAssoc($rAddr);
					}
					if(!empty($aAddr['value'])) {
						$from_addr = $aAddr['value'];
					} else {
						$from_addr = '';
					} 
				}
				
				// handle to: address, prefer reply-to
				if(!empty($email->reply_to_email)) {
					$to[0]['email'] = $email->reply_to_email;
				} else {
					$to[0]['email'] = $email->from_addr;
				}
				// handle to name: address, prefer reply-to
				if(!empty($email->reply_to_name)) {
					$to[0]['display'] = $email->reply_to_name;
				} elseif(!empty($email->from_name)) {
					$to[0]['display'] = $email->from_name;
				} 
				
				if(!class_exists('EmailTemplate')) {
					require_once('modules/EmailTemplates/EmailTemplate.php');
				}
				$et = new EmailTemplate();
				$et->retrieve($this->template_id);
				if(empty($et->subject))		{ $et->subject = ''; }
				if(empty($et->body))		{ $et->body = ''; }
				if(empty($et->body_html))	{ $et->body_html = ''; }
				
				$reply = new Email();
				$reply->type				= 'out';
				$reply->to_addrs			= $to[0]['email'];
				$reply->to_addrs_arr		= $to;
				$reply->cc_addrs_arr		= array();
				$reply->bcc_addrs_arr		= array();
				$reply->from_name			= $from_name;
				$reply->from_addr			= $from_addr;
				$reply->name				= $et->subject;
				$reply->description			= $et->body;
				$reply->description_html	= $et->body_html;
				
				$GLOBALS['log']->debug('saving and sending auto-reply email');
				//$reply->save(); // don't save the actual email.
				$reply->send();
				$this->setAutoreplyStatus($contactAddr);
			} else {
				$GLOBALS['log']->debug('InboundEmail: auto-reply threshold reached for email ('.$contactAddr.') - not sending auto-reply');
			}
		}
	}	
	
	
	/**
	 * handles functionality specific to the Mailbox type (Cases, bounced
	 * campaigns, etc.)
	 * 
	 * @param object email Email object passed as a reference
	 * @param object header Header object generated by imap_headerinfo();
	 */
	function handleMailboxType(&$email, &$header) {
		switch($this->mailbox_type) {
			case 'support':
				if(!class_exists('aCase')) {
					require_once('modules/Cases/Case.php');
				}
				$c = new aCase();
				
				$GLOBALS['log']->debug('looking for a case for '.$email->name);
				
				if($caseId = $this->getCaseIdFromCaseNumber($email->name, $c)) {



					$c->retrieve($caseId);
					$c->load_relationship('emails');
					$c->emails->add($email->id);

					$email->retrieve($email->id);
					$email->parent_type = "Cases";
					$email->parent_id = $caseId;
					$email->save();
					$GLOBALS['log']->debug('InboundEmail found exactly 1 match for a case: '.$c->name);
				}
				break;
			case 'bug':
			
				break;
				
			case 'info':
				// do something with this?
				break;
			case 'sales':
				// do something with leads? we don't have an email_leads table
				break;
			case 'task':
				// do something?
				break;
			case 'bounce':
				require_once('modules/Campaigns/ProcessBouncedEmails.php');
				campaign_process_bounced_emails($email, $header);
				break;
			
			case 'pick': // do all except bounce handling
				require_once('modules/Cases/Case.php');
				$c = new aCase();
				
				$GLOBALS['log']->debug('looking for a case for '.$email->name);
				
				if($caseId = $this->getCaseIdFromCaseNumber($email->name, $c)) {



					$c->retrieve($caseId);
					$c->load_relationship('emails');
					$c->emails->add($email->id);

					$email->retrieve($email->id);
					$email->parent_type = "Cases";
					$email->parent_id = $caseId;
					$email->save();
					$GLOBALS['log']->debug('InboundEmail found exactly 1 match for a case: '.$c->name);
				}

				break;
		}
	}
	
	/**
	 * handles linking contacts, accounts, etc. to an email
	 * 
	 * @param object Email bean to be linked against
	 * @return string contactAddr is the email address of the sender
	 */
	function handleLinking(&$email) {
		// link email to an User if emails match TO addr
		if($userIds = $this->getRelatedId($email->to_addrs, 'users')) {
			// link the user to the email
			$email->load_relationship('users');
			$email->users->add($userIds);
		}
		
		// link email to a Contact, Lead, or Account if the emails match
		// give precedence to REPLY-TO above FROM 
		if(!empty($email->reply_to_email)) {
			$contactAddr = $email->reply_to_email;
		} else {
			$contactAddr = $email->from_addr;
		}

		if($leadIds = $this->getRelatedId($contactAddr, 'leads')) {
			$email->load_relationship('leads');
			$email->leads->add($leadIds);
			
			if(!class_exists('Lead')) {
				require_once('modules/Leads/Lead.php');
			}
			foreach($leadIds as $leadId) {
				$lead = new Lead();
				$lead->retrieve($leadId);
				$lead->load_relationship('emails');
				$lead->emails->add($email->id);
			}
		}

		if($contactIds = $this->getRelatedId($contactAddr, 'contacts')) {
			// link the contact to the email
			$email->load_relationship('contacts');
			$email->contacts->add($contactIds);
		}

		if($accountIds = $this->getRelatedId($contactAddr, 'accounts')) {
			// link the account to the email
			$email->load_relationship('accounts');
			$email->accounts->add($accountIds);
			
			if(!class_exists('Account')) {
				require_once('modules/Accounts/Account.php');
			}
			foreach($accountIds as $accountId) {
				$acct = new Account();
				$acct->retrieve($accountId);
				$acct->load_relationship('emails');
				$acct->account_emails->add($email->id);
			}
		}
		
		return $contactAddr;
	}
	/**
	 * takes a breadcrumb and returns the encoding at that level
	 * @param	string bc the breadcrumb string in format (1.1.1)
	 * @param	array parts the root level parts array
	 * @return	int retInt Int key to transfer encoding (see handleTranserEncoding())
	 */
	function getEncodingFromBreadCrumb($bc, $parts) {
		if(strstr($bc,'.')) {
			$exBc = explode('.', $bc);
		} else {
			$exBc[0] = $bc;
		}
		
		$depth = count($exBc);

		for($i=0; $i<$depth; $i++) {
			$tempObj[$i] = $parts[($exBc[$i]-1)];
			$retInt = imap_utf8($tempObj[$i]->encoding);
			if(!empty($tempObj[$i]->parts)) {
				$parts = $tempObj[$i]->parts;
			}
		}
		return $retInt;
	}

    /**
     * retrieves the charset for a given part of an email body
     * 
     * @param string bc target part of the message in format (1.1.1)
     * @param array parts 1 level above ROOT array of Objects representing a multipart body
     * @return string charset name
     */
    function getCharsetFromBreadCrumb($bc, $parts) {
        if(strstr($bc,'.')) {
            $exBc = explode('.', $bc);
        } else {
            $exBc[0] = $bc;
        }
        
        foreach($exBc as $crumb) {
        	$tempObj = $parts[$crumb-1];
        	if(is_array($tempObj->parts)) {
        		$parts = $tempObj->parts;
        	}
        }
        
        // now we have the tempObj at the end of the breadCrumb trail
        //$GLOBALS['log']->fatal(print_r(debug_backtrace(), true));
        
        if($tempObj->ifparameters) {
        	foreach($tempObj->parameters as $param) {
        		if($param->attribute == 'charset') {
        			return $param->value;
        		}
        	}
        }
        
        return 'default';
    }

	/**
	 * returns the HTML text part of a multi-part message
     * 
	 * @param int msgNo the relative message number for the monitored mailbox
	 * @param string $type the type of text processed, either 'PLAIN' or 'HTML'
     * @return string UTF-8 encoded version of the requested message text
	 */
	function getMessageText($msgNo, $type, $structure, $fullHeader) {
		$msgPart = '';
		$bc = $this->buildBreadCrumbs($structure->parts, $type);

		if(!empty($bc)) { // multi-part
            $msgPartRaw = imap_fetchbody($this->conn, $msgNo, $bc);
            $enc = $this->getEncodingFromBreadCrumb($bc, $structure->parts);
            $charset = $this->getCharsetFromBreadCrumb($bc, $structure->parts);
            $msgPart = $this->handleTranserEncoding($msgPartRaw, $enc);
            $msgPart = $this->handleCharsetTranslation($msgPart, $charset);

			/*
			_pp('bc: '.$bc);
			_pp("enc: ".$enc);
			_pp("charset: ".$charset);
			_pp("msgPart: ".$msgPart);
			_pp('xfer-encoding: '.$this->transfer_encoding);
			_ppd($structure->parts);
			*/
			
			return $msgPart;
		} else { // either PLAIN message type (flowed) or b0rk3d RFC
			// make sure we're working on valid data here.
			if($structure->subtype != $type) {
				return '';
			}
		
			$decodedHeader = $this->decodeHeader($fullHeader);
			
			//_pp($fullHeader);
			//_ppd($decodedHeader);
			
			// now get actual body contents
			$text = imap_body($this->conn, $msgNo);
			
			// handle transfer encoding (usually mb-char for text portions)
			if(isset($decodedHeader['Content-Transfer-Encoding'])) {
				$flip = array_flip($this->transferEncoding);
				$text = $this->handleTranserEncoding($text, $flip[strtoupper($decodedHeader['Content-Transfer-Encoding'])]);
			}
			$msgPart = $this->handleCharsetTranslation($text, $decodedHeader['Content-Type']['charset']);
			//_ppl($msgPart);
			return $msgPart;
		} // end else clause
	}
	
	/**
	 * decodes raw header information and passes back an associative array with
	 * the important elements key'd by name
	 * @param header string the raw header
	 * @return decodedHeader array the associative array
	 */
	function decodeHeader($fullHeader) {
		$decodedHeader = array();
		$exHeaders = explode("\r", $fullHeader);
		$quotes = array('"', "'");

		foreach($exHeaders as $lineNum => $head) {
			$key 	= '';
			$key	= trim(substr($head, 0, strpos($head, ':')));
			$value	= '';
			$value	= trim(substr($head, (strpos($head, ':') + 1), strlen($head)));
			
			// handle content-type section in headers
			if($key == 'Content-Type' && strpos($value, ';')) { // ";" means something follows related to (such as Charset)
				/* 	Outlook can't fucking follow RFC if someone PAID them to do it... 
					oh wait, someone paid them NOT to do it. */
				$semiColPos = mb_strpos($value, ';');
				$strLenVal = mb_strlen($value);
				if(($semiColPos + 4) >= $strLenVal) {
					// the charset="[something]" is on the next line
					$value .= str_replace($quotes, "", trim($exHeaders[$lineNum+1]));
				}
			
				$newValue = array();
				$exValue = explode(';', $value);
				$newValue['type'] = $exValue[0];
				
				for($i=1; $i<count($exValue); $i++) {
					$exContent = explode('=', $exValue[$i]);
					$newValue[trim($exContent[0])] = trim($exContent[1]);
				}
				$value = $newValue;
			}
			
			if(!empty($key) && !empty($value)) {
				$decodedHeader[$key] = $value;
			}
		}
		
		// cn: bug 3332 and others probably
		
//		if(isset($decodedHeader['Content-Type']) && ($decodedHeader['Content-Type']['charset'] == '') {
//			 
//			 /*	
//			 
//			reset($exHeaders);
//			$bad = array('"', "'");
//			foreach($exHeaders as $line) {
//				$line = trim($line);
//				if(strpos($line, 'charset=') !== false) {
//					$line = str_replace('charset=', "", $line);
//					$line = str_replace($bad, "#", $line);
//					$match = preg_split('/#/', $line, -1, PREG_SPLIT_NO_EMPTY);
//				}
//			}
//			$decodedHeader['Content-Type']['charset'] = $match[0];
//		}
		return $decodedHeader;
	}
	
    /**
     * handles translating message text from orignal encoding into UTF-8
     * 
     * @param string text test to be re-encoded
     * @param string charset original character set
     * @return string utf8 re-encoded text
	 */
	function handleCharsetTranslation($text, $charset) {
		if(empty($charset)) {
			$GLOBALS['log']->fatal("***ERROR: InboundEmail::handleCharsetTranslation() called without a \$charset!");
			$GLOBALS['log']->fatal("***STACKTRACE: ".print_r(debug_backtrace(), true));
			return $text;
		}
		
		// typical headers have no charset - let destination pick (since it's all ASCII anyways)
		if($charset == 'default' || $charset == 'UTF-8') {
			return $text;
		}
		
        if(function_exists('mb_convert_encoding')) {
        	$GLOBALS['log']->debug('handleCharsetTranslation is using [ mb_convert_encoding() ] to translate encoding to UTF-8 from '.$charset);
            return mb_convert_encoding($text, 'UTF-8', $charset);
        } elseif(function_exists('iconv')) {  // oh please, oh please, oh please!
        	$GLOBALS['log']->debug('handleCharsetTranslation is using [ iconv() ] to translate encoding.');
            return iconv($charset, 'UTF-8', $text);
        } else {
        	$GLOBALS['log']->debug('handleCharsetTranslation is using [ SugarPHP Code ] to translate encoding.');
        	
        	return $text;
        }
    }
    
    
    
	/**
	 * Builds up the "breadcrumb" trail that imap_fetchbody() uses to return
	 * parts of an email message, including attachments and inline images
	 * @param	$parts	array of objects
	 * @param	$subtype	what type of trail to return? HTML? Plain? binaries?
	 * @param	$breadcrumb	text trail to build up
	 */
	function buildBreadCrumbs($parts, $subtype, $breadcrumb = '0') {
		//_pp('buildBreadCrumbs building for '.$subtype.' with BC at '.$breadcrumb);
		// loop through available parts in the array
		foreach($parts as $k => $part) {
			// mark passage through level
			$thisBc = ($k+1);
			// if this is not the first time through, start building the map
			if($breadcrumb != 0) {
				$thisBc = $breadcrumb.'.'.$thisBc;
			} 
			
			// found a multi-part/mixed 'part' - keep digging
			if($part->type == 1 && ($part->subtype == 'ALTERNATIVE' || $part->subtype == 'MIXED')) {
				//_pp('in loop: going deeper with subtype: '.$part->subtype.' $k is: '.$k);
				$thisBc = $this->buildBreadCrumbs($part->parts, $subtype, $thisBc);
				return $thisBc;
				
			} elseif($part->subtype == $subtype) { // found the subtype we want, return the breadcrumb value
				//_pp('found '.$subtype.' bc! returning: '.$thisBc);
				return $thisBc;
			} else {
				//_pp('found '.$part->subtype.' instead');
			}
		}
	}
	
	/** 
	 * Takes a PHP imap_* object's to/from/cc/bcc address field and converts it
	 * to a standard string that SugarCRM expects
	 * @param	$arr	an array of email address objects
	 */
	function convertImapToSugarEmailAddress($arr) {
		if(is_array($arr)) {
			$addr = '';
			foreach($arr as $key => $obj) {
				$addr .= $obj->mailbox.'@'.$obj->host.', ';
			}
			// strip last comma
			return substr_replace($addr,'',-2,-1);
		}
	}

	/**
	 * tries to figure out what character set a given filename is using and
	 * decode based on that
	 * 
	 * @param string name Name of attachment
	 * @return string decoded name
	 */
	function handleEncodedFilename($name) {
		$imapDecode = imap_mime_header_decode($name);
		/******************************
	    $imapDecode => stdClass Object
	        (
	            [charset] => utf-8
	            [text] => wählen.php
	        )
	        		
	        		OR
	        		
		$imapDecode => stdClass Object
	        (
	            [charset] => default
	            [text] => UTF-8''%E3%83%8F%E3%82%99%E3%82%A4%E3%82%AA%E3%82%AF%E3%82%99%E3%83%A9%E3%83%95%E3%82%A3%E3%83%BC.txt
	        )
		*******************************/
		if($imapDecode[0]->charset != 'default') { // mime-header encoded charset
			$encoding = $imapDecode[0]->charset;
			$name = $imapDecode[0]->text; // encoded in that charset
		} else {
			/* encoded filenames are formatted as [encoding]''[filename] */
			if(strpos($name, "''") !== false) {
				
				$encoding = substr($name, 0, strpos($name, "'"));

				while(strpos($name, "'") !== false) {
					$name = trim(substr($name, (strpos($name, "'")+1), strlen($name)));	
				}
			}
			$name = urldecode($name);
		}

		return (strtolower($encoding) == 'utf-8') ? $name : mb_convert_encoding($name, 'UTF-8', $encoding);  
	}
	
	/** 
	 * Takes the "parts" attribute of the object that imap_fetchbody() method
	 * returns, and recursively goes through looking for objects that have a
	 * disposition of "attachement" or "inline"
	 * @param	$msgNo	the relative message number for the monitored mailbox
	 * @param	$parts	array of objects to examine
	 * @param	$emailId	the GUID of the email saved prior to calling this method
	 * @param	$breadcrumb	build up of the parts mapping
	 */
	function saveAttachments(&$msgNo, &$parts, &$emailId, $breadcrumb='0') {
		global $sugar_config;
		if(!class_exists('Note')) {
			require_once('modules/Notes/Note.php');
		}
		
		foreach($parts as $k => $part) {
			$thisBc = $k+1;
			if($breadcrumb != '0') {
				$thisBc = $breadcrumb.'.'.$thisBc;
			}
			
			// check if we need to recurse into the object
			if($part->type == 1 && !empty($part->parts)) {
				$this->saveAttachments($msgNo,$part->parts,$emailId,$thisBc);
			} elseif($part->ifdisposition) {
				// we will take either 'attachments' or 'inline'
				if(strtolower($part->disposition) == 'attachment' || strtolower($part->disposition) == 'inline') {
					$attach = new Note();
					$attach->parent_id = $emailId;
					$attach->parent_type = 'Emails';
					
					$fname = $this->handleEncodedFilename($part->dparameters[0]->value);
					
					
					if(!empty($fname)) {//assign name to attachment
						$attach->name = $fname;
					} else {//if name is empty, default to filename
						$attach->name = urlencode($part->dparameters[0]->value);
					}
					
					$attach->filename = urlencode($attach->name);
		
					// deal with the MIME types email has
					switch($part->type) {
						case 0:// text file
							$attach->file_mime_type = 'text/'.$part->subtype;
							break;
						case 1:// multipart
							$attach->file_mime_type = 'multipart/'.$part->subtype;
							break;
						case 2:// message
							$attach->file_mime_type = 'message/'.$part->subtype;
							break;
						case 3:// application
							$attach->file_mime_type = 'application/'.$part->subtype;
							break;
						case 4:// audio
							$attach->file_mime_type = 'audio/'.$part->subtype;
							break;
						case 5:// image
							$attach->file_mime_type = 'image/'.$part->subtype;
							break;
						case 6:// video
							$attach->file_mime_type = 'video/'.$part->subtype;
							break;
						case 7:// other
							$attach->file_mime_type = 'other/'.$part->subtype;
							break;
						default:
							break;
					}
					//get position of last "." in file name
					$file_ext_beg = strrpos($attach->filename,".");
					$file_ext = "";
					//get file extension
					if($file_ext_beg >0){
						$file_ext = substr($attach->filename, $file_ext_beg+1 );
					}
					//check to see if this is a file with extension located in "badext"
					foreach($sugar_config['upload_badext'] as $badExt) {
		            	if(strtolower($file_ext) == strtolower($badExt)) {
			            	//if found, then append with .txt and break out of lookup
			                $attach->name = $attach->name . ".txt";
			                $attach->file_mime_type = 'text/';
			                $attach->filename = $attach->filename . ".txt";
			                break; // no need to look for more
		            	}
	        		}
					$attach->save();
					
					// deal with attachment encoding and decode the text string
					$msgPartRaw = imap_fetchbody($this->conn, $msgNo, $thisBc);
					$msgPart = $this->handleTranserEncoding($msgPartRaw, $part->encoding);
					if($fp = fopen($sugar_config['upload_dir'].$attach->id.$attach->filename, 'wb')) {
						if(fwrite($fp, $msgPart)) {
							$GLOBALS['log']->debug('InboundEmail saved attachment file: '.$attach->filename);	
						} else {
							$GLOBALS['log']->fatal('InboundEmail could not create attachment file: '.$attach->filename);
						}
						fclose($fp);
					} else {
						$GLOBALS['log']->fatal('InboundEmail could not open a filepointer to: '.$sugar_config['upload_dir'].$attach->filename);
					}
				} // end if disposition type 'attachment'
			} // end ifdisposition
		} // end foreach
	}
	
	/**
	 * decodes a string based on its associated encoding
	 * if nothing is passed, we default to no-encoding type
	 * @param	$str	encoded string
	 * @param	$enc	detected encoding
	 */
	function handleTranserEncoding($str, $enc=0) {
		switch($enc) {
			case 2:// BINARY
				$ret = $str;
				break;
			case 3:// BASE64
				$ret = base64_decode($str);
				break;
			case 4:// QUOTED-PRINTABLE
				$ret = quoted_printable_decode($str);
				break;
			case 0:// 7BIT or 8BIT
			case 1:// already in a string-useable format - do nothing
			case 5:// OTHER
			default:// catch all
				$ret = $str;
				break;
		}
		
		return $ret;
	}


	/**
	 * Some emails do not get assigned a message_id, specifically from
	 * Outlook/Exchange.
	 * 
	 * We need to derive a reliable one for duplicate import checking.
	 */	
	function getMessageId($header) {
		$message_id = md5(print_r($header, true));
		return $message_id;
	}
	
	/**
	 * checks for duplicate emails on polling
	 * 
	 * @param string message_id message ID generated by sending server
	 * @param int message number (mailserver's key) of email
	 * @param object header object generated by imap_headerinfo()
	 * @return bool
	 */
	function importDupeCheck($message_id, $msgNo, $header) {
		$GLOBALS['log']->debug('*********** InboundEmail doing dupe check.');
		
		if(empty($message_id) && !isset($message_id)) {
			$GLOBALS['log']->debug('*********** NO MESSAGE_ID.');
			$message_id = $this->getMessageId($header);
		}
		
		$query = 'SELECT count(emails.id) AS c FROM emails WHERE emails.message_id = \''.$message_id.'\' AND deleted = 0';
		$r = $this->db->query($query);
		$a = $this->db->fetchByAssoc($r);
		
		if($a['c'] > 0) {
			$GLOBALS['log']->debug('InboundEmail found a duplicate email with ID ('.$message_id.')');
			return false; // we have a dupe and don't want to import the email'
		} else {
			return true;
		}
	}

    
    /**
     * gets raw message with headers
     * @param int message number (mailserver's key) of email
     * @return string raw email (header + body) or empty string on fail
     */
    function importRaw($msgNo) {
    	global $mod_strings;
    	
        $header = imap_fetchheader($this->conn, $msgNo);
        $body = utf8_encode(imap_body($this->conn, $msgNo));

        $strlenAll = function_exists('mb_strlen') ? mb_strlen($body.$header, 'latin1') : strlen($body.$header);
        $strlenMod = function_exists('mb_strlen') ? mb_strlen($mod_strings['ERR_BODY_TOO_LONG'], 'latin1') : strlen($mod_strings['ERR_BODY_TOO_LONG']);

        if($strlenAll <= 65535) {
        	$raw = $header.$body;
        } else {
        	ini_set('mbstring.func_overload', 0);
        	$oops = $header.$body;
        	$messLength = 65534 - $strlenMod;
        	$raw = function_exists('mb_substr') ? mb_substr($oops, 0, $messLength) : substr($oops, 0, $messLength);
        	$raw .= $mod_strings['ERR_BODY_TOO_LONG'];
        	ini_set('mbstring.func_overload', 7);
        }
        
        return $raw;
    }
    
    /**
     * takes the output from imap_mime_hader_decode() and handles multiple types of encoding
     * @param string subject Raw subject string from email
     * @return string ret properly formatted UTF-8 string
     */
    function handleMimeHeaderDecode($subject) {
		$subjectDecoded = imap_mime_header_decode($subject);
		
		$ret = '';
		foreach($subjectDecoded as $object) {
			if($object->charset != 'default') {
				$ret .= $this->handleCharsetTranslation($object->text, $object->charset);
			} else {
				$ret .= $object->text;
			}
		}
		return $ret;
    }

    /**
     * shiny new importOneEmail() method
     * @param msgNo int
     */
	function importOneEmail($msgNo) {
        $GLOBALS['log']->debug('InboundEmail processing 1 email-----------------------------------------------------------------------------------------'); 

        global $app_list_strings;
        global $sugar_config;
        global $current_user;
        
        $header = imap_headerinfo($this->conn, $msgNo);
        ///////////////////////////////////////////////////////////////////////
        ////    DUPLICATE CHECK
        if($this->importDupeCheck($header->message_id, $msgNo, $header)) {
            $GLOBALS['log']->debug('*********** NO duplicate found, continuing with processing.');

            $structure = imap_fetchstructure($this->conn, $msgNo); // map of email
            $fullHeader = imap_fetchheader($this->conn, $msgNo); // raw headers
            
            ///////////////////////////////////////////////////////////////////
            ////    CREATE SEED EMAIL OBJECT
            if(!class_exists('Email')) {
                require_once('modules/Emails/Email.php');
            }
            $email = new Email();
            $email->mailbox_id = $this->id;
            $message = array();
            $email->id = create_guid();
            $email->new_with_id = true; //forcing a GUID here to prevent double saves. 
            ////    END CREATE SEED EMAIL
            ///////////////////////////////////////////////////////////////////
            
            ///////////////////////////////////////////////////////////////////
            ////    GET RAW EMAIL
            $GLOBALS['log']->debug('*********** Importing RAW email.');
            $raw = $this->importRaw($msgNo);
            $email->raw_source = $raw;
            ////    END GET RAW EMAIL
            ///////////////////////////////////////////////////////////////////

            ///////////////////////////////////////////////////////////////////////
            ////    ASSIGN APPROPRIATE ATTRIBUTES TO NEW EMAIL OBJECT
                if(!empty($header->date)) {
                    $headerDate = (isset($header->date) && !empty($header->date)) ? $header->date : $header->Date;
                    // need to hack PHP/windows' bad handling of strings when using POP3
                    if(strstr($headerDate,'+0000 GMT')) { 
                        $headerDate = str_replace('GMT','', $headerDate);   
                    } elseif(!strtotime($headerDate)) {
                        $headerDate = 'now'; // catch non-standard format times.    
                    }
                } else {
                    $headerDate = 'now';
                }

				$unixHeaderDate = strtotime($headerDate);
				if(strtotime('Jan 1, 2001') > $unixHeaderDate) {
				        $unixHeaderDate = strtotime('now');
				}
                
                // I-E runs as admin, get admin prefs
                if(empty($current_user)) {
                    require_once('modules/Users/User.php');
                    $current_user = new User();
                    $current_user->getSystemUser();
                }
                $tPref                  = $current_user->getUserDateTimePreferences($current_user);
                // handle UTF-8/charset encoding in the ***headers***
                $email->name            = $this->handleMimeHeaderDecode($header->subject);//handleCharsetTranslation($subjectDecoded[0]->text, $subjectDecoded[0]->charset);
                $email->date_start      = date($tPref['date'], $unixHeaderDate);
                $email->time_start      = date($tPref['time'], $unixHeaderDate);
                $email->type            = 'inbound';
                $email->date_created    = date($tPref['date']." ".$tPref['time'], $unixHeaderDate);
                $email->status          = 'unread'; // this is used in Contacts' Emails SubPanel
                if(!empty($header->toaddress)) {
                    $email->to_name     = $this->handleTranserEncoding($this->handleMimeHeaderDecode($header->toaddress), $structure->encoding);
                }
                if(!empty($header->to)) {
                    $email->to_addrs    = $this->convertImapToSugarEmailAddress($header->to);
                }
                $email->from_name       = $this->handleTranserEncoding($this->handleMimeHeaderDecode($header->fromaddress), $structure->encoding);
                $email->from_addr       = $this->convertImapToSugarEmailAddress($header->from);
                if(!empty($header->cc)) {
                    $email->cc_addrs    = $this->convertImapToSugarEmailAddress($header->cc);
                }
                $email->reply_to_name   = $this->handleTranserEncoding($this->handleMimeHeaderDecode($header->reply_toaddress), $structure->encoding);
                $email->reply_to_email  = $this->convertImapToSugarEmailAddress($header->reply_to);
                if(!empty($header->message_id) && isset($header->message_id)) { // POP3 doesn't return this value
                    $email->message_id  = $header->message_id;
                    $messageId          = $email->message_id;
                } else {
                    $email->message_id  = $this->getMessageId($header); // generate one for Outlook emails
                    $messageId          = $this->getMessageId($header); // generate one for Outlook emails
                }
                $email->intent          = $this->mailbox_type;
    
                // handle multi-part email bodies
                $email->description         = $this->getMessageText($msgNo, 'PLAIN', $structure, $fullHeader); // runs through handleTranserEncoding() already
                $email->description_html    = $this->getMessageText($msgNo, 'HTML', $structure, $fullHeader); // runs through handleTranserEncoding() already

                // empty() check for body content
                if(empty($email->description)) {
                    $GLOBALS['log']->debug('InboundEmail Message (id:'.$email->message_id.') has no body');
                }
                
                // assign_to group
                $email->assigned_user_id = $this->group_id;









    
                $email->save();
                $email->new_with_id = false; // to allow future saves by UPDATE, instead of INSERT
            ////    ASSIGN APPROPRIATE ATTRIBUTES TO NEW EMAIL OBJECT
            ///////////////////////////////////////////////////////////////////////

    
			///////////////////////////////////////////////////////////////////////
			////    HANDLE EMAIL ATTACHEMENTS OR HTML TEXT
			// parts defines attachements - be mindful of .html being interpreted as an attachment
			if($structure->type == 1 && !empty($structure->parts)) {
				$GLOBALS['log']->debug('InboundEmail found multipart email - saving attachments if found.');
				$this->saveAttachments($msgNo, $structure->parts, $email->id);
			} elseif($structure->type == 0) {
				$uuemail = ($this->isUuencode($email->description)) ? true : false; 
				/* 
				 * UUEncoded attachments - legacy, but still have to deal with it
				 * format:
				 * begin 777 filename.txt
				 * UUENCODE
				 * 
				 * end
				 */
				// set body to the filtered one
				if($uuemail)
					$email->description = $this->handleUUEncodedEmailBody($email->description, $email->id);
				$email->save();
			} else {
				if($this->port != 110) {
					$GLOBALS['log']->debug('InboundEmail found a multi-part email (id:'.$messageId.') with no child parts to parse.');
				} else {
					$GLOBALS['log']->debug('InboundEmail found a multi-part email with no child parts to parse - BUT we\'re using POP3, so we suck.');
				}
			}
			////    END HANDLE EMAIL ATTACHEMENTS OR HTML TEXT
			///////////////////////////////////////////////////////////////////////

			///////////////////////////////////////////////////////////////////////
			////    LINK APPROPRIATE BEANS TO NEWLY SAVED EMAIL
			$contactAddr = $this->handleLinking($email);
			////    END LINK APPROPRIATE BEANS TO NEWLY SAVED EMAIL
			///////////////////////////////////////////////////////////////////////
			
			///////////////////////////////////////////////////////////////////////
			////    MAILBOX TYPE HANDLING
			$this->handleMailboxType($email, $header);
			////    END MAILBOX TYPE HANDLING
			///////////////////////////////////////////////////////////////////////

			///////////////////////////////////////////////////////////////////////
			////    SEND AUTORESPONSE
			$this->handleAutoresponse($email, $contactAddr);
			////    END SEND AUTORESPONSE
			///////////////////////////////////////////////////////////////////////
			////	END IMPORT ONE EMAIL
			///////////////////////////////////////////////////////////////////////
        } else {
            // only log if not POP3; pop3 iterates through ALL mail
            if($this->protocol != 'pop3') {
                $GLOBALS['log']->info("InboundEmail found a duplicate email: ".$header->message_id);
            }



        }
        ////    END DUPLICATE CHECK
        ///////////////////////////////////////////////////////////////////////
        
		///////////////////////////////////////////////////////////////////////
		////    DEAL WITH THE MAILBOX
		imap_setflag_full($this->conn, $msgNo, '\\SEEN');
		// if delete_seen, mark msg as deleted
		if($this->delete_seen == 1) {
			imap_setflag_full($this->conn, $msgNo, '\\DELETED');
		}
		





		
		$GLOBALS['log']->debug('********************************* InboundEmail finished import of 1 email: '.$email->name);
		////    END DEAL WITH THE MAILBOX
		///////////////////////////////////////////////////////////////////////
	}

	/**
	 * figures out if a plain text email body has UUEncoded attachments
	 * @param string string The email body
	 * @return bool True if UUEncode is detected.
	 */
	function isUuencode($string) {
		$rx = "begin [0-9]{3} .*";
		
		$exBody = explode("\r", $string);
		foreach($exBody as $line) {
			if(preg_match("/begin [0-9]{3} .*/i", $line)) {
				return true;
			}
		}
		
		return false;
	}

	/**
	 * handles UU Encoded emails - a legacy from pre-RFC 822 which must still be supported (?)
	 * @param string raw The raw email body
	 * @param string id Parent email ID
	 * @return string The filtered email body, stripped of attachments
	 */
	function handleUUEncodedEmailBody($raw, $id) {
		global $locale;
		
		$emailBody = '';
		$attachmentBody = '';
		$inAttachment = false;
		
		$exRaw = explode("\n", $raw);

		foreach($exRaw as $k => $line) {
			$line = trim($line);
			
			if(preg_match("/begin [0-9]{3} .*/i", $line, $m)) {
				$inAttachment = true;
				$fileName = $this->handleEncodedFilename(substr($m[0], 10, strlen($m[0])));
				
				$attachmentBody = ''; // reset for next part of loop;
				continue;
			}

			// handle "end"
			if(strpos($line, "end") === 0) {
				if(!empty($fileName) && !empty($attachmentBody)) {
					$this->handleUUDecode($id, $fileName, trim($attachmentBody));
					$attachmentBody = ''; // reset for next part of loop;
				}
			}
		
			if($inAttachment === false) {
				$emailBody .= "\n".$line;
			} else {
				$attachmentBody .= "\n".$line;
			}
		}
		
		/* since UUEncode was developed before MIME, we have NO idea what character set encoding was used.  we will assume the user's locale character set */
		$emailBody = $locale->translateCharset($emailBody, $locale->getExportCharset(), 'UTF-8');
		return $emailBody;
	}
	
	/**
	 * wrapper for UUDecode
	 * @param string id Id of the email
	 * @param string UUEncode Encode US-ASCII
	 */
	function handleUUDecode($id, $fileName, $UUEncode) {
		global $sugar_config;
		/* include PHP_Compat library; it auto-feels for PHP5's compiled convert_uuencode() function */ 
		require_once('include/PHP_Compat/convert_uudecode.php');
		require_once('modules/Notes/Note.php');
	
		$attach = new Note();
		$attach->parent_id = $id;
		$attach->parent_type = 'Emails';
		
		$fname = $this->handleEncodedFilename($fileName);

		if(!empty($fname)) {//assign name to attachment
			$attach->name = $fname;
		} else {//if name is empty, default to filename
			$attach->name = urlencode($fileName);
		}
		
		$attach->filename = urlencode($attach->name);

		//get position of last "." in file name
		$file_ext_beg = strrpos($attach->filename,".");
		$file_ext = "";
		//get file extension
		if($file_ext_beg >0) {
			$file_ext = substr($attach->filename, $file_ext_beg+1);
		}
		//check to see if this is a file with extension located in "badext"
		foreach($sugar_config['upload_badext'] as $badExt) {
	    	if(strtolower($file_ext) == strtolower($badExt)) {
	        	//if found, then append with .txt and break out of lookup
	            $attach->name = $attach->name . ".txt";
	            $attach->file_mime_type = 'text/';
	            $attach->filename = $attach->filename . ".txt";
	            break; // no need to look for more
	    	}
		}
		$attach->save();

		$bin = convert_uudecode($UUEncode);
		if($fp = fopen($sugar_config['upload_dir'].$attach->id.$attach->filename, 'wb')) {
			if(fwrite($fp, $bin)) {
				$GLOBALS['log']->debug('InboundEmail saved attachment file: '.$attach->filename);	
			} else {
				$GLOBALS['log']->fatal('InboundEmail could not create attachment file: '.$attach->filename);
			}
			fclose($fp);
		} else {
			$GLOBALS['log']->fatal('InboundEmail could not open a filepointer to: '.$sugar_config['upload_dir'].$attach->filename);
		}
	}

	/**
	 * returns true if the email's domain is NOT in the filter domain string
	 * 
	 * @param object email Email object in question
	 * @return bool true if not filtered, false if filtered
	 */
	function checkFilterDomain($email) {
		$filterDomain = $this->get_stored_options('filter_domain');
		if(!isset($filterDomain) || empty($filterDomain)) {
			return true; // nothing set for this
		} else {
			$replyTo = strtolower($email->reply_to_email);
			$from = strtolower($email->from_addr); 
			$filterDomain = '@'.strtolower($filterDomain);
			if(strpos($replyTo, $filterDomain) !== false) {
				$GLOBALS['log']->debug('Autoreply cancelled - [reply to] address domain matches filter domain.');
				return false;
			} elseif(strpos($from, $filterDomain) !== false) {
				$GLOBALS['log']->debug('Autoreply cancelled - [from] address domain matches filter domain.');
				return false;
			} else {
				return true; // no match
			}
		}
	}

	/** 
	 * returns true if subject is NOT "out of the office" type
	 * 
	 * @param string subject Subject line of email in question
	 * @return bool returns false if OOTO found
	 */
	function checkOutOfOffice($subject) {
		$ooto = array("Out of the Office", "Out of Office");
		
		foreach($ooto as $str) {
			if(eregi($str, $subject)) {
				$GLOBALS['log']->debug('Autoreply cancelled - found "Out of Office" type of subject.');
				return false;
			}
		}
		return true; // no matches to ooto strings
	}


	/**
	 * sets a timestamp for an autoreply to a single email addy
	 * 
	 * @param string addr Address of auto-replied target
	 */
	function setAutoreplyStatus($addr) {
		$this->db->query(	'INSERT INTO inbound_email_autoreply (id, deleted, date_entered, date_modified, autoreplied_to) VALUES (
							\''.create_guid().'\',
							0,
							\''.gmdate('Y-m-d H:i:s', strtotime('now')).'\',
							\''.gmdate('Y-m-d H:i:s', strtotime('now')).'\',
							\''.$addr.'\') ');	
	}
	
	
	/**
	 * returns true if recipient has NOT received 10 auto-replies in 24 hours
	 * 
	 * @param string from target address for auto-reply
	 * @return bool true if target is valid/under limit
	 */
	function getAutoreplyStatus($from) {
		$q_clean = 'UPDATE inbound_email_autoreply SET deleted = 1 WHERE date_entered < \''.gmdate('Y-m-d H:i:s', strtotime('now -24 hours')).'\'';
		$r_clean = $this->db->query($q_clean);
		
		$q = 'SELECT count(*) AS c FROM inbound_email_autoreply WHERE deleted = 0 AND autoreplied_to = \''.$from.'\'';
		$r = $this->db->query($q);
		$a = $this->db->fetchByAssoc($r);
		if($a['c'] >= 10) {
			$GLOBALS['log']->debug('Autoreply cancelled - more than 10 replies sent in 24 hours.');
			return false;
		} else {
			return true;
		}	
	}
	
	/**
	 * returns exactly 1 id match. if more than one, than returns false
	 * @param	$emailName		the subject of the email to match
	 * @param	$tableName		the table of the matching bean type
	 */
	function getSingularRelatedId($emailName, $tableName) {
		$repStrings = array('RE:','Re:','re:');
		$preppedName = str_replace($repStrings,'',trim($emailName));
		
		//TODO add team security to this query
		$q = 'SELECT count(id) AS c FROM '.$tableName.' WHERE deleted = 0 AND name LIKE \'%'.$preppedName.'%\'';
		$r = $this->db->query($q);
		$a = $this->db->fetchByAssoc($r);
		
		if($a['c'] == 0) {
			$q = 'SELECT id FROM '.$tableName.' WHERE deleted = 0 AND name LIKE \'%'.$preppedName.'%\'';
			$r = $this->db->query($q);
			$a = $this->db->fetchByAssoc($r);
			return $a['id'];
		} else {
			return false;
		}	
	}
	
	/**
	 * saves InboundEmail parse macros to config.php
	 * @param string type Bean to link
	 * @param string macro The new macro
	 */
	function saveMacro($type, $macro) {
		global $sugar_config;
		
		// inbound_email_case_subject_macro
		$var = "inbound_email_".strtolower($type)."_subject_macro";
		
		$sugar_config[$var] = $macro;

		ksort($sugar_config);
		
		$sugar_config_string = "<?php\n" .
			'// created: ' . date('Y-m-d H:i:s') . "\n" .
			'$sugar_config = ' .
			var_export($sugar_config, true) .
			";\n?>\n";
			
		write_array_to_file("sugar_config", $sugar_config, "config.php");
	}
	
	/**
	 * returns the HTML for the Case macro setting
	 * @return string form
	 */
	function getCaseMacroForm() {
		global $mod_strings;
		global $app_strings;
		
		if(!class_exists('aCase')) {
			require_once('modules/Cases/Case.php');
		}
		$c = new aCase();
		
		$macro = $c->getEmailSubjectMacro();
		
		$ret =<<<eoq
			<form action="index.php" method="post" name="Macro" id="form">
						<input type="hidden" name="module" value="InboundEmail">
						<input type="hidden" name="action" value="ListView">
						<input type="hidden" name="save" value="true">
			
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td style="padding-bottom: 2px;">
						<input 	title="{$app_strings['LBL_SAVE_BUTTON_TITLE']}"
								accessKey="{$app_strings['LBL_SAVE_BUTTON_KEY']}" 
								class="button"
								onclick="this.form.return_module.value='InboundEmail'; this.form.return_action.value='ListView';" 
								type="submit" name="Edit" value="  {$app_strings['LBL_SAVE_BUTTON_LABEL']}  ">
					</td>
				</tr>
			</table>
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabDetailView">
				<tr>
					<td valign="top" class="tabDetailViewDL" width='10%' NOWRAP>
						<slot>
							<b>{$mod_strings['LBL_CASE_MACRO']}:</b>
						</slot>
					</td>
					<td valign="top" class="tabDetailViewDF" width='20%'>
						<slot>
							<input name="inbound_email_case_macro" type="text" value="{$macro}">
						</slot>
					</td>
					<td valign="top" class="tabDetailViewDF" width='70%'>
						<slot>
							{$mod_strings['LBL_CASE_MACRO_DESC']}
							<br />
							<i>{$mod_strings['LBL_CASE_MACRO_DESC2']}</i>
						</slot>
					</td>
				</tr>
			</table>
			</form>
eoq;
		return $ret;
	}
	
	/**
	 * for mailboxes of type "Support" parse for '[CASE:%1]'
	 * @param	$emailName		the subject line of the email
	 * @param	$aCase			a Case object
	 */
	function getCaseIdFromCaseNumber($emailName, $aCase) {
		//$emailSubjectMacro
		$exMacro = explode('%1', $aCase->getEmailSubjectMacro());
		$open = $exMacro[0];
		$close = $exMacro[1];
		
		if($sub = stristr($emailName, $open)) { // eliminate everything up to the beginning of the macro and return the rest
			// $sub is [CASE:XX] xxxxxxxxxxxxxxxxxxxxxx
			$sub2 = str_replace($open, '', $sub);
			// $sub2 is XX] xxxxxxxxxxxxxx
			$sub3 = substr($sub2, 0, strpos($sub2, $close));
			
			$r = $this->db->query('SELECT id FROM cases WHERE case_number = \''.$sub3.'\'');
			if(is_resource($r)) {
				$a = $this->db->fetchByAssoc($r);
				return $a['id'];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function get_stored_options($option_name,$default_value=null,$stored_options=null) {
		if (empty($stored_options)) {
			$stored_options=$this->stored_options;
		}
		if(!empty($stored_options)) {
			$storedOptions = unserialize(base64_decode($stored_options));
			if (isset($storedOptions[$option_name])) {
				$default_value=$storedOptions[$option_name];
			}
		}		
		return $default_value;
	}	

	
	/** 
	 * This function returns a contact or user ID if a matching email is found
	 * @param	$email		the email address to match
	 * @param	$table		which table to query
	 */
	function getRelatedId($email, $table) {
		$email = trim($email);
		$q = "SELECT id FROM ".$table." WHERE deleted=0 AND ";
		if(strstr($email, ',')) {
			$emails = explode(',', $email);
			$fixedEmail = '';
			foreach($emails as $k => $oneEmail) {
				$fixedEmail .= "'".trim($oneEmail)."',";
			}
			$fixedEmail = substr_replace($fixedEmail, '', -1, 1);
			$q .= "email1 IN (".$fixedEmail.") OR email2 IN (".$fixedEmail.")";
		} else {
			$q .= "email1 LIKE '".$email."' OR email2 LIKE '".$email."'";
		}
		$r = $this->db->query($q);

		$retArr = array();
		while($a = $this->db->fetchByAssoc($r)) {
			$retArr[] = $a['id'];
		}
		if(count($retArr) > 0) {
			return $retArr;	
		} else {
			return false;
		}
	}
	
	/**
	 * finds emails tagged "//UNSEEN" on mailserver and "SINCE: [date]" if that
	 * option is set
	 * 
	 * @return array Array of messageNumbers (mail server's internal keys)
	 */
	function getNewMessageIds() {
		$storedOptions = unserialize(base64_decode($this->stored_options));
		
		//TODO figure out if the since date is UDT
		if($storedOptions['only_since']) {// POP3 does not support Unseen flags
			if(!isset($storedOptions['only_since_last']) && !empty($storedOptions['only_since_last'])) {
				$q = 'SELECT last_run FROM schedulers WHERE job = \'function::pollMonitoredInboxes\'';
				$r = $this->db->query($q);
				$a = $this->db->fetchByAssoc($r);
				
				$date = date('r', strtotime($a['last_run']));
			} else {
				$date = $storedOptions['only_since_last'];
			}
			$ret = imap_search($this->conn, 'SINCE "'.$date.'" UNSEEN');
			$check = imap_check($this->conn);
			$storedOptions['only_since_last'] = $check->Date;
			$this->stored_options = base64_encode(serialize($storedOptions));
			$this->save();
		} else {
			$ret = imap_search($this->conn, 'UNSEEN');
		}
		
		$GLOBALS['log']->debug('-----> getNewMessageIds() got '.count($ret).' new Messages');
		return $ret;
	}
	
	/**
	 * connects to mailserver
	 * 
	 * @param bool test Flag to test connection
	 * @return string "true" on success, "false" or $errorMessage on failure
	 */
	function connectMailserver($test=false) {
		if(!function_exists("imap_open")) {
			$GLOBALS['log']->fatal('------------------------- IMAP libraries NOT available!!!! die()ing thread.----');
			return "false";	
		}
		
		global $mod_strings;
		imap_errors(); // clearing error stack
		error_reporting(0); // turn off notices from IMAP

		// tls::ca::ssl::protocol::novalidate-cert::notls
		if($test) {
			imap_timeout(1, 15); // 60 secs is the default 
			imap_timeout(2, 15);
			imap_timeout(3, 15);

			$opts = $this->findOptimumSettings();
			if(isset($opts['good']) && empty($opts['good'])) {
				return array_pop($opts['err']);
			} else {
				$service = $opts['service'];
				$service = str_replace('foo','', $service); // foo there to support no-item explodes	
			}
		} else {
			$exServ = explode('::', $this->service);
	
			foreach($exServ as $v) {
				if(!empty($v) && ($v != 'imap' && $v !='pop3')) {
					$service .= '/'.$v;
				}	
			}
		}

		$connectString = '{'.$this->server_url.':'.$this->port.'/service='.$this->protocol.$service.'}'.$this->mailbox;

		$this->conn = imap_open($connectString, $this->email_user, $this->email_password, CL_EXPUNGE);

		if($test) {
			$errors = '';
			$alerts = '';
			$successful = false;
			if(($errors = imap_last_error()) || ($alerts = imap_alerts())) {
				if($errors == 'Mailbox is empty') { // false positive
					$successful = true;
				} else {
					$msg .= $errors;
					$msg .= '<p>'.$alerts.'<p>';
					$msg .= '<p>'.$mod_strings['ERR_TEST_MAILBOX'];
				}
			} else {
				$successful = true;
			}
			
			if($successful) {
				if($this->protocol == 'imap') {
					$testConnectString = '{'.$this->server_url.':'.$this->port.'/service='.$this->protocol.$service.'}';
					$list = imap_getmailboxes($this->conn, $testConnectString, "*");
					if (is_array($list)) {
						sort($list);
						$msg .= '<b>'.$mod_strings['LBL_FOUND_MAILBOXES'].'</b><p>';
						foreach ($list as $key => $val) {
							$mb = imap_utf7_decode(str_replace($testConnectString,'',$val->name));
							$msg .= '<a onClick=\'setMailbox(\"'.$mb.'\"); window.close();\'>';
							$msg .= $mb;
							$msg .= '</a><br>';
						}
					} else {
						$msg .= $errors;
						$msg .= '<p>'.$mod_strings['ERR_MAILBOX_FAIL'].imap_last_error().'</p>';
						$msg .= '<p>'.$mod_strings['ERR_TEST_MAILBOX'].'</p>';
					}
				} else {
					$msg .= $mod_strings['LBL_POP3_SUCCESS'];
				}
			}




			imap_errors(); // collapse error stack
			imap_close($this->conn);
			return $msg;
		} elseif(!is_resource($this->conn)) {
			return "false";
		} else {
			return "true";
		}
	}


	
	function checkImap() {
		global $mod_strings;
		
		if(!function_exists('imap_open')) {
			echo '
			<table cellpadding="0" cellspacing="0" width="100%" border="0" class="listView">
				<tr height="20">
					<td scope="col" width="25%" class="listViewThS1" colspan="2"><slot>
						'.$mod_strings['LBL_WARN_IMAP_TITLE'].' 
					</slot></td>
				</tr>
				<tr>
					<td scope="row" valign=TOP class="tabDetailViewDL" bgcolor="#fdfdfd" width="20%"><slot>
						'.$mod_strings['LBL_WARN_IMAP'].'
					<td scope="row" valign=TOP class="oddListRowS1" bgcolor="#fdfdfd" width="80%"><slot>
						<span class=error>'.$mod_strings['LBL_WARN_NO_IMAP'].'</span>
					</slot></td>
				</tr>
			</table>
			<br>';
		}
	}

	/**
	 * retrieves an array of I-E beans based on the group_id
	 * @param	string	$groupId	GUID of the group user or Individual
	 * @return	array	$beans		array of beans
	 * @return 	boolean false if none returned
	 */
	function retrieveByGroupId($groupId) {
		$q = 'SELECT id FROM inbound_email WHERE group_id = \''.$groupId.'\' AND deleted = 0 AND status = \'Active\'';
		$r = $this->db->query($q);

		$beans = array();
		while($a = $this->db->fetchByAssoc($r)) {
			$ie = new InboundEmail();
			$ie->retrieve($a['id']);
			$beans[] = $ie;
		}
		return $beans;
	}


	/**
	 * returns the bean name - overrides SugarBean's
	 */
	function get_summary_text() {
		return $this->name;
	}

	/**
	 * Override's SugarBean's
	 */
	function create_export_query($order_by, $where, $show_deleted = 0) {
		return $this->create_list_query($order_by, $where, $show_deleted = 0);
	}
	
	/**
	 * Override's SugarBean's
	 */
	function create_list_query($order_by, $where, $show_deleted = 0) {
		$query = 'SELECT '.$this->table_name.'.*';
		
		$query .= ' FROM '.$this->table_name.' ';

		if($show_deleted == 0) {
			$where_auto = 'DELETED=0';
		} elseif($show_deleted == 1) {
			$where_auto = 'DELETED=1';
		} else {
			$where_auto = '1=1';
		}

		if($where != "") {
			$query .= 'WHERE ('.$where.') AND '.$where_auto;
		}
		else {
			$query .= 'WHERE '.$where_auto;
		}

		if(!empty($order_by))
			$query .= ' ORDER BY '.$order_by;
		return $query;
	}

	/**
	 * Override's SugarBean's
	 */
	function get_list_view_data(){
		global $mod_strings;
		global $app_list_strings;
		$temp_array = $this->get_list_view_array();
		$temp_array['MAILBOX_TYPE_NAME']= $app_list_strings['dom_mailbox_type'][$this->mailbox_type];
		return $temp_array;
	}

	/**
	 * Override's SugarBean's
	 */
	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	/**
	 * Override's SugarBean's
	 */
	function fill_in_additional_detail_fields() {
		if(!empty($this->service)) {
			$exServ = explode('::', $this->service);
			$this->tls		= $exServ[0];
			$this->ca		= $exServ[1];
			$this->ssl		= $exServ[2];
			$this->protocol	= $exServ[3];
		}
	}

} // end class definition


?>
