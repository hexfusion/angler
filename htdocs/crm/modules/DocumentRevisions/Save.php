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
 * $Id: Save.php,v 1.3 2006/06/06 17:57:58 majed Exp $
 * Description:  Base Form For Notes
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
require_once('modules/Documents/Document.php');
require_once('modules/DocumentRevisions/DocumentRevision.php');

require_once('include/formbase.php');
require_once('include/upload_file.php');

global $mod_strings;
$mod_strings = return_module_language($current_language, 'DocumentRevisions');

$prefix='';

$do_final_move = 0;

$Revision = new DocumentRevision();
$Document = new Document();
if (isset($_REQUEST['record'])) {
	$Document->retrieve($_REQUEST['record']);
}
if(!$Document->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
}
if (isset($_REQUEST['SaveRevision'])) {
	
	//fetch the document record.
	$Document->retrieve($_REQUEST['return_id']);
	
	if($useRequired &&  !checkRequired($prefix, array_keys($Revision->required_fields))){
		return null;
	}

	$Revision = populateFromPost($prefix, $Revision);
	$upload_file = new UploadFile('uploadfile');
	if (isset($_FILES['uploadfile']) && $upload_file->confirm_upload())
	{
        $Revision->filename = $upload_file->get_stored_file_name();
        $Revision->file_mime_type = $upload_file->mime_type;
		$Revision->file_ext = $upload_file->file_ext;
  	 	  	 	
  	 	$do_final_move = 1;
	}
	
	//save revision
	$Revision->document_id = $_REQUEST['return_id'];
	$Revision->save();

	//revsion is the document.	
	$Document->document_revision_id = $Revision->id;
	$Document->save();
	$return_id = $Document->id;
} 

if ($do_final_move)
{
   	 $upload_file->final_move($Revision->id);
}
else if ( ! empty($_REQUEST['old_id']))
{
   	 $upload_file->duplicate_file($_REQUEST['old_id'], $Revision->id, $Revision->filename);
}

$GLOBALS['log']->debug("Saved record with id of ".$return_id);
handleRedirect($return_id, "Documents");
?>
