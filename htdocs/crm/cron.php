<?php
 if(!defined('sugarEntry'))define('sugarEntry', true);
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
 * Created On: Oct 17, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/
require_once('include/entryPoint.php');

if(empty($current_language)) {
	$current_language = $sugar_config['default_language'];
}

///////////////////////////////////////////////////////////////////////////////
////	PREP FOR SCHEDULER PID
$GLOBALS['log']->debug('--------------------------------------------> at cron.php <--------------------------------------------');

$cachePath = 'cache/modules/Schedulers';
$pid = 'pid.php';
if(!is_dir($cachePath)) {
	mkdir_recursive($cachePath);
}
if(!is_file($cachePath.'/'.$pid)) {
	if(is_writable($cachePath)) { // the "file" does not yet exist
		write_array_to_file('timestamp', array(strtotime(date('H:i'))) , $cachePath.'/'.$pid);
		require_once($cachePath.'/'.$pid);
	} else {
		$GLOBALS['log']->fatal('Scheduler cannot write PID file.  Please check permissions on '.$cachePath);
	}
} else {
	if(is_writable($cachePath.'/'.$pid)) {
		require_once($cachePath.'/'.$pid);
	} else {
		$GLOBALS['log']->fatal('Scheduler cannot read the PID file.  Please check permissions on '.$cachePath);
	}
}
////	END PREP FOR SCHEDULER PID
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	EXECUTE IF VALID TIME (NOT DDOS)




if($timestamp[0] < strtotime(date('H:i'))) {
	if(is_writable($cachePath.'/'.$pid)) {
		write_array_to_file('timestamp', array(strtotime(date('H:i'))) , $cachePath.'/'.$pid);
		require('modules/Schedulers/Scheduler.php');
		$s = new Scheduler();
		$s->flushDeadJobs();
		$s->checkPendingJobs();
	} else {
		$GLOBALS['log']->fatal('Scheduler cannot write PID file.  Please check permissions on '.$cachePath);
	}
} else {
	$GLOBALS['log']->fatal('If you see a whole string of these, there is a chance someone is attacking your system.');



}
sugar_cleanup(true);
?>
