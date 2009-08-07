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
 * $Id: Audit.php,v 1.22 2006/06/06 17:57:55 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/




require_once('data/SugarBean.php');
require_once('modules/Audit/field_assoc.php');

class Audit extends SugarBean {
	var $module_dir = "Audit";

	var $object_name = "Audit";


	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array();

	function Audit() {
		parent::SugarBean();



	}

	var $new_schema = true;

	function get_summary_text()
	{
		return $this->name;
	}

	function create_list_query($order_by, $where, $show_deleted=0)
	{
	}

    function create_export_query(&$order_by, &$where)
    {
    }

	function fill_in_additional_list_fields()
	{
	}

	function fill_in_additional_detail_fields()
	{
	}

	function fill_in_additional_parent_fields()
	{
	}

	function get_list_view_data()
    {
	}

    function get_audit_link()
    {

    }

   function get_audit_list()
    {
        global $focus, $genericAssocFieldsArray, $moduleAssocFieldsArray, $current_user, $timedate, $app_strings;

        $audit_list = array();
        if(!empty($_REQUEST['record'])) {
   			$result = $focus->retrieve($_REQUEST['record']);

    	if($result == null || !$focus->ACLAccess('', $focus->isOwner($current_user->id)))
    		{
    			sugar_die($app_strings['ERROR_NO_RECORD']);
    		}
		}

        if($focus->is_AuditEnabled()){
            $order= ' order by '.$focus->get_audit_table_name().'.date_created desc' ;//order by contacts_audit.date_created desc
            $query = "SELECT ".$focus->get_audit_table_name().".*, users.user_name FROM ".$focus->get_audit_table_name().", users WHERE ".$focus->get_audit_table_name().".created_by = users.id AND ".$focus->get_audit_table_name().".parent_id = '$focus->id'".$order;

		    $result = $focus->db->query($query);
            //if($focus->db->getRowCount($result) > 0){
                // We have some data.
                require('metadata/audit_templateMetaData.php');
                $fieldDefs = $dictionary['audit']['fields'];
			    while (($row = $focus->db->fetchByAssoc($result))!= null) {

                    $temp_list = array();

                    foreach($fieldDefs as $field){
					        if(isset($row[$field['name']])) {
                                if(($field['name'] == 'before_value_string' || $field['name'] == 'after_value_string') &&
                                	(array_key_exists($row['field_name'], $genericAssocFieldsArray) || (!empty($moduleAssocFieldsArray[$focus->object_name]) && array_key_exists($row['field_name'], $moduleAssocFieldsArray[$focus->object_name])) )
                                   ){

                                $temp_list[$field['name']] = Audit::getAssociatedFieldName($row['field_name'], $row[$field['name']]);
                                }
                                else{
                                    $temp_list[$field['name']] = $row[$field['name']];
                                }
                                if ($field['name'] == 'date_created') {
                                	$temp_list[$field['name']]=$timedate->to_display_date_time($temp_list[$field['name']]);
                                }
								 if(($field['name'] == 'before_value_string' || $field['name'] == 'after_value_string') && $row['data_type']="enum")
								 {
									global $app_list_strings;
									if(isset($focus->field_defs[$row['field_name']]['options'])) {
										$domain = $focus->field_defs[$row['field_name']]['options'];
										if(isset($app_list_strings[$domain][$temp_list[$field['name']]]))
											$temp_list[$field['name']] = $app_list_strings[$domain][$temp_list[$field['name']]];
									}
								 }
								 elseif($field['name'] == 'field_name')
								 {
									global $mod_strings;
									if(isset($focus->field_defs[$row['field_name']]['vname'])) {
										$label = $focus->field_defs[$row['field_name']]['vname'];
										$temp_list[$field['name']] = translate($label, $focus->module_dir);
									}
								}
                        }
                    }

                    $temp_list['created_by'] = $row['user_name'];
                    $audit_list[] = $temp_list;
                }
            //}
        }
        return $audit_list;
    }

    function getAssociatedFieldName($fieldName, $fieldValue){
    global $focus,  $genericAssocFieldsArray, $moduleAssocFieldsArray;

        if(!empty($moduleAssocFieldsArray[$focus->object_name]) && array_key_exists($fieldName, $moduleAssocFieldsArray[$focus->object_name])){
        $assocFieldsArray =  $moduleAssocFieldsArray[$focus->object_name];

        }
        else if(array_key_exists($fieldName, $genericAssocFieldsArray)){
            $assocFieldsArray =  $genericAssocFieldsArray;
        }
        else{
            return $fieldValue;
        }
        $query = "";
        $field_arr = $assocFieldsArray[$fieldName];
        $query = "SELECT ";
        if(is_array($field_arr['select_field_name'])){
        	$count = count($field_arr['select_field_name']);
            $index = 1;
            foreach($field_arr['select_field_name'] as $col){
            	$query .= $col;
            	if($index < $count){
            		$query .= ", ";
            	}
            	$index++;
            }
         }
         else{
           	$query .= $field_arr['select_field_name'];
         }

         $query .= " FROM ".$field_arr['table_name']." WHERE ".$field_arr['select_field_join']." = '".$fieldValue."'";

         $result = $focus->db->query($query);
         if(!empty($result)){
         	if($row = $focus->db->fetchByAssoc($result)){
                if(is_array($field_arr['select_field_name'])){
                	$returnVal = "";
                	foreach($field_arr['select_field_name'] as $col){
            			$returnVal .= $row[$col]." ";
            		}
            		return $returnVal;
            	}
                else{
                   	return $row[$field_arr['select_field_name']];
                }
            }
        }
    }
}
?>
