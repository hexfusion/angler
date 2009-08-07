<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for ProspectLists
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
 
// $Id: layout_defs.php,v 1.6 2006/06/06 17:58:34 majed Exp $

$layout_defs['Prospects'] = array(
	// list of what Subpanels to show in the DetailView 
	'subpanel_setup' => array(
        'campaigns' => array(
			'order' => 10,
			'sort_by' => 'activity_date',
			'sort_order' => 'desc',
			'module' => 'CampaignLog',
			'get_subpanel_data'=>'campaigns',
			'subpanel_name' => 'ForTargets',
			'title_key' => 'LBL_CAMPAIGN_LIST_SUBPANEL_TITLE',
			'top_buttons' => array(),
		),
	),
);
?>
