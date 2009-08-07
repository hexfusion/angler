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
$dictionary['prospect_list_campaigns'] = array ( 

	'table' => 'prospect_list_campaigns',

	'fields' => array (
		array (
			'name' => 'id',
			'type' => 'varchar',
			'len' => '36',
		),
		array (
			'name' => 'prospect_list_id',
			'type' => 'varchar',
			'len' => '36',
		),
		array (
			'name' => 'campaign_id',
			'type' => 'varchar',
			'len' => '36',
		),
       array ('name' => 'date_modified','type' => 'datetime'),
		array (
			'name' => 'deleted',
			'type' => 'bool',
			'len' => '1',
			'default' => '0'
		),
		
	),
	
	'indices' => array (
		array (
			'name' => 'prospect_list_campaignspk',
			'type' => 'primary',
			'fields' => array ( 'id' )
		),
		array (
			'name' => 'idx_pro_id',
			'type' => 'index',
			'fields' => array ('prospect_list_id')
		),
		array (
			'name' => 'idx_cam_id',
			'type' => 'index',
			'fields' => array ('campaign_id')
		),
		array (
			'name' => 'idx_prospect_list_campaigns', 
			'type'=>'alternate_key', 
			'fields'=>array('prospect_list_id','campaign_id')
		),		
	),

 	'relationships' => array (
		'prospect_list_campaigns' => array('lhs_module'=> 'ProspectLists', 'lhs_table'=> 'prospect_lists', 'lhs_key' => 'id',
		'rhs_module'=> 'Campaigns', 'rhs_table'=> 'campaigns', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'prospect_list_campaigns', 'join_key_lhs'=>'prospect_list_id', 'join_key_rhs'=>'campaign_id')
	)
)
                                  
?>
