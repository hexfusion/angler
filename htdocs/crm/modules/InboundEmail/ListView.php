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
 * Created On: Oct 17, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/
global $theme;
global $mod_strings;
global $app_list_strings;
global $current_user;

require_once('modules/InboundEmail/InboundEmail.php');
require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/ListView/ListView.php');

$focus = new InboundEmail();
$focus->checkImap();

////	handle saving case macros
if(isset($_REQUEST['save']) && $_REQUEST['save'] == 'true') {
	$focus->saveMacro('Case', $_REQUEST['inbound_email_case_macro']);
}


if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
	$ListView->setHeaderText("<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>" );
}

$where = '';
$limit = '0';
$orderBy = 'date_entered';
$varName = $focus->object_name;
$allowByOverride = true;

$listView = new ListView();
$listView->initNewXTemplate('modules/InboundEmail/ListView.html', $mod_strings);
$listView->setHeaderTitle($mod_strings['LBL_MODULE_TITLE']);

echo $focus->getCaseMacroForm();

$listView->show_export_button = false;
$listView->setQuery($where, $limit, $orderBy, 'InboundEmail', $allowByOverride);
$listView->xTemplateAssign("REMOVE_INLINE_PNG", get_image($image_path.'delete_inline','align="absmiddle" alt="'.$app_strings['LNK_REMOVE'].'" border="0"')); 
$listView->processListView($focus, "main", "InboundEmail");


?>
