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
$dictionary['Administration'] = array('table' => 'config', 'comment' => 'System table containing system-wide definitions'
                               ,'fields' => array (
  'category' => 
  array (
    'name' => 'category',
    'vname' => 'LBL_LIST_SYMBOL',
    'type' => 'varchar',
    'len' => '32',
    'comment' => 'Settings are grouped under this category; arbitraily defined based on requirements'
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_NAME',
    'type' => 'varchar',
    'len' => '32',
    'comment' => 'The name given to the setting'
  ),
  'value' => 
  array (
    'name' => 'value',
    'vname' => 'LBL_LIST_RATE',
    'type' => 'text',
    'comment' => 'The value given to the setting'
  ),
  
), 'indices'=>array( array('name'=>'idx_config_cat', 'type'=>'index',  'fields'=>array('category')),)
                            );

$dictionary['UpgradeHistory'] = array(
    'table'  => 'upgrade_history', 'comment' => 'Tracks Sugar Suite upgrades made over time; used by Upgrade Wizard and Module Loader',
    'fields' => array (
        'id' => array (
                'name'       => 'id',
                'type'       => 'id',
                'required'   => true,
                'reportable' => false,
    		    'comment' => 'Unique identifier'
        ),
        'filename' => array (
                'name' => 'filename',
                'type' => 'varchar',
                'len' => '255',
    		    'comment' => 'Cached filename containing the upgrade scripts and content'
        ),
        'md5sum' => array (
                'name' => 'md5sum',
                'type' => 'varchar',
                'len' => '32',
    		    'comment' => 'The MD5 checksum of the upgrade file'
        ),
        'type' => array (
                'name' => 'type',
                'type' => 'varchar',
                'len' => '30',
    		    'comment' => 'The upgrade type (module, patch, theme, etc)'
        ),
        'status' => array (
                'name' => 'status',
                'type' => 'varchar',
                'len' => '50',
    		    'comment' => 'The status of the upgrade (ex:  "installed")',
        ),
        'version' => array (
                'name' => 'version',
                'type' => 'varchar',
                'len' => '10',
    		    'comment' => 'Version as contained in manifest file'
        ),
        'date_entered' => array (
                'name' => 'date_entered',
                'type' => 'datetime',
                'required'=>true,
    		    'comment' => 'Date of upgrade or module load'
        ),
    ),
    
    'indices' => array(
        array('name'=>'upgrade_history_pk',     'type'=>'primary', 'fields'=>array('id')),
        array('name'=>'upgrade_history_md5_uk', 'type'=>'unique',  'fields'=>array('md5sum')),
       
    ),
);



















































































































































?>
