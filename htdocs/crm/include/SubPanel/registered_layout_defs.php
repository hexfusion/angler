<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Registration for layout_defs.php
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

// $Id: registered_layout_defs.php,v 1.22 2006/06/12 22:00:07 jacob Exp $

require_once('modules/Accounts/layout_defs.php');
require_once('modules/Activities/layout_defs.php');
require_once('modules/Bugs/layout_defs.php');
require_once('modules/Calls/layout_defs.php');
require_once('modules/Campaigns/layout_defs.php');
require_once('modules/Cases/layout_defs.php');
require_once('modules/Contacts/layout_defs.php');
require_once('modules/History/layout_defs.php');
require_once('modules/Leads/layout_defs.php');
require_once('modules/Meetings/layout_defs.php');
require_once('modules/Opportunities/layout_defs.php');
require_once('modules/Project/layout_defs.php');
require_once('modules/ProjectTask/layout_defs.php');
require_once('modules/ProspectLists/layout_defs.php');
require_once('modules/Roles/layout_defs.php');
require_once('modules/Users/layout_defs.php');
require_once('modules/Prospects/layout_defs.php');
require_once('modules/Emails/layout_defs.php');
require_once('modules/EmailMarketing/layout_defs.php');





/**
 * Retrieves an array of all the layout_defs defined in the app.
 */

function get_layout_defs()
{
    //TODO add global memory cache support here.  If there is an in memory cache, leverage it.
	global $layout_defs;
	return $layout_defs;
}

?>
