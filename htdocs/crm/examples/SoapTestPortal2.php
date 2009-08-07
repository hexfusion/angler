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
$portal_name ='';
$portal_password = '';
$user_name ='lead';
$user_password = 'lead';
foreach($_POST as $name=>$value){
		$$name = $value;
}
echo <<<EOQ
<form name='test' method='POST'>
<table width ='800'><tr>
<tr><th colspan='6'>Enter  SugarCRM Portal User Information (to configure this login to SugarCRM as an admin and go the administration panel then select a user from user management)</th></tr>
<td >PORTAL NAME:</td><td><input type='text' name='portal_name' value='$portal_name'></td><td>PORTAL PASSWORD:</td><td><input type='password' name='portal_password' value='$portal_password'></td>
</tr>
<tr><th colspan='6'>Use the name 'lead' and password 'lead' for portal lead generation</th></tr>
<tr>
<td>CONTACT NAME:</td><td><input type='text' name='user_name' value='$user_name'></td></td>
</tr>
<tr><td><input type='submit' value='Submit'></td></tr>
</table>
</form>


EOQ;
if(!empty($portal_name)){
$portal_password = md5($portal_password);
require_once('../include/nusoap/nusoap.php');  //must also have the nusoap code on the ClientSide.
$soapclient = new nusoapclient('http://localhost/sugarcrm/soap.php');  //define the SOAP Client an

echo '<b>LOGIN:</b><BR>';
$result = $soapclient->call('portal_login',array('portal_auth'=>array('user_name'=>$portal_name,'password'=>$portal_password, 'version'=>'.01'),'user_name'=>$user_name, 'application_name'=>'SoapTestPortal'));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
$session = $result['id'];
    

echo '<br><br><b>CREATE LEAD:</b><BR>';
$result = $soapclient->call('portal_set_entry',array('session'=>$session , 'module_name'=>'Leads', 'name_value_list'=>array(array('name'=>'first_name', 'value'=>'Test'), array('name'=>'last_name', 'value'=>'Lead'),  array('name'=>'portal_name', 'value'=>'portal_name'),  array('name'=>'portal_app', 'value'=>'SoapTestPortal'), array('name'=>'description', 'value'=>'A lead created through webservices'))));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);




echo '<br><br><b>LOGOUT:</b><BR>';
$result = $soapclient->call('portal_logout',array('session'=>$session));
echo '<b>HERE IS ERRORS:</b><BR>';
echo $soapclient->error_str;

echo '<BR><BR><b>HERE IS RESPONSE:</b><BR>';
echo $soapclient->response;

echo '<BR><BR><b>HERE IS RESULT:</b><BR>';
echo print_r($result);
}

?>   
