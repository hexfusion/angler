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
 * $Id: DeleteRelationship.php,v 1.13 2006/06/06 17:57:52 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/*
 Removes Relationships, input is a form POST

ARGS:
 $_REQUEST['module']; : the module associated with this Bean instance (will be used to get the class name)
 $_REQUEST['record']; : the id of the Bean instance
 $_REQUEST['linked_field']; : the linked field name of the Parent Bean
 $_REQUEST['linked_id']; : the id of the Related Bean instance to

 $_REQUEST['return_url']; : the URL to redirect to
  or use:
  1) $_REQUEST['return_id']; : 
  2) $_REQUEST['return_module']; : 
  3) $_REQUEST['return_action']; : 
*/
//_ppd($_REQUEST);

require_once('include/utils.php');
require_once('include/formbase.php'); 

 global $beanFiles,$beanList;
 $bean_name = $beanList[$_REQUEST['module']];
 require_once($beanFiles[$bean_name]);
 $focus = new $bean_name();
 if (  empty($_REQUEST['linked_id']) || empty($_REQUEST['linked_field'])  || empty($_REQUEST['record']))
 {
	die("need linked_field, linked_id and record fields");
 }
 $linked_field = $_REQUEST['linked_field'];
 $record = $_REQUEST['record'];
 $linked_id = $_REQUEST['linked_id'];
 if($bean_name == 'Team')
 {
 	$focus->retrieve($record);
 	$focus->remove_user_from_team($linked_id);
 }
 else
 {
 	// cut it off:
 	$focus->load_relationship($linked_field);
 	$focus->$linked_field->delete($record,$linked_id);
 }
 if ($bean_name == 'Campaign' and $linked_field=='prospectlists' ) {
 	
 	$query="SELECT email_marketing_prospect_lists.id from email_marketing_prospect_lists ";
 	$query.=" left join email_marketing on email_marketing.id=email_marketing_prospect_lists.email_marketing_id";
	$query.=" where email_marketing.campaign_id='$record'";
 	$query.=" and email_marketing_prospect_lists.prospect_list_id='$linked_id'";

 	$result=$focus->db->query($query);
	while (($row=$focus->db->fetchByAssoc($result)) != null) {
			$del_query =" update email_marketing_prospect_lists set email_marketing_prospect_lists.deleted=1, email_marketing_prospect_lists.date_modified=".db_convert("'".gmdate("Y-m-d H:i:s",time())."'",'datetime');
 			$del_query.=" WHERE  email_marketing_prospect_lists.id='{$row['id']}'";
 			_pp($del_query);
		 	$focus->db->query($del_query);
	}
 	$focus->db->query($query);
 }
$GLOBALS['log']->debug("deleted relationship: bean: $bean_name, linked_field: $linked_field, linked_id:$linked_id" );
if(empty($_REQUEST['refresh_page'])){
	handleRedirect();
}
exit;
?>
