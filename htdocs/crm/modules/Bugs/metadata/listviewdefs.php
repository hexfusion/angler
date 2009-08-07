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

// $Id: listviewdefs.php,v 1.7 2006/08/22 19:09:18 awu Exp $


$listViewDefs['Bugs'] = array(
	'BUG_NUMBER' => array(
		'width' => '5', 
		'label' => 'LBL_LIST_NUMBER', 
		'link' => true,
        'default' => true), 
	'NAME' => array(
		'width' => '32', 
		'label' => 'LBL_LIST_SUBJECT', 
		'default' => true,
        'link' => true),
	'STATUS' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_STATUS',
        'default' => true),
    'TYPE' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_TYPE',
        'default' => true), 
    'PRIORITY' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_PRIORITY',
        'default' => true),  
    'RELEASE_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_FOUND_IN_RELEASE',
        'default' => false,
        'related_fields' => array('found_in_release'),
        'module' => 'Releases',
        'id' => 'FOUND_IN_RELEASE',),
    'FIXED_IN_RELEASE_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_FIXED_IN_RELEASE',
        'default' => true,
        'related_fields' => array('fixed_in_release'),
        'module' => 'Releases',
        'id' => 'FIXED_IN_RELEASE',),  
    'RESOLUTION' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_RESOLUTION',
        'default' => false),          






	'ASSIGNED_USER_NAME' => array(
		'width' => '9', 
		'label' => 'LBL_LIST_ASSIGNED_USER',
        'default' => true)
);
?>
