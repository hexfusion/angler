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

 // $Id: StudioParser.php,v 1.17 2006/09/06 03:46:39 majed Exp $


require_once('include/utils/file_utils.php');
/**
 * interface for studio parsers
 */

class StudioParser {
    var $positions = array ();
    var $rows = array ();
    var $cols = array ();
    var $curFile = '';
    var $curText = '';
    var $form;
    var $labelEditor = true;
    var $curType = 'detail';
    var $fieldEditor = true;

    function getFileType($type, $setType=true){
    	switch($type){
    		case 'EditView':
    		case 'SearchForm': $type= 'edit';break;
    		case 'ListView': $type= 'list';break;
    		default: $type= 'detail';
    	}
    	
    	if($setType){
    		$this->curType = $type;
    	}
    	return $type;
    }

    function getParsers($file){
        if(substr_count($file, 'DetailView.html') > 0 || substr_count($file, 'EditView.html' ) > 0) return array('default'=>'StudioParser', array('StudioParser', 'StudioRowParser'));
        if(substr_count($file, 'ListView.html' ) > 0) return array('default'=>'XTPLListViewParser', array('XTPLListViewParser'));
        return array('default'=>'StudioParser', array('StudioParser'));
    }
    function parseRows($str){
        preg_match_all("'(<tr[^>]*)>(.*?)(</tr[^>]*>)'si", $str, $this->rows,PREG_SET_ORDER);

    }

    function getMaxPosition(){
        $max = 0;
        for($i = 0; $i < count($this->positions) ; $i++){
            if($this->positions[$i][2] >= $max){
                $max = $this->positions[$i][2] + 1;
            }
        }
        return $max;
    }
    function parsePositions($str) {
        preg_match_all("'<span[^>]*sugar=[\'\"]+([a-zA-Z\_]*)([0-9]+)([b]*)[\'\"]+[^>]*>(.*?)</span[ ]*sugar=[\'\"]+[a-zA-Z0-9\_]*[\'\"]+>'si", $str, $this->positions, PREG_SET_ORDER);
    }
    function parseCols($str){
        preg_match_all("'(<td[^>]*?)>(.*?)(</td[^>]*?>)'si", $str, $this->cols,PREG_SET_ORDER);
        
    }
    function parse($str){
        $this->parsePositions($str);
    }
    function positionCount($str) {
        $result = array ();
        return preg_match_all("'<span[^>]*sugar=[\'\"]+([a-zA-Z\_]*)([0-9]+)([b]*)[\'\"]+[^>]*>(.*?)</span[ ]*sugar=[\'\"]+[a-zA-Z0-9\_]*[\'\"]+>'si", $str, $result, PREG_SET_ORDER)/2;
    }
    function rowCount($str) {
        $result = array ();
        return preg_match_all("'(<tr[^>]*>)(.*?)(</tr[^>]*>)'si", $str, $result);
    }

