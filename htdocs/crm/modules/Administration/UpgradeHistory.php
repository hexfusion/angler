<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * UpgradeHistory class definition file
 *
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

// $Id: UpgradeHistory.php,v 1.14 2006/06/06 17:57:55 majed Exp $




require_once('data/SugarBean.php');

// The history of upgrades on the system
class UpgradeHistory extends SugarBean
{
	var $new_schema = true;
	var $module_dir = 'Administration';
	
	// Stored fields
	var $id;
	var $filename;
	var $md5sum;
	var $type;
	var $version;
	var $status;
	var $date_entered;
	
	var $table_name = "upgrade_history";
	var $object_name = "UpgradeHistory";
	var $column_fields = Array( "id", "filename", "md5sum", "type", "version", "status", "date_entered" );

	function delete()
	{
		$this->dbManager->deleteSQL( "delete from " . $this->table_name . " where id = '" . $this->id . "'" );
	}
	
	function UpgradeHistory()
	{
		parent::SugarBean();
        $this->disable_row_level_security = true;
	}
    
    function getAllOrderBy($orderBy){
        $query = "SELECT id FROM " . $this->table_name . " ORDER BY ".$orderBy; 
        return $this->getList($query);
    }

	function getAll()
	{
		$query = "SELECT id FROM " . $this->table_name . " ORDER BY date_entered desc";
		return $this->getList($query);
	}
    
    function getList($query){
        return( parent::build_related_list( $query, $this ) );    
    }
	
	function findByMd5( $var_md5 )
	{
		$query = "SELECT id FROM " . $this->table_name . " where md5sum = '$var_md5'";
		return( parent::build_related_list( $query, $this ) );
	}
	
	function UninstallAvailable($patch_list, $patch_to_check)
	{
		foreach($patch_list as $more_recent_patch)
		{
			if($more_recent_patch->id == $patch_to_check->id)
				break;
			
			$patch_to_check_backup_path    = clean_path(remove_file_extension(from_html($patch_to_check->filename))).'-restore';
			$more_recent_patch_backup_path = clean_path(remove_file_extension(from_html($more_recent_patch->filename))).'-restore';
			
			if($this->foundConflict($patch_to_check_backup_path, $more_recent_patch_backup_path) &&
				($more_recent_patch->date_entered >= $patch_to_check->date_entered))
			{
				return false;
			}
		}
		
		return true;
	}

	function foundConflict($check_path, $recent_path)
	{
		if(is_file($check_path))
		{
			if(file_exists($recent_path))
				return true;
			else
				return false;
		}
		elseif(is_dir($check_path))
		{
			$status = false;
			
			$d = dir( $check_path );
			while( $f = $d->read() )
			{
				if( $f == "." || $f == ".." )
					continue;
				
				$status = $this->foundConflict("$check_path/$f", "$recent_path/$f");
				
				if($status)
					break;
			}
			
			$d->close();
			return( $status );
		}
		
		return false;
	}
}
?>
