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

 // $Id: listviewdefs.php,v 1.13 2006/08/22 19:41:20 awu Exp $

$listViewDefs['Opportunities'] = array(
	'NAME' => array(
		'width'   => '30',  
		'label'   => 'LBL_LIST_OPPORTUNITY_NAME', 
		'link'    => true,
        'default' => true),
	'ACCOUNT_NAME' => array(
		'width'   => '20', 
		'label'   => 'LBL_LIST_ACCOUNT_NAME', 
		'id'      => 'ACCOUNT_ID',
        'module'  => 'Accounts',
		'link'    => true,
        'default' => true,
        'sortable'=> false,
        'ACLTag' => 'ACCOUNT'),
	'SALES_STAGE' => array(
		'width'   => '10',  
		'label'   => 'LBL_LIST_SALES_STAGE',
        'default' => true), 
	'AMOUNT_USDOLLAR' => array(
		'width'   => '10', 
		'label'   => 'LBL_LIST_AMOUNT',
        'align'   => 'right',
        'default' => true,
        'currency_format' => true,
	),  
    'OPPORTUNITY_TYPE' => array(
        'width' => '15', 
        'label' => 'LBL_TYPE'),
    'LEAD_SOURCE' => array(
        'width' => '15', 
        'label' => 'LBL_LEAD_SOURCE'),
    'NEXT_STEP' => array(
        'width' => '10', 
        'label' => 'LBL_NEXT_STEP'),
    'PROBABILITY' => array(
        'width' => '10', 
        'label' => 'LBL_PROBABILITY'),
	'DATE_CLOSED' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_DATE_CLOSED',
        'default' => true),
    'DATE_ENTERED' => array(
        'width' => '10', 
        'label' => 'LBL_DATE_ENTERED'),
    'CREATED_BY_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_CREATED'),






	'ASSIGNED_USER_NAME' => array(
		'width' => '5', 
		'label' => 'LBL_LIST_ASSIGNED_USER',
        'default' => true),
    'MODIFIED_USER_NAME' => array(
        'width' => '5', 
        'label' => 'LBL_MODIFIED')
);

?>
