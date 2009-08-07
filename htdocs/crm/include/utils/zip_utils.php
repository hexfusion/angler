<?php
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

 // $Id: zip_utils.php,v 1.6 2006/08/22 18:56:15 awu Exp $
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('include/pclzip/pclzip.lib.php');

function unzip( $zip_archive, $zip_dir ){
    if( !is_dir( $zip_dir ) ){
        die( "Specified directory '$zip_dir' for zip file '$zip_archive' extraction does not exist." );
    }

    $archive = new PclZip( $zip_archive );

    if( $archive->extract( PCLZIP_OPT_PATH, $zip_dir ) == 0 ){
        die( "Error: " . $archive->errorInfo(true) );
    }
}

function unzip_file( $zip_archive, $archive_file, $to_dir ){
    if( !is_dir( $to_dir ) ){
        die( "Specified directory '$to_dir' for zip file '$zip_archive' extraction does not exist." );
    }

    $archive = new PclZip( "$zip_archive" );
    if( $archive->extract(  PCLZIP_OPT_BY_NAME, $archive_file,
                            PCLZIP_OPT_PATH,    $to_dir         ) == 0 ){
        die( "Error: " . $archive->errorInfo(true) );
    }
}

function zip_dir( $zip_dir, $zip_archive ){
    $archive    = new PclZip( "$zip_archive" );
    $v_list     = $archive->create( "$zip_dir" );
    if( $v_list == 0 ){
        die( "Error: " . $archive->errorInfo(true) );
    }
}
?>
