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
 * $Id: field_arrays.php,v 1.6 2006/06/06 17:57:58 majed Exp $
 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['Document'] = array ('column_fields' => Array("id"
		,"document_name"
		,"description"
		,"category_id"
		,"subcategory_id"
		,"status_id"
		,"active_date"
		,"exp_date"
		,"date_entered"
		,"date_modified"
		,"created_by"
		,"modified_user_id"



		,"document_revision_id"
		,"related_doc_id"
		,"related_doc_rev_id"
		,"is_template"
		,"template_type"
		),
        'list_fields' =>  Array("id"
		,"document_name"
		,"description"
		,"category_id"
		,"subcategory_id"
		,"status_id"
		,"active_date"
		,"exp_date"
		,"date_entered"
		,"date_modified"
		,"created_by"
		,"modified_user_id"



		,"document_revision_id"
		,"last_rev_create_date"
		,"last_rev_created_by"
		,"latest_revision"
		,"file_url"
		,"file_url_noimage"
		),
        'required_fields' => Array("document_name"=>1,"active_date"=>1,"revision"=>1),
);
?>
