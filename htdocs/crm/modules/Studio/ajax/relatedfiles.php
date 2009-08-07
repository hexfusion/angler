<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
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

 // $Id: relatedfiles.php,v 1.7 2006/08/26 02:46:02 majed Exp $

require_once('include/SubPanel/SubPanel.php');
require_once('modules/Studio/parsers/StudioParser.php');
$moduleFiles = StudioParser::getFiles($the_module);
echo '<table width="100%" class="tabForm" cellpadding="0" cellspacing="0"><tr><td align="right"><b>Layouts:</b></td><td class="dataLabel" align="left">';
$count = 0;
foreach($moduleFiles as $id=>$attr){
	$label = translate($id, $the_module);
	if($count > 0){
	echo '&nbsp;|&nbsp;';
	}
	echo " <a href='index.php?module=Studio&action=wizard&wizard=SelectModuleLayout&option=$id' class='tabFormAdvLink'>{$label}</a>&nbsp;";
	
	$count++;
}
echo '</td></tr>';
echo '<tr><td align="right"><b>Subpanels:</b></td><td align="left">';

$layout_def = SubPanel::getModuleSubpanels($the_module);
$count = 0;

foreach($layout_def as $key=>$sub){
	if($sub == 'users')unset($layout_def[$key]);
		
}
$totalCount = count($layout_def);
foreach($layout_def as $sub){
	
	if($count > 0){
	echo '&nbsp;|&nbsp;';
	}
    $subname = (!empty($GLOBALS['layout_defs'][$the_module]['subpanel_setup'][$sub]['title_key']))? translate($GLOBALS['layout_defs'][$the_module]['subpanel_setup'][$sub]['title_key'], $the_module):$sub;
	echo "&nbsp;<a href='index.php?module=Studio&action=index&subpanel={$sub}' class='tabFormAdvLink'>{$subname}</a> ";
	
	$count++;
}	


echo '</td></tr></table>';

?>
