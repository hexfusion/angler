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

// $Id: MyAccountsDashlet.data.php,v 1.5 2006/08/22 19:09:18 awu Exp $

global $current_user;

$dashletData['MyAccountsDashlet']['searchFields'] = array('date_entered'     => array('default' => ''),
                                                          'date_modified'    => array('default' => ''),



                                                          'assigned_user_id' => array('type'    => 'assigned_user_name', 
                                                                                      'default' => $current_user->name));
$dashletData['MyAccountsDashlet']['columns'] =  array('name' => array('width'   => '40', 
                                                                      'label'   => 'LBL_LIST_ACCOUNT_NAME',
                                                                      'link'    => true,
                                                                      'default' => true), 
                                                      'phone_office' => array('width'   => '15', 
                                                                              'label'   => 'LBL_LIST_PHONE',
                                                                              'default' => true),
                                                      'phone_fax' => array('width' => '8',
                                                                          'label' => 'LBL_PHONE_FAX'),
                                                      'phone_alternate' => array('width' => '8',
                                                                                 'label' => 'LBL_PHONE_ALT'),
                                                      'billing_address_city' => array('width' => '8',
                                                                                      'label' => 'LBL_BILLING_ADDRESS_CITY'),
                                                      'billing_address_street' => array('width' => '8',
                                                                                        'label' => 'LBL_BILLING_ADDRESS_STREET'),
                                                      'billing_address_state' => array('width' => '8',
                                                                                       'label' => 'LBL_BILLING_ADDRESS_STATE'),
                                                      'billing_address_postalcode' => array('width' => '8',
                                                                                            'label' => 'LBL_BILLING_ADDRESS_POSTALCODE'),
                                                      'billing_address_country' => array('width' => '8',
                                                                                         'label' => 'LBL_BILLING_ADDRESS_COUNTRY'),
                                                      'shipping_address_city' => array('width' => '8',
                                                                                       'label' => 'LBL_SHIPPING_ADDRESS_CITY'),
                                                      'shipping_address_street' => array('width' => '8',
                                                                                        'label' => 'LBL_SHIPPING_ADDRESS_STREET'),
                                                      'shipping_address_state' => array('width' => '8',
                                                                                        'label' => 'LBL_SHIPPING_ADDRESS_STATE'),
                                                      'shipping_address_postalcode' => array('width' => '8',
                                                                                             'label' => 'LBL_SHIPPING_ADDRESS_POSTALCODE'),
                                                      'shipping_address_country' => array('width' => '8',
                                                                                          'label' => 'LBL_SHIPPING_ADDRESS_COUNTRY'),
                                                      'email1' => array('width' => '8',
                                                                        'label' => 'LBL_EMAIL'),
                                                      'website' => array('width' => '8',
                                                                         'label' => 'LBL_WEBSITE'),
                                                      'account_name' => array('width'    => '15',
                                                                              'label'    => 'LBL_MEMBER_OF',
                                                                              'sortable' => false),
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
