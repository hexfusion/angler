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

 // $Id: Configurator.php,v 1.10 2006/08/22 19:28:18 awu Exp $

require_once ('include/utils/file_utils.php');
class Configurator {
	var $config = '';
	var $override = '';
	var $allow_undefined = array ('stack_trace_errors', 'export_delimiter', 'use_real_names');
	var $errors = array ('main' => '');
	function Configurator() {
		$this->loadConfig();
	}

	function loadConfig() {

		global $sugar_config;
		$this->config = $sugar_config;
	}

	function populateFromPost() {
		foreach ($_POST as $key => $value) {
			if (isset ($this->config[$key]) || in_array($key, $this->allow_undefined)) {
				$this->config[$key] = $_POST[$key];
			}
		}
	}


	function handleOverride() {
		global $sugar_config, $sugar_version;

		$this->readOverride();
		foreach ($this->config as $key => $value) {

			if ((in_array($key, $this->allow_undefined) || isset ($sugar_config[$key]) && strcmp("{$sugar_config[$key]}", "{$value}") != 0)) {
				if (strcmp("$value", 'true') == 0) {
					$value = true;
					$this->config[$key] = $value;
				}
				if (strcmp("$value", 'false') == 0) {

					$value = false;
					$this->config[$key] = false;
				}
				$this->replaceOverride('sugar_config', $key, $value);
			}
		}
		sugar_cache_put('sugar_config', $this->config);
		$GLOBALS['sugar_config'] = $this->config;
		$this->saveOverride();
	}

	function saveConfig() {
		$this->saveImages();
		$this->populateFromPost();
		$this->handleOverride();
	}

	function readOverride() {
		$this->override = '';
		if (file_exists('config_override.php')) {
			$this->override = file_get_contents('config_override.php');
		} else {
			$this->override = "<?php\n\n?>";
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

 // $Id: Configurator.php,v 1.10 2006/08/22 19:28:18 awu Exp $


		}
	}
	function saveOverride() {
		$fp = fopen('config_override.php', 'w');
		fwrite($fp, $this->override);
		
		fclose($fp);
	}

	function overrideClearDuplicates($array_name, $key) {

		if (!empty ($this->override)) {
			$pattern = '/.*CONFIGURATOR[^\$]*\$'.$array_name.'\[\''.$key.'\'\][\ ]*=[\ ]*[^;]*;\n/';

			$this->override = preg_replace($pattern, '', $this->override);
		} else {
			$this->override = "<?php\n\n?>";
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

 // $Id: Configurator.php,v 1.10 2006/08/22 19:28:18 awu Exp $

		}

	}

	function replaceOverride($array_name, $key, $value) {
		$GLOBALS[$array_name][$key] = $value;
		$this->overrideClearDuplicates($array_name, $key);
		$new_entry = '/***CONFIGURATOR***/'.override_value_to_string($array_name, $key, $value);
		$this->override = str_replace('?>', "$new_entry\n?>", $this->override);
	}

	function restoreConfig() {
		$this->readOverride();
		$this->overrideClearDuplicates('sugar_config', '[a-zA-Z0-9\_]+');
		$this->saveOverride();
		ob_clean();
		header('Location: index.php?action=EditView&module=Configurator');
	}

	function saveImages() {
		if (!empty ($_FILES['company_logo']['tmp_name'])) {
			$this->saveCompanyLogo($_FILES['company_logo']['tmp_name']);
		}





	}
	function saveCompanyLogo($path) {

			copy($path, 'include/images/company_logo.png');
			//copy to each themes dir
			foreach (unserialize($_SESSION['avail_themes']) as $dir => $name) {
				copy('include/images/company_logo.png', 'themes/'.$dir.'/images/company_logo.png');
			}

	}
	






	
}
?>
