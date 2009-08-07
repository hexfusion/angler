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
* $Id: DBManagerFactory.php,v 1.24 2006/08/01 03:30:40 ajay Exp $
* Description: This file generates the appropriate manager for the database
* 
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/

global $sugar_config;

class DBManagerFactory
{
	/** This function returns the correct instance of the manager
	*   depending on the database type
	*/
	function &getInstance($instanceName=''){
		global $sugar_config;
			$temp_var =& DBManager::getInstance($instanceName);
			return $temp_var;
}

    /** This function returns the correct instance of the manager
    *   depending on the database type
    */
    function getHelperInstance(){
        global $sugar_config;

        if( $sugar_config['dbconfig']['db_type'] == "oci8" || $sugar_config['dbconfig']['setup_db_type'] == 'oci8'){



        } 
        elseif( $sugar_config['dbconfig']['db_type'] == "mssql" ) 
		{				
            $my_db_manager = 'MssqlHelper';
        }
        else {
            $my_db_manager = 'MysqlHelper';
        }
        DBManagerFactory::load_db_manager_class($my_db_manager);
        return new $my_db_manager();
    }
    
	function load_db_manager_class($class_name) {
		global $sugar_config;
		
		
		if( $sugar_config['dbconfig']['db_type'] == 'mysql' and !class_exists($class_name))
		{
		    require_once('include/database/MysqlManager.php');
			require_once('include/database/MysqlHelper.php');
		}
		if($sugar_config['dbconfig']['db_type'] == 'mssql' and !class_exists($class_name))
		{
		    require_once('include/database/MssqlManager.php');  
			require_once('include/database/MssqlHelper.php');
		}







	}
}

?>
