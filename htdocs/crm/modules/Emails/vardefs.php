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
$dictionary['Email'] = array(
    'table' => 'emails', 
    'comment' => 'Contains a record of emails sent to and from the Sugar application',
	'fields' => array (
		'id' => array (
			'name' => 'id',
			'vname' => 'LBL_ID',
			'type' => 'id',
			'required' => true,
			'reportable'=>true,
			'comment' => 'Unique identifier',
		),
		'date_entered' => array (
			'name' => 'date_entered',
			'vname' => 'LBL_DATE_ENTERED',
			'type' => 'datetime',
			'required'=>true,
			'comment' => 'Date record created',
		),
		'date_modified' => array (
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required'=>true,
			'comment' => 'Date record last modified',
		),
		'assigned_user_id' => array (
			'name' => 'assigned_user_id',
			'rname' => 'user_name',
			'id_name' => 'assigned_user_id',
			'vname' => 'LBL_ASSIGNED_TO',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'isnull' => 'false',
			'reportable'=>true,
			'dbType' => 'id',
			'comment' => 'User ID that last modified record',
		),
		'assigned_user_name' => array (
			'name' => 'assigned_user_name',
			'vname' => 'LBL_ASSIGNED_TO',
			'type' => 'varchar',
			'reportable'=>false,
			'source'=>'nondb',
			'table' => 'users',
		),
		'modified_user_id' => array (
			'name' => 'modified_user_id',
			'rname' => 'user_name',
			'id_name' => 'modified_user_id',
			'vname' => 'LBL_MODIFIED_BY',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'isnull' => 'false',
			'reportable'=>true,
			'dbType' => 'id',
			'comment' => 'User ID that last modified record',
		),
		'created_by' => array (
			'name' => 'created_by',
			'vname' => 'LBL_CREATED_BY',
			'type' => 'id',
			'len'=> '36',
			'reportable' => false,
			'comment' => 'User name who created record',
		),










		'name' => array (
			'name' => 'name',
			'vname' => 'LBL_SUBJECT',
			'type' => 'varchar',
			'required' => false,
			'len' => '255',
			'comment' => 'The subject of the email',
		),
		'date_start' => array (
			'name' => 'date_start',
			'vname' => 'LBL_DATE',
			'type' => 'date',
			'len' => '255',
			'rel_field' => 'time_start',
			'massupdate'=>false,
			'comment' => 'Date of last inbound email check',
		),
		'time_start' => array (
			'name' => 'time_start',
			'vname' => 'LBL_TIME',
			'type' => 'time',
			'len' => '255',
			'rel_field' => 'date_start',
			'comment' => 'Time of last inbound email check',
		),
		'parent_type' => array (
			'name' => 'parent_type',
			'type' => 'varchar',
			'reportable'=>false,
			'len' => '25',
			'comment' => 'Identifier of Sugar module to which this email is associated (deprecated as of 4.2)',
		),
		'parent_name' => array (
			'name' => 'parent_name',
			'type' => 'varchar',
			'reportable'=>false,
			'source'=>'nodb',
		),
		'parent_id' => array (
			'name' => 'parent_id',
			'type' => 'id',
			'len' => '36',
			'reportable'=>false,
			'comment' => 'ID of Sugar object referenced by parent_type (deprecated as of 4.2)',
		), 
		'description' => array (
			'name' => 'description',
			'vname' => 'LBL_TEXT_BODY',
			'type' => 'text',
			'comment' => 'Email body in plain text',
		),
		'description_html' => array (
			'name' => 'description_html',
			'vname' => 'LBL_HTML_BODY',
			'type' => 'text',
			'comment' => 'Email body in HTML format',
		),
		'from_addr' => array (
			'name' => 'from_addr',
			'vname' => 'LBL_FROM',
			'type' => 'varchar',
			'len' => '100',
			'comment' => 'Email address of the person sending the email',
		),
		'from_name' => array (
			'name' => 'from_name',
			'vname' => 'LBL_FROM_NAME',
			'type' => 'varchar',
			'len' => '100',
			'comment' => 'Name of the person sending the email',
		),
		'to_addrs' => array (
			'name' => 'to_addrs',
			'vname' => 'LBL_TO',
			'type' => 'text',
			'comment' => 'Email address(es) of person(s) to receive the email',
		),
		'cc_addrs' => array (
			'name' => 'cc_addrs',
			'vname' => 'LBL_CC',
			'type' => 'text',
			'comment' => 'Email address(es) of person(s) to receive a carbon copy of the email',
		),
		'bcc_addrs' => array (
			'name' => 'bcc_addrs',
			'vname' => 'LBL_BCC',
			'type' => 'text',
			'comment' => 'Email address(es) of person(s) to receive a blind carbon copy of the email',
		),
		'to_addrs_ids' => array (
			'name' => 'to_addrs_ids',
			'type' => 'text',
			'reportable'=>false,
			'comment' => 'Sugar ID(s) of person(s) to receive the email',
		),
		'to_addrs_names' => array (
			'name' => 'to_addrs_names',
			'type' => 'text',
			'reportable'=>false,
			'comment' => 'Name(s) of person(s) to receive the email',
		),
		'to_addrs_emails' => array (
			'name' => 'to_addrs_emails',
			'type' => 'text',
			'reportable'=>false,
		),
		'cc_addrs_ids' => array (
			'name' => 'cc_addrs_ids',
			'type' => 'text',
			'reportable'=>false,
			'comment' => 'Sugar ID(s) of person(s) to receive carbon copy of the email',
		),
		'cc_addrs_names' => array (
			'name' => 'cc_addrs_names',
			'type' => 'text',
			'reportable'=>false,
			'comment' => 'Name(s) of person(s) to receive carbon copy of the email',
		),
		'cc_addrs_emails' => array (
			'name' => 'cc_addrs_emails',
			'type' => 'text',
			'reportable'=>false,
		),
		'bcc_addrs_ids' => array (
			'name' => 'bcc_addrs_ids',
			'type' => 'text',
			'reportable'=>false,
			'comment' => 'Sugar ID(s) of person(s) to receive blind carbon copy of the email',
		),
		'bcc_addrs_names' => array (
			'name' => 'bcc_addrs_names',
			'type' => 'text',
			'reportable'=>false,
			'comment' => 'Name(s) of person(s) to receive blind carbon copy of the email',
		),
		'bcc_addrs_emails' => array (
			'name' => 'bcc_addrs_emails',
			'type' => 'text',
			'reportable'=>false,
		),
		'type' => array (
			'name' => 'type',
			'vname' => 'LBL_LIST_TYPE',
			'type' => 'enum',
			'options' => 'dom_email_types',
			'len' => '25',
			'massupdate'=>false,
			'comment' => 'Type of email (ex: draft)',
		),
		'status' => array (
			'name' => 'status',
			'vname' => 'LBL_STATUS',
			'type' => 'enum',
			'len' => '25',
			'options' => 'dom_email_status',
		),
		'message_id' => array (
			'name' => 'message_id',
			'vname' => 'LBL_MESSAGE_ID',
			'type' => 'varchar',
			'len' => '25',
			'comment' => 'ID of the email item obtained from the email transport system',
		),
		'reply_to_name' => array (
			'name' => 'reply_to_name',
			'vname' => 'LBL_REPLY_TO_NAME',
			'type' => 'varchar',
			'len' => '100',
			'comment' => 'Name of person indicated in the Reply-to email field',
		),
		'reply_to_addr' => array (
			'name' => 'reply_to_addr',
			'vname' => 'LBL_REPLY_TO_ADDRESS',
			'type' => 'varchar',
			'len' => '100',
			'comment' => 'Email address of person indicated in the Reply-to email field',
		),
		'intent' => array (
			'name'	=> 'intent',
			'vname' => 'LBL_INTENT',
			'type'	=> 'varchar',
			'len'	=> 25,
			'default'	=> 'pick',
			'comment' => 'Target of action used in Inbound Email assignment',
		),
		'message_id' => array (
			'name'		=> 'message_id',
			'vname' 	=> 'LBL_MESSAGE_ID',
			'type'		=> 'varchar',
			'len'		=> 100,
			'comment' => 'ID of the email item obtained from the email transport system',
		),
		'mailbox_id' => array (
			'name' => 'mailbox_id',
			'vname' => 'LBL_MAILBOX_ID',
			'type' => 'id',
			'len'=> '36',
			'reportable' => false,
		),
        'raw_source' => array (
            'name' => 'raw_source',
            'vname' => 'LBL_RAW',
            'type' => 'text',
            'reportable' => false,
        ),
		'deleted' => array (
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'reportable'=>false,
			'comment' => 'Record deletion indicator',
		),
		'link_action' => array (
			'name' => 'link_action',
			'type' => 'text',
			'source'=>'non-db',
		),
		'first_name'=> array(
			'name'=>'first_name',
			'rname'=>'first_name',
			'id_name'=>'contact_id',
			'vname'=>'LBL_CONTACT_FIRST_NAME',
			'type'=>'relate',
			'link'=>'contacts',
			'table'=>'contacts',
			'isnull'=>'true',
			'module'=>'Contacts',
			'source'=>'non-db',
			'massupdate'=>false,
		),
		'last_name'=> array(
			'name'=>'last_name',
			'rname'=>'last_name',
			'id_name'=>'contact_id',
			'vname'=>'LBL_CONTACT_LAST_NAME',
			'type'=>'relate',
			'link'=>'contacts',
			'table'=>'contacts',
			'isnull'=>'true',
			'module'=>'Contacts',
			'source'=>'non-db',
			'massupdate'=>false,
		),
         'contact_name' => array (
            'name' => 'contact_name',
            'rname' => 'last_name',
            'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
            'id_name' => 'contact_id',
            'massupdate' => false,
            'vname' => 'LBL_CONTACT_NAME',
            'type' => 'relate',
            'link'=>'contacts',
            'table' => 'contacts',
            'isnull' => 'true',
            'module' => 'Contacts',
        
            'dbType' => 'varchar',
            'source'=>'non-db',
            'len' => 36,
        ),
    












		'created_by_link' => array (
			'name' => 'created_by_link',
			'type' => 'link',
			'relationship' => 'emails_created_by',
			'vname' => 'LBL_CREATED_BY_USER',
			'link_type' => 'one',
			'module'=>'Users',
			'bean_name'=>'User',
			'source'=>'non-db',
		),
		'modified_user_link' => array (
			'name' => 'modified_user_link',
			'type' => 'link',
			'relationship' => 'emails_modified_user',
			'vname' => 'LBL_MODIFIED_BY_USER',
			'link_type' => 'one',
			'module'=>'Users',
			'bean_name'=>'User',
			'source'=>'non-db',
		),
		'assigned_user_link' => array (
			'name' => 'assigned_user_link',
			'type' => 'link',
			'relationship' => 'emails_assigned_user',
			'vname' => 'LBL_ASSIGNED_TO_USER',
			'link_type' => 'one',
			'module'=>'Users',
			'bean_name'=>'User',
			'source'=>'non-db',
		),
		'date_sent' => array (
			'name'			=> 'date_sent',
			'vname'			=> 'LBL_DATE_SENT',
			'type'			=> 'datetime',
			'source'		=> 'non-db',
		),
		/* relationship collection attributes */
		/* added to support InboundEmail */
		'accounts'	=> array (
			'name'			=> 'accounts',
			'vname'			=> 'LBL_EMAILS_ACCOUNTS_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_accounts_rel',
			'module'		=> 'Accounts',
			'bean_name'		=> 'Account',
			'source'		=> 'non-db',
		),
		'bugs'	=> array (
			'name'			=> 'bugs',
			'vname'			=> 'LBL_EMAILS_BUGS_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_bugs_rel',
			'module'		=> 'Bugs',
			'bean_name'		=> 'Bug',
			'source'		=> 'non-db',
		),
		'cases'	=> array (
			'name'			=> 'cases',
			'vname'			=> 'LBL_EMAILS_CASES_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_cases_rel',
			'module'		=> 'Cases',
			'bean_name'		=> 'Case',
			'source'		=> 'non-db',
		),
		'contacts'	=> array (
			'name'			=> 'contacts',
			'vname'			=> 'LBL_EMAILS_CONTACTS_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_contacts_rel',
			'module'		=> 'Contacts',
			'bean_name'		=> 'Contact',
			'source'		=> 'non-db',
		),
		'leads'	=> array (
			'name'			=> 'leads',
			'vname'			=> 'LBL_EMAILS_LEADS_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_leads_rel',
			'module'		=> 'Leads',
			'bean_name'		=> 'Lead',
			'source'		=> 'non-db',
		),		
		'opportunities'	=> array (
			'name'			=> 'opportunities',
			'vname'			=> 'LBL_EMAILS_OPPORTUNITIES_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_opportunities_rel',
			'module'		=> 'Opportunities',
			'bean_name'		=> 'Opportunity',
			'source'		=> 'non-db',
		),
		'project'=> array(
			'name'			=> 'project',
			'vname'			=> 'LBL_EMAILS_PROJECT_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_projects_rel',
			'module'		=> 'Project',
			'bean_name'		=> 'Project',
			'source'		=> 'non-db',
		),
		'projecttask'=> array(
			'name'			=> 'projecttask',
			'vname'			=> 'LBL_EMAILS_PROJECT_TASK_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_project_task_rel',
			'module'		=> 'ProjectTask',
			'bean_name'		=> 'ProjectTask',
			'source'		=> 'non-db',
		),
		'prospects'=> array(
			'name'			=> 'prospects',
			'vname'			=> 'LBL_EMAILS_PROSPECT_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_prospects_rel',
			'module'		=> 'Prospects',
			'bean_name'		=> 'Prospect',
			'source'		=> 'non-db',
		),











		'tasks'=> array(
			'name'			=> 'tasks',
			'vname'			=> 'LBL_EMAILS_TASKS_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_tasks_rel',
			'module'		=> 'Tasks',
			'bean_name'		=> 'Task',
			'source'		=> 'non-db',
		),
		'users'=> array(
			'name'			=> 'users',
			'vname'			=> 'LBL_EMAILS_USERS_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_users_rel',
			'module'		=> 'Users',
			'bean_name'		=> 'User',
			'source'		=> 'non-db',
		),
		'notes' => array(
			'name'			=> 'notes',
			'vname'			=> 'LBL_EMAILS_NOTES_REL',
			'type'			=> 'link',
			'relationship'	=> 'emails_notes_rel',
			'module'		=> 'Notes',
			'bean_name'		=> 'Note',
			'source'		=> 'non-db',
		),
		/* end relationship collections */

	), /* end fields() array */
	'relationships' => array(
		'emails_assigned_user'	=> array(
			'lhs_module'		=> 'Users',
			'lhs_table'			=> 'users', 
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Emails',
			'rhs_table'			=> 'emails',
			'rhs_key'			=> 'assigned_user_id',
			'relationship_type'	=>'one-to-many'
		),
		'emails_modified_user'	=> array(
			'lhs_module'		=> 'Users',
			'lhs_table'			=> 'users',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Emails',
			'rhs_table'			=> 'emails',
			'rhs_key'			=> 'modified_user_id',
			'relationship_type'	=>'one-to-many'
		),
		'emails_created_by'		=> array(
			'lhs_module'		=> 'Users',
			'lhs_table'			=> 'users',
			'lhs_key'			=> 'id',
			'rhs_module'		=> 'Emails',
			'rhs_table'			=> 'emails',
			'rhs_key'			=> 'created_by',
			'relationship_type'	=>'one-to-many'
		),











		'emails_notes_rel' => array(
			'lhs_module'	=> 'Emails',
			'lhs_table'		=> 'emails',
			'lhs_key'		=> 'id',
			'rhs_module'	=> 'Notes',
			'rhs_table'		=> 'notes',
			'rhs_key'		=> 'parent_id',
			'relationship_type'=> 'one-to-many',
		),
	), // end relationships
	'indices' => array (
		array('name' =>'emailspk', 'type' =>'primary', 'fields'=>array('id')),
 		array('name' =>'idx_email_name', 'type'=>'index', 'fields'=>array('name')),
 		array('name' =>'idx_message_id', 'type'=>'index', 'fields'=>array('message_id')),
 		array('name' =>'idx_email_parent_id', 'type'=>'index', 'fields'=>array('parent_id')),
 		



 		array('name' =>'idx_email_assigned', 'type'=>'index', 'fields'=>array('assigned_user_id', 'type','status')),
	) // end indices
);
?>
