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

 // $Id: SubpanelColParser.php,v 1.4 2006/08/22 19:59:34 awu Exp $



class SubpanelColParser extends SubpanelParser{

    var $labelEditor = false;
    var $fieldEditor = false;


    function parse($str){
        $this->parseRows($str);
    }
    function handleSave(){
        $fields = $this->panel->panel_definition['list_fields'];
        $newFields = array();
        foreach($fields as $name => $field){
            if(!isset($field['usage'])|| $field['usage'] != 'query_only'){
                $existingFields[$name] = $field;

            }else{
                $newFields[$name] = $field;
                
            }
        }
        $keys = array_keys($existingFields);
        for($i = 0; isset($_REQUEST['add_studiocol' . $i]) && !empty($keys[$i]); $i++){

            if($_REQUEST['add_studiocol' . $i] > -1){
                $newFields[$keys[$i]] = $existingFields[$keys[$i]];
                for($j = 0; $j < $_REQUEST['add_studiocol' . $i]; $j++){
                    $newFields[time(). $j] = array('name'=>'', 'usage'=>'display_only','sortable'=>false, 'display_label'=>false);
                }
            }


        }
        $this->subpanel->saveSubPanelDefOverride( $this->panel,'list_fields', $newFields);


    }





    function generateButtons(){
        global $image_path;
        $imageSave = get_image($image_path. 'studio_save', '');
        $buttons = array();
        $buttons[] = array('image'=>$imageSave,'text'=>$GLOBALS['mod_strings']['LBL_BTN_SAVEPUBLISH'],'actionScript'=>"onclick='document.studio.submit()'");
        return $buttons;
    }

    function prepSlots(){
        $this->parseCols($this->curText);
        global $image_path;
        $view = $this->curText;
        $counter = 0;
        $midpoint = floor(count($this->cols)) / 2 - 2;

        foreach($this->cols as $col){
            $explode = explode($col[0], $view, 2);
            if($this->positionCount($col[0]) > 0 && $this->rowCount($col[2]. '</tr>') == 0){
                if($counter <= $midpoint){
                    $view = $explode[0] . "<td class='tabDetailViewDF' nowrap><a href='javascript:void(0);' onclick='addNewColToView(\"studiocol$counter\", $counter)' >".get_image($image_path."plus_inline","border='0' alt='Add Row'")."</a>&nbsp;<a href='javascript:void(0);' onclick='deleteColFromView(\"studiocol$counter\", $counter)' >".get_image($image_path."minus_inline","border='0' alt='Remove col'")."</a></td>";
                    $view .= $col[1] . " id='studiocol$counter' >";
                }else{
                    $view = $explode[0] .  '<td>&nbsp;</td>'. $col[1] .  " id='studiocol{$counter}b' >";
                }
                $view .=  $col[2]. $col[3].  $explode[1];
                $this->addSlotToForm($counter);
                $counter++;
            }else{
                $view = $explode[0].$col[0] . 	$explode[1];
            }


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
        $this->form .= "\n<input type='hidden' name='add_studiocol$slot_count'  id='add_studiocol$slot_count' value='0'>";
    }
    function getForm(){
        $this->form .= "<input type='hidden' name='parser' value='SubpanelColParser'>";
        return parent::getForm();
    }


}

?>
