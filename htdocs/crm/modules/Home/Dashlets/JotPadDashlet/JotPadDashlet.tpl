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

// $Id: JotPadDashlet.tpl,v 1.5 2006/08/23 00:13:44 awu Exp $

*}


<div id='jotpad_{$id}' ondblclick='JotPad.edit(this, "{$id}")' style='overflow: auto; width: 100%; height: {$height}px; border: 1px #ddd solid'>{$savedText}</div>
<textarea id='jotpad_textarea_{$id}' rows="5" onblur='JotPad.blur(this, "{$id}")' style='display: none; width: 100%; height: {$height}px; overflow: auto'></textarea>
