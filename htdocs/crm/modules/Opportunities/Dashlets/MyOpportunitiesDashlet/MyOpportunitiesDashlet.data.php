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

 // $Id: MyOpportunitiesDashlet.data.php,v 1.12 2006/08/23 00:07:19 wayne Exp $

global $current_user;

$dashletData['MyOpportunitiesDashlet']['searchFields'] = array('date_entered'     => array('default' => ''),
                                                               'date_modified'    => array('default' => ''),
                                                               'opportunity_type' => array('default' => ''),



                                                               'sales_stage'      => array('default' => 
                                                                    array('Prospecting', 'Qualification', 'Needs Analysis', 'Value Proposition', 'Id. Decision Makers', 'Perception Analysis', 'Proposal/Price Quote', 'Negotiation/Review')),
                                                               'assigned_user_id' => array('type'    => 'assigned_user_name', 
                                                                                           'default' => $current_user->name));
                                                                                           
$dashletData['MyOpportunitiesDashlet']['columns'] = array('name' => array('width'   => '40', 
                                                                          'label'   => 'LBL_OPPORTUNITY_NAME',
                                                                          'link'    => true,
                                                                          'default' => true 
                                                                          ), 
                                                          'account_name' => array('width'  => '29', 
                                                                                  'label'   => 'LBL_ACCOUNT_NAME',
                                                                                  'default' => false,
                                                                                  'link' => true,
                                                                                  'id' => 'account_id',
                                                                                  'ACLTag' => 'ACCOUNT'),
                                                          'amount' => array('width'   => '15', 
                                                                            'label'   => 'LBL_AMOUNT',
                                                                            'default' => true,
                                                                            'related_fields' => array('amount_usdollar')),
                                                          'date_closed' => array('width'   => '15', 
                                                                                 'label'   => 'LBL_DATE_CLOSED',
                                                                                 'default' => true),  
                                                          'opportunity_type' => array('width'   => '15', 
                                                                                      'label'   => 'LBL_TYPE'),
                                                          'lead_source' => array('width'   => '15', 
                                                                                 'label'   => 'LBL_LEAD_SOURCE'),
                                                          'sales_stage' => array('width'   => '15', 
                                                                                 'label'   => 'LBL_SALES_STAGE'),
                                                          'probability' => array('width'   => '15', 
                                                                                  'label'   => 'LBL_PROBABILITY'),
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
