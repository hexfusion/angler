<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*
 * Creates demo data for the team table
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

// $Id: TeamDemoData.php,v 1.8 2006/08/17 23:22:14 eddy Exp $

class TeamDemoData
{
	var $_team;
	var $_large_scale_test;

	/**
	 * Constructor for creating demo data for teams
	 */
	function TeamDemoData($seed_team, $large_scale_test = false)
	{
		$this->_team = $seed_team;
		$this->_large_scale_test = $large_scale_test;
	}
	
	/**
	 * 
	 */
	function create_demo_data()
	{
		if (!$this->_team->retrieve("East"))
		{
			$this->_team->create_team("East", "This is the team for the East", "East");
		}

		if (!$this->_team->retrieve("West"))
		{
			$this->_team->create_team("West", "This is the team for the West", "West");
		}

		if($this->_large_scale_test)
		{
			$team_list = $this->_seed_data_get_team_list();
			foreach($team_list as $team_name)
			{
				$this->_quick_create($team_name);
			}
		}
		
		// Create the west team memberships
		$this->_team->retrieve("West");
		$this->_team->add_user_to_team("sarah_id");
		$this->_team->add_user_to_team("sally_id");
		$this->_team->add_user_to_team("max_id");

		// Create the east team memberships
		$this->_team->retrieve("East");
		$this->_team->add_user_to_team("will_id");
		$this->_team->add_user_to_team("chris_id");
		
	}

	function create_demo_data_jp()
	{
		if (!$this->_team->retrieve("Eastイースト"))
		{
			$this->_team->create_team("Eastイースト", "これは東のためのチームです。", "East");
		}

		if (!$this->_team->retrieve("Westウエスト"))
		{
			$this->_team->create_team("Westウエスト", "これは西のためのチームです。 ", "West");
		}

		if($this->_large_scale_test)
		{
			$team_list = $this->_seed_data_get_team_list();
			foreach($team_list as $team_name)
			{
				$this->_quick_create($team_name);
			}
		}
		
		// Create the west team memberships
		$this->_team->retrieve("West");
		$this->_team->add_user_to_team("sarah_id");
		$this->_team->add_user_to_team("sally_id");
		$this->_team->add_user_to_team("max_id");

		// Create the east team memberships
		$this->_team->retrieve("East");
		$this->_team->add_user_to_team("will_id");
		$this->_team->add_user_to_team("chris_id");
		
	}

	
	/**
	 * 
	 */
	function get_random_team()
	{
		$team_list = $this->_seed_data_get_team_list();
		$team_list_size = count($team_list);
		$random_index = mt_rand(0,$team_list_size-1);
		
		return $team_list[$random_index];
	}
	
	/**
	 * 
	 */
	function _seed_data_get_team_list()
	{
		$teams = Array();

		$teams[] = "north";
		$teams[] = "south";
		$teams[] = "east";
		$teams[] = "west";
		$teams[] = "left";
		$teams[] = "right";
		$teams[] = "in";
		$teams[] = "out";
		$teams[] = "fly";
		$teams[] = "walk";
		$teams[] = "crawl";
		$teams[] = "pivot";
		$teams[] = "money";
		$teams[] = "dinero";
		$teams[] = "shadow";
		$teams[] = "roof";
		$teams[] = "sales";
		$teams[] = "pillow";
		$teams[] = "feather";

		return $teams;
	}
	
	/**
	 * 
	 */
	function _quick_create($name)
	{
		if (!$this->_team->retrieve($name))
		{
			$this->_team->create_team($name, "This is the team for the $name", $name);
		}
	}
	
	
}
?>
