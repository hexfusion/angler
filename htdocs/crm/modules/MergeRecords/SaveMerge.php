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
/*********************************************************************************
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('modules/MergeRecords/MergeRecord.php');
require_once('log4php/LoggerManager.php');

$focus = new MergeRecord();
$focus->load_merge_bean($_REQUEST['merge_module'], true, $_REQUEST['record']);

foreach($focus->merge_bean->column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$value = $_POST[$field];
		$focus->merge_bean->$field = $value;
	}
}

foreach($focus->merge_bean->additional_column_fields as $field)
{
	if(isset($_POST[$field]))
	{
		$value = $_POST[$field];
		$focus->merge_bean->$field = $value;
	}
}
global $check_notify;
$focus->merge_bean->save($check_notify);
$return_id = $focus->merge_bean->id;
$return_module = $focus->merge_module;
$return_action = 'DetailView';

//handle realated data.

$linked_fields=$focus->merge_bean->get_linked_fields();
if (is_array($_POST['merged_ids'])) {
    foreach ($_POST['merged_ids'] as $id) {
        require_once ($focus->merge_bean_file_path);
        $mergesource = new $focus->merge_bean_class();
        $mergesource->retrieve($id);        
        
        foreach ($linked_fields as $name => $properties) {
            if (isset($properties['duplicate_merge'])) {
                if ($properties['duplicate_merge']=='disabled' or $properties['duplicate_merge']=='false') {
                    continue;
                }
            }
            if ($mergesource->load_relationship($name)) {
                $data=$mergesource->$name->get();
                if (is_array($data)) {
                    if ($focus->merge_bean->load_relationship($name) ) {
                        foreach ($data as $related_id) {
                            //add to primary bean
                            $focus->merge_bean->$name->add($related_id);
                        }   
                    }
                }
            }
        }
        //delete the child bean, this action will cascade into related data too.
        $mergesource->mark_deleted($mergesource->id);
    }
}
$GLOBALS['log']->debug("Merged record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
