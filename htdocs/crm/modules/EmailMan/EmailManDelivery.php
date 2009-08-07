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
require_once('modules/EmailMan/EmailMan.php');
require_once('modules/Users/User.php');
require_once('include/SugarPHPMailer.php');
require_once("modules/Administration/Administration.php");
$test=false;
if (isset($_REQUEST['mode']) && $_REQUEST['mode']=='test') {
	$test=true;
}
if (isset($_REQUEST['send_all']) && $_REQUEST['send_all']== true) {
	$send_all= true;
}
else  {
	$send_all=false; //if set to true email delivery will continue..to run until all email have been delivered.
}
$mail = new SugarPHPMailer();
$admin = new Administration();
$admin->retrieveSettings();
if (isset($admin->settings['massemailer_campaign_emails_per_run'])) {
	$max_emails_per_run=$admin->settings['massemailer_campaign_emails_per_run'];
}
if (empty($max_emails_per_run)) {
	$max_emails_per_run=500;//default
}

$emailsPerSecond = 10;
if ($admin->settings['mail_sendtype'] == "SMTP") {
	$mail->Host = $admin->settings['mail_smtpserver'];
	$mail->Port = $admin->settings['mail_smtpport'];	
	

	if ($admin->settings['mail_smtpauth_req']) {
		$mail->SMTPAuth = TRUE;
		$mail->Username = $admin->settings['mail_smtpuser'];
		$mail->Password = $admin->settings['mail_smtppass'];
	}
	$mail->Mailer   = "smtp";
	$mail->SMTPKeepAlive = true;
} else {
	$mail->mailer='sendmail'; 
}

$mail->From     = "no-reply@example.com";
$mail->FromName = "no-reply";
$mail->ContentType="text/html";

$campaign_id=null;
if (isset($_REQUEST['campaign_id']) && !empty($_REQUEST['campaign_id'])) {
	$campaign_id=$_REQUEST['campaign_id'];
}

$db = & PearDatabase::getInstance();
$emailman = new EmailMan();

$select_query =" SELECT *";
$select_query.=" FROM $emailman->table_name";
$select_query.=" WHERE send_date_time <= ". db_convert("'".gmdate('Y-m-d H:i:s')."'" ,"datetime");
$select_query.=" AND (in_queue ='0' OR ( in_queue ='1' AND in_queue_date <= " .db_convert("'". gmdate('Y-m-d H:i:s', strtotime("-1 day")) ."'" ,"datetime")."))"; 
if (!empty($campaign_id)) {
	$select_query.=" AND campaign_id='{$campaign_id}'";
}
$select_query.=" ORDER BY campaign_id,user_id, list_id";
do {
	$no_items_in_queue=true;	
	
	$result = $db->limitQuery($select_query,0,$max_emails_per_run);
	global $current_user;
	if(isset($current_user)){
		$temp_user = $current_user;
	}	
	$current_user = new User();
	$startTime = microtime();
	
	while($row = $db->fetchByAssoc($result)){
		$no_items_in_queue=false;	
		
		//fetch user that scheduled the campaign.
		if(empty($current_user) or $row['user_id'] != $current_user->id){
			$current_user->retrieve($row['user_id']);
		}
	
		foreach($row as $name=>$value){
			$emailman->$name = $value;
		}
	
		//for the campaign find the supression lists.
		if (!isset($current_campaign_id) or empty($current_campaign_id) or $current_campaign_id != $row['campaign_id']) {
			$current_campaign_id= $row['campaign_id'];

			//is this email address suppressed?
			$plc_query= " SELECT prospect_list_id, prospect_lists.list_type,prospect_lists.domain_name FROM prospect_list_campaigns ";
			$plc_query.=" LEFT JOIN prospect_lists on prospect_lists.id = prospect_list_campaigns.prospect_list_id";
			$plc_query.=" WHERE ";
			$plc_query.=" campaign_id='{$current_campaign_id}' ";
			$plc_query.=" AND prospect_lists.list_type in ('exempt_address','exempt_domain')";
			$plc_query.=" AND prospect_list_campaigns.deleted=0";
			$plc_query.=" AND prospect_lists.deleted=0";
			
			$emailman->restricted_domains=array();
			$emailman->restricted_addresses=array();
			
			$result1=$db->query($plc_query);
			while($row1 = $db->fetchByAssoc($result1)){
				if ($row1['list_type']=='exempt_domain') {
					$emailman->restricted_domains[strtolower($row1['domain_name'])]=1;
				} else {
	   			    //find email address of targets in this prospect list.	
				 	$email_query= " select email1 from prospects  join prospect_lists_prospects on related_id = prospects.id and related_type='Prospects' and prospect_list_id = '{$row1['prospect_list_id']}' and prospect_lists_prospects.deleted=0";
				 	$email_query.=" UNION"; 
				 	$email_query.=" select email1 from contacts join prospect_lists_prospects on related_id = contacts.id and related_type='Contacts' and prospect_list_id = '{$row1['prospect_list_id']}' and prospect_lists_prospects.deleted=0";
				 	$email_query.=" union"; 
				 	$email_query.=" select email1 from leads join prospect_lists_prospects on related_id = leads.id and related_type='Leads' and prospect_list_id = '{$row1['prospect_list_id']}' and prospect_lists_prospects.deleted=0";
					$email_query.=" union ";
					$email_query.=" select email1 from users join prospect_lists_prospects on related_id = users.id and related_type='Users' and prospect_list_id = '{$row1['prospect_list_id']}' and prospect_lists_prospects.deleted=0";

					$email_query_result=$db->query($email_query);
					while($email_address = $db->fetchByAssoc($email_query_result)){
						$emailman->restricted_addresses[strtolower($email_address['email1'])]=1;
					}
				}
			}
		}
		if(!$emailman->sendEmail($mail,$test)){
			emaillog("FAILURE:");		
		} else {
		 	emaillog("SUCCESS:");	
	 	}
		emaillog($emailman->toString());
		if($mail->isError()){
			emaillog($mail->ErrorInfo);
		}
	}

	$send_all=$send_all?!$no_items_in_queue:$send_all;
	
}while ($send_all == true);

if ($admin->settings['mail_sendtype'] == "SMTP") {
	$mail->SMTPClose();
}
if(isset($temp_user)){
	$current_user = $temp_user;	
}
if (isset($_REQUEST['return_module']) && isset($_REQUEST['return_action']) && isset($_REQUEST['return_id'])) {
		header("Location: index.php?module={$_REQUEST['return_module']}&action={$_REQUEST['return_action']}&record={$_REQUEST['return_id']}"); 	
} else {
	/* this will be triggered when manually sending off Email campaigns from the
	 * Mass Email Queue Manager.
 	*/
	if(isset($_POST['manual'])) {
		header("Location: index.php?module=EmailMan&action=index"); 
	}
}
function emaillog($text){
	if(!empty($_REQUEST['verbose'])){
		echo $text . '<br>';
	}
	$GLOBALS['log']->info($text);
}
?>
