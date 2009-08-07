<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*
 * Creates demo data for the user table
 *
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
 */

// $Id: UserDemoData.php,v 1.14 2006/08/12 05:58:11 chris Exp $

class UserDemoData
{
	var $_user;
	var $_large_scale_test;
	
	/**
	 * Constructor for creating user demo data
	 */
	function UserDemoData($seed_user, $large_scale_test = false)
	{
		// use a seed user so it does not have to be known which file to
		// include the User class from
		$this->_user = $seed_user;
		$this->_large_scale_test = $large_scale_test;
	}
	
	/**
	 * 
	 */
	function create_demo_data()
	{
		// Create the VP
		if (!$this->_user->retrieve("jim_id"))
		{
			$this->_create_seed_user("jim_id", "Brennan", "Jim", "jim",
				"VP Sales", false, null, null, "jim@example.com");
		}

		// Create the west team
		if (!$this->_user->retrieve("sarah_id"))
		{
			$this->_create_seed_user("sarah_id", "Smith", "Sarah", "sarah",
				"Sales Manager West", false, "jim_id", "Brennan, Jim", "sarah@example.com");
		}
		if (!$this->_user->retrieve("sally_id"))
		{
			$this->_create_seed_user("sally_id", "Bronsen", "Sally", "sally",
				"Senior Account Rep", false, "sarah_id", "Smith, Sarah", "sally@example.com");
		}
		if (!$this->_user->retrieve("max_id"))
		{
			$this->_create_seed_user("max_id", "Jensen", "Max", "max",
				"Account Rep", false, "sarah_id", "Smith, Sarah", "max@example.com");
		}

		// Create the east team
		if (!$this->_user->retrieve("will_id"))
		{
			$this->_create_seed_user("will_id", "Westin", "Will", "will",
				"Sales Manager East", false, "jim_id", "Brennan, Jim", "will@example.com");
		}
		if (!$this->_user->retrieve("chris_id"))
		{
			$this->_create_seed_user("chris_id", "Olliver", "Chris", "chris",
				"Senior Account Rep", false, "will_id", "Westin, Will", "chris@example.com");
		}
		
		if($this->_large_scale_test)
		{
			$user_list = $this->_seed_data_get_user_list();
			foreach($user_list as $user_name)
			{
				$this->_quick_create_user($user_name);
			}
		}
	}

	/**
	 * creates seed users using multi-byte characters to simulate real-world
	 * login conditions
	 */
	function create_demo_data_jp() {
		// Create the VP
		if (!$this->_user->retrieve("jim_id"))
		{
			$this->_create_seed_user("jim_id", "ブレナン", "ジーム", "jim",
				"VP Sales", false, null, null, "jim@example.com");
		}

		// Create the west team
		if (!$this->_user->retrieve("sarah_id"))
		{
			$this->_create_seed_user("sarah_id", "スミス", "サーラー", "sarah",
				"Sales Manager West", false, "jim_id", "ブレナン, ジーム", "sarah@example.com");
		}
		if (!$this->_user->retrieve("sally_id"))
		{
			$this->_create_seed_user("sally_id", "ブロンソン", "サーリー", "sally",
				"Senior Account Rep", false, "sarah_id", "スミス, サーラー", "sally@example.com");
		}
		if (!$this->_user->retrieve("max_id"))
		{
			$this->_create_seed_user("max_id", "ジェンソン", "マクス", "max",
				"Account Rep", false, "sarah_id", "スミス、サーラー", "max@example.com");
		}

		// Create the east team
		if (!$this->_user->retrieve("will_id"))
		{
			$this->_create_seed_user("will_id", "ウエストン", "ウイル", "will",
				"Sales Manager East", false, "jim_id", "ブレナン, ジーム", "will@example.com");
		}
		if (!$this->_user->retrieve("chris_id"))
		{
			$this->_create_seed_user("chris_id", "オリバー", "クリス", "chris",
				"Senior Account Rep", false, "will_id", "ウエストン, ウイル", "chris@example.com");
		}
	}

	/**
	 *  Create a user in the seed data.
	 */
	function _create_seed_user($id, $last_name, $first_name, $user_name,
		$title, $is_admin, $reports_to, $reports_to_name, $email)
	{
		$u = $this->_user;
		
		$u->id=$id;
		$u->new_with_id = true;
		$u->last_name = $last_name;
		$u->first_name = $first_name;
		$u->user_name = $user_name;
		$u->title = $title;
		$u->status = 'Active';
		$u->employee_status = 'Active';
		$u->is_admin = $is_admin;
		//$u->user_password = $u->encrypt_password($user_name);
		$u->user_hash = strtolower(md5($user_name));
		$u->reports_to_id = $reports_to;
		$u->reports_to_name = $reports_to_name;
		$u->email1 = $email;
		$u->save();
	}
	
	/**
	 * 
	 */
	function _seed_data_get_user_list()
	{
		$users = Array();

		$users[] = "north";
		$users[] = "south";
		$users[] = "east";
		$users[] = "west";
		$users[] = "left";
		$users[] = "right";
		$users[] = "in";
		$users[] = "out";
		$users[] = "fly";
		$users[] = "walk";
		$users[] = "crawl";
		$users[] = "pivot";
		$users[] = "money";
		$users[] = "dinero";
		$users[] = "shadow";
		$users[] = "roof";
		$users[] = "sales";
		$users[] = "pillow";
		$users[] = "feather";

		return $users;
	}
	
	/**
	 * 
	 */
	function _quick_create_user($name)
	{
		if (!$this->_user->retrieve($name.'_id'))
		{
			$this->_create_seed_user("{$name}_id", $name, $name, $name,
				"Sales Manager of no territory", false, "jim_id", "Brennan, Jim", "jim@example.com");
		}
	}
	
}
?>
