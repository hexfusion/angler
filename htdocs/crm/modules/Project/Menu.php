<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Side-bar menu for Project
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

// $Id: Menu.php,v 1.17 2006/06/06 17:58:32 majed Exp $

global $current_user;
global $mod_strings;
$module_menu = array();

// Each index of module_menu must be an array of:
// the link url, display text for the link, and the icon name.

if(ACLController::checkAccess('Project', 'edit', true))$module_menu[] = array("index.php?module=Project&action=EditView&return_module=Project&return_action=DetailView",
	$mod_strings['LNK_NEW_PROJECT'], 'CreateProject');
if(ACLController::checkAccess('Project', 'list', true))$module_menu[] = array('index.php?module=Project&action=index',
	$mod_strings['LNK_PROJECT_LIST'], 'Project');
if(ACLController::checkAccess('ProjectTask', 'edit', true))$module_menu[] = array("index.php?module=ProjectTask&action=EditView&return_module=ProjectTask&return_action=DetailView",
	$mod_strings['LNK_NEW_PROJECT_TASK'], 'CreateProjectTask');
if(ACLController::checkAccess('ProjectTask', 'list', true))$module_menu[] = array('index.php?module=ProjectTask&action=index',
	$mod_strings['LNK_PROJECT_TASK_LIST'], 'ProjectTask');

?>
