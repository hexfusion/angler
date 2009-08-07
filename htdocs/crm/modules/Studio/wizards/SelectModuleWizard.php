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

 // $Id: SelectModuleWizard.php,v 1.7 2006/08/26 02:46:03 majed Exp $


class SelectModuleWizard extends StudioWizard {
	var $wizard = 'SelectModuleWizard';
	function welcome(){
		return  $GLOBALS['mod_strings']['LBL_SMW_WELCOME'];
	}
	function back(){
	    ob_clean();
	    header('LOCATION:index.php?action=wizard&module=Studio&wizard=StudioWizard');
	       sugar_cleanup(true);
	}
	function options(){
		global $studioConfig;
		$options = array();
		$d = dir('modules');
		while($entry = $d->read()){
			if($entry != '.' && $entry != '..'){
				if(is_file('modules/'. $entry . '/metadata/studio.php')){
					$options[$entry] = $GLOBALS['app_list_strings']['moduleList'][$entry];	
				}
			}
		}
		
		 
		/*$options = array(
		'Accounts'=>'Accounts',
        'Bugs'=>'Bugs',
        'Calls'=>'Calls',
        'Campaigns'=>'Campaigns',
        'Cases'=>'Cases',
         'Contacts'=>'Contacts',
        'Contracts'=>'Contracts',
        'Documents'=>'Documents',
        'Leads'=>'Leads',
        'Meetings'=>'Meetings',
        'Notes'=>'Notes',
        'Opportunities'=>'Opportunities',
        'Products'=>'Products',
        'Project'=>'Projects',
        'ProjectTask'=>'Project Tasks',
        'Quotes'=>'Quotes');
        foreach($options as $key=>$option){
            if(isset($GLOBALS['app_list_strings']['moduleList'][$key])){
                $options[$key]= $GLOBALS['app_list_strings']['moduleList'][$key];
            }
        }
        */
        asort($options);
		return $options;
	}
	
	function process($option){
	    unset( $_SESSION['studio']['selectedFileId']);
		require_once('modules/Studio/wizards/SelectModuleAction.php');
		$_SESSION['studio']['module'] = $option;
		$newWiz = new SelectModuleAction();
		$newWiz->display();
	}
	
}

?>
