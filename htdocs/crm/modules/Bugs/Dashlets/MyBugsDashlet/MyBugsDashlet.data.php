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

// $Id: MyBugsDashlet.data.php,v 1.12 2006/08/28 17:51:38 wayne Exp $

global $current_user;

$dashletData['MyBugsDashlet']['searchFields'] = array('priority'              => array('default' => ''),
                                                      'status'                => array('default' => array('Assigned', 'New', 'Pending')),
                                                      'found_in_release'      => array('default' => ''),
                                                      'fixed_in_release' => array('default' => ''),
                                                      'type'                  => array('default' => ''),
                                                      ''                      => array('default' => ''),
                                                      'date_entered'          => array('default' => ''),
                                                      'date_modified'         => array('default' => ''),



                                                      'assigned_user_id'      => array('type'    => 'assigned_user_name', 
                                                                                     'default' => $current_user->name));
$dashletData['MyBugsDashlet']['columns'] = array('bug_number' => array('width'   => '5', 
                                                                       'label'   => 'LBL_NUMBER',
                                                                       'default' => true),
                                                 'name' => array('width'   => '40', 
                                                                 'label'   => 'LBL_LIST_SUBJECT',
                                                                 'link'    => true,
                                                                 'default' => true), 
                                                 'priority' => array('width'  => '10', 
                                                                     'label'   => 'LBL_PRIORITY',
                                                                     'default' => true),
                                                 'status' => array('width'   => '10', 
                                                                   'label'   => 'LBL_STATUS',
                                                                   'default' => true), 
                                                 'resolution' => array('width'   => '15', 
                                                                       'label'   => 'LBL_RESOLUTION'),
                                                 'release_name' => array('width'   => '15', 
                                                                         'label'   => 'LBL_FOUND_IN_RELEASE',
                                                                         'related_fields' => array('found_in_release')),
                                                 'type' => array('width'   => '15', 
                                                                 'label'   => 'LBL_TYPE'),                                                  
                                                 'fixed_in_release_name' => array('width'   => '15', 
                                                                                  'label'   => 'LBL_FIXED_IN_RELEASE'),
                                                 'source' => array('width'   => '15', 
                                                                   'label'   => 'LBL_SOURCE'),
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
