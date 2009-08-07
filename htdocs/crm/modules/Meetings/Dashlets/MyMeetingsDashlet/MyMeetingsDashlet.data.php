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

 // $Id: MyMeetingsDashlet.data.php,v 1.15 2006/08/22 19:40:01 awu Exp $

global $current_user;

$dashletData['MyMeetingsDashlet']['searchFields'] = array('name'             => array('default' => ''),
                                                          'date_start'       => array('default' => ''),
                                                          'status'           => array('default' => array('Planned')),



                                                          'assigned_user_id' => array('type'    => 'assigned_user_name', 
                                                                                      'default' => $current_user->name));
$dashletData['MyMeetingsDashlet']['columns'] = array('set_complete' => array('width'    => '1', 
                                                                             'label'    => 'LBL_LIST_CLOSE',
                                                                             'default'  => true,
                                                                             'sortable' => false,
                                                                             'related_fields' => array('status')),
                                                   'name' => array('width'   => '40', 
                                                                   'label'   => 'LBL_SUBJECT',
                                                                   'link'    => true,
                                                                   'default' => true),
                                                   'parent_name' => array('width' => '29', 
                                                                          'label' => 'LBL_LIST_RELATED_TO',
                                                                          'sortable' => false,
                                                                          'link' => true,
                                                                          'id' => 'parent_id',
                                                                          'ACLTag' => 'PARENT',
                                                                          'related_fields' => array('parent_id', 'parent_type')),
                                                   'duration' => array('width'    => '15', 
                                                                       'label'    => 'LBL_DURATION',
                                                                       'default'  => true,
                                                                       'sortable' => false,
                                                                       'related_fields' => array('duration_hours', 'duration_minutes')),
                                                   'date_start' => array('width'   => '15', 
                                                                         'label'   => 'LBL_DATE',
                                                                         'default' => true),                               
                                                   'time_start' => array('width'   => '15', 
                                                                         'label'   => 'LBL_TIME',
                                                                         'default' => true),
                                                   'status' => array('width'   => '8', 
                                                                     'label'   => 'LBL_STATUS'),
                                                   'date_entered' => array('width'   => '15', 
                                                                           'label'   => 'LBL_DATE_ENTERED'),
                                                   'date_modified' => array('width'   => '15', 
                                                                           'label'   => 'LBL_DATE_MODIFIED'),    
                                                   'created_by' => array('width'   => '8', 
                                                                         'label'   => 'LBL_CREATED'),
                                                   'assigned_user_name' => array('width'   => '8', 
                                                                                 'label'   => 'LBL_LIST_ASSIGNED_USER'),




                                                                         );


?>
