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
 * $Id: en_us.lang.php,v 1.54 2006/08/25 21:24:41 eddy Exp $
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
  'LBL_MODULE_NAME' => 'Accounts',
  'LBL_MODULE_TITLE' => 'Accounts: Home',
  'LBL_HOMEPAGE_TITLE' => 'My Accounts',
  'LBL_SEARCH_FORM_TITLE' => 'Account Search',
  'LBL_LIST_FORM_TITLE' => 'Account List',
  'LBL_VIEW_FORM_TITLE' => 'Account View',
  'LBL_NEW_FORM_TITLE' => 'New Account',
  'LBL_MEMBER_ORG_FORM_TITLE' => 'Member Organizations',
  'LBL_BUG_FORM_TITLE' => 'Accounts',
  'LBL_LIST_ACCOUNT_NAME' => 'Account Name',
  'LBL_LIST_CITY' => 'City',
  'LBL_LIST_WEBSITE' => 'Website',
  'LBL_LIST_STATE' => 'State',
  'LBL_LIST_PHONE' => 'Phone',
  'LBL_LIST_EMAIL_ADDRESS' => 'Email Address',
  'LBL_LIST_CONTACT_NAME' => 'Contact Name',
  'LBL_BILLING_ADDRESS_STREET_2' =>'Billing Address Street 2',
  'LBL_BILLING_ADDRESS_STREET_3' =>'Billing Address Street 3',
  'LBL_BILLING_ADDRESS_STREET_4' =>'Billing Address Street 4',
  'LBL_SHIPPING_ADDRESS_STREET_2' => 'Shipping Address Street 2',
  'LBL_SHIPPING_ADDRESS_STREET_3' => 'Shipping Address Street 3',
  'LBL_SHIPPING_ADDRESS_STREET_4' => 'Shipping Address Street 4',
  'LBL_PARENT_ACCOUNT_ID' => 'Parent Account ID',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_name' => 'LBL_LIST_ACCOUNT_NAME',
  'db_website' => 'LBL_LIST_WEBSITE',
  'db_billing_address_city' => 'LBL_LIST_CITY',
