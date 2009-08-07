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

// $Id: performSetup.php,v 1.73 2006/08/19 01:35:30 chris Exp $
// This file will load the configuration settings from session data,
// write to the config file, and execute any necessary database steps.

if( !isset( $install_script ) || !$install_script ){
	die($mod_strings['ERR_NO_DIRECT_SCRIPT']);
}
set_time_limit(3600);
// flush after each output so the user can see the progress in real-time
ob_implicit_flush();
require_once('include/utils.php');
require_once('data/Tracker.php');
require_once('include/database/DBManagerFactory.php');
require_once('include/modules.php');
require_once('include/utils/file_utils.php');
require_once('modules/Schedulers/Scheduler.php');
require_once('modules/TableDictionary.php');

$cache_dir							= 'cache/';
$line_entry_format					= "&nbsp&nbsp&nbsp&nbsp&nbsp<b>";
$line_exit_format					= "... &nbsp&nbsp</b>";
$rel_dictionary					= $dictionary; // sourced by modules/TableDictionary.php
$render_table_close				= "</td> </tr> </table>\n";
$render_table_open					= "<table cellspacing='0' cellpadding='0' border='0' bgcolor='#dddddd' align='center' style='padding:0px 5px 0px 5px;border-left:1px solid #000000;border-right:1px solid #000000'><tr><td colspan='2' width='588'>\n";
$setup_db_admin_password			= $_SESSION['setup_db_admin_password'];
$setup_db_admin_user_name			= $_SESSION['setup_db_admin_user_name'];
$setup_db_create_database			= $_SESSION['setup_db_create_database'];
$setup_db_create_sugarsales_user	= $_SESSION['setup_db_create_sugarsales_user'];
$setup_db_database_name			= $_SESSION['setup_db_database_name'];
$setup_db_drop_tables				= $_SESSION['setup_db_drop_tables'];
$setup_db_host_instance			= $_SESSION['setup_db_host_instance'];
$setup_db_host_name				= $_SESSION['setup_db_host_name'];
$setup_db_pop_demo_data			= $_SESSION['setup_db_pop_demo_data'];
$setup_db_sugarsales_password		= $_SESSION['setup_db_sugarsales_password'];
$setup_db_sugarsales_user			= $_SESSION['setup_db_sugarsales_user'];
$setup_db_use_mb_demo_data			= $_SESSION['setup_db_use_mb_demo_data'];
$setup_site_admin_password			= $_SESSION['setup_site_admin_password'];
$setup_site_guid					= (isset($_SESSION['setup_site_specify_guid']) && $_SESSION['setup_site_specify_guid'] != '') ? $_SESSION['setup_site_guid'] : '';
$setup_site_url					= $_SESSION['setup_site_url'];
$parsed_url							= parse_url($setup_site_url);
$setup_site_host_name				= $parsed_url['host'];
$setup_site_log_dir				= isset($_SESSION['setup_site_custom_log_dir']) ? $_SESSION['setup_site_log_dir'] : '';
$setup_site_log_file				= 'sugarcrm.log';  // may be an option later
$setup_site_session_path			= isset($_SESSION['setup_site_custom_session_path']) ? $_SESSION['setup_site_session_path'] : '';



$out =<<<EOQ
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Script-Type" content="text/javascript">
   <meta http-equiv="Content-Style-Type" content="text/css">
	<title>{$mod_strings['LBL_WIZARD_TITLE']} {$next_step}</title>
   <link REL="SHORTCUT ICON" HREF="include/images/sugar_icon.ico">
   <link rel="stylesheet" href="install/install.css" type="text/css" />
   <script type="text/javascript" src="install/installCommon.js"></script>
</head>
<body onload="javascript:document.getElementById('defaultFocus').focus();">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell" style="height=15px;margin-bottom:0px;border-bottom:0px">
<tr>
	<th width="400">{$mod_strings['LBL_STEP']} {$next_step}: {$mod_strings['LBL_PERFORM_TITLE']}</th>
	<th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target="_blank">
	<IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
</tr>
<tr>
   <td colspan="2" width="600">
EOQ;
echo $out;

$bottle = handleSugarConfig();
handleLog4Php();
handleHtaccess();

///////////////////////////////////////////////////////////////////////////////
////	START TABLE STUFF
echo "<br>";
echo "<b>{$mod_strings['LBL_PERFORM_TABLES']}</b><br>";
echo "<br>";

// create the SugarCRM database
if($setup_db_create_database) {
	handleDbCreateDatabase();
} else {
// ensure the charset and collation are utf8
	handleDbCharsetCollation();
}
// create the SugarCRM database user
if($setup_db_create_sugarsales_user)
	handleDbCreateSugarUser();

foreach( $beanFiles as $bean => $file ){
	require_once( $file );
}

// load up the config_override.php file.
// This is used to provide default user settings
if( is_file("config_override.php") ){
    require_once("config_override.php");
}

