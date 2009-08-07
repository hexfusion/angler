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

 // $Id: SubpanelParser.php,v 1.4 2006/08/22 19:59:34 awu Exp $



/**
 * interface for studio parsers
 */
class SubpanelParser extends StudioParser {
    var $positions = array ();
    var $rows = array ();
    var $cols = array ();
    var $curFile = '';
    var $curText = '';
    var $form;
    var $labelEditor = true;
    var $fieldEditor = true;
  

    function loadSubpanel($module_name,$child_module){
		$this->parent_module = $module_name;
		$this->child_module = $child_module;
		global $beanList, $beanFiles;
		$class = $beanList[$module_name];
		require_once($beanFiles[$class]);
		require_once('include/SubPanel/SubPanelDefinitions.php');
		$mod = new $class();
		$spd = new SubPanelDefinitions($mod);
		$spd->open_layout_defs(true);
		$panel = $spd->load_subpanel($child_module, true);
		$this->panel = $panel;
		$subpanel = new SubPanel($module_name, 'fab4', $child_module, $panel);
		$subpanel->setTemplateFile('include/SubPanel/SubPanelDynamic.html');
		$oldcontents = ob_get_contents();
		ob_clean();
		$subpanel->display();
		$this->curText= ob_get_contents();
		ob_clean();
	   echo $oldcontents;
		$this->subpanel = $subpanel;
		 $this->form = <<<EOQ
		</form>
		<form name='studio' method='POST'>
			<input type='hidden' name='action' value='saveSubpanel'>
			<input type='hidden' name='module' value='Studio'>
			<input type='hidden' name='subpanel' value='$child_module'>
EOQ;
	
		
	}
 

    function generateButtons(){
        global $image_path;
        $imageSave = get_image($image_path. 'studio_save', '');
        $imagePublish = get_image($image_path. 'studio_publish', '');
        $imageHistory = get_image($image_path. 'studio_history', '');
        $imageAddRows = get_image($image_path.'studio_addRows', '');
        $imageUndo = get_image($image_path.'studio_undo', '');
        $imageRedo = get_image($image_path.'studio_redo', '');
         $imageAddField = get_image($image_path. 'studio_addField', '');
        $buttons = array();
        $buttons[] = array('image'=>$imageUndo,'text'=>$GLOBALS['mod_strings']['LBL_BTN_UNDO'],'actionScript'=>"onclick='jstransaction.undo()'" );
        $buttons[] = array('image'=>$imageRedo,'text'=>$GLOBALS['mod_strings']['LBL_BTN_REDO'],'actionScript'=>"onclick='jstransaction.redo()'" );
        
        $buttons[] = array('image'=>$imageAddRows,'text'=>$GLOBALS['mod_strings']['LBL_BTN_ADDCOLS'],'actionScript'=>"onclick='if(!confirmNoSave())return false;document.location.href=\"index.php?module=Studio&action=EditSubpanel&parser=SubpanelColParser&subpanel=$this->child_module\"'" ,);
        $buttons[] = array('image'=>'', 'text'=>'-', 'actionScript'=>'', 'plain'=>true);
        
        $buttons[] = array('image'=>$imagePublish,'text'=>$GLOBALS['mod_strings']['LBL_BTN_SAVEPUBLISH'],'actionScript'=>"onclick='document.studio.submit()'");
        return $buttons;
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
	   for($i = 1; isset($_REQUEST['slot_' . $i]); $i++){
	       if(is_numeric($_REQUEST['slot_' . $i])){
	           $key = $keys[$_REQUEST['slot_' . $i] - 1];
	           $newFields[$key] = $existingFields[$key];
	       }else if(strcmp('add:delete', trim($_REQUEST['slot_' . $i])) == 0){
	           $newFields[time()] =  array('name'=>'', 'usage'=>'display_only','sortable'=>false, 'display_label'=>false);
	       }
	       else if(substr_count($_REQUEST['slot_' . $i], 'add:')){
               $addfield = explode('add:', $_REQUEST['slot_'.$i], 2);
               $vname = '';	
               if(isset($this->subpanel->subpanel_defs->template_instance->field_defs[$addfield[1]])){
					$vname = $this->subpanel->subpanel_defs->template_instance->field_defs[$addfield[1]]['vname'];
			    }
	           $newFields[$addfield[1]] = array('name'=>$addfield[1], 'vname'=>$vname);
	       }
	       
	   }
	 $this->subpanel->saveSubPanelDefOverride( $this->panel,'list_fields', $newFields); 
        
        
    }
    







}
?>
