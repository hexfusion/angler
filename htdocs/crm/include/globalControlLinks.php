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
 * $Id: globalControlLinks.php,v 1.4 2006/06/06 17:57:47 majed Exp $
 * Description:  controls which link show up in the upper right hand corner of the app
 ********************************************************************************/
 if(!isset($global_control_links)){
 	$global_control_links = array();
	$sub_menu = array();
 }
if(isset( $sugar_config['disc_client']) && $sugar_config['disc_client']){
	require_once('modules/Sync/headermenu.php');
}
$global_control_links['myaccount'] = array(
'linkinfo' => array($app_strings['LBL_MY_ACCOUNT'] => 'index.php?module=Users&action=DetailView&record='.$current_user->id.''),
'submenu' => ''
 );
$global_control_links['employees'] = array(
'linkinfo' => array($app_strings['LBL_EMPLOYEES']=> 'index.php?module=Employees&action=ListView'),
'submenu' => ''
);
if (is_admin($current_user)) $global_control_links['admin'] = array(
'linkinfo' => array($app_strings['LBL_ADMIN'] => 'index.php?module=Administration&action=index'),
'submenu' => ''
);
$global_control_links['users'] = array(
'linkinfo' => array($app_strings['LBL_LOGOUT'] => 'index.php?module=Users&action=Logout'),
'submenu' => ''
);
$global_control_links['about'] = array('linkinfo' => array($app_strings['LNK_ABOUT'] => 'index.php?module=Home&action=About'),
'submenu' => ''
);

?>
