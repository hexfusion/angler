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

 // $Id: StudioFields.php,v 1.4 2006/08/22 20:45:10 awu Exp $

class StudioFields {

	var $fields = array ();
	var $module = false;
	var $fieldCount = 0;
	var $dynFields = array ();
	var $script = '';
	var $existingFields = array();
	function addFields($fileType) {
	    $GLOBALS['loading_studio_fields'] = true;
		if ($this->module) {
			
			$result = $this->module->custom_fields->getAllBeanFieldsView($fileType, 'html');
			foreach ($result as $f_name => $f_field) {
			    
				$this->addField($f_name, $f_field['html'], $f_field['label'], $f_field['fieldType'], $f_field['isCustom'],'', 'studio_fields');
			}
		}
		$GLOBALS['loading_studio_fields'] = false;
		return '<script>'.$this->script . '</script>';
	}

	function quoteCleanup($value) {
		$quote_cleanup = array ('"' => '&qt;', "'" => '&sqt;', "\r\n" => '', "\n" => '', '</script>' => '&lt;/script&gt;');
		return str_replace(array_keys($quote_cleanup), array_values($quote_cleanup), $value);
	}

	function addField($field_name, $field_html, $field_label,$field_type, $is_custom, $prefix = '', $table = 'studio_fields', $use_name_in_add = false) {
		if(empty($this->existingFields[$field_name])){
			$div_id = 'dyn_field_'.$this->fieldCount;
			$dynFields[$div_id] = array ('name' => $field_name, 'html' => $field_html);
			$labels = array();
			preg_match_all("'\{[a-zA-Z]+\.([a-zA-Z\_\-0-9]+)\}'si", $field_html, $labels, PREG_SET_ORDER);
			foreach($labels as $lbl){
				$newlbl = translate($lbl[1], $this->module->module_dir);
				$field_html = str_replace($lbl[0],$newlbl,$field_html);
			}
			$labels = array();
			preg_match_all("'\{[a-zA-Z]+\.([a-zA-Z\_\-0-9]+)\}'si", $field_label, $labels, PREG_SET_ORDER);
			foreach($labels as $lbl){
				$newlbl = translate($lbl[1], $this->module->module_dir);
				$label = str_replace($lbl[0],$newlbl,$field_label);
			}
			
			$field_html = $this->quoteCleanup($field_html);
			$field_html = str_replace(array('{', '}'), '', $field_html);
			$is_custom = ($is_custom)?'true':'false';
			$this->script .= "\n \n".$prefix."addNewField('$div_id', '$field_name', '$label',  '$field_html', '$field_type',$is_custom, '$table');";
			$this->fieldCount++;
		}

	}
	
	function getExistingFields($txt){
		$fields = array();
		preg_match_all("'name[ ]*=[ ]*[\'\"]([a-zA-Z0-9\_]*)[\'\"]'si", $txt, $fields, PREG_SET_ORDER);
		foreach($fields as $field){
			$this->existingFields[$field[1]] = 1;	
		}
	}
}
?>
