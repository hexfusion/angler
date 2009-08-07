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

class Dashlet {
   /**
     * Id of the Dashlet
     * @var guid
     */ 
    var $id; 
    /**
     * Title of the Dashlet
     * @var string
     */
    var $title = 'Generic Dashlet';
    /**
     * true if the Dashlet has configuration options. 
     * @var bool
     */
    var $isConfigurable = false;
    /**
     * true if the Dashlet is refreshable (ie charts that provide their own refresh) 
     * @var bool
     */
    var $isRefreshable = true;
    /**
     * true if the Dashlet contains javascript 
     * @var bool
     */
    var $hasScript = false;
    /**
     * Language strings, must be loaded at the Dashlet level w/ loadLanguage
     * @var array
     */
    var $dashletStrings;
    
    function Dashlet($id) {
        $this->id = $id;
    }
    
    /**
     * Called when Dashlet is displayed
     * 
     * @param string $text text after the title
     * @return string title html
     */
    function getTitle($text) {
        global $image_path, $app_strings, $sugar_config;
        
        if($this->isConfigurable) 
            $additionalTitle = '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td width="99%">' . $text 
                               . '</td><td nowrap width="1%"><div style="width: 100%;text-align:right"><a href="#" onclick="SUGAR.sugarHome.configureDashlet(\'' 
                               . $this->id . '\'); return false;" class="chartToolsLink">'    
                               . get_image($image_path.'edit','title="Edit Dashlet" alt="Edit Dashlet"  border="0"  align="absmiddle"').'</a> ' 
                               . '';
        else 
            $additionalTitle = '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td width="99%">' . $text 
                   . '</td><td nowrap width="1%"><div style="width: 100%;text-align:right">';
        
        if($this->isRefreshable)
            $additionalTitle .= '<a href="#" onclick="SUGAR.sugarHome.retrieveDashlet(\'' 
                                . $this->id . '\'); return false;"><img width="13" height="13" border="0" align="absmiddle" title="Refresh Dashlet" alt="Refresh Dashlet" src="' 
                                . $image_path . 'refresh.gif"/></a> ';
        $additionalTitle .= '<a href="#" onclick="SUGAR.sugarHome.deleteDashlet(\'' 
                            . $this->id . '\'); return false;"><img width="13" height="13" border="0" align="absmiddle" title="Delete Dashlet" alt="Delete Dashlet" src="' 
                            . $image_path . 'close_dashboard.gif"/></a></div></td></tr></table>';
            
        if(!function_exists('get_form_header')) {
            global $theme;
            require_once('themes/'.$theme.'/layout_utils.php');
        }
        
        $str = '<div ';
        if(empty($sugar_config['lock_homepage']) || $sugar_config['lock_homepage'] == false) $str .= ' onmouseover="this.style.cursor = \'move\';"';
        $str .= 'id="dashlet_header_' . $this->id . '">' . get_form_header($this->title, $additionalTitle, false) . '</div>';
        
        return $str;
    }
    /**
     * Called when Dashlet is displayed, override this
     * 
     * @param string $text text after the title
     * @return string title html
     */
    function display($text = '') {
        return $this->getTitle($text);
    }
    
    /**
     * Called when Dashlets configuration options are called
     * 
     */
    function displayOptions() {
    }
    
    /**
     * override if you need to do pre-processing before display is called
     * 
     */
    function process() {
    }    
    
    function save() {
    }
    
    /**
     * Override this if your dashlet is configurable (this is called when the the configureDashlet form is shown)
     * Filters the array for only the parameters it needs to save
     * 
     * @param array $req the array to pull options from
     * 
     * @return array options array
     */
    function saveOptions($req) {
    }
    
    /**
     * Sets the language strings
     * 
     * @param string $dashletClassname classname of the dashlet
     * 
     */
    function loadLanguage($dashletClassname, $dashletDirectory = 'modules/Home/Dashlets/') {
        global $current_language, $dashletStrings;
        
        if(!isset($dashletStrings[$dashletClassname])) {
            // load current language strings for current language, else default to english
            if(is_file($dashletDirectory . $dashletClassname . '/' . $dashletClassname . '.' . $current_language . '.lang.php')) 
                require_once($dashletDirectory . $dashletClassname . '/' . $dashletClassname . '.' . $current_language . '.lang.php');
            else 
                require_once($dashletDirectory . $dashletClassname . '/' . $dashletClassname . '.en_us.lang.php');
        }

        $this->dashletStrings = $dashletStrings[$dashletClassname];
    }
    
    /**
     * Generic way to store an options array into UserPreferences
     * 
     * @param array $optionsArray the array to save
     */
    function storeOptions($optionsArray) {
        global $current_user;
        $dashletDefs = $current_user->getPreference('dashlets', 'home'); // load user's dashlets config
        $dashletDefs[$this->id]['options'] = $optionsArray;
        $current_user->setPreference('dashlets', $dashletDefs, 0, 'home');   
    }
    
    /**
     * Generic way to retrieve options array from UserPreferences
     * 
     * @return array options array stored in UserPreferences
     */
    function loadOptions() {
        global $current_user;
        $dashletDefs = $current_user->getPreference('dashlets', 'home'); // load user's dashlets config
        if(isset($dashletDefs[$this->id]['options']))
            return $dashletDefs[$this->id]['options'];
        else 
            return array();   
    }
    
    /**
     * Generic way call a proxy, pass in the parameters url (and optionally postData) to fetch a url
     * use the parameter method for post or get.
     * @return array options array stored in UserPreferences
     */    
    /**
     * ON VACATION WILL BE BACK SOON
    function proxyCall() {
        if(!empty($_REQUEST['method']) && !empty($_REQUEST['url'])) {
            if(strtolower($_REQUEST['method']) == 'get') {
                // taken from PHP Cookbook pg 283
                $page = '';
                $fh = fopen($_REQUEST['url'], 'r');
                while(!feof($fh)) {
                    $page .= fread($fh, 1048576);
                }
                fclose($fh);
                echo $page;
            }
            else if(strtolower($_REQUEST['method']) == 'post') {
                //
            }
        }
        
    }
    **/
}
?>
