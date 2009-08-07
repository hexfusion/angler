<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * SugarWidgetTabs
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
 
// $Id: tabs.php,v 1.9 2006/07/11 02:57:25 wayne Exp $

require_once('include/generic/SugarWidgets/SugarWidget.php');

class SugarWidgetTabs extends SugarWidget
{
 var $tabs;
 var $current_key;

 function SugarWidgetTabs(&$tabs,$current_key,$jscallback)
 {
   $this->tabs = $tabs;
   $this->current_key = $current_key;
   $this->jscallback = $jscallback;
 }

 function display()
 {
	global $image_path;
	$IMAGE_PATH = $image_path;
	ob_start();
?>
<script>
var keys = [ <?php 
$tabs_count = count($this->tabs);
for($i=0; $i < $tabs_count;$i++) 
{
 $tab = $this->tabs[$i];
 echo "\"".$tab['key']."\""; 
 if ($tabs_count > ($i + 1))
 {
   echo ",";
 }
}
?>]; 
tabPreviousKey = '';

function selectTabCSS(key)
{


  for( var i=0; i<keys.length;i++)
  {
   var liclass = '';
   var linkclass = '';

 if ( key == keys[i])
 {
   var liclass = 'active';
   var linkclass = 'current';
 }
  	document.getElementById('tab_li_'+keys[i]).className = liclass;

  	document.getElementById('tab_link_'+keys[i]).className = linkclass;
  }
    <?php echo $this->jscallback;?>(key, tabPreviousKey);
    tabPreviousKey = key;
}
</script>

<ul class="tablist">
<?php 
	foreach ($this->tabs as $tab)
	{
		$TITLE = $tab['title'];
		$LI_ID = "";
		$A_ID = "";

	  if ( ! empty($tab['hidden']) && $tab['hidden'] == true)
		{
			  $LI_ID = "style=\"display: none\"";
			  $A_ID = "style=\"display: none\"";

		} else if ( $this->current_key == $tab['key'])
		{
			  $LI_ID = "class=\"active\"";
			  $A_ID = "class=\"current\"";
		}

		$LINK = "<li $LI_ID id=\"tab_li_".$tab['link']."\"><a $A_ID id=\"tab_link_".$tab['link']."\" href=\"javascript:selectTabCSS('{$tab['link']}');\">$TITLE</a></li>";

?>
<?php echo $LINK; ?>	
<?php
	}
?>
</ul>
<?php 
	$ob_contents = ob_get_contents();
        ob_end_clean();
        return $ob_contents;
	}
}
?>
