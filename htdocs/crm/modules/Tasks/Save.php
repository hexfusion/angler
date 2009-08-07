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
 * $Id: Save.php,v 1.41 2006/06/06 17:58:40 majed Exp $
 * Description:  Saves an Account record and then redirects the browser to the
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Tasks/Task.php');

$focus = new Task();
if (!isset($prefix)) $prefix='';

global $timedate;
if(!empty($_POST[$prefix.'due_meridiem']))
	$_POST[$prefix.'time_due'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_due'],$timedate->get_time_format(true), $_POST[$prefix.'due_meridiem']);
if(!empty($_POST[$prefix.'start_meridiem']))
	$_POST[$prefix.'time_start'] = $timedate->merge_time_meridiem($_POST[$prefix.'time_start'],$timedate->get_time_format(true), $_POST[$prefix.'start_meridiem']);


require_once('include/formbase.php');

$focus = populateFromPost('', $focus);

if(!$focus->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
}

if (!isset($_POST['date_due_flag'])) $focus->date_due_flag = 'off';
if (!isset($_POST['date_start_flag'])) $focus->date_start_flag = 'off';



///////////////////////////////////////////////////////////////////////////////
////	INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////
if(isset($_REQUEST['inbound_email_id']) && !empty($_REQUEST['inbound_email_id'])) {
	// fake this case like it's already saved.
	$focus->save();
	require_once('modules/Emails/Email.php');
	$email = new Email();
	$email->retrieve($_REQUEST['inbound_email_id']);
	$email->parent_type = 'Tasks';
	$email->parent_id = $focus->id;
	$email->assigned_user_id = $current_user->id;
	$email->status = 'read';
	$email->save();
	$email->load_relationship('tasks');
	$email->tasks->add($focus->id);
	
	header("Location: index.php?&module=Emails&action=EditView&type=out&inbound_email_id=".$_REQUEST['inbound_email_id']."&parent_id=".$email->parent_id."&parent_type=".$email->parent_type.'&start='.$_REQUEST['start'].'&assigned_user_id='.$current_user->id);
	exit();
}
////	END INBOUND EMAIL HANDLING
///////////////////////////////////////////////////////////////////////////////	



$focus->save($GLOBALS['check_notify']);
$return_id = $focus->id;
handleRedirect($return_id,'Tasks');
?>
