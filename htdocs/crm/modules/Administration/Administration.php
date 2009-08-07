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
 * $Id: Administration.php,v 1.43 2006/08/03 19:56:03 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



require_once('data/SugarBean.php');

class Administration extends SugarBean {
	var $settings;
	var $table_name = "config";
	var $object_name = "Administration";
	var $new_schema = true;
	var $module_dir = 'Administration';
	var $config_categories = Array(
							'mail',
							 'notify', 
							'system',
							 'portal',
							 'proxy',
							 'massemailer',
							 'ldap',



							 );

	var $checkbox_fields = Array("notify_send_by_default", "mail_smtpauth_req", "notify_on", 'portal_on', 'skypeout_on', 'system_mailmerge_on', 'proxy_auth', 'proxy_on', 'system_ldap_enabled');

	function Administration() {
		parent::SugarBean();
		
		 $this->setupCustomFields('Administration');



	}

	function retrieveSettings($category = FALSE, $clean=false) {
	    // declare a chache for all settings
	    static $settings_cache = array();
	    if($clean){
	    	$settings_cache = array();	
	    }
	    // Check for a cache hit
	    if(!empty($settings_cache))
	    {
	        $this->settings = $settings_cache;
	        return $this;
	    }
	    
		$query = "SELECT category, name, value FROM $this->table_name";

		$result = $this->db->query($query, true, "Unable to retrieve system settings");
		if (empty($result)) {
			return NULL;
		}
		
		while ($row = $this->db->fetchByAssoc($result, -1, true)) {
			$this->settings[$row['category']."_".$row['name']] = $row['value'];
		}
		
		// At this point, we have built a new array that should be cached.
        $settings_cache = $this->settings;
		
		return $this;
	}

	function saveConfig() {
		foreach ($_POST as $key => $val) {
			$prefix = $this->get_config_prefix($key);
			if (in_array($prefix[0], $this->config_categories)) {
				$this->saveSetting($prefix[0], $prefix[1], $val); 
			}
		}
		$this->retrieveSettings(false, true);
	}
	
    function saveSetting($category, $key, $value) {
        $result = $this->db->query("SELECT count(*) AS the_count FROM config WHERE category = '{$category}' AND name = '{$key}'");
        $row = $this->db->fetchByAssoc( $result, -1, true );
        $row_count = $row['the_count'];

        if( $row_count == 0){
            $result = $this->db->query("INSERT INTO config (value, category, name) VALUES ('$value','$category', '$key')");
        }
        else{
            $result = $this->db->query("UPDATE config SET value = '{$value}' WHERE category = '{$category}' AND name = '{$key}'");
        }
        
        return $this->db->getAffectedRowCount($result);
    }

	function get_config_prefix($str) {
		return Array(substr($str, 0, strpos($str, "_")), substr($str, strpos($str, "_")+1));
	}    
}
?>
