<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * MassUpdate for ListViews
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

// $Id: MassUpdate.php,v 1.94 2006/08/09 21:43:47 liliya Exp $

class MassUpdate
{
	var $sugarbean = null;
	
	function setSugarBean(&$sugar)
	{
		$this->sugarbean = $sugar;
	}

	function getDisplayMassUpdateForm($bool, $multi_select_popup = false)
	{
		
		require_once('include/formbase.php');
		
		if(!$multi_select_popup) 
			$form = '<form action="index.php" method="post" name="displayMassUpdate" id="displayMassUpdate">' . "\n";
		else 
			$form = '<form action="index.php" method="post" name="MassUpdate" id="MassUpdate">' . "\n";
				
		if($bool)
		{
			$form .= '<input type="hidden" name="mu" value="false" />' . "\n";
		}
		else
		{
			$form .= '<input type="hidden" name="mu" value="true" />' . "\n";
		}
		
		$form .= getAnyToForm('mu');
		if(!$multi_select_popup) $form .= "</form>\n";
		
		return $form;
	}

	function getMassUpdateFormHeader($multi_select_popup = false)
	{
		global $sugar_version, $sugar_config;
		if($multi_select_popup) $tempString = '';
		else $tempString = '<script type="text/javascript" src="include/javascript/popup_parent_helper.js?s=' . $sugar_version . '&c=' . $sugar_config['js_custom_version'] . '"></script>'
								. "<form action='index.php' method='post' name='MassUpdate'  id=\"MassUpdate\" onsubmit=\"return check_form('MassUpdate');\">\n"
								. "<input type='hidden' name='action' value='{$_REQUEST['action']}' />\n"
								. "<input type='hidden' name='massupdate' value='true' />\n"
								. "<input type='hidden' name='delete' value='false' />\n"
								. "<input type='hidden' name='merge' value='false' />\n";		 
							
		$tempString .= "<input type='hidden' name='module' value='{$_REQUEST['module']}' />\n";
		return $tempString;
	}

function handleMassUpdate(){
	if(!is_array($this->sugarbean) && $this->sugarbean->bean_implements('ACL') && !ACLController::checkAccess($this->sugarbean->module_dir, 'edit', true)){
		
	}
	require_once('include/formbase.php');
	global $current_user, $db;
	
	foreach($_POST as $post=>$value){
			if(empty($value)){
				unset($_POST[$post]);
			}
	}

	if(!empty($_REQUEST['uid'])) $_POST['mass'] = explode(',', $_REQUEST['uid']); // coming from listview
	elseif(isset($_REQUEST['entire'])) {
		if(isset($_SESSION['export_where']) && !empty($_SESSION['export_where'])) { // bug 4679
			$where = $_SESSION['export_where'];
		} else {
			$where = '';
		}
		if(empty($order_by))$order_by = '';
		$query = $this->sugarbean->create_export_query($order_by,$where);
		
		$result = $db->query($query,true);
	
		$new_arr = array();
		while($val = $db->fetchByAssoc($result,-1,false))
		{
			array_push($new_arr, $val['id']);
		}
		$_POST['mass'] = $new_arr;
	}
	
	if(isset($_POST['mass']) && is_array($_POST['mass'])){
	foreach($_POST['mass'] as $id){
		if(isset($_POST['Delete'])){
			$this->sugarbean->retrieve($id);
			if($this->sugarbean->ACLAccess('Delete')){
				$this->sugarbean->mark_deleted($id);
			}
		}
		else {
			if($this->sugarbean->object_name == 'Contact' && isset($_POST['Sync'])){ // special for contacts module
				if($_POST['Sync'] == 'true') {
					$this->sugarbean->retrieve($id);
					if($this->sugarbean->ACLAccess('EditView')){
						if($this->sugarbean->object_name == 'Contact'){
							
						 		$this->sugarbean->contacts_users_id = $current_user->id;
						 		$this->sugarbean->save(false);
						}
					}
				}
				elseif($_POST['Sync'] == 'false') {
					$this->sugarbean->retrieve($id);
					if($this->sugarbean->ACLAccess('EditView')){
						if($this->sugarbean->object_name == 'Contact'){
						 		if (!isset($this->sugarbean->users))
								{
				      	  			$this->sugarbean->load_relationship('user_sync');
								}
				      			$this->sugarbean->contacts_users_id = null;
								$this->sugarbean->user_sync->delete($this->sugarbean->id, $current_user->id);
						}	
					}
				}
			}
			$this->sugarbean->retrieve($id);
            









             
			if($this->sugarbean->ACLAccess('EditView')){
				$_POST['record'] = $id;
				$_GET['record'] = $id;
				$_REQUEST['record'] = $id;
				$newbean=$this->sugarbean;
				populateFromPost('', $newbean);
				$newbean->save_from_post = false;
				$check_notify = FALSE;
				
				if (isset( $this->sugarbean->assigned_user_id)) {
					$old_assigned_user_id = $this->sugarbean->assigned_user_id;
					if (!empty($_POST['assigned_user_id']) 
						&& ($old_assigned_user_id != $_POST['assigned_user_id']) 
						&& ($_POST['assigned_user_id'] != $current_user->id)) {
						$check_notify = TRUE;
					}
				}
				$newbean->save($check_notify);
			}	
		}
	  }
	}

}

function getMassUpdateForm(){
	global $app_strings;
	
	if($this->sugarbean->bean_implements('ACL') && !ACLController::checkAccess($this->sugarbean->module_dir, 'edit', true)){
		return '';
	}
	$lang_delete = translate('LBL_DELETE');
	$lang_update = translate('LBL_UPDATE');
	$lang_confirm= translate('NTC_DELETE_CONFIRMATION_MULTIPLE');
	$lang_sync = translate('LBL_SYNC_CONTACT');
    $lang_oc_status = translate('LBL_OC_STATUS');
	$lang_unsync = translate('LBL_UNSYNC');
	$lang_archive = translate('LBL_ARCHIVE');

				
	if(!isset($this->sugarbean->field_defs) || count($this->sugarbean->field_defs) == 0) {
		$html = "<table cellpadding='0' cellspacing='0' border='0' width='100%'><tr><td>";

		if($this->sugarbean->ACLAccess('Delete', true) ){
			$html .= "<input type='submit' name='Delete' value='{$lang_delete}' onclick=\"return confirm('{$lang_confirm}')\" class='button'>";
		}
		$html .= "</td></tr></table>";
		return $html;
	}

	$should_use = false;
	
	$html = get_form_header($app_strings['LBL_MASS_UPDATE'], '', false);
	$html .= "<table cellpadding='0' cellspacing='0' border='0' width='100%'><tr><td style='padding-bottom: 2px;' class='listViewButtons'><input onclick='return sListView.send_mass_update(\"selected\", \"{$app_strings['LBL_LISTVIEW_NO_SELECTED']}\")' type='submit' id='update_button' name='Update' value='{$lang_update}' class='button'>";
	// TODO: allow ACL access for Delete to be set false always for users
	if($this->sugarbean->ACLAccess('Delete', true) && $this->sugarbean->object_name != 'User') { 
		global $app_list_strings;
		$html .=" <input id='delete_button' type='submit' name='Delete' value='{$lang_delete}' onclick='return confirm(\"{$lang_confirm}\") && sListView.send_mass_update(\"selected\", \"{$app_strings['LBL_LISTVIEW_NO_SELECTED']}\", 1)' class='button'>";
	}

	// only for My Inbox views - to allow CSRs to have an "Archive" emails feature to get the email "out" of their inbox.
	if($this->sugarbean->object_name == 'Email' 
		&& (isset($_REQUEST['assigned_user_id']) && !empty($_REQUEST['assigned_user_id']))
		&& (isset($_REQUEST['type']) && !empty($_REQUEST['type']) && $_REQUEST['type'] == 'inbound')) {
		$html .= " <input type='button' name='archive' value='{$lang_archive}' class='button' onClick='setArchived();'>";
	}
	
	$html .= "</td></tr></table><table cellpadding='0' cellspacing='0' border='0' width='100%' class='tabForm'><tr><td><table width='100%' border='0' cellspacing='0' cellpadding='0'>";
	
	$even = true;
    if($this->sugarbean->object_name == 'Contact'){
		$html .= "<tr><td width='15%' class='dataLabel'>$lang_sync</td><td width='35%' class='dataField'><select name='Sync'><option value=''>{$app_strings['LBL_NONE']}</option><option value='false'>{$app_list_strings['checkbox_dom']['2']}</option><option value='true'>{$app_list_strings['checkbox_dom']['1']}</option></select></td>";
		$even = false;
	}







	  static $banned = array('date_modified'=>1, 'date_entered'=>1, 'created_by'=>1, 'modified_user_id'=>1);
      foreach($this->sugarbean->field_defs as $field){
      	
      	if(!isset($banned[$field['name']]) && (!isset($field['massupdate']) || !empty($field['massupdate']))){
      		$newhtml = '';
      		if($even){
      			$newhtml .= "<tr>";
      		}
      		if(isset($field['vname'])){
      			$displayname = translate($field['vname']);
      		}else{
      			$displayname = '';	
      			
      		}
      		if(isset($field['custom_type']))$field['type'] = $field['custom_type'];
			if(isset($field['type']))
			{
      			switch($field["type"]){
      				case "relate": $even = !$even; $newhtml .= $this->handleRelationship($displayname, $field); break;
      				case "parent":$even = !$even; $newhtml .=$this->addParent($displayname, $field); break;
      				case "contact_id":$even = !$even; $newhtml .=$this->addContactID($displayname, $field["name"]); break;
      				case "assigned_user_name":$even = !$even; $newhtml .= $this->addAssignedUserID($displayname,  $field["name"]); break;
      				case "account_id":$even = !$even; $newhtml .= $this->addAccountID($displayname,  $field["name"]); break;
      				case "account_name":$even = !$even; $newhtml .= $this->addAccountID($displayname,  $field["id_name"]); break;
      				case "enum":$even = !$even; $newhtml .= $this->addStatus($displayname,  $field["name"], translate($field["options"])); break;
      				case "date":$even = !$even; $newhtml .= $this->addDate($displayname,  $field["name"]); break;



      			}
			}
      		if($even){
      			$newhtml .="</tr>";
      		}else{
      			$should_use = true;
      		}
      		if(!in_array($newhtml, array('<tr>', '</tr>', '<tr></tr>', '<tr><td></td></tr>'))){
      			$html.=$newhtml;
      		}
      	}
      }
      
      $html .="</table></td></tr></table>";
      
      
      if($should_use){
		return $html;
      }else{
      	if($this->sugarbean->ACLAccess('Delete', true)){
      	return "<table cellpadding='0' cellspacing='0' border='0' width='100%'><tr><td><input type='submit' name='Delete' value='$lang_delete' onclick=\"return confirm('{$lang_confirm}')\" class='button'></td></tr></table>";
      	}else{
      		return '';
      	}
      }
}

function endMassUpdateForm(){
	return '</form>';
}

function handleRelationship($displayname, $field)
{
	$ret_val = '<td></td><td></td>';

	if(isset($field['module']))
	{
		switch($field['module'])
		{
			case 'Accounts':
				$ret_val = $this->addAccountID($displayname, $field['name'], $field['id_name']);
				break;
			case 'Contacts':
				$ret_val = $this->addContactID($displayname, $field['name'], $field['id_name']);
				break;
			default:
				$ret_val = '<td></td><td></td>';
		}
	}

	return $ret_val;
}
function addParent($displayname, $field){
	global $app_strings, $app_list_strings;

	///////////////////////////////////////
	///
	/// SETUP POPUP
	
	$popup_request_data = array(
		'call_back_function' => 'set_return',
		'form_name' => 'MassUpdate',
		'field_to_name_array' => array(
			'id' => "parent_id",
			'name' => "parent_name",
			),
		);
	
	$json = getJSONobj();
	$encoded_popup_request_data = $json->encode($popup_request_data);
	
	//
	///////////////////////////////////////

	$change_parent_button = " <input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."'  type='button' class='button' value='".$app_strings['LBL_SELECT_BUTTON_LABEL']
		."' name='button' onclick='open_popup(document.MassUpdate.{$field['type_name']}.value, 600, 400, \"\", true, false, {$encoded_popup_request_data});'  />";
	$parent_type = $field['parent_type'];
	$types = get_select_options_with_id($app_list_strings[$parent_type], '');
	return "<td width='25%' class='dataField' valign='top'><select name='{$field['type_name']}'>$types</select></td><td width='25%' class='dataField'><input name='{$field['id_name']}' type='hidden' value=''><input name='parent_name' readonly type='text' value=''>$change_parent_button</td>";
}

function addContactID($displayname, $varname, $id_name=''){
		global $app_strings;

		if(empty($id_name))
			$id_name = "contact_id";

		///////////////////////////////////////
		///
		/// SETUP POPUP
		
		$popup_request_data = array(
			'call_back_function' => 'set_return',
			'form_name' => 'MassUpdate',
			'field_to_name_array' => array(
				'id' => "{$id_name}",
				'name' => "{$varname}",
				),
			);
		
		$json = getJSONobj();
		$encoded_popup_request_data = $json->encode($popup_request_data);
		
		//
		///////////////////////////////////////

		return "<td width='15%' class='dataLabel'>$displayname</td><td width='35%' class='dataField'><input name='{$varname}' readonly type='text' value=''><input name='{$id_name}' type='hidden' value=''>&nbsp;<input title=\"{$app_strings['LBL_SELECT_BUTTON_TITLE']}\" accessKey='{$app_strings['LBL_SELECT_BUTTON_KEY']}'  type='button' class='button' value='{$app_strings['LBL_SELECT_BUTTON_LABEL']}' name='button'"
			. " onclick='open_popup(\"Contacts\", 600, 400, \"\", true, false, {$encoded_popup_request_data});' /></td>";
}

function addAccountID($displayname, $varname, $id_name=''){
		global $app_strings;

		$json = getJSONobj();
		
		if(empty($id_name))
			$id_name = "account_id";

		///////////////////////////////////////
		///
		/// SETUP POPUP

		$popup_request_data = array(
			'call_back_function' => 'set_return',
			'form_name' => 'MassUpdate',
			'field_to_name_array' => array(
				'id' => "{$id_name}",
				'name' => "{$varname}",
				),
			);
			
		$encoded_popup_request_data = $json->encode($popup_request_data);
		
		//
		///////////////////////////////////////

		$qsParent = array(  'method' => 'query',
							'modules' => array('Accounts'), 
							'group' => 'or', 
							'field_list' => array('name', 'id'), 
							'populate_list' => array('parent_name', 'parent_id'), 
							'conditions' => array(array('name'=>'name','op'=>'like_custom','end'=>'%','value'=>'')), 
							'order' => 'name', 
							'limit' => '30',
							'no_match_text' => $app_strings['ERR_SQS_NO_MATCH']
							); 
		$qsParent['populate_list'] = array('mass_'. $varname, 'mass_' . $id_name);
		
		$html = '<td class="dataLabel">' . $displayname . " </td>\n"
			. '<td><input class="sqsEnabled" type="text" autocomplete="off" id="mass_' . $varname .'" name="' . $varname . '" value="" /><input id="mass_' . $id_name . '" type="hidden" name="'
			. $id_name . '" value="" />&nbsp;<input type="button" name="btn1" class="button" title="'
			. $app_strings['LBL_SELECT_BUTTON_LABEL'] . '" accesskey="' 
			. $app_strings['LBL_SELECT_BUTTON_KEY'] . '" value="' . $app_strings['LBL_SELECT_BUTTON_LABEL'] . '" onclick='
			. "'open_popup(\"Accounts\",600,400,\"\",true,false,{$encoded_popup_request_data});' /></td>\n";
		$html .= '<script type="text/javascript" language="javascript">sqs_objects[\'mass_' . $varname . '\'] = ' . 
					$json->encode($qsParent) . '; registerSingleSmartInputListener(document.getElementById(\'mass_' . $varname . '\'));
					addToValidateBinaryDependency(\'MassUpdate\', \''.$varname.'\', \'alpha\', false, \'' . $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ACCOUNT'] . '\',\''.$id_name.'\');
					</script>';
		
		return $html;
}















































function addAssignedUserID($displayname, $varname){
	global $app_strings;

	$json = getJSONobj();
	
	$popup_request_data = array(
		'call_back_function' => 'set_return',
		'form_name' => 'MassUpdate',
		'field_to_name_array' => array(
			'id' => 'assigned_user_id',
			'user_name' => 'assigned_user_name',
			),
		);
	$encoded_popup_request_data = $json->encode($popup_request_data);
	$qsUser = array(  'method' => 'get_user_array', // special method  
						'field_list' => array('user_name', 'id'), 
						'populate_list' => array('assigned_user_name', 'assigned_user_id'), 
						'conditions' => array(array('name'=>'user_name','op'=>'like_custom','end'=>'%','value'=>'')),
						'limit' => '30','no_match_text' => $app_strings['ERR_SQS_NO_MATCH']);
						
	$qsUser['populate_list'] = array('mass_assigned_user_name', 'mass_assigned_user_id'); 
	$html = <<<EOQ
		<td width="15%" class="dataLabel">$displayname</td>
		<td class="dataField"><input class="sqsEnabled" autocomplete="off" id="mass_assigned_user_name" name='assigned_user_name' type="text" value=""><input id='mass_assigned_user_id' name='assigned_user_id' type="hidden" value="" />
		<input title="{$app_strings['LBL_SELECT_BUTTON_TITLE']}" accessKey="{$app_strings['LBL_SELECT_BUTTON_KEY']}" type="button" class="button" value='{$app_strings['LBL_SELECT_BUTTON_LABEL']}' name=btn1
				onclick='open_popup("Users", 600, 400, "", true, false, $encoded_popup_request_data);' />
		</td>
EOQ;
	$html .= '<script type="text/javascript" language="javascript">sqs_objects[\'mass_assigned_user_name\'] = ' . 
				$json->encode($qsUser) . '; registerSingleSmartInputListener(document.getElementById(\'mass_assigned_user_name\'));
				addToValidateBinaryDependency(\'MassUpdate\', \'assigned_user_name\', \'alpha\', false, \'' . $app_strings['ERR_SQS_NO_MATCH_FIELD'] . $app_strings['LBL_ASSIGNED_TO'] . '\',\'assigned_user_id\');
				</script>';
	
	return $html;
}

function addStatus($displayname, $varname, $options){
	global $app_strings, $app_list_strings;

	if(!isset($options['']) && !isset($options['0']))
		$options = array_merge(array(''=>''), $options);
	$options = get_select_options_with_id($options, '');

	// cn: added "mass_" to the id tag to diffentieate from the status id in StoreQuery
	$html = '<td class="dataLabel" width="15%">'.$displayname.'</td>
			 <td><select id="mass_'.$varname.'" name="'.$varname.'">'.$options.'</select></td>';
	return $html;
}

function addDate($displayname, $varname){
	global $timedate;
	$userformat = '('. $timedate->get_user_date_format().')';
	$cal_dateformat = $timedate->get_cal_date_format();
	global $app_strings, $app_list_strings, $theme;

	$javascriptend = <<<EOQ
		 <script type="text/javascript">
		Calendar.setup ({
			inputField : "${varname}jscal_field", ifFormat : "$cal_dateformat", showsTime : false, button : "${varname}jscal_trigger", singleClick : true, step : 1
		});
		</script>
EOQ;

	 $html = <<<EOQ
	<td class="dataLabel" width="20%">$displayname</td>
	<td class='dataField' width="30%"><input onblur="parseDate(this, '$cal_dateformat')" type="text" name='$varname' size="12" id='{$varname}jscal_field' maxlength='10' value=""> <img src="themes/$theme/images/jscalendar.gif" id="{$varname}jscal_trigger" align="absmiddle" alt="Select Date">&nbsp;<span class="dateFormat">$userformat</span>$javascriptend</td>
		<script> addToValidate('MassUpdate','$varname','date',false,'$displayname');</script>
EOQ;
return $html;

}
}

?>
