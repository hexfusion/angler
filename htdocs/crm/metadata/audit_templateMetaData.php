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
/* this table should never get created, it should only be used as a template for the acutal audit tables
 * for each moudule.
 */
$dictionary['audit'] = 
		array ( 'table' => 'audit',
              	'fields' => array (
              	      'id'=> array('name' =>'id', 'type' =>'id', 'len'=>'36','required'=>true), 
              	      'parent_id'=>array('name' =>'parent_id', 'type' =>'id', 'len'=>'36','required'=>true),               	                   	
				      'date_created'=>array('name' =>'date_created','type' => 'datetime'),
				      'created_by'=>array('name' =>'created_by','type' => 'varchar','len' => 36),				
					  'field_name'=>array('name' =>'field_name','type' => 'varchar','len' => 100),
					  'data_type'=>array('name' =>'data_type','type' => 'varchar','len' => 100),
					  'before_value_string'=>array('name' =>'before_value_string','type' => 'varchar'),
					  'after_value_string'=>array('name' =>'after_value_string','type' => 'varchar'),
					  'before_value_text'=>array('name' =>'before_value_text','type' => 'text'),
					  'after_value_text'=>array('name' =>'after_value_text','type' => 'text'),
				)
		)
?>
