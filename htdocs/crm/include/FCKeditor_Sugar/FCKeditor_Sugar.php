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
 * $Id: FCKeditor_Sugar.php,v 1.5 2006/08/03 02:57:47 chris Exp $
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 ********************************************************************************/
require_once('include/FCKeditor/fckeditor.php');
class FCKeditor_Sugar extends fckeditor {

	function __construct( $instanceName ) {
		parent::__construct($instanceName);
		$this->SugarDefaults();
 	}	

	function FCKeditor_Sugar($instanceName) {
		parent::fckeditor($instanceName);
		$this->SugarDefaults();
	}
	
	function SugarDefaults() {
		$this->BasePath   = 'include/FCKeditor/' ;
		$this->Height   = '400px' ;
		
		if(isset($GLOBALS['PHP_SELF']) && !empty($GLOBALS['PHP_SELF'])) { 
			$host = $GLOBALS['PHP_SELF'];
		} else {
			global $sugar_config;
			$host = $sugar_config['site_url'];
		}
		$this->Config["CustomConfigurationsPath"] = $host."/include/FCKeditor_Sugar/myfckconfig.js?". time();
				
	}

	/* overrides this function to remove htmlspecialchars call*/
	function CreateHtml()
	{
		$HtmlValue = $this->Value;

		$Html = '<div>' ;
		
		if ( $this->IsCompatible() )
		{
			if ( isset( $_GET['fcksource'] ) && $_GET['fcksource'] == "true" )
				$File = 'fckeditor.original.html' ;
			else
				$File = 'fckeditor.html' ;

			$Link = "{$this->BasePath}editor/{$File}?InstanceName={$this->InstanceName}" ;
			
			if ( $this->ToolbarSet != '' )
				$Link .= "&amp;Toolbar={$this->ToolbarSet}" ;

			// Render the linked hidden field.
			$Html .= "<input type=\"hidden\" id=\"{$this->InstanceName}\" name=\"{$this->InstanceName}\" value=\"{$HtmlValue}\" style=\"display:none\" />" ;

			// Render the configurations hidden field.
			$Html .= "<input type=\"hidden\" id=\"{$this->InstanceName}___Config\" value=\"" . $this->GetConfigFieldString() . "\" style=\"display:none\" />" ;

			// Render the editor IFRAME.
			$Html .= "<iframe name=\"{$this->InstanceName}___Frame\" id=\"{$this->InstanceName}___Frame\" src=\"{$Link}\" width=\"{$this->Width}\" height=\"{$this->Height}\" frameborder=\"no\" scrolling=\"no\"></iframe>" ;
		}
		else
		{
			if ( strpos( $this->Width, '%' ) === false )
				$WidthCSS = $this->Width . 'px' ;
			else
				$WidthCSS = $this->Width ;

			if ( strpos( $this->Height, '%' ) === false )
				$HeightCSS = $this->Height . 'px' ;
			else
				$HeightCSS = $this->Height ;

			$Html .= "<textarea name=\"{$this->InstanceName}\" rows=\"4\" cols=\"40\" style=\"width: {$WidthCSS}; height: {$HeightCSS}\">{$HtmlValue}</textarea>" ;
		}

		$Html .= '</div>' ;
		
		return $Html ;
	}		
}
?>
