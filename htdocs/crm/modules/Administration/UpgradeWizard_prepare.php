<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
/**
 * UpgradeWizard_prepare
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

// $Id: UpgradeWizard_prepare.php,v 1.23 2006/07/06 20:18:58 sadek Exp $

require_once('modules/Administration/UpgradeWizardCommon.php');

unset($_SESSION['rebuild_relationships']);
unset($_SESSION['rebuild_extensions']);
// process commands
if( !isset($_REQUEST['install_file']) || ($_REQUEST['install_file'] == "") ){
    die( "File to install not specified." );
}
if( !isset($_REQUEST['mode']) || ($_REQUEST['mode'] == "") ){
    die( "No mode specified." );
}

$hidden_fields = "";
$new_lang_name = "";
$new_lang_desc = "";

$mode           = $_REQUEST['mode'];
$hidden_fields .= "<input type=hidden name=\"mode\" value=\"$mode\"/><br>\n";

$install_file   = urldecode( $_REQUEST['install_file'] );
$install_type   = getInstallType( $install_file );

$version        = "";
$show_files     = true;
$unzip_dir      = mk_temp_dir( $base_tmp_upgrade_dir );
$zip_from_dir   = ".";
$zip_to_dir     = ".";
$zip_force_copy = array();

unzip( $install_file, $unzip_dir );

// assumption -- already validated manifest.php at time of upload
require_once( "$unzip_dir/manifest.php" );

if( isset( $manifest['copy_files']['from_dir'] ) && $manifest['copy_files']['from_dir'] != "" ){
    $zip_from_dir   = $manifest['copy_files']['from_dir'];
}
if( isset( $manifest['copy_files']['to_dir'] ) && $manifest['copy_files']['to_dir'] != "" ){
    $zip_to_dir     = $manifest['copy_files']['to_dir'];
}
if( isset( $manifest['copy_files']['force_copy'] ) && $manifest['copy_files']['force_copy'] != "" ){
    $zip_force_copy     = $manifest['copy_files']['force_copy'];
}
if( isset( $manifest['version'] ) ){
    $version    = $manifest['version'];
}


switch( $install_type ){
    case "full":
    case "patch":
        if( !is_writable( "config.php" ) ){
            die( $mod_strings['ERR_UW_CONFIG'] );
        }
        break;
    case "theme":
        break;
    case "langpack":
        // find name of language pack: find single file in include/language/xx_xx.lang.php
        $d = dir( "$unzip_dir/$zip_from_dir/include/language" );
        while( $f = $d->read() ){
            if( $f == "." || $f == ".." ){
                continue;
            }
            else if( preg_match("/(.*)\.lang\.php\$/", $f, $match) ){
                $new_lang_name = $match[1];
            }
        }
        if( $new_lang_name == "" ){
            die( $mod_strings['ERR_UW_NO_LANGPACK'].$install_file );
        }
        $hidden_fields .= "<input type=hidden name=\"new_lang_name\" value=\"$new_lang_name\"/><br>\n";

        $new_lang_desc = getLanguagePackName( "$unzip_dir/$zip_from_dir/include/language/$new_lang_name.lang.php" );
        if( $new_lang_desc == "" ){
            die( $mod_strings['ERR_UW_NO_LANG_DESC_1']."include/language/$new_lang_name.lang.php".$mod_strings['ERR_UW_NO_LANG_DESC_2']."$install_file." );
        }
        $hidden_fields .= "<input type=hidden name=\"new_lang_desc\" value=\"$new_lang_desc\"/><br>\n";

        if( !is_writable( "config.php" ) ){
            die( $mod_strings['ERR_UW_CONFIG'] );
        }
        break;
    case "module":
        $show_files = false;
        break;
    default:
        die( $mod_strings['ERR_UW_WRONG_TYPE'].$install_type );
}


$new_files      = findAllFilesRelative( "$unzip_dir/$zip_from_dir", array() );
$hidden_fields .= "<input type=hidden name=\"version\" value=\"$version\"/><br>\n";

// present list to user
?>
<form action="<?php print( $form_action . "_commit" ); ?>" name="files" method="post">
<?php
    switch( $mode ){
        case "Install":
            if( $install_type == "module" ){
                print( $mod_strings['LBL_UW_MODULE_READY'] );
            }
            else{
?>






<?php
            }
            break;
        case "Uninstall":
            if( $install_type == "module" ){
                print( $mod_strings['LBL_UW_MODULE_READY_UNISTALL'] );
            }
            else{
                print( $mod_strings['LBL_UW_FILES_REMOVED'] );
            }
            break;
    }

echo "<br><br>";
foreach ($script_files as $script_name => $script_filename) {
	if (is_file("{$unzip_dir}/{$script_filename}")) {
   		//echo "Found {$script_name} script: <i>{$script_filename}</i><br>";
   	}
}
?>

<p>

<?php
$count = 0;
    if( $show_files == true ){
        $count = 0;
        
        $new_studio_mod_files = array();
        $new_sugar_mod_files = array();

		  $cache_html_files = findAllFilesRelative( "cache/layout", array());
		  
        foreach($new_files as $the_file) {
          if(substr(strtolower($the_file), -5, 5) == '.html' && in_array($the_file, $cache_html_files))
            array_push($new_studio_mod_files, $the_file);
          else
            array_push($new_sugar_mod_files, $the_file);
        }

     echo '<script>
            function toggle_these(start, end, ca) {
              while(start < end) {
                elem = eval("document.forms.files.copy_" + start);
                if(!ca.checked) elem.checked = false;
                else elem.checked = true;
                start++;
              }
            }
			</script>';
        if(empty($new_studio_mod_files)) {
           echo $mod_strings['LBL_UW_PATCH_READY'];
        }
		  else {
		  	echo $mod_strings['LBL_UW_PATCH_READY2'];
			echo '<input type="checkbox" onclick="toggle_these(0, ' . count($new_studio_mod_files) . ', this)"> '.$mod_strings['LBL_UW_CHECK_ALL'];
			foreach($new_studio_mod_files as $the_file) {
                $new_file   = clean_path( "$zip_to_dir/$the_file" );
                print( "<li><input id=\"copy_$count\" name=\"copy_$count\" type=\"checkbox\" value=\"" . $the_file . "\"> " . $new_file . "</li>");
                $count++;
          }
        }

        global $theme;
        echo '</ul><br><br>';
        echo '<div style="text-align: right; cursor: hand; cursor: pointer; text-decoration: underline;" onclick=\'this.style.display="none"; toggleDisplay("more");\'id="all_text">
'.$mod_strings['LBL_UW_SHOW_DETAILS'].' <img src="themes/' . $theme . '/images/advanced_search.gif"></div><div id=\'more\' style=\'display: none\'>
              <div style="text-align: right; cursor: hand; cursor: pointer; text-decoration: underline;" onclick=\'document.getElementById("all_text").style.display=""; toggleDisplay("more");\'>'
              .$mod_strings['LBL_UW_HIDE_DETAILS'].' <img name="options" src="themes/' . $theme . '/images/basic_search.gif"></div><br>';
		  echo '<input type="checkbox" checked onclick="toggle_these(' . count($new_studio_mod_files) . ',' . count($new_files) . ', this)"> '.$mod_strings['LBL_UW_CHECK_ALL'];
		  echo '<ul>';
        foreach( $new_sugar_mod_files as $the_file ){
            $highlight_start    = "";
            $highlight_end      = "";
            $checked            = "";
            $disabled           = "";
            $unzip_file = "$unzip_dir/$zip_from_dir/$the_file";
            $new_file   = clean_path( "$zip_to_dir/$the_file" );
            $forced_copy    = false;

            if( $mode == "Install" ){
                $checked = "checked";
                foreach( $zip_force_copy as $pattern ){
                    if( preg_match("#" . $pattern . "#", $unzip_file) ){
                        $disabled = "disabled=\"true\"";
                        $forced_copy = true;
                    }
                }
                if( !$forced_copy && is_file( $new_file ) && (md5_file( $unzip_file ) == md5_file( $new_file )) ){
                    $disabled = "disabled=\"true\"";
                    //$checked = "";
                }







                if( $checked != "" && $disabled != "" ){    // need to put a hidden field
                    print( "<input name=\"copy_$count\" type=\"hidden\" value=\"" . $the_file . "\">\n" );
                }
                print( "<li><input id=\"copy_$count\" name=\"copy_$count\" type=\"checkbox\" value=\"" . $the_file . "\" $checked $disabled > " . $highlight_start . $new_file . $highlight_end );
                if( $checked == "" && $disabled != "" ){    // need to explain this file hasn't changed
                    print( " (no changes)" );
                }
                print( "<br>\n" );
            }
            else if( $mode == "Uninstall" && file_exists( $new_file ) ){
                if( md5_file( $unzip_file ) == md5_file( $new_file ) ){
                    $checked = "checked=\"true\"";
                }
                else{
                    $highlight_start    = "<font color=red>";
                    $highlight_end      = "</font>";
                }
                print( "<li><input name=\"copy_$count\" type=\"checkbox\" value=\"" . $the_file . "\" $checked $disabled > " . $highlight_start . $new_file . $highlight_end . "<br>\n" );
            }
            $count++;
        }
        print( "</ul>\n" );
    }
    echo '</div>';
    
?>
	<br>
    <input type=submit value="Commit" />
    <?php print( $hidden_fields ); ?>
    <input type=hidden name="copy_count" value="<?php print( $count );?>"/>
    <input type=hidden name="run" value="commit" />
    <input type=hidden name="install_file"  value="<?php print( urlencode( $install_file ) ); ?>" />
    <input type=hidden name="unzip_dir"     value="<?php echo $unzip_dir; ?>" />
    <input type=hidden name="zip_from_dir"  value="<?php echo $zip_from_dir; ?>" />
    <input type=hidden name="zip_to_dir"    value="<?php echo $zip_to_dir; ?>" />
</form>

<?php
    $GLOBALS['log']->info( "Upgrade Wizard patches" );
?>
