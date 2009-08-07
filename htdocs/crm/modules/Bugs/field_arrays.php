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
 * $Id: field_arrays.php,v 1.7 2006/06/06 17:57:55 majed Exp $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['Bug'] = array ('column_fields' => Array("id"
		, "name"
		, "bug_number"
		, "date_entered"
		, "date_modified"
		, "modified_user_id"
		, "assigned_user_id"



		, "status"
		, "found_in_release"
		, "created_by"
		, "resolution"
		, "priority"
		, "description"
		,'type'
		, "fixed_in_release"
		, "work_log"
		, "source"
		, "product_category"
		),
        'list_fields' => Array('id', 'priority', 'status', 'name', 'bug_number', 'assigned_user_name', 'assigned_user_id', 'release', 'release_name', 'resolution', 'type'




		),
        'required_fields' => array('name'=>1),
);
?>
