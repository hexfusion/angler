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
// $Id: Email.php,v 1.255 2006/08/29 23:12:14 wayne Exp $
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Users/User.php');
require_once('include/SugarPHPMailer.php');
require_once('include/utils.php');

// Email is used to store customer information.
class Email extends SugarBean {
	var $field_name_map;

	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;



	var $description;
	var $description_html;
	var $name;
	var $duration_hours;
	var $duration_minutes;
	var $date_start;
	var $time_start;
	var $parent_type;
	var $parent_id;
	var $contact_id;
	var $user_id;
	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $account_id;
	var $opportunity_id;
	var $case_id;
	var $assigned_user_name;



	var $from_addr;
	var $from_name;
	var $to_addrs;
    var $cc_addrs;
    var $bcc_addrs;
	var $to_addrs_arr;
    var $cc_addrs_arr;
    var $bcc_addrs_arr;
	var $to_addrs_ids;
	var $to_addrs_names;
	var $to_addrs_emails;
	var $cc_addrs_ids;
	var $cc_addrs_names;
	var $cc_addrs_emails;
	var $bcc_addrs_ids;
	var $bcc_addrs_names;
	var $bcc_addrs_emails;
    var $type = 'archived';
    var $status;
    var $status_name;
    var $intent;
    var $message_id;
    var $raw_source;
	var $mailbox_id; // id of the inbound email mailbox
    var $link_action;
    var $reply_to_addr;
	
	// composite attributes
	var $date_sent;
    var $attachments = array();
    var $saved_attachments = array();
    var $attachment_image;
	var $default_email_subject_values = array('Follow-up on proposal', 'Initial discussion', 'Review needs', 'Discuss pricing', 'Demo', 'Introduce all players', );
	var $minutes_values = array('00', '15', '30', '45');
	var $new_schema = true;
	var $table_name = 'emails';
	var $module_dir = 'Emails';
	var $object_name = 'Email';

	// relationship stuff
	var $rel_users_table = 'emails_users';
	var $rel_contacts_table = 'emails_contacts';
	var $rel_cases_table = 'emails_cases';
	var $rel_accounts_table = 'emails_accounts';
	var $rel_opportunities_table = 'emails_opportunities';
	var $rel_leads_table = 'emails_leads';

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = array('assigned_user_name', 'assigned_user_id', 'contact_id', 'user_id', 'contact_name','to_addrs_id');
	
    // to support InboundEmail
    var $accounts;
    var $cases;
    var $leads;
    var $contacts;
    var $opportunities;
    var $users;

    var $reply_to_name;
    var $reply_to_email;
    var $rollover;
	var $rolloverStyle		= "<style>div#rollover {position: relative;float: left;margin: none;text-decoration: none;}div#rollover a:hover {padding: 0;}div#rollover a span {display: none;}div#rollover a:hover span {text-decoration: none;display: block;width: 250px;margin-top: 5px;margin-left: 5px;position: absolute;padding: 10px;color: #333;	border: 1px solid #ccc;	background-color: #fff;	font-size: 12px;z-index: 1000;}</style>\n";
	var $cachePath			= 'cache/modules/Emails';
	var $cacheFile			= 'robin.cache.php';
	

	function Email() {
		parent::SugarBean();



	}
	
	
    /**
     * retrieves Notes that belong to this Email and stuffs them into the "attachments" attribute
     */
    function getNotes($id, $duplicate=false) {
        if(!class_exists('Note')) {
            require_once('modules/Notes/Note.php');
        }
        
        $noteArray = array();
        $q = "SELECT id FROM notes WHERE parent_id = '".$id."'";
        $r = $this->db->query($q);
        
        while($a = $this->db->fetchByAssoc($r)) {
            $note = new Note();
            $note->retrieve($a['id']);
            
            // duplicate actual file when creating forwards
	        if($duplicate) {
	        	if(!class_exists('UploadFile')) {
	        		require_once('include/upload_file.php');
	        	}
	        	// save a brand new Note
	        	$note->id = create_guid();
	        	$note->new_with_id = true;
				$note->parent_id = $this->id;
				$note->parent_type = $this->module_dir;
	        		
				$noteFile = new UploadFile('none');
				$noteFile->duplicate_file($a['id'], $note->id, $note->filename);
				
				$note->save();
	        }
	        // add Note to attachments array
            $this->attachments[] = $note;
        }
        
    }
    
