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
 * Created On: Sep 28, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/
$fields_array['InboundEmail'] = array (
	'column_fields' => array (
		'id',
		'deleted',
		'date_entered',
		'date_modified',
		'modified_user_id',
		'created_by',
		'name',
		'status',
		'server_url',
		'email_user',
		'email_password',
		'port',
		'service',
		'mailbox',
		'delete_seen',
		'mailbox_type',
		'template_id',
	),
	'list_fields' => array (
		'id',
		'name',
		'server_url',
		'status',
		'mailbox_type_name',
	),
	'required_fields' => array (
		'server_url' => 1,
		'service' => 1,
	),
);
?>
