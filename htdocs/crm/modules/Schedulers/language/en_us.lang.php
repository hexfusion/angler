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
 * $Id: en_us.lang.php,v 1.21 2006/07/31 19:43:38 jenny Exp $
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $sugar_config;
 
$mod_strings = array (
// OOTB Scheduler Job Names:
'LBL_OOTB_WORKFLOW'		=> 'Process Workflow Tasks',
'LBL_OOTB_REPORTS'		=> 'Run Report Generation Scheduled Tasks',
'LBL_OOTB_IE'			=> 'Check Inbound Mailboxes',
'LBL_OOTB_BOUNCE'		=> 'Run Nightly Process Bounced Campaign Emails',
'LBL_OOTB_CAMPAIGN'		=> 'Run Nightly Mass Email Campaigns',
'LBL_OOTB_PRUNE'		=> 'Prune Database on 1st of Month',
// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Interval:',
'LBL_LIST_LIST_ORDER' => 'Schedulers:',
'LBL_LIST_NAME' => 'Scheduler:',
'LBL_LIST_RANGE' => 'Range:',
'LBL_LIST_REMOVE' => 'Remove:',
'LBL_LIST_STATUS' => 'Status:',
'LBL_LIST_TITLE' => 'Schedule List:',
'LBL_LIST_EXECUTE_TIME' => 'Will Run At:',
// human readable:
'LBL_SUN'		=> 'Sunday',
'LBL_MON'		=> 'Monday',
'LBL_TUE'		=> 'Tuesday',
'LBL_WED'		=> 'Wednesday',
'LBL_THU'		=> 'Thursday',
'LBL_FRI'		=> 'Friday',
'LBL_SAT'		=> 'Saturday',
'LBL_ALL'		=> 'Every Day',
'LBL_EVERY_DAY'	=> 'Every day ',
'LBL_AT_THE'	=> 'At the ',
'LBL_EVERY'		=> 'Every ',
'LBL_FROM'		=> 'From ',
'LBL_ON_THE'	=> 'On the ',
'LBL_RANGE'		=> ' to ',
'LBL_AT' 		=> ' at ',
'LBL_IN'		=> ' in ',
'LBL_AND'		=> ' and ',
'LBL_MINUTES'	=> ' minutes ',
'LBL_HOUR'		=> ' hours',
'LBL_HOUR_SING'	=> ' hour',
'LBL_MONTH'		=> ' month',
'LBL_OFTEN'		=> ' As often as possible.',
'LBL_MIN_MARK'	=> ' minute mark',


// crontabs
'LBL_MINS' => 'min',
'LBL_HOURS' => 'hrs',
'LBL_DAY_OF_MONTH' => 'date',
'LBL_MONTHS' => 'mo',
'LBL_DAY_OF_WEEK' => 'day',
'LBL_CRONTAB_EXAMPLES' => 'The above uses standard crontab notation.',
// Labels
'LBL_ALWAYS' => 'Always',
'LBL_CATCH_UP' => 'Execute If Missed',
'LBL_CATCH_UP_WARNING' => 'Uncheck if this Job may take more than a moment to run.',
'LBL_DATE_TIME_END' => 'Date & Time End',
'LBL_DATE_TIME_START' => 'Date & Time Start',
'LBL_INTERVAL' => 'Interval',
'LBL_JOB' => 'Job',
'LBL_LAST_RUN' => 'Last Successful Run',
'LBL_MODULE_NAME' => 'Sugar Scheduler',
'LBL_MODULE_TITLE' => 'Schedulers',
'LBL_NAME' => 'Job Name',
'LBL_NEVER' => 'Never',
'LBL_NEW_FORM_TITLE' => 'New Schedule',
'LBL_PERENNIAL' => 'perpetual',
'LBL_SEARCH_FORM_TITLE' => 'Scheduler Search',
'LBL_SCHEDULER' => 'Scheduler:',
'LBL_STATUS' => 'Status',
'LBL_TIME_FROM' => 'Active From',
'LBL_TIME_TO' => 'Active To',
'LBL_WARN_CURL_TITLE' => 'cURL Warning:',
'LBL_WARN_CURL' => 'Warning:',
'LBL_WARN_NO_CURL' => 'This system does not have the cURL libraries enabled/compiled into the PHP module (--with-curl=/path/to/curl_library).  Please contact your administrator to resolve this issue.  Without the cURL functionality, the Scheduler cannot thread its jobs.',
'LBL_BASIC_OPTIONS' => 'Basic Setup',
'LBL_ADV_OPTIONS'		=> 'Advanced Options',
'LBL_TOGGLE_ADV' => 'Advanced Options',
'LBL_TOGGLE_BASIC' => 'Basic Options',
// Links
'LNK_LIST_SCHEDULER' => 'Schedulers',
'LNK_NEW_SCHEDULER' => 'Create Scheduler',
'LNK_LIST_SCHEDULED' => 'Scheduled Jobs',



// Messages
'SOCK_GREETING' => "\nThis is the interface for SugarCRM Schedulers Service. \n[ Available daemon commands: start|restart|shutdown|status ]\nTo quit, type 'quit'.  To shutdown the service 'shutdown'.\n",
'ERR_DELETE_RECORD' => 'A record number must be specified to delete the schedule.',
'NTC_DELETE_CONFIRMATION' => 'Are you sure you want to delete this record?',
'NTC_STATUS' => 'Set status to Inactive to remove this Schedule from the Scheduler dropdown lists',
'NTC_LIST_ORDER' => 'Set the order this Schedule will appear in the Scheduler dropdown lists',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'To Setup Windows Scheduler',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'To Setup Crontab',
'LBL_CRON_LINUX_DESC' => 'Add this line to your crontab: ',
'LBL_CRON_WINDOWS_DESC' => 'Create a batch file with the following commands: ',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1', 
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'Job Log',
'LBL_EXECUTE_TIME'			=> 'Execute Time',
// _DOM
'scheduler_status_dom' => 
	array (
	'Active' => 'Active',
	'Inactive' => 'Inactive',
	),
'scheduler_period_dom' => 
	array (
	'min' => 'Minutes',
	'hour' => 'Hours',
	),
);

?>
