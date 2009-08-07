<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * UpgradeWizardCommon
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

// $Id: UpgradeWizardCommon.php,v 1.21 2006/07/10 20:43:25 chris Exp $

require_once('include/utils/db_utils.php');
require_once('include/utils/file_utils.php');
require_once('include/utils/zip_utils.php');
require_once('modules/Administration/Administration.php');
require_once('modules/Administration/UpgradeHistory.php');
require_once('include/dir_inc.php');

// increase the cuttoff time to 1 hour
ini_set("max_execution_time", "3600");

if( isset( $_REQUEST['view'] ) && ($_REQUEST['view'] != "") ){
    $view = $_REQUEST['view'];
    if( $view != "default" && $view != "module" ){
        die($mod_strings['ERR_UW_INVALID_VIEW']);
    }
}
else{
    die($mod_strings['ERR_UW_NO_VIEW']);
}
$form_action = "index.php?module=Administration&view=" . $view . "&action=UpgradeWizard";


$base_upgrade_dir       = $sugar_config['upload_dir'] . "/upgrades";
$base_tmp_upgrade_dir   = "$base_upgrade_dir/temp";
$subdirs = array('full', 'langpack', 'module', 'patch', 'theme', 'temp');
// array of special scripts that are executed during (un)installation-- key is type of script, value is filename

if(!defined('SUGARCRM_PRE_INSTALL_FILE'))
{
	define('SUGARCRM_PRE_INSTALL_FILE', 'scripts/pre_install.php');
	define('SUGARCRM_POST_INSTALL_FILE', 'scripts/post_install.php');
	define('SUGARCRM_PRE_UNINSTALL_FILE', 'scripts/pre_uninstall.php');
	define('SUGARCRM_POST_UNINSTALL_FILE', 'scripts/post_uninstall.php');
}
$script_files = array(
	"pre-install" => constant('SUGARCRM_PRE_INSTALL_FILE'),
	"post-install" => constant('SUGARCRM_POST_INSTALL_FILE'),
	"pre-uninstall" => constant('SUGARCRM_PRE_UNINSTALL_FILE'),
	"post-uninstall" => constant('SUGARCRM_POST_UNINSTALL_FILE'),
);


function extractFile( $zip_file, $file_in_zip ){
    global $base_tmp_upgrade_dir;
    $my_zip_dir = mk_temp_dir( $base_tmp_upgrade_dir );
    unzip_file( $zip_file, $file_in_zip, $my_zip_dir );
    return( "$my_zip_dir/$file_in_zip" );
}

function extractManifest( $zip_file ){
    return( extractFile( $zip_file, "manifest.php" ) );
}

function getInstallType( $type_string ){
    // detect file type
    global $subdirs;

    foreach( $subdirs as $subdir ){
        if( preg_match( "#/$subdir/#", $type_string ) ){
            return( $subdir );
        }
    }
    // return empty if no match
    return( "" );
}

function getImageForType( $type ){
    global $image_path;
    $icon = "";
    switch( $type ){
        case "full":
            $icon = get_image( $image_path . "Upgrade", "" );
            break;
        case "langpack":
            $icon = get_image( $image_path . "LanguagePacks", "" );
            break;
        case "module":
            $icon = get_image( $image_path . "ModuleLoader", "" );
            break;
        case "patch":
            $icon = get_image( $image_path . "PatchUpgrades", "" );
            break;
        case "theme":
            $icon = get_image( $image_path . "Themes", "" );
            break;
        default:
            break;
    }
    return( $icon );
}

function getLanguagePackName( $the_file ){
    require_once( "$the_file" );
    if( isset( $app_list_strings["language_pack_name"] ) ){
        return( $app_list_strings["language_pack_name"] );
    }
    return( "" );
}

function getUITextForType( $type ){
    if( $type == "full" ){
        return( "Full Upgrade" );
    }
    if( $type == "langpack" ){
        return( "Language Pack" );
    }
    if( $type == "module" ){
        return( "Module" );
    }
    if( $type == "patch" ){
        return( "Patch" );
    }
    if( $type == "theme" ){
        return( "Theme" );
    }
}

function run_upgrade_wizard_sql( $script ){
    global $unzip_dir;
    global $sugar_config;

    $db_type = $sugar_config['dbconfig']['db_type'];
    $script = str_replace( "%db_type%", $db_type, $script );
    if( !run_sql_file( "$unzip_dir/$script" ) ){
        die( "{$mod_strings['ERR_UW_RUN_SQL']} $unzip_dir/$script" );
    }
}

function validate_manifest( $manifest ){
    // takes a manifest.php manifest array and validates contents
    global $subdirs;
    global $sugar_version;
    global $sugar_flavor;
	global $mod_strings;

    if( !isset($manifest['type']) ){
        die($mod_strings['ERROR_MANIFEST_TYPE']);
    }
    $type = $manifest['type'];
    if( getInstallType( "/$type/" ) == "" ){
        die($mod_strings['ERROR_PACKAGE_TYPE']. ": '" . $type . "'." );
    }

    if( isset($manifest['acceptable_sugar_versions']) ){
        $version_ok = false;
        $matches_empty = true;
        if( isset($manifest['acceptable_sugar_versions']['exact_matches']) ){
            $matches_empty = false;
            foreach( $manifest['acceptable_sugar_versions']['exact_matches'] as $match ){
                if( $match == $sugar_version ){
                    $version_ok = true;
                }
            }
        }
        if( !$version_ok && isset($manifest['acceptable_sugar_versions']['regex_matches']) ){
            $matches_empty = false;
            foreach( $manifest['acceptable_sugar_versions']['regex_matches'] as $match ){
                if( preg_match( "/$match/", $sugar_version ) ){
                    $version_ok = true;
                }
            }
        }

        if( !$matches_empty && !$version_ok ){
            die( $mod_strings['ERROR_VERSION_INCOMPATIBLE'] . $sugar_version );
        }
    }

    if( isset($manifest['acceptable_sugar_flavors']) && sizeof($manifest['acceptable_sugar_flavors']) > 0 ){
        $flavor_ok = false;
        foreach( $manifest['acceptable_sugar_flavors'] as $match ){
            if( $match == $sugar_flavor ){
                $flavor_ok = true;
            }
        }
        if( !$flavor_ok ){
            die( $mod_strings['ERROR_FLAVOR_INCOMPATIBLE'] . $sugar_version );
        }
    }
}
?>
