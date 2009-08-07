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

 // $Id: en_us.lang.php,v 1.10 2006/08/29 04:37:45 majed Exp $



$mod_strings = array (
'LBL_EDIT_LAYOUT'=>'Edit Layout',
'LBL_EDIT_ROWS'=>'Edit Rows',
'LBL_EDIT_COLUMNS'=>'Edit Columns',
'LBL_EDIT_LABELS'=>'Edit Labels',
'LBL_EDIT_FIELDS'=>'Edit Custom Fields',
'LBL_ADD_FIELDS'=>'Add Custom Fields',
'LBL_DISPLAY_HTML'=>'Display HTML Code',
'LBL_SELECT_FILE'=> 'Select File',
'LBL_SAVE_LAYOUT'=> 'Save Layout',
'LBL_SELECT_A_SUBPANEL' => 'Select a Subpanel',
'LBL_SELECT_SUBPANEL' => 'Select Subpanel',
'LBL_MODULE_TITLE' => 'Studio',
'LBL_TOOLBOX' => 'Toolbox',
'LBL_STAGING_AREA' => 'Staging Area (drag and drop items here)',
'LBL_SUGAR_FIELDS_STAGE' => 'Sugar Fields (click items to add to staging area)',
'LBL_SUGAR_BIN_STAGE' => 'Sugar Bin (click items to add to staging area)',
'LBL_VIEW_SUGAR_FIELDS' => 'View Sugar Fields',
'LBL_VIEW_SUGAR_BIN' => 'View Sugar Bin', 
'LBL_FAILED_TO_SAVE' => 'Failed To Save',
'LBL_PUBLISHING' => 'Publishing ...',
'LBL_PUBLISHED' => 'Published',
'LBL_FAILED_PUBLISHED' => 'Failed to Publish',

//CUSTOM FIELDS
'LBL_NAME'=>'Name',
'LBL_LABEL'=>'Label',
'LBL_MASS_UPDATE'=>'Mass Update',
'LBL_AUDITED'=>'Audit',
'LBL_CUSTOM_MODULE'=>'Module',
'LBL_DEFAULT_VALUE'=>'Default Value',
'LBL_REQUIRED'=>'Required',
'LBL_DATA_TYPE'=>'Type',


'LBL_HISTORY'=>'History',

//WIZARDS
//STUDIO WIZARD
'LBL_SW_WELCOME'=>'<h2>Welcome to Studio!</h2><br> What would you like to do today?<br><b> Please select from the options below.</b>',
'LBL_SW_EDIT_MODULE'=>'Edit a Module',
'LBL_SW_EDIT_DROPDOWNS'=>'Edit Drop Downs',
'LBL_SW_EDIT_TABS'=>'Configure Tabs',
'LBL_SW_RENAME_TABS'=>'Rename Tabs',
'LBL_SW_EDIT_GROUPTABS'=>'Configure Group Tabs',
'LBL_SW_EDIT_PORTAL'=>'Edit Portal',
'LBL_SW_EDIT_WORKFLOW'=>'Edit Workflow',
'LBL_SW_REPAIR_CUSTOMFIELDS'=>'Repair Custom Fields',

//SELECT MODULE WIZARD
'LBL_SMW_WELCOME'=>'<h2>Welcome to Studio!</h2><br><b>Please select a module from below.',

//SELECT MODULE ACTION
'LBL_SMA_WELCOME'=>'<h2>Edit a Module</h2>What do you want to do with that module?<br><b>Please select what action you would like to take.',
'LBL_SMA_EDIT_CUSTOMFIELDS'=>'Edit Custom Fields',
'LBL_SMA_EDIT_LAYOUT'=>'Edit Layout',

//Manager Backups History
'LBL_MB_PREVIEW'=>'Preview',
'LBL_MB_RESTORE'=>'Restore',
'LBL_MB_DELETE'=>'Delete',
'LBL_MB_COMPARE'=>'Compare',
'LBL_MB_WELCOME'=> '<h2>History</h2><br> History allows you to view previously published editions of the file you are currently working on. You can compare and restore previous versions. If you do restore a file it will become your working file. You must publish it before it is visible by everyone else.<br> What would you like to do today?<br><b> Please select from the options below.</b>',

//EDIT DROP DOWNS
'LBL_ED_CREATE_DROPDOWN'=> 'Create a Drop Down',
'LBL_ED_WELCOME'=>'<h2>Drop Down Editor</h2><br><b>You can either edit an exisiting drop down or create a new  drop down.',

//EDIT CUSTOM FIELDS
'LBL_EC_WELCOME'=>'<h2>Custom Field Editor</h2><br><b>You can either view and edit an exisiting custom field., create a new  custom field, or clean the custom field cache.',
'LBL_EC_VIEW_CUSTOMFIELDS'=> 'View Custom Fields',
'LBL_EC_CREATE_CUSTOMFIELD'=>'Create Custom Field',
'LBL_EC_CLEAR_CACHE'=>'Clear Cache',

//SELECT MODULE
'LBL_SM_WELCOME'=> '<h2>History</h2><br><b>Please select the file you would like to view.</b>',
//END WIZARDS

//DROP DOWN EDITOR
'LBL_DD_DISPALYVALUE'=>'Display Value',
'LBL_DD_DATABASEVALUE'=>'Database Value',
'LBL_DD_ALL'=>'All',

//BUTTONS
'LBL_BTN_SAVE'=>'Save',
'LBL_BTN_SAVEPUBLISH'=>'Save & Publish',
'LBL_BTN_HISTORY'=>'History',
'LBL_BTN_NEXT'=>'Next',
'LBL_BTN_BACK'=>'Back',
'LBL_BTN_ADDCOLS'=>'Add Columns',
'LBL_BTN_ADDROWS'=>'Add Rows',
'LBL_BTN_UNDO'=>'Undo',
'LBL_BTN_REDO'=>'Redo',
'LBL_BTN_ADDCUSTOMFIELD'=>'Add Custom Field',
'LBL_BTN_TABINDEX'=>'Edit Tabbing Order',

//TABS
'LBL_TAB_SUBTABS'=>'Sub Tabs',
'LBL_MODULES'=>'Modules',

//LIST VIEW EDITOR
'LBL_DEFAULT'=>'Default',
'LBL_ADDITIONAL'=>'Additional',
'LBL_AVAILABLE'=>'Available',
'LBL_LISTVIEW_DESCRIPTION'=>'There are three columns displayed below. The default column contains the fields that are displayed in a list view by default, the additional column contains fields that a user may choose to use for creating a custom view, and the available columns are columns availabe for you as an admin to either add to the default or additional columns for use by users but are currently not used.', 
'LBL_LISTVIEW_EDIT'=>'List View Editor',
);
?>
