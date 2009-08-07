 <!DOCTYPE html PUBLIC "-//IETF//DTD HTML 2.0//EN">
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
 * $Header: /var/cvsroot/sugarcrm/modules/EmailTemplates/EditView.php,v 1.33 2006/06/16 15:55:20 eddy Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/EmailTemplates/EmailTemplate.php');
require_once('modules/EmailTemplates/Forms.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$focus = new EmailTemplate();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

$old_id = '';
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
    $old_id = $focus->id; // for attachments down below
    $focus->id = "";
}



//setting default flag value so due date and time not required
if(!isset($focus->id)) $focus->date_due_flag = 'on';

//needed when creating a new case with default values passed in
if(isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
    $focus->contact_name = $_REQUEST['contact_name'];
}
if(isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
    $focus->contact_id = $_REQUEST['contact_id'];
}
if(isset($_REQUEST['parent_name']) && is_null($focus->parent_name)) {
    $focus->parent_name = $_REQUEST['parent_name'];
}
if(isset($_REQUEST['parent_id']) && is_null($focus->parent_id)) {
    $focus->parent_id = $_REQUEST['parent_id'];
}
if(isset($_REQUEST['parent_type'])) {
    $focus->parent_type = $_REQUEST['parent_type'];
}
elseif(!isset($focus->parent_type)) {
    $focus->parent_type = $app_list_strings['record_type_default_key'];
}
if(isset($_REQUEST['filename']) && $_REQUEST['isDuplicate'] != 'true') {
        $focus->filename = $_REQUEST['filename'];
}


echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].": ".$focus->name, true); 
echo "\n</p>\n";
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$GLOBALS['log']->info("EmailTemplate detail view");

$xtpl=new XTemplate ('modules/EmailTemplates/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("LBL_ACCOUNT",$app_list_strings['moduleList']['Accounts']);
$xtpl->parse("main.variable_option");

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
// handle Create $module then Cancel
if(empty($_REQUEST['return_id'])) {
    $xtpl->assign("RETURN_ACTION", 'index');
}
$cancel_script="this.form.action.value='{$_REQUEST['return_action']}'; this.form.module.value='{$_REQUEST['return_module']}';
this.form.record.value=";
if(empty($_REQUEST['return_id'])) {
    $cancel_script ="'index'"; 
} else {
    $cancel_script.="'{$_REQUEST['return_id']}'";
}

$xtpl->assign("CANCEL_SCRIPT", $cancel_script);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
if(isset($focus->name)) $xtpl->assign("NAME", $focus->name); else $xtpl->assign("NAME", "");
if(isset($focus->description)) $xtpl->assign("DESCRIPTION", $focus->description); else $xtpl->assign("DESCRIPTION", "");
if(isset($focus->subject)) $xtpl->assign("SUBJECT", $focus->subject); else $xtpl->assign("SUBJECT", "");
if( $focus->published == 'on')
{
$xtpl->assign("PUBLISHED","CHECKED");
}







include_once('modules/Contacts/Contact.php');
include_once('modules/Prospects/Prospect.php');
include_once('modules/Leads/Lead.php');

$contact = new Contact();
$lead = new Lead();
$prospect = new Prospect();

$fields = array();

$field_defs_js = "var field_defs = {'Contacts':[";
foreach($contact->field_defs as $field_def)
{
    if( ( $field_def['type'] == 'relate' && empty($field_def['custom_type']) ) 
    	|| $field_def['type'] == 'assigned_user_name' || $field_def['type'] =='link')
    {
        continue;
    }

 $field_def['vname'] = preg_replace('/:$/','',translate($field_def['vname'],'Contacts'));
 $temp_Value = "{name:'contact_".$field_def['name']."',value:'". $field_def['vname']."'}";

//Build Value string for comparison, push to array only if the value does not exist already 
 $temp_Value = "{name:'contact_".$field_def['name']."',value:'". $field_def['vname']."'}";
  if (! in_array($temp_Value, $fields)){
 	array_push($fields,$temp_Value);
  }
}

//build the Prospects portion of the fields.  Note we are still prepending with contact_, in order to have
//duplicate values.  We will then filter out values at end of building array.
foreach($prospect->field_defs as $field_def)
{	
    if( ( $field_def['type'] == 'relate' && empty($field_def['custom_type']) ) 
    	|| $field_def['type'] == 'assigned_user_name' || $field_def['type'] =='link')
    {
        continue;
    }
 $field_def['vname'] = preg_replace('/:$/','',translate($field_def['vname'],'Prospects'));

//Build Value string for comparison, push to array only if the value does not exist already 
 $temp_Value = "{name:'contact_".$field_def['name']."',value:'". $field_def['vname']."'}";
  if (! in_array($temp_Value, $fields)){
 	array_push($fields,$temp_Value);
  }
}

//build the Leads portion of the fields.  Note we are still prepending with contact_, in order to have
//duplicate values.  We will then filter out values at end of building array.
foreach($lead->field_defs as $field_def)
{
    if( ( $field_def['type'] == 'relate' && empty($field_def['custom_type']) ) 
    	|| $field_def['type'] == 'assigned_user_name' || $field_def['type'] =='link')
    {
        continue;
    }

 $field_def['vname'] = preg_replace('/:$/','',translate($field_def['vname'],'Leads'));

//Build Value string for comparison, push to array only if the value does not exist already 
 $temp_Value = "{name:'contact_".$field_def['name']."',value:'". $field_def['vname']."'}";
  if (! in_array($temp_Value, $fields)){
 	array_push($fields,$temp_Value);
  }
}

$field_defs_js .= implode(",\n",$fields);
$field_defs_js .= "],";

$field_defs_js .= "'Accounts':[";
include_once('modules/Accounts/Account.php');
$account = new Account();
$fields = array();
foreach($account->field_defs as $field_def)
{
    if( ( $field_def['type'] == 'relate' && empty($field_def['custom_type']) ) || $field_def['type'] == 'assigned_user_name' || 
    $field_def['type'] =='link') {
        continue;
    }

 $field_def['vname'] = preg_replace('/:$/','',translate($field_def['vname'],'Accounts'));
 array_push($fields,"{name:'account_".$field_def['name']."',value:'". $field_def['vname']."'}");
}
$field_defs_js .= implode(",\n",$fields);
$field_defs_js .= "]};";
$xtpl->assign("FIELD_DEFS_JS", $field_defs_js );
$xtpl->assign("LBL_CONTACT",$app_list_strings['moduleList']['Contacts']);

global $current_user;
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])) { 
    $record = '';
    if(!empty($_REQUEST['record'])) {
        $record =   $_REQUEST['record'];
    }
    $xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=" . $_REQUEST['action']
	."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".get_image($image_path."EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");    
}
if(isset($focus->parent_type) && $focus->parent_type != "") {
    $change_parent_button = "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_SELECT_BUTTON_KEY']."'
tabindex='3' type='button' class='button' value='".$app_strings['LBL_SELECT_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return
window.open(\"index.php?module=\"+ document.EditView.parent_type.value +
\"&action=Popup&html=Popup_picker&form=TasksEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
    $xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
}
if($focus->parent_type == "Account") {
	$xtpl->assign("DEFAULT_SEARCH","&query=true&account_id=$focus->parent_id&account_name=".urlencode($focus->parent_name));
}

$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['record_type_display'], $focus->parent_type));
$xtpl->assign("DEFAULT_MODULE","Accounts");

