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
$dictionary['contacts_cases'] = array ( 'table' => 'contacts_cases'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'varchar', 'len'=>'36')
      , array('name' =>'contact_id', 'type' =>'varchar', 'len'=>'36', )
      , array('name' =>'case_id', 'type' =>'varchar', 'len'=>'36', )
      , array('name' =>'contact_role', 'type' =>'varchar', 'len'=>'50')
      , array ('name' => 'date_modified','type' => 'datetime')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0','required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'contacts_casespk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_con_case_con', 'type' =>'index', 'fields'=>array('contact_id'))
      , array('name' =>'idx_con_case_case', 'type' =>'index', 'fields'=>array('case_id'))
      , array('name' => 'idx_contacts_cases', 'type'=>'alternate_key', 'fields'=>array('contact_id','case_id'))                  
                                                      )
 	  , 'relationships' => array ('contacts_cases' => array('lhs_module'=> 'Contacts', 'lhs_table'=> 'contacts', 'lhs_key' => 'id',
							  'rhs_module'=> 'Cases', 'rhs_table'=> 'cases', 'rhs_key' => 'id',
							  'relationship_type'=>'many-to-many',
							  'join_table'=> 'contacts_cases', 'join_key_lhs'=>'contact_id', 'join_key_rhs'=>'case_id'))
                                  
)
?>
