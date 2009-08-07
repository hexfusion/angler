<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
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
 */

 // $Id: listviewdefs.php,v 1.12 2006/08/22 19:38:27 awu Exp $

$listViewDefs['Leads'] = array(
	'NAME' => array(
		'width' => '30',
		'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'related_fields' => array('first_name', 'last_name', 'salutation')), 
	'STATUS' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_STATUS',
        'default' => true), 
	'ACCOUNT_NAME' => array(
		'width' => '20', 
		'label' => 'LBL_LIST_ACCOUNT_NAME', 
        'default' => true),
	'EMAIL1' => array(
		'width' => '15', 
		'label' => 'LBL_LIST_EMAIL_ADDRESS',
		'customCode' => '{$EMAIL1_LINK}{$EMAIL1}</a>',
        'default' => true),  
	'PHONE_WORK' => array(
		'width' => '15', 
		'label' => 'LBL_LIST_PHONE'),
    'TITLE' => array(
        'width' => '10', 
        'label' => 'LBL_TITLE'),
    'REFERED_BY' => array(
        'width' => '10', 
        'label' => 'LBL_REFERED_BY'),
    'LEAD_SOURCE' => array(
        'width' => '10', 
        'label' => 'LBL_LEAD_SOURCE'),
    'DEPARTMENT' => array(
        'width' => '10', 
        'label' => 'LBL_DEPARTMENT'),
    'DO_NOT_CALL' => array(
        'width' => '10', 
        'label' => 'LBL_DO_NOT_CALL'),
    'PHONE_HOME' => array(
        'width' => '10', 
        'label' => 'LBL_HOME_PHONE'),
    'PHONE_MOBILE' => array(
        'width' => '10', 
        'label' => 'LBL_MOBILE_PHONE'),
    'PHONE_OTHER' => array(
        'width' => '10', 
        'label' => 'LBL_OTHER_PHONE'),
    'PHONE_FAX' => array(
        'width' => '10', 
        'label' => 'LBL_FAX_PHONE'),
    'EMAIL2' => array(
        'width' => '15', 
        'label' => 'LBL_LIST_EMAIL_ADDRESS',
        'customCode' => '{$EMAIL2_LINK}{$EMAIL2}</a>'),  
    'EMAIL_OPT_OUT' => array(
        'width' => '10', 
        'label' => 'LBL_EMAIL_OPT_OUT'),
    'PRIMARY_ADDRESS_STREET' => array(
        'width' => '10', 
        'label' => 'LBL_PRIMARY_ADDRESS_STREET'),
    'PRIMARY_ADDRESS_CITY' => array(
        'width' => '10', 
        'label' => 'LBL_PRIMARY_ADDRESS_CITY'),
    'PRIMARY_ADDRESS_STATE' => array(
        'width' => '10', 
        'label' => 'LBL_PRIMARY_ADDRESS_STATE'),
    'PRIMARY_ADDRESS_POSTALCODE' => array(
        'width' => '10', 
        'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE'),
    'ALT_ADDRESS_COUNTRY' => array(
        'width' => '10', 
        'label' => 'LBL_ALT_ADDRESS_COUNTRY'),
    'ALT_ADDRESS_STREET' => array(
        'width' => '10', 
        'label' => 'LBL_ALT_ADDRESS_STREET'),
    'ALT_ADDRESS_CITY' => array(
        'width' => '10', 
        'label' => 'LBL_ALT_ADDRESS_CITY'),
    'ALT_ADDRESS_STATE' => array(
        'width' => '10', 
        'label' => 'LBL_ALT_ADDRESS_STATE'),
    'ALT_ADDRESS_POSTALCODE' => array(
        'width' => '10', 
        'label' => 'LBL_ALT_ADDRESS_POSTALCODE'),
    'ALT_ADDRESS_COUNTRY' => array(
        'width' => '10', 
        'label' => 'LBL_ALT_ADDRESS_COUNTRY'),
    'DATE_ENTERED' => array(
        'width' => '10', 
        'label' => 'LBL_DATE_ENTERED'),
    'CREATED_BY' => array(
        'width' => '10', 
        'label' => 'LBL_CREATED'),






    'ASSIGNED_USER_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'default' => true),
    'MODIFIED_USER_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_MODIFIED')
);
?>
