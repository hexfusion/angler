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
 * $Id: Save.php,v 1.20 2006/06/06 17:57:58 majed Exp $
 * Description:  Base Form For Notes
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('modules/Documents/Document.php');

require_once('include/formbase.php');
require_once('include/upload_file.php');
require_once('modules/DocumentRevisions/DocumentRevision.php');

global $mod_strings;
$mod_strings = return_module_language($current_language, 'Documents');

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
	
$Document = populateFromPost($prefix, $Document);

if (!isset($_POST[$prefix.'is_template'])) $Document->is_template = 0;
else $Document->is_template = 1;

$upload_file = new UploadFile('uploadfile');

$do_final_move = 0;

//$_FILES['uploadfile']['name'] = $_REQUEST['escaped_document_name'];
if (isset($_FILES['uploadfile']) && $upload_file->confirm_upload())
{
    $Revision->filename = $upload_file->get_stored_file_name();
    $Revision->file_mime_type = $upload_file->mime_type;
	$Revision->file_ext = $upload_file->file_ext;
 	$do_final_move = 1;
} else {
	if (!empty($_REQUEST['old_id'])) {
		
		//populate the document revision based on the old_id
		$old_revision = new DocumentRevision();
		$old_revision->retrieve($_REQUEST['old_id']);

    	$Revision->filename = $old_revision->filename;
    	$Revision->file_mime_type = $old_revision->file_mime_type;
		$Revision->file_ext = $old_revision->file_ext;
	}
}

if (isset($Document->id)) {
	//save document
	$return_id = $Document->save();
} else {
	//save document
	$return_id = $Document->save();

	//save revision.
	$Revision->change_log = $mod_strings['DEF_CREATE_LOG'];
	$Revision->revision = $Document->revision;
	$Revision->document_id = $Document->id;
	$Revision->save();
	
	//update document with latest revision id
	$Document->process_save_dates=false; //make sure that conversion does not happen again.
	$Document->document_revision_id = $Revision->id;
	$Document->save();
	
	//set relationship field values if parent_id is passed.
	//this happens only in the new mode because document edit view
	//does not have a way to change the parent.
	//also we want to stamp the document revision only once.
	if (!empty($_POST['parent_id']) && !empty($_POST['parent_type'])) {

		$save_revision['document_revision_id']=$Document->document_revision_id;	
		switch (strtolower($_POST['parent_type'])) {
		
			case "contracts" :
				$Document->load_relationship('contracts');
				$Document->contracts->add($_POST['parent_id'],$save_revision);
				break;
			
			//todo remove leads case.
			case "leads" :
				$Document->load_relationship('leads');
				$Document->leads->add($_POST['parent_id'],$save_revision);
				break;
		}	
	}
	//after loading the signed document, delete the relationship between
	//the template and the contract.
	if ((isset($_POST['load_signed_id']) and !empty($_POST['load_signed_id']))) {
		$query="update linked_documents set deleted=1 where id='".$_POST['load_signed_id']."'";
		$Document->db->query($query);
	}
}

if ($do_final_move) {
	$upload_file->final_move($Revision->id);
}
else if ( ! empty($_REQUEST['old_id'])) {
   	$upload_file->duplicate_file($_REQUEST['old_id'], $Revision->id, $Revision->filename);
}

$GLOBALS['log']->debug("Saved record with id of ".$return_id);
handleRedirect($return_id, "Documents");
?>
