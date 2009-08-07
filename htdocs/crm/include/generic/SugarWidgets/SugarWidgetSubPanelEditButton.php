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

// $Id: SugarWidgetSubPanelEditButton.php,v 1.11 2006/06/12 22:00:07 jacob Exp $

require_once('include/generic/SugarWidgets/SugarWidgetField.php');
//TODO Rename this to edit link
class SugarWidgetSubPanelEditButton extends SugarWidgetField
{
	function displayHeaderCell(&$layout_def)
	{
		return '&nbsp;';
	}

	function displayList(&$layout_def)
	{
		global $app_strings;
		global $image_path;

		$href = 'index.php?module=' . $layout_def['module']
			. '&action=' . 'EditView'
			. '&record=' . $layout_def['fields']['ID']
			. '&return_module=' . $_REQUEST['module']
			. '&return_action=' . 'DetailView'
			. '&return_id=' . $_REQUEST['record'];

		$edit_icon_html = get_image($image_path . 'edit_inline',
			'align="absmiddle" alt="' . $app_strings['LNK_EDIT'] . '" border="0"');
		if($layout_def['EditView']){
			return '<a href="' . $href . '"'
			. 'class="listViewTdToolsS1">' . $edit_icon_html . '&nbsp;' . $app_strings['LNK_EDIT'] .'</a>&nbsp;';
		}else{
			return '';
		}
	}
		
}

?>
