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
if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");

$add_index=array();
$drop_index=array();
$change_index=array();

function compare($focus,$db_indexes, $var_indexes) {
	global $add_index,  $drop_index,$change_index;
	foreach ($var_indexes as $var_i_name=>$var_i_def) {
		//find  corresponding db index with same name
		//else by columns in the index.
		$sel_db_index=null;
		$var_fields_string= implode('',$var_i_def['fields']);
		$field_list_match=false;
		if (isset($db_indexes[$var_i_name])) {
			$sel_db_index=$db_indexes[$var_i_name];	
			$db_fields_string=implode('',$db_indexes[$var_i_name]['fields']);
			if (strcasecmp($var_fields_string , $db_fields_string)==0) {
				$field_list_match=true;
			}
					
		} else {
			//search by column list.
			foreach ($db_indexes as $db_i_name=>$db_i_def) {
				$db_fields_string=implode('',$db_i_def['fields']);
				if (strcasecmp($var_fields_string , $db_fields_string)==0) {
					$sel_db_index=$db_indexes[$db_i_name];			
					$field_list_match=true;
					break;
				}
			}
		}    

		//no matching index in database.  
		if (empty($sel_db_index)) {
            $add_index[]=$focus->db->helper->add_drop_constraint($focus->table_name,$var_i_def);
            continue;
		}
		if (!$field_list_match) {
			//drop the db index and create new index based on vardef
            $drop_index[]=$focus->db->helper->add_drop_constraint($focus->table_name,$sel_db_index,true);
            $add_index[]=$focus->db->helper->add_drop_constraint($focus->table_name,$var_i_def);
            continue;
		}
		//check for name match.
		//it should not occur for indexes of type primary or unique.
		if ( $var_i_def['type'] != 'primary' and  $var_i_def['type'] != 'unique' and $var_i_def['name'] != $sel_db_index['name']) {
			//rename index.
            $rename=$focus->db->helper->rename_index($sel_db_index,$var_i_def,$focus->table_name);
            if (is_array($rename)) {
                $change_index=array_merge($change_index,$rename);
            } else {
                $change_index[]=$rename;
            }
            continue;
		}
	}	
}

global $current_user,$beanFiles,$dictionary;
set_time_limit(3600);

include_once ('include/database/DBManager.php');
$db = & DBManager::getInstance();
$processesd_tables=array();
foreach ($beanFiles as $beanname=>$beanpath) {

	require_once($beanpath);
	$focus= new $beanname();

    //skips beans based on same tables. user, employee and group are an example.
    if (isset($processesd_tables[$focus->table_name])) {        
        continue;
    } else {
        $processesd_tables[$focus->table_name]=$focus->table_name;
    }
	if (!empty($dictionary[$focus->object_name]['indices'])) {
		$indices=$dictionary[$focus->object_name]['indices'];
	} else  {
		$indices=array();
	}	

	//clean vardef defintions.. removed indexes not value for this dbtype.
	//set index name as the key.
	$var_indices=array();
	foreach ($indices as $definition) {
		if (empty($definition['db']) or  $definition['db'] == $focus->db->dbType) {
			$var_indices[$definition['name']] = $definition;
		}
	}

	$db_indices=$focus->db->helper->get_indices($focus->table_name);

	compare($focus,$db_indices,$var_indices);
}

if ((count($drop_index) > 0 or count($add_index) > 0 or count($change_index) > 0)) {
    global $sugar_config;

    if (!isset($_REQUEST['mode']) or $_REQUEST['mode'] != 'execute' ) {
        echo "<BR><BR><BR>";
        echo "<a href='index.php?module=Administration&action=RepairIndex&mode=execute'>Execute Script</a>";
    }

    $focus = new Account();
    if (count($drop_index) > 0)  {
        if (isset($_REQUEST['mode']) and $_REQUEST['mode']=='execute') {
            echo "<BR>Dropping constraints/indexes.";
            foreach ($drop_index as $statement) {
                echo "<BR> Executing ".$statement;
                $focus->db->query($statement);
            }
        } else {
            echo "<BR>Drop these constraints/indexes.";
            foreach ($drop_index as $statement) {
                echo "<BR>".$statement.";";
            }
        }        
    }

    if (count($add_index) > 0)  {
        if (isset($_REQUEST['mode']) and $_REQUEST['mode']=='execute') {
            echo "<BR>Adding constraints/indexes.";
            foreach ($add_index as $statement) {
                echo "<BR> Executing ".$statement;
                $focus->db->query($statement);
            }
        } else {
            echo "<BR><BR>Add these constraints/indexes.";
            foreach ($add_index as $statement) {
                echo "<BR>".$statement.";";
            }
        }
    }
    if (count($change_index) > 0)  {
        if (isset($_REQUEST['mode']) and $_REQUEST['mode']=='execute') {
            echo "<BR>Altering constraints/indexes.";
            foreach ($change_index as $statement) {
                echo "<BR> Executing ".$statement;
                $focus->db->query($statement);
            }
        } else {
            echo "<BR><BR>Alter these constraints/indexes.";
            foreach ($change_index as $statement) {
                echo "<BR>".$statement.";";
            }
        }
    }
    
    if (!isset($_REQUEST['mode']) or $_REQUEST['mode'] != 'execute') {
        echo "<BR><BR><BR>";
        echo "<a href='index.php?module=Administration&action=RepairIndex&mode=execute'>Execute Script</a>";
    }
} else {
    echo "<BR><BR><BR> Index definitions are in sync.";
}
?>
