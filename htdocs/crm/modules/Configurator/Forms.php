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
 * $Id: Forms.php,v 1.6 2006/06/06 17:57:56 majed Exp $
 * Description:  Contains a variety of utility functions specific to this module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

function get_configsettings_js() {
	global $mod_strings;
	global $app_strings;

	$lbl_last_name = $mod_strings['LBL_NOTIFY_FROMADDRESS'];
	$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];

	return <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function notify_setrequired(f) {

	return true;
}

function add_checks(f) {
	removeFromValidate('ConfigureSettings', 'mail_smtpserver');
	removeFromValidate('ConfigureSettings', 'mail_smtpport');
	removeFromValidate('ConfigureSettings', 'mail_smtpuser');
	removeFromValidate('ConfigureSettings', 'mail_smtppass');
	
	removeFromValidate('ConfigureSettings', 'proxy_port');
	removeFromValidate('ConfigureSettings', 'proxy_host');
	removeFromValidate('ConfigureSettings', 'proxy_username');
	removeFromValidate('ConfigureSettings', 'proxy_password');
	
	
	if (typeof f.mail_sendtype != "undefined" && f.mail_sendtype.value == "SMTP") {
		addToValidate('ConfigureSettings', 'mail_smtpserver', 'varchar', 'true', '{$mod_strings['LBL_MAIL_SMTPSERVER']}');
		addToValidate('ConfigureSettings', 'mail_smtpport', 'int', 'true', '{$mod_strings['LBL_MAIL_SMTPPORT']}');
		if (f.mail_smtpauth_req.checked) {
			addToValidate('ConfigureSettings', 'mail_smtpuser', 'varchar', 'true', '{$mod_strings['LBL_MAIL_SMTPUSER']}');
			addToValidate('ConfigureSettings', 'mail_smtppass', 'varchar', 'true', '{$mod_strings['LBL_MAIL_SMTPPASS']}');
		}
	}
	
	if (f.proxy_on.checked ) {
		addToValidate('ConfigureSettings', 'proxy_port', 'int', 'true', '{$mod_strings['LBL_PROXY_PORT']}');
		addToValidate('ConfigureSettings', 'proxy_host', 'varchar', 'true', '{$mod_strings['LBL_PROXY_HOST']}');
		if (f.proxy_auth.checked ) {
			addToValidate('ConfigureSettings', 'proxy_username', 'varchar', 'true', '{$mod_strings['LBL_PROXY_USERNAME']}');
			addToValidate('ConfigureSettings', 'proxy_password', 'varchar', 'true', '{$mod_strings['LBL_PROXY_PASSWORD']}');
		}
	}
	return true;
}

notify_setrequired(document.ConfigureSettings);

// end hiding contents from old browsers  -->
</script>

EOQ;
}


?>
