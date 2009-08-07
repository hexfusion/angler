<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for Campaigns
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
 
// $Id: layout_defs.php,v 1.32 2006/06/19 23:13:47 awu Exp $
$layout_defs['Campaigns'] = array( 
	// list of what Subpanels to show in the DetailView 
	'subpanel_setup' => array(
        'prospectlists' => array(
			'order' => 10,
			'sort_order' => 'asc',
			'sort_by' => 'name',
			'module' => 'ProspectLists',
			'get_subpanel_data'=>'prospectlists',
			'set_subpanel_data'=>'prospectlists',			
			'subpanel_name' => 'default',
			'title_key' => 'LBL_PROSPECT_LIST_SUBPANEL_TITLE',
		),
        'tracked_urls' => array(
			'order' => 15,
			'sort_order' => 'asc',
			'sort_by' => 'tracker_name',
			'module' => 'CampaignTrackers',
			'get_subpanel_data'=>'tracked_urls',
			'subpanel_name' => 'default',
			'title_key' => 'LBL_TRACKED_URLS_SUBPANEL_TITLE',
		),
        'emailmarketing' => array(
			'order' => 20,
			'sort_order' => 'desc',
			'sort_by' => 'date_start',
			'module' => 'EmailMarketing',
			'get_subpanel_data'=>'emailmarketing',
			'subpanel_name' => 'default',
			'title_key' => 'LBL_EMAIL_MARKETING_SUBPANEL_TITLE',
		),

		//subpanels for the tracking view...
        'track_queue' => array(
			'order' => 100,
			'module' => 'EmailMan',
			'get_subpanel_data'=>'function:get_queue_items',
			'subpanel_name' => 'default',
			'title_key' => 'LBL_MESSAGE_QUEUE_TITLE',
			'sort_order' => 'desc',
		),
        'targeted' => array(
			'order' => 110,
			'module' => 'CampaignLog',
			'get_subpanel_data'=>"function:track_log_entries",
			'function_parameters'=>array(0=>'targeted',),
			'subpanel_name' => 'default',
			'title_key' => 'LBL_LOG_ENTRIES_TARGETED_TITLE',
			'sort_order' => 'desc',
		),
        'viewed' => array(
			'order' => 120,
			'module' => 'CampaignLog',
			'get_subpanel_data'=>"function:track_log_entries",
			'subpanel_name' => 'default',
			'function_parameters'=>array(0=>'viewed',),
			'title_key' => 'LBL_LOG_ENTRIES_VIEWED_TITLE',
			'sort_order' => 'desc',
		),
        'link' => array(
			'order' => 130,
			'module' => 'CampaignLog',
			'get_subpanel_data'=>"function:track_log_entries",
			'function_parameters'=>array(0=>'link',),
			'subpanel_name' => 'default',
			'title_key' => 'LBL_LOG_ENTRIES_LINK_TITLE',
			'sort_order' => 'desc',
		),
        'lead' => array(
			'order' => 140,
			'module' => 'CampaignLog',
			'get_subpanel_data'=>"function:track_log_entries",
			'function_parameters'=>array(0=>'lead',),
			'subpanel_name' => 'default',
			'title_key' => 'LBL_LOG_ENTRIES_LEAD_TITLE',
			'sort_order' => 'desc',
		),
        'contact' => array(
			'order' => 150,
			'module' => 'CampaignLog',
			'get_subpanel_data'=>"function:track_log_entries",
			'function_parameters'=>array(0=>'contact',),
			'subpanel_name' => 'default',
			'title_key' => 'LBL_LOG_ENTRIES_CONTACT_TITLE',
			'sort_order' => 'desc',
		),
        'invalid email' => array(
			'order' => 160,
			'module' => 'CampaignLog',
			'get_subpanel_data'=>"function:track_log_entries",
			'function_parameters'=>array(0=>'invalid email',),
			'subpanel_name' => 'default',
			'title_key' => 'LBL_LOG_ENTRIES_INVALID_EMAIL_TITLE',
			'sort_order' => 'desc',
		),				
        'send error' => array(
			'order' => 170,
			'module' => 'CampaignLog',
			'get_subpanel_data'=>"function:track_log_entries",
			'function_parameters'=>array(0=>'send error',),
			'subpanel_name' => 'default',
			'title_key' => 'LBL_LOG_ENTRIES_SEND_ERROR_TITLE',
			'sort_order' => 'desc',
		),
        'removed' => array(
			'order' => 180,
			'module' => 'CampaignLog',
			'get_subpanel_data'=>"function:track_log_entries",
			'function_parameters'=>array(0=>'removed',),
			'subpanel_name' => 'default',
			'title_key' => 'LBL_LOG_ENTRIES_REMOVED_TITLE',
			'sort_order' => 'desc',
		),
	),
);	
?>
