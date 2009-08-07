{*

/**
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

// $Id: SupportPortal.tpl,v 1.9 2006/08/23 00:05:37 awu Exp $

*}


{if $helpFileExists}
	<html>
	<head>
	<title>{$title}</title>
	<link href='{$styleSheet}' rel='stylesheet' type='text/css' />
{if isset($styleColor)}
	<link href='{$styleColor}' rel='stylesheet' type='text/css' />
{/if}
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}">
	</head>
	<body onLoad='window.focus();'>
	{$helpBar}
	<table class='tabForm'>
		<tr>
		<td>{include file="$helpPath"}</td>
		</tr>
	</table>
	{$bookmarkScript}
	</body>
	</html>	
{else}
	<IFRAME frameborder="0" marginwidth="0" marginheight="0" bgcolor="#FFFFFF" SRC="{$iframeURL}"  NAME="SUGARIFRAME" ID="SUGARIFRAME" WIDTH="100%" height="700"></IFRAME>
{/if}
