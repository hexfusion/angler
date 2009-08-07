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
/**
 * Job class
 * TODO do a write up of this object's functionality
 * @author		Chris Nojima
 * @version		0.1
 */

require_once('data/SugarBean.php');

class SchedulersJob extends SugarBean {
	// schema attributes
	var $id = '';
	var $deleted = '';
	var $date_entered = '';
	var $date_modified = '';
	var $scheduler_id = '';
	var $execute_time = '';
	var $status;
	// standard SugarBean child attrs
	var $table_name		= "schedulers_times";
	var $object_name	= "SchedulersJob";
	var $module_dir		= "SchedulersJobs";
	var $new_schema		= true;
	var $process_save_dates = true;
	// related fields
	var $job_name;	// the Scheduler's 'name' field
	var $job;		// the Scheduler's 'job' field
	// object specific attributes
	var $user; // User object
	var $scheduler; // Scheduler parent
	
	/**
	 * Sole constructor.
	 */
	function SchedulersJob() {
		parent::SugarBean();

		require_once('modules/Users/User.php');
		$user = new User();
		$user->retrieve('1'); // Scheduler jobs run as Admin
		$this->user = $user;




	}
	
	///////////////////////////////////////////////////////////////////////////
	////	SCHEDULERSJOB HELPER FUNCTIONS

	function fireSelf($id) {
		require_once('modules/Schedulers/Scheduler.php');
		$sched = new Scheduler();
		$sched->retrieve($id);
		
		$exJob = explode('::', $sched->job);

		if(is_array($exJob)) {
			$this->scheduler_id	= $sched->id;
			$this->scheduler	= $sched;
			$this->execute_time	= $this->handleDateFormat('now');
			$this->save();
			
			if($exJob[0] == 'function') {
				$GLOBALS['log']->debug('----->Scheduler found a job of type FUNCTION');
				require_once('modules/Schedulers/_AddJobsHere.php');

				$this->setJobFlag(1);
				
				$func = $exJob[1];
				$GLOBALS['log']->debug('----->SchedulersJob firing '.$func);
				
				$res = call_user_func($func);
				if($res) {
					$this->setJobFlag(2);
					$this->finishJob();
					return true;
				} else {
					$this->setJobFlag(3);
					return false;
				}
			} elseif($exJob[0] == 'url') {
				if(function_exists('curl_init')) {
					$GLOBALS['log']->debug('----->SchedulersJob found a job of type URL');
					$this->setJobFlag(1);
	
					$GLOBALS['log']->debug('----->SchedulersJob firing URL job: '.$exJob[1]);
					if($this->fireUrl($exJob[1])) {
						$this->setJobFlag(2);
						$this->finishJob();
						return true;
					} else {
						$this->setJobFlag(3);
						return false;
					}
				} else {
					$this->setJobFlag(4);
					return false;
				}
			}
		}
		return false;
	}
	




















	function handleDateFormat($time) {
		global $timedate;
		if(!isset($timedate) || empty($timedate)) {
			$timedate = new TimeDate();
		}
		$format = $this->user->getUserDateTimePreferences($this->user);
		$adjustedDateTime = date($format['date'].' '.$format['time'], strtotime($time)); // bug 4682
		$GLOBALS['log']->debug('Saving LAST_RUN for SchedulerJob ['.$this->id.'] using: ['.$adjustedDateTime.']');
		return $adjustedDateTime;
	}
	
	function setJobFlag($flag) {
		$status = array (0 => 'ready', 1 => 'in progress', 2 => 'completed', 3 => 'failed', 4 => 'no curl');
		$statusScheduler = array (0 => 'Active', 1 => 'In Progress', 2 => 'Active', 3 => 'Active', 4 => 'Active');
		$GLOBALS['log']->info('-----> SchedulersJob setting Job flag: '.$status[$flag].' AND setting Scheduler status to: '.$statusScheduler[$flag]);
		
		$this->status = $status[$flag];
		$this->scheduler->retrieve($this->scheduler_id);
		$this->scheduler->status = $statusScheduler[$flag];
		$this->scheduler->save();
		$this->execute_time	= $this->handleDateFormat('now');
		$this->save();
	}
	
	/**
	 * This function takes a job_id, and updates schedulers last_run as well as
	 * soft delete the job instance from schedulers_times
	 * @return	boolean		Success
	 */
	function finishJob() {
		$GLOBALS['log']->debug('----->SchedulersJob updating Job Status and finishing Job execution.');
		$this->scheduler->retrieve($this->scheduler->id);
		$this->scheduler->last_run = $this->handleDateFormat('now');
		if($this->scheduler->last_run == gmdate('Y-m-d H:i:s', strtotime('Jan 01 2000 00:00:00'))) {
			$this->scheduler->last_run = $this->handleDateFormat('now');
			$GLOBALS['log']->fatal('Scheduler applying bogus date for "Last Run": '.$this->scheduler->last_run);
		}
		$this->scheduler->save();
	}

