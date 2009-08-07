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
 * Description:
 * Created On: Oct 21, 2005
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): Chris Nojima
 ********************************************************************************/

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $theme;
global $currentModule;
global $gridline;
global $timedate;
global $current_user;
global $db;

if ($db->dbType == 'oci8') {
	echo "<BR>";
	echo "<p>".$mod_strings['ERR_NOT_FOR_ORACLE']."</p>";
	echo "<BR>";
	sugar_die('');	
}
if ($db->dbType == 'mssql') {
    echo "<BR>";
    echo "<p>".$mod_strings['ERR_NOT_FOR_MSSQL']."</p>";
    echo "<BR>";
    sugar_die('');  
}
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

require_once('include/utils.php');
require_once('include/TimeDate.php');
$display = '';
if(empty($db)) {
	
	$db &= PearDatabase::getInstance();
}

// check if this fix has been applied already
$qDone = "SELECT * FROM versions WHERE name = 'DST Fix'";
$rDone = $db->query($qDone);
$rowsDone = $db->getRowCount($rDone);
if($rowsDone > 0) {
	$done = true;
} else {
	$done = false;
}

// some inits:
$disabled = 'DISABLED';
$confirmed = 'false';

// apply the fix
if(!empty($_REQUEST['confirmed']) && $_REQUEST['confirmed'] == true) {
	// blowaway vCal server cache
	$qvCal = "TRUNCATE vcals";
	$rvCal = $db->query($qvCal);
	
	// disable refresh double-ups
	$rDblCheck = $db->query($qDone);
	$rowsDblCheck = $db->getRowCount($rDblCheck);
	if($rowsDblCheck < 1) {

		// majed's sql generation
		$tables = array(
			'calls'=>array(
						'date_start'=>'time_start',
					),
			'meetings'=>array(
						'date_start'=>'time_start',
					),
			'tasks'=>array(
						'date_due'=>'time_due',
					),
			'project_task'=>array(
						'date_due'=>'time_due',
					),
			'email_marketing'=>array(
						'date_start'=>'time_start',
					),
			'emailman'=>array(
						'send_date_time'=>'datetime',
					)
		);
		
		$zone = $_REQUEST['server_timezone'];
		$td = new TimeDate();
		$startyear = 2004;
		$maxyear = 2014;
		$date_modified = gmdate('Y-m-d H:i:s');
		$display = '';
		
		foreach($tables as $table_name =>$table) {
		
			//$display .=  '<B>'. $table_name . '</b><BR>';
			$year = $startyear;
	
			for($year = $startyear; $year <= $maxyear; $year++) {
				$range = $td->getDSTRange($year,$timezones[$zone]);
				$startDateTime = explode(' ',$range['start']);
				$endDateTime = explode(' ',$range['end']);
	
				if($range) {
					if( strtotime($range['start']) < strtotime($range['end'])) {
						foreach($table as $date=>$time) {
							$interval='PLUSMINUS INTERVAL 3600 second';
							if($time != 'datetime'){
								if ( ( $this->db->dbType == 'mysql' ) or ( $this->db->dbType == 'oci8' ) )
								{
									$field = "CONCAT($table_name.$date,' ', $table_name.$time)";
								}
								if ( $this->db->dbType == 'mssql' )
								{
									$field = "$table_name.$date + ' ' + $table_name.$time";
								}
								$updateBase= "UPDATE  $table_name SET date_modified='$date_modified', $table_name.$date=LEFT($field $interval,10),";
								$updateBase .= " $table_name.$time=RIGHT($field $interval,8)";			
							
							}else{
								$field = "$table_name.$date";
								$updateBase = "UPDATE $table_name SET  date_modified='$date_modified', $table_name.$date = $table_name.$date $interval";
							}
							//BEGIN DATE MODIFIED IN DST WITH DATE OUT DST 
							$update = str_replace('PLUSMINUS', '+', $updateBase);
							$queryInDST = $update ."
											WHERE 
											$table_name.date_modified >= '{$range['start']}' AND $table_name.date_modified < '{$range['end']}'
											AND ( $field < '{$range['start']}'  OR $field >= '{$range['end']}' )";
									
							$result = $db->query($queryInDST);	
							$count = $db->getAffectedRowCount($result);
							//$display .= "$year - Records updated with date modified in DST with date out of DST: $count <br>";	
							//BEGIN DATE MODIFIED OUT DST WITH DATE IN DST 
							$update = str_replace('PLUSMINUS', '-', $updateBase);
							$queryOutDST =  $update ."
											WHERE 
											( $table_name.date_modified < '{$range['start']}' OR $table_name.date_modified >= '{$range['end']}' )
											AND $field >= '{$range['start']}' AND $field < '{$range['end']}' ";
							
							$result = $db->query($queryOutDST);	
							$count = $db->getAffectedRowCount($result);
							//$display .= "$year - Records updated with date modified out of DST with date in DST: $count <br>";	
						}
					}else{
						
						foreach($table as $date=>$time){
							$interval='PLUSMINUS INTERVAL 3600 second';
							if($time != 'datetime'){
									
								if ( ( $this->db->dbType == 'mysql' ) or ( $this->db->dbType == 'oci8' ) )
								{
									$field = "CONCAT($table_name.$date,' ', $table_name.$time)";
								}
								if ( $this->db->dbType == 'mssql' )  
								{
									$field = "$table_name.$date + ' ' + $table_name.$time";
								}
									$updateBase= "UPDATE  $table_name SET $table_name.$date=LEFT($field $interval,10),";
									$updateBase .= " $table_name.$time=RIGHT($field $interval,8)";			
								
							}else{
								$field = "$table_name.$date";
								$updateBase = "UPDATE $table_name SET $table_name.$date = $table_name.$date $interval";
							}
							
							
							//BEGIN DATE MODIFIED IN DST WITH DATE OUT OF DST 
							$update = str_replace('PLUSMINUS', '+', $updateBase);
							$queryInDST =  $update ." 
											WHERE 
											($table_name.date_modified >= '{$range['start']}' OR $table_name.date_modified < '{$range['end']}' )
											AND $field < '{$range['start']}'  AND $field >= '{$range['end']}'";
											
							$result = $db->query($queryInDST);	
							$count = $db->getAffectedRowCount($result);
							//$display .= "$year - Records updated with date modified in DST with date out of DST: $count <br>";	
				
							//BEGIN DATE MODIFIED OUT DST WITH DATE IN DST 
							$update = str_replace('PLUSMINUS', '-', $updateBase);
							$queryOutDST =  $update ." 
											WHERE 
											($table_name.date_modified < '{$range['start']}' AND $table_name.date_modified >= '{$range['end']}' )
											 AND 
											 ($field >= '{$range['start']}' OR $field < '{$range['end']}' )";
									
											
										
						}
							
						$result = $db->query($queryOutDST);	
						$count = $db->getAffectedRowCount($result);
						//$display .= "$year - Records updated with date modified out of DST with date in DST: $count <br>";	
					}
				}
			} // end outer forloop
		}// end foreach loop

		
	}
	$display .= "<br><b>".$mod_strings['LBL_DST_FIX_DONE_DESC']."</b>";
} elseif(!$done) {  // show primary screen
	$disabled = "";
	$confirmed = 'true';
	if(empty($timedate)) {
		require_once("include/TimeDate.php");
		$timedate = new TimeDate();
	}
	
	require_once('include/timezone/timezones.php');
	global $timezones;
	$timezoneOptions = '';
	ksort($timezones);
	if(!isset($defaultServerZone)){
		$defaultServerZone = lookupTimezone(0); 
	}
	foreach($timezones as $key => $value) {
		if(!empty($value['dstOffset'])) {
			$dst = " (+DST)";
		} else {
			$dst = "";
		}
		if($key == $defaultServerZone){
			$selected = 'selected';
		}else{
			$selected = '';
		}
		$gmtOffset = ($value['gmtOffset'] / 60);
		if(!strstr($gmtOffset,'-')) {
			$gmtOffset = "+".$gmtOffset;
		}
		$timezoneOptions .= "<option value='$key'".$selected.">".str_replace(array('_','North'), array(' ', 'N.'),$key). " (GMT".$gmtOffset.") ".$dst."</option>";
	}
	
	// descriptions and assumptions
	$display = "
	
		<tr>
			<td width=\"20%\" class=\"tabDetailViewDL2\" nowrap align='right'><slot>
				".$mod_strings['LBL_DST_FIX_TARGET']."
			</slot></td>
			<td class=\"tabDetailViewDF2\"><slot>
				".$mod_strings['LBL_APPLY_DST_FIX_DESC']."
			</slot></td>
		</tr>
		<tr>
			<td width=\"20%\" class=\"tabDetailViewDL2\" nowrap align='right'><slot>
				".$mod_strings['LBL_DST_BEFORE']."
			</slot></td>
			<td class=\"tabDetailViewDF2\"><slot>
				".$mod_strings['LBL_DST_BEFORE_DESC']."
			</slot></td>
		</tr>
		<tr>
			<td width=\"20%\" class=\"tabDetailViewDL2\" nowrap align='right'><slot>
				".$mod_strings['LBL_DST_FIX_CONFIRM']."
			</slot></td>
			<td class=\"tabDetailViewDF2\"><slot>
				".$mod_strings['LBL_DST_FIX_CONFIRM_DESC']."
			</slot></td>
		</tr>
		<tr>
			<td width=\"20%\" class=\"tabDetailViewDL2\" nowrap align='right'><slot>
		
			</slot></td>
			<td class=\"tabDetailViewDF2\"><slot>
				<table cellpadding='0' cellspacing='0' border='0'>
					<tr>
						<td class=\"tabDetailViewDF2\"><slot>
							<b>".$mod_strings['LBL_DST_CURRENT_SERVER_TIME']."</b>
						</td>
						<td class=\"tabDetailViewDF2\"><slot>
							".$timedate->to_display_time(date('Y-m-d H:i:s', strtotime('now')), true, false)."
						</td>
					<tr>
					</tr>
						<td class=\"tabDetailViewDF2\"><slot>
							<b>".$mod_strings['LBL_DST_CURRENT_SERVER_TIME_ZONE']."</b>
						</td>
						<td class=\"tabDetailViewDF2\"><slot>
							".date("T")."<br>
						</td>
					</tr>
					<tr>
						<td class=\"tabDetailViewDF2\"><slot>
							<b>".$mod_strings['LBL_DST_CURRENT_SERVER_TIME_ZONE_LOCALE']."</b>
						</td>
						<td class=\"tabDetailViewDF2\"><slot>
							<select name='server_timezone'>".$timezoneOptions."</select><br>
						</td>
					</tr>
				</table>
			</slot></td>
		</tr>";
} else { // fix has been applied - don't want to allow a 2nd pass
	$display = $mod_strings['LBL_DST_FIX_DONE_DESC'];
	$disabled = 'DISABLED';
	$confirmed = 'false';
}

