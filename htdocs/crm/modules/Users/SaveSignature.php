<?php
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
require_once('modules/Users/UserSignature.php');
global $current_user;

$us = new UserSignature();
if(isset($_REQUEST['record']) && !empty($_REQUEST['record'])) {
	$us->retrieve($_REQUEST['record']);
} else {
	$us->id = create_guid();
	$us->new_with_id = true;
}
$us->name = $_REQUEST['name'];
$us->signature = strip_tags(br2nl(from_html($_REQUEST['description'])));
$us->signature_html = $_REQUEST['body_html'];
$us->user_id = $current_user->id;
//_pp($_REQUEST);
//_pp($us);
$us->save();

$js = '
<script type="text/javascript">
function refreshTemplates() {
	window.opener.refresh_signature_list("'.$us->id.'","'.$us->name.'");
	window.close();
}

refreshTemplates();
window.close();
</script>';

echo $js;
?>
