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
$error_defs = array(
'no_error'=>array('number'=>0 , 'name'=>'No Error', 'description'=>'No Error'),
'invalid_login'=>array('number'=>10 , 'name'=>'Invalid Login', 'description'=>'Login attempt failed please check the username and password'),
'invalid_session'=>array('number'=>11 , 'name'=>'Invalid Session ID', 'description'=>'The session ID is invalid'),
'user_not_configure'=>array('number'=>12 , 'name'=>'User Not Configured', 'description'=>'Please log into your instance of SugarCRM to configure your user. '),
'no_portal'=>array('number'=>12 , 'name'=>'Invalid Portal Client', 'description'=>'Portal Client does not have authorized access'),
'no_module'=>array('number'=>20 , 'name'=>'Module Does Not Exist', 'description'=>'This module is not available on this server'),
'no_file'=>array('number'=>21 , 'name'=>'File Does Not Exist', 'description'=>'The desired file does not exist on the server'),
'no_module_support'=>array('number'=>30 , 'name'=>'Module Not Supported', 'description'=>'This module does not support this feature'),
'no_relationship_support'=>array('number'=>31 , 'name'=>'Relationship Not Supported', 'description'=>'This module does not support this relationship'),
'no_access'=>array('number'=>40 , 'name'=>'Access Denied', 'description'=>'You do not have access'),
'duplicates'=>array('number'=>50 , 'name'=>'Duplicate Records', 'description'=>'Duplicate records have been found. Please be more specific.'),
'no_records'=>array('number'=>51 , 'name'=>'No Records', 'description'=>'No records were found.'),
'cannot_add_client'=>array('number'=>52 , 'name'=>'Cannot Add Offline Client', 'description'=>'Unable to add Offline Client.'),
'client_deactivated'=>array('number'=>53 , 'name'=>'Client Deactivated', 'description'=>'Your Offline Client instance has been deactivated.  Please contact your Administrator in order to resolve.'),
'sessions_exceeded'=>array('number'=>60 , 'name'=>'Number of sessions exceeded.'),

)



?>
