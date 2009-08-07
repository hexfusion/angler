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
 * $Id: Menu.php,v 1.12 2006/06/06 17:58:21 majed Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $mod_strings;
$module_menu = array (
	array("index.php?module=InboundEmail&action=index", $mod_strings['LNK_LIST_MAILBOXES'],"InboundEmail"),
	array("index.php?module=InboundEmail&action=EditView", $mod_strings['LNK_LIST_CREATE_NEW'],"CreateMailboxes"),
	//array("index.php?module=Queues&action=index", $mod_strings['LNK_LIST_QUEUES'],"DataSets"),
	//array("index.php?module=Queues&action=EditView", $mod_strings['LNK_NEW_QUEUES'],"CreateDataSet"),
	array("index.php?module=Schedulers&action=index", $mod_strings['LNK_LIST_SCHEDULER'],"Schedulers"),



	//array("index.php?module=Queues&action=Seed", $mod_strings['LNK_SEED_QUEUES'],"CustomQueries"),
);
?>
