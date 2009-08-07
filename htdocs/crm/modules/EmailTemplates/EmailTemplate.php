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
 * $Header: /var/cvsroot/sugarcrm/modules/EmailTemplates/EmailTemplate.php,v 1.53 2006/08/21 17:57:26 roger Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/




require_once('data/SugarBean.php');

// EmailTemplate is used to store email email_template information.
class EmailTemplate extends SugarBean {
	var $field_name_map = array();
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $name;
	var $published;
	var $description;
	var $body;
	var $body_html;
	var $attachments;





	var $table_name = "email_templates";
	var $object_name = "EmailTemplate";
	var $module_dir = "EmailTemplates";
	var $new_schema = true;
	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = array(



									);
	
	function EmailTemplate() {
		parent::SugarBean();








	}

	function get_summary_text() {
		return "$this->name";
	}

	function create_list_query($order_by, $where, $show_deleted=0) {
		$custom_join = $this->custom_fields->getJOIN();
		$query = 'SELECT email_templates.id, email_templates.name, email_templates.description, email_templates.date_modified ';



		if($custom_join) {
   				$query .= $custom_join['select'];
 			}
		$query .= ' FROM email_templates ';
		if($custom_join) {
  				$query .= $custom_join['join'];
			}
		






		$where_auto = '1 = 1';
		if($show_deleted == 0) {
			$where_auto = "email_templates.deleted=0";
		}else if($show_deleted == 1) {
			$where_auto = "email_templates.deleted=1";
		}
			
		if($where != "")
			$query .= "WHERE $where AND ".$where_auto;
		else
			$query .= "WHERE ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY email_templates.name";

		return $query;
	}

	function create_export_query(&$order_by, &$where) {
		return $this->create_list_query($order_by, $where);
	}

	function fill_in_additional_list_fields() {
		$this->fill_in_additional_parent_fields();
	}

	function fill_in_additional_detail_fields() {
		$this->created_by_name = get_assigned_user_name($this->created_by);
		$this->modified_by_name = get_assigned_user_name($this->modified_user_id);
		$this->fill_in_additional_parent_fields();



	}

	function fill_in_additional_parent_fields() {
	}
	
	function get_list_view_data() {
		global $app_list_strings, $focus, $action, $currentModule;
		$fields = $this->get_list_view_array();
		$fields["DATE_MODIFIED"] = substr($fields["DATE_MODIFIED"], 0 , 10);
		return $fields;
	}

	//function all string that match the pattern {.} , also catches the list of found strings.
	//the cache will get refreshed when the template bean instance changes.
	//The found url key patterns are replaced with name value pairs provided as function parameter. $tracked_urls.
	//$url_template is used to construct the url for the email message. the template should have place holder for 1 varaible parameter, represented by %1
	//$template_text_array is a list of text strings that need to be searched. usually the subject, html body and text body of the email message.
	//$removeme_url_template, if the url has is_optout property checked then use this template.
	function parse_tracker_urls($template_text_array,$url_template,$tracked_urls,$removeme_url_template) {
		global $beanFiles,$beanList, $app_list_strings;	
		$this->parsed_urls=array();

		//parse the template and find all the dynamic strings that need replacement.
		$pattern = '/\{[a-z_0-9A-Z \x80-\xFF]+\}/'; // cn: bug 6638, find multibyte strings
		foreach ($template_text_array as $key=>$template_text) {
			if (!empty($template_text)) {
				if(!isset($this->parsed_urls[$key])) {
					$matches=array();
					$count=preg_match_all($pattern,$template_text,$matches,PREG_OFFSET_CAPTURE);
					$this->parsed_urls[$key]=$matches;		
				} else {
					$matches=$this->parsed_urls[$key];
					if(!empty($matches[0])) {
						$count=count($matches[0]);
					} else {
						$count=0;
					}
				}
				
				//navigate thru all the matched keys and replace the keys with actual strings.
				for ($i=($count -1); $i>=0; $i--) {
					$url_key_name=$matches[0][$i][0];
	
					if (!empty($tracked_urls[$url_key_name])) {
						if ($tracked_urls[$url_key_name]['is_optout']==1){
							$tracker_url = $removeme_url_template;
						} else {
							$tracker_url = sprintf($url_template,$tracked_urls[$url_key_name]['id']);
						}
					}
					$template_text=substr_replace($template_text,$tracker_url,$matches[0][$i][1], strlen($matches[0][$i][0]));
				}
			}
			$return_array[$key]=$template_text;
		}
		return $return_array;		
	}
			
