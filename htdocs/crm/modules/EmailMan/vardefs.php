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
$dictionary['EmailMan'] = 
array( 'table' => 'emailman', 'comment' => 'Email campaign queue', 'fields' => array(
	'date_entered' => array(
		'name' => 'date_entered',
		'vname' => 'LBL_DATE_ENTERED',
		'type' => 'datetime',
		'comment' => 'Date record created',
	),
	'date_modified' => array(
		'name' => 'date_modified',
		'vname' => 'LBL_DATE_MODIFIED',
		'type' => 'datetime',
		'comment' => 'Date record last modified',
	),
	'user_id' => array(
		'name' => 'user_id',
		'vname' => 'LBL_USER_ID',
		'type' => 'id','len' => '36',
		'reportable' =>false,
		'comment' => 'User ID representing assigned-to user',
	),
  	'id' => 
  	array (
    	'name' => 'id',
    	'vname' => 'LBL_ID',
    	'type' => 'int',
    	'len' => '11',
    	'auto_increment'=>true,
    	'comment' => 'Unique identifier',
  	),	
	'campaign_id' => array(
		'name' => 'campaign_id',
		'vname' => 'LBL_CAMPAIGN_ID',
		'type' => 'id',
		'reportable' =>false,
		'comment' => 'ID of related campaign',
	),
	'marketing_id' => array(
		'name' => 'marketing_id',
		'vname' => 'LBL_MARKETING_ID',
		'type' => 'id',
		'reportable' =>false,
		'comment' => '',
	),
	'list_id' => array(
		'name' => 'list_id',
		'vname' => 'LBL_LIST_ID',
		'type' => 'id',
		'reportable' =>false,
		'len' => '36',
		'comment' => 'Associated list',
	),
	'send_date_time' => array(
		'name' => 'send_date_time' ,
		'vname' => 'LBL_SEND_DATE_TIME',
		'type' => 'datetime',
	),
	'modified_user_id' => array(
		'name' => 'modified_user_id',
		'vname' => 'LBL_MODIFIED_USER_ID',
		'type' => 'id',
		'reportable' =>false,
		'len' => '36',
		'comment' => 'User ID who last modified record',
	),
	'in_queue' => array(
		'name' => 'in_queue',
		'vname' => 'LBL_IN_QUEUE',
		'type' => 'bool',
		'comment' => 'Flag indicating if item still in queue',
	),
	'in_queue_date' => array(
		'name' => 'in_queue_date',
		'vname' => 'LBL_IN_QUEUE_DATE',
		'type' => 'datetime',
		'comment' => 'Datetime in which item entered queue',
	),
	'send_attempts' => array(
		'name' => 'send_attempts',
		'vname' => 'LBL_SEND_ATTEMPTS',
		'type' => 'int',
		'default' => '0',
		'comment' => 'Number of attempts made to send this item',
	),
	'deleted' => array(
		'name' => 'deleted',
		'vname' => 'LBL_DELETED',
		'type' => 'bool',
		'reportable' =>false,
		'comment' => 'Record deletion indicator',
	),
	'related_id' => array(
		'name' => 'related_id',
		'vname' => 'LBL_RELATED_ID',
		'type' => 'id',
		'reportable' =>false,
		'comment' => 'ID of Sugar object to which this item is related',
	),
	'related_type' => array(
		'name' => 'related_type' ,
		'vname' => 'LBL_RELATED_TYPE',
		'type' => 'varchar',
		'len' => '100',
		'comment' => 'Descriptor of the Sugar object indicated by related_id',
	),
	'recipient_name' => array(
		'name' => 'recipient_name',
		'type' => 'varchar',
		'len' => '255',
		'source'=>'non-db',
	),
	'recipient_email' => array(
		'name' => 'recipient_email',
		'type' => 'varchar',
		'len' => '255',
		'source'=>'non-db',
	),
	'message_name' => array(
		'name' => 'message_name',
		'type' => 'varchar',
		'len' => '255',
		'source'=>'non-db',
	),
	'campaign_name' => array(
		'name' => 'campaign_name',
		'vname' => 'LBL_LIST_CAMPAIGN',
		'type' => 'varchar',
		'len' => '50',
		'source'=>'non-db',
	),

), 'indices' => array (
					array('name' => 'emailmanpk', 'type' => 'primary', 'fields' => array('id')),
					array('name' => 'idx_eman_list', 'type' => 'index', 'fields' => array('list_id','user_id','deleted')),
					array('name' => 'idx_eman_campaign_id', 'type' => 'index', 'fields' => array('campaign_id')),
					
					)
);
?>
