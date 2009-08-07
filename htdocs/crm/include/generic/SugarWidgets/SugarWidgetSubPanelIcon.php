<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelIcon
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

// $Id: SugarWidgetSubPanelIcon.php,v 1.14 2006/08/29 20:53:08 awu Exp $

require_once('include/generic/SugarWidgets/SugarWidgetField.php');

class SugarWidgetSubPanelIcon extends SugarWidgetField
{
	function displayHeaderCell(&$layout_def)
	{
		return '&nbsp;';
	}

	function displayList(&$layout_def)
	{
		global $app_strings;
		global $image_path;

		if(isset($layout_def['varname']))
		{
			$key = strtoupper($layout_def['varname']);
		}
		else
		{
			$key = $this->_get_column_alias($layout_def);
			$key = strtoupper($key);
		}
//add module image
		$module = $layout_def['module'];
		$action = 'DetailView';
		$record = $layout_def['fields']['ID'];
		$icon_img_html = get_image($image_path . $module . '', 'border="0" alt="' . $module . '"');
		if (!empty($layout_def['attachment_image_only']) && $layout_def['attachment_image_only'] == true) {
			$ret="";
		}else { 
			$ret= '<a href="index.php?module=' . $module
				. '&action=' . $action
				. '&record=' . $record
				. '" class="listViewTdLinkS1">' . "$icon_img_html</a>";
		}
//if requested, add attachement icon.
		if(!empty($layout_def['image2']) && !empty($layout_def['image2_url_field'])){
			if (is_array($layout_def['image2_url_field'])) {
				$filepath="";
				//Generate file url.
				if (!empty($layout_def['fields'][strtoupper($layout_def['image2_url_field']['id_field'])])
				and !empty($layout_def['fields'][strtoupper($layout_def['image2_url_field']['filename_field'])]) ){
					
					$key=$layout_def['fields'][strtoupper($layout_def['image2_url_field']['id_field'])];
					$file=$layout_def['fields'][strtoupper($layout_def['image2_url_field']['filename_field'])];
					//$filepath=UploadFile :: get_url(from_html($file), $key);	
					$filepath="download.php?id=".$key."&type=".$layout_def['module'];
				}
			}
			else {
				if (!empty($layout_def['fields'][strtoupper($layout_def['image2_url_field'])])) {
					$filepath="download.php?id=".$layout_def['fields']['ID']."&type=".$layout_def['module'];						
				 }
			}
			$icon_img_html = get_image($image_path . $layout_def['image2'] . '', 'border="0" alt="' . $layout_def['image2'] . '"');
			$ret.= (empty($filepath)) ? '' : '<a href="' . $filepath. '" class="listViewTdLinkS1">' . "$icon_img_html</a>";	
		}
		// now handle attachments for Emails
		else if(!empty($layout_def['module']) && $layout_def['module'] == 'Emails' && !empty($layout_def['fields']['ATTACHMENT_IMAGE'])) {			
			$ret.= $layout_def['fields']['ATTACHMENT_IMAGE'];	
		}
		return $ret;
	}
}
?>
