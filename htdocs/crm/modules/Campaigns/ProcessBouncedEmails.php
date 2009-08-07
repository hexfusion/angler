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
 * $Id: ProcessBouncedEmails.php,v 1.6 2006/06/06 17:57:56 majed Exp $
 * Description:
 ********************************************************************************/
//find all mailboxes of type bounce.
function campaign_process_bounced_emails(&$email, &$email_header) {

	if (preg_match('/MAILER-DAEMON/i',$email_header->fromaddress)) {
		//do we have the identifier tag in the email?
		
		$matches=array();
		if (preg_match('/removeme.php\?identifier=[a-z0-9\-]*/',$email->description,$matches)) {
			$identifiers=preg_split('/removeme.php\?identifier=/',$matches[0],-1,PREG_SPLIT_NO_EMPTY);
			if (!empty($identifiers)) {
				
				//array should have only one element in it.
				$identifier=trim($identifiers[0]);
				if (!class_exists('CampaignLog')) {
					require_once('modules/CampaignLog/CampaignLog.php');
				}
				$targeted = new CampaignLog();
				$where="campaign_log.activity_type='targeted' and campaign_log.target_tracker_key='{$identifier}'";
				$query=$targeted->create_list_query('',$where);
				$result=$targeted->db->query($query);
				$row=$targeted->db->fetchByAssoc($result);
				if (!empty($row)) {
					//found entry

					//do not create another campaign_log record is we already have an
					//invalid email or send error entry for this tracker key.
					$query_log = "select * from campaign_log where target_tracker_key='{$row['target_tracker_key']}'"; 
					$query_log .=" and (activity_type='invalid email' or activity_type='send error')";

					$result_log=$targeted->db->query($query_log);
					$row_log=$targeted->db->fetchByAssoc($result_log);

					if (empty($row_log)) {
						$bounce = new CampaignLog();

						$bounce->campaign_id=$row['campaign_id'];
						$bounce->target_tracker_key=$row['target_tracker_key'];
						$bounce->target_id= $row['target_id'];
						$bounce->target_type=$row['target_type'];
						$bounce->list_id=$row['list_id'];

						$bounce->activity_date=$email->date_created;
						$bounce->related_type='Emails';
						$bounce->related_id= $email->id;
					
						//do we have the phrase permanent error in the email body.
						if (preg_match('/permanent[ ]*error/',$email->description)) {
							//invalid email address
							$bounce->activity_type='invalid email';
						} else {
							//other -bounced email.	
							$bounce->activity_type='send error';
						}			
						$return_id=$bounce->save();
					}				
				} else {
					$GLOBALS['log']->info("Warning: skipping bounced email with this tracker_key(identifier) in the message body ".$identifier);
				}			
		} else {
			//todo mark the email address as invalid. search for prospects/leads/contact associated
			//with this email address and set the invalid_email flag... also make email available.
		}
	}  else {
		$GLOBALS['log']->info("Warning: skipping bounced email because it does not have the removeme link.");	  	
  	}
  } else {
	$GLOBALS['log']->info("Warning: skipping bounced email because the sender is not MAILER-DAEMON.");
  }
}
?>
