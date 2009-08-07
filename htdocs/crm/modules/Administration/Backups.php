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

require_once('include/dir_inc.php');
require_once('include/utils/zip_utils.php');

$form_action = "index.php?module=Administration&action=Backups";

$backup_dir = "";
$backup_zip = "";
$run        = "confirm";
$input_disabled = "";
global $mod_strings;

function processBackupForm(){
    global $backup_dir;
    global $backup_zip;
    global $input_disabled;
    global $run;
    global $mod_strings;
    
    $errors = array();

    // process "run" commands
    if( isset( $_REQUEST['run'] ) && ($_REQUEST['run'] != "") ){
        $run = $_REQUEST['run'];

        $backup_dir = $_REQUEST['backup_dir'];
        $backup_zip = $_REQUEST['backup_zip'];

        if( $run == "confirm" ){
            if( $backup_dir == "" ){
                $errors[] = $mod_strings['LBL_BACKUP_DIRECTORY_ERROR'];
            }
            if( $backup_zip == "" ){
                $errors[] = $mod_strings['LBL_BACKUP_FILENAME_ERROR'];
            }

            if( sizeof($errors) > 0 ){
                return( $errors );
            }

            if( !is_dir( $backup_dir ) ){
                if( !mkdir_recursive( $backup_dir ) ){
                    $errors[] = $mod_strings['LBL_BACKUP_DIRECTORY_EXISTS'];
                }
            }

            if( !is_writable( $backup_dir ) ){
                $errors[] = $mod_strings['LBL_BACKUP_DIRECTORY_NOT_WRITABLE'];
            }

            if( is_file( "$backup_dir/$backup_zip" ) ){
                $errors[] = $mod_strings['LBL_BACKUP_FILE_EXISTS'];
            }
            if( is_dir( "$backup_dir/$backup_zip" ) ){
                $errors[] = $mod_strings['LBL_BACKUP_FILE_AS_SUB'];
            }

            if( sizeof( $errors ) == 0 ){
                $run = "confirmed";
                $input_disabled = "readonly";
            }
        }
        else if( $run == "confirmed" ){
            ini_set( "memory_limit", "-1" );
            ini_set( "max_execution_time", "0" );
            zip_dir( ".", "$backup_dir/$backup_zip" );
            $run = "done";
        }
    }
    return( $errors );
}

$errors = processBackupForm();

if( sizeof($errors) > 0 ){
    foreach( $errors as $error ){
        print( "<font color=\"red\">$error</font><br>" );
    }
}

if( $run == "done" ){
    $size = filesize( "$backup_dir/$backup_zip" );
    print( $mod_strings['LBL_BACKUP_FILE_STORED'] . " $backup_dir/$backup_zip ($size bytes).<br>\n" );
    print( "<a href=\"index.php?module=Administration&action=index\">" . $mod_strings['LBL_BACKUP_BACK_HOME']. "</a>\n" );
}
else{
?>

    <?php 
    echo "\n<p>\n";
    echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$mod_strings['LBL_BACKUPS_TITLE'], true); 
    echo "\n</p>\n";
    echo $mod_strings['LBL_BACKUP_INSTRUCTIONS_1']; ?>
    <br>
    <?php echo $mod_strings['LBL_BACKUP_INSTRUCTIONS_2']; ?><br>
    <form action="<?php print( $form_action );?>" method="post">
    <table>
    <tr>
        <td><?php echo $mod_strings['LBL_BACKUP_DIRECTORY']; ?><br><i><?php echo $mod_strings['LBL_BACKUP_DIRECTORY_WRITABLE']; ?></i></td>
        <td><input type="input" name="backup_dir" <?php print( $input_disabled );?> value="<?php print( $backup_dir );?>"/></td>
    </tr>
    <tr>
        <td><?php echo $mod_strings['LBL_BACKUP_FILENAME']; ?></td>
        <td><input type="input" name="backup_zip" <?php print( $input_disabled );?> value="<?php print( $backup_zip );?>"/></td>
    </tr>
    </table>
    <input type=hidden name="run" value="<?php print( $run );?>" />

<?php
    switch( $run ){
        case "confirm":
?>
            <input type="submit" value="<?php echo $mod_strings['LBL_BACKUP_CONFIRM']; ?>" />
<?php
            break;
        case "confirmed":
?>
            <?php echo $mod_strings['LBL_BACKUP_CONFIRMED']; ?><br>
            <input type="submit" value="<?php echo $mod_strings['LBL_BACKUP_RUN_BACKUP']; ?>" />
<?php
            break;
    }
?>

    </form>

<?php
}   // end if/else of $run options
$GLOBALS['log']->info( "Backups" );
?>
