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
 * $Id: SugarPDF.php,v 1.1 2006/08/04 21:02:21 chris Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

require_once("include/pdf/class.expdf.php");
/**
 * Subclass of EzPDF for SugarCRM
 * contains SugarCRM-specific private methods for handling of data for PDF
 * export
 */
class SugarPDF extends Cezpdf {
	
	/**
	 * sole constructor
	 * @param array vars Setup values for parent class, EzPDF
	 */
	function SugarPDF($vars) {
		parent::Cezpdf($vars);
	}
	
	/**
	 * takes a $bean and processes all of its list variables for character set
	 * issues
	 * @param bean object The focus bean
	 * @return bean object The focus bean with processed strings
	 */
	function handleBeanStrings($bean) {
		foreach($bean->field_defs as $k => $field) {
			if($field['type'] == 'varchar' || $field['type'] == 'text' || $field['type'] == 'enum') {
				$bean->$k = $this->handleCharset($bean->$k);
			}
		}
		
		return $bean;
	}

	/**
	 * Translates text from UTF-8 (as of SugarCRM v4.5) into the selected
	 * default character set for a given instance, abrogated by user preference.
	 * @param string text The text to be handled
	 * @return string ret The translated string.
	 */
	function handleCharset($text) {
		global $locale;
		
		$ret = $locale->translateCharset($text, 'UTF-8', $locale->getPrecedentPreference('default_export_charset'));
		return $ret;
	}
}
?>
