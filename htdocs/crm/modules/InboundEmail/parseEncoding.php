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
 * $Id: parseEncoding.php,v 1.2 2006/06/06 17:58:21 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
// takes a file as an argument and parses the stuff as text;

function write_array_to_file( $the_name, $the_array, $the_file ) {
	
    $the_string =   "<?php\n" .
"\n
if(empty(\$GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
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
 * Description:
 * Created On: Apr 22, 2006
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): Chris Nojima
 ********************************************************************************/\n\n" .


                    "\$$the_name = " .
                    var_export_helper( $the_array ) .
                    ";\n?>\n";

    if( $fh = @fopen( $the_file, "w" ) ){
        fputs( $fh, $the_string, strlen($the_string) );
        fclose( $fh );
        return( true );
    }
    else{
        return( false );
    }
}

function var_export_helper($tempArray) { 	 
 		if(!is_array($tempArray)){
 			return var_export($tempArray, true);	
 		}
         $addNone = 0; 	 
  	 
         foreach($tempArray as $key=>$val) 	 
         { 	 
                 if($key == '' && $val == '') 	 
                         $addNone = 1; 	 
         } 	 
  	 
         $newArray = var_export($tempArray, true); 	 
  	 
         if($addNone) 	 
         { 	 
                 $newArray = str_replace("array (", "array ( '' => '',", $newArray); 	 
         } 	 
  	 
         return $newArray;
 }

function grabFiles($url) {
	$dh = fsockopen($url, 80);
	while($fileName = readdir($dh)) {
		if(is_dir($url.$fileName)) {
			grabFiles($url.$fileName);
		}
		
		$fh = fopen($url.$fileName, "r");
		
		$fileContent = fread($fh, filesize($url.$fileName));
		
		$writeFile = "./{$fileName}";
		$fhLocal = fopen($writeFile, "w");
		
		fwrite($writeFile, $fileContent);
	}
}

///////////////////////////////////////////////////////////////////////////////
////	START CODE

while($file = readdir($dhUnicode)) {
	if(is_dir($file)) {
		$dhUniDeep = opendir("http://www.unicode.org/Public/MAPPINGS/OBSOLETE/EASTASIA/{$file}");
		
	}
}







$dh = opendir("./");
$search = array(" ", "  ", "   ", "    ");
$replace = array("\t","\t","\t","\t");


if(is_resource($dh)) {
	while($inputFile = readdir($dh)) {
		if(strpos($inputFile, "php")) {
			continue;
		}
		
		$inputFileVarSafe = str_replace("-","_",$inputFile);
		$outputFile = $inputFileVarSafe.".php";
		
		$fh = fopen($inputFile, "r");
		if(is_resource($fh)) {
			$charset = array();
			while($line = fgets($fh)) {
				$commentPos = strpos($line, "#");
				if($commentPos == 0) {
					continue; // skip comment strings
				}
				

				$exLine = str_replace($search, $replace, $line);
				$exLine = explode("\t", $line);


				$count = count($exLine);
				if($count < 2) {
					echo "count was {$count} :: file is {$inputFile} :: Error parsing line: {$line}\r";
					continue; // unexpected explode
				}
				
				// we know 0 is charset encoding
				// we know 1 is unicode in hex
				$countExLine = count($exLine);
				for($i=1; $i<$countExLine; $i++) {
					$exLine[$i] = trim($exLine[$i]);
					if($exLine[$i] != "") {
						$unicode = $exLine[$i];
						break 1;
					}
				}
				$charset[$exLine[0]] = $unicode;
				
			}
			
			if(count($charset) > 0) {
				write_array_to_file($inputFileVarSafe, $charset, $outputFile);
			}
			
		} else {
			echo "Error occured reading line from file!\r";
		}
		
	}	
} else {
	die("no directory handle");
}




echo "DONE\r";
?>