//END DON'T CONVERT
  'LBL_ACCOUNT_INFORMATION' => 'Account Information',
  'LBL_ACCOUNT' => 'Account:',
  'LBL_ACCOUNT_NAME' => 'Account Name:',
  'LBL_PHONE' => 'Phone:',
  'LBL_PHONE_ALT' => 'Alternate Phone:',
  'LBL_WEBSITE' => 'Website:',
  'LBL_FAX' => 'Fax:',
  'LBL_TICKER_SYMBOL' => 'Ticker Symbol:',
  'LBL_OTHER_PHONE' => 'Other Phone:',
  'LBL_ANY_PHONE' => 'Any Phone:',
  'LBL_MEMBER_OF' => 'Member of:',
  'LBL_PHONE_OFFICE' => 'Phone Office:',
  'LBL_PHONE_FAX' => 'Phone Fax:',
  'LBL_EMAIL' => 'Email:',
  'LBL_EMPLOYEES' => 'Employees:',
  'LBL_OTHER_EMAIL_ADDRESS' => 'Other Email:',
  'LBL_ANY_EMAIL' => 'Any Email:',
  'LBL_OWNERSHIP' => 'Ownership:',
  'LBL_RATING' => 'Rating:',
  'LBL_INDUSTRY' => 'Industry:',
  'LBL_SIC_CODE' => 'SIC Code:',
  'LBL_TYPE' => 'Type:',
  'LBL_ANNUAL_REVENUE' => 'Annual Revenue:',
  'LBL_ADDRESS_INFORMATION' => 'Address Information',
  'LBL_BILLING_ADDRESS' => 'Billing Address:',
  'LBL_BILLING_ADDRESS_STREET' => 'Billing Address Street:',
  'LBL_BILLING_ADDRESS_CITY' => 'Billing Address City:',
  'LBL_BILLING_ADDRESS_STATE' => 'Billing Address State:',
  'LBL_BILLING_ADDRESS_POSTALCODE' => 'Billing Address Postal Code:',
  'LBL_BILLING_ADDRESS_COUNTRY' => 'Billing Address Country:',
  'LBL_SHIPPING_ADDRESS_STREET' => 'Shipping Address Street:',
  'LBL_SHIPPING_ADDRESS_CITY' => 'Shipping Address City:',
  'LBL_SHIPPING_ADDRESS_STATE' => 'Shipping Address State:',
  'LBL_SHIPPING_ADDRESS_POSTALCODE' => 'Shipping Address Postal Code:',
  'LBL_SHIPPING_ADDRESS_COUNTRY' => 'Shipping Address Country:',
  'LBL_SHIPPING_ADDRESS' => 'Shipping Address:',
  'LBL_DATE_MODIFIED' => 'Date Modified:',
  'LBL_DATE_ENTERED' => 'Date Entered:',
  'LBL_ANY_ADDRESS' => 'Any Address:',
  'LBL_CITY' => 'City:',
  'LBL_STATE' => 'State:',
  'LBL_POSTAL_CODE' => 'Postal Code:',
  'LBL_COUNTRY' => 'Country:',
  'LBL_PUSH_CONTACTS_BUTTON_TITLE' => 'Copy...',
  'LBL_PUSH_CONTACTS_BUTTON_LABEL' => 'Copy to Contacts',
  'LBL_DESCRIPTION_INFORMATION' => 'Description Information',
  'LBL_DESCRIPTION' => 'Description:',
  'NTC_COPY_BILLING_ADDRESS' => 'Copy billing address to shipping address',
  'NTC_COPY_SHIPPING_ADDRESS' => 'Copy shipping address to billing address',
  'NTC_REMOVE_MEMBER_ORG_CONFIRMATION' => 'Are you sure you want to remove this record as a member organization?',
  'NTC_REMOVE_ACCOUNT_CONFIRMATION' => 'Are you sure you want to remove this record?',
  'LBL_DUPLICATE' => 'Possible Duplicate Account',
  'MSG_SHOW_DUPLICATES' => 'Creating this account may potentialy create a duplicate account. You may either click on Create Account to continue creating this new account with the previously entered data or you may click Cancel.',
  'MSG_DUPLICATE' => 'Creating this account may potentialy create a duplicate account. You may either select an account from the list below or you may click on Create Account to continue creating a new account with the previously entered data.',
  'LNK_NEW_ACCOUNT' => 'Create Account',



  'LNK_ACCOUNT_LIST' => 'Accounts',
  'LBL_INVITEE' => 'Contacts',
  'ERR_DELETE_RECORD' => 'A record number must be specified to delete the account.',
  'NTC_DELETE_CONFIRMATION' => 'Are you sure you want to delete this record?',
  'LBL_SAVE_ACCOUNT' => 'Save Account',
  'LBL_BUG_FORM_TITLE' => 'Accounts',
    'ACCOUNT_REMOVE_PROJECT_CONFIRM' => 'Are you sure you want to remove this account from this project?',
    'LBL_USERS_ASSIGNED_LINK'=>'Assigned Users',
    'LBL_USERS_MODIFIED_LINK'=>'Modified Users',
    'LBL_USERS_CREATED_LINK'=>'Created By Users',
    'LBL_TEAMS_LINK'=>'Teams',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'Accounts',
    'LBL_PRODUCTS_TITLE'=>'Products',
    'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'Activities',
    'LBL_HISTORY_SUBPANEL_TITLE'=>'History',
    'LBL_MEMBER_ORG_SUBPANEL_TITLE'=>'Member Organizations',
    'LBL_NAME'=>'Name:',
    
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'Contacts',
    'LBL_OPPORTUNITIES_SUBPANEL_TITLE' => 'Opportunities',
    'LBL_LEADS_SUBPANEL_TITLE' => 'Leads',
    'LBL_CASES_SUBPANEL_TITLE' => 'Cases',






    'LBL_MEMBER_ORG_SUBPANEL_TITLE' => 'Member Organizations',
    'LBL_BUGS_SUBPANEL_TITLE' => 'Bugs',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'Projects',
    'LBL_ASSIGNED_TO_NAME' => 'Assigned User Name:',

    // Dashlet Categories
    'LBL_DEFAULT' => 'Views',
    'LBL_CHARTS'    => 'Charts',
    'LBL_UTILS'    => 'Utils',
    'LBL_MISC'    => 'Misc',
    
);


?>
