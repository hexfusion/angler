<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

 // $Id: config.php,v 1.8 2006/09/05 18:11:19 majed Exp $

error_reporting(0);
//destroying global variables
$GLOBALS['studio'] = array();
$GLOBALS['studio']['parsers']['ListViewParser'] = 'modules/Studio/parsers/ListViewParser.php';
$GLOBALS['studio']['parsers']['SlotParser'] = 'modules/Studio/parsers/SlotParser.php';
$GLOBALS['studio']['parsers']['StudioParser'] = 'modules/Studio/parsers/StudioParser.php';
$GLOBALS['studio']['parsers']['StudioRowParser'] = 'modules/Studio/parsers/StudioRowParser.php';
$GLOBALS['studio']['parsers']['StudioUpgradeParser'] = 'modules/Studio/parsers/StudioUpgradeParser.php';
$GLOBALS['studio']['parsers']['SubpanelColParser'] = 'modules/Studio/parsers/SubpanelColParser.php';
$GLOBALS['studio']['parsers']['SubpanelParser'] = 'modules/Studio/parsers/SubpanelParser.php';
$GLOBALS['studio']['parsers']['TabIndexParser'] = 'modules/Studio/parsers/TabIndexParser.php';
$GLOBALS['studio']['parsers']['XTPLListViewParser'] = 'modules/Studio/parsers/XTPLListViewParser.php';
$GLOBALS['studio']['ajax']['customfieldview'] = 'modules/Studio/ajax/customfieldview.php';
$GLOBALS['studio']['ajax']['editcustomfield'] = 'modules/Studio/ajax/editcustomfield.php';
$GLOBALS['studio']['ajax']['relatedfiles'] = 'modules/Studio/ajax/relatedfiles.php';
$GLOBALS['studio']['dynamicFields']['bool'] = 'modules/DynamicFields/templates/Fields/Forms/bool.php';
$GLOBALS['studio']['dynamicFields']['date'] = 'modules/DynamicFields/templates/Fields/Forms/date.php';
$GLOBALS['studio']['dynamicFields']['email'] = 'modules/DynamicFields/templates/Fields/Forms/email.php';
$GLOBALS['studio']['dynamicFields']['enum'] = 'modules/DynamicFields/templates/Fields/Forms/enum.php';
$GLOBALS['studio']['dynamicFields']['float'] = 'modules/DynamicFields/templates/Fields/Forms/float.php';
$GLOBALS['studio']['dynamicFields']['html'] = 'modules/DynamicFields/templates/Fields/Forms/html.php';
$GLOBALS['studio']['dynamicFields']['int'] = 'modules/DynamicFields/templates/Fields/Forms/int.php';
$GLOBALS['studio']['dynamicFields']['multienum'] = 'modules/DynamicFields/templates/Fields/Forms/multienum.php';
$GLOBALS['studio']['dynamicFields']['radioenum'] = 'modules/DynamicFields/templates/Fields/Forms/radioenum.php';
$GLOBALS['studio']['dynamicFields']['text'] = 'modules/DynamicFields/templates/Fields/Forms/text.php';
$GLOBALS['studio']['dynamicFields']['url'] = 'modules/DynamicFields/templates/Fields/Forms/url.php';
$GLOBALS['studio']['dynamicFields']['varchar'] = 'modules/DynamicFields/templates/Fields/Forms/varchar.php';

?>
