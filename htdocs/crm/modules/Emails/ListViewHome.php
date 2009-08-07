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
 * $Id: ListViewHome.php,v 1.13 2006/07/12 22:53:10 jenny Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $theme;
global $sugar_config;
global $current_language;
$currentMax = $sugar_config['list_max_entries_per_page'];
$sugar_config['list_max_entries_per_page'] = 10;

require_once('XTemplate/xtpl.php');
require_once('modules/Emails/Email.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/ListView/ListView.php');
require_once('include/utils.php');

$current_mod_strings = return_module_language($current_language, 'Emails');
$focus			= new Email();
$ListView 		= new ListView();
$display_title	= $current_mod_strings['LBL_LIST_TITLE_MY_INBOX'].': '.$current_mod_strings['LBL_UNREAD_HOME'];
$where			= 'emails.deleted = 0 AND emails.assigned_user_id = \''.$current_user->id.'\' AND emails.type = \'inbound\' AND emails.status = \'unread\'';
$limit			= 10;
///////////////////////////////////////////////////////////////////////////////
////	OUTPUT
///////////////////////////////////////////////////////////////////////////////
echo $focus->rolloverStyle;
$ListView->initNewXTemplate('modules/Emails/ListViewHome.html',$current_mod_strings);
$ListView->xTemplateAssign('ATTACHMENT_HEADER', get_image('themes/'.$theme.'/images/attachment',"","",""));
$ListView->setHeaderTitle($display_title);
$ListView->setQuery($where, '', 'date_sent, date_entered DESC', "EMAIL");
$ListView->setAdditionalDetails();
$ListView->processListView($focus, 'main', 'EMAIL');

//echo $focus->quickCreateJS();

$sugar_config['list_max_entries_per_page'] = $currentMax;
?>
