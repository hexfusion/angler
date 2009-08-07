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
 * $Id: field_arrays.php,v 1.6 2006/06/06 17:58:20 majed Exp $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['Email'] = array(
	'column_fields' => array(
		"id"
		, "date_entered"
		, "date_modified"
		, "assigned_user_id"
		, "modified_user_id"
		, "created_by"



		, "description"
		, "description_html"
		, "name"
		, "date_start"
		, "time_start"
		, "parent_type"
		, "parent_id"
		, "from_addr"
		, "from_name"
		, "to_addrs"
		, "cc_addrs"
		, "bcc_addrs"
		, "to_addrs_ids"
		, "to_addrs_names"
		, "to_addrs_emails"
		, "cc_addrs_ids"
		, "cc_addrs_names"
		, "cc_addrs_emails"
		, "bcc_addrs_ids"
		, "bcc_addrs_names"
		, "bcc_addrs_emails"
		, "type"
		, "status"
		, "intent"
		),
	'list_fields' => array(
		'id', 'name', 'parent_type', 'parent_name', 'parent_id', 'date_start', 'time_start', 'assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_id', 'first_name','last_name','to_addrs','from_addr','date_sent','type_name','type','status','link_action','date_entered','attachment_image','intent','date_sent'




		),
);
?>
