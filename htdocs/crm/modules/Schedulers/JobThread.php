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
 * Description:
 * Created On: Oct 14, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/

/*if($_SERVER['SERVER_ADDR'] != $_SERVER['REMOTE_ADDR']) { // make sure this script only gets executed locally
	header('Location: index.php?action=Login&module=Users');
	return;
} else
*/
if(!empty($_REQUEST['job_id'])) {
	require_once('modules/Schedulers/Scheduler.php');
	
	$job_id = $_REQUEST['job_id'];

	if(empty($GLOBALS['log'])) { // setup logging
		
		$GLOBALS['log'] = LoggerManager::getLogger('SugarCRM'); 	
	}
	ob_implicit_flush();
	ignore_user_abort(true);// keep processing if browser is closed
	set_time_limit(0);// no time out
	$GLOBALS['log']->debug('Job [ '.$job_id.' ] is about to FIRE. Updating Job status in DB');
	$qLastRun = "UPDATE schedulers SET last_run = '".$runTime."' WHERE id = '".$job_id."'";
	$this->db->query($qStatusUpdate);
	$this->db->query($qLastRun);
	
	$job = new Job();
	$job->runtime = gmdate('Y-m-d H:i:s', strtotime('now'));
	if($job->startJob($job_id)) {
		$GLOBALS['log']->info('----->Job [ '.$job_id.' ] was fired successfully');
	} else {
		$GLOBALS['log']->fatal('----->Job FAILURE job [ '.$job_id.' ] could not complete successfully.');
	}
	
	$GLOBALS['log']->debug('Job [ '.$a['job'].' ] has been fired - dropped from schedulers_times queue and last_run updated');
	$this->finishJob($job_id);
	return true;
} else {
	$GLOBALS['log']->fatal('JOB FAILURE JobThread.php called with no job_id.  Suiciding this thread.');
	die();
}
?>
