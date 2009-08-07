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

 // $Id: StudioRowParser.php,v 1.6 2006/09/05 18:11:19 majed Exp $



class StudioRowParser extends StudioParser{
	
	var $labelEditor = false;
	var $fieldEditor = false;
	
	
	function parse($str){
		$this->parseRows($str);
	}
	function handleSave(){
		$view = $this->curText;
		$return_view = '';
		$this->parsePositions($view);
		$max = $this->getMaxPosition();
		$counter = 0;
		foreach($this->rows as $row){
			$explode = explode($row[0], $view, 2);
			if($this->positionCount($row[0]) > 0 && $this->rowCount($row[2]. '</tr>') == 0){
				if(isset($_REQUEST['form_studiorow'. $counter]) && $_REQUEST['form_studiorow'. $counter] == 1){
					
					$return_view .=  $explode[0] . $row[0];
				}else{
					$return_view .=  $explode[0];
				}
				if(isset($_REQUEST['add_studiorow'. $counter]) && $_REQUEST['add_studiorow'. $counter] > 0){
					
					$this->parseCols($row[0]);
					for($j = 0; $j < $_REQUEST['add_studiorow'. $counter]; $j++){
						$col = '';
						
						for($i= 0 ; $i < count($this->cols); $i++){
							$sugarId = 'slot'.$max; 
							if($i % 2 == 1){
								$sugarId .='b';
								$max++;
							}
							
							$col .= $this->cols[$i][1] . '><span sugar="'.$sugarId. '">&nbsp;</span sugar="'. $sugarId . '">' . $this->cols[$i][3];
					}
					$return_view .= $row[1] .'>'. $col . $row[3];
					}
					
				}
			}else{
				$return_view .= $explode[0].$row[0] ;
			}
			$counter++;
			$view = $explode[1];
		}
		$return_view .= $view;
		
		$this->saveFile('', $return_view);
		return $return_view;
		
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
        $buttons[] = array('image'=>$imageSave,'text'=>$GLOBALS['mod_strings']['LBL_BTN_SAVE'],'actionScript'=>"onclick='document.studio.submit()'");
        $buttons[] = array('image'=>$imageHistory,'text'=>$GLOBALS['mod_strings']['LBL_BTN_HISTORY'],'actionScript'=>"onclick='if(!confirmNoSave())return false;document.location.href=\"index.php?module=Studio&action=wizard&wizard=ManageBackups&setFile={$_SESSION['studio']['selectedFileId']}\"'");
        return $buttons;
    }	
		
	function prepSlots(){
		global $image_path;
		$view = $this->curText;
		$counter = 0;
		
		foreach($this->rows as $row){
			$explode = explode($row[0], $view, 2);
			if($this->positionCount($row[0]) > 0 && $this->rowCount($row[2]. '</tr>') == 0){
				$view =  $explode[0] . $row[1]. " id='studiorow$counter' ><td class='tabDetailViewDF' nowrap><a href='#' onclick='addNewRowToView(\"studiorow$counter\")' >".get_image($image_path."plus_inline","border='0' alt='Add Row'")."</a>&nbsp;<a href='#' onclick='deleteRowFromView(\"studiorow$counter\")' >".get_image($image_path."minus_inline","border='0' alt='Remove row'")."</a></td>". $row[2]. $row[3]. $explode[1];
				$this->addSlotToForm($counter);
			}else{
				$view = $explode[0].$row[0] . 	$explode[1];
			}
			
			$counter++;
		}
		$this->slotCount = $counter;
		
		return $view;
		
	}
	
	function yahooJS() {
		return "<script type='text/javascript' src='modules/Studio/JSTransaction.js' ></script>
			<script>
			var jstransaction = new JSTransaction();
			</script><script type='text/javascript' src='modules/Studio/studio.js' ></script><script>var slotCount =". $this->slotCount.";</script>\n";
	}
	//disable the label editor
	function enableLabelEditor($str){
		return $str;
	}
	function addSlotToForm($slot_count){
		$this->form .= "\n<input type='hidden' name='form_studiorow$slot_count'  id='form_studiorow$slot_count' value='1'>";
		$this->form .= "\n<input type='hidden' name='add_studiorow$slot_count'  id='add_studiorow$slot_count' value='0'>";
	}
	function getForm(){
		$this->form .= "<input type='hidden' name='parser' value='StudioRowParser'>";
		return parent::getForm();
	}


}

?>
