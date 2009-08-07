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
/*
 * Created on Oct 7, 2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once('soap/SoapHelperFunctions.php');
require_once('modules/MailMerge/MailMerge.php'); 
require_once('modules/Documents/Document.php'); 

global  $beanList, $beanFiles;

$module = $_POST['mailmerge_module'];
$document_id = $_POST['document_id'];
$selObjs = urldecode($_POST['selected_objects_def']);

$item_ids = array();
parse_str($selObjs,$item_ids);

$class_name = $beanList[$module];
$includedir = $beanFiles[$class_name];
require_once($includedir);
$seed = new $class_name(); 

$fields =  get_field_list($seed);

$document = new Document();
$document->retrieve($document_id);

$items = array();
foreach($item_ids as $key=>$value)
{
	$seed->retrieve($key);
	$items[] = $seed;
}

ini_set('max_execution_time', 600);
ini_set('error_reporting', 'E_ALL');
$dataDir = getcwd()."\\MergedDocuments\\";
$fileName = getcwd()."\\".$document->file_url_noimage;
list($outfile, $ext) = split('[.]', $document->filename);

$mm = new MailMerge(null, null, $dataDir);
$mm->SetDataList($items);
$mm->SetFieldList($fields);
$mm->Template(array($fileName, $outfile));
$file = $mm->Execute();
$mm->CleanUp();

header("Location: index.php?module=MailMerge&action=Step4&file=".urlencode($file));


?>
