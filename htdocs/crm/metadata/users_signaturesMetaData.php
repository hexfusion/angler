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

///////////////////////////////////////////////////////////////////////////////
////	TABLE DEFINITION FOR EMAIL STUFF
$dictionary['UserSignature'] = array(
	'table' => 'users_signatures',
	'fields' => array(
		'id' => array(
			'name'		=> 'id',
			'vname'		=> 'LBL_ID',
			'type'		=> 'id',
			'required'	=> true,
		),
		'date_entered' => array (
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			'required'=>true,
		),
		'date_modified' => array (
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required'=>true,
		),
		'deleted' => array (
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'reportable'=>false,
		),
		'user_id' => array(
			'name' => 'user_id',
			'vname' => 'LBL_USER_ID',
			'type' => 'varchar',
			'len' => 36,
		),  
		'name' => array(
			'name' => 'name',
			'vname' => 'LBL_SUBJECT',
			'type' => 'varchar',
			'required' => false,
			'len' => '255',
		),
		'signature' => array(
			'name' => 'signature',
			'vname' => 'LBL_SIGNATURE',
			'type' => 'text',
			'reportable' => false,
		),
		'signature_html' => array(
			'name' => 'signature_html',
			'vname' => 'LBL_SIGNATURE_HTML',
			'type' => 'text',
			'reportable' => false,
		),
	),
	'indices' => array(
		array(
			'name' => 'users_signaturespk',
			'type' =>'primary',
			'fields' => array('id')
		),
		array(
			'name' => 'idx_usersig_uid',
			'type' => 'index',
			'fields' => array('user_id')
		)
	),
);
?>
