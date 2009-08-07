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
$dictionary['accounts_opportunities'] = array ( 'table' => 'accounts_opportunities'
      , 'fields' => array (
       array('name' =>'id', 'type' =>'varchar', 'len'=>'36')
      , array('name' =>'opportunity_id', 'type' =>'varchar', 'len'=>'36', )
      , array('name' =>'account_id', 'type' =>'varchar', 'len'=>'36', )
      , array ('name' => 'date_modified','type' => 'datetime')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0', 'required'=>true)
      )                                  
      , 'indices' => array (
       array('name' =>'accounts_opportunitiespk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_acc_opp_acc', 'type' =>'index', 'fields'=>array('account_id'))
      , array('name' =>'idx_acc_opp_opp', 'type' =>'index', 'fields'=>array('opportunity_id'))
      , array('name' =>'idx_a_o_opp_acc_del', 'type' =>'index', 'fields'=>array('opportunity_id','account_id','deleted'))
      , array('name' => 'idx_account_opportunity', 'type'=>'alternate_key', 'fields'=>array('account_id','opportunity_id'))      
      )
      
      ,'relationships' => array ('accounts_opportunities' => array('lhs_module'=> 'Accounts', 'lhs_table'=> 'accounts', 'lhs_key' => 'id',
							  'rhs_module'=> 'Opportunities', 'rhs_table'=> 'opportunities', 'rhs_key' => 'id',
							  'relationship_type'=>'many-to-many',
							  'join_table'=> 'accounts_opportunities', 'join_key_lhs'=>'account_id', 'join_key_rhs'=>'opportunity_id'))
	  	
)
?>
