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
 * $Id: Save.php,v 1.35 2006/08/04 23:31:55 matt Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Cases/Case.php');

require_once('include/formbase.php');



$focus = new aCase();

$focus = populateFromPost('', $focus);

$focus->save($GLOBALS['check_notify']);
$return_id = $focus->id;

///////////////////////////////////////////////////////////////////////////////
////	INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////
if(isset($_REQUEST['inbound_email_id']) && !empty($_REQUEST['inbound_email_id'])) {
	// fake this case like it's already saved.
	require_once('modules/Emails/Email.php');
	$email = new Email();
	$email->retrieve($_REQUEST['inbound_email_id']);
	$email->parent_type = 'Cases';
	$email->parent_id = $focus->id;
	$email->assigned_user_id = $current_user->id;
	$email->status = 'read';
	$email->save();
	$email->load_relationship('cases');
	$email->cases->add($focus->id);
	
	if(!empty($email->reply_to_addr)) {
		$contactAddr = $email->reply_to_addr;
	} else {
		$contactAddr = $email->from_addr;
	}
	
	if($contactIds = $email->getRelatedId($contactAddr, 'contacts')) {
		$focus->load_relationship('contacts');
		$focus->contacts->add($contactIds);
	}	
	
	header("Location: index.php?&module=Emails&action=EditView&type=out&inbound_email_id=".$_REQUEST['inbound_email_id']."&parent_id=".$email->parent_id."&parent_type=".$email->parent_type.'&start='.$_REQUEST['start'].'&assigned_user_id='.$current_user->id);
	break;
}
////	END INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////



handleRedirect($return_id,'Cases');
?>
