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
include('include/modules.php');

global $beanFiles;
echo 'Searching for new audit enabled modules <BR>';
foreach ($beanFiles as $bean => $file)
{
	if(strlen($file) > 0 && file_exists($file)) {
		require_once($file);
	    $focus = new $bean();
		if ($focus->is_AuditEnabled()) {
			if (!$focus->db->tableExists($focus->get_audit_table_name())) {
				echo "creating table ".$focus->get_audit_table_name().' for '. $focus->object_name.'.<BR>';
				$focus->create_audit_table();
			} else {
				echo "Audit table for ". $focus->object_name." already exists. skipping...<BR>";	
			}
		}
	}
}
echo 'done';
?>
