<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SubPanelViewer
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

// $Id: SubPanelViewer.php,v 1.12 2006/06/06 17:57:50 majed Exp $

global $gridline;
global $theme;
global $beanList;
global $beanFiles;


if(empty($_REQUEST['module']))
{
	die("'module' was not defined");
}

if(empty($_REQUEST['record']))
{
	die("'record' was not defined");
}

if(!isset($beanList[$_REQUEST['module']]))
{
	die("'".$_REQUEST['module']."' is not defined in \$beanList");
}

$subpanel = $_REQUEST['subpanel'];
$record = $_REQUEST['record'];
$module = $_REQUEST['module'];

require_once('themes/'.$theme.'/layout_utils.php');
$image_path = 'themes/'.$theme.'/images/';

if(empty($_REQUEST['inline']))
{
	insert_popup_header($theme);
}

//require_once('include/SubPanel/SubPanelDefinitions.php');
//require_once($beanFiles[$beanList[$_REQUEST['module']]]);
//$focus=new $beanList[$_REQUEST['module']];
//$focus->retrieve($record);

include('include/SubPanel/SubPanel.php');
$layout_def_key = '';
if(!empty($_REQUEST['layout_def_key'])){
	$layout_def_key = $_REQUEST['layout_def_key'];
}

$subpanel_object = new SubPanel($module, $record, $subpanel,null, $layout_def_key);

$subpanel_object->setTemplateFile('include/SubPanel/SubPanelDynamic.html');
echo (empty($_REQUEST['inline']))?$subpanel_object->get_buttons():'' ;  

$subpanel_object->display();

if(empty($_REQUEST['inline']))
{
	insert_popup_footer($theme);
}

?>
