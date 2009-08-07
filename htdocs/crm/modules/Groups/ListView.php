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
 * Description:
 * Created On: Nov 12, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/
require_once('modules/Groups/Group.php');
require_once('include/ListView/ListView.php');
global $mod_strings;
global $current_language;

$focus = new Group();
$where = ' users.users.is_group = 1 ';

$current_module_strings = return_module_language($current_language, 'Users');

$ListView = new ListView();
$ListView->initNewXTemplate('modules/Groups/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($mod_strings['LBL_LIST_TITLE']);
$ListView->setQuery($where, "", "last_name, first_name", "USER");
$ListView->show_mass_update=false;
$ListView->processListView($focus, "main", "USER");
?>
