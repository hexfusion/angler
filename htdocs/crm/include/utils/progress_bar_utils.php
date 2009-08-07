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

 // $Id: progress_bar_utils.php,v 1.4 2006/08/22 18:56:15 awu Exp $
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
function display_flow_bar($name,$delay, $size=200){
	$chunk = $size/5;
	echo "<div id='{$name}_flow_bar'><table  class='listView' cellpading=0 cellspacing=0><tr><td id='{$name}_flow_bar0' width='{$chunk}px' bgcolor='#cccccc' align='center'>&nbsp;</td><td id='{$name}_flow_bar1' width='{$chunk}px' bgcolor='#ffffff' align='center'>&nbsp;</td><td id='{$name}_flow_bar2' width='{$chunk}px' bgcolor='#ffffff' align='center'>&nbsp;</td><td id='{$name}_flow_bar3' width='{$chunk}px' bgcolor='#ffffff' align='center'>&nbsp;</td><td id='{$name}_flow_bar4' width='{$chunk}px' bgcolor='#ffffff' align='center'>&nbsp;</td></tr></table></div><br>";

	echo str_repeat(' ',256);
	
	ob_flush();
	flush();
	start_flow_bar($name, $delay);


}

function start_flow_bar($name, $delay){
	$delay *= 1000;
	$timer_id = $name . '_id';
	echo "<script>
		function update_flow_bar(name, count){
			var last = (count - 1) % 5;
			var cur = count % 5;
			var next = cur + 1;
			eval(\"document.getElementById('\" + name+\"_flow_bar\" + last+\"').style.backgroundColor='#ffffff';\");
			eval(\"document.getElementById('\" + name+\"_flow_bar\" + cur+\"').style.backgroundColor='#cccccc';\");
			$timer_id = setTimeout(\"update_flow_bar('$name', \" + next + \")\", $delay);
		}
		 var $timer_id = setTimeout(\"update_flow_bar('$name', 1)\", $delay);

	</script>
";
	echo str_repeat(' ',256);
	
	ob_flush();
	flush();	
}
function destroy_flow_bar($name){
	$timer_id = $name . '_id';
	echo "<script>clearTimeout($timer_id);document.getElementById('{$name}_flow_bar').innerHTML = '';</script>";
	echo str_repeat(' ',256);
	
	ob_flush();
	flush();
	
}		
	
function display_progress_bar($name,$current, $total){
	$percent = $current/$total * 100;
	$remain = 100 - $percent;
	$status = floor($percent);
	//scale to a larger size
	$percent *= 2;
	$remain *= 2;
	if($remain == 0){
		$remain = 1;
	}
	if($percent == 0){
		$percent = 1;	
	}
	echo "<div id='{$name}_progress_bar'><table class='listView' cellpading=0 cellspacing=0><tr><td id='{$name}_complete_bar' width='{$percent}px' bgcolor='#cccccc' align='center'>$status% </td><td id='{$name}_remain_bar' width={$remain}px' bgcolor='#ffffff'>&nbsp;</td></tr></table></div><br>";
	if($status == 0){
		echo "<script>document.getElementById('{$name}_complete_bar').style.backgroundColor='#ffffff';</script>";
	}
	echo str_repeat(' ',256);
	
	ob_flush();
	flush();
	
}	

function update_progress_bar($name,$current, $total){
	$percent = $current/$total * 100;
	$remain = 100 - $percent;
	$status = floor($percent);
	//scale to a larger size
	$percent *= 2;
	$remain *= 2;
	if($remain == 0){
		$remain = 1;
	}
	if($status == 100){
		echo "<script>document.getElementById('{$name}_remain_bar').style.backgroundColor='#cccccc';</script>";
	}
	if($status == 0){
		echo "<script>document.getElementById('{$name}_remain_bar').style.backgroundColor='#ffffff';</script>";
		echo "<script>document.getElementById('{$name}_complete_bar').style.backgroundColor='#ffffff';</script>";
	}
	if($status > 0){
		echo "<script>document.getElementById('{$name}_complete_bar').style.backgroundColor='#cccccc';</script>";
	}
	
		
	if($percent == 0){
		$percent = 1;	
	}
		
	echo "<script>
		document.getElementById('{$name}_complete_bar').width='{$percent}px';
		document.getElementById('{$name}_complete_bar').innerHTML = '$status%';
		document.getElementById('{$name}_remain_bar').width='{$remain}px';
		</script>";
	ob_flush();
	flush();
	
	
	
}	

?>
