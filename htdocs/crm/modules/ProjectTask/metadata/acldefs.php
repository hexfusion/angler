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
// created: 2005-11-04 15:39:46
$acldefs['ProjectTask'] = array (
  'forms' => 
  array (
    'by_name' => 
    array (
      'change_project' => 
      array (
        'display_option' => 'disabled',
        'action_option' => 'list',
        'app_action' => 'EditView',
        'module' => 'Project',
      ),
      'change_parent' => 
      array (
        'display_option' => 'disabled',
        'action_option' => 'list',
        'app_action' => 'EditView',
        'module' => 'ProjectTask',
      ),
    ),
  ),
  'form_names' => 
  array (
    'by_id' => 'by_id',
    'by_name' => 'by_name',
    'DetailView' => 'DetailView',
    'EditView' => 'EditView',
  ),
);
?>
