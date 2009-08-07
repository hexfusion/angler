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
 * $Id: cancel.php,v 1.10 2006/08/12 07:21:15 chris Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/
logThis('[At cancel.php]');
logThis('cleaning up files and session.  goodbye.');
unlinkTempFiles();
unlinkUploadFiles();
resetUwSession();

$uwMain =<<<eoq
<table cellpadding="3" cellspacing="0" border="0">
	<tr>
		<th align="left">
			{$mod_strings['LBL_UW_TITLE_CANCEL']}
		</th>
	</tr>
	<tr>
		<td align="left">
			<p>
			{$mod_strings['LBL_UW_CANCEL_DESC']}
			</p>
		</td>
	</tr>
	<tr>
		<th align="left">
			<input	title		= "{$mod_strings['LBL_BUTTON_DONE']}" 
					class		= "button"
					onclick		= "window.location.href ='{$sugar_config['site_url']}/index.php?module=UpgradeWizard&action=index';" 
					type		= "submit"
					value		= "  {$mod_strings['LBL_BUTTON_DONE']}  "
					id			= "done_button" >
		</th>
	</tr>
</table>
eoq;


$showBack		= false;
$showCancel		= false;
$showRecheck	= false;
$showNext		= false;

$stepBack		= $_REQUEST['step'] - 1;
$stepNext		= $_REQUEST['step'] + 1;
$stepCancel		= -1;
$stepRecheck	= $_REQUEST['step'];

?>
