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
 * $Id: Menu.php,v 1.14 2006/07/15 01:22:50 jenny Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings, $app_strings;
if(ACLController::checkAccess('Campaigns', 'edit', true))$module_menu[]=	Array("index.php?module=Campaigns&action=EditView&return_module=Campaigns&return_action=index", $mod_strings['LNK_NEW_CAMPAIGN'],"CreateCampaigns");
if(ACLController::checkAccess('Campaigns', 'list', true))$module_menu[]=	Array("index.php?module=Campaigns&action=index&return_module=Campaigns&return_action=index", $mod_strings['LNK_CAMPAIGN_LIST'],"Campaigns");
if(ACLController::checkAccess('ProspectLists', 'edit', true))$module_menu[]=	Array("index.php?module=ProspectLists&action=EditView&return_module=ProspectLists&return_action=DetailView", $mod_strings['LNK_NEW_PROSPECT_LIST'],"CreateProspectLists");
if(ACLController::checkAccess('ProspectLists', 'list', true))$module_menu[]=	Array("index.php?module=ProspectLists&action=index&return_module=ProspectLists&return_action=index", $mod_strings['LNK_PROSPECT_LIST_LIST'],"ProspectLists");
if(ACLController::checkAccess('Prospects', 'edit', true))$module_menu[]=	Array("index.php?module=Prospects&action=EditView&return_module=Prospects&return_action=DetailView", $mod_strings['LNK_NEW_PROSPECT'],"CreateProspects");
if(ACLController::checkAccess('Prospects', 'list', true))$module_menu[]=	Array("index.php?module=Prospects&action=index&return_module=Prospects&return_action=index", $mod_strings['LNK_PROSPECT_LIST'],"Prospects");
if(ACLController::checkAccess('EmailTemplates', 'edit', true))$module_menu[]= Array("index.php?module=EmailTemplates&action=EditView&return_module=EmailTemplates&return_action=DetailView", $mod_strings['LNK_NEW_EMAIL_TEMPLATE'],"CreateEmails","Emails");
if(ACLController::checkAccess('EmailTemplates', 'list', true))$module_menu[]=Array("index.php?module=EmailTemplates&action=index", $mod_strings['LNK_EMAIL_TEMPLATE_LIST'],"EmailFolder", 'Emails');
if(ACLController::checkAccess('Prospects', 'import', true))$module_menu[] = Array("index.php?module=Prospects&action=Import&step=1&return_module=Campaigns&return_action=index", $app_strings['LBL_IMPORT_PROSPECTS'],"Import");
if(ACLController::checkAccess('Campaigns','list', true)) $module_menu[] = Array('#', '<span style="display: none">wp_shortcut_fill_0</span>', '');
?>
