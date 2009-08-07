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
echo get_module_title('Administration', $mod_strings['LBL_REBUILD_SCHEDULERS_TITLE'].":", true);

if(isset($_REQUEST['perform_rebuild']) && $_REQUEST['perform_rebuild'] == 'true') {
	require_once('modules/Schedulers/Scheduler.php');
	$focus = new Scheduler();
	$focus->rebuildDefaultSchedulers();
	
$admin_mod_strings = return_module_language($current_language, 'Administration');	
?>
<table width="100%" cellpadding="0" cellspacing="{CELLSPACING}" border="0" class="tabDetailView2">
	<tr> 
		<td class="tabDetailViewDL2" width="35%"><?php echo $admin_mod_strings['LBL_REBUILD_SCHEDULERS_DESC_SUCCESS']; ?></td>
		<td><a href="index.php?module=Administration&action=Upgrade"><?php echo $admin_mod_strings['LBL_RETURN']; ?></a></td>
	</tr>
</table>
<?php
} else {
?>	
<p>
<form name="RebuildSchedulers" method="post" action="index.php">
<input type="hidden" name="module" value="Administration">
<input type="hidden" name="action" value="RebuildSchedulers">
<input type="hidden" name="return_module" value="Administration">
<input type="hidden" name="return_action" value="Upgrade">
<input type="hidden" name="perform_rebuild" value="true">
<table width="100%" cellpadding="0" cellspacing="{CELLSPACING}" border="0" class="tabDetailView2">
	<tr>
	    <td class="tabDetailViewDL2" width="15%"><?php echo $mod_strings['LBL_REBUILD_SCHEDULERS_TITLE']; ?></td>
	    <td><input type="submit" name="button" value="<?php echo $mod_strings['LBL_REBUILD']; ?>"></td>
	</tr>
	<tr> 
		<td colspan="2" class="tabDetailViewDL2"><?php echo $mod_strings['LBL_REBUILD_SCHEDULERS_DESC']; ?></td>
	</tr>
</table>
</form>
</p>
<?php
}
?>
