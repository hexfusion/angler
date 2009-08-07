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

require_once('include/ytree/Node.php');
require_once('include/ytree/Tree.php');

class DashletsTree {
    function display() {
        global $app_strings, $current_language, $mod_strings;
        require_once('cache/dashlets/dashlets.php');
        
        $tree = new Tree('Dashlets');
        
        // key => number of dashlets in cat
        $theCats = array('Module Views' => 0, 
                         'Portal' => 0, 
                         'Charts' => 0, 
                         'Tools' => 0, 
                         'Miscellaneous' => 0 );
        $categories = array();
        
        foreach($theCats as $cat => $num) {
            $categories[$cat] = new Node($cat, $mod_strings['dashlet_categories_dom'][$cat], true);
        }

        $dashletStrings = array();
        asort($dashletsFiles);
        foreach($dashletsFiles as $className => $files) {
            if(!empty($files['meta']) && is_file($files['meta'])) {
                require_once($files['meta']); // get meta file
                
                $directory = substr($files['meta'], 0, strrpos($files['meta'], '/') + 1);
                if(is_file($directory . $files['class'] . '.' . $current_language . '.lang.php')) 
                    require_once($directory . $files['class'] . '.' . $current_language . '.lang.php');
                elseif(is_file($directory . $files['class'] . '.en_us.lang.php')) 
                    require_once($directory . $files['class'] . '.en_us.lang.php');

                // try to translate the string
                if(empty($dashletStrings[$files['class']][$dashletMeta[$files['class']]['title']]))
                    $title = $dashletMeta[$files['class']]['title'];
                else 
                    $title = $dashletStrings[$files['class']][$dashletMeta[$files['class']]['title']];
                
                // try to translate the string
                if(empty($dashletStrings[$files['class']][$dashletMeta[$files['class']]['description']]))
                    $description = $dashletMeta[$files['class']]['description']; 
                else 
                    $description = $dashletStrings[$files['class']][$dashletMeta[$files['class']]['description']];
                
                if(empty($dashletMeta[$files['class']]['icon'])) { // no icon defined in meta data
                    if(empty($files['icon'])) {
                        $icon = ''; // no icon found
                    }
                    else { // use one found in directory
                        $icon = '<img border="0" src="' . $files['icon'] . '">';
                    }
                }
                else { // use one defined in meta data
                    $icon = '<img border="0" src="' . $dashletMeta[$files['class']]['icon'] . '">';
                }
                
                $node = new Node($title, '<table cellpadding="0" border="0"><tr onclick="return SUGAR.sugarHome.addDashlet(\'' . $className . '\');" onmouseover="return overlib(\'' 
                                . $description .
                                '\', FGCLASS, \'olFgClass\', CGCLASS, \'olCgClass\', BGCLASS, \'olBgClass\', TEXTFONTCLASS, \'olFontClass\', CAPTIONFONTCLASS, \'olCapFontClass\', CLOSEFONTCLASS, \'olCloseFontClass\' );" onmouseout="return nd();">
                                <td valign="top">' . $icon . '</td><td>' . $title . '</td></tr></table>');
                
                if(isset($categories[$dashletMeta[$files['class']]['category']])) { // is it categorized?
                    $theCats[$dashletMeta[$files['class']]['category']]++;
                    $categories[$dashletMeta[$files['class']]['category']]->add_node($node);
                } 
                else {// default to misc cat
                    $theCats['Miscellaneous']++;
                    $categories['Miscellaneous']->add_node($node);
                }
            }
        }
        
        foreach($theCats as $cat => $num) {
            if($num != 0) // only add cat if there are dashlets in this category 
                $tree->add_node($categories[$cat]);
        }

        return $tree->generate_nodes_array(false); 
    }   
}

global $current_language;
$mod_strings = return_module_language($current_language, 'Home');
$DashletsTree = new DashletsTree();
$script = $DashletsTree->display();
$html = '<a href="#" onclick="return SUGAR.sugarHome.doneAddDashlets();"> ' .$mod_strings['LBL_CLOSE_DASHLETS'] . '</a><br><br><h2>' . $mod_strings['LBL_ADD_DASHLETS'] . '</h2><div id="Dashlets"></div>';

$json = getJSONobj();

echo 'response = ' . $json->encode(array('html' => $html, 'script' => $script));
?>
