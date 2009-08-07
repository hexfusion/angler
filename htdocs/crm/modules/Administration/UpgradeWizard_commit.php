<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * UpgradeWizard_commit
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

// $Id: UpgradeWizard_commit.php,v 1.29 2006/08/17 17:48:50 roger Exp $
require_once('modules/Administration/UpgradeWizardCommon.php');

function UWrebuild() {
	$log =& $GLOBALS['log'];
	$db =& $GLOBALS['db'];
	$log->info('Deleting Relationship Cache. Relationships will automatically refresh.');

	echo "
	<div id='rrresult'></div>
	<script>
		var xmlhttp=false;
		/*@cc_on @*/
		/*@if (@_jscript_version >= 5)
		// JScript gives us Conditional compilation, we can cope with old IE versions.
		// and security blocked creation of the objects.
		 try {
		  xmlhttp = new ActiveXObject(\"Msxml2.XMLHTTP\");
		 } catch (e) {
		  try {
		   xmlhttp = new ActiveXObject(\"Microsoft.XMLHTTP\");
		  } catch (E) {
		   xmlhttp = false;
		  }
		 }
		@end @*/
		if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			try {
				xmlhttp = new XMLHttpRequest();
			} catch (e) {
				xmlhttp = false;
			}
		}
		if (!xmlhttp && window.createRequest) {
			try {
				xmlhttp = window.createRequest();
			} catch (e) {
				xmlhttp = false;
			}
		}
		xmlhttp.onreadystatechange = function() {
		            if(xmlhttp.readyState == 4) {
		              document.getElementById('rrresult').innerHTML = xmlhttp.responseText;
		            }
		          }
		xmlhttp.open('GET', 'index.php?module=Administration&action=RebuildRelationship&to_pdf=true', true);
		xmlhttp.send(null);
		</script>";
		 			
	$log->info('Rebuilding everything.');
	require_once('ModuleInstall/ModuleInstaller.php');
	$mi = new ModuleInstaller();
	$mi->rebuild_all();
	$query = "DELETE FROM versions WHERE name='Rebuild Extensions'";
	$log->info($query);
	$db->query($query);
	
	// insert a new database row to show the rebuild extensions is done
	$id = create_guid();
	$gmdate = gmdate('Y-m-d H:i:s');
	$date_entered = db_convert("'$gmdate'", 'datetime');
	$query = 'INSERT INTO versions (id, deleted, date_entered, date_modified, modified_user_id, created_by, name, file_version, db_version) '
		. "VALUES ('$id', '0', $date_entered, $date_entered, '1', '1', 'Rebuild Extensions', '4.0.0', '4.0.0')"; 
	$log->info($query);
	$db->query($query);
}

unset($_SESSION['rebuild_relationships']);
unset($_SESSION['rebuild_extensions']);

$log =& $GLOBALS['log'];
$db =& $GLOBALS['db'];

// process commands
if( !isset($_REQUEST['mode']) || ($_REQUEST['mode'] == "") ){
    die($mod_strings['ERR_UW_NO_MODE']);
}
$mode = $_REQUEST['mode'];

if( !isset($_REQUEST['version']) ){
    die($mod_strings['ERR_UW_NO_MODE']);
}
$version = $_REQUEST['version'];

if( !isset($_REQUEST['copy_count']) || ($_REQUEST['copy_count'] == "") ){
    die($mod_strings['ERR_UW_NO_FILES']);
}

if( !isset($_REQUEST['unzip_dir']) || ($_REQUEST['unzip_dir'] == "") ){
    die($mod_strings['ERR_UW_NO_TEMP_DIR']);
}
$unzip_dir      = $_REQUEST['unzip_dir'];

if( !isset($_REQUEST['install_file']) || ($_REQUEST['install_file'] == "") ){
    die($mod_strings['ERR_UW_NO_INSTALL_FILE']);
}
$install_file   = urldecode( $_REQUEST['install_file'] );
$install_type   = getInstallType( $install_file );

if( $install_type != "module" ){
    if( !isset($_REQUEST['zip_from_dir']) || ($_REQUEST['zip_from_dir'] == "") ){
        $zip_from_dir     = ".";
    }
    else{
        $zip_from_dir   = $_REQUEST['zip_from_dir'];
    }
    if( !isset($_REQUEST['zip_to_dir']) || ($_REQUEST['zip_to_dir'] == "") ){
        $zip_to_dir     = ".";
    }
    else{
        $zip_to_dir     = $_REQUEST['zip_to_dir'];
    }
}


