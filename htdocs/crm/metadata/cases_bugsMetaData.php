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
$dictionary['cases_bugs'] = array ( 'table' => 'cases_bugs'
                                  , 'fields' => array (
       array('name' =>'id', 'type' =>'varchar', 'len'=>'36')
      , array('name' =>'case_id', 'type' =>'varchar', 'len'=>'36')
      , array('name' =>'bug_id', 'type' =>'varchar', 'len'=>'36')
      , array ('name' => 'date_modified','type' => 'datetime')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
                                                      )                                  , 'indices' => array (
       array('name' =>'cases_bugspk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_cas_bug_cas', 'type' =>'index', 'fields'=>array('case_id'))
      , array('name' =>'idx_cas_bug_bug', 'type' =>'index', 'fields'=>array('bug_id'))
      , array('name' => 'idx_case_bug', 'type'=>'alternate_key', 'fields'=>array('case_id','bug_id'))            
      
                                                      )
 	  , 'relationships' => array ('cases_bugs' => array('lhs_module'=> 'Cases', 'lhs_table'=> 'cases', 'lhs_key' => 'id',
							  'rhs_module'=> 'Bugs', 'rhs_table'=> 'bugs', 'rhs_key' => 'id',
							  'relationship_type'=>'many-to-many',
							  'join_table'=> 'cases_bugs', 'join_key_lhs'=>'case_id', 'join_key_rhs'=>'bug_id'))
                                                      
                                  )
?>
