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
$searchFields['Accounts'] = 
	array (
		'name' => array( 'query_type'=>'default'),
		'account_type'=> array('query_type'=>'default', 'options' => 'account_type_dom', 'template_var' => 'ACCOUNT_TYPE_OPTIONS'),
		'industry'=> array('query_type'=>'default', 'options' => 'industry_dom', 'template_var' => 'INDUSTRY_OPTIONS'),
		'annual_revenue'=> array('query_type'=>'default'),
		'address_street'=> array('query_type'=>'default','db_field'=>array('billing_address_street','shipping_address_street')),
		'address_city'=> array('query_type'=>'default','db_field'=>array('billing_address_city','shipping_address_city')),
		'address_state'=> array('query_type'=>'default','db_field'=>array('billing_address_state','shipping_address_state')),
		'address_postalcode'=> array('query_type'=>'default','db_field'=>array('billing_address_postalcode','shipping_address_postalcode')),
		'address_country'=> array('query_type'=>'default','db_field'=>array('billing_address_country','shipping_address_country')),
		'rating'=> array('query_type'=>'default'),
		'phone'=> array('query_type'=>'default','db_field'=>array('phone_office')),
		'email'=> array('query_type'=>'default','db_field'=>array('email1','email2')),
		'website'=> array('query_type'=>'default'),
		'ownership'=> array('query_type'=>'default'),
		'employees'=> array('query_type'=>'default'),
		'sic_code'=> array('query_type'=>'default'),
		'ticker_symbol'=> array('query_type'=>'default'),
		'current_user_only'=> array('query_type'=>'default','db_field'=>array('assigned_user_id'),'my_items'=>true),
		'assigned_user_id'=> array('query_type'=>'default'),
	);
?>