$file_action    = "";
$uh_status      = "";

$rest_dir = clean_path( remove_file_extension($install_file)."-restore");

$files_to_handle  = array();

//
// execute the PRE scripts
//
if($install_type == 'patch' || $install_type == 'module')
{
	switch($mode)
 	{
 		case 'Install':
 			$file = "$unzip_dir/" . constant('SUGARCRM_PRE_INSTALL_FILE');
			if(is_file($file))
			{
				print("{$mod_strings['LBL_UW_INCLUDING']}: $file <br>\n");
				include($file);
				pre_install();
   		}
 			break;
 		case 'Uninstall':
 			$file = "$unzip_dir/" . constant('SUGARCRM_PRE_UNINSTALL_FILE');
			if(is_file($file))
			{
				print("{$mod_strings['LBL_UW_INCLUDING']}: $file <br>\n");
				include($file);
				pre_uninstall();
   		}
 			break;
 		default:
 			break;
 		}
}

//
// perform the action
//

for( $iii = 0; $iii < $_REQUEST['copy_count']; $iii++ ){
    if( isset($_REQUEST["copy_" . $iii]) && ($_REQUEST["copy_" . $iii] != "") ){
        $file_to_copy = $_REQUEST["copy_" . $iii];
        $src_file   = clean_path( "$unzip_dir/$zip_from_dir/$file_to_copy" );

        $sugar_home_dir = getCwd();
        $dest_file  = clean_path( "$sugar_home_dir/$zip_to_dir/$file_to_copy" );
        if($zip_to_dir != '.')
        	$rest_file  = clean_path("$rest_dir/$zip_to_dir/$file_to_copy");
        else
        	$rest_file  = clean_path("$rest_dir/$file_to_copy");

        switch( $mode ){
            case "Install":
                mkdir_recursive( dirname( $dest_file ) );

                if($install_type=="patch" && is_file($dest_file))
                {
	                if(!is_dir(dirname( $rest_file )))
                		mkdir_recursive( dirname( $rest_file ) );

	                copy( $dest_file, $rest_file);
	                touch( $rest_file, filemtime($dest_file) );
                }

                if( !copy( $src_file, $dest_file ) ){
                    die( $mod_strings['ERR_UW_COPY_FAILED'].$src_file.$mod_strings['LBL_TO'].$dest_file);
                }
                $uh_status = "installed";
                break;
            case "Uninstall":
                if($install_type=="patch" && is_file($rest_file))
                {
	                copy( $rest_file, $dest_file);
	                touch( $dest_file, filemtime($rest_file) );
                }
                elseif(!unlink($dest_file))
                {
                    die($mod_strings['ERR_UW_REMOVE_FAILED'].$dest_file);
                }
                $uh_status = "uninstalled";
                break;
            default:
                die("{$mod_strings['LBL_UW_OP_MODE']} '$mode' {$mod_strings['ERR_UW_NOT_RECOGNIZED']}." );
        }
        $files_to_handle[] = clean_path( "$zip_to_dir/$file_to_copy" );
    }
}

