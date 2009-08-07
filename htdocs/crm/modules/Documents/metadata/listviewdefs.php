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

 // $Id: listviewdefs.php,v 1.7 2006/08/22 22:47:14 awu Exp $

$listViewDefs['Documents'] = array(
    'FILE_URL' => array(
        'width' => '2', 
        'label' => '&nbsp;', 
        'link' => true,
        'default' => true,
        'related_fields' => array('document_revision_id'),
        'sortable' => false), 
	'DOCUMENT_NAME' => array(
		'width' => '40', 
		'label' => 'LBL_NAME', 
		'link' => true,
        'default' => true), 
    'CATEGORY_ID' => array(
        'width' => '40', 
        'label' => 'LBL_LIST_CATEGORY',
        'default' => true),
    'SUBCATEGORY_ID' => array(
        'width' => '40', 
        'label' => 'LBL_LIST_SUBCATEGORY',
        'default' => true),
    'LATEST_REVISION' => array(
        'width' => '10', 
        'label' => 'LBL_LATEST_REVISION',
        'default' => true,
        'sortable' => false),







    'CREATED_BY_NAME' => array(
        'width' => '2', 
        'label' => 'LBL_LIST_LAST_REV_CREATOR',
        'default' => true,
        'sortable' => false),
    'LAST_REV_CREATE_DATE' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_LAST_REV_DATE',
        'default' => true,
        'related_fields' => array('document_revision_id')),
    'ACTIVE_DATE' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_ACTIVE_DATE',
        'default' => true),
    'EXP_DATE' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_EXP_DATE',
        'default' => true),        
        );
?>
