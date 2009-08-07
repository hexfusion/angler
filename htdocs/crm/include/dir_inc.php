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
// $Id: dir_inc.php,v 1.12 2006/07/18 07:07:36 chris Exp $

function copy_recursive( $source, $dest ){
    if( is_file( $source ) ){
        return( copy( $source, $dest ) );
    }
    if( !is_dir($dest) ){
        mkdir( $dest );
    }

    $status = true;

    $d = dir( $source );
    while( $f = $d->read() ){
        if( $f == "." || $f == ".." ){
            continue;
        }
        $status &= copy_recursive( "$source/$f", "$dest/$f" );
    }
    $d->close();
    return( $status );
}

function mkdir_recursive($path, $check_is_parent_dir = false) {
	if(is_dir($path)) {
		return(true);
	}
	if(is_file($path)) {
		print("ERROR: mkdir_recursive(): argument $path is already a file.\n");
		return false;
	}
	
	$path = clean_path($path);
	$path = str_replace(clean_path(getcwd()), '', $path);
	$thePath = '';
	$dirStructure = explode("/", $path);

	foreach($dirStructure as $dirPath) {
		$thePath .= '/'.$dirPath;
		$mkPath = getcwd().'/'.$thePath;

		if(!is_dir($mkPath )) {
			mkdir($mkPath);
		}
	}
}

function rmdir_recursive( $path ){
    if( is_file( $path ) ){
        return( unlink( $path ) );
    }
    if( !is_dir( $path ) ){
        print( "ERROR: rmdir_recursive(): argument $path is not a file or a dir.\n" );
        return( false );
    }

    $status = true;

    $d = dir( $path );
    while( $f = $d->read() ){
        if( $f == "." || $f == ".." ){
            continue;
        }
        $status &= rmdir_recursive( "$path/$f" );
    }
    $d->close();
    rmdir( $path );
    return( $status );
}

function findTextFiles( $the_dir, $the_array ){
    $d = dir( $the_dir );
    while( $f = $d->read() ){
        if( $f == "." || $f == ".." ){
            continue;
        }
        if( is_dir( "$the_dir/$f" ) ){
            // i think depth first is ok, given our cvs repo structure -Bob.
            $the_array = findTextFiles( "$the_dir/$f", $the_array );
        }
        else {
            switch( mime_content_type( "$the_dir/$f" ) ){
                // we take action on these cases
                case "text/html":
                case "text/plain":
                    array_push( $the_array, "$the_dir/$f" );
                    break;
                // we consciously skip these types
                case "application/pdf":
                case "application/x-zip":
                case "image/gif":
                case "image/jpeg":
                case "image/png":
                case "text/rtf":
                    break;
                default:
                    debug( "no type handler for $the_dir/$f with mime_content_type: " . mime_content_type( "$the_dir/$f" ) . "\n" );
            }
        }
    }
    return( $the_array );
}

function findAllFiles( $the_dir, $the_array, $include_dirs=false ){
    $d = dir($the_dir);
    while( $f = $d->read() ) {
        if( $f == "." || $f == ".." ){
            continue;
        }

        if( is_dir( "$the_dir/$f" ) ) {
			if($include_dirs) {
				$the_array[] = clean_path("$the_dir/$f");
			}
            $the_array = findAllFiles( "$the_dir/$f/", $the_array, $include_dirs );
        } else {
            $the_array[] = clean_path("$the_dir/$f");
        }


    }
    rsort($the_array);
    
    return $the_array;
}

function findAllFilesRelative( $the_dir, $the_array ){
    $original_dir   = getCwd();
    chdir( $the_dir );
    $the_array = findAllFiles( ".", $the_array );
    chdir( $original_dir );
    return( $the_array );
}

/*
 * Obtain an array of files that have been modified after the $date_modified
 * 
 * @param the_dir			the directory to begin the search
 * @param the_array			array to hold the results
 * @param date_modified		the date to use when searching for files that have
 * 							been modified
 * 
 * return					an array containing all of the files that have been
 * 							modified since date_modified
 */
function findAllTouchedFiles( $the_dir, $the_array, $date_modified){
    $d = dir( $the_dir );
    while( $f = $d->read() ){
        if( $f == "." || $f == ".." ){
            continue;
        }
        if( is_dir( "$the_dir/$f" ) ){
            // i think depth first is ok, given our cvs repo structure -Bob.
            $the_array = findAllTouchedFiles( "$the_dir/$f", $the_array, $date_modified);
        }
        else {
        	$file_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', filemtime("$the_dir/$f"))) - date('Z'));

        	if(strtotime($file_date) > strtotime($date_modified)){
        		 array_push( $the_array, "$the_dir/$f");
        	}
        }
    }
    return( $the_array );
}
?>