$db					= &DBManagerFactory::getInstance();
$startTime			= microtime();
$focus				= 0;
$processed_tables	= array(); // for keeping track of the tables we have worked on
$empty				= '';
$new_tables		= 1; // is there ever a scenario where we DON'T create the admin user?
$new_config			= 1;
$new_report		= 1;

// add non-module Beans to this array to keep the installer from erroring.
$nonStandardModules = array (
	'Tracker',
	'UserPreferences',
);

/**
 * loop through all the Beans and create their tables
 */
foreach( $beanFiles as $bean => $file ) {
    $focus = new $bean();
    $table_name = $focus->table_name;

    // check to see if we have already setup this table
    if(!in_array($table_name, $processed_tables)) {
		if(!in_array($bean, $nonStandardModules)) {
			require_once("modules/".$focus->module_dir."/vardefs.php"); // load up $dictionary
			if($dictionary[$focus->object_name]['table'] == 'does_not_exist') {
				continue; // support new vardef definitions
			}
		}
		
		// table has not been setup...we will do it now and remember that
		$processed_tables[] = $table_name;
	
		$focus->db->database = $db->database; // set db connection so we do not need to reconnect
	        
		echo $line_entry_format.strtolower($focus->table_name).$line_exit_format;
	
        if($setup_db_drop_tables) {
			echo $mod_strings['LBL_PERFORM_DROPPING'];
            drop_table_install($focus);
        }

        if(create_table_if_not_exist($focus)) {
			echo $mod_strings['LBL_PERFORM_CREATING'];
            if( $bean == "User" ){
                $new_tables = 1;
            }
			if($bean == "Administration")
                $new_config = 1;




        }

        //create audit table if audit is enabled and table does not exist.
        if ($focus->is_AuditEnabled()) {
			echo $mod_strings['LBL_PERFORM_AUDIT_TABLE'];

            $auditTableExists=$focus->db->tableExists($focus->get_audit_table_name());
			if($setup_db_drop_tables && $auditTableExists) {
                $focus->dbManager->dropTableName($focus->get_audit_table_name());
				$auditTableExists=false;
			} 
		if(!$auditTableExists) {
                $focus->create_audit_table();
            }
        }
	
		echo $mod_strings['LBL_PERFORM_REL_META'];
        SugarBean::createRelationshipMeta($focus->getObjectName(), $db, $table_name, $empty, $focus->module_dir);
		echo $mod_strings['LBL_PERFORM_DONE'];
	
		ob_implicit_flush();
		flush();			
	} // end if()





}
////	END TABLE STUFF


///////////////////////////////////////////////////////////////////////////////
////	START RELATIONSHIP CREATION
   echo "<br>";
	echo "<b>{$mod_strings['LBL_PERFORM_CREATE_RELATIONSHIPS']}</b><br>";
   echo "<br>";

	ksort($rel_dictionary);
    foreach( $rel_dictionary as $rel_name => $rel_data ){  
        print( $render_table_close );
        print( $render_table_open );
        $table = $rel_data['table'];

        if( $setup_db_drop_tables ){
            if( $db->tableExists($table) ){
                $db->dropTableName($table);
            }
        }

        if( !$db->tableExists($table) ){
            $db->createTableParams($table, $rel_data['fields'], $rel_data['indices']);
        }

        echo $line_entry_format.strtolower($rel_name).$line_exit_format;
        SugarBean::createRelationshipMeta($rel_name,$db,$table,$rel_dictionary,'');
		echo $mod_strings['LBL_PERFORM_DONE'];			
	
      
	  ob_implicit_flush();
	  flush();
    }

///////////////////////////////////////////////////////////////////////////////
////	START CREATE DEFAULTS
    echo "<br>";
	echo "<b>{$mod_strings['LBL_PERFORM_CREATE_DEFAULT']}</b><br>";
    echo "<br>";


    ob_implicit_flush();
    flush();

    if ($new_config) {
		echo $line_entry_format.$mod_strings['LBL_PERFORM_DEFAULT_SETTINGS'].$line_exit_format;
        insert_default_settings();
		echo $mod_strings['LBL_PERFORM_DONE'];
    }

    print( $render_table_close );
    print( $render_table_open );

	// Default currency
//	$currencyService->insertDefaults();












    if ($new_tables) {
		echo $line_entry_format.$mod_strings['LBL_PERFORM_DEFAULT_USERS'].$line_exit_format;
        create_default_users();
		echo $mod_strings['LBL_PERFORM_DONE'];
	} else {
		echo $line_entry_format.$mod_strings['LBL_PERFORM_ADMIN_PASSWORD'].$line_exit_format;
		$db->setUserName($setup_db_sugarsales_user);
		$db->setUserPassword($setup_db_sugarsales_password);
        set_admin_password($setup_site_admin_password);
		echo $mod_strings['LBL_PERFORM_DONE'];
    }

    print( $render_table_close );
    print( $render_table_open );









	// default OOB schedulers
	echo $line_entry_format.$mod_strings['LBL_PERFORM_DEFAULT_SCHEDULER'].$line_exit_format;
	$scheduler = new Scheduler();
	$scheduler->rebuildDefaultSchedulers();
	echo $mod_strings['LBL_PERFORM_DONE'];
	

