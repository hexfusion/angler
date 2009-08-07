<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
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
 */

 // $Id: MyEmailsDashlet.data.php,v 1.4 2006/08/22 19:33:33 awu Exp $

global $current_user, $app_strings;

$dashletData['MyEmailsDashlet']['searchFields'] = array(         
                                                       'name'          => array('default' => ''),
                                                       'assigned_user_id'   => array('default' => ''),                                                       
                                                       'from_addr'          => array('default' => ''),
                                                       );
$dashletData['MyEmailsDashlet']['columns'] = array('name' => array('width'   => '40', 
                                                                   'label'   => 'LBL_SUBJECT',
                                                                   'link'    => true,
                                                                   'default' => true),
                                                   'from_addr' => array('width'   => '15',
                                                                       'label'   => 'LBL_FROM',
                                                                       'default' => true),                                                               
                                                   'to_addrs' => array('width'   => '15', 
                                                                         'label'   => 'LBL_TO_ADDRS',
                                                                         'default' => false),    
                                                   'assigned_user_name' => array('width'   => '15', 
                                                                         'label'   => 'LBL_LIST_ASSIGNED',
                                                                         'default' => false),    
                                                                                                                                                                            





                                                                        
                                                   'create_related' => array('width'   => '15', 
                                                                        'label'   => 'LBL_QUICK_CREATE', 
                                                                        'sortable' => false,
                                                                        'default' => true),
                                                   'quick_reply' => array('width'   => '15', 
                                                                        'label'   => 'LBL_REPLIED', 
                                                                        'sortable' => false,
                                                                        'default' => true),);

?>
