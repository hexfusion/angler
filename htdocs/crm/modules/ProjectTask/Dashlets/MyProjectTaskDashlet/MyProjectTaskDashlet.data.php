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

 // $Id: MyProjectTaskDashlet.data.php,v 1.7 2006/08/22 19:45:16 awu Exp $

global $current_user;

$dashletData['MyProjectTaskDashlet']['searchFields'] =  array(
                                                            'date_entered'     => array('default' => ''),                                    
                                                            'date_due'         => array('default' => ''),
                                                            'date_start'        => array('default' => ''),
                                                            'status'            => array('default' => array('Not Started', 'In Progress', 'Pending Input')),
                                                            'assigned_user_id' => array('type'    => 'assigned_user_name', 
                                                                                        'default' => $current_user->name),



                                                            );
$dashletData['MyProjectTaskDashlet']['columns'] = array('name' => array('width'   => '40', 
                                                                       'label'   => 'LBL_NAME',
                                                                       'link'    => true,
                                                                       'default' => true), 
                                                       'priority' => array('width'   => '29',
                                                                           'label'   => 'LBL_PRIORITY',
                                                                           'default' => true),
                                                       'status' => array('width' => '15',
                                                                         'label' => 'LBL_STATUS'),
                                                       'date_due' => array('width'   => '20',
                                                                           'label'   => 'LBL_DATE_DUE',
                                                                           'default' => true),
                                                       'time_due' => array('width' => '15',
                                                                           'label' => 'LBL_TIME_DUE'),
                                                       'date_start' => array('width' => '15',
                                                                             'label' => 'LBL_DATE_START'),
                                                       'time_start' => array('width' => '15',
                                                                             'label' => 'LBL_TIME_START'),
                                                       'task_number' => array('width' => '15',
                                                                              'label' => 'LBL_TASK_NUMBER'),
                                                       'parent_name' => array('width' => '15',
                                                                              'label' => 'LBL_PROJECT_NAME',
                                                                              'sortable' => false),
                                                       'utilization' => array('width' => '10',
                                                                              'label' => 'LBL_UTILIZATION'),
                                                       'milestone_flag' => array('width' => '10',
                                                                                 'label' => 'LBL_MILESTONE_FLAG'),
                                                       'estimated_effort' => array('width' => '10',
                                                                                   'label' => 'LBL_ESTIMATED_EFFORT'),
                                                       'actual_effort' => array('width' => '10',
                                                                                'label' => 'LBL_ACTUAL_EFFORT'),
                                                       'date_entered' => array('width' => '15', 
                                                                               'label' => 'LBL_DATE_ENTERED',
                                                                               'default' => true),
                                                       'date_modified' => array('width' => '15', 
                                                                                'label' => 'LBL_DATE_MODIFIED'),    
                                                       'created_by' => array('width' => '8', 
                                                                             'label' => 'LBL_CREATED'),
                                                       'assigned_user_name' => array('width'   => '8', 
                                                                                     'label'   => 'LBL_LIST_ASSIGNED_USER',
                                                                                     'default' => true),




                                                                           );


?>
