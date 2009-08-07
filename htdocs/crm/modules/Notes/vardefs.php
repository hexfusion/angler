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
$dictionary['Note'] = array('table' => 'notes', 'comment' => 'Notes and Attachments'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required'=>true,
    'reportable'=>false,
    'comment' => 'Unique identifier'
  ),
   'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required' => true,
    'comment' => 'Date record created'
  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required' => true,
    'comment' => 'Date record last modified'
  ),
    'modified_user_id' => 
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_MODIFIED',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'reportable'=>true,
    'comment' => 'User who last modified record'
  ),
  'created_by' => 
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'comment' => 'User who created record'
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_NOTE_SUBJECT',
    'dbType' => 'varchar',
    'type' => 'name',
    'len' => '255',
    'comment' => 'Name of the note'
  ),
  'filename' => 
  array (
    'name' => 'filename',
    'vname' => 'LBL_FILENAME',
    'type' => 'varchar',
    'len' => '255',
    'reportable'=>true,
    'comment' => 'File name associated with the note (attachment)'
  ),
  'file_mime_type' => 
  array (
    'name' => 'file_mime_type',
    'vname' => 'LBL_FILE_MIME_TYPE',
    'type' => 'varchar',
    'len' => '100',
    'comment' => 'Attachment MIME type'
  ),
  'file_url'=>
  array(
  	'name'=>'file_url',
    'vname' => 'LBL_FILE_URL',
  	'type'=>'function',
  	'function_require'=>'include/upload_file.php',
  	'function_class'=>'UploadFile',
  	'function_name'=>'get_url',
  	'function_params'=> array('filename', 'id'),
  	'source'=>'function',
  	'reportable'=>false,
  	'comment' => 'Path to file (can be URL)'
  	),
  'parent_type'=>
  array(
  	'name'=>'parent_type',
  	'vname'=>'LBL_PARENT_TYPE',
  	'type'=>'varchar',
  	'len'=> '25',
  	'comment' => 'Sugar module the Note is associated with'
  ),
  'parent_id'=>
  array(
  	'name'=>'parent_id',
  	'vname'=>'LBL_PARENT_ID',
  	'type'=>'id',
  	'required'=>false,
  	'reportable'=>false,
  	'comment' => 'The ID of the Sugar item specified in parent_type'
  ),
  'contact_id'=>
  array(
  	'name'=>'contact_id',
  	'vname'=>'LBL_CONTACT_ID',
  	'type'=>'id',
  	'required'=>false,
  	'reportable'=>false,
  	'comment' => 'Contact ID note is associated with'
  ),
  'portal_flag' => 
  array (
    'name' => 'portal_flag',
    'vname' => 'LBL_PORTAL_FLAG',
    'type' => 'bool',
	'required' => true,
	'comment' => 'Portal flag indicator determines if note created via portal'
  ),
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
    'comment' => 'Full text of the note'
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'default' => '0',
    'reportable'=>false,
    'comment' => 'Record deletion indicator'
  ),













 'parent_name'=>
 	array(
		'name'=> 'parent_name', 
		'parent_type'=>'record_type_display' , 
		'type_name'=>'parent_type',
		'id_name'=>'parent_id', 'vname'=>'LBL_RELATED_TO', 
		'type'=>'parent',
		'source'=>'non-db',
		),

 'contact_name'=> 
 	array(
		'name'=>'contact_name',
		'rname'=>'last_name',
		'id_name'=>'contact_id',
		'vname'=>'LBL_CONTACT_NAME',
		'type'=>'relate',
		'link'=>'contact',
		'table'=>'contacts',
		'isnull'=>'true',
		'module'=>'Contacts',
		'source'=>'non-db',
		'db_concat_fields'=> array(0=>'first_name', 1=>'last_name'),
		),  
















  'account_id' => 
  array (
    'name' => 'account_id',
    'vname' => 'LBL_ACCOUNT_ID',
    'type' => 'id',
    'reportable'=>false,
	'source'=>'non-db',
  ),
  'opportunity_id' => 
  array (
    'name' => 'opportunity_id',
    'vname' => 'LBL_OPPORTUNITY_ID',
    'type' => 'id',
    'reportable'=>false,
	'source'=>'non-db',
  ),
  'acase_id' => 
  array (
    'name' => 'acase_id',
    'vname' => 'LBL_CASE_ID',
    'type' => 'id',
    'reportable'=>false,
	'source'=>'non-db',
  ),
  'lead_id' => 
  array (
    'name' => 'lead_id',
    'vname' => 'LBL_LEAD_ID',
    'type' => 'id',
    'reportable'=>false,
	'source'=>'non-db',
  ),































  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'notes_created_by',
    'vname' => 'LBL_CREATED_BY_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
  'modified_user_link' =>
  array (
        'name' => 'modified_user_link',
    'type' => 'link',
    'relationship' => 'notes_modified_user',
    'vname' => 'LBL_MODIFIED_BY_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),

  'contact' =>
  array (
    'name' => 'contact',
    'type' => 'link',
    'relationship' => 'contact_notes',
    'vname' => 'LBL_LIST_CONTACT_NAME',
    'source'=>'non-db',
  ),

),
'relationships'=>array(
'notes_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'notes_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')







)
                                                      , 'indices' => array (
       array('name' =>'notespk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_note_name', 'type'=>'index', 'fields'=>array('name')),
       array('name' =>'idx_notes_parent', 'type'=>'index', 'fields'=>array('parent_id', 'parent_type')),
       array('name' =>'idx_note_contact', 'type'=>'index', 'fields'=>array('contact_id')),
                                                      )
                                                      
                                                      
                                                      //This enables optimistic locking for Saves From EditView
	,'optimistic_locking'=>true,
                            );
?>
