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
$dictionary['meetings_contacts'] = array(
	'table'=> 'meetings_contacts',
	'fields'=> array(
		array(	'name'			=> 'id', 
				'type'			=> 'varchar', 
				'len'			=> '36'
		),
		array(	'name'			=> 'meeting_id', 
				'type'			=> 'varchar', 
				'len'			=> '36',
		),
		array(	'name'			=> 'contact_id', 
				'type'			=> 'varchar', 
				'len'			=> '36',
		),
		array(	'name'			=> 'required', 
				'type'			=> 'varchar', 
				'len'			=> '1', 
				'default'		=> '1',
		),
		array(	'name'			=> 'accept_status', 
				'type'			=> 'varchar', 
				'len'			=> '25', 
				'default'		=> 'none'
		),
		array(	'name'			=> 'date_modified',
				'type'			=> 'datetime'
		),
		array(	'name'			=> 'deleted', 
				'type'			=> 'bool', 
				'len'			=> '1', 
				'default'		=> '0', 
				'required'		=> true
		),
 	), 
	'indices' => array(
 		array(	'name'			=> 'meetings_contactspk', 
				'type'			=> 'primary', 
				'fields'		=> array('id'),
		),
		array(	'name'			=> 'idx_con_mtg_mtg', 
				'type'			=> 'index', 
				'fields'		=> array('meeting_id'),
		),
		array(	'name'			=> 'idx_con_mtg_con', 
				'type'			=> 'index', 
				'fields'		=> array('contact_id'),
		),
		array(	'name'			=> 'idx_meeting_contact', 
				'type'			=> 'alternate_key', 
				'fields'		=> array('meeting_id','contact_id'),
		),
	),
	'relationships' => array(
		'meetings_contacts' => array(
			'lhs_module'		=> 'Meetings', 
			'lhs_table'			=> 'meetings', 
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Contacts', 
			'rhs_table'			=> 'contacts', 
			'rhs_key'			=> 'id',
			'relationship_type'	=> 'many-to-many',
			'join_table'		=> 'meetings_contacts', 
			'join_key_lhs'		=> 'meeting_id', 
			'join_key_rhs'		=> 'contact_id',
		),
	),
);
?>
