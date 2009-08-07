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

 // $Id: wizard.php,v 1.3 2006/08/22 20:49:00 awu Exp $

require_once('modules/Studio/config.php');
require_once('modules/Studio/wizards/StudioWizard.php');
require_once('include/Sugar_Smarty.php');
$wizard = !empty($_REQUEST['wizard'])? $_REQUEST['wizard']: 'StudioWizard';

if(file_exists('modules/Studio/wizards/'. $wizard . '.php')){
	require_once('modules/Studio/wizards/'. $wizard . '.php');
	$thewiz = new $wizard();
}else{
	unset($_SESSION['studio']['lastWizard']);
	$thewiz = new StudioWizard();
}

if(!empty($_REQUEST['back'])){
    $thewiz->back();
}
if(!empty($_REQUEST['option'])){
	$thewiz->process($_REQUEST['option']);
}else{
	$thewiz->display();
	
}


