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
 * $Id: Delete.php,v 1.14 2006/06/06 17:57:58 majed Exp $
 * Description:  Deletes an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/DocumentRevisions/DocumentRevision.php');
require_once('modules/Documents/Document.php');

global $mod_strings;



if(!isset($_REQUEST['record']))
	sugar_die($mod_strings['ERR_DELETE_RECORD']);
$focus = new Document();
$focus->retrieve($_REQUEST['record']);
if(!$focus->ACLAccess('Delete')){
	ACLController::displayNoAccess(true);
	sugar_cleanup(true);
}
if (isset($_REQUEST['object']) && $_REQUEST['object']="documentrevision") {
	//delete document revision.
	$focus = new DocumentRevision();
	
	UploadFile::unlink_file($_REQUEST['revision_id'],$_REQUEST['filename']);
	
} else {
	//delete document and its revisions.
	$focus = new Document();
	$focus->retrieve($_REQUEST['record']);

	$focus->load_relationships('revisions');	
	$revisions= $focus->get_linked_beans('revisions','DocumentRevision');

	if (!empty($revisions) && is_array($revisions)) {
		foreach($revisions as $key=>$thisversion) {
			UploadFile::unlink_file($thisversion->id,$thisversion->filename);
			//mark the version deleted.
			$thisversion->mark_deleted($thisversion->id);
		}				
	}
}

$focus->mark_deleted($_REQUEST['record']);

header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
?>
