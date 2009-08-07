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
<p class="style2"> This script will fix the problem you have with adsense by loading some data into your database table. </p>
<p>&nbsp; </p>
<p class="style2">Please click Next. </p>
<form name="form1" method="post" action="adsense_fix2.php?step=2">
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
$sql = "INSERT INTO `review_adsense` (`ad_id`, `ad_clientid`, `ad_channel`, `ad_share`, `ad_active`, `ad_percent`) VALUES (1, 'pub-4166677013602529', '8209498954', 'y', 'y', '40')
";

		//execute the query
$result = mysql_query($sql,$connection) or die(mysql_error());

//success message
if ($result) {
	$msg ="<P class=\"style2\">review_adsense has been updated with some sample data!</P>
	<p>";
  }
  
echo "$msg"; 
echo "Success!  Please delete this file.";
 } //end step 2 
 ?>

