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
$dictionary['Document'] = array('table' => 'documents'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_DOCUMENT_ID',
    'type' => 'varchar',
    'len' => '36',
    'required'=>true,
    'reportable'=>false,
  ),

  'document_name' => 
  array (
    'name' => 'document_name',
    'vname' => 'LBL_NAME',
    'type' => 'varchar',
    'len' => '255',
    'required'=>true
  ),
'active_date' => 
  array (
    'name' => 'active_date',
    'vname' => 'LBL_DOC_ACTIVE_DATE',
    'type' => 'date',
  ),

'exp_date' => 
  array (
    'name' => 'exp_date',
    'vname' => 'LBL_DOC_EXP_DATE',
    'type' => 'date',
  ),
  
  'description' => 
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),

  'category_id' => 
  array (
    'name' => 'category_id',
    'vname' => 'LBL_SF_CATEGORY',
    'type' => 'enum',
    'len' => '25',
    'options' => 'document_category_dom', 
    'reportable'=>false,   
  ),

  'subcategory_id' => 
  array (
    'name' => 'subcategory_id',
    'vname' => 'LBL_SF_SUBCATEGORY',
    'type' => 'enum',
    'len' => '25',
    'options' => 'document_subcategory_dom', 
    'reportable'=>false,   
  ),

  'status_id' => 
  array (
    'name' => 'status_id',
    'vname' => 'LBL_DOC_STATUS',
    'type' => 'enum',
    'len' => '25',
    'options' => 'document_status_dom',
    'reportable'=>false,    
  ),

  'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
  ),

  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
  ),

  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'default'=> 0,
    'reportable'=>false,
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
    'reportable'=>true,
    'dbType' => 'id'
  ), 
  'created_by' => 
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_CREATED',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id'
  ),
  


























      
  'document_revision_id'=>
  array (
    'name' => 'document_revision_id',
    'vname' => 'LBL_LATEST_REVISION',
    'type' => 'varchar',
    'len' => '36',
    'reportable'=>false,
  ),  

  'revisions' =>
  array (
    'name' => 'revisions',
    'type' => 'link',
    'relationship' => 'document_revisions',
    'source'=>'non-db',
        'vname'=>'LBL_REVISIONS',
  ),
  'latest_revision' =>
  array (
    'name' => 'latest_revision',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'non-db'
  ),
  'last_rev_create_date' =>
  array (
    'name' => 'last_rev_create_date',
    'type' => 'relate',
    'table' => 'document_revisions',
    'link'  => 'revisions',
    'join_name'  => 'document_revisions',
    'rname'=> 'date_entered',
    'reportable'=>false,
    'source'=>'non-db'
  ),
  /* mail_merge_document has been deprecated*/
 'mail_merge_document' => 
  array (
    'name' => 'mail_merge_document',
    'vname' => 'LBL_MAIL_MERGE_DOCUMENT',
    'type' => 'bool',
    'dbType' => 'varchar',
    'len' => '3',
    'default' => 'off',
    'audited'=>true,
  ),













  'contracts' => array (
    'name' => 'contracts',
    'type' => 'link',
    'relationship' => 'contracts_documents',
    'source' => 'non-db',
    'vname' => 'LBL_CONTRACTS',
  ),
  //todo remove
  'leads' => array (
    'name' => 'leads',
    'type' => 'link',
    'relationship' => 'leads_documents',
    'source' => 'non-db',
    'vname' => 'LBL_CONTRACTS',
  ),
  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'documents_created_by',
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
    'relationship' => 'documents_modified_user',
    'vname' => 'LBL_MODIFIED_BY_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),

  'related_doc_id' => 
  array (
    'name' => 'related_doc_id',
    'vname' => 'LBL_RELATED_DOCUMENT_ID',
    'reportable'=>false,
    'dbtype' => 'id',
    'type' => 'varchar',
    'len' => '36',
  ),
  'related_doc_rev_id' => 
  array (
    'name' => 'related_doc_rev_id',
    'vname' => 'LBL_RELATED_DOCUMENT_REVISION_ID',
    'reportable'=>false,
    'dbtype' => 'id',
    'type' => 'varchar',
    'len' => '36',    
  ),
  'is_template' => 
  array (
    'name' => 'is_template',
    'vname' => 'LBL_IS_TEMPLATE',
    'type' => 'bool',
    'default'=> 0,
    'reportable'=>false,
  ),
  'template_type' => 
  array (
    'name' => 'template_type',
    'vname' => 'LBL_TEMPLATE_TYPE',
    'type' => 'enum',
    'len' => '25',
    'options' => 'document_template_type_dom', 
    'reportable'=>false,   
  ),
//BEGIN field used for contract document subpanel.
  'latest_revision_name' =>
  array (
    'name' => 'latest_revision_name',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'non-db'
  ),
  'selected_revision_name' =>
  array (
    'name' => 'selected_revision_name',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'non-db'
  ),
  'contract_status' =>
  array (
    'name' => 'contract_status',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'non-db'
  ),
  'contract_name'=>
  array (
    'name' => 'contract_name',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'non-db'
  ),
  'linked_id'=>
  array (
    'name' => 'linked_id',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'non-db'
  ),
  'selected_revision_id'=>
  array (
    'name' => 'selected_revision_id',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'non-db'
  ),
  'latest_revision_id'=>
  array (
    'name' => 'latest_revision_id',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'non-db'
  ),
  'selected_revision_filename'=>
  array (
    'name' => 'selected_revision_filename',
    'type' => 'varchar',
    'reportable'=>false,
    'source'=>'non-db'
  ),

//END fields used for contract documents subpanel.

),
 'indices' => array (
       array('name' =>'documentspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_doc_cat', 'type' =>'index', 'fields'=>array('category_id', 'subcategory_id')),       
       ),
 'relationships' => array (
    'document_revisions' => array('lhs_module'=> 'Documents', 'lhs_table'=> 'documents', 'lhs_key' => 'id',
                              'rhs_module'=> 'Documents', 'rhs_table'=> 'document_revisions', 'rhs_key' => 'document_id',   
                              'relationship_type'=>'one-to-many')

   ,'documents_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Documents', 'rhs_table'=> 'documents', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'documents_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Documents', 'rhs_table'=> 'documents', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')






    ),
       
);
?>
