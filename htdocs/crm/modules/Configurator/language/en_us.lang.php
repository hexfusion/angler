<?php
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
 * $Id: en_us.lang.php,v 1.21 2006/08/01 04:38:53 majed Exp $
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
	'ADMIN_EXPORT_ONLY'=>'Admin export only',
	'ADVANCED'=>'Advanced',
	'CURRENT_LOGO'=>'Current logo in use',
	'DEFAULT_CURRENCY_ISO4217'=>'ISO 4217 currency code',
	'DEFAULT_CURRENCY_NAME'=>'Currency name',
	'DEFAULT_CURRENCY_SYMBOL'=>'Currency symbol',
	'DEFAULT_CURRENCY'=>'Default Currency',
	'DEFAULT_DATE_FORMAT'=>'Default date format',
	'DEFAULT_DECIMAL_SEP'					=> 'Decimal symbol',
	'DEFAULT_LANGUAGE'=>'Default language',
	'DEFAULT_NUMBER_GROUPING_SEP'			=> '1000s separator',
	'DEFAULT_SYSTEM_SETTINGS'=>'User Interface',
	'DEFAULT_THEME'=> 'Default theme',
	'DEFAULT_TIME_FORMAT'=>'Default time format',
	'DISABLE_EXPORT'=>'Disable export',
	'DISPLAY_LOGIN_NAV'=>'Display tabs on login screen',
	'DISPLAY_RESPONSE_TIME'=>'Display server response times',
	'EXPORT'=>'Export',
	'EXPORT_CHARSET' => 'Default Export Character Set',
	'EXPORT_DELIMITER' => 'Export Delimiter',
	'IMAGES'=>'Logos',
	'LBL_CONFIGURE_SETTINGS_TITLE' => 'System Settings',
	'LBL_ENABLE_MAILMERGE' => 'Enable mail merge?',
	'LBL_LOGVIEW' => 'Configure Log Settings',
	'LBL_MAIL_SMTPAUTH_REQ'				=> 'Use SMTP Authentication?',
	'LBL_MAIL_SMTPPASS'					=> 'SMTP Password:',
	'LBL_MAIL_SMTPPORT'					=> 'SMTP Port:',
	'LBL_MAIL_SMTPSERVER'				=> 'SMTP Server:',
	'LBL_MAIL_SMTPUSER'					=> 'SMTP Username:',
	'LBL_MAILMERGE_DESC' => 'This flag should be checked only if you have the Sugar Plug-in for Microsoft&reg; Word&reg;.',
	'LBL_MAILMERGE' => 'Mail Merge',
	'LBL_MODULE_NAME'=>'System Settings',
    'LBL_MODULE_ID'  => 'Configurator',
	'LBL_MODULE_TITLE'=>'User Interface',
	'LBL_NOTIFY_FROMADDRESS' => '"From" Address:',
	'LBL_NOTIFY_SUBJECT' => 'Email subject:',
	'LBL_PORTAL_ON_DESC' => 'Allows Case, Note and other data to be accessible by an external customer self-service portal system.',
	'LBL_PORTAL_ON' => 'Enable self-service portal integration?',
	'LBL_PORTAL_TITLE' => 'Customer Self-Service Portal',
	'LBL_PROXY_AUTH'=>'Authentication?',
	'LBL_PROXY_HOST'=>'Proxy Host',
	'LBL_PROXY_ON_DESC'=>'Configure proxy server address and authentication settings',
	'LBL_PROXY_ON'=>'Use proxy server?',
	'LBL_PROXY_PASSWORD'=>'Password',
	'LBL_PROXY_PORT'=>'Port',
	'LBL_PROXY_TITLE'=>'Proxy Settings',
	'LBL_PROXY_USERNAME'=>'User Name',
	'LBL_RESTORE_BUTTON_LABEL'=>'Restore',
	'LBL_SKYPEOUT_ON_DESC' => 'Allows users to click on phone numbers to call using SkypeOut&reg;. The numbers must be formatted properly to make use of this feature. That is, it must be "+"  "The Country Code" "The Number", like +1 (555) 555-1234. For more information, see the Skype FAQ at <a href="http://www.skype.com/help/faq/skypeout.html#calling" target="skype">skype&reg; faq</a>	',
	'LBL_SKYPEOUT_ON' => 'Enable SkypeOut&reg; integration?',
	'LBL_SKYPEOUT_TITLE' => 'SkypeOut&reg;',
	'LBL_USE_REAL_NAMES'	=> 'Show Full Name (not Login)',
	'LIST_ENTRIES_PER_LISTVIEW'=>'Listview items per page',
	'LIST_ENTRIES_PER_SUBPANEL'=>'Subpanel items per page',
	'LOG_MEMORY_USAGE'=>'Log memory usage',
	'LOG_SLOW_QUERIES'=> 'Log slow queries',
	'NEW_LOGO'=>'Upload new logo (212x40)',
	'NEW_QUOTE_LOGO'=>'Upload new Quote logo (867x74)',
	'QUOTES_CURRENT_LOGO'=>'Logo used in Quotes ',
	'SLOW_QUERY_TIME_MSEC'=>'Slow query time threshold (msec)',
	'STACK_TRACE_ERRORS'=>'Display stack trace of errors',
	'UPLOAD_MAX_SIZE'=>'Maximum upload size',
	'VERIFY_CLIENT_IP'=>'Validate user IP address',
    'LOCK_HOMEPAGE' => 'Prevent user customizable Homepage layout',
    'LOCK_SUBPANELS' => 'Prevent user customizable subpanel layout',
    'MAX_DASHLETS' => 'Maximum number of Dashlets on Homepage',







    'LBL_LDAP_TITLE'=>'LDAP Authentication Support',
    'LBL_LDAP_ENABLE'=>'Enable LDAP',
    'LBL_LDAP_SERVER_HOSTNAME'=> 'Server:',
    'LBL_LDAP_ADMIN_USER'=> 'Authenticated User:',
    'LBL_LDAP_ADMIN_USER_DESC'=>'Used to search for the sugar user. [May need to be fully qualified]<br>It will bind anonymously if not provided.',
    'LBL_LDAP_ADMIN_PASSWORD'=> 'Authenticated Password:',
    'LBL_LDAP_AUTO_CREATE_USERS'=>'Auto Create Users:',
    'LBL_LDAP_BASE_DN'=>'Base DN:',
    'LBL_LDAP_LOGIN_ATTRIBUTE'=>'Login Attribute:',
    'LBL_LDAP_BIND_ATTRIBUTE'=>'Bind Attribute:',
    'LBL_LDAP_BIND_ATTRIBUTE_DESC'=>'For Binding the LDAP User Examples:[<b>AD:</b>&nbsp;userPrincipalName] [<b>openLDAP:</b>&nbsp;userPrincipalName] [<b>Mac&nbsp;OS&nbsp;X:</b>&nbsp;uid] ',
    'LBL_LDAP_LOGIN_ATTRIBUTE_DESC'=>'For searching for the LDAP User Examples:[<b>AD:</b>&nbsp;userPrincipalName] [<b>openLDAP:</b>&nbsp;dn] [<b>Mac&nbsp;OS&nbsp;X:</b>&nbsp;dn] ',
    'LBL_LDAP_SERVER_HOSTNAME_DESC'=>'Example: ldap.example.com',
    'LBL_LDAP_BASE_DN_DESC'=>'Example: DC=SugarCRM,DC=com',
    'LBL_LDAP_AUTO_CREATE_USERS_DESC'=> 'If an authenticated user does not exist one will be created in Sugar.',
);


?>