switch( $install_type ){
    case "langpack":
        if( !isset($_REQUEST['new_lang_name']) || ($_REQUEST['new_lang_name'] == "") ){
            die($mod_strings['ERR_UW_NO_LANG']);
        }
        if( !isset($_REQUEST['new_lang_desc']) || ($_REQUEST['new_lang_desc'] == "") ){
            die($mod_strings['ERR_UW_NO_LANG_DESC']);
        }

        if( $mode == "Install" ){
            $sugar_config['languages'] = $sugar_config['languages'] + array( $_REQUEST['new_lang_name'] => $_REQUEST['new_lang_desc'] );
        }
        else if( $mode == "Uninstall" ){
            $new_langs = array();
            $old_langs = $sugar_config['languages'];
            foreach( $old_langs as $key => $value ){
                if( $key != $_REQUEST['new_lang_name'] ){
                    $new_langs += array( $key => $value );
                }
            }
            $sugar_config['languages'] = $new_langs;
        }

        ksort( $sugar_config );

        if( !write_array_to_file( "sugar_config", $sugar_config, "config.php" ) ){
            die($mod_strings['ERR_UW_CONFIG_FAILED']);
        }
        break;
    case "module":
        require_once( "ModuleInstall/ModuleInstaller.php" );
        $mi = new ModuleInstaller();
        switch( $mode ){
            case "Install":
                $mi->install( "$unzip_dir" );
                break;
            case "Uninstall":
                $mi->uninstall( "$unzip_dir" );
                break;
            default:
                break;
        }
        
			$file = "$unzip_dir/" . constant('SUGARCRM_POST_INSTALL_FILE');
			if(is_file($file))
			{
				print("{$mod_strings['LBL_UW_INCLUDING']}: $file <br>\n");
				include($file);
				post_install();
			}
        
        break;
    case "full":
        // purposely flow into "case: patch"
    case "patch":
 		switch($mode)
 		{
 			case 'Install':
 				$file = "$unzip_dir/" . constant('SUGARCRM_POST_INSTALL_FILE');
				if(is_file($file))
				{
					print("{$mod_strings['LBL_UW_INCLUDING']}: $file <br>\n");
					include($file);
					post_install();
				}
	
				UWrebuild();
 				break;
 			case 'Uninstall':
 				$file = "$unzip_dir/" . constant('SUGARCRM_POST_UNINSTALL_FILE');
 				if(is_file($file)) {
					print("{$mod_strings['LBL_UW_INCLUDING']}: $file <br>\n");
					include($file);
					post_uninstall();
				}
				
				if(is_dir($rest_dir))
				{
					rmdir_recursive($rest_dir);
				}
				
				UWrebuild();
 				break;
 			default:
 				break;
 		}
 		
		require( "sugar_version.php" );
		$sugar_config['sugar_version'] = $sugar_version;
		ksort( $sugar_config );
		
		if( !write_array_to_file( "sugar_config", $sugar_config, "config.php" ) )
		{
			die($mod_strings['ERR_UW_UPDATE_CONFIG']);
		}
        break;
    default:
        break;
}

switch( $mode ){
    case "Install":
        $file_action = "copied";
        // if error was encountered, script should have died before now
        $new_upgrade = new UpgradeHistory();
        $new_upgrade->filename      = $install_file;
        $new_upgrade->md5sum        = md5_file( $install_file );
        $new_upgrade->type          = $install_type;
        $new_upgrade->version       = $version;
        $new_upgrade->status        = "installed";
        $new_upgrade->save();
    break;
    case "Uninstall":
        $file_action = "removed";
        $uh = new UpgradeHistory();
        $the_md5 = md5_file( $install_file );
        $md5_matches = $uh->findByMd5( $the_md5 );
        if( sizeof( $md5_matches ) == 0 ){
            die( "{$mod_strings['ERR_UW_NO_UPDATE_RECORD']} $install_file." );
        }
        foreach( $md5_matches as $md5_match ){
            $md5_match->delete();
        }
        break;
}

// present list to user
?>
<form action="<?php print( $form_action ); ?>" method="post">

<p>
<?php
    if( $install_type == "module" ){
        print( $mod_strings['LBL_UW_UPLOAD_MODULE'] ." ". $mode . " ". $mod_strings['LBL_UW_SUCCESSFUL']."<br>\n" );
        print( "<input type=submit value=\"{$mod_strings['LBL_UW_BTN_BACK_TO_MOD_LOADER']}\" />\n" );
    }
    else{
        if( sizeof( $files_to_handle ) > 0 ){
            echo '<div style="text-align: right; cursor: hand; cursor: pointer; text-decoration: underline;" onclick=\'this.style.display="none"; toggleDisplay("more");\' id="all_text">Show Details <img src="themes/' . $theme . '/images/advanced_search.gif"></div><div id=\'more\' style=\'display: none\'>
           	     <div style="text-align: right; cursor: hand; cursor: pointer; text-decoration: underline;" onclick=\'document.getElementById("all_text").style.display=""; toggleDisplay("more");\'>Hide Details <img name="options" src="themes/' . $theme . '/images/basic_search.gif"></div><br>';
            print( "{$mod_strings['LBL_UW_FOLLOWING_FILES']} $file_action:<br>\n" );
            print( "<ul id=\"subMenu\">\n" );
            foreach( $files_to_handle as $file_to_copy ){
                print( "<li>$file_to_copy<br>\n" );
            }
            print( "</ul>\n" );
            echo '</div>';
        }
        else{
            print( "{$mod_strings['LBL_UW_NO_FILES_SELECTED']} $file_action.<br>\n" );
        }

        print($mod_strings['LBL_UW_UPGRADE_SUCCESSFUL']);
        print( "<input type=submit value=\"{$mod_strings['LBL_UW_BTN_BACK_TO_UW']}\" />\n" );
    }
?>
</form>

<?php
    $GLOBALS['log']->info( "Upgrade Wizard patches" );
?>
