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

// $Id: Charts.php,v 1.11 2006/08/23 01:41:07 wayne Exp $
function create_chart($chartName,$xmlFile,$width="800",$height="400") {
	$html ='<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
 codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
 WIDTH="'.$width.'" HEIGHT="'.$height.'" id="'.$chartName.'" ALIGN="">';
	$html .='<PARAM NAME=movie VALUE="include/charts/'.$chartName.'.swf?filename='.$xmlFile.'">';
	$html .='<PARAM NAME=bgcolor VALUE=#FFFFFF>';
	$html .='<PARAM NAME=wmode VALUE=transparent>';
	$html .= '<PARAM NAME=quality VALUE=high>';
	$html .='<EMBED src="include/charts/'.$chartName.'.swf?filename='.$xmlFile.'" wmode="transparent" quality=high bgcolor=#FFFFFF  WIDTH="'.$width.'" HEIGHT="'.$height.'" NAME="'.$chartName.'" ALIGN=""
 TYPE="application/x-shockwave-flash" PLUGINSPAGE="https://www.macromedia.com/go/getflashplayer">';
	$html .='</EMBED>';
	$html .='</OBJECT>';
return $html;
}


function generate_graphcolor($input,$instance) {
	if ($instance <20) {
	$color = array(
	"0xFF0000",
	"0x00FF00",
	"0x0000FF",
	"0xFF6600",
	"0x42FF8E",
	"0x6600FF",
	"0xFFFF00",
	"0x00FFFF",
	"0xFF00FF",
	"0x66FF00",
	"0x0066FF",
	"0xFF0066",
	"0xCC0000",
	"0x00CC00",
	"0x0000CC",
	"0xCC6600",
	"0x00CC66",
	"0x6600CC",
	"0xCCCC00",
	"0x00CCCC");
	$out = $color[$instance];
	} else {
	$out = "0x" . substr(md5($input), 0, 6);

	}
	return $out;
}

function save_xml_file($filename,$xml_file) {
	global $app_strings;

	if (!$handle = fopen($filename, 'w')) {
		$GLOBALS['log']->debug("Cannot open file ($filename)");
		return;
	}
	// Write $somecontent to our opened file.)
if ($app_strings['LBL_CHARSET'] != "UTF-8") {
	if (fwrite($handle,utf8_encode($xml_file)) === FALSE) {
		$GLOBALS['log']->debug("Cannot write to file ($filename)");
		return false;
	}
} else {
	if (fwrite($handle,$xml_file) === FALSE) {
		$GLOBALS['log']->debug("Cannot write to file ($filename)");
		return false;
	}
}

	$GLOBALS['log']->debug("Success, wrote ($xml_file) to file ($filename)");

	fclose($handle);
	return true;

}

function get_max($numbers) {
    $max = max($numbers);
    if ($max < 1) return $max;
    $base = pow(10, floor(log10($max)));
    return ceil($max/$base) * $base;
}

// retrieve the translated strings.
global $current_language;
$app_strings = return_application_language($current_language);

if(isset($app_strings['LBL_CHARSET']))
{
	$charset = $app_strings['LBL_CHARSET'];
}
else
{
	global $sugar_config;
	$charset = $sugar_config['default_charset'];
}
?>
