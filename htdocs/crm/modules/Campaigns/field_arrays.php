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
 * $Id: field_arrays.php,v 1.4 2006/06/06 17:57:56 majed Exp $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['Campaign'] = array ('column_fields' => array(
				"id", "date_entered",
				"date_modified", "modified_user_id",
				"assigned_user_id", "created_by",



				"name", "start_date",
				"end_date", "status",
				"budget", "expected_cost",
				"actual_cost", "expected_revenue",
				"campaign_type", "objective",
				"content", "tracker_key","refer_url","tracker_text",
				"tracker_count","currency_id",
	),
        'list_fields' => array(
				'id', 'name', 'status',
				'campaign_type','assigned_user_id','assigned_user_name','end_date',




				'refer_url',"currency_id",
	),
        'required_fields' => array(
				'name'=>1, 'end_date'=>2,
				'status'=>3, 'campaign_type'=>4
	),
);
?>
