<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetSubPanelEmailLink
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

// $Id: SugarWidgetSubPanelEmailLink.php,v 1.6 2006/06/06 17:57:52 majed Exp $

require_once('include/generic/SugarWidgets/SugarWidgetField.php');

class SugarWidgetSubPanelEmailLink extends SugarWidgetField {

	function displayList(&$layout_def) {
		static $emailLinkable = array('Contacts'=>1, 'Leads'=>1, 'Prospects'=>1);
		global $current_user;
		global $beanList;
		global $focus;
		global $sugar_config;
		
		if(isset($layout_def['varname'])) {
			$key = strtoupper($layout_def['varname']);
		} else {
			$key = $this->_get_column_alias($layout_def);
			$key = strtoupper($key);
		}
	
		$value = $layout_def['fields'][$key];
		
		if(isset($emailLinkable[$this->layout_manager->defs['module_name']])) {

			if(isset($_REQUEST['action'])) $action = $_REQUEST['action'];
			else $action = '';

			if(isset($_REQUEST['module'])) $module = $_REQUEST['module'];
			else $module = '';

			if(isset($_REQUEST['record'])) $record = $_REQUEST['record'];
			else $record = '';

			if (!empty($focus->name)) {
				$name = $focus->name;
			} else {
				if( !empty($focus->first_name) && !empty($focus->last_name)) {
					$name = $focus->first_name . ' '. $focus->last_name;
				} else {
					if(!empty($focus->last_name)) {
						$name = $focus->last_name;
					} else {
						$name = '*';
					}
				}
			}
							
			$userPref = $current_user->getPreference('email_link_type');
			$defaultPref = $sugar_config['email_default_client'];
			if($userPref != '') {
				$client = $userPref;
			} else {
				$client = $defaultPref;
			}
			
			
			if($client == 'sugar') {				
				$link = '<a href="index.php?module=Emails&action=EditView&type=out'.
					'&load_id='.$layout_def['fields']['ID'].
					'&load_module='. $this->layout_manager->defs['module_name'] . 
					'&parent_type='.$this->layout_manager->defs['module_name'].
					'&parent_id='.$layout_def['fields']['ID'].
					'&parent_name='.urlencode($layout_def['fields']['FULL_NAME']).
					'&return_module='.$module.
					'&return_action='.$action.
					'&return_id='.$record.'" '. 
					'class="listViewTdLinkS1">';
			
			} else {
				$link = '<a href="mailto:' . $value .'" class="listViewTdLinkS1">';
			}

			return $link.$value.'</a>';
		}
	}
} // end class def
?>




















