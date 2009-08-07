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
 * $Header: /var/cvsroot/sugarcrm/include/SugarPHPMailer.php,v 1.13 2006/08/29 20:36:12 chris Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
include('include/phpmailer/class.phpmailer.php');

class SugarPHPMailer extends PHPMailer {
	var $preppedForOutbound = false;
	
	/**
	 * Sole constructor
	 */
	function SugarPHPMailer() {
		global $locale;
		
		$this->SetLanguage('en', 'include/phpmailer/language/');
		$this->PluginDir	= 'include/phpmailer/';
		$this->Mailer		= 'sendmail';
        // cn: i18n
        $this->CharSet		= $locale->getPrecedentPreference('default_email_charset');
		$this->Encoding		= 'quoted-printable';
        $this->IsHTML(false);  // default to plain-text email
        $this->WordWrap		= 996;



	}
	
	/**
	 * handles Charset translation for all visual parts of the email.
	 */
	function prepForOutbound() {
		global $locale;
		
		if($this->preppedForOutbound == false) {
			$this->preppedForOutbound = true; // flag so we don't redo this
			
			// body text
			$this->Body				= $locale->translateCharset($this->Body, 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
			$this->AltBody			= $locale->translateCharset($this->AltBody, 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
			$this->Subject			= $locale->translateCharset($this->Subject, 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));

			// Headers /////////////////////////////////
			$this->From				= $locale->translateCharsetMIME($this->From, 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
			$this->FromName			= $locale->translateCharsetMIME($this->FromName, 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
			$this->Sender			= $locale->translateCharsetMIME($this->Sender, 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
			// TO: fields
			foreach($this->to as $k => $toArr) {
				$this->to[$k][0]	= $locale->translateCharsetMIME($toArr[0], 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
				$this->to[$k][1]	= $locale->translateCharsetMIME($toArr[1], 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
			}
			// CC: fields
			foreach($this->cc as $k => $ccAddr) {
				$this->cc[$k][0]	= $locale->translateCharsetMIME($ccAddr[0], 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
				$this->cc[$k][1]	= $locale->translateCharsetMIME($ccAddr[1], 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
			}
			// BCC: fields
			foreach($this->bcc as $k => $bccAddr) {
				$this->bcc[$k][0]	= $locale->translateCharsetMIME($bccAddr[0], 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
				$this->bcc[$k][1]	= $locale->translateCharsetMIME($bccAddr[1], 'UTF-8', $locale->getPrecedentPreference('default_email_charset'));
			}
		}
		
	}
	
	/**
	 * @param notes	array of note beans
	 */
	function handleAttachments($notes) {
		// cn: bug 4864 - reusing same SugarPHPMailer class, need to clear attachments
		$this->ClearAttachments();
		require_once('include/upload_file.php');
		
		foreach($notes as $note) {
				$mime_type = 'text/plain';
				$file_location = '';
				$filename = '';

				if($note->object_name == 'Note') {
					if (! empty($note->file->temp_file_location) && is_file($note->file->temp_file_location)) {
						$file_location = $note->file->temp_file_location;
						$filename = $note->file->original_file_name;
						$mime_type = $note->file->mime_type;
					} else {
						$file_location = rawurldecode(UploadFile::get_file_path($note->filename,$note->id));
						$filename = $note->id.$note->filename;
						$mime_type = $note->file_mime_type;
					}
				} elseif($note->object_name == 'DocumentRevision') { // from Documents
					$filename = $note->id.$note->filename;
					$file_location = getcwd().'/cache/upload/'.$filename;
					$mime_type = $note->file_mime_type;
				}
	
				$filename = substr($filename, 36, strlen($filename)); // strip GUID	for PHPMailer class to name outbound file
				$this->AddAttachment($file_location, $filename, 'base64', $mime_type);
			}
	}		
	
	/**
	 * overloads class.phpmailer's SetError() method so that we can log errors in sugarcrm.log
	 * 
	 */
	function SetError($msg) {
		$GLOBALS['log']->fatal("SugarPHPMailer encountered an error: {$msg}");
		parent::SetError($msg);
	}
	
	/**
	 * overloads PHPMailer's EncodeHeader method to correctly use mb_encode_header() if/when available
	 * @param string header
	 * @return string encoded
	 */
	function EncodeHeader($string, $position='text') {
		global $locale;
		
		if(function_exists('mb_encode_header')) {
			return mb_encode_mimeheader($string, $locale->getPrecedentPreference('default_export_charset'));
		} else {
			return parent::EncodeHeader($string, $position);
		}
	}
} // end class definition
?>
