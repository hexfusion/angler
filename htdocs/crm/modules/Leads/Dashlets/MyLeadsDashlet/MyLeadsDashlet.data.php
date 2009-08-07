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

 // $Id: MyLeadsDashlet.data.php,v 1.10 2006/08/22 19:38:27 awu Exp $

global $current_user;
$dashletData['MyLeadsDashlet']['searchFields'] = array('date_entered'     => array('default' => ''), 



                                                       'status'           => array('default' => array('New', 'Assigned', 'In Process')),
                                                       'assigned_user_id' => array('type'    => 'assigned_user_name', 
                                                                                   'default' => $current_user->name));
 
$dashletData['MyLeadsDashlet']['columns'] = array('name' => array('width'          => '40', 
                                                                  'label'          => 'LBL_NAME',
                                                                  'link'           => true,
                                                                  'default'        => true,
                                                                  'related_fields' => array('first_name', 'last_name')), 
                                                  'title' => array('width' => '10',
                                                                   'label' => 'LBL_TITLE'),
                                                  'lead_source' => array('width' => '10',
                                                                         'label' => 'LBL_LEAD_SOURCE'),
                                                  'status' => array('width' => '10',
                                                                    'label' => 'LBL_STATUS'),
                                                  'phone_work' => array('width'   => '10',
                                                                        'label'   => 'LBL_OFFICE_PHONE',
                                                                        'default' => true),
                                                  'phone_home' => array('width' => '10',
                                                                        'label' => 'LBL_HOME_PHONE'),
                                                  'phone_mobile' => array('width' => '10',
                                                                          'label' => 'LBL_MOBILE_PHONE'),
                                                  'phone_other' => array('width' => '10',
                                                                         'label' => 'LBL_OTHER_PHONE'),
                                                  'date_entered' => array('width'   => '15', 
                                                                          'label'   => 'LBL_DATE_ENTERED',
                                                                          'default' => true),
                                                  'date_modified' => array('width'   => '15', 
                                                                           'label'   => 'LBL_DATE_MODIFIED'),    
                                                  'created_by' => array('width'   => '8', 
                                                                        'label'   => 'LBL_CREATED'),
                                                  'assigned_user_name' => array('width'   => '8', 
                                                                                'label'   => 'LBL_LIST_ASSIGNED_USER'),




                                                 );
?>
