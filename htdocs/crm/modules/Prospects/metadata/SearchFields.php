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
$searchFields['Prospects'] = 
	array (
		'first_name' => array( 'query_type'=>'default'),
		'last_name'=> array('query_type'=>'default'),
		'do_not_call'=> array('query_type'=>'default', 'operator'=>'='),
		'phone'=> array('query_type'=>'default','db_field'=>array('phone_mobile','phone_work','phone_other','phone_fax','phone_home')),
		'email'=> array('query_type'=>'default','db_field'=>array('email1','email2')),
		'assistant'=> array('query_type'=>'default'),
		'email_opt_out'=> array('query_type'=>'default', 'operator'=>'='),
		'address_street'=> array('query_type'=>'default','db_field'=>array('primary_address_street','alt_address_street')),
		'address_city'=> array('query_type'=>'default','db_field'=>array('primary_address_city','alt_address_city')),
		'address_state'=> array('query_type'=>'default','db_field'=>array('primary_address_state','alt_address_state')),
		'address_postalcode'=> array('query_type'=>'default','db_field'=>array('primary_address_postalcode','alt_address_postalcode')),
		'address_country'=> array('query_type'=>'default','db_field'=>array('primary_address_country','alt_address_country')),
		'current_user_only'=> array('query_type'=>'default','db_field'=>array('assigned_user_id'),'my_items'=>true),
		'assigned_user_id'=> array('query_type'=>'default'),

	);
?>
