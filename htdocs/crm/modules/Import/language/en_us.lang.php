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
/*********************************************************************************
 * $Id: en_us.lang.php,v 1.41 2006/07/26 22:40:07 jenny Exp $
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
  'LBL_IMPORT_MODULE_NO_DIRECTORY' => 'The directory ',
  'LBL_IMPORT_MODULE_NO_DIRECTORY_END' => ' does not exist or is not writable',
  'LBL_IMPORT_MODULE_ERROR_NO_UPLOAD' => 'File was not uploaded successfully. It may be that the \'upload_max_filesize\' setting in your php.ini file is set to a small number',
  'LBL_IMPORT_MODULE_ERROR_LARGE_FILE' => 'File is too large. Max:',
  'LBL_IMPORT_MODULE_ERROR_LARGE_FILE_END' => 'Bytes. Change $sugar_config[\'upload_maxsize\'] in config.php',
  'LBL_MODULE_NAME' => 'Import',
  'LBL_TRY_AGAIN' => 'Try Again',
  'LBL_ERROR' => 'Error:',
  'ERR_MULTIPLE' => 'Multiple columns have been defined with the same field name.',
  'ERR_MISSING_REQUIRED_FIELDS' => 'Missing required fields:',
  'ERR_SELECT_FULL_NAME' => 'You cannot select Full Name when First Name and Last Name are selected.',
  'ERR_SELECT_FILE' => 'Select a file to upload.',
  'LBL_SELECT_FILE' => 'Select file:',
  'LBL_CUSTOM' => 'Custom',
  'LBL_CUSTOM_CSV' => 'Custom Comma Delimited File',
  'LBL_CSV' => 'Comma Delimited File',
  'LBL_TAB' => 'Tab Delimited File',
  'LBL_CUSTOM_DELIMETED' => 'Custom Delimited File',
  'LBL_CUSTOM_DELIMETER' => 'Custom Delimter:',
  'LBL_CUSTOM_TAB' => 'Custom Tab Delimited File',
  'LBL_DONT_MAP' => '-- Do not map this field --',
  'LBL_STEP_1_TITLE' => 'Step 1: Select the Source',
  'LBL_WHAT_IS' => 'What is the data source?',
  'LBL_MICROSOFT_OUTLOOK' => 'Microsoft Outlook',
  'LBL_ACT' => 'Act!',
  'LBL_ACT_2005' => 'Act! 2005',
  'LBL_SALESFORCE' => 'Salesforce.com',
  'LBL_MY_SAVED' => 'My Saved Sources:',
  'LBL_PUBLISH' => 'publish',
  'LBL_DELETE' => 'delete',
  'LBL_PUBLISHED_SOURCES' => 'Published Sources:',
  'LBL_UNPUBLISH' => 'un-publish',
  'LBL_NEXT' => 'Next >',
  'LBL_BACK' => '< Back',
  'LBL_STEP_2_TITLE' => 'Step 2: Upload Export File',
  'LBL_HAS_HEADER' => 'Has Header:',
  'LBL_NUM_1' => '1.',
  'LBL_NUM_2' => '2.',
  'LBL_NUM_3' => '3.',
  'LBL_NUM_4' => '4.',
  'LBL_NUM_5' => '5.',
  'LBL_NUM_6' => '6.',
  'LBL_NUM_7' => '7.',
  'LBL_NUM_8' => '8.',
  'LBL_NUM_9' => '9.',
  'LBL_NUM_10' => '10.',
  'LBL_NUM_11' => '11.',
  'LBL_NUM_12' => '12.',
  'LBL_NOTES' => 'Notes:',
  'LBL_NOW_CHOOSE' => 'Now choose that file to import:',
  'LBL_IMPORT_OUTLOOK_TITLE' => 'Microsoft Outlook 98 and 2000 can export data in the <b>Comma Separated Values</b> format which can be used to import data into the system. To export your data from Outlook, follow the steps below:',
  'LBL_OUTLOOK_NUM_1' => 'Start <b>Outlook</b>',
  'LBL_OUTLOOK_NUM_2' => 'Select the <b>File</b> menu, then the <b>Import and Export ...</b> menu option',
  'LBL_OUTLOOK_NUM_3' => 'Choose <b>Export to a file</b> and click Next',
  'LBL_OUTLOOK_NUM_4' => 'Choose <b>Comma Separated Values (Windows)</b> and click <b>Next</b>.<br>  Note: You may be prompted to install the export component',
  'LBL_OUTLOOK_NUM_5' => 'Select the <b>Contacts</b> folder and click <b>Next</b>. You can select different contacts folders if your contacts are stored in multiple folders',
  'LBL_OUTLOOK_NUM_6' => 'Choose a filename and click <b>Next</b>',
  'LBL_OUTLOOK_NUM_7' => 'Click <b>Finish</b>',
  'LBL_IMPORT_ACT_TITLE' => 'Act! can export data in the <b>Comma Separated Values</b> format which can be used to import data into the system. To export your data from Act!, follow the steps below:',
  'LBL_ACT_NUM_1' => 'Launch <b>ACT!</b>',
  'LBL_ACT_NUM_2' => 'Select the <b>File</b> menu, the <b>Data Exchange</b> menu option, then the <b>Export...</b> menu option',
  'LBL_ACT_NUM_3' => 'Select the file type <b>Text-Delimited</b>',
  'LBL_ACT_NUM_4' => 'Choose a filename and location for the exported data and click <b>Next</b>',
  'LBL_ACT_NUM_5' => 'Select <b>Contacts records only</b>',
  'LBL_ACT_NUM_6' => 'Click the <b>Options...</b> button',
  'LBL_ACT_NUM_7' => 'Select <b>Comma</b> as the field separator character',
  'LBL_ACT_NUM_8' => 'Check the <b>Yes, export field names</b> checkbox and click <b>OK</b>',
  'LBL_ACT_NUM_9' => 'Click <b>Next</b>',
  'LBL_ACT_NUM_10' => 'Select <b>All Records</b> and then Click <b>Finish</b>',
  'LBL_IMPORT_SF_TITLE' => 'Salesforce.com can export data in the <b>Comma Separated Values</b> format which can be used to import data into the system. To export your data from Salesforce.com, follow the steps below:',
  'LBL_SF_NUM_1' => 'Open your browser, go to http://www.salesforce.com, and login with your email address and password',
  'LBL_SF_NUM_2' => 'Click on the <b>Reports</b> tab on the top menu',
  'LBL_SF_NUM_3' => '<b>To export Accounts:</b> Click on the <b>Active Accounts</b> link<br><b>To export Contacts:</b> Click on the <b>Mailing List</b> link',
  'LBL_SF_NUM_4' => 'On <b>Step 1: Select your report type</b>, select <b>Tabular Report</b>click <b>Next</b>',
  'LBL_SF_NUM_5' => 'On <b>Step 2: Select the report columns</b>, choose the columns you want to export and click <b>Next</b>',
  'LBL_SF_NUM_6' => 'On <b>Step 3: Select the information to summarize</b>, just click <b>Next</b>',
  'LBL_SF_NUM_7' => 'On <b>Step 4: Order the report columns</b>, just click <b>Next</b>',
  'LBL_SF_NUM_8' => 'On <b>Step 5: Select your report criteria</b>, under <b>Start Date</b>, choose a date far enough in the past to include all your Accounts. You can also export a subset of Accounts using more advanced criteria. When you are done, click <b>Run Report</b>',
  'LBL_SF_NUM_9' => 'A report will be generated, and the page should display <b>Report Generation Status: Complete.</b> Now click <b>Export to Excel</b>',
  'LBL_SF_NUM_10' => 'On <b>Export Report:</b>, for <b>Export File Format:</b>, choose <b>Comma Delimited .csv</b>. Click <b>Export</b>.',
  'LBL_SF_NUM_11' => 'A dialog will pop up for you to save the export file to your computer.',
  'LBL_IMPORT_CUSTOM_TITLE' => 'Many applications will allow you to export data into a <b>Comma Delimited text file (.csv)</b>. Generally most applications follow these general steps:',
  'LBL_CUSTOM_NUM_1' => 'Launch the application and Open the data file',
  'LBL_CUSTOM_NUM_2' => 'Select the <b>Save As...</b> or <b>Export...</b> menu option',
  'LBL_CUSTOM_NUM_3' => 'Save the file in a <b>CSV</b> or <b>Comma Separated Values</b> format',
  'LBL_IMPORT_TAB_TITLE' => 'Many applications will allow you to export data into a <b>Tab Delimited text file (.tsv or .tab)</b>. Generally most applications follow these general steps:',
  'LBL_TAB_NUM_1' => 'Launch the application and Open the data file',
  'LBL_TAB_NUM_2' => 'Select the <b>Save As...</b> or <b>Export...</b> menu option',
  'LBL_TAB_NUM_3' => 'Save the file in a <b>TSV</b> or <b>Tab Separated Values</b> format',
  'LBL_STEP_3_TITLE' => 'Step 3: Confirm Fields and Import',
  'LBL_SELECT_FIELDS_TO_MAP' => 'In the list below, select the fields in your import file that should be imported into each field in the system. When you are finished, click <b>Import Now</b>:',
  'LBL_DATABASE_FIELD' => 'Database Field',
  'LBL_HEADER_ROW' => 'Header Row',
  'LBL_ROW' => 'Row',
  'LBL_SAVE_AS_CUSTOM' => 'Save as Custom Mapping:',
  'LBL_CONTACTS_NOTE_1' => 'Either Last Name or Full Name must be mapped.',
  'LBL_CONTACTS_NOTE_2' => 'If Full Name is mapped, then First Name and Last Name are ignored.',
  'LBL_CONTACTS_NOTE_3' => 'If Full Name is mapped, then the data in Full Name will be split into First Name and Last Name when inserted into the database.',
  'LBL_CONTACTS_NOTE_4' => 'Fields ending in Address Street 2 and Address Street 3 are concatenated together with the main Address Street Field when inserted into the database.',
  'LBL_ACCOUNTS_NOTE_1' => 'Account Name must be mapped.',
  'LBL_ACCOUNTS_NOTE_2' => 'Fields ending in Address Street 2 and Address Street 3 are concatenated together with the main Address Street Field when inserted into the database.',
  'LBL_OPPORTUNITIES_NOTE_1' => 'Opportunity Name, Account Name, Date Closed, and Sales Stage are required fields.',
  'LBL_IMPORT_NOW' => 'Import Now',
  'LBL_' => '',
  'LBL_CANNOT_OPEN' => 'Cannot open the imported file for reading',
  'LBL_NOT_SAME_NUMBER' => 'There were not the same number of fields per line in your file',
  'LBL_NO_LINES' => 'There were no lines in your import file',
  'LBL_FILE_ALREADY_BEEN_OR' => 'The import file has already been processed or does not exist',
  'LBL_SUCCESS' => 'Success:',
  'LBL_SUCCESSFULLY' => 'Succesfully Imported',
  'LBL_LAST_IMPORT_UNDONE' => 'Your last import was undone',
  'LBL_NO_IMPORT_TO_UNDO' => 'There was no import to undo.',
  'LBL_FAIL' => 'Fail:',
  'LBL_RECORDS_SKIPPED' => 'records skipped because they were missing one or more required fields',
  'LBL_IDS_EXISTED_OR_LONGER' => 'records skipped because the id\'s either existed or where longer than 36 characters',
  'LBL_RESULTS' => 'Results',
  'LBL_IMPORT_MORE' => 'Import More',
  'LBL_FINISHED' => 'Finished',
  'LBL_UNDO_LAST_IMPORT' => 'Undo Last Import',
  'LBL_LAST_IMPORTED'=>'Last Imported',
  'ERR_MULTIPLE_PARENTS' => 'You can only have one Parent ID defined',
  'LBL_DUPLICATES' => 'Duplicates Found',
  'LBL_DUPLICATE_LIST' => 'Download List of Duplicates',
  'LBL_UNIQUE_INDEX' => 'Choose Index for duplicate comparison',
);
?>
