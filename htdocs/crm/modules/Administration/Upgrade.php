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

// $Id: Upgrade.php,v 1.55 2006/09/05 19:52:04 majed Exp $

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $theme;
global $currentModule;
global $gridline;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_UPGRADE_TITLE'], true);
echo "\n</p>\n";

?>
<p>
<table width="100%" cellpadding="0" cellspacing="<?php echo $gridline;?>" border="0" class="tabDetailView2">
<tr>
	<td width="20%" class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Opportunities','alt="'. $mod_strings['LBL_MANAGE_OPPORTUNITIES'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Opportunities&action=UpgradeCurrency"><?php echo $mod_strings['LBL_MANAGE_OPPORTUNITIES']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_UPGRADE_CURRENCY'] .' ' .$mod_strings['LBL_MANAGE_OPPORTUNITIES']; ?> </td>
</tr>







<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Upgrade','alt="'. $mod_strings['LBL_UPGRADE_CUSTOM_LABELS_TITLE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=language_upgrade"><?php echo $mod_strings['LBL_UPGRADE_CUSTOM_LABELS_TITLE']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_UPGRADE_CUSTOM_LABELS_DESC'] ; ?> </td>
</tr>
<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Upgrade','alt="'. $mod_strings['LBL_UPGRADE_STUDIO_TITLE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=upgradeStudio"><?php echo $mod_strings['LBL_UPGRADE_STUDIO_TITLE']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_UPGRADE_STUDIO_DESC'] ; ?> </td>
</tr>
<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Repair','alt="'. $mod_strings['LBL_APPLY_DST_FIX'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=DstFix"><?php echo $mod_strings['LBL_APPLY_DST_FIX']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_APPLY_DST_FIX_DESC'] ; ?> </td>
</tr>
<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Repair','alt="'. $mod_strings['LBL_REPAIR_DATABASE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=repairDatabase"><?php echo $mod_strings['LBL_REPAIR_DATABASE']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REPAIR_DATABASE_DESC'] ; ?> </td>
</tr>
<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Repair','alt="'. $mod_strings['LBL_REPAIR_ENTRY_POINTS'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=UpdateSugarEntry"><?php echo $mod_strings['LBL_REPAIR_ENTRY_POINTS']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REPAIR_ENTRY_POINTS_DESC'] ; ?> </td>
</tr>

<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Rebuild','alt="'. $mod_strings['LBL_CLEAR_CHART_DATA_CACHE_TITLE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=clear_chart_cache"><?php echo $mod_strings['LBL_CLEAR_CHART_DATA_CACHE_TITLE']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_CLEAR_CHART_DATA_CACHE_DESC'] ; ?> </td>
</tr>

<tr>
    <td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Rebuild','alt="'. $mod_strings['LBL_REBUILD_HTACCESS'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=UpgradeAccess"><?php echo $mod_strings['LBL_REBUILD_HTACCESS']; ?></a></td>
    <td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REBUILD_HTACCESS_DESC'] ; ?> </td>
</tr>
<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Rebuild','alt="'. $mod_strings['LBL_REBUILD_AUDIT_TITLE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=RebuildAudit"><?php echo $mod_strings['LBL_REBUILD_AUDIT_TITLE']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REBUILD_AUDIT_DESC'] ; ?> </td>
</tr>
<tr>
    <td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Rebuild','alt="'. $mod_strings['LBL_REBUILD_CONFIG'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=RebuildConfig"><?php echo $mod_strings['LBL_REBUILD_CONFIG']; ?></a></td>
    <td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REBUILD_CONFIG_DESC'] ; ?> </td>
</tr>
<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Rebuild','alt="'. $mod_strings['LBL_REBUILD_EXTENSIONS_TITLE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=RebuildExtensions"><?php echo $mod_strings['LBL_REBUILD_EXTENSIONS_TITLE']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REBUILD_EXTENSIONS_DESC'] ; ?> </td>
</tr>
<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Rebuild','alt="'. $mod_strings['LBL_REBUILD_REL_TITLE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=RebuildRelationship"><?php echo $mod_strings['LBL_REBUILD_REL_TITLE']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REBUILD_REL_DESC'] ; ?> </td>
</tr>
<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Rebuild','alt="'. $mod_strings['LBL_REBUILD_SCHEDULERS_TITLE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=RebuildSchedulers"><?php echo $mod_strings['LBL_REBUILD_SCHEDULERS_TITLE']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REBUILD_SCHEDULERS_DESC_SHORT'] ; ?> </td>
</tr>
<tr>
    <td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Rebuild','alt="'. $mod_strings['LBL_REBUILD_DASHLETS_TITLE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=RebuildDashlets"><?php echo $mod_strings['LBL_REBUILD_DASHLETS_TITLE']; ?></a></td>
    <td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REBUILD_DASHLETS_DESC_SHORT'] ; ?> </td>
</tr>
<tr>
    <td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Rebuild','alt="'. $mod_strings['LBL_REBUILD_JAVASCRIPT_LANG_TITLE'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=RebuildJSLang"><?php echo $mod_strings['LBL_REBUILD_JAVASCRIPT_LANG_TITLE']; ?></a></td>
    <td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REBUILD_JAVASCRIPT_LANG_DESC_SHORT'] ; ?> </td>
</tr>











<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Repair','alt="'. $mod_strings['LBL_REPAIR_ROLES'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=ACL&action=install_actions"><?php echo $mod_strings['LBL_REPAIR_ROLES']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REPAIR_ROLES_DESC'] ; ?> </td>
</tr>
<tr>
	<td class="tabDetailViewDL2" nowrap><?php echo get_image($image_path.'Repair','alt="'. $mod_strings['LBL_REPAIR_INDEX'].'" align="absmiddle" border="0"'); ?>&nbsp;<a href="./index.php?module=Administration&action=RepairIndex"><?php echo $mod_strings['LBL_REPAIR_INDEX']; ?></a></td>
	<td class="tabDetailViewDF2"> <?php echo $mod_strings['LBL_REPAIR_INDEX_DESC'] ; ?> </td>
</tr>
</table></p>
