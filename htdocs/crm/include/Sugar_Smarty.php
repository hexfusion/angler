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

require_once('include/Smarty/Smarty.class.php');
require_once('include/dir_inc.php');
if(!defined('SUGAR_SMARTY_DIR'))
{
	define('SUGAR_SMARTY_DIR', 'cache/smarty/');
}

class Sugar_Smarty extends Smarty
{
	
	function Sugar_Smarty()
	{
		if(!file_exists(SUGAR_SMARTY_DIR))mkdir_recursive(SUGAR_SMARTY_DIR, true);
		if(!file_exists(SUGAR_SMARTY_DIR . 'templates_c'))mkdir_recursive(SUGAR_SMARTY_DIR . 'templates_c', true);
		if(!file_exists(SUGAR_SMARTY_DIR . 'configs'))mkdir_recursive(SUGAR_SMARTY_DIR . 'configs', true);
		if(!file_exists(SUGAR_SMARTY_DIR . 'cache'))mkdir_recursive(SUGAR_SMARTY_DIR . 'cache', true);
		
		$this->template_dir = '.';
		$this->compile_dir = SUGAR_SMARTY_DIR . 'templates_c';
		$this->config_dir = SUGAR_SMARTY_DIR . 'configs';
		$this->cache_dir = SUGAR_SMARTY_DIR . 'cache';
		$this->request_use_auto_globals = true; // to disable Smarty from using long arrays
		



	}
	
}

?>