	/**
	 * handles attachments of various kinds when sending email
	 */
	function handleAttachments() {
		require_once('modules/Documents/Document.php');
		require_once('modules/DocumentRevisions/DocumentRevision.php');
		require_once('modules/Notes/Note.php');
		
		global $mod_strings;
        
        ///////////////////////////////////////////////////////////////////////////
        ////    ATTACHMENTS FROM DRAFTS
        if(($this->type == 'out' || $this->type == 'draft') && $this->status == 'draft' && isset($_REQUEST['record'])) {
            $this->getNotes($_REQUEST['record']); // cn: get notes from OLD email for use in new email
        }
        ////    END ATTACHMENTS FROM DRAFTS
        ///////////////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////////////
        ////    ATTACHMENTS FROM FORWARDS
        // Bug 8034 Jenny - Need the check for type 'draft' here to handle cases where we want to save 
        // forwarded messages as drafts.  We still need to save the original message's attachments.
        if(($this->type == 'out' || $this->type == 'draft')  && isset($_REQUEST['origType']) && $_REQUEST['origType']=='forward' && isset($_REQUEST['return_id']) && !empty($_REQUEST['return_id'])){
                $this->getNotes($_REQUEST['return_id'], true);
        }
        ////    END ATTACHMENTS FROM FORWARDS
        ///////////////////////////////////////////////////////////////////////////

		///////////////////////////////////////////////////////////////////////////
		////	ATTACHMENTS FROM TEMPLATES
		// to preserve individual email integrity, we must dupe Notes and associated files
		// for each outbound email - good for integrity, bad for filespace
		if(isset($_REQUEST['template_attachment']) && !empty($_REQUEST['template_attachment'])) {
			$removeArr = array();
			$noteArray = array();
			
			if(isset($_REQUEST['temp_remove_attachment']) && !empty($_REQUEST['temp_remove_attachment'])) {
				$removeArr = $_REQUEST['temp_remove_attachment'];
			}
			

			foreach($_REQUEST['template_attachment'] as $noteId) {
				if(in_array($noteId, $removeArr)) {
					continue;
				}
				$noteTemplate = new Note();
				$noteTemplate->retrieve($noteId);
				$noteTemplate->id = create_guid();
				$noteTemplate->new_with_id = true; // duplicating the note with files
				$noteTemplate->parent_id = $this->id;
				$noteTemplate->parent_type = $this->module_dir;
				$noteTemplate->save();



				
				$noteFile = new UploadFile('none');
				$noteFile->duplicate_file($noteId, $noteTemplate->id, $noteTemplate->filename);
				$noteArray[] = $noteTemplate;
			}
			$this->attachments = array_merge($this->attachments, $noteArray);
		}
		////	END ATTACHMENTS FROM TEMPLATES
		///////////////////////////////////////////////////////////////////////////
	
		///////////////////////////////////////////////////////////////////////////
		////	ADDING NEW ATTACHMENTS
		$max_files_upload = 10;
        // Jenny - Bug 8211 Since attachments for drafts have already been processed, 
        // we don't need to re-process them.
        if($this->status != "draft") {
    		$notes_list = array();
    		if(!empty($this->id) && !$this->new_with_id) {
    			$note = new Note();
    			$where = "notes.parent_id='{$this->id}'";
    			$notes_list = $note->get_full_list("", $where, true);
    		}
    		$this->attachments = array_merge($this->attachments, $notes_list);
        }
		// cn: Bug 5995 - rudimentary error checking
		$filesError = array(
			0 => 'UPLOAD_ERR_OK - There is no error, the file uploaded with success.',
			1 => 'UPLOAD_ERR_INI_SIZE - The uploaded file exceeds the upload_max_filesize directive in php.ini.',
			2 => 'UPLOAD_ERR_FORM_SIZE - The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
			3 => 'UPLOAD_ERR_PARTIAL - The uploaded file was only partially uploaded.',
			4 => 'UPLOAD_ERR_NO_FILE - No file was uploaded.',
			5 => 'UNKNOWN ERROR',
			6 => 'UPLOAD_ERR_NO_TMP_DIR - Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.',
			7 => 'UPLOAD_ERR_CANT_WRITE - Failed to write file to disk. Introduced in PHP 5.1.0.',
		);
	
		for($i = 0; $i < $max_files_upload; $i++) {
			// cn: Bug 5995 - rudimentary error checking
			if($_FILES['email_attachment'.$i]['error'] != 0 && $_FILES['email_attachment'.$i]['error'] != 4) {
				$GLOBALS['log']->fatal('Email Attachment could not be attach due to error: '.$filesError[$_FILES['email_attachment'.$i]['error']]);
				continue;
			}
			
			$note = new Note();
			$note->parent_id = $this->id;
			$note->parent_type = $this->module_dir;
			$upload_file = new UploadFile('email_attachment'.$i);
	
			if(empty($upload_file)) {
				continue;
			}
	
			if(isset($_FILES['email_attachment'.$i]) && $upload_file->confirm_upload()) {
				$note->filename = $upload_file->get_stored_file_name();
				$note->file = $upload_file;
				$note->name = $mod_strings['LBL_EMAIL_ATTACHMENT'].': '.$note->file->original_file_name;



	
				$this->attachments[] = $note;
			}
		}
        
		$this->saved_attachments = array();
		foreach($this->attachments as $note) {
			if(!empty($note->id)) {
				array_push($this->saved_attachments, $note);
				continue;
			}
			$note->parent_id = $this->id;
			$note->parent_type = 'Emails';
			$note->file_mime_type = $note->file->mime_type;
			$note_id = $note->save();
			
			$this->saved_attachments[] = $note;
			
			$note->id = $note_id;
			$note->file->final_move($note->id);
		}
		////	END NEW ATTACHMENTS
		///////////////////////////////////////////////////////////////////////////
		
		///////////////////////////////////////////////////////////////////////////
		////	ATTACHMENTS FROM DOCUMENTS
		for($i=0; $i<10; $i++) {
			if(isset($_REQUEST['documentId'.$i]) && !empty($_REQUEST['documentId'.$i])) {
				$doc = new Document();
				$docRev = new DocumentRevision();
				$docNote = new Note();
				$noteFile = new UploadFile('none');
				
				$doc->retrieve($_REQUEST['documentId'.$i]);
				$docRev->retrieve($doc->document_revision_id);
				
				$this->saved_attachments[] = $docRev;
				
				$docNote->name = $doc->document_name;
				$docNote->filename = $docRev->filename;
				$docNote->description = $doc->description;
				$docNote->parent_id = $this->id;
				$docNote->parent_type = 'Emails';
				$docNote->file_mime_type = $docRev->file_mime_type;
				$docId = $docNote = $docNote->save();
				
				$noteFile->duplicate_file($docRev->id, $docId, $docRev->filename);
			}
			
		}
		
		////	END ATTACHMENTS FROM DOCUMENTS
		///////////////////////////////////////////////////////////////////////////
	
		///////////////////////////////////////////////////////////////////////////
		////	REMOVE ATTACHMENTS
	
		if(isset($_REQUEST['remove_attachment']) && !empty($_REQUEST['remove_attachment'])) {
			foreach($_REQUEST['remove_attachment'] as $noteId) {
				$q = 'UPDATE notes SET deleted = 1 WHERE id = \''.$noteId.'\'';
				$this->db->query($q);
			}
		}
	
		////	END REMOVE ATTACHMENTS
		///////////////////////////////////////////////////////////////////////////
	}
	
	/**
	 * "quotes" (i.e., "> my text yadda" the HTML part of an email
	 */
	function quoteHtmlEmail($text) {
		// TODO: extract all "real" text and apply "forwading" to the HTML
		$text = trim($text);
		if(empty($text)) {
			return '';
		}
		 
		$out = '<i>';
		$raw = explode("\r",strip_tags(from_html($text)));
		
		foreach($raw as $line) {
			$line = trim($line);
			if($line != "") {
				$out .= "> ".$line."<br />";;
			}
		}
		$out .= '</i>';
		
		//_ppd($out);
		return nl2br($out);
	}

	/**
	 * replaces the javascript in utils.php - more specialized
	 */
	function u_get_clear_form_js($type='', $group='', $assigned_user_id='') {
		$uType				= '';
		$uGroup				= '';
		$uAssigned_user_id	= '';
		
		if(!empty($type)) { $uType = '&type='.$type; }
		if(!empty($group)) { $uGroup = '&group='.$group; }
		if(!empty($assigned_user_id)) { $uAssigned_user_id = '&assigned_user_id='.$assigned_user_id; }
		
		$the_script = '
		<script type="text/javascript" language="JavaScript"><!-- Begin
			function clear_form(form) {
				var newLoc = "index.php?action=" + form.action.value + "&module=" + form.module.value + "&query=true&clear_query=true'.$uType.$uGroup.$uAssigned_user_id.'";
				if(typeof(form.advanced) != "undefined"){
					newLoc += "&advanced=" + form.advanced.value;
				}
				document.location.href= newLoc;
			}
		//  End --></script>';
		return $the_script;
	}
	
	/**
	 * outputs JS to set fields in the MassUpdate form in the "My Inbox" view
	 */
	function js_set_archived() {
		global $mod_strings;
		$script = '
		<script type="text/javascript" language="JavaScript"><!-- Begin
			function setArchived() {
				var form = document.getElementById("MassUpdate");
				var status = document.getElementById("mass_status");
				var ok = false;

				for(var i=0; i < form.elements.length; i++) {
					if(form.elements[i].name == "mass[]") {
						if(form.elements[i].checked == true) {
							ok = true;
						}
					}
				}

				if(ok == true) {
					var user = document.getElementById("mass_assigned_user_name");				
					var team = document.getElementById("team");
	
					user.value = "";
					for(var j=0; j<status.length; j++) {
						if(status.options[j].value == "archived") {
							status.options[j].selected = true;
							status.selectedIndex = j; // for IE
						}
					}
	
					form.submit();
				} else { 
					alert("'.$mod_strings['ERR_ARCHIVE_EMAIL'].'");
				}

			}
		//  End --></script>';
		return $script;
	}

	/**
	 * checks status of an email (most likely Inbound) to see if it is "locked"
	 * @param	$silent		flag to redirect user to a chooser screen
	 * @return	boolean		locked or not
	 */
	function checkPessimisticLock($silent=false) {
		global $current_user;
		
		$userIds = array();
		$userIds[] = $current_user->id;
		
		$rG = $this->db->query('SELECT id FROM users WHERE users.is_group = 1 AND deleted = 0');
		while($aG = $this->db->fetchByAssoc($rG)) {
			$userIds[] = $aG['id'];
		}
		
		if(!in_array($this->assigned_user_id, $userIds)) {
			if($silent) {
				return false;
			} else {
				$GLOBALS['log']->debug('Emails found a Pessimistic Lock situation.  Redirecting user to PessimisticLock.php');
				header('Location: index.php?module=Emails&action=PessimisticLock&user='.$this->assigned_user_id);
			}
		}
		return true;
	}