    function loadFile($file) {
        $this->curFile = $file;
        $this->curText = file_get_contents($file);
        $this->form = <<<EOQ
		</form>
		<form name='studio'  method='POST'>
			<input type='hidden' name='action' value='save'>
			<input type='hidden' name='module' value='Studio'>
			
EOQ;

    }
    function buildImageButtons($buttons,$horizontal=true){
        $text = '<table cellspacing=2><tr>';
        foreach($buttons as $button){
            if(!$horizontal){
                $text .= '</tr><tr>';
            }
            if(!empty($button['plain'])){
                $text .= <<<EOQ
	             <td valign='center' {$button['actionScript']}>
EOQ;

            }else{


                $text .= <<<EOQ
	           <td valign='center' class='button' style='cursor:default' onmousedown='this.className="buttonOn";return false;' onmouseup='this.className="button"' onmouseout='this.className="button"' {$button['actionScript']} >
EOQ;
}
$text .= "{$button['image']}&nbsp;{$button['text']}</td>";
        }
        $text .= '</tr></table>';
        return $text;
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
         $buttons[] = array('image'=>$imageAddField,'text'=>$GLOBALS['mod_strings']['LBL_BTN_ADDCUSTOMFIELD'],'actionScript'=>"onclick='studiopopup.display();return false;'" );
        $buttons[] = array('image'=>$imageAddRows,'text'=>$GLOBALS['mod_strings']['LBL_BTN_ADDROWS'],'actionScript'=>"onclick='if(!confirmNoSave())return false;document.location.href=\"index.php?module=Studio&action=EditLayout&parser=StudioRowParser\"'" ,);
         $buttons[] = array('image'=>$imageAddRows,'text'=>$GLOBALS['mod_strings']['LBL_BTN_TABINDEX'],'actionScript'=>"onclick='if(!confirmNoSave())return false;document.location.href=\"index.php?module=Studio&action=EditLayout&parser=TabIndexParser\"'" ,);
        $buttons[] = array('image'=>'', 'text'=>'-', 'actionScript'=>'', 'plain'=>true);
        
        $buttons[] = array('image'=>$imageSave,'text'=>$GLOBALS['mod_strings']['LBL_BTN_SAVE'],'actionScript'=>"onclick='studiojs.save(\"studio\", false);'");
        $buttons[] = array('image'=>$imagePublish,'text'=>$GLOBALS['mod_strings']['LBL_BTN_SAVEPUBLISH'],'actionScript'=>"onclick='studiojs.save(\"studio\", true);'");
        $buttons[] = array('image'=>$imageHistory,'text'=>$GLOBALS['mod_strings']['LBL_BTN_HISTORY'],'actionScript'=>"onclick='if(!confirmNoSave())return false;document.location.href=\"index.php?module=Studio&action=wizard&wizard=ManageBackups&setFile={$_SESSION['studio']['selectedFileId']}\"'");
        return $buttons;
    }
    function getFormButtons(){
        $buttons = $this->generateButtons();
        return $this->buildImageButtons($buttons);
    }
    function getForm(){
        return $this->form  . <<<EOQ
		</form>
		
		
EOQ;

}



function getFiles($module, $fileId=false){
	if(empty($GLOBALS['studioDefs'][$module])){
		require_once('modules/'. $module . '/metadata/studio.php');
	}
	if($fileId){
		return 	$GLOBALS['studioDefs'][$module][$fileId];
	}
	return $GLOBALS['studioDefs'][$module];
}


function getWorkingFile($file, $refresh = false){
	$workingFile = 'working/' . $file;
	$customFile = create_custom_directory($workingFile);
	if($refresh || !file_exists($customFile)){
		copy($file, $customFile);
	}	
	return $customFile;
}

function getSwapWith($value){
    return $value * 2 - 1;
}

function handleSave() {
	$fileDef = $this->getFiles($_SESSION['studio']['module'], $_SESSION['studio']['selectedFileId']);
	$type = $this->getFileType($fileDef['type']);
    $view = $this->curText;
    $counter = 0;
    $return_view = '';
    $slotCount = 0;
    $slotLookup = array();
    for ($i = 0; $i < sizeof($this->positions); $i ++) {
        //used for reverse lookups to figure out where the associated slot is
        $slotLookup[$this->positions[$i][2]][$this->positions[$i][3]] = array('position'=>$i, 'value'=>$this->positions[$i][4]);
    }
    
    $customFields = $this->focus->custom_fields->getAllBeanFieldsView($type, 'html');
    //now we set it to the new values
    
    for ($i = 0; $i < sizeof($this->positions); $i ++) {
        $slot = $this->positions[$i];

        if (empty($slot[3])) {
            $slotCount ++;

            //if the value in the request doesn't equal our current slot then something should be done
            if(isset($_REQUEST['slot_'.$slotCount]) && $_REQUEST['slot_'.$slotCount] != $slotCount){

                $swapValue = $_REQUEST['slot_'.$slotCount] ;
                //if its an int then its a simple swap
                if(is_numeric($swapValue)){

                    $swapWith = $this->positions[$this->getSwapWith($swapValue)];

                    //label
                    $slotLookup[$slot[2]]['']['value'] = $this->positions[ $slotLookup[$swapWith[2]]['']['position']][4];
                    //html
                    $slotLookup[$slot[2]]['b']['value'] = $this->positions[ $slotLookup[$swapWith[2]]['b']['position']][4];
                }
                //now check if its a delete action
                if(strcmp('add:delete', $swapValue) == 0){
                    //label
                    $slotLookup[$slot[2]][$slot[3]]['value'] = '&nbsp;';
                    //html
                    $slotLookup[$slot[2]]['b']['value'] = '&nbsp;';
                }else{

                //now handle the adding of custom fields
                if(substr_count($swapValue, 'add:')){
                    $addfield = explode('add:', $_REQUEST['slot_'.$slotCount], 2);
                    
                    //label
                    $slotLookup[$slot[2]][$slot[3]]['value'] = $customFields[$addfield[1]]['label'] ;
                    //html
                    $slotLookup[$slot[2]]['b']['value'] = $customFields[$addfield[1]]['html'];

                }
                }
            }
        }
    }

    for ($i = 0; $i < sizeof($this->positions); $i ++) {
        $slot = $this->positions[$i];
        $explode = explode($slot[0], $view, 2);
        $explode[0] .= "<span sugar='". $slot[1] . $slot[2]. $slot[3]. "'>";
        $explode[1] = "</span sugar='" .$slot[1] ."'>".$explode[1];

        $return_view .= $explode[0].$slotLookup[$slot[2]][$slot[3]]['value'];
        $view = $explode[1];
        $counter ++;
    }
    $return_view .= $view;
  
    $this->saveFile('', $return_view);
    return $return_view;
}



function saveFile($file = '', $contents = false) {
    if (empty ($file)) {
        $file = $this->curFile;
    }
    $fp = fopen($file, 'w');
    if ($contents) {
        fwrite($fp, $contents);
    } else {
        fwrite($fp, $this->curText);
    }
    fclose($fp);

}

function handleSaveLabels($module_name, $language){
    $the_strings = return_module_language($language, $module_name);
    foreach($_REQUEST as $key=>$value){
        if(substr_count($key, 'label_') == 1 && strcmp($value, 'no_change') != 0){
            $key = substr($key, 6);
            //if(isset($the_strings[$key])){
                create_field_label($module_name, $language, $key, $value, true);
            //}
        }
    }
}

/**
	 * UTIL FUNCTIONS
	 */
/**
	 * STATIC FUNCTION DISABLE INPUTS IN AN HTML STRING
	 * 
	 */
function disableInputs($str) {
    $match = array ("'(<input)([^>]*>)'si" => "\$1 disabled readonly $2",
    "'(<input)([^>]*?type[ ]*=[ ]*[\'\"]submit[\'\"])([^>]*>)'si" => "\$1 disabled readonly style=\"display:none\" $2",
     "'(<select)([^>]*)'si" => "\$1 disabled readonly $2",
    // "'<a .*>(.*)</a[^>]*>'siU"=>"\$1",
"'(href[\ ]*=[\ ]*)([\'])([^\']*)([\'])'si" => "href=\$2javascript:void(0);\$2 alt=\$2\$3\$2", "'(href[\ ]*=[\ ]*)([\"])([^\"]*)([\"])'si" => "href=\$2javascript:void(0)\$2 title=\$2\$3\$2");
    return preg_replace(array_keys($match), array_values($match), $str);
}

function enableLabelEditor($str) {
    global $image_path;
    $image = get_image($image_path . 'edit_inline', "onclick='studiojs.handleLabelClick(\"$2\", 1);' onmouseover='this.style.cursor=\"default\"'");
    $match = array ("'>[^<]*\{(MOD.)([^}]*)\}'si" => "$image<span id='label$2' onclick='studiojs.handleLabelClick(\"$2\", 2);' >\{$1$2}</span><span id='span$2' style='display:none'><input type='text' id='$2' name='$2' msi='label' value='\{$1$2}' onblur='studiojs.endLabelEdit(\"$2\")'></span>");
    $keys = array_keys($match);
    $matches = array();
    preg_match_all($keys[0], $str, $matches, PREG_SET_ORDER);
    foreach($matches as $labelmatch){
        $label_name = 'label_' . $labelmatch[2];
        $this->form .= "\n<input type='hidden' name='$label_name'  id='$label_name' value='no_change'>";

    }
    return preg_replace(array_keys($match), array_values($match), $str);
}



function writeToCache($file, $view, $preview_file=false) {
    if (!is_writable($file)) {
        echo "<br><span style='color:red'>Warning: $file is not writeable. Please make sure it is writeable before continuing</span><br><br>";
    }
	
    if(!$preview_file){
        $file_cache = create_cache_directory('studio/'.$file);
    }else{
        $file_cache = create_cache_directory('studio/'.$preview_file);
    }
    $fp = fopen($file_cache, 'w');
    $view = $this->disableInputs($view);
    if(!$preview_file){
        $view = $this->enableLabelEditor($view);
    }
    fwrite($fp, $view);
    fclose($fp);
    return $this->cacheXTPL($file, $file_cache, $preview_file);
}

function populateRequestFromBuffer($file) {
    $results = array ();
    $temp = fopen($file, 'r');
    $buffer = fread($temp, filesize($file));
    fclose($temp);
    preg_match_all("'name[\ ]*=[\ ]*[\']([^\']*)\''si", $buffer, $results);
    $res = $results[1];
    foreach ($res as $r) {
        $_REQUEST[$r] = $r;
    }
    preg_match_all("'name[\ ]*=[\ ]*[\"]([^\"]*)\"'si", $buffer, $results);
    $res = $results[1];
    foreach ($res as $r) {
        $_REQUEST[$r] = $r;
    }

    $_REQUEST['query'] = true;
    $_REQUEST['advanced'] = true;

}
function cacheXTPL($file, $cache_file, $preview_file = false) {
    global $beanList;
    //now if we have a backup_file lets use that instead of the original
    if($preview_file){
        $file  = $preview_file;
    }
    
    if(!isset($the_module))$the_module = $_SESSION['studio']['module'];
	$files = StudioParser::getFiles($the_module);
	$xtpl = $files[$_SESSION['studio']['selectedFileId']]['php_file'];
	$originalFile = $files[$_SESSION['studio']['selectedFileId']]['template_file'];
	$type = StudioParser::getFileType($files[$_SESSION['studio']['selectedFileId']]['type']);
    $xtpl_fp = fopen($xtpl, 'r');
    $buffer = fread($xtpl_fp, filesize($xtpl));
    fclose($xtpl_fp);
    $cache_file = create_cache_directory('studio/'.$file);
    $xtpl_cache = create_cache_directory('studio/'.$xtpl);
    $module = $this->workingModule;

    $form_string = "require_once('modules/".$module."/Forms.php');";
   
    if ($type == 'edit' || $type == 'detail') {
        if (empty ($_REQUEST['record'])) {
            $buffer = preg_replace('(\$xtpl[\ ]*=)', "\$focus->assign_display_fields('$module'); \$0", $buffer);
        } else {
            $buffer = preg_replace('(\$xtpl[\ ]*=)', "\$focus->retrieve('".$_REQUEST['record']."');\n\$focus->assign_display_fields('$module');\n \$0", $buffer);
        }
    }
    $_REQUEST['query'] = true;
    if (substr_count($file, 'SearchForm') > 0) {
        $temp_xtpl = new XTemplate($file);
        if ($temp_xtpl->exists('advanced')) {

            global $current_language, $beanFiles, $beanList;
            $mods = return_module_language($current_language, 'DynamicLayout');
            $class_name = $beanList[$module];
            require_once ($beanFiles[$class_name]);
            $mod = new $class_name ();

            $this->populateRequestFromBuffer($file);
            $mod->assign_display_fields($module);
            $buffer = str_replace(array ('echo $lv->display();','$search_form->parse("advanced");', '$search_form->out("advanced");', '$search_form->parse("main");', '$search_form->out("main");'), '', $buffer);
            $buffer = str_replace('echo get_form_footer();', '$search_form->parse("main");'."\n".'$search_form->out("main");'."\necho '<br><b>".translate('LBL_ADVANCED', 'DynamicLayout')."</b><br>';".'$search_form->parse("advanced");'."\n".'$search_form->out("advanced");'."\necho get_form_footer();\n \$sugar_config['list_max_entries_per_page'] = 1;", $buffer);
        }
    }else{
    	
    	 if ($type == 'detail') {
        $buffer = str_replace('header(', 'if(false) header(', $buffer);
    }
    }

    $buffer = str_replace($originalFile, $cache_file, $buffer);
    $buffer = "<?php\n\$sugar_config['list_max_entries_per_page'] = 1;\n ?>".$buffer;

    $buffer = str_replace($form_string, '', $buffer);
    $buffer = $this->disableInputs($buffer);
    $xtpl_fp_cache = fopen($xtpl_cache, 'w');
    fwrite($xtpl_fp_cache, $buffer);
    fclose($xtpl_fp_cache);
    return $xtpl_cache;
}

/**
	 * Yahoo Drag & Drop Support
	 */
////<script type="text/javascript" src="modules/Studio/studio.js" ></script>
function yahooJS() {
    $custom_module = $_SESSION['studio']['module'];
    $custom_type = $this->curType;
    return<<<EOQ
	 	<style type='text/css'>
.slot {
	border-width:1px;border-color:#999999;border-style:solid;padding:0px 1px 0px 1px;margin:2px;cursor:move;

}

.slotB {
	border-width:0;cursor:move;

}
</style>

<!-- Namespace source file -->
		
			<script type="text/javascript" src="modules/Studio/JSTransaction.js" ></script>
			<script>
			var jstransaction = new JSTransaction();
			</script>
			
			<!-- Drag and Drop source file -->
			<script src = "include/javascript/yui/dragdrop.js" ></script>
				 	
			<script type="text/javascript" src="modules/Studio/studiodd.js" ></script>	
			<script type="text/javascript" src="modules/Studio/studio.js" ></script>	
			<script>
			
			var gLogger = new ygLogger("Studio");
		    

			 var yahooSlots = [];
			function dragDropInit(){
			
					if (typeof(ygLogger) != "undefined") {
				//ygLogger.init(document.getElementById("logDiv"));
			}
				YAHOO.util.DDM.mode = YAHOO.util.DDM.POINT;
				
				 gLogger.debug("point mode");
				for(mj = 0; mj <= $this->yahooSlotCount; mj++){
					yahooSlots["slot" + mj] = new ygDDSlot("slot" + mj, "studio");
				}
				for(mj = 0; mj < dyn_field_count; mj++){
					yahooSlots["dyn_field_" + mj] = new ygDDSlot("dyn_field_" + mj, "studio");
				}
				  // initPointMode();
				  yahooSlots['s_field_delete'] =  new YAHOO.util.DDTarget("s_field_delete", 'studio');
			}
			YAHOO.util.Event.addListener(window, "load", dragDropInit);
			var custom_module = '$custom_module';
			var custom_view = '$custom_type';
			
			</script>			
			
EOQ;

}

/**
	 * delete:-1
	 * add:2000
	 * swap: 0 - 1999
	 *
	 */
function addSlotToForm($slot_count, $display_count){
    $this->form .= "\n<input type='hidden' name='slot_$slot_count'  id='slot_$display_count' value='$slot_count'>";
}
function prepSlots() {
    $view = $this->curText;
    $counter = 0;
    $return_view = '';
    $slotCount = 0;
    for ($i = 0; $i < sizeof($this->positions); $i ++) {
        $slot = $this->positions[$i];
        $class = '';
      
        if (empty($this->positions[$i][3])) {
            $slotCount ++;
            $class = " class='slot' ";
             $displayCount = $slotCount. $this->positions[$i][3];
            $this->addSlotToForm($slotCount, $displayCount);
        }else{
        	  $displayCount = $slotCount. $this->positions[$i][3];
        }	


        $explode = explode($slot[0], $view, 2);
        $style = '';
        $explode[0] .= "<div id = 'slot$displayCount'  $class style='cursor: move$style'>";
        $explode[1] = "</div>".$explode[1];
        $return_view .= $explode[0].$slot[4];
        $view = $explode[1];
        $counter ++;
    }
    $this->yahooSlotCount = $slotCount;
    $newView = $return_view.$view;
    $newView = str_replace(array ('<slot>', '</slot>'), array ('', ''), $newView);

    return $newView;
}



function clearWorkingDirectory(){

     $file = 'custom/working/';
      if(file_exists($file)){
      	
            rmdir_recursive($file);
        }
       
        return true;
    
}


/**
	 * UPGRADE TO SMARTY
	 */
function upgradeToSmarty() {
    return str_replace('{', '{$', $this->curText);
}
}
?>
