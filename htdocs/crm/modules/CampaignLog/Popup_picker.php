<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Popup Picker
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

// $Id: Popup_picker.php,v 1.6 2006/06/06 17:57:56 majed Exp $

global $theme;

require_once('modules/CampaignLog/CampaignLog.php');
require_once('themes/'.$theme.'/layout_utils.php');

require_once('XTemplate/xtpl.php');
require_once('include/ListView/ListView.php');

$image_path = 'themes/'.$theme.'/images/';

class Popup_Picker
{
	
	
	/*
	 * 
	 */
	function Popup_Picker()
	{
		;
	}
	
	/*
	 * 
	 */
	function _get_where_clause()
	{
		$where = '';
		if(isset($_REQUEST['query']))
		{
			$where_clauses = array();
			append_where_clause($where_clauses, "target_id", "campaign_log.target_id");
			//append_where_clause($where_clauses, "last_name", "prospects.last_name");
		
			$where = generate_where_statement($where_clauses);
			if (!empty($where)) 
				$where.=" AND ";
			$where .=" activity_type='targeted'";
			
		}
		
		return $where;
	}
	
	/**
	 *
	 */
	function process_page()
	{
		global $theme;
		global $mod_strings;
		global $app_strings;
		global $app_list_strings;
		global $currentModule;
		global $sugar_version, $sugar_config;
		
		$output_html = '';
		$where = '';
		
		$where = $this->_get_where_clause();
		
		$image_path = 'themes/'.$theme.'/images/';
		
		$first_name = empty($_REQUEST['first_name']) ? '' : $_REQUEST['first_name'];
		$last_name = empty($_REQUEST['last_name']) ? '' : $_REQUEST['last_name'];
		
		$request_data = empty($_REQUEST['request_data']) ? '' : $_REQUEST['request_data'];
		$hide_clear_button = empty($_REQUEST['hide_clear_button']) ? false : true;
		
		$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
		//START:FOR MULTI-SELECT
		$multi_select=false;
		if (!empty($_REQUEST['mode']) && strtoupper($_REQUEST['mode']) == 'MULTISELECT') {
			$multi_select=true;
			$button .= "<input type='button' name='button' class='button' onclick=\"send_back_selected('Prospects',document.MassUpdate,'mass[]','" .$app_strings['ERR_NOTHING_SELECTED']."');\" title='"
				.$app_strings['LBL_SELECT_BUTTON_TITLE']."' accesskey='"
				.$app_strings['LBL_SELECT_BUTTON_KEY']."' value='  "
				.$app_strings['LBL_SELECT_BUTTON_LABEL']."  ' />\n";
		}
		//END:FOR MULTI-SELECT
		if(!$hide_clear_button)
		{
			$button .= "<input type='button' name='button' class='button' onclick=\"send_back('','');\" title='"
				.$app_strings['LBL_CLEAR_BUTTON_TITLE']."' accesskey='"
				.$app_strings['LBL_CLEAR_BUTTON_KEY']."' value='  "
				.$app_strings['LBL_CLEAR_BUTTON_LABEL']."  ' />\n";
		}
		$button .= "<input type='submit' name='button' class='button' onclick=\"window.close();\" title='"
			.$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accesskey='"
			.$app_strings['LBL_CANCEL_BUTTON_KEY']."' value='  "
			.$app_strings['LBL_CANCEL_BUTTON_LABEL']."  ' />\n";
		$button .= "</form>\n";

		$form = new XTemplate('modules/CampaignLog/Popup_picker.html');
		$form->assign('MOD', $mod_strings);
		$form->assign('APP', $app_strings);
		$form->assign('THEME', $theme);
		$form->assign('MODULE_NAME', $currentModule);
		$form->assign('request_data', $request_data);


		ob_start();
		insert_popup_header($theme);
		$output_html .= ob_get_contents();
		ob_end_clean();
		
		//$output_html .= get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], '', false);
		
		$form->parse('main.SearchHeader');
		$output_html .= $form->text('main.SearchHeader');
		
		$output_html .= get_form_footer();
		
		// Reset the sections that are already in the page so that they do not print again later.
		$form->reset('main.SearchHeader');

		// create the listview
		$seed_bean = new CampaignLog();
		$ListView = new ListView();
		$ListView->show_export_button = false;
		$ListView->process_for_popups = true;
		$ListView->setXTemplate($form);
		$ListView->multi_select_popup=$multi_select;  //FOR MULTI-SELECT	
		if ($multi_select) $ListView->xTemplate->assign("TAG_TYPE","SPAN"); else  $ListView->xTemplate->assign("TAG_TYPE","A");//FOR MULTI-SELECT
		//$ListView->setHeaderTitle($mod_strings['LBL_LIST_FORM_TITLE']); //FOR MULTI-SELECT
		//$ListView->setHeaderText($button); //FOR MULTI-SELECT
		$ListView->setQuery($where, '', 'campaign_name', 'CAMPAIGNLOG');
		$ListView->setModStrings($mod_strings);

		ob_start();
		$output_html .= get_form_header($mod_strings['LBL_LIST_FORM_TITLE'], $button, false); //FOR MULTI-SELECT		
		$ListView->processListView($seed_bean, 'main', 'CAMPAIGNLOG');
		$output_html .= ob_get_contents();
		ob_end_clean();
				
		$output_html .= get_form_footer();
		$output_html .= insert_popup_footer();
		return $output_html;
	}
} // end of class Popup_Picker
?>
