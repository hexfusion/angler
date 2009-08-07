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
 * $Id: index.php,v 1.90 2006/07/24 18:55:04 wayne Exp $
 * Description:  Main file for the Home module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $theme;
global $currentModule;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

require_once('modules/Charts/code/predefined_charts.php');





$lowerBeanList = array();

foreach($beanList as $module=>$class_name)
{
  $lowerBeanList[strtolower($module)] = $class_name;
}

echo "<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_TITLE'], true); 
echo "\n</p>\n";


?>
<script type="text/javascript" language="JavaScript">
<!-- Begin
function toggleDisplay(id){

	if(this.document.getElementById( id).style.display=='none'){
		this.document.getElementById( id).style.display='inline'
		if(this.document.getElementById(id+"link") != undefined){
			this.document.getElementById(id+"link").style.display='none';
		}

	}else{
		this.document.getElementById(  id).style.display='none'
		if(this.document.getElementById(id+"link") != undefined){
			this.document.getElementById(id+"link").style.display='inline';
		}
	}
}
		//  End -->
// WARNING: this function is repeated in Reports/templates/templates_report_functions_js.php for charts within Reports.
// This "saved_chart_drilldown" is run when a graph is clicked from the dashboard. 
function saved_chart_drilldown(group_value,group_key,id)
{
       var report_url = 'index.php?module=Reports&page=report&action=index&id='+id+'#'+group_value;
       document.location = report_url;
}

	</script>

<?php
require_once('modules/Dashboard/Dashboard.php');
$dashboard = new Dashboard();
$users_dashboard = $dashboard->getUsersTopDashboard($current_user->id);
$dashboard_def = unserialize(from_html($users_dashboard->content));

print_add($users_dashboard->id,$dashboard_def);

$count = 0;
// if the user has changed the theme then refresh all charts
if($_SESSION['theme_changed'] || $theme != $current_user->getPreference('lastTheme')) { 
	$_REQUEST['pbss_refresh'] = 'true';
	$_REQUEST['lsbo_refresh'] = 'true';
	$_REQUEST['pbls_refresh'] = 'true';
	$_REQUEST['mypbss_refresh'] = 'true';
	$_REQUEST['obm_refresh'] = 'true';
}
echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $mod_strings['LBL_MOVE_CHARTS'];
echo '<div id="chartStatus" style="z-index: 100; display: none; font-weight: bold; font-size: 20px" class="required"></div>';
echo '<div id="entireWindow" style="z-index: 99; display: none; opacity: .7; filter: alpha(opacity=70); display: none; background-color: #ffffff">&nbsp;</div>';
echo '
<script type="text/javascript" src="include/javascript/yui/dragdrop.js" ></script>
<script type="text/javascript" src="include/javascript/yui/ygDDList.js" ></script>';
echo '<ul class="noBullet" id="charts">';

foreach ($dashboard_def as $def)
{
	echo '<li class="noBullet" id="chart_' . $count . '">';
  $dashboard_tools = get_chart_header($users_dashboard->id, $count);
	if ( $def['type'] == 'code')
	{
		$func = $def['id'];
    global $currentModule; 
    $currentModule = 'Dashboard';
		require_once ("modules/Charts/code/".$func.".php");
		$chart = new $func;
		$chart->order = $count;
		if ( ! get_display_status($chart->modules) )
		{
			continue;
		}

		$chart->draw($dashboard_tools);
















































			
	}
  $count++;
  echo '</li>';
}
echo '<li id="hidden1" style="visibility: hidden;">Hidden</li>';
echo '</ul>';
echo '<script type="text/javascript">
	var dd = []

 function dragDropInit() {
    var i = 1;
    for (j=0;j < ' . $count . ';++j) {
      dd[j] = new ygDDList("chart_" + j);
	  dd[j].setHandleElId("chart_handle_" + j);
      dd[j].onMouseDown = onDrag;  
	  dd[j].onDragDrop = onDrop;
    }

    dd[0] = new ygDDListBoundary("hidden1");

    YAHOO.util.DDM.mode = 1;

  }

  YAHOO.util.Event.addListener(window, "load", dragDropInit);  
';
  

