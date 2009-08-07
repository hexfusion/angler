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
$dictionary['linked_documents'] = array ( 'table' => 'linked_documents'
   , 'fields' => array (
        array('name' =>'id', 'type' =>'varchar', 'len'=>'36')
      , array('name' =>'parent_id', 'type' =>'varchar', 'len'=>'36')
      , array('name' =>'parent_type', 'type' =>'varchar', 'len'=>'25')      
      , array('name' =>'document_id', 'type' =>'varchar', 'len'=>'36')
      , array('name' =>'document_revision_id', 'type' =>'varchar', 'len'=>'36')
      , array('name' =>'date_modified','type' => 'datetime')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
   )   
   , 'indices' => array (
        array('name' =>'linked_documentspk', 'type' =>'primary', 'fields'=>array('id'))
   )
   , 'relationships' => array (
			'contracts_documents' => array('lhs_module'=> 'contracts', 'lhs_table'=> 'contracts', 'lhs_key' => 'id',
				   'rhs_module'=> 'documents', 'rhs_table'=> 'documents', 'rhs_key' => 'id',
				   'relationship_type'=>'many-to-many',
				   'join_table'=> 'linked_documents', 'join_key_lhs'=>'parent_id', 'join_key_rhs'=>'document_id', 'relationship_role_column'=>'parent_type',
				   'relationship_role_column_value'=>'Contracts'),
			'leads_documents' => array('lhs_module'=> 'leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
				   'rhs_module'=> 'documents', 'rhs_table'=> 'documents', 'rhs_key' => 'id',
				   'relationship_type'=>'many-to-many',
				   'join_table'=> 'linked_documents', 'join_key_lhs'=>'parent_id', 'join_key_rhs'=>'document_id', 'relationship_role_column'=>'parent_type',
				   'relationship_role_column_value'=>'Leads'),
			'contracttype_documents' => array('lhs_module'=> 'ContracTemplates', 'lhs_table'=> 'contract_types', 'lhs_key' => 'id',
				   'rhs_module'=> 'documents', 'rhs_table'=> 'documents', 'rhs_key' => 'id',
				   'relationship_type'=>'many-to-many',
				   'join_table'=> 'linked_documents', 'join_key_lhs'=>'parent_id', 'join_key_rhs'=>'document_id', 'relationship_role_column'=>'parent_type',
				   'relationship_role_column_value'=>'ContracTemplates'),
			),
   );
?>