///////////////////////////////////////////////////////////////////////////////
////	START DEMO DATA
	
    // populating the db with seed data
    if( $setup_db_pop_demo_data ){
        set_time_limit( 301 );

   	  echo "<br>";
		echo "<b>{$mod_strings['LBL_PERFORM_DEMO_DATA']}</b>";
        echo "<br><br>";

        print( $render_table_close );
        print( $render_table_open );

        $current_user = new User();
        $current_user->retrieve(1);
        include("install/populateSeedData.php");
    }

	$endTime = microtime();
	$deltaTime = microtime_diff($startTime, $endTime);
	













					
///////////////////////////////////////////////////////////////////////////
////	FINALIZE LANG PACK INSTALL
	if(isset($_SESSION['INSTALLED_LANG_PACKS']) && is_array($_SESSION['INSTALLED_LANG_PACKS']) && !empty($_SESSION['INSTALLED_LANG_PACKS'])) {
		updateUpgradeHistory();
	}

	///////////////////////////////////////////////////////////////////////////
	////	HANDLE SUGAR VERSIONS
	require_once('modules/Versions/InstallDefaultVersions.php');

///////////////////////////////////////////////////////////////////////////////
////	SETUP DONE
$memoryUsed = '';
    if(function_exists('memory_get_usage')) {
	$memoryUsed = $mod_strings['LBL_PERFORM_OUTRO_5'].memory_get_usage().$mod_strings['LBL_PERFORM_OUTRO_6'];
    }







$errTcpip = '';
    $fp = @fsockopen("www.sugarcrm.com", 80, $errno, $errstr, 3);
    if (!$fp) {
	$errTcpip = "<p>{$mod_strings['ERR_PERFORM_NO_TCPIP']}</p>";
    }
   if ($fp && (!isset( $_SESSION['oc_install']) ||  $_SESSION['oc_install'] == false)) {
      @fclose($fp);
	$fpResult =<<<FP
     <form action="install.php" method="post" name="form" id="form">
	 <input type="hidden" name="current_step" value="{$next_step}">
     <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
       <tr>
		 <td><input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="{$mod_strings['LBL_HELP']}" /></td>
         <td>
			<input class="button" type="button" name="goto" value="{$mod_strings['LBL_BACK']}" onclick="document.getElementById('form').submit();" />
            <input type="hidden" name="goto" value="Back" />
         </td>
		 <td><input class="button" type="submit" name="goto" value="{$mod_strings['LBL_NEXT']}" id="defaultFocus"/></td>
       </tr>
     </table>
     </form>
FP;
   } else {
		$fpResult =<<<FP
     <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
       <tr>
		 <td><input class="button" type="button" onclick="showHelp(4);" value="{$mod_strings['LBL_HELP']}" /></td>
         <td>
            <form action="install.php" method="post" name="form" id="form">
                <input type="hidden" name="current_step" value="4">
				<input class="button" type="button" name="goto" value="{$mod_strings['LBL_BACK']}" />
	            <input type="hidden" name="goto" value="{$mod_strings['LBL_BACK']}" />
            </form>
         </td>
         <td>
            <form action="index.php" method="post" name="formFinish" id="formFinish">
                <input type="hidden" name="default_user_name" value="admin" />
				<input class="button" type="submit" name="next" value="{$mod_strings['LBL_PERFORM_FINISH']}" id="defaultFocus"/>
            </form>
         </td>
       </tr>
     </table>
FP;
   }
    require_once('modules/Administration/updater_utils.php');
    if( isset($_SESSION['setup_site_sugarbeet_automatic_checks']) && $_SESSION['setup_site_sugarbeet_automatic_checks'] == true){
        $sugar_config['sugarbeet']      = $_SESSION['setup_site_sugarbeet_automatic_checks'];
		set_CheckUpdates_config_setting('automatic');
	}else{
		set_CheckUpdates_config_setting('manual');
	}   
if( count( $bottle ) > 0 ){
	foreach( $bottle as $bottle_message ){
		$bottleMsg .= "{$bottle_message}\n";
	}
} else {
	$bottleMsg = $mod_strings['LBL_PERFORM_SUCCESS'];
}


$out =<<<EOQ
<br><p><b>{$mod_strings['LBL_PERFORM_OUTRO_1']} {$setup_sugar_version} {$mod_strings['LBL_PERFORM_OUTRO_2']}</b></p>
<hr>
{$mod_strings['LBL_PERFORM_OUTRO_3']} {$deltaTime} {$mod_strings['LBL_PERFORM_OUTRO_4']}<br />
{$memoryUsed}
{$errTcpip}
<hr>
<p></p>
	</td>
</tr>
<tr>
<td align="right" colspan="2" style="border-bottom:1px solid #000000">
<hr>
<table cellspacing="0" cellpadding="0" border="0" class="stdTable">
<tr>
<td>
{$fpResult}
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
</body>
</html>
<!--
<bottle>{$bottleMsg}</bottle>
-->
EOQ;

echo $out;

?>
