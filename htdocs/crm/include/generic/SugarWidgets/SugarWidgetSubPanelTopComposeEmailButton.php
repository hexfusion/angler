<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelTopCreateNoteButton
 *
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

// $Id: SugarWidgetSubPanelTopComposeEmailButton.php,v 1.17 2006/06/06 17:57:53 majed Exp $

require_once('include/generic/SugarWidgets/SugarWidgetSubPanelTopButton.php');

class SugarWidgetSubPanelTopComposeEmailButton extends SugarWidgetSubPanelTopButton
{
	function display($defines)
	{
		global $app_strings;
		global $currentModule;
		
		$title = $app_strings['LBL_COMPOSE_EMAIL_BUTTON_TITLE'];
		$accesskey = $app_strings['LBL_COMPOSE_EMAIL_BUTTON_KEY'];
		$value = $app_strings['LBL_COMPOSE_EMAIL_BUTTON_LABEL'];
		$this->module = 'Emails';
		$to_addrs = '';
		$additionalFormFields = array();
		$additionalFormFields['type'] = 'out';
		// cn: bug 5727 - must override the parents' parent for contacts (which could be an Account)
		$additionalFormFields['parent_type'] = $defines['focus']->module_dir; 
		$additionalFormFields['parent_id'] = $defines['focus']->id;
		$additionalFormFields['parent_name'] = $defines['focus']->name;

		if(isset($defines['focus']->email1)) {
			$to_addrs = $defines['focus']->email1;
		} elseif($defines['focus']->object_name == 'Case') {
			require_once('modules/Accounts/Account.php');
			$acct = new Account();
			$acct->retrieve($defines['focus']->account_id);
			$to_addrs = $acct->email1;
		}
		
		if(!empty($to_addrs)) {
			$additionalFormFields['to_email_addrs'] = $to_addrs;
		}
		if(ACLController::moduleSupportsACL($defines['module'])  && !ACLController::checkAccess($defines['module'], 'edit', true)){
			$button = "<input title='$title' class='button' type='button' name='button' value='  $value  '/>\n";
			return $button;
		}
		$button = $this->_get_form($defines, $additionalFormFields);
		$button .= "<input title='$title' accesskey='$accesskey' class='button' type='submit' name='button' value='  $value  '/>\n";
		$button .= "</form>";
		return $button;
	}
}
?>
