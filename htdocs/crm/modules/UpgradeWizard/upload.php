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
 * $Id: upload.php,v 1.12 2006/08/12 00:58:54 chris Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/
logThis('At upload.php');
$stop = true; // flag to show "next"

$run = isset($_REQUEST['run']) ? $_REQUEST['run'] : '';
$out = '';
///////////////////////////////////////////////////////////////////////////////
////	UPLOAD FILE PROCESSING
switch($run) {
	case 'upload':
		logThis('running upload');
		if( empty( $_FILES['upgrade_zip']['tmp_name'] ) ) {
			logThis('ERROR: no file uploaded!');
			echo $mod_strings['ERR_UW_NO_FILE_UPLOADED'];
		} else {
			if(!move_uploaded_file($_FILES['upgrade_zip']['tmp_name'], getcwd().'/'.$sugar_config['upload_dir'].$_FILES['upgrade_zip']['name'])) {
				logThis('ERROR: could not move temporary file to final destination!');
				unlinkTempFiles();
				$out = "<b><span class='error'>{$mod_strings['ERR_UW_NOT_VALID_UPLOAD']}</span></b><br />";
			} else {
				$tempFile = getcwd().'/'.$sugar_config['upload_dir'].$_FILES['upgrade_zip']['name'];
				logThis('File uploaded to '.$tempFile);
			}
		
		    $manifest_file = extractManifest($tempFile);
		    
			if(is_file($manifest_file)) {
	    		require_once( $manifest_file );
				$error = validate_manifest( $manifest );
				if(!empty($error)) {
					$out = "<b><span class='error'>{$error}</span></b><br />";
					break;
				}
				$upgrade_zip_type = $manifest['type'];
	
				// exclude the bad permutations
				if($upgrade_zip_type != "patch") {
					logThis('ERROR: incorrect patch type found: '.$upgrade_zip_type);
					unlinkTempFiles();
					$out = "<b><span class='error'>{$mod_strings['ERR_UW_ONLY_PATCHES']}</span></b><br />";
					break;
				}
	
				$base_filename = urldecode( $_REQUEST['upgrade_zip_escaped'] );
				$base_filename = preg_replace( "#\\\\#", "/", $base_filename );
				$base_filename = basename( $base_filename );
		
				mkdir_recursive( "$base_upgrade_dir/$upgrade_zip_type" );
				$target_path = "$base_upgrade_dir/$upgrade_zip_type/$base_filename";
				$target_manifest = remove_file_extension( $target_path ) . "-manifest.php";
			
				if(isset($manifest['icon']) && $manifest['icon'] != "" ) {
					logThis('extracting icons.');
					 $icon_location = extractFile( $tempFile ,$manifest['icon'] );
					 $path_parts = pathinfo( $icon_location );
					 copy( $icon_location, remove_file_extension( $target_path ) . "-icon." . $path_parts['extension'] );
				}
		
				if(copy($tempFile , $target_path)){
					logThis('copying manifest.php to final destination.');
					copy($manifest_file, $target_manifest);
					$out .= "{$base_filename} {$mod_strings['LBL_UW_FILE_UPLOADED']}.<br>\n";
				} else {
					logThis('ERROR: cannot copy manifest.php to final destination.');
					$out .= "<b><span class='error'>{$mod_strings['ERR_UW_UPLOAD_ERR']}</span></b><br />";
					break;
				}
			} else {
				logThis('ERROR: no manifest.php file found!');
				unlinkTempFiles();
				$out = "<b><span class='error'>{$mod_strings['ERR_UW_NO_MANIFEST']}</span></b><br />";
				break;
			}
			$_SESSION['install_file'] = clean_path($tempFile);
			logThis('zip file moved to ['.$_SESSION['install_file'].']');
		}

	break; // end 'upload'
	
	case 'delete':
		logThis('running delete');
		
        if(!isset($_REQUEST['install_file']) || ($_REQUEST['install_file'] == "")) {
        	logThis('ERROR: trying to delete non-existent file: ['.$_REQUEST['install_file'].']');
            $error = $mod_strings['ERR_UW_NO_FILE_UPLOADED'];
        }
        
        // delete file in upgrades/patch
        $delete_me = urldecode( $_REQUEST['install_file'] );
        if(@unlink($delete_me)) {
        	logThis('unlinking: '.$delete_me);
            $out = basename($delete_me).$mod_strings['LBL_UW_FILE_DELETED'];
        } else {
        	logThis('ERROR: could not delete ['.$delete_me.']');
			$error = $mod_strings['ERR_UW_FILE_NOT_DELETED'].$delete_me;
        }
        
        // delete file in cache/upload
        $fileS = explode('/', $delete_me);
        $c = count($fileS);
        $fileName = (isset($fileS[$c-1]) && !empty($fileS[$c-1])) ? $fileS[$c-1] : $fileS[$c-2];
        $deleteUpload = getcwd().'/'.$sugar_config['upload_dir'].$fileName;
        logThis('Trying to delete '.$deleteUpload);
        if(!@unlink($deleteUpload)) {
        	logThis('ERROR: could not delete: ['.$deleteUpload.']');
        	$error = $mod_strings['ERR_UW_FILE_NOT_DELETED'].$sugar_config['upload_dir'].$fileName;
        }
        
        if(!empty($error)) {
			$out = "<b><span class='error'>{$error}</span></b><br />";
        }
        
        unlinkTempFiles();
        unlinkUploadFiles();
	break;
}
////	END UPLOAD FILE PROCESSING FORM
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	READY TO INSTALL UPGRADES
$validReturn = getValidPatchName();
$ready = $validReturn['ready'];
$disabled = $validReturn['disabled'];
////	END READY TO INSTALL UPGRADES
///////////////////////////////////////////////////////////////////////////////

