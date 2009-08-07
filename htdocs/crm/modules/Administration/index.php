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
 * $Id: index.php,v 1.128 2006/08/27 12:08:56 majed Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $theme;
global $currentModule;
global $current_language;
global $gridline;
global $current_user;
global $sugar_flavor;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

require_once($theme_path.'layout_utils.php');

require_once('XTemplate/xtpl.php');



if (!is_admin($current_user))
{
   sugar_die("Unauthorized access to administration.");
}

echo '<p>' .
      get_module_title($mod_strings['LBL_MODULE_NAME'],
                       $mod_strings['LBL_MODULE_TITLE'], true)
      . '</p>';

//Sugar Network
$admin_option_defs=array();
$license_key = 'no_key';

$admin_option_defs['support']= array($image_path . 'Support','LBL_SUPPORT_TITLE','LBL_SUPPORT','./index.php?module=Administration&action=SupportPortal&view=support_portal');
//$admin_option_defs['documentation']= array($image_path . 'OnlineDocumentation','LBL_DOCUMENTATION_TITLE','LBL_DOCUMENTATION','./index.php?module=Administration&action=SupportPortal&view=documentation&help_module=Administration&edition='.$sugar_flavor.'&key='.$server_unique_key.'&language='.$current_language);
$admin_option_defs['documentation']= array($image_path . 'OnlineDocumentation','LBL_DOCUMENTATION_TITLE','LBL_DOCUMENTATION',
    'javascript:void window.open("index.php?module=Administration&action=SupportPortal&view=documentation&help_module=Administration&edition='.$sugar_flavor.'&key='.$server_unique_key.'&language='.$current_language.'", "helpwin","width=600,height=600,status=0,resizable=1,scrollbars=1,toolbar=0,location=0")');


$admin_option_defs['update'] = array($image_path . 'sugarupdate','LBL_SUGAR_UPDATE_TITLE','LBL_SUGAR_UPDATE','./index.php?module=Administration&action=Updater');
if(!empty($license->settings['license_latest_versions'])){
	$encodedVersions = $license->settings['license_latest_versions'];
	$versions = unserialize(base64_decode( $encodedVersions));
	include('sugar_version.php');
	if(!empty($versions)){
		foreach($versions as $version){
			if($version['version'] > $sugar_version )
			{
				$admin_option_defs['update'][] ='red';
				if(!isset($admin_option_defs['update']['additional_label']))$admin_option_defs['update']['additional_label']= '('.$version['version'].')';

			}
		}
	}
}








$admin_group_header[]=array('LBL_SUGAR_NETWORK_TITLE','',false,$admin_option_defs);


//system.
$admin_option_defs=array();
$admin_option_defs['configphp_settings']= array($image_path .'Administration','LBL_CONFIGURE_SETTINGS_TITLE','LBL_CONFIGURE_SETTINGS','./index.php?module=Configurator&action=EditView');
$admin_option_defs['backup_management']= array($image_path . 'Backups','LBL_BACKUPS_TITLE','LBL_BACKUPS','./index.php?module=Administration&action=Backups');
$admin_option_defs['scheduler'] = array($image_path . 'Schedulers','LBL_SUGAR_SCHEDULER_TITLE','LBL_SUGAR_SCHEDULER','./index.php?module=Schedulers&action=index');
$admin_option_defs['repair']= array($image_path . 'Repair','LBL_UPGRADE_TITLE','LBL_UPGRADE','./index.php?module=Administration&action=Upgrade');
$admin_option_defs['diagnostic']= array($image_path . 'Diagnostic','LBL_DIAGNOSTIC_TITLE','LBL_DIAGNOSTIC_DESC','./index.php?module=Administration&action=Diagnostic');
$admin_option_defs['currencies_management']= array($image_path . 'Currencies','LBL_MANAGE_CURRENCIES','LBL_CURRENCY','./index.php?module=Currencies&action=index');
$admin_option_defs['upgrade_wizard']= array($image_path . 'Upgrade','LBL_UPGRADE_WIZARD_TITLE','LBL_UPGRADE_WIZARD','./index.php?module=UpgradeWizard&action=index');
$admin_option_defs['module_loader'] = array($image_path . 'ModuleLoader','LBL_MODULE_LOADER_TITLE','LBL_MODULE_LOADER','./index.php?module=Administration&action=UpgradeWizard&view=module');
$admin_option_defs['currencies_management']= array($image_path . 'Currencies','LBL_MANAGE_CURRENCIES','LBL_CURRENCY','./index.php?module=Currencies&action=index');
$admin_option_defs['locale']= array($image_path . 'Currencies','LBL_MANAGE_LOCALE','LBL_LOCALE','./index.php?module=Administration&action=Locale&view=default');



$admin_group_header[]=array('LBL_ADMINISTRATION_HOME_TITLE','',false,$admin_option_defs);

//users and security.
$admin_option_defs=array();
$admin_option_defs['user_management']= array($image_path . 'Users','LBL_MANAGE_USERS_TITLE','LBL_MANAGE_USERS','./index.php?module=Users&action=ListView');
$admin_option_defs['roles_management']= array($image_path . 'Roles','LBL_MANAGE_ROLES_TITLE','LBL_MANAGE_ROLES','./index.php?module=ACLRoles&action=index');



