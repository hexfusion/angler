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
* $Id: Dashboard.php,v 1.9 2006/06/06 17:57:57 majed Exp $
* Description:  TODO: To be written.
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/



require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('modules/Charts/code/predefined_charts.php');

class Dashboard extends SugarBean {

	var $db;
	var $field_name_map;

	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $assigned_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;

	var $team_id;

	var $name;
	var $description;
	var $content;
	var $user_id;

	var $table_name = "dashboards";
	var $object_name = "Dashboard";

	var $new_schema = true;


	var $additional_column_fields = array();

	var $module_dir = 'Dashboard';
	var $field_defs = array();
	var $field_defs_map = array();

	function Dashboard()
	{
		parent::SugarBean();
		$this->setupCustomFields('Dashboard');
		foreach ($this->field_defs as $field)
		{
			$this->field_name_map[$field['name']] = $field;
		}


		$this->team_id = 1; // make the item globally accessible

	}

	function create_tables ()
	{
		parent::create_tables();
	}

	function get_summary_text()
	{
		return $this->title;
	}

	function getUsersTopDashboard($user_id)
	{
		$where = "dashboards.assigned_user_id='$user_id'";
		$response = $this->get_list("", $where, 0);

		if ( count($response['list']) > 0)
		{
			return $response['list'][0];
		}

		return $this->createUserDashboard($user_id);

	}

	function &createUserDashboard($user_id)
	{
		$test = array();
		$dashboard = new Dashboard();

		$dashboard->assigned_user_id = $user_id;
		$dashboard->created_by = $user_id;
		$dashboard->modified_user_id = $user_id;
		$dashboard->name = "Home";
		$dashboard->content = $this->getDefaultDashboardContents();
		$dashboard->save();
		return $dashboard;
	}

	function getDefaultDashboardContents()
	{
		$contents = array(
		array('type'=>'code','id'=>'Chart_pipeline_by_sales_stage'),
		array('type'=>'code','id'=>'Chart_lead_source_by_outcome'),
		array('type'=>'code','id'=>'Chart_outcome_by_month'),
		array('type'=>'code','id'=>'Chart_pipeline_by_lead_source'),
		);
		return serialize($contents);

	}


	function move ($dir='up',$chart_index)
	{
		$dashboard_def = unserialize(from_html($this->content));
		if ( $dir == 'up' && $chart_index != 0)
		{
			$extracted_array = $dashboard_def[$chart_index];
			array_splice($dashboard_def,$chart_index,1);
			array_splice($dashboard_def,$chart_index-1,0,array($extracted_array));
		}
		else if ( $dir == 'down' && $chart_index != (count($dashboard_def) - 1))
		{
			$extracted_array = $dashboard_def[$chart_index];
			array_splice($dashboard_def,$chart_index,1);
			array_splice($dashboard_def,$chart_index+1,0,array($extracted_array));
		}
		
		$this->content = serialize($dashboard_def);
		$this->save();
	}

	function arrange($chart_order) {
		$dashboard_def = unserialize(from_html($this->content));
		$dashboard_def_new = array();
		foreach($chart_order as $chart_index) {
			array_push($dashboard_def_new, $dashboard_def[$chart_index]);
		}

		$this->content = serialize($dashboard_def_new);
		$this->save();
	}
	
	function delete ($chart_index)
	{
		$dashboard_def = unserialize(from_html($this->content));
		array_splice($dashboard_def,$chart_index,1);
		$this->content = serialize($dashboard_def);
		$this->save();
	}

	function add ($chart_type,$chart_id,$chart_index)
	{
		global $predefined_charts;
		$dashboard_def = unserialize(from_html($this->content));
		if ( $chart_type == 'code')
		{
			if ( isset($predefined_charts[$chart_id]))
			{
				array_splice($dashboard_def,$chart_index,0,array($predefined_charts[$chart_id]));
			}
		} else if ($chart_type=='report')
		{
			$chart_def = array('type'=>'report','id'=>$chart_id);
			array_splice($dashboard_def,$chart_index,0,array($chart_def));

		}
		$this->content = serialize($dashboard_def);
		$this->save();
	}
}

?>
