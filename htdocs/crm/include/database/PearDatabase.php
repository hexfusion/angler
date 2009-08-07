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

// $Id: PearDatabase.php,v 1.75 2006/07/18 04:42:50 majed Exp $

require_once('log4php/LoggerManager.php');
require_once('include/utils.php');
require_once('include/database/DBManager.php');
class PearDatabase{
 
    function &getInstance($instanceName=''){
        $instance = DBManager::getInstance($instanceName);
        return $instance;
    }
    function quote($string,$isLike=true){
        $string=$GLOBALS['db']->quote($string, $isLike);
        return $string;
    }

    function arrayQuote(&$array, $isLike=true) {
        $string=$GLOBALS['db']->arrayQuote($array, $isLike);
        return $string;
    }


}
?>
