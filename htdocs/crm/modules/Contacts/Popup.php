<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Popup
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

// $Id: Popup.php,v 1.71 2006/06/06 17:57:56 majed Exp $




if(!empty($_REQUEST['html'])) {
	require_once('modules/Contacts/Popup_picker.php');
	$popup = new Popup_Picker();
	
	if($_REQUEST['html'] == 'Email_picker')
	{
		echo $popup->process_page_for_email();
	}
	elseif($_REQUEST['html'] == 'change_address')
	{
		echo $popup->process_page_for_address();
	}
	elseif($_REQUEST['html'] == 'mail_merge')
	{
		echo $popup->process_page_for_merge();
	}
}
else
{
	require_once('include/Popups/Popup_picker.php');
	$popup = new Popup_Picker();

	echo $popup->process_page();
}

?>