	/**
	 * returns the name from the email
	 * @param	$part	'first','last' or 'both'
	 * @return  string  name value
	 */
	function getName($part) {
		$first = '';
		$last = '';
		$both = '';
		$email = '';
		if(!empty($this->reply_to_name)) {
			$name = $this->reply_to_name;
		} elseif(!empty($this->from_name)) {
			$name = $this->from_name;
		} else {
			return '';
		}
		
		$name = trim($name);
		
		if(strpos($name, '<')) {
			$start = strpos($name, '<');
		} elseif(strpos($name, '&lt;')) {
			$start = strpos($name, '&lt;');
		}
		if(!empty($start)) {
			$email = substr($name, $start, strlen($name));
			$name = substr($name, 0, ($start-1));
		}
		
		if(strpos($name, ',')) { // explode last, first format
			$exName = explode(',', $name);
		} elseif (strpos($name, ' ')) { // explode first x last format
			$exName = explode(' ', $name);
			if(count($exName) > 2) {
				$last = $exName[(count($exName) - 1)];
				$loop = count($exName) - 2; // -2 because we only want the values before 'last name'
				$first = '';
				for($i=0; $i<$loop; $i++) {
					$first .= $exName[$i];
				}
			} else {
				$first = $exName[0];
				$last = $exName[1];
			}
		} else { //
			// likely that this is ONLY an email address
			if(strpos($name, "@")) {
				$email = $name;
			} else {
				$first = $name;
			}
		}
		
		$names = array();
		$names['first'] = $first;
		$names['last'] = $last;
		$names['both'] = $first.' '.$last;
		$names['email'] = $email;
		
		return $names[$part];
	}
	
	/**
	 * returns the description part of an Email preferring the HTML, with
	 * striped tags
	 */
	function getDescription() {
		$desc = '';
		$tagCaret = array('&lt;', '&gt;');
		$tagCaretReal = array('<','>');
		
		if(!empty($this->description_html)) {	
			$desc = trim(strip_tags(str_replace($tagCaret, $tagCaretReal, $this->description_html)));
		} elseif(!empty($this->description)) {
			$desc = trim($this->description);
		}
		return $desc;
	}


	function set_notification_body($xtpl, $email) {
		$xtpl->assign("EMAIL_SUBJECT", $email->name);
		$xtpl->assign("EMAIL_DATESENT", $email->date_start . " " . $email->time_start);
		return $xtpl;
	}

	function check_email_settings() {
		global $current_user;

		$mail_fromaddress = $current_user->email1;
		$replyToName = $current_user->getPreference('mail_fromname');
		$mail_fromname = (!empty($replyToName)) ? $current_user->getPreference('mail_fromname') : $current_user->full_name;
		
		if(empty($mail_fromaddress)) {
			return false;
		}
		if(empty($mail_fromname)) {
	  		return false;
		}
		
    	$send_type = $current_user->getPreference('mail_sendtype') ;
		if (!empty($send_type) && $send_type == "SMTP") {
			$mail_smtpserver = $current_user->getPreference('mail_smtpserver');
			$mail_smtpport = $current_user->getPreference('mail_smtpport');
			$mail_smtpauth_req = $current_user->getPreference('mail_smtpauth_req');
			$mail_smtpuser = $current_user->getPreference('mail_smtpuser');
			$mail_smtppass = $current_user->getPreference('mail_smtppass');
			if (empty($mail_smtpserver) || 
				empty($mail_smtpport) ||
                (!empty($mail_smtpauth_req) && ( empty($mail_smtpuser) || empty($mail_smtppass)))
			) {
				return false;
			}
		}
		return true;
	}

	function send() {
		global $current_user;
		global $sugar_config;
		global $locale;
		$mail = new SugarPHPMailer();

		foreach ($this->to_addrs_arr as $addr_arr) {
			if ( empty($addr_arr['display'])) {
				$mail->AddAddress($addr_arr['email'], "");
			} else {
				$mail->AddAddress($addr_arr['email'], $addr_arr['display']);
			}
		}
		foreach ($this->cc_addrs_arr as $addr_arr) {
			if ( empty($addr_arr['display'])) {
				$mail->AddCC($addr_arr['email'], "");
			} else {
				$mail->AddCC($addr_arr['email'], $addr_arr['display']);
			}
		}
		
		foreach ($this->bcc_addrs_arr as $addr_arr) {
			if ( empty($addr_arr['display'])) {
				$mail->AddBCC($addr_arr['email'], "");
			} else {
				$mail->AddBCC($addr_arr['email'], $addr_arr['display']);
			}
		}

		if ($current_user->getPreference('mail_sendtype') == "SMTP") {
			$mail->Mailer = "smtp";
			$mail->Host = $current_user->getPreference('mail_smtpserver');
			$mail->Port = $current_user->getPreference('mail_smtpport');

			if ($current_user->getPreference('mail_smtpauth_req')) {
				$mail->SMTPAuth = TRUE;
				$mail->Username = $current_user->getPreference('mail_smtpuser');
				$mail->Password = $current_user->getPreference('mail_smtppass');
			}
		} else /*if ($current_user->getPreference('mail_sendtype') == 'sendmail')*/ { // cn:no need to check since we default to it in any case!
			$mail->Mailer = "sendmail";
		}
		// FROM ADDRESS
		if(!empty($this->from_addr)) {
			$mail->From = $this->from_addr;
		} else {
			$mail->From = $current_user->getPreference('mail_fromaddress');
			$this->from_addr = $mail->From;
		}
		// FROM NAME
		if(!empty($this->from_name)) {
			$mail->FromName = $this->from_name;
		} else {
			$mail->FromName =  $current_user->getPreference('mail_fromname');
			$this->from_name = $mail->FromName;
		}

		$mail->Sender = $mail->From; /* set Return-Path field in header to reduce spam score in emails sent via Sugar's Email module */
		$mail->AddReplyTo($mail->From,$mail->FromName);

		$encoding = version_compare(phpversion(), '5.0', '>=') ? 'UTF-8' : 'ISO-8859-1';
		$subj = html_entity_decode($this->name, ENT_QUOTES, $encoding);
		$mail->Subject = $locale->translateCharset($subj, 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));


		///////////////////////////////////////////////////////////////////////
		////	ATTACHMENTS
		foreach($this->saved_attachments as $note) {
			$mime_type = 'text/plain';
			if($note->object_name == 'Note') {
				if(!empty($note->file->temp_file_location) && is_file($note->file->temp_file_location)) { // brandy-new file upload/attachment
					$file_location = $sugar_config['upload_dir'].$note->id.$note->file->original_file_name;
					$filename = $note->file->original_file_name;
					$mime_type = $note->file->mime_type;
				} else { // attachment coming from template/forward
					$file_location = rawurldecode(UploadFile::get_file_path($note->filename,$note->id));
					$filename = $note->id.$note->filename;
					$mime_type = $note->file_mime_type;
				}
			} elseif($note->object_name == 'DocumentRevision') { // from Documents
				$filename = $note->id.$note->filename;
				$file_location = getcwd().'/cache/upload/'.$filename;
				$mime_type = $note->file_mime_type;
			}

			//$filename = $note->file->original_file_name; 
            $filename = $note->filename;

			//is attachment in our list of bad files extensions?  If so, append .txt to file location
			//get position of last "." in file name
			$file_ext_beg = strrpos($file_location,".");
			$file_ext = "";
			//get file extension
			if($file_ext_beg >0){
				$file_ext = substr($file_location, $file_ext_beg+1 );
			}
			//check to see if this is a file with extension located in "badext"
			foreach($sugar_config['upload_badext'] as $badExt) {
		       	if(strtolower($file_ext) == strtolower($badExt)) {
			       	//if found, then append with .txt to filename and break out of lookup
			       	//this will make sure that the file goes out with right extension, but is stored
			       	//as a text in db.
			        $file_location = $file_location . ".txt";
			        break; // no need to look for more
		       	}
	        }			
			$mail->AddAttachment($file_location, $filename, 'base64', $mime_type);
		}
		////	END ATTACHMENTS
		///////////////////////////////////////////////////////////////////////
		

