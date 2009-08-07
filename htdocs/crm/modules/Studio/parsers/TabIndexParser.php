<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

 // $Id: TabIndexParser.php,v 1.1 2006/08/29 04:37:45 majed Exp $



class TabIndexParser extends StudioParser{
	
	var $labelEditor = false;
	var $fieldEditor = false;
	var $inputs = array();
	 function parseInputs($str){
        preg_match_all("'(<(textarea|input|select)[^>]*?)>'si", $str, $this->inputs,PREG_SET_ORDER);
       
    }
    
    function disableInputs($str) {
   return $str;
}
    
	function parse($str){
		$this->parseInputs($str);
	}
	
	
	function handleSave(){
		global $image_path;
		$view = $this->curText;
		$counter = 0;
		$newView = '';
		
		foreach($this->inputs as $row){
			$explode = explode($row[0], $view, 2);
		
				$newView .=  $explode[0]; ;
				if(!preg_match("'type[ ]*=[ ]*[\'\"]hidden[\'\"]'", $row[0])){
					//remove old  tab indicies
					$row[1] = preg_replace("'tabindex[ ]*=[ ]*[\'\"]*([0-9]+)[\'\"]*'si" , '',$row[1]);	
					$newView .= $row[1];
					//add new tab indicies
					if(!empty($_REQUEST['form_tabindex'. $counter])){
						$newView .= " tabindex='" . $_REQUEST['form_tabindex'. $counter] . "'";	
					}
					$newView .= '>';
					
					$counter++;
					
				}else{
					$newView .= $row[1]. '>';	
				}
				$view = $explode[1];
				
			
			
			
		}
		
		$this->slotCount = $counter;
		$newView .= $view;
		
		//save
	
		$this->saveFile('', $newView);
		
		return $newView;
		
	}
	
		
		
	 function generateButtons(){
        global $image_path;
        $imageSave = get_image($image_path. 'studio_save', '');
        $imagePublish = get_image($image_path. 'studio_publish', '');
        $imageHistory = get_image($image_path. 'studio_history', '');
        $imageAddRows = get_image($image_path.'studio_addRows', '');
        $imageUndo = get_image($image_path.'studio_undo', '');
        $imageRedo = get_image($image_path.'studio_redo', '');
        $buttons = array();
        $buttons[] = array('image'=>$imageSave,'text'=>$GLOBALS['mod_strings']['LBL_BTN_SAVE'],'actionScript'=>"onclick='if(lastTabIndex)lastTabIndex.blur();document.studio.submit()' ");
        $buttons[] = array('image'=>$imageHistory,'text'=>$GLOBALS['mod_strings']['LBL_BTN_HISTORY'],'actionScript'=>"onclick='if(!confirmNoSave())return false;document.location.href=\"index.php?module=Studio&action=wizard&wizard=ManageBackups&setFile={$_SESSION['studio']['selectedFileId']}\"'");
        return $buttons;
    }	
		
	function prepSlots(){
		global $image_path;
		$view = $this->curText;
		$counter = 0;
		$newView = '';
		
		foreach($this->inputs as $row){
			$explode = explode($row[0], $view, 2);
		
				$newView .=  $explode[0] . $row[1] . '  readonly disabled>';
				if(!preg_match("'type[ ]*=[ ]*[\'\"]hidden[\'\"]'", $row[0])){
					if($row[2] != 'input'){
						$myinput = $row[2];
						$subMatch = array();
						
						preg_match("'(.*?<\/". $myinput . "[^>]*>)(.*)'si" , $explode[1],$subMatch);	
						$newView .= $subMatch[1];
						$explode[1] = $subMatch[2];
					}
					$tabMatch = array();
					$tabIndex= '';
					preg_match("'tabindex[ ]*=[ ]*[\'\"]*([0-9]+)[\'\"]*'si" , $row[1],$tabMatch);	
					$tabIndex = (isset($tabMatch[1]))?$tabMatch[1]:'';
					$newView .= '<input size="3" tabindex="1" value="'. $tabIndex . '" onfocus="lastTabIndex=this" onchange="document.getElementById(\'form_tabindex'.$counter. '\').value=this.value;">';
				
					$this->addSlotToForm($counter, $tabIndex);
					$counter++;
				}
				$view = $explode[1];
				
			
			
			
		}
		$this->slotCount = $counter;
		
		return $newView . $view;
		
	}
	
	
	
	function yahooJS() {
		return "<script type='text/javascript' src='modules/Studio/JSTransaction.js' ></script>
			<script>
			var lastTabIndex = false;
			var jstransaction = new JSTransaction();
			</script><script type='text/javascript' src='modules/Studio/studio.js' ></script><script>var slotCount =". $this->slotCount.";</script>\n";
	}
	//disable the label editor
	function enableLabelEditor($str){
		return $str;
	}
	function addSlotToForm($slot_count, $tabIndex){
		$this->form .= "\n<input type='hidden' name='form_tabindex$slot_count'  id='form_tabindex$slot_count' value='$tabIndex'>";
	}
	function getForm(){
		$this->form .= "<input type='hidden' name='parser' value='TabIndexParser'>";
		return parent::getForm();
	}


}

?>