if(isset($focus->body)) $xtpl->assign("BODY", $focus->body); else $xtpl->assign("BODY", "");
if(isset($focus->body_html)) $xtpl->assign("BODY_HTML", $focus->body_html); else $xtpl->assign("BODY_HTML", "");


if( file_exists("include/FCKeditor/fckeditor.php"))
{
  include("include/FCKeditor_Sugar/FCKeditor_Sugar.php") ;
  ob_start();
  $instancename='body_html';
  $oFCKeditor = new FCKeditor_Sugar($instancename) ;
  if( !empty($focus->body_html)) {
    $oFCKeditor->Value = $focus->body_html ;
  }
  $oFCKeditor->Create() ;
  $htmlarea_src =  ob_get_contents();
  $xtpl->assign("HTMLAREA",$htmlarea_src);
  $xtpl->parse("main.htmlarea");
  ob_end_clean();

 echo <<<EOQ
	  <SCRIPT>
	  function insert_variable_html(text) {
	  	var oEditor = FCKeditorAPI.GetInstance('{$instancename}') ;
	  	oEditor.InsertHtml(text);
	  }
	  function insert_variable_html_link(text) {
	  	var oEditor = FCKeditorAPI.GetInstance('{$instancename}') ;
	  	thelink="<a href='" + text + "''" + ">{$mod_strings['LBL_DEFAULT_LINK_TEXT']}</a>";
	  	oEditor.InsertHtml(thelink);
	  }	  
	  </SCRIPT>
EOQ;
	  $xtpl->assign("INSERT_VARIABLE_ONCLICK", "insert_variable_html(document.EditView.variable_text.value)");
  $xtpl->parse("main.variable_button");

	///////////////////////////////////////
	////    ATTACHMENTS
	$attachments = '';
	if(!empty($focus->id)) {
	    $etid = $focus->id;
	} elseif(!empty($old_id)) {
	    $xtpl->assign('OLD_ID', $old_id);
	    $etid = $old_id;
	}
	if(!empty($etid)) {
	    $note = new Note();
	    $where = "notes.parent_id='{$etid}' AND notes.filename IS NOT NULL";
	    $notes_list = $note->get_full_list("", $where,true);
	
	    if(!isset($notes_list)) {
	        $notes_list = array();
	    }
	    for($i = 0;$i < count($notes_list);$i++) {
	        $the_note = $notes_list[$i];
	        if( empty($the_note->filename)) {
	            continue;
	        }
	        $attachments .= '<input type="checkbox" name="remove_attachment[]" value="'.$the_note->id.'"> '.$app_strings['LNK_REMOVE'].'&nbsp;&nbsp;';
	        $attachments .= '<a href="'.UploadFile::get_url($the_note->filename,$the_note->id).'" target="_blank">'. $the_note->filename .'</a><br>';
	    }
	}
	$attJs  = '<script type="text/javascript">';
	$attJs .= 'var file_path = "'.$sugar_config['site_url'].'/'.$sugar_config['upload_dir'].'";';
	$attJs .= 'var lnk_remove = "'.$app_strings['LNK_REMOVE'].'";';
	$attJs .= '</script>';
	$xtpl->assign('ATTACHMENTS', $attachments);
	$xtpl->assign('ATTACHMENTS_JAVASCRIPT', $attJs);

	////    END ATTACHMENTS
	///////////////////////////////////////

}
else {
  $xtpl->parse("main.textarea");
}

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

$xtpl->parse("main");

$xtpl->out("main");
require_once('include/javascript/javascript.php');
$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript();
?>
