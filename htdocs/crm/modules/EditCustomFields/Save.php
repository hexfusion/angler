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
$label = '';
if(isset($_REQUEST['label']))$label = $_REQUEST['label'];
$ext1 = '';
if(!empty($_REQUEST['ext1'])){		
	$ext1 = $_REQUEST['ext1'];
}
$ext2 = '';
if(!empty($_REQUEST['ext2'])){		
	$ext2 = $_REQUEST['ext2'];
}
$ext3 = '';
if(!empty($_REQUEST['ext3'])){		
	$ext3 = $_REQUEST['ext3'];
}
$max_size = '255';
if(!empty($_REQUEST['max_size'])){		
	$max_size = $_REQUEST['max_size'];
}
$required_opt = 'optional';
if(isset($_REQUEST['required_option'])){
	$required_opt = 'required';
}
$default_value = '';
if(isset($_REQUEST['default_value'])){
	$default_value = $_REQUEST['default_value'];
}

$audit_value=0;

if(isset($_REQUEST['audited'])){
	$audit_value = 1;
 
}
$mass_update = 0;
if(isset($_REQUEST['mass_update'])){
	$mass_update = 1;

}
$id = '';
if(isset($_REQUEST['id']))$id = $_REQUEST['id'];
if(empty($id)){
	
	$custom_fields->addField($_REQUEST['name'],$label, $_REQUEST['data_type'],$max_size,$required_opt, $default_value, $ext1, $ext2, $ext3,$audit_value, $mass_update ,$_REQUEST['duplicate_merge']);
}else{
	$custom_fields->updateField($id, array('max_size'=>$max_size,'required_option'=>$required_opt, 'default_value'=>$default_value, 'audited'=>$audit_value, 'mass_update'=>$mass_update,'duplicate_merge'=>$_REQUEST['duplicate_merge'])); 
}
if($_REQUEST['style'] == 'popup'){
	$name = $_REQUEST['name'];
	$html = $custom_fields->getFieldHTML($name, $_REQUEST['file_type']);

	set_register_value('dyn_layout', 'field_counter', $_REQUEST['field_count']);
	$label = $custom_fields->getFieldLabelHTML($name, $_REQUEST['data_type']);
	require_once('modules/DynamicLayout/AddField.php');
	$af = new AddField();
	$af->add_field($name, $html,$label, 'window.opener.');
	echo $af->get_script('window.opener.');
	echo "\n<script>window.close();</script>";
}else{
	header("Location: index.php?action=index&module=EditCustomFields&module_name=" . $_REQUEST['module_name']);
}

?>