echo <<<EOQ
dashboard_id = "{$users_dashboard->id}";
var OriginalChartOrder;
  function getChartOrder() {
    chartsObj = document.getElementById('charts');
    chartIds = new Array();
    for(wp = 0; wp < chartsObj.childNodes.length; wp++) {
      if(chartsObj.childNodes[wp].id.match(/chart_\d/))
        chartIds.push(chartsObj.childNodes[wp].id.replace(/chart_/,''));
    }
    return chartIds.join('-');    
  }

  function onDrag(e, id) {
	OriginalChartOrder = getChartOrder();   	
  }
  
  function onDrop(e, id) {	
  	if(OriginalChartOrder != getChartOrder()) {
  		saveCharts(getChartOrder(), id);
  	}
  }
  
  function saveCharts(order, elId) {
    chartIds = getChartOrder();
    ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING_LAYOUT'));
            var success = function(data) {
                ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVED_LAYOUT'));
                window.setTimeout('ajaxStatus.hideStatus()', 2000);
            }
            
            url = 'index.php?module=Dashboard&action=EditDashboard&dashboard_action=arrange&to_pdf=true&chart_index=0&record=' + dashboard_id + '&chartorder=' + order;
            var cObj = YAHOO.util.Connect.asyncRequest('GET', url, {success: success, failure: success});       
  }
  
  
  
</script>
EOQ;


function get_chart_header($dashboard_id, $chart_index)
{
	ob_start();
 global $image_path,$mod_strings;
?>
<a href="index.php?return_action=<?php echo $_REQUEST['action'];?>&return_module=Dashboard&action=EditDashboard&dashboard_action=move_up&module=Dashboard&chart_index=<?Php echo $chart_index; ?>&record=<?php echo $dashboard_id; ?>"><?php echo get_image($image_path."uparrow",'border="0" align="absmiddle" alt="'.$mod_strings['LBL_MOVE_UP'].'"');?></a>
<a href="index.php?return_action=<?php echo $_REQUEST['action'];?>&return_module=Dashboard&action=EditDashboard&dashboard_action=move_down&module=Dashboard&chart_index=<?Php echo $chart_index; ?>&record=<?php echo $dashboard_id; ?>"><?php echo get_image($image_path."downarrow",'border="0" align="absmiddle" alt="'.$mod_strings['LBL_MOVE_DOWN'].'"');?></a>
<a href="index.php?return_action=<?php echo $_REQUEST['action'];?>&return_module=Dashboard&action=EditDashboard&dashboard_action=delete&module=Dashboard&chart_index=<?Php echo $chart_index; ?>&record=<?php echo $dashboard_id; ?>" class="listViewTdToolsS1"><?php echo get_image($image_path."close_dashboard",'border="0" align="absmiddle" alt="'.$mod_strings['LBL_DELETE_FROM_DASHBOARD'].'"');?></a>
<?php
	$contents = ob_get_contents();
	ob_end_clean();
	return $contents;

}

function print_add($dashboard_id,&$dashboard_def)
{
  $chart_map = array();
  foreach($dashboard_def as $def)
  {
      $chart_map[$def['id']] = 1;
  }
 global $image_path,$mod_strings,$predefined_charts;
print $mod_strings['LBL_ADD_A_CHART'].":&nbsp;";
?>

<script language="javascript">
function add_chart(chart_index,chart_id,dashboard_id)
{
 var arr = chart_id.split('|');
 if (arr[0] == 'nothing')
 {
   return true;
 }
window.location="index.php?return_action=index&return_module=Dashboard&action=EditDashboard&module=Dashboard&dashboard_action=add&chart_index="+chart_index+"&chart_type="+arr[0]+"&chart_id="+arr[1]+"&record="+dashboard_id;
}
</script>
<style>
.do_nothing{font-weight: bold;}
</style>

<?php

print '<select name="add_chart" onchange="add_chart(0,this.options[this.selectedIndex].value,\''.$dashboard_id.'\')">';

print '<option value="nothing|" class="do_nothing" SELECTED>'.$mod_strings['LBL_BASIC_CHARTS'].'</option>';

foreach ($predefined_charts as $chart)
{
  if ( ! empty($chart_map[$chart['id']]))
  {
    continue;
  }
	    $func = $chart['id'];
//    global $currentModule;
//    $currentModule = 'Dashboard';
    require_once ("modules/Charts/code/".$func.".php");
    $chart_obj = new $func;

    if ( ! get_display_status($chart_obj->modules) )
    {
      continue;
    }

  print '<option value="code|'.$chart['id'].'">'.$chart['label'].'</option>';
}











































































































print '</select>';

}

function get_module_display_status($module){
	$display = true;
	global $beanList, $beanFiles;
	require_once($beanFiles[$beanList[$module]]);
    $seed = new $beanList[$module];
    if(!$seed->ACLAccess('access') || !$seed->ACLAccess('DetailView')){
    	$display = false;
    }
	return $display;
}

function get_display_status(&$chart_modules)
{
		$display = true;
    global $beanList,$beanFiles;
		foreach($chart_modules as $module)
		{	
      		require_once($beanFiles[$beanList[$module]]);
      		$seed = new $beanList[$module];
      		if(!$seed->ACLAccess('DetailView')){
        		$display = false;
        		break;
      		}
    	}
		return $display;
}


?>
