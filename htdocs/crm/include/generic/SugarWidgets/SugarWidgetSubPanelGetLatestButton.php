<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelEditButton
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

// $Id: SugarWidgetSubPanelGetLatestButton.php,v 1.3 2006/06/06 17:57:52 majed Exp $
//this widget is used only by the contracts module..

require_once('include/generic/SugarWidgets/SugarWidgetField.php');
class SugarWidgetSubPanelGetLatestButton extends SugarWidgetField
{
	function displayHeaderCell(&$layout_def)
	{
		return '&nbsp;';
	}

	function displayList(&$layout_def)
	{
		//if the contract has been executed or selected_revision is same as latest revision
		//then hide the latest button. 		
		//if the contract state is executed or document is not a template hide this action.
		if ((!empty($layout_def['fields']['CONTRACT_STATUS']) && $layout_def['fields']['CONTRACT_STATUS']=='executed') or
			$layout_def['fields']['SELECTED_REVISION_ID']== $layout_def['fields']['LATEST_REVISION_ID']) {
			return "";
		}
		
		global $app_strings;
		global $image_path;

		$href = 'index.php?module=' . $layout_def['module']
			. '&action=' . 'GetLatestRevision'
			. '&record=' . $layout_def['fields']['ID']
			. '&return_module=' . $_REQUEST['module']
			. '&return_action=' . 'DetailView'
			. '&return_id=' . $_REQUEST['record']
			. '&get_latest_for_id=' . $layout_def['fields']['LINKED_ID'];

		$edit_icon_html = get_image($image_path . 'getLatestDocument',
			'align="absmiddle" alt="' . $app_strings['LNK_GET_LATEST'] . '" border="0"');
		if($layout_def['EditView']){
			return '<a href="' . $href . '"' . "title ='". $app_strings['LNK_GET_LATEST_TOOLTIP']  ."'"
			. 'class="listViewTdToolsS1">' . $edit_icon_html . '&nbsp;' . $app_strings['LNK_GET_LATEST'] .'</a>&nbsp;';
		}else{
			return '';
		}
	}
		
}

?>
