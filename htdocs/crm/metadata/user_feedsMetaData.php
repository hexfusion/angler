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
$dictionary['users_feeds'] = array ( 'table' => 'users_feeds'
                                  , 'fields' => array (
    
       array('name' =>'user_id', 'type' =>'varchar', 'len'=>'36', )
      , array('name' =>'feed_id', 'type' =>'varchar', 'len'=>'36', )
      , array('name' =>'rank', 'type' =>'int', 'required' => false)
      , array ('name' => 'date_modified','type' => 'datetime')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'', 'default'=>'0', 'required' => true)
                                                      ) 
                                 , 'indices' => array (
  
       array('name' =>'idx_ud_user_id', 'type' =>'index', 'fields'=>array('user_id', 'feed_id'))                                  
                                                      )
                                  )
?>
