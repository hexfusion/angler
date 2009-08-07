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
 * $Id: field_arrays.php,v 1.3 2006/06/06 17:58:40 majed Exp $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['Task'] = array ('column_fields' =>Array("id"
		, "date_entered"
		, "date_modified"
		, "assigned_user_id"
		, "modified_user_id"
		, "created_by"



		, "description"
		, "name"
		, "status"
		, "date_due"
		, "time_due"
		, "date_start_flag"
		, "date_start"
		, "time_start"
		, "priority"
		, "date_due_flag"
		, "parent_type"
		, "parent_id"
		, "contact_id"
		),
        'list_fields' =>  Array('id', 'status', 'name', 'parent_type', 'parent_name', 'parent_id', 'date_due', 'contact_id', 'contact_name', 'assigned_user_name', 'assigned_user_id','first_name','last_name','time_due', 'priority'




		),
    'required_fields' =>   array('name'=>1),
);
?>
