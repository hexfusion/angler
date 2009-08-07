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
 * $Id: end.php,v 1.7 2006/08/19 05:51:34 awu Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/
logThis('[At end.php]');

// flag to say upgrade has completed
$_SESSION['upgrade_complete'] = true;

$stop = false;

$uwMain =<<<eoq
<table cellpadding="3" cellspacing="0" border="0">
	<tr>
		<th align="left">
			{$mod_strings['LBL_UW_TITLE_END']}
		</th>
	</tr>
	<tr>
		<td align="left">
			<p>
			{$mod_strings['LBL_UW_END_DESC']}
			</p>
			<p>
			{$mod_strings['LBL_UW_END_DESC2']}
			</p>
			<p>
			{$mod_strings['LBL_UW_REPAIR_INDEX']}
			</p>			
		</td>
	</tr>
	<tr>
		<td align="left">
			<input type="button" value="{$mod_strings['LBL_BUTTON_DONE']}" onclick="window.location.href='{$sugar_config['site_url']}/index.php?module=Home&action=About'">
		</td>
	</tr>
</table>
eoq;

$showBack		= false;
$showCancel		= false;
$showRecheck	= false;
$showNext		= false;

$stepBack		= 0;
$stepNext		= 0;
$stepCancel	= 0;
$stepRecheck	= 0;

$_SESSION['step'][$steps['files'][$_REQUEST['step']]] = ($stop) ? 'failed' : 'success';

?>
