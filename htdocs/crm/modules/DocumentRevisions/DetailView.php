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
 * $Id: DetailView.php,v 1.4 2006/08/29 20:53:08 awu Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Documents/Document.php');
require_once('modules/DocumentRevisions/DocumentRevision.php');
require_once('include/upload_file.php');
require_once('modules/Users/User.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $gridline;

$focus = new DocumentRevision();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
$old_id = '';

echo "\n<p>\n";
echo get_module_title('DocumentRevisions', $mod_strings['LBL_MODULE_NAME'].": ".$focus->document_name, true); 
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("Document revision detail view");

$xtpl=new XTemplate ('modules/DocumentRevisions/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

$focus->fill_document_name_revision($focus->document_id);

$xtpl->assign("ID", $focus->id);
$xtpl->assign("DOCUMENT_NAME",$focus->name);
$xtpl->assign("CURRENT_REVISION",$focus->latest_revision);
$xtpl->assign("CHANGE_LOG",$focus->change_log);
$created_user = new User();
$created_user->retrieve($focus->created_by);
$xtpl->assign("CREATED_BY",$created_user->first_name . ' ' . $created_user->last_name);

$xtpl->assign("DATE_CREATED",$focus->date_entered);
$xtpl->assign("REVISION",$focus->revision);
$xtpl->assign("FILENAME",$focus->filename);

$xtpl->assign("FILE_NAME", $focus->filename);
$xtpl->assign("SAVE_FILE", $focus->id);

$xtpl->assign("FILE_URL", UploadFile::get_url($focus->filename,$focus->id));
$xtpl->assign("GRIDLINE", $gridline);


$xtpl->parse("main");
$xtpl->out("main");
?>
