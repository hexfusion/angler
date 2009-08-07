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
 if(!defined('ACL_ALLOW_NONE')){   
 	define('ACL_ALLOW_ADMIN', 99);
 	define('ACL_ALLOW_ALL', 90);                        
 	define('ACL_ALLOW_ENABLED', 89);
 	define('ACL_ALLOW_OWNER', 75);
 	define('ACL_ALLOW_NORMAL', 1);
 	define('ACL_ALLOW_DEFAULT', 0);
 	define('ACL_ALLOW_DISABLED', -98);
 	define('ACL_ALLOW_NONE', -99);
 }
 /**
  * $ACLActionAccessLevels
  * these are rendering descriptions for Access Levels giving information such as the label, color, and text color to use when rendering the access level
  */
 $ACLActionAccessLevels = array(
 	ACL_ALLOW_ALL=>array('color'=>'#008000', 'label'=>'LBL_ACCESS_ALL', 'text_color'=>'white'),
 	ACL_ALLOW_OWNER=>array('color'=>'#6F6800', 'label'=>'LBL_ACCESS_OWNER', 'text_color'=>'white'),
 	ACL_ALLOW_NONE=>array('color'=>'#FF0000', 'label'=>'LBL_ACCESS_NONE', 'text_color'=>'white'),
 	ACL_ALLOW_ENABLED=>array('color'=>'#008000', 'label'=>'LBL_ACCESS_ENABLED', 'text_color'=>'white'),
 	ACL_ALLOW_DISABLED=>array('color'=>'#FF0000', 'label'=>'LBL_ACCESS_DISABLED', 'text_color'=>'white'),
 	ACL_ALLOW_ADMIN=>array('color'=>'#0000FF', 'label'=>'LBL_ACCESS_ADMIN', 'text_color'=>'white'),
 	ACL_ALLOW_NORMAL=>array('color'=>'#008000', 'label'=>'LBL_ACCESS_NORMAL', 'text_color'=>'white'),
 	ACL_ALLOW_DEFAULT=>array('color'=>'#008000', 'label'=>'LBL_ACCESS_DEFAULT', 'text_color'=>'white'),
 );
/**
 * $ACLActions
 * These are the actions for a given type. It includes the ACCESS Levels for that action and the label for that action. Every an object of the category (e.g. module) is added all associated actions are added for that object
 */
$ACLActions = array(
	'module'=>array('actions'=>
						array(








							'access'=>
								array(
									'aclaccess'=>array(ACL_ALLOW_ENABLED,ACL_ALLOW_DEFAULT, ACL_ALLOW_DISABLED),
									'label'=>'LBL_ACTION_ACCESS',
									'default'=>ACL_ALLOW_ENABLED,
								),
							
								'view'=>
								array(
									'aclaccess'=>array(ACL_ALLOW_ALL,ACL_ALLOW_OWNER,ACL_ALLOW_DEFAULT, ACL_ALLOW_NONE),
									'label'=>'LBL_ACTION_VIEW',
									'default'=>ACL_ALLOW_ALL,
								),
					
						'list'=>
								array(
									'aclaccess'=>array(ACL_ALLOW_ALL,ACL_ALLOW_OWNER,ACL_ALLOW_DEFAULT, ACL_ALLOW_NONE),
									'label'=>'LBL_ACTION_LIST',
									'default'=>ACL_ALLOW_ALL,
								),
						'edit'=>
								array(
									'aclaccess'=>array(ACL_ALLOW_ALL,ACL_ALLOW_OWNER,ACL_ALLOW_DEFAULT, ACL_ALLOW_NONE),
									'label'=>'LBL_ACTION_EDIT',
									'default'=>ACL_ALLOW_ALL,
									
								),
						'delete'=>
							array(
									'aclaccess'=>array(ACL_ALLOW_ALL,ACL_ALLOW_OWNER,ACL_ALLOW_DEFAULT, ACL_ALLOW_NONE),
									'label'=>'LBL_ACTION_DELETE',
									'default'=>ACL_ALLOW_ALL,
									
								),
						'import'=>
							array(
									'aclaccess'=>array(ACL_ALLOW_ALL,ACL_ALLOW_DEFAULT, ACL_ALLOW_NONE),
									'label'=>'LBL_ACTION_IMPORT',
									'default'=>ACL_ALLOW_ALL,
								),
						'export'=>
							array(
									'aclaccess'=>array(ACL_ALLOW_ALL,ACL_ALLOW_OWNER,ACL_ALLOW_DEFAULT, ACL_ALLOW_NONE),
									'label'=>'LBL_ACTION_EXPORT',
									'default'=>ACL_ALLOW_ALL,
								),
						)
						
					
				)


);



?>
