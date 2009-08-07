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
 * $Id: Save.php,v 1.7 2006/08/25 19:39:17 chris Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('modules/Administration/Administration.php');

$focus = new Administration();

if(isset($_POST['tracking_entities_location_type'])) {
	if ($_POST['tracking_entities_location_type'] != '2') {
		unset($_POST['tracking_entities_location']);
		unset($_POST['tracking_entities_location_type']);
	}
}
// cn: handle mail_smtpauth_req checkbox on/off (removing double reference in the form itself
if(!isset($_POST['mail_smtpauth_req'])) { $_POST['mail_smtpauth_req'] = 0; }
$focus->saveConfig();

// save User defaults for emails
$sugar_config['email_default_client'] = $_REQUEST['email_default_client'];
$sugar_config['email_default_editor'] = $_REQUEST['email_default_editor'];
$sugar_config['default_email_charset'] = $_REQUEST['default_email_charset'];
write_array_to_file('sugar_config', $sugar_config, 'config.php');

header("Location: index.php?action={$_POST['return_action']}&module={$_POST['return_module']}");
?>
