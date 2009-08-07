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
require_once('modules/DynamicFields/DynamicField.php');
require_once('modules/Studio/parsers/StudioParser.php');
global $beanList, $beanFiles;
//this was added to address problems in oracle when creating a custom field with
//upper case characters in column name.
if (!empty($_REQUEST['name'])) {
	$_REQUEST['name']=strtolower($_REQUEST['name']);
}

$module = $_REQUEST['module_name'];
$custom_fields = new DynamicField($module);
if(!empty($module)){
			if(!isset($beanList[$module])){
				if(isset($beanList[ucfirst($module)]))
				$module = ucfirst($module);
			}
			$class_name = $beanList[$module];
			require_once($beanFiles[$class_name]);
			$mod = new $class_name();
			$custom_fields->setup($mod);
}else{
	echo "\nNo Module Included Could Not Save";	
}

$label = (!empty($_REQUEST['label']))?   $_REQUEST['label']: '';
$ext1 = (!empty($_REQUEST['ext1']))?   $_REQUEST['ext1']: '';
$ext2 = (!empty($_REQUEST['ext2']))?   $_REQUEST['ext2']: '';
$ext3 = (!empty($_REQUEST['ext3']))?   $_REQUEST['ext3']: '';
$ext4 = (!empty($_REQUEST['ext4']))?   $_REQUEST['ext4']: '';
$help = (!empty($_REQUEST['help']))?   $_REQUEST['help']: '';
$max_size = (!empty($_REQUEST['max_size']))?   $_REQUEST['max_size']: '';
$default_value = (!empty($_REQUEST['default_value']))?   $_REQUEST['default_value']: '';
$audit_value = (!empty($_REQUEST['audited']))?  1: 0;
$mass_update = (!empty($_REQUEST['mass_update']))?  1: 0;
$required_opt = (!empty($_REQUEST['required_option']))?  'required': 'optional';
$id = (!empty($_REQUEST['id']))?   $_REQUEST['id']: '';


if(empty($id)){

	$custom_fields->addField($_REQUEST['name'],$label, $_REQUEST['data_type'],$max_size,$required_opt, $default_value, $ext1, $ext2, $ext3,$audit_value, $mass_update ,$ext4, $help,$_REQUEST['duplicate_merge']);
}else{
   $values = array('max_size'=>$max_size,'required_option'=>$required_opt, 'default_value'=>$default_value, 'audited'=>$audit_value, 'mass_update'=>$mass_update, 'ext4'=>$ext4, 'help'=>$help,'duplicate_merge'=>$_REQUEST['duplicate_merge'],);
   if(!empty($ext1)){
       $values['ext1'] = $ext1;
   }
	$custom_fields->updateField($id, $values); 
}


if(!empty($_REQUEST['popup'])){
    ob_clean();
    
$name = $custom_fields->getDBName($_REQUEST['name']);
    $files = StudioParser::getFiles($module);
    $view = StudioParser::getFileType($files[$_SESSION['studio']['selectedFileId']]['type']);
    $custom_fields->avail_fields = array();
    $custom_fields->getAvailableFields(true);
    $field = $custom_fields->getField($name);
    $custom_fields->bean->field_defs[$name] = $field->get_field_def();
$custom_fields->bean->field_defs[$name]['type'] = $custom_fields->bean->field_defs[$name]['custom_type'];
	$html= $custom_fields->getAllBeanFieldsView($view,'html');
	
	$html = $html[$name];
    

	
 
	$string = '[NAME]'. $name.'[TYPE]' . $html['fieldType'] . '[LABEL]' . translate(str_replace(array('{', '}', 'MOD.', 'mod.', 'APP.', 'app.'), '', $html['label']), $module);
$string .= '[DATA]'.$html['html'];

echo str_replace(array('{', '}', 'MOD.', 'mod.', 'APP.', 'app.'), '', $string);
sugar_cleanup(true);
}else{
    
	header("Location: index.php?module=Studio&action=wizard&wizard=EditCustomFieldsWizard&option=ViewCustomFields");
    sugar_cleanup(true);
}

?>