if(!empty($_POST['upgrade'])){
	// enter row in versions table
	$qDst = "INSERT INTO versions VALUES ('".create_guid()."', 0, '".gmdate('Y-m-d H:i:s', strtotime('now'))."', '".gmdate('Y-m-d H:i:s', strtotime('now'))."', '".$current_user->id."', '".$current_user->id."', 'DST Fix', '3.5.1b', '3.5.1b')";
	$qRes = $db->query($qDst);
	// record server's time zone locale for future upgrades
	$qSTZ = "INSERT INTO config VALUES ('Update', 'server_timezone', '".$_REQUEST['server_timezone']."')";
	$rSTZ = $db->query($qSTZ);
	if(empty($_REQUEST['confirmed']) || $_REQUEST['confirmed'] == 'false') {
		$display = $mod_strings['LBL_DST_FIX_DONE_DESC'];
		$disabled = 'DISABLED';
		$confirmed = 'false';
	}
	unset($_SESSION['GMTO']);
}



echo "\n<p>\n";
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_APPLY_DST_FIX'], true);
echo "\n</p>\n";

if(empty($disabled)){
?>
<h2>Step 1:</h2>
<table width="100%" cellpadding="0" cellspacing="<?php echo $gridline;?>" border="0" class="tabDetailView2">

					<tr>
			<td width="20%" class="tabDetailViewDL2" nowrap align='right'><slot>
				<?php echo $mod_strings['LBL_DST_FIX_USER']; ?>		
			</slot></td>
			<td class="tabDetailViewDF2"><slot>
			<?php echo $mod_strings['LBL_DST_FIX_USER_TZ']; ?><br>
			<input type='button' class='button' value='<?php echo $mod_strings['LBL_DST_SET_USER_TZ']; ?>' onclick='document.location.href="index.php?module=Administration&action=updateTimezonePrefs"'>	 
			</slot></td>
</tr>


</table>
<?php }?>
<p>
<form name='DstFix' action='index.php' method='POST'>
<input type='hidden' name='module' value='Administration'>
<input type='hidden' name='action' value='DstFix'>
<?php
if(empty($disabled)){
	echo "<h2>Step 2:</h2>";
}
?>
<table width="100%" cellpadding="0" cellspacing="<?php echo $gridline;?>" border="0" class="tabDetailView2">
	<?php 
	echo $display;
	 if(empty($disabled)){ 
	 	?>
	<tr>
		<td width="20%" class="tabDetailViewDL2" nowrap align='right'><slot>
			<?php echo $mod_strings['LBL_DST_UPGRADE']; ?>
		</slot></td>
		<td class="tabDetailViewDF2"><slot>
			<input type='checkbox' name='confirmed' value='true' CHECKED >
			<?php echo $mod_strings['LBL_DST_APPLY_FIX']; ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td width="20%" class="tabDetailViewDL2" nowrap align='right'></td>
		<td class="tabDetailViewDF2"><slot>
<?php 
if(empty($disabled)){
	echo "<input ".$disabled." title='".$mod_strings['LBL_APPLY_DST_FIX']."' accessKey='".$app_strings['LBL_SAVE_BUTTON_KEY']."' class=\"button\" onclick=\"this.form.action.value='DstFix';\" type=\"submit\" name=\"upgrade\" value='".$mod_strings['LBL_APPLY_DST_FIX']."' >";
}else{
	echo "<input title='".$app_strings['LBL_DONE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_DONE_BUTTON_KEY']."' class=\"button\" onclick=\"this.form.action.value='Upgrade'; this.form.module.value='Administration';\" type=\"submit\" name=\"done\" value='".$app_strings['LBL_DONE_BUTTON_LABEL']."'>";
}
?>
</tr>
</table>
</form>
