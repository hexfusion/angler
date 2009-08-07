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
 * $Id: field_arrays.php,v 1.4 2006/06/06 17:57:54 majed Exp $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['Account'] = array ('column_fields' => Array(
		"annual_revenue"
		,"billing_address_street"
		,"billing_address_city"
		,"billing_address_state"
		,"billing_address_postalcode"
		,"billing_address_country"
		,"date_entered"
		,"date_modified"
		,"modified_user_id"
		,"assigned_user_id"
		,"description"
		,"email1"
		,"email2"
		,"employees"
		,"id"
		,"industry"
		,"name"
		,"ownership"
		,"parent_id"
		,"phone_alternate"
		,"phone_fax"
		,"phone_office"
		,"rating"
		,"shipping_address_street"
		,"shipping_address_city"
		,"shipping_address_state"
		,"shipping_address_postalcode"
		,"shipping_address_country"
		,"sic_code"
		,"ticker_symbol"
		,"account_type"
		,"website"
		, "created_by"



		),
        'list_fields' => Array('id', 'name', 'website', 'phone_office', 'assigned_user_name', 'assigned_user_id'
	, 'billing_address_street'
	, 'billing_address_city'
	, 'billing_address_state'
	, 'billing_address_postalcode'
	, 'billing_address_country'
	, 'shipping_address_street'
	, 'shipping_address_city'
	, 'shipping_address_state'
	, 'shipping_address_postalcode'
	, 'shipping_address_country'




		),
        'required_fields' => array("name"=>1),
);
?>
