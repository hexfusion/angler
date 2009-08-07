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

// $Id: DashletGenericConfigure.tpl,v 1.8 2006/08/22 23:58:49 awu Exp $

*}


<div style='width: 500px'>
<form action='index.php' id='configure_{$id}' method='post' onSubmit='SUGAR.sugarHome.setChooser(); return SUGAR.dashlets.postForm("configure_{$id}", SUGAR.sugarHome.uncoverPage);'>
<input type='hidden' name='id' value='{$id}'>
<input type='hidden' name='module' value='Home'>
<input type='hidden' name='action' value='ConfigureDashlet'>
<input type='hidden' name='configure' value='true'>
<input type='hidden' name='to_pdf' value='true'>
<input type='hidden' id='displayColumnsDef' name='displayColumnsDef' value=''>
<input type='hidden' id='hideTabsDef' name='hideTabsDef' value=''>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tabForm">
	<tr>
        <td class='dataLabel' colspan='4' align='left'>
        	<h2>{$strings.general}</h2>
        </td>
    </tr>
    <tr>
	    <td class='dataLabel'>
		    {$strings.title}
        </td>
        <td class='dataField' colspan='3'>
            <input type='text' name='dashletTitle' value='{$dashletTitle}'>
        </td>
	</tr>
    <tr>
	    <td class='dataLabel'>
		    {$strings.displayRows}
        </td>
        <td class='dataField' colspan='3'>
            <select name='displayRows'>
				{html_options values=$displayRowOptions output=$displayRowOptions selected=$displayRowSelect}
           	</select>
        </td>
    </tr>
    <tr>
        <td colspan='4' align='center'>
        	<table border='0' cellpadding='0' cellspacing='0'>
        	<tr><td>
			    {$columnChooser}
		    </td>
		    </tr></table>
	    </td>    
	</tr>
	<tr>
        <td class='dataLabel' colspan='4' align='left'>
	        <br>
        	<h2>{$strings.filters}</h2>
        </td>
    </tr>
    <tr>
	    <td class='dataLabel'>
            {$strings.myItems}
        </td>
        <td class='dataField'>
            <input type='checkbox' {if $myItemsOnly == 'true'}checked{/if} name='myItemsOnly' value='true'>
        </td>
    </tr>
    <tr>
    {foreach name=searchIteration from=$searchFields key=name item=params}
        <td class='dataLabel' valign='top'>
            {$params.label}
        </td>
        <td class='dataField' valign='top' style='padding-bottom: 5px'>
            {$params.input}
        </td>
        {if ($smarty.foreach.searchIteration.iteration is even) and $smarty.foreach.searchIteration.iteration != $smarty.foreach.searchIteration.last}
        </tr><tr>
        {/if}
    {/foreach}
    </tr>
    <tr>
	    <td colspan='4' align='right'>
	        <input type='submit' class='button' value='{$strings.save}'>
	    </td>    
	</tr>
</table>
</form>
</div>
