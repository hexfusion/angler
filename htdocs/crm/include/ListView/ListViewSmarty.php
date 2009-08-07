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
 
require_once('include/ListView/ListViewDisplay.php');
require_once('include/Sugar_Smarty.php');
require_once('include/utils.php');

class ListViewSmarty extends ListViewDisplay{
    
	var $data;
	var $ss; // the smarty object 
	var $displayColumns;
	var $tpl;
	var $moduleString;
	var $export = true;
    var $mailMerge = true;
	var $multiSelect = true;
	var $overlib = true;
	var $quickViewLinks = true;
	var $lvd;
	var $mergeduplicates=true;
    /**
     * Constructor, Smarty object immediately available after 
     *
     */
	function ListViewSmarty() {
		parent::ListViewDisplay();
		$this->ss = new Sugar_Smarty();
	}
	
    /**
     * Processes the request. Calls ListViewData process. Also assigns all lang strings, export links,
     * This is called from ListViewDisplay
     *    
     * @param file file Template file to use
     * @param data array from ListViewData
     * @param html_var string the corresponding html var in xtpl per row
     *
     */ 
	function process($file, $data, $htmlVar) {
		if(!$this->should_process)return;
		global $odd_bg, $even_bg, $hilite_bg, $click_bg, $image_path, $app_strings;
		parent::process($file, $data, $htmlVar);
		
		$this->tpl = $file;
		$this->data = $data;
		
        $totalWidth = 0;
        foreach($this->displayColumns as $name => $params) {
            $totalWidth += $params['width'];
        }
        $adjustment = $totalWidth / 100;

        foreach($this->displayColumns as $name => $params) {
            $this->displayColumns[$name]['width'] = round($this->displayColumns[$name]['width'] / $adjustment, 2);
        }
		$this->ss->assign('displayColumns', $this->displayColumns);
        
       
		$this->ss->assign('bgHilite', $hilite_bg);
		$this->ss->assign('colCount', count($this->displayColumns) + 1);
		$this->ss->assign('htmlVar', strtoupper($htmlVar));
		$this->ss->assign('moduleString', $this->moduleString);
        $this->ss->assign('editLinkString', $app_strings['LBL_EDIT_BUTTON']);
        $this->ss->assign('viewLinkString', $app_strings['LBL_VIEW_BUTTON']);
		
        $this->ss->assign('imagePath', $image_path);

		if($this->overlib) $this->ss->assign('overlib', true);
		if($this->export) $this->ss->assign('exportLink', $this->buildExportLink());
		$this->ss->assign('quickViewLinks', $this->quickViewLinks);
		if($this->mailMerge) $this->ss->assign('mergeLink', $this->buildMergeLink()); // still check for mailmerge access
        if($this->mergeduplicates) $this->ss->assign('mergedupLink', $this->buildMergeDuplicatesLink());
        
        
		// handle save checks and stuff
		if($this->multiSelect) {
			$this->ss->assign('selectedObjectsSpan', $this->buildSelectedObjectsSpan());
			$this->ss->assign('multiSelectData', $this->getMultiSelectData());
		}
		
		$this->processArrows($data['pageData']['ordering']);
		$this->ss->assign('prerow', $this->multiSelect);
		$this->ss->assign('clearAll', $app_strings['LBL_CLEARALL']);
		$this->ss->assign('rowColor', array('oddListRow', 'evenListRow'));
		$this->ss->assign('bgColor', array($odd_bg, $even_bg));
	}
    
    /**
     * Assigns the sort arrows in the tpl
     *    
     * @param ordering array data that contains the ordering info
     *
     */
	function processArrows($ordering) {
		global $png_support;
        
        if(empty($GLOBALS['image_path'])) {
            global $theme;
            $GLOBALS['image_path'] = 'themes/'.$theme.'/images/';
        }
        
		if ($png_support == false) $ext = 'gif';
		else $ext = 'png';
		
		list($width,$height) = getimagesize($GLOBALS['image_path'] . 'arrow.' . (($png_support) ? 'png' : 'gif'));
		
		$this->ss->assign('arrowExt', $ext);
		$this->ss->assign('arrowWidth', $width);
		$this->ss->assign('arrowHeight', $height);
	}	



    /**
     * Displays the xtpl, either echo or returning the contents
     *    
     * @param end bool display the ending of the listview data (ie MassUpdate)
     *
     */
	function display($end = true) {
		
		if(!$this->should_process) return $GLOBALS['app_strings']['LBL_SEARCH_POPULATE_ONLY'];
        global $app_strings;
        
        $this->ss->assign('data', $this->data['data']);
		$this->data['pageData']['offsets']['lastOffsetOnPage'] = $this->data['pageData']['offsets']['current'] + count($this->data['data']);
		$this->ss->assign('pageData', $this->data['pageData']);
        
        $navStrings = array('next' => $app_strings['LNK_LIST_NEXT'],
                            'previous' => $app_strings['LNK_LIST_PREVIOUS'],
                            'end' => $app_strings['LNK_LIST_END'],
                            'start' => $app_strings['LNK_LIST_START'],
                            'of' => $app_strings['LBL_LIST_OF']);
        $this->ss->assign('navStrings', $navStrings);
		
		$str = parent::display();
		$strend = parent::displayEnd();
		
		return $str . $this->ss->fetch($this->tpl) . (($end) ? '<br><br>' . $strend : '');
	}
}

?>
