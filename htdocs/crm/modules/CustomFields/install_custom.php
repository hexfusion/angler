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
 * $Id: install_custom.php,v 1.11 2006/06/06 17:57:57 majed Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/



require_once('data/Tracker.php');
require_once('include/utils.php');
require_once('include/modules.php');

require_once('modules/CustomFields/CustomFields.php');

// load up the config_override.php file.  This is used to provide default user settings
if (is_file("config_override.php")) {
	require_once("config_override.php");
}
$db = PearDatabase::getInstance();
$focus = new CustomFields();


	if ($db->tableExists($focus->table_name)) {			
		$focus->drop_tables();
		$focus->create_tables();
		$GLOBALS['log']->info("<li>Table ".$focus->table_name." already exists.");
		return 0;
	} else {
		$focus->create_tables();
		$GLOBALS['log']->info("<li>Created ".$focus->table_name." table.");
		return 1;
	}
?>
