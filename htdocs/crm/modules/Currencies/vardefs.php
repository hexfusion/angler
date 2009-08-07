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
$dictionary['Currency'] = array('table' => 'currencies',
	'comment' => 'Currencies allow Sugar to store and display monetary values in various denominations'
                               ,'fields' => array (
  'id' => 
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required' => true,
    'reportable'=>false,
    'comment' => 'Unique identifer'
    ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_NAME',
    'type' => 'varchar',
    'len' => '36',
    'required' => true,
    'comment' => 'Name of the currency'
  ),
  'symbol' => 
  array (
    'name' => 'symbol',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '36',
     'required' => true,
     'comment' => 'Symbol representing the currency'
  ),
  'iso4217' => 
  array (
    'name' => 'iso4217',
    'vname' => 'LBL_LIST_ISO4217',
    'type' => 'varchar',
    'len' => '3',
     'required' => true,
     'comment' => '3-letter identifier specified by ISO 4217 (ex: USD)'
  ),
  'conversion_rate' => 
  array (
    'name' => 'conversion_rate',
    'vname' => 'LBL_LIST_RATE',
    'type' => 'float',
    'dbType' => 'double',
    'default' => '0',
     'required' => true,
	 'comment' => 'Conversion rate factor (relative to stored value)'
  ),
  'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'dbType'=>'varchar',
    'options' => 'currency_status_dom',
    'len' => '25',
    'comment' => 'Currency status'
  ),
  'deleted' => 
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'reportable'=>false,
    'comment' => 'Record deletion indicator'
  ),
  'date_entered' => 
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
     'required' => true,
    'comment' => 'Date record created'

  ),
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
     'required' => true,
    'comment' => 'Date record last modified'
  ),
  'created_by' => 
  array (
    'name' => 'created_by',
    'vname' => 'LBL_CREATED_BY',
    'type' => 'id',
    'len'  => '36',
    'required' => true,
  	'comment' => 'User ID who created record'
  ),
)
                                                      , 'indices' => array (
   array('name' =>'currenciespk', 'type' =>'primary', 'fields'=>array('id')),
   array('name' =>'idx_currency_name', 'type' =>'index', 'fields'=>array('name','deleted'))
                                                      )

                            );
?>
