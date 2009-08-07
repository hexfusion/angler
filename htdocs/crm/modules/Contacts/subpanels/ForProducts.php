<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Layout definition for Contacts
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

// $Id: ForProducts.php,v 1.5 2006/06/06 17:57:57 majed Exp $

$subpanel_layout = array(
			'buttons' => array(
                array('widget_class' => 'SubPanelTopCreateButton'),
				array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Products'),
			),
	
	
            'list_fields' => array(
				'status' => array(
		 		 		'name' => 'status',
		 		 		'vname' => 'LBL_LIST_STATUS',
						'width' => '8%',
					),
				'name' => array(
		 		 		'name' => 'name',
		 		 		'vname' => 'LBL_LIST_NAME',
						'widget_class' => 'SubPanelDetailViewLink',
						'width' => '28%',
					),
				'account_name' => array(
		 		 		'name' => 'account_name',
		 		 		'vname' => 'LBL_LIST_ACCOUNT_NAME',
						'widget_class' => 'SubPanelDetailViewLink',
		 		 		'module' => 'Accounts',
						'width' => '15%',
						'sortable'=>false,
					),
				'contact_name' => array(
		 		 		'name' => 'contact_name',
		 		 		'vname' => 'LBL_LIST_CONTACT_NAME',
						'widget_class' => 'SubPanelDetailViewLink',
		 		 		'module' => 'Contacts',
						'width' => '15%',
					),
				'date_purchased' =>	array(
		 		 		'name' => 'date_purchased',
		 		 		'vname' => 'LBL_LIST_DATE_PURCHASED',
						'width' => '10%',
					),
				'discount_price' =>	array(
		 		 		'name' => 'discount_price',
		 		 		'vname' => 'LBL_LIST_DISCOUNT_PRICE',
						'width' => '10%',
					),
				'date_support_expires' => array(
		 		 		'name' => 'date_support_expires',
		 		 		'vname' => 'LBL_LIST_SUPPORT_EXPIRES',
						'width' => '10%',
					),
				'nothing' => array(
			 		 	'name' => 'nothing',
						'widget_class' => 'SubPanelEditButton',
			 		 	'module' => 'Products',
		 		 		'width' => '4%',
					),
				),
);
?>
