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

// $Id: listviewdefs.php,v 1.11 2006/08/22 19:19:20 awu Exp $


$listViewDefs['Cases'] = array(
	'CASE_NUMBER' => array(
		'width' => '5', 
		'label' => 'LBL_LIST_NUMBER',
        'default' => true), 
	'NAME' => array(
		'width' => '35', 
		'label' => 'LBL_LIST_SUBJECT', 
		'link' => true,
        'default' => true), 
	'ACCOUNT_NAME' => array(
		'width' => '25', 
		'label' => 'LBL_LIST_ACCOUNT_NAME', 
		'module' => 'Accounts',
		'id' => 'ACCOUNT_ID',
		'link' => true,
        'default' => true,
        'ACLTag' => 'ACCOUNT',
        'related_fields' => array('account_id')),
	'PRIORITY' => array(
		'width' => '8', 
		'label' => 'LBL_LIST_PRIORITY',
        'default' => true),  
	'STATUS' => array(
		'width' => '8', 
		'label' => 'LBL_LIST_STATUS',
        'default' => true),






	'ASSIGNED_USER_NAME' => array(
		'width' => '5', 
		'label' => 'LBL_LIST_ASSIGNED_USER',
        'default' => true)
);
?>
