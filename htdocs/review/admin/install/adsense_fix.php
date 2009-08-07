<?
error_reporting(E_ALL ^ E_NOTICE);
//include ("../body.php");
//BodyHeader("Install Five Star Review Script");

if (@$_POST['step'] == "") { ?>
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
}
-->
</style>


<p align="center" class="style1">Adsense Fix </p>
<hr>
<p class="style2"> This script will fix the problem you have with adsense. </p>
<p>&nbsp; </p>
<p class="style2">Please click Next. </p>
<form name="form1" method="post" action="adsense_fix.php?step=2">
  <div align="right">
    <input name="step" type="hidden" id="step" value="2">
    <input type="submit" name="Submit" value="Next">
  </div>
</form>
<p>&nbsp;</p>
<? } //end step = "" 

if (@$_POST['step'] == "2") { 




//create review_adsense table
include ("../../functions.php");
include ("../../f_secure.php");


//insert data into config table
$sql = "CREATE TABLE `review_adsense` (
  `ad_id` int(11) NOT NULL auto_increment,
  `ad_clientid` char(20) NOT NULL default '',
  `ad_channel` char(15) NOT NULL default '',
  `ad_share` enum('y','n') NOT NULL default 'y',
  `ad_active` enum('y','n') NOT NULL default 'y',
  `ad_percent` char(3) NOT NULL default '',
  PRIMARY KEY  (`ad_id`)
)  
";

		//execute the query
$result = mysql_query($sql,$connection) or die(mysql_error());

//success message
if ($result) {
	$msg ="<P class=\"style2\">review_adsense has been created!</P>
	<p>";
  }
  
echo "$msg"; 
echo "Success!  Please delete this file.";
 } //end step 2 
 ?>