if(isset($_SESSION['install_file']) && !empty($_SESSION['install_file']) && is_file($_SESSION['install_file'])) {
	$stop = false;
} else {
	$stop = true;
}
$frozen = $out;

///////////////////////////////////////////////////////////////////////////////
////	UPLOAD FORM
$uwMain =<<<eoq
<form name="the_form" id='the_form' enctype="multipart/form-data" action="index.php" method="post">
	<input type="hidden" name="module" value="UpgradeWizard">
	<input type="hidden" name="action" value="index">
	<input type="hidden" name="step" value="{$_REQUEST['step']}">
	<input type="hidden" name="run" value="upload">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr><td>
	<table width="450" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				{$mod_strings['LBL_UPLOAD_UPGRADE']}
				<input type="file" name="upgrade_zip" size="40" />
			</td>
			<td>&nbsp;
				<input	type=button
						{$disabled}
						value="{$mod_strings['LBL_UW_TITLE_UPLOAD']}"
						onClick="document.the_form.upgrade_zip_escaped.value = escape( document.the_form.upgrade_zip.value );document.the_form.submit();" />
				<input type=hidden name="upgrade_zip_escaped" value="" />
			</td>
		</tr>
	</table>
</td></tr>
</table>
</form>

<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr><td>
		
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				{$mod_strings['LBL_UW_FILES_QUEUED']}<br>
				{$ready}
			</td>
		</tr>
	</table>
</td></tr>
</table>
eoq;
////	END UPLOAD FORM
///////////////////////////////////////////////////////////////////////////////

$showBack		= true;
$showCancel		= true;
$showRecheck	= true;
$showNext		= ($stop) ? false : true;

$stepBack		= $_REQUEST['step'] - 1;
$stepNext		= $_REQUEST['step'] + 1;
$stepCancel		= -1;
$stepRecheck	= $_REQUEST['step'];


$_SESSION['step'][$steps['files'][$_REQUEST['step']]] = ($stop) ? 'failed' : 'success';

?>