		///////////////////////////////////////////////////////////////////////
		////	HANDLE EMAIL FORMAT PREFERENCE
		// the if() below is HIGHLY dependent on the Javascript unchecking the Send HTML Email box
		// HTML email
		if( (isset($_REQUEST['setEditor']) /* from Email EditView navigation */
			&& $_REQUEST['setEditor'] == 1 
			&& trim($_REQUEST['description_html']) != '')
			|| trim($this->description_html) != '' /* from email templates */
		) {
			// wp: if body is html, then insert new lines at 996 characters. no effect on client side
			// due to RFC 2822 which limits email lines to 998
			$mail->IsHTML(true);
			$body = $locale->translateCharset(from_html(wordwrap($this->description_html, 996)), 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
			$mail->Body = $body;

			// if alternative body is defined, use that, else, striptags the HTML part
			if(trim($this->description) == '') {
				$plainText = from_html($this->description_html);
				$plainText = strip_tags(br2nl($plainText));
				$plainText = $locale->translateCharset($plainText, 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
				$mail->AltBody = $plainText;
				$this->description = $plainText;
			} else {
				$mail->AltBody = $locale->translateCharset(wordwrap(from_html($this->description), 996), 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
			}
		} else {
			// plain text only
			$mail->IsHTML(false);
			$mail->Body = $locale->translateCharset(wordwrap(from_html($this->description, 996)), 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
		}

		// wp: if plain text version has lines greater than 998, use base64 encoding
		foreach(explode("\n", ($mail->ContentType == "text/html") ? $mail->AltBody : $mail->Body) as $line) {
			if(strlen($line) > 998) { 
				$mail->Encoding = 'base64';
				break;
			}
		}
		////	HANDLE EMAIL FORMAT PREFERENCE
		///////////////////////////////////////////////////////////////////////
        
        ///////////////////////////////////////////////////////////////////////
        ////    SAVE RAW MESSAGE
        $mail->SetMessageType();
        $raw  = $mail->CreateHeader();
        $raw .= $mail->CreateBody();
        $this->raw_source = $raw;
        ////    END SAVE RAW MESSAGE
        ///////////////////////////////////////////////////////////////////////

		$GLOBALS['log']->debug('Email sending --------------------- ');
		
		if($mail->Send()) {
			///////////////////////////////////////////////////////////////////////
			////	INBOUND EMAIL HANDLING
			// mark replied
			if(!empty($_REQUEST['inbound_email_id'])) {
				$ieMail = new Email();
				$ieMail->retrieve($_REQUEST['inbound_email_id']);
				$ieMail->status = 'replied';
				$ieMail->save();
			}
			$GLOBALS['log']->debug(' --------------------- buh bye -- sent successful');
			////	END INBOUND EMAIL HANDLING
			///////////////////////////////////////////////////////////////////////
  			return true;
		}
	    $GLOBALS['log']->fatal("Error emailing:".$mail->ErrorInfo);
		return false;
	}

	function remove_empty_fields(&$arr) {
		$newarr = array();
		
		foreach($arr as $field) {
			$field = trim($field);
			if(empty($field)) {
				continue;
			}
			array_push($newarr,$field);
		}
		return $newarr;
	}

	/**
	 * takes the mess we pass from EditView and tries to create some kind of order
	 * @param array addrs
	 * @param array addrs_ids (from contacts)
	 * @param array addrs_names (from contacts);
	 * @param array addrs_emails (from contacts);
	 * @return array Parsed assoc array to feed to PHPMailer
	 */
	function parse_addrs($addrs, $addrs_ids, $addrs_names, $addrs_emails) {
		$ltgt = array('&lt;','&gt;');
		$gtlt = array('<','>');
		
		$return			= array();
		$addrs				= str_replace($ltgt, '', $addrs);
		$addrs_arr			= explode(";",$addrs);
		$addrs_arr			= $this->remove_empty_fields($addrs_arr);
		$addrs_ids_arr		= explode(";",$addrs_ids);
		$addrs_ids_arr		= $this->remove_empty_fields($addrs_ids_arr);
		$addrs_emails_arr	= explode(";",$addrs_emails);
		$addrs_emails_arr	= $this->remove_empty_fields($addrs_emails_arr);
		$addrs_names_arr	= explode(";",$addrs_names);
		$addrs_names_arr	= $this->remove_empty_fields($addrs_names_arr);












		///////////////////////////////////////////////////////////////////////
		////	HANDLE EMAILS HAND-WRITTEN
		$contactRecipients = array();
		$knownEmails = array();
		
		foreach($addrs_arr as $i => $v) {
			if(trim($v) == "") 
				continue; // skip any "blanks" - will always have 1
			
			$recipient = array();
			
			//// get the email to see if we're dealing with a dupe
			//// what crappy coding
			preg_match("/[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i",$v, $match);

			if(!empty($match[0]) && !in_array(trim($match[0]), $knownEmails)) {
				$knownEmails[] = $match[0];
				$recipient['email'] = $match[0];
				
				//// handle the Display name
				$display = trim(str_replace($match[0], '', $v));
				
				//// only trigger a "displayName" <email@address> when necessary
				if(isset($addrs_names_arr[$i])){
						$recipient['display'] = $addrs_names_arr[$i];
				}
				else if(!empty($display)) {
					$recipient['display'] = $display;
				}
				if(isset($addrs_ids_arr[$i]) && $addrs_emails_arr[$i] == $match[0]){
					$recipient['contact_id'] = $addrs_ids_arr[$i];
				}
				$return[] = $recipient;
			}
		}

		///////////////////////////////////////////////////////////////////////
		////	HANDLE KNOWN CONTACTS
        //RRS: removed to fix bug 8339
		/*foreach($addrs_ids_arr as $k => $id) {
			$recipient = array();
			$recipient['email'] = $addrs_emails_arr[$k];
			$recipient['display'] = $addrs_names_arr[$k];
			//$recipient['contact_id'] = $id;
			if(!in_array($recipient['email'], $knownEmails)) {
				/* cn: bug 8204 - if a contact is Selected, but then 
				 * removed/changed/etc., don't add it to the TO: list
				 */
				/*continue;
			}
			
			$return[] = $recipient;
			$knownEmails[] = $recipient['email'];
		}*/

		return $return;
	}
	
	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}
	
	function listviewACLHelper(){
		$array_assign = parent::listviewACLHelper();
		$is_owner = false;
		if(!empty($this->parent_name)){
			
			if(!empty($this->parent_name_owner)){
				global $current_user;
				$is_owner = $current_user->id == $this->parent_name_owner;
			}
		}
		if(!ACLController::moduleSupportsACL($this->parent_type) || ACLController::checkAccess($this->parent_type, 'view', $is_owner)){
			$array_assign['PARENT'] = 'a';
		} else {
			$array_assign['PARENT'] = 'span';
		}
		$is_owner = false;
		if(!empty($this->contact_name)) {
			if(!empty($this->contact_name_owner)) {
				global $current_user;
				$is_owner = $current_user->id == $this->contact_name_owner;
			}
		}
		if(ACLController::checkAccess('Contacts', 'view', $is_owner)) {
			$array_assign['CONTACT'] = 'a';
		} else {
			$array_assign['CONTACT'] = 'span';
		}
		
		return $array_assign;
	}

	/** 
	 * takes all existing queues and writes it to a cached file for performance
	 */
	function writeToCache($varName, $array) {
		if(!function_exists('mkdir_recursive')) {
			require_once('include/dir_inc.php');
		}
		if(!function_exists('write_array_to_file')) {
			require_once('include/utils/file_utils.php');
		}
		// cache results
		if(!file_exists($this->cachePath) || !file_exists($this->cachePath.'/'.$this->cacheFile)) {
			// create directory if not existent
			mkdir_recursive($this->cachePath, false);
		}
		// write cache file
		write_array_to_file($varName, $array, $this->cachePath.'/'.$this->cacheFile);
	}

	/**
	 * distributes emails to users on Round Robin basis
	 * @param	$userIds	array of users to dist to
	 * @param	$mailIds	array of email ids to push on those users
	 * @return  boolean		true on success
	 */
	function distRoundRobin($userIds, $mailIds) {
		// check if we have a 'lastRobin'
		if(!file_exists($this->cachePath.'/'.$this->cacheFile)) {
			$this->writeToCache('robin', array($userIds[0]));
			$lastRobin = $userIds[0];
		} else {
			require_once($this->cachePath.'/'.$this->cacheFile);
			$lastRobin = $robin[0];
		}
				
		foreach($mailIds as $k => $mailId) {
			$userIdsKeys = array_flip($userIds); // now keys are values
			$thisRobinKey = $userIdsKeys[$lastRobin] + 1;
			if(!empty($userIds[$thisRobinKey])) {
				$thisRobin = $userIds[$thisRobinKey];
				$lastRobin = $userIds[$thisRobinKey];
			} else {
				$thisRobin = $userIds[0];
				$lastRobin = $userIds[0];
			}

			$email = new Email();
			$email->retrieve($mailId);
			if($email->checkPessimisticLock()) {
				$email->assigned_user_id = $thisRobin;
				$email->save();
			} else {
				$GLOBALS['log']->debug('Emails: Round-robin distribution hit a Pessimistic Lock.  Skipping email('.$email->id.').');
			}
		}
		$this->writeToCache('robin', array($lastRobin));
		return true;
	}

	/**
	 * distributes emails to users on Least Busy basis
	 * @param	$userIds	array of users to dist to
	 * @param	$mailIds	array of email ids to push on those users
	 * @return  boolean		true on success
	 */
	function distLeastBusy($userIds, $mailIds) {
		foreach($mailIds as $k => $mailId) {
			$email = new Email();
			$email->retrieve($mailId);
			if($email->checkPessimisticLock()) {
		
				foreach($userIds as $k => $id) {
					$r = $this->db->query("SELECT count(*) AS c FROM emails WHERE assigned_user_id = '.$id.' AND status = 'unread'");
					$a = $this->db->fetchByAssoc($r);
					$counts[$id] = $a['c'];
				}
				asort($counts); // lowest to highest
				$countsKeys = array_flip($counts); // keys now the 'count of items'
				$leastBusy = array_shift($countsKeys); // user id of lowest item count
	
				$email->assigned_user_id = $leastBusy;
				$email->save();
			} else {
				$GLOBALS['log']->debug('Emails: Least-busy distribution hit a Pessimistic Lock.  Skipping email('.$email->id.').');
			}
		}
		
		return true;
	}

	/**
	 * distributes emails to 1 user
	 * @param	$user		users to dist to
	 * @param	$mailIds	array of email ids to push
	 * @return  boolean		true on success
	 */
	function distDirect($user, $mailIds) {
		foreach($mailIds as $k => $mailId) {
			$email = new Email();
			$email->retrieve($mailId);
			if($email->checkPessimisticLock()) {
				$email->assigned_user_id = $user;
				$email->save();
			} else {
				$GLOBALS['log']->debug('Emails: Least-busy distribution hit a Pessimistic Lock.  Skipping email('.$email->id.').');
			}
		}
		
		return true;
	}

	function pickOneButton() {
		global $theme;
		global $mod_strings;
		$out = '<div class="listViewButtons"><input	title="'.$mod_strings['LBL_BUTTON_GRAB_TITLE'].'" 
						accessKey="'.$mod_strings['LBL_BUTTON_GRAB_KEY'].'" 
						class="button"  
						type="button" name="button"
						onClick="window.location=\'index.php?module=Emails&action=Grab\';"
						style="margin-bottom:2px"
						value="  '.$mod_strings['LBL_BUTTON_GRAB'].'  "></div>';
		return $out;
	}
	
	function takeError() {
		global $mod_strings;
		$out = '<br><span class="error">'.$mod_strings['LBL_NO_GRAB_DESC'].'</span><br>';
		return $out;
	}
	
	function checkInbox($type) {
		global $theme;
		global $mod_strings;
		$out = '<div class="listViewButtons"><input	title="'.$mod_strings['LBL_BUTTON_CHECK_TITLE'].'" 
						accessKey="'.$mod_strings['LBL_BUTTON_CHECK_KEY'].'" 
						class="button"  
						type="button" name="button"
						onClick="window.location=\'index.php?module=Emails&action=Check&type='.$type.'\';"
						style="margin-bottom:2px"
						value="  '.$mod_strings['LBL_BUTTON_CHECK'].'  "></div>';
		return $out;
	}


	function distributionForm($where) {
		global $app_list_strings;
		global $app_strings;
		global $mod_strings;
		global $theme;
		global $current_user;
		
		$distribution	= get_select_options_with_id($app_list_strings['dom_email_distribution'], '');
		$_SESSION['distribute_where'] = $where;
		
		$out = '
		<form name="Distribute" id="Distribute" method="POST" action="index.php">';
		$out .= get_form_header($mod_strings['LBL_DIST_TITLE'], '', false);
		$out .= '
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
			<tr>
				<td>
					<script type="text/javascript">
				
				
						function checkDeps(form) {
							return;
						}

						function mySubmit() {
							var assform = document.getElementById("Distribute");
							var select = document.getElementById("userSelect");
							var assign1 = assform.r1.checked;
							var assign2 = assform.r2.checked;
							var dist = assform.dm.value;
							var assign = false;
							var users = false;
							var rules = false;
							var warn1 = "'.$mod_strings['LBL_WARN_NO_USERS'].'";
							var warn2 = "";

							if(assign1 || assign2) {
								assign = true;
								
							}
							
							for(i=0; i<select.options.length; i++) {
								if(select.options[i].selected == true) {
									users = true;
									warn1 = "";
								}
							}
							
							if(dist != "") {
								rules = true;
							} else {
								warn2 = "'.$mod_strings['LBL_WARN_NO_DIST'].'";
							}
							
							if(assign && users && rules) {

								if(document.getElementById("r1").checked) {
									var mu = document.getElementById("MassUpdate");
									var grabbed = "";
							
									for(i=0; i<mu.elements.length; i++) {
										if(mu.elements[i].type == "checkbox" && mu.elements[i].checked && mu.elements[i].name.value != "massall") {
											if(grabbed != "") { grabbed += "::"; }
											grabbed += mu.elements[i].value;
										}
									}
									var formgrab = document.getElementById("grabbed");
									formgrab.value = grabbed;
								}
								assform.submit();
							} else {
								alert("'.$mod_strings['LBL_ASSIGN_WARN'].'" + "\n" + warn1 + "\n" + warn2);
							}
						}

						function submitDelete() {
							if(document.getElementById("r1").checked) {
								var mu = document.getElementById("MassUpdate");
								var grabbed = "";
						
								for(i=0; i<mu.elements.length; i++) {
									if(mu.elements[i].type == "checkbox" && mu.elements[i].checked && mu.elements[i].name != "massall") {
										if(grabbed != "") { grabbed += "::"; }
										grabbed += mu.elements[i].value;
									}
								}
								var formgrab = document.getElementById("grabbed");
								formgrab.value = grabbed;
							}
							if(grabbed == "") {
								alert("'.$mod_strings['LBL_MASS_DELETE_ERROR'].'");
							} else {
								document.getElementById("Distribute").submit();
							}	
						}
				
					</script>
						<input type="hidden" name="module" value="Emails">
						<input type="hidden" name="action" id="action" value="Distribute">
						<input type="hidden" name="grabbed" id="grabbed">
						
					<table cellpadding="1" cellspacing="0" width="100%" border="0" class="tabForm">
						<tr height="20">
							<td scope="col" width="15%" class="dataLabel" NOWRAP align="left" valign="middle">
								'.$mod_strings['LBL_USE'].'&nbsp;
								<input id="r1" type="radio" CHECKED style="border:0px solid #000000" name="use" value="checked" onclick="checkDeps(this.form);">&nbsp;'.$mod_strings['LBL_USE_CHECKED'].'
								<input id="r2" type="radio" style="border:0px solid #000000" name="use" value="all" onclick="checkDeps(this.form);">&nbsp;'.$mod_strings['LBL_USE_ALL'].'
							</select>
							</td>

							<td scope="col" width="25%" class="dataLabel" NOWRAP align="center">
								&nbsp;'.$mod_strings['LBL_TO'].'&nbsp;';
					$out .= $this->userSelectTable();
					$out .=	'</td>
							<td scope="col" width="15%" class="dataLabel" NOWRAP align="left">
								&nbsp;'.$mod_strings['LBL_USING_RULES'].'&nbsp;
								<select name="distribute_method" id="dm" onChange="checkDeps(this.form);">'.$distribution.'</select>
							</td>

							<td scope="col" width="50%" class="dataLabel" NOWRAP align="right">
								<input title="'.$mod_strings['LBL_BUTTON_DISTRIBUTE_TITLE'].'"
									id="dist_button"  
									accessKey="'.$mod_strings['LBL_BUTTON_DISTRIBUTE_KEY'].'" 
									class="button" onClick="this.form.action.value=\'Distribute\'; this.form.module.value=\'Emails\'; mySubmit();" 
									type="button" name="button"
									value="  '.$mod_strings['LBL_BUTTON_DISTRIBUTE'].'  ">';
					if(is_admin($current_user)) {
						$out .= '<div class="listViewButtons">
								<input title="'.$app_strings['LBL_DELETE_BUTTON_TITLE'].'"
									id="del_button"  
									accessKey="'.$app_strings['LBL_DELETE_BUTTON_KEY'].'" 
									class="button" onClick="this.form.action.value=\'MassDelete\'; this.form.module.value=\'Emails\'; submitDelete();" 
									type="button" name="del_button"
									value="  '.$app_strings['LBL_DELETE_BUTTON'].'  "></div>'; 	
					}
					
					$out .= '
							</td>
						</tr>
					</table>
					
				</td>
			</tr>
		</table>
		</form>';
	return $out;
	}

	function userSelectTable() {
		global $theme;
		global $mod_strings;
		
		$colspan = 1;
		$setTeamUserFunction = '';
		








































			
		// get users
		$r = $this->db->query("SELECT users.id, users.user_name, users.first_name, users.last_name FROM users WHERE deleted=0 AND status = 'Active' ORDER BY users.last_name, users.first_name");

		$userTable = '<table cellpadding="0" cellspacing="0" border="0">';
		$userTable .= '<tr><td colspan="2"><b>'.$mod_strings['LBL_USER_SELECT'].'</b></td></tr>';
		$userTable .= '<tr><td><input type="checkbox" style="border:0px solid #000000" onClick="toggleAll(this); setCheckMark(); checkDeps(this.form);"></td> <td>'.$mod_strings['LBL_TOGGLE_ALL'].'</td></tr>';
		$userTable .= '<tr><td colspan="2"><select name="users[]" id="userSelect" multiple size="12">';
		
		while($a = $this->db->fetchByAssoc($r)) {
			$userTable .= '<option value="'.$a['id'].'" id="'.$a['id'].'">'.$a['first_name'].' '.$a['last_name'].'</option>';	
		}
		$userTable .= '</select></td></tr>';
		$userTable .= '</table>';
		
		$out  = '<script type="text/javascript">';
		$out .= $setTeamUserFunction; 
		$out .= '
					function setCheckMark() {
						var select = document.getElementById("userSelect");
					
						for(i=0 ; i<select.options.length; i++) {
							if(select.options[i].selected == true) {
								document.getElementById("checkMark").style.display="";
								return;
							}
						}
						
						document.getElementById("checkMark").style.display="none";
						return;
					}
									
					function showUserSelect() {
						var targetTable = document.getElementById("user_select");
						targetTable.style.visibility="visible";

						return;
					}
					function hideUserSelect() {
						var targetTable = document.getElementById("user_select");
						targetTable.style.visibility="hidden";
						return;
					}
					function toggleAll(toggle) {
						if(toggle.checked) {
							var stat = true;
						} else {
							var stat = false;
						}
						var form = document.getElementById("userSelect");
						for(i=0; i<form.options.length; i++) {
							form.options[i].selected = stat;
						}
					}


				</script>
			<span id="showUsersDiv" style="position:relative;">
				<a href="#" id="showUsers" onClick="javascript:showUserSelect();">
					<img border="0" src="themes/'.$theme.'/images/Users.gif"></a>&nbsp;
				<a href="#" id="showUsers" onClick="javascript:showUserSelect();">
					<span style="display:none;" id="checkMark"><img border="0" src="themes/'.$theme.'/images/check_inline.gif"></span>
				</a>
				
				
				<div id="user_select" style="width:200px;position:absolute;left:2;top:2;visibility:hidden;z-index:1000;">
				<table cellpadding="0" cellspacing="0" border="0" class="listView">
					<tr height="20">
						<td class="listViewThS1" colspan="'.$colspan.'" id="hiddenhead" onClick="hideUserSelect();" onMouseOver="this.style.border = \'outset red 1px\';" onMouseOut="this.style.border = \'inset white 0px\';this.style.borderBottom = \'inset red 1px\';">
							<a href="#" onClick="javascript:hideUserSelect();"><img border="0" src="themes/'.$theme.'/images/close.gif"></a>
							'.$mod_strings['LBL_USER_SELECT'].'
						</td>
					</tr>
					<tr>';
//<td valign="middle" height="30" class="listViewThS1" colspan="'.$colspan.'" id="hiddenhead" onClick="hideUserSelect();" onMouseOver="this.style.border = \'outset red 1px\';" onMouseOut="this.style.border = \'inset white 0px\';this.style.borderBottom = \'inset red 1px\';">





		$out .=	'		<td style="padding:5px" class="oddListRowS1" bgcolor="#fdfdfd" valign="top" align="left" style="left:0;top:0;">
							'.$userTable.'
						</td>
					</tr>
				</table></div>
			</span>';
		return $out;
	}
	
	
    function quickCreateForm() {
        global $mod_strings, $app_strings, $image_path, $currentModule, $current_language, $theme;
        
        // Coming from the home page via Dashlets
        if($currentModule != 'Email') $mod_strings = return_module_language($current_language, 'Emails');
         

        return "<a id='$this->id' onclick='return quick_create_overlib(\"{$this->id}\", \"{$theme}\");' href=\"#\" class=\"listViewPaginationLinkS1\">".get_image($image_path."advanced_search","alt='".$mod_strings['LBL_QUICK_CREATE']."'  border='0' align='absmiddle'")."&nbsp;".$mod_strings['LBL_QUICK_CREATE']."</a>";
        //cases,leads, contacts,bugs,tasks
    }    

	function getStartPage($uri) {
		if(strpos($uri, '&')) { // "&" to ensure that we can explode the GET vars - else we're gonna trigger a Notice error
			$serial = substr($uri, (strpos($uri, '?')+1), strlen($uri));
			$exUri = explode('&', $serial);
			$start = array('module' => '', 'action' => '', 'group' => '', 'record' => '');

			foreach($exUri as $k => $pair) {
				$exPair = explode('=', $pair);
				$start[$exPair[0]] = $exPair[1];
			} 
		
			return $start;
		} else {
			return 'index.php';
		}	
	}
	
	
	function getSystemDefaultEmail() {
		$email = array();
		
		$r1 = $this->db->query('SELECT config.value FROM config WHERE name=\'fromaddress\'');
		$r2 = $this->db->query('SELECT config.value FROM config WHERE name=\'fromname\'');
		$a1 = $this->db->fetchByAssoc($r1);
		$a2 = $this->db->fetchByAssoc($r2);
		
		$email['email'] = $a1['value'];
		$email['name']  = $a2['value'];
		
		return $email;
	}


	function getMailboxDefaultEmail() {
		$email = array();
		
		$r = $this->db->query('SELECT inbound_email.stored_options FROM inbound_email WHERE id = \''.$this->mailbox_id.'\'');
		$a = $this->db->fetchByAssoc($r);
		
		if(empty($a['stored_options'])) {
			$email = $this->getSystemDefaultEmail();
		} else {
			$storedOptions = unserialize(base64_decode($a['stored_options']));
			if(isset($storedOptions['from_addr']) && !empty($storedOptions['from_addr'])) {
				$email['email'] = $storedOptions['from_addr'];
				$email['name']  = $storedOptions['from_name'];
			} else {
				$email = $this->getSystemDefaultEmail();
			}
		}
		return $email;
	}

	/**
	 * takes a long TO: string of emails and returns the first appended by an
	 * elipse
	 */	
	function trimLongTo($str) {
		if(strpos($str, ',')) {
			$exStr = explode(',', $str);
			return $exStr[0].'...';
		} elseif(strpos($str, ';')) {
			$exStr = explode(';', $str);
			return $exStr[0].'...';
		} else {
			return $str;
		}	
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
	
	////	END HELPER FUNCTIONS
	///////////////////////////////////////////////////////////////////////////
	
	///////////////////////////////////////////////////////////////////////////
	////	SUGARBEAN OVERRIDES
	/**
	 * overrides SugarBean's - for debugging
	 */
	function save($check_notify = false) {
		$GLOBALS['log']->debug('-------------------------------> Email called save()');	
		parent::save($check_notify);
	}
	
	function get_list_view_data() {
		global $app_list_strings;
		global $theme;
		global $current_user;
		global $timedate;
		
		$email_fields = $this->get_list_view_array();
		$mod_strings = return_module_language('', 'Emails'); // hard-coding for Home screen ListView
		
		if($this->status != 'replied') {
			$email_fields['QUICK_REPLY'] = '<a class="listViewTdLinkS1" href="index.php?module=Emails&action=EditView&type=out&inbound_email_id='.$this->id.'">'.$mod_strings['LNK_QUICK_REPLY'].'</a>';
		} else {
			$email_fields['QUICK_REPLY'] = $mod_strings['LBL_REPLIED'];
		}
		
		if (!empty($this->parent_type)) {
			$email_fields['PARENT_MODULE'] = $this->parent_type;
		} else {
			switch($this->intent) {
				case 'support':
					$email_fields['CREATE_RELATED'] = '<a href="index.php?module=Cases&action=EditView&inbound_email_id='.$this->id.'" class="listViewTdLinkS1"><img border="0" src="themes/'.$theme.'/images/CreateCases.gif">'.$mod_strings['LBL_CREATE_CASE'].'</a>';
				break;
				
				case 'sales':
					$email_fields['CREATE_RELATED'] = '<a href="index.php?module=Leads&action=EditView&inbound_email_id='.$this->id.'" class="listViewTdLinkS1"><img border="0" src="themes/'.$theme.'/images/CreateLeads.gif">'.$mod_strings['LBL_CREATE_LEAD'].'</a>';
				break;
				
				case 'contact':
					$email_fields['CREATE_RELATED'] = '<a href="index.php?module=Contacts&action=EditView&inbound_email_id='.$this->id.'" class="listViewTdLinkS1"><img border="0" src="themes/'.$theme.'/images/CreateContacts.gif">'.$mod_strings['LBL_CREATE_CONTACT'].'</a>';
				break;
				
				case 'bug':
					$email_fields['CREATE_RELATED'] = '<a href="index.php?module=Bugs&action=EditView&inbound_email_id='.$this->id.'" class="listViewTdLinkS1"><img border="0" src="themes/'.$theme.'/images/CreateBugs.gif">'.$mod_strings['LBL_CREATE_BUG'].'</a>';
				break;

				case 'task':
					$email_fields['CREATE_RELATED'] = '<a href="index.php?module=Tasks&action=EditView&inbound_email_id='.$this->id.'" class="listViewTdLinkS1"><img border="0" src="themes/'.$theme.'/images/CreateTasks.gif">'.$mod_strings['LBL_CREATE_TASK'].'</a>';
				break;
				
				case 'bounce':
				break;
				
				case 'pick':
				// break;

				case 'info':
				//break;

				default:
					$email_fields['CREATE_RELATED'] = $this->quickCreateForm();					
				break;
			}
			 
		}

		$email_fields['CONTACT_NAME']		= empty($this->contact_name) ? '</a>'.$this->trimLongTo($this->from_addr).'<a>' : $this->contact_name;//return_name($email_fields,'FIRST_NAME','LAST_NAME');
		$email_fields['CONTACT_ID']			= empty($this->contact_id) ? '' : $this->contact_id;
		$email_fields['ATTACHMENT_IMAGE']	= $this->attachment_image;

		//$email_fields['TYPE_NAME'] = $this->type;
		global $mod_strings;
    	if(isset($this->type_name))
      	$email_fields['TYPE_NAME'] = $this->type_name;
		
		return $email_fields;
	}

	function get_summary_text() {
		return "$this->name";
	}
    


	function create_list_query($order_by, $where, $show_deleted = 0) {
        $custom_join = $this->custom_fields->getJOIN();

		if($this->db->dbType == 'oci8') {



		}elseif($this->db->dbType == 'mysql') {
			$concat = "CONCAT(emails.date_start, CONCAT(' ', emails.time_start))";
		}elseif($this->db->dbType == 'mssql') {
			
			$concat = "emails.date_start + " . ' ' . " emails.time_start";
		}


		$query = "SELECT ".$this->table_name.".*, {$concat} date_sent,
					users.user_name as assigned_user_name,
					contacts.first_name, contacts.last_name";






    	if($custom_join){
			$query .= $custom_join['select'];
		}
    	$query .= " FROM emails";
    	$query .= " LEFT JOIN emails_contacts ec ON emails.id = ec.email_id";
    	$query .= " LEFT JOIN contacts ON ec.contact_id = contacts.id ";  







    	$query .= " LEFT JOIN users
    				ON emails.assigned_user_id=users.id ";

		if($custom_join){
			$query .= $custom_join['join'];
		}

		$where_auto = ' 1=1 ';
		if($show_deleted == 0){
			$where_auto = " emails.deleted=0 ";	
		}else if($show_deleted == 1){
			$where_auto = " emails.deleted=1 ";	
		}
	   
        if($where != "")
			$query .= "WHERE $where AND ".$where_auto;
		else
			$query .= "WHERE ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY date_sent DESC";
		return $query;
	}

	function create_export_query(&$order_by, &$where) {
		$contact_required = ereg("contacts", $where);
		$custom_join = $this->custom_fields->getJOIN();
		
		if($contact_required) {
			$query = "SELECT emails.*, contacts.first_name, contacts.last_name";



			if($custom_join) {
				$query .= $custom_join['select'];
			}

			$query .= " FROM contacts, emails, emails_contacts ";
			$where_auto = "emails_contacts.contact_id = contacts.id AND emails_contacts.email_id = emails.id AND emails.deleted=0 AND contacts.deleted=0";
		} else {
			$query = 'SELECT emails.*';



			if($custom_join) {
				$query .= $custom_join['select'];
			}
            
            $query .= ' FROM emails ';
            $where_auto = "emails.deleted=0";
		}






		if($custom_join){
			$query .= $custom_join['join'];
		}

		if($where != "")
			$query .= "where $where AND ".$where_auto;
        else
			$query .= "where ".$where_auto;

        if($order_by != "")
			$query .= " ORDER BY $order_by";
        else
			$query .= " ORDER BY emails.name";
        return $query;
    }



	function fill_in_additional_list_fields() {
		
		$this->fill_in_additional_detail_fields();
		
		///////////////////////////////////////////////////////////////////////
		//populate attachment_image, used to display attachment icon.
		$query =  "select 1 from notes where notes.parent_id = '$this->id' and notes.deleted = 0";
		$result =$this->db->query($query,true," Error filling in additional list fields: ");

		$row = $this->db->fetchByAssoc($result);

		global $theme;

		if ($row !=null) {
			$this->attachment_image = get_image('themes/'.$theme.'/images/attachment',"","","");
		} else {
			$this->attachment_image = get_image('include/images/blank',"","","");
		}
		///////////////////////////////////////////////////////////////////////
	}

	function fill_in_additional_detail_fields() {
		global $app_list_strings,$mod_strings;
		// Fill in the assigned_user_name
		$this->assigned_user_name = get_assigned_user_name($this->assigned_user_id, '');




		$query  = "SELECT contacts.first_name, contacts.last_name, contacts.phone_work, contacts.email1, contacts.id, contacts.assigned_user_id contact_name_owner, 'Contacts' contact_name_mod FROM contacts, emails_contacts ";
		$query .= "WHERE emails_contacts.contact_id=contacts.id AND emails_contacts.email_id='$this->id' AND emails_contacts.deleted=0 AND contacts.deleted=0";
		$result =$this->db->query($query,true," Error filling in additional detail fields: ");

		// Get the id and the name.
		$row = $this->db->fetchByAssoc($result);

		$GLOBALS['log']->info($row);

		if($row != null)
		{
			$this->contact_name = return_name($row, 'first_name', 'last_name');
			$this->contact_phone = $row['phone_work'];
			$this->contact_id = $row['id'];
			$this->contact_email = $row['email1'];
			$this->contact_name_owner = $row['contact_name_owner'];
			$this->contact_name_mod = $row['contact_name_mod'];
			$GLOBALS['log']->debug("Call($this->id): contact_name = $this->contact_name");
			$GLOBALS['log']->debug("Call($this->id): contact_phone = $this->contact_phone");
			$GLOBALS['log']->debug("Call($this->id): contact_id = $this->contact_id");
			$GLOBALS['log']->debug("Call($this->id): contact_email1 = $this->contact_email");
		}
		else {
			$this->contact_name = '';
			$this->contact_phone = '';
			$this->contact_id = '';
			$this->contact_email = '';
			$this->contact_name_owner = '';
			$this->contact_name_mod = '';
			$GLOBALS['log']->debug("Call($this->id): contact_name = $this->contact_name");
			$GLOBALS['log']->debug("Call($this->id): contact_phone = $this->contact_phone");
			$GLOBALS['log']->debug("Call($this->id): contact_id = $this->contact_id");
			$GLOBALS['log']->debug("Call($this->id): contact_email1 = $this->contact_email");
		}

		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);

		$this->link_action = 'DetailView';

		if(!empty($this->type)) {
			if($this->type == 'out' && $this->status == 'send_error') {
				$this->type_name = $mod_strings['LBL_NOT_SENT'];
			} else {
				$this->type_name = $app_list_strings['dom_email_types'][$this->type];
			}
	
			if(($this->type == 'out' && $this->status == 'send_error') || $this->type == 'draft') {
				$this->link_action = 'EditView';
			}
		}
		
		//todo this  isset( $app_list_strings['dom_email_status'][$this->status]) is hack for 3261.
		if(!empty($this->status) && isset( $app_list_strings['dom_email_status'][$this->status])) {
			$this->status_name = $app_list_strings['dom_email_status'][$this->status];	
		}

		if ( empty($this->name ) &&  empty($_REQUEST['record'])) {
			$this->name = '(no subject)';
		}

		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_parent_fields() {
		global  $app_strings;
		$this->parent_name_owner = '';
		$this->parent_name_mod = $this->parent_type;
		
		///////////////////////////////////////////////////////////////////////
		////	SPLIT INTO TWO TYPES: 
		////	1. NAME IS CONCATENATION OF FIRST+LAST 
		////	2. JUST NAME
		
		if($this->parent_type == "Opportunities") {
			require_once("modules/Opportunities/Opportunity.php");
			$parent = new Opportunity();
			$query = "SELECT name, assigned_user_id from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
				$this->parent_name_owner = stripslashes($row['assigned_user_id']);
			}
		}
		elseif ($this->parent_type == "Tasks") {
			require_once("modules/Tasks/Task.php");
			$parent = new Task();
			$query = "SELECT name, assigned_user_id  from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
				$this->parent_name_owner = stripslashes($row['assigned_user_id']);
			}
		}
		elseif ($this->parent_type == "Cases") {
			require_once("modules/Cases/Case.php");
			$parent = new aCase();
			$query = "SELECT name, assigned_user_id  from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
				$this->parent_name_owner = stripslashes($row['assigned_user_id']);
			}
		}
		elseif ($this->parent_type == "Bugs") {
			require_once("modules/Bugs/Bug.php");
			$parent = new Bug();
			$query = "SELECT name, assigned_user_id  from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
				$this->parent_name_owner = stripslashes($row['assigned_user_id']);
			}
		}
		elseif ($this->parent_type == "Project") {
			require_once("modules/Project/Project.php");
			$parent = new Project();
			$query = "SELECT name, assigned_user_id  from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
				$this->parent_name_owner = stripslashes($row['assigned_user_id']);
			}
		}
		elseif ($this->parent_type == "ProjectTask") {
			require_once("modules/ProjectTask/ProjectTask.php");
			$parent = new ProjectTask();
			$query = "SELECT name, assigned_user_id  from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
				$this->parent_name_owner = stripslashes($row['assigned_user_id']);
			}
		}
		elseif ($this->parent_type == "Accounts") {
			require_once("modules/Accounts/Account.php");
			$parent = new Account();
			$query = "SELECT name, assigned_user_id  from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->parent_name = stripslashes($row['name']);
				$this->parent_name_owner = stripslashes($row['assigned_user_id']);
			}
		}
		elseif ($this->parent_type == "Leads") {
			require_once("modules/Leads/Lead.php");
			$parent = new Lead();
			$query = "SELECT first_name, last_name, assigned_user_id  from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, "ERROR CREATING ADDITIONAL FIELDS");


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				$this->parent_name = '';
				if ($row['first_name'] != '') $this->parent_name .= stripslashes($row['first_name']). ' ';
				if ($row['last_name'] != '') $this->parent_name .= stripslashes($row['last_name']);
				$this->parent_name_owner = stripslashes($row['assigned_user_id']);
			}
		}
		elseif ($this->parent_type == "Contacts") {
			require_once("modules/Contacts/Contact.php");
			$parent = new Contact();
			$query = "SELECT first_name, last_name, assigned_user_id  from $parent->table_name where id = '$this->parent_id'";

			$result =$this->db->query($query,true, "ERROR CREATING ADDITIONAL FIELDS");


			// Get the id and the name.

			$row = $this->db->fetchByAssoc($result);


			if($row != null)
			{
				$this->parent_name = '';
				if ($row['first_name'] != '') $this->parent_name .= stripslashes($row['first_name']). ' ';
				if ($row['last_name'] != '') $this->parent_name .= stripslashes($row['last_name']);
				$this->parent_name_owner = stripslashes($row['assigned_user_id']);
			}
		}
























		else {
			$this->parent_name = '';
		}
	}	
	////	END SUGARBEAN OVERRIDES
	///////////////////////////////////////////////////////////////////////////
} // end class def
?>