	function parse_email_template($template_text_array,$focus_name, $focus) {
		global $beanFiles,$beanList, $app_list_strings;	
		
		if(!isset($this->parsed_entities)) $this->parsed_entities=array();

		//parse the template and find all the dynamic strings that need replacement.
		$pattern_prefix='$'.strtolower($beanList[$focus_name]).'_';
		$pattern_prefix_length=strlen($pattern_prefix);
		$pattern='/\\'.$pattern_prefix.'[A-Za-z_0-9]*/';
		foreach ($template_text_array as $key=>$template_text) {
			if(!isset($this->parsed_entities[$key])) {
				$matches=array();
				$count=preg_match_all($pattern,$template_text,$matches,PREG_OFFSET_CAPTURE);
				if($count != 0) {
					for ($i=($count -1); $i>=0; $i--) {
						if(!isset($matches[0][$i][2])) {
							//find the field name in the bean.	
							$matches[0][$i][2]=substr($matches[0][$i][0],$pattern_prefix_length,strlen($matches[0][$i][0]) - $pattern_prefix_length);
							
							//store the localized strings if the field is of type enum..
							if(isset($focus->field_defs[$matches[0][$i][2]]) && $focus->field_defs[$matches[0][$i][2]]['type']=='enum' && isset($focus->field_defs[$matches[0][$i][2]]['options'])) {
								$matches[0][$i][3]=$focus->field_defs[$matches[0][$i][2]]['options'];	
							}
						}
					}	
				}
				$this->parsed_entities[$key]=$matches;						
			} else {
				$matches=$this->parsed_entities[$key];
				if(!empty($matches[0])) {
					$count=count($matches[0]);
				} else {
					$count=0;
				}
			}
			for ($i=($count -1); $i>=0; $i--) {
				$field_name=$matches[0][$i][2];
				$value=$focus->{$field_name};
				//check dom
				if(isset($matches[0][$i][3])) {
					if(isset($app_list_strings[$matches[0][$i][3]][$value])) {
						$value=$app_list_strings[$matches[0][$i][3]][$value];
					}					
				} 
				$template_text=substr_replace($template_text,$value,$matches[0][$i][1], strlen($matches[0][$i][0]));
			}
			
			//parse the template for tracker url strings. patter for these strings in {[a-zA-Z_0-9]+}

			$return_array[$key]=$template_text;
		}
		
		return $return_array;
	}

	function parse_template_bean($string, $bean_name, &$focus) {
		global $beanFiles, $beanList;
		$repl_arr = array();

		foreach($focus->field_defs as $field_def) {
			if(isset($focus->$field_def['name'])) {
				if(($field_def['type'] == 'relate' && empty($field_def['custom_type'])) || $field_def['type'] == 'assigned_user_name') {
             		continue;
				}

				if($field_def['type'] == 'enum') {
					$translated = translate($field_def['options'],$bean_name,$focus->$field_def['name']);
	
					if(isset($translated) && ! is_array($translated)) {
						$repl_arr[strtolower($beanList[$bean_name])."_".$field_def['name']] = $translated;
					} else { // unset enum field, make sure we have a match string to replace with ""
						$repl_arr[strtolower($beanList[$bean_name])."_".$field_def['name']] = '';
					}
				} else {
					$repl_arr[strtolower($beanList[$bean_name])."_".$field_def['name']] = $focus->$field_def['name'];
				}
			} else {
				if($field_def['name'] == 'full_name') {
					$repl_arr[strtolower($beanList[$bean_name]).'_full_name'] = $focus->get_summary_text();
				} else {
					$repl_arr[strtolower($beanList[$bean_name])."_".$field_def['name']] = '';
				}
			}
		} // end foreach()
		
		krsort($repl_arr);
		reset($repl_arr);

		foreach ($repl_arr as $name=>$value) {
			if($value != '') {
				$string = str_replace("\$$name",$value,$string);
			} else {
				$string = str_replace("\$$name", ' ', $string);
			}
		}
		
		return $string;
	}

	function parse_template($string, &$bean_arr) {
		global $beanFiles,$beanList;

		foreach ($bean_arr as $bean_name=>$bean_id) {
			require_once($beanFiles[$beanList[$bean_name]]);

			$focus = new $beanList[$bean_name];
			$result=$focus->retrieve($bean_id);
			if(empty($result)) {
				//sugar_die("bean not found by id: ".$bean_id);
				continue;
			}
			
			if($bean_name == 'Leads' || $bean_name == 'Prospects') {
				$bean_name = 'Contacts';
			}
			if(isset($this) && $this->module_dir == 'EmailTemplates') {
				$string = $this->parse_template_bean($string, $bean_name, $focus);
			} else {
				$string = EmailTemplate::parse_template_bean($string, $bean_name, $focus);	
			}
		}
		return $string;
	}
	
	function bean_implements($interface) {
		switch($interface) {
			case 'ACL':return true;
		}
		return false;
	}
}
?>
