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
 * $Id: en_us.lang.php,v 1.23 2006/08/09 21:49:45 awu Exp $
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
	//module
	'LBL_MODULE_NAME' => 'Documents',
	'LBL_MODULE_TITLE' => 'Documents: Home',
	'LNK_NEW_DOCUMENT' => 'Create Document',
	'LNK_DOCUMENT_LIST'=> 'Documents List',
	'LBL_DOC_REV_HEADER' => 'Document Revisions',
	'LBL_SEARCH_FORM_TITLE'=> 'Document Search',
	//vardef labels
	'LBL_DOCUMENT_ID' => 'Document Id',	
	'LBL_NAME' => 'Document Name',
	'LBL_DESCRIPTION' => 'Description',
	'LBL_CATEGORY' => 'Category',
	'LBL_SUBCATEGORY' => 'Sub Category',
	'LBL_STATUS' => 'Status', 
	'LBL_CREATED_BY'=> 'Created by',
	'LBL_DATE_ENTERED'=> 'Date Entered',
	'LBL_DATE_MODIFIED'=> 'Date Modified',
	'LBL_DELETED' => 'Deleted',
	'LBL_MODIFIED'=> 'Modified by',
	'LBL_CREATED'=> 'Created by',
	'LBL_RELATED_DOCUMENT_ID'=>'Related Dcocument Id',
	'LBL_RELATED_DOCUMENT_REVISION_ID'=>'Related Document Revision Id',
	'LBL_IS_TEMPLATE'=>'Is a Template',
	'LBL_TEMPLATE_TYPE'=>'Document Type',
	
	'LBL_REVISION_NAME' => 'Revision Number',
	'LBL_FILENAME' => 'Filename',
	'LBL_MIME' => 'Mime Type',
	'LBL_REVISION' => 'Revision',
	'LBL_DOCUMENT' => 'Related Document',
	'LBL_LATEST_REVISION' => 'Latest Revision',
	'LBL_CHANGE_LOG'=> 'Change Log',
	'LBL_ACTIVE_DATE'=> 'Publish Date',
	'LBL_EXPIRATION_DATE' => 'Expiration Date',
	'LBL_FILE_EXTENSION'  => 'File Extension',
	
	'LBL_CAT_OR_SUBCAT_UNSPEC'=>'Unspecified',
	//document edit and detail view
	'LBL_DOC_NAME' => 'Document Name:',
	'LBL_FILENAME' => 'File Name:',
	'LBL_DOC_VERSION' => 'Revision:',
	'LBL_CATEGORY_VALUE' => 'Category:',
	'LBL_SUBCATEGORY_VALUE'=> 'Sub Category:',
	'LBL_DOC_STATUS'=> 'Status:',
	'LBL_LAST_REV_CREATOR' => 'Revision Created By:',
	'LBL_LAST_REV_DATE' => 'Revision Date:',
	'LBL_DOWNNLOAD_FILE'=> 'Download File:',
	'LBL_DET_RELATED_DOCUMENT'=>'Related Document:',
	'LBL_DET_RELATED_DOCUMENT_VERSION'=>"Related Document's Revision:",
	'LBL_DET_IS_TEMPLATE'=>'Template? :',
	'LBL_DET_TEMPLATE_TYPE'=>'Document Type:',



	'LBL_DOC_DESCRIPTION'=>'Description:',
	'LBL_DOC_ACTIVE_DATE'=> 'Publish Date:',
	'LBL_DOC_EXP_DATE'=> 'Expiration Date:',
	
	//document list view.	
	'LBL_LIST_FORM_TITLE' => 'Document List',	
	'LBL_LIST_DOCUMENT' => 'Document',
	'LBL_LIST_CATEGORY' => 'Category',
	'LBL_LIST_SUBCATEGORY' => 'Sub Category',
	'LBL_LIST_REVISION' => 'Revision',
	'LBL_LIST_LAST_REV_CREATOR' => 'Published By',
	'LBL_LIST_LAST_REV_DATE' => 'Revision Date',
	'LBL_LIST_VIEW_DOCUMENT'=>'View',
    'LBL_LIST_DOWNLOAD'=> 'Download',    
	'LBL_LIST_ACTIVE_DATE' => 'Publish Date',
	'LBL_LIST_EXP_DATE' => 'Expiration Date',
	'LBL_LIST_STATUS'=>'Status',
	
	//document search form.
	'LBL_SF_DOCUMENT' => 'Document Name:',
	'LBL_SF_CATEGORY' => 'Category:',
	'LBL_SF_SUBCATEGORY'=> 'Sub Category:',
	'LBL_SF_ACTIVE_DATE' => 'Publish Date:',
	'LBL_SF_EXP_DATE'=> 'Expiration Date:',
	
	'DEF_CREATE_LOG' => 'Document Created',
	
	//error messages
	'ERR_DOC_NAME'=>'Document Name',
	'ERR_DOC_ACTIVE_DATE'=>'Publish Date',
	'ERR_DOC_EXP_DATE'=> 'Expiration Date',
	'ERR_FILENAME'=> 'File name',
	'ERR_DOC_VERSION'=> 'Document Version',
	'ERR_DELETE_CONFIRM'=> 'Do you want to delete this document revision?',
	'ERR_DELETE_LATEST_VERSION'=> 'You are not allowed to delete the latest revision of a document.',
	'LNK_NEW_MAIL_MERGE' => 'Mail Merge',
	'LBL_MAIL_MERGE_DOCUMENT' => 'Mail Merge Template:',
	
	'LBL_TREE_TITLE' => 'Documents',
	//sub-panel vardefs.
	'LBL_LIST_DOCUMENT_NAME'=>'Document Name',
	'LBL_CONTRACT_NAME'=>'Contract Name:',
	'LBL_LIST_IS_TEMPLATE'=>'Template?',
	'LBL_LIST_TEMPLATE_TYPE'=>'Document Type',
	'LBL_LIST_SELECTED_REVISION'=>'Selected Revision',
	'LBL_LIST_LATEST_REVISION'=>'Latest Revision',
	'LBL_CONTRACTS_SUBPANEL_TITLE'=>'Related Contracts',
    //'LNK_DOCUMENT_CAT'=>'Document Categories',
);


?>