$admin_group_header[]=array('LBL_USERS_TITLE','',false,$admin_option_defs);

//email manager.
$admin_option_defs=array();
$admin_option_defs['mass_Email_config']= array($image_path . 'EmailMan','LBL_MASS_EMAIL_CONFIG_TITLE','LBL_MASS_EMAIL_CONFIG_DESC','./index.php?module=EmailMan&action=config');
$admin_option_defs['mass_Email']= array($image_path . 'EmailMan','LBL_MASS_EMAIL_MANAGER_TITLE','LBL_MASS_EMAIL_MANAGER_DESC','./index.php?module=EmailMan&action=index');
$admin_option_defs['mailboxes']= array($image_path . 'InboundEmail','LBL_MANAGE_MAILBOX','LBL_MAILBOX_DESC','./index.php?module=InboundEmail&action=index');
$admin_group_header[]=array('LBL_EMAIL_TITLE','',false,$admin_option_defs);

//studio.
$admin_option_defs=array();
$admin_option_defs['studio']= array($image_path . 'Layout','LBL_STUDIO','LBL_STUDIO_DESC','./index.php?module=Studio&action=index');
$admin_option_defs['portal']= array($image_path . 'iFrames','LBL_IFRAME','DESC_IFRAME','./index.php?module=iFrames&action=index');
//$admin_option_defs['manage_layout']= array($image_path . 'Layout','LBL_MANAGE_LAYOUT','LBL_LAYOUT','./index.php?module=DynamicLayout&action=index');
//$admin_option_defs['dropdown_editor']= array($image_path . 'Dropdown','LBL_DROPDOWN_EDITOR','LBL_DROPDOWN_EDITOR','./index.php?module=Dropdown&action=index');
//$admin_option_defs['edit_custom_fields']= array($image_path . 'FieldLabels','LBL_EDIT_CUSTOM_FIELDS','DESC_EDIT_CUSTOM_FIELDS','./index.php?module=EditCustomFields&action=index');
$admin_option_defs['configure_tabs']= array($image_path . 'ConfigureTabs','LBL_CONFIGURE_TABS','LBL_CHOOSE_WHICH','./index.php?module=Administration&action=ConfigureTabs');
$admin_option_defs['configure_group_tabs']= array($image_path . 'ConfigureTabs','LBL_CONFIGURE_GROUP_TABS','LBL_CONFIGURE_GROUP_TABS_DESC','./index.php?action=wizard&module=Studio&wizard=StudioWizard&option=ConfigureGroupTabs');
//$admin_option_defs['migrate_custom_fields']= array($image_path . 'MigrateFields','LBL_EXTERNAL_DEV_TITLE','LBL_EXTERNAL_DEV_DESC','./index.php?module=Administration&action=Development');
$admin_option_defs['rename_tabs']= array($image_path . 'RenameTabs','LBL_RENAME_TABS','LBL_CHANGE_NAME_TABS',"./index.php?action=wizard&module=Studio&wizard=StudioWizard&option=RenameTabs");




$admin_group_header[]=array('LBL_STUDIO_TITLE','',false,$admin_option_defs);













//bug tracker.
$admin_option_defs=array();
$admin_option_defs['bug_tracker']= array($image_path . 'Releases','LBL_MANAGE_RELEASES','LBL_RELEASE','./index.php?module=Releases&action=index');
$admin_group_header[]=array('LBL_BUG_TITLE','',false,$admin_option_defs);














if(file_exists('custom/modules/Administration/Ext/Administration/administration.ext.php')){
	require_once('custom/modules/Administration/Ext/Administration/administration.ext.php');
}
$xtpl=new XTemplate ('modules/Administration/index.html');

foreach ($admin_group_header as $values) {
	$group_header_value=get_form_header($mod_strings[$values[0]],$values[1],$values[2]);
	$xtpl->assign("GROUP_HEADER", $group_header_value);

   $colnum=0;
	foreach ($values[3] as $admin_option) {
		$colnum+=1;
		$xtpl->assign("ITEM_HEADER_IMAGE", get_image($admin_option[0],'alt="' .  $mod_strings[$admin_option[1]] .'" border="0" align="absmiddle"'));
		$xtpl->assign("ITEM_URL", $admin_option[3]);
		$label = $mod_strings[$admin_option[1]];
		if(!empty($admin_option['additional_label']))$label.= ' '. $admin_option['additional_label'];
		if(!empty($admin_option[4])){
			$label = ' <font color="red">'. $label . '</font>';
		}

		$xtpl->assign("ITEM_HEADER_LABEL",$label);
		$xtpl->assign("ITEM_DESCRIPTION", $mod_strings[$admin_option[2]]);

		$xtpl->parse('main.group.row.col');
		if (($colnum % 2) == 0) {
			$xtpl->parse('main.group.row');
		}
	}
	//if the loop above ends with an odd entry add a blank column.
	if (($colnum % 2) != 0) {
		$xtpl->parse('main.group.row.empty');
		$xtpl->parse('main.group.row');
	}

	$xtpl->parse('main.group');
}
$xtpl->parse('main');
$xtpl->out('main');
?>