	/** 
	 * This function takes a passed URL and cURLs it to fake multi-threading with another httpd instance
	 * @param	$job		String in URI-clean format
	 * @param	$timeout	Int value in secs for cURL to timeout. 30 default.
	 */
	//TODO: figure out what error is thrown when no more apache instances can be spun off
	function fireUrl($job, $timeout=30) {
		// cURL inits
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $job); // set url 
		curl_setopt($ch, CURLOPT_FAILONERROR, true); // silent failure (code >300);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // do not follow location(); inits - we always use the current
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);  // not thread-safe
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return into a variable to continue program execution
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // never times out - bad idea?
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 5 secs for connect timeout
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);  // open brand new conn
		curl_setopt($ch, CURLOPT_HEADER, true); // do not return header info with result
		curl_setopt($ch, CURLOPT_NOPROGRESS, true); // do not have progress bar
		curl_setopt($ch, CURLOPT_PORT, $_SERVER['SERVER_PORT']); // set port as reported by Server
		//TODO make the below configurable
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // most customers will not have Certificate Authority account
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // most customers will not have Certificate Authority account
		
		if(constant('PHP_VERSION') > '5.0.0') {
			curl_setopt($ch, CURLOPT_NOSIGNAL, true); // ignore any cURL signals to PHP (for multi-threading)
		}
		$result = curl_exec($ch);
		$cInfo = curl_getinfo($ch);	//url,content_type,header_size,request_size,filetime,http_code
									//ssl_verify_result,total_time,namelookup_time,connect_time
									//pretransfer_time,size_upload,size_download,speed_download,
									//speed_upload,download_content_length,upload_content_length
									//starttransfer_time,redirect_time
		curl_close($ch);

		if($cInfo['http_code'] < 400) {
			$GLOBALS['log']->debug('----->Firing was successful: ('.$job.') at '.$this->handleDateFormat('now'));
			$GLOBALS['log']->debug('----->WTIH RESULT: '.strip_tags($result).' AND '.strip_tags(print_r($cInfo)));
			return true;
		} else {
			$GLOBALS['log']->fatal('Job errored: ('.$job.') at '.$this->handleDateFormat('now'));
			return false;
		}
	}
	////	END SCHEDULERSJOB HELPER FUNCTIONS
	///////////////////////////////////////////////////////////////////////////


	///////////////////////////////////////////////////////////////////////////
	////	STANDARD SUGARBEAN OVERRIDES
	/**
	 * This function gets DB data and preps it for ListViews
	 */
	function get_list_view_data(){
		global $mod_strings;

		$temp_array = $this->get_list_view_array();
		$temp_array['JOB_NAME'] = $this->job_name;
		$temp_array['JOB']		= $this->job;

    	return $temp_array;
	}

	/** method stub for future customization
	 * 
	 */
	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields() {
		// get the Job Name and Job fields from schedulers table
//		$q = "SELECT name, job FROM schedulers WHERE id = '".$this->job_id."'";
//		$result = $this->db->query($q);
//		$row = $this->db->fetchByAssoc($result);
//		$this->job_name = $row['name'];
//		$this->job = $row['job'];
//		$GLOBALS['log']->info('Assigned Name('.$this->job_name.') and Job('.$this->job.') to Job');
//		
//		$this->created_by_name = get_assigned_user_name($this->created_by);
//		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
		
    }

	/**
	 * returns the bean name - overrides SugarBean's
	 */
	function get_summary_text() {
		return $this->name;
	}

	/**
	 * function overrides the one in SugarBean.php
	 */
	function create_list_query($order_by, $where, $show_deleted = 0) {
		$custom_join = $this->custom_fields->getJOIN();
		$query = 'SELECT '.$this->table_name.'.*';
		$order_by = 'schedulers_times.execute_time ASC';
		
		if($custom_join) {
			$query .= $custom_join['select'];
 		}
		$query .= ' FROM '.$this->table_name.' ';
		if($custom_join) {
			$query .= $custom_join['join'];
		}

		if($show_deleted == 0) {
			$where_auto = 'DELETED=0';
		} elseif($show_deleted == 1) {
			$where_auto = 'DELETED=1';
		} else {
			$where_auto = '1=1';
		}

		if($where != "") {
			$query .= 'WHERE ('.$where.') AND '.$where_auto;
		}
		else {
			$query .= 'WHERE '.$where_auto;
		}

		if(!empty($order_by))
			$query .= ' ORDER BY '.$order_by;
		return $query;
	}

}  // end class Job 
?>
