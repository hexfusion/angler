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
 * Created On: Nov 12, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/

require_once('modules/Users/User.php');

class Group extends User {
	// User attribute overrides
	var $status			= 'Group';
	var $password		= ''; // to disallow logins
	var $default_team;
	
	function Group() {
		parent::User();
	}

	/** 
	 * overrides SugarBean method
	 */
	function mark_deleted($id) {
		SugarBean::mark_deleted($id);
	}

	function create_export_query($order_by, $where)
	{
		$query = "SELECT users.*";
		$query .= " FROM users ";
		$where_auto = " users.deleted = 0";
		if($where != "")
			$query .= " WHERE $where AND " . $where_auto;
		else
			$query .= " WHERE " . $where_auto;
		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY users.user_name";
		return $query;
	}
	
} // end class def 

?>
