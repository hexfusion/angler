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

function build_related_list_by_user_id($bean, $user_id,$where) {
	$bean_id_name = strtolower($bean->object_name).'_id';

	$select = "SELECT {$bean->table_name}.* from {$bean->rel_users_table},{$bean->table_name} ";

	$auto_where = ' WHERE ';
	if(!empty($where)) {
		$auto_where .= $where. ' AND ';
	}

	$auto_where .= " {$bean->rel_users_table}.{$bean_id_name}={$bean->table_name}.id AND {$bean->rel_users_table}.user_id='{$user_id}' AND {$bean->table_name}.deleted=0 AND {$bean->rel_users_table}.deleted=0";

	$query = $select.$auto_where;

	$result = $bean->db->query($query, true);












	$list = array();

	while($row = $bean->db->fetchByAssoc($result)) {
		foreach($bean->column_fields as $field) {
			if(isset($row[$field])) {
				$bean->$field = $row[$field];
			} else {
				$bean->$field = '';
			}
		}





		$bean->processed_dates_times = array();
		$bean->check_date_relationships_load();
		
		/**
		 * PHP  5+ always treats objects as passed by reference
		 * Need to clone it if we're using 5.0+
		 * clone() not supported by 4.x
		 */
		if(version_compare(phpversion(), "5.0", ">=")) {
			$newBean = clone($bean);	
		} else {
			$newBean = $bean;
		}
		$list[] = $newBean;
	}

	return $list;
}
?>
