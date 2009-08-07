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
 * Created On: Oct 11, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/

/**
 * Set up an array of Jobs with the appropriate metadata
 * 'jobName' => array (
 * 		'X' => 'name',
 * )
 * 'X' should be an increment of 1
 * 'name' should be the EXACT name of your function
 * 
 * Your function should not be passed any parameters
 * Always  return a Boolean. If it does not the Job will not terminate itself 
 * after completion, and the webserver will be forced to time-out that Job instance.
 * DO NOT USE sugar_cleanup(); in your function flow or includes.  this will
 * break Schedulers.  That function is called at the foot of cron.php
 */

/**
 * This array provides the Schedulers admin interface with values for its "Job"
 * dropdown menu.
 */
$job_strings = array (
	0 => 'refreshJobs',
	1 => 'pollMonitoredInboxes',
	2 => 'runMassEmailCampaign',
	3 => 'pruneDatabase',
	/*4 => 'securityAudit()',*/
	5 => 'pollMonitoredInboxesForBouncedCampaignEmails',








);

/**
 * Job 0 refreshes all job schedulers at midnight
 * DEPRECATED
 */
function refreshJobs() {
	return true;
}


/**
 * Job 1
 */
function pollMonitoredInboxes() {
	
	$GLOBALS['log']->info('----->Scheduler fired job of type pollMonitoredInboxes()');
	global $dictionary;
	require_once('modules/InboundEmail/InboundEmail.php');
	
	$ie = new InboundEmail();
	$r = $ie->db->query('SELECT id, name FROM inbound_email WHERE deleted=0 AND status=\'Active\' AND mailbox_type != \'bounce\'');
	$GLOBALS['log']->debug('Just got Result from get all Inbounds of Inbound Emails');
	
	while($a = $ie->db->fetchByAssoc($r)) {
		$GLOBALS['log']->debug('In while loop of Inbound Emails');
		
		
		$ieX = new InboundEmail();
		$ieX->retrieve($a['id']);
		$newMsgs = array();
		
		$GLOBALS['log']->debug('Trying to connect to mailserver for [ '.$a['name'].' ]');
		if($ieX->connectMailserver() == 'true') {
			$GLOBALS['log']->debug('Connected to mailserver');
		} else {
			$GLOBALS['log']->fatal('Failing job because we could not get an IMAP connection resource.');
			return false;
		}
	
		$newMsgs = $ieX->getNewMessageIds();
		if(is_array($newMsgs)) {
			$current = 1;
			$total = count($newMsgs);
			foreach($newMsgs as $k => $msgNo) {
				$ieX->importOneEmail($msgNo);
				$GLOBALS['log']->debug('***** On message [ '.$current.' of '.$total.' ] *****');
				$current++;
			}
		}
		
		imap_expunge($ieX->conn); 
		imap_close($ieX->conn, CL_EXPUNGE);
	}
	
	return true;
}

/**
 * Job 2
 */
function runMassEmailCampaign() {
	if (!class_exists('LoggerManager')){
		require('log4php/LoggerManager.php');
	}
	$GLOBALS['log'] = LoggerManager::getLogger('emailmandelivery');
	$GLOBALS['log']->debug('Called:runMassEmailCampaign');

	if (!class_exists('PearDatabase')){
		require('include/database/PearDatabase.php');
	}
	require_once('include/utils.php');
	global $beanList;
	global $beanFiles;
	require("config.php");
	require('include/modules.php');
	if(!class_exists('AclController')) {
		require('modules/ACL/ACLController.php');
	}

	require('modules/EmailMan/EmailManDelivery.php');
	return true;
}

/**
 *  Job 3
 */
function pruneDatabase() {
	$GLOBALS['log']->info('----->Scheduler fired job of type pruneDatabase()');
	$backupDir	= 'cache/backups';
	$backupFile	= 'backup-pruneDatabase-GMT0_'.gmdate('Y_m_d-H_i_s', strtotime('now')).'.php';
	
	$db = PearDatabase::getInstance();
	$tables = $db->getTablesArray();

//_ppd($tables);	
	if(!empty($tables)) {
		foreach($tables as $kTable => $table) {
			// find tables with deleted=1
			$qDel = 'SELECT * FROM '.$table.' WHERE deleted = 1';
			$rDel = $db->query($qDel);// OR continue; // continue if no 'deleted' column

			// make a backup INSERT query if we are deleting.
			while($aDel = $db->fetchByAssoc($rDel)) {
				// build column names
				$rCols = $db->query('SHOW COLUMNS FROM '.$table);
				$colName = array();

				while($aCols = $db->fetchByAssoc($rCols)) {
					$colName[] = $aCols['Field']; 
				}

				$query = 'INSERT INTO '.$table.' (';
				$values = '';
				foreach($colName as $kC => $column) {
					$query .= $column.', ';
					$values .= '"'.$aDel[$column].'", ';
				}
				
				$query  = substr($query, 0, (strlen($query) - 2));
				$values = substr($values, 0, (strlen($values) - 2));
				$query .= ') VALUES ('.str_replace("'", "&#039;", $values).');';
			
				$queryString[] = $query;

				if(empty($colName)) {
					$GLOBALS['log']->fatal('pruneDatabase() could not get the columns for table ('.$table.')');
				}
			} // end aDel while()
			// now do the actual delete
			$db->query('DELETE FROM '.$table.' WHERE deleted = 1');	
		} // foreach() tables
		
		// now output file with SQL
		if(!function_exists('mkdir_recursive')) {
			require_once('include/dir_inc.php');
		}
		if(!function_exists('write_array_to_file')) {
			require_once('include/utils/file_utils.php');
		}
		if(!file_exists($backupDir) || !file_exists($backupDir.'/'.$backupFile)) {
			// create directory if not existent
			mkdir_recursive($backupDir, false);
		}
		// write cache file
		
		write_array_to_file('pruneDatabase', $queryString, $backupDir.'/'.$backupFile);
		return true;
	} 
	return false;	
}


///**
// * Job 4
// */
//function securityAudit() {
//	// do something
//	return true;
//}

/* Job 5
 * 
 */
function pollMonitoredInboxesForBouncedCampaignEmails() {
	$GLOBALS['log']->info('----->Scheduler job of type pollMonitoredInboxesForBouncedCampaignEmails()');
	global $dictionary;
	require_once('modules/InboundEmail/InboundEmail.php');
	
	$ie = new InboundEmail();
	$r = $ie->db->query('SELECT id FROM inbound_email WHERE deleted=0 AND status=\'Active\' AND mailbox_type=\'bounce\'');
	
	while($a = $ie->db->fetchByAssoc($r)) {
		$ieX = new InboundEmail();
		$ieX->retrieve($a['id']);
		$ieX->connectMailserver();
	
		$newMsgs = $ieX->getNewMessageIds();
		if(is_array($newMsgs)) {
			foreach($newMsgs as $k => $msgNo) {
				$ieX->importOneEmail($msgNo);
			}
		}
		imap_expunge($ieX->conn);
		imap_close($ieX->conn);
	}
	
	return true;
}

























































?>
