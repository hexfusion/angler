<?php
//if a session does not yet exist for this user, start one
session_start();

ini_set('error_reporting', E_ALL); 
//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page
	Header("Location: index.php");
	}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$ad_clientid = makeStringSafe($_POST["ad_clientid"]);
$ad_channel = makeStringSafe($_POST["ad_channel"]);
$ad_share = makeStringSafe($_POST["ad_share"]);
$ad_active = makeStringSafe($_POST["ad_active"]);
$ad_percent = makeStringSafe($_POST["ad_percent"]);


	$sql = "UPDATE `review_adsense` SET 
ad_clientid='$ad_clientid',
ad_channel='$ad_channel',
ad_share='$ad_share',
ad_active='$ad_active',
ad_percent='$ad_percent'
 WHERE `ad_id` ='1' LIMIT 1 
";
        $sql_result = mysql_query($sql) 
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	if (!$sql_result) {  
   
		echo "<P>Couldn't edit record!";
exit;
	} 

		BodyHeader("$sitename:  Edit AdSense Settings","",""); 
        ?>
<h1><center><BR>
<font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have Edited 
  the following: </font>
</center></h1>
<table width="65%" border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><p>Do you want to display Google AdSense Ads after the first review? </p></td>
    <td><select name="ad_active">
      <option value="y"<?php if ($ad_active == "y") { echo " SELECTED"; } ?>>Yes</option>
      <option value="n"<?php if ($ad_active == "n") { echo " SELECTED"; } ?>>No</option>
    </select></td>
  </tr>
  <tr>
    <td><p><strong>Is Revenue Sharing active?</strong> If not, only <?php echo "$sitename"; ?> default AdSense settings (below) will be used.</p></td>
    <td><select name="ad_share" id="ad_share">
      <option value="y"<?php if ($ad_share == "y") { echo " SELECTED"; } ?>>Yes</option>
      <option value="n"<?php if ($ad_share == "n") { echo " SELECTED"; } ?>>No</option>
    </select>
    </td>
  </tr>
  <tr>
    <td><strong>Percentage Share:</strong> The percentage as a number between 1 and 100 for the percent of times you wish to display a users Adsense Client ID instead of your Client ID.</td>
    <td><input id="ad_percent" maxlength="20" size="25" value="<?php if ($ad_percent != "") { echo "$ad_percent"; } ?>" name="ad_percent" /></td>
  </tr>
  <tr>
    <td><p><strong>Google AdSense Client ID:</strong> Your default <a href="https://www.google.com/support/adsense" target="_blank">Google AdSense   Client ID</a>. This is the code that will be used unless it is overwritten by a   participating user.</p></td>
    <td><input id="ad_clientid" maxlength="20" size="25" value="<?php if ($ad_clientid != "") { echo "$ad_clientid"; } ?>" name="ad_clientid" /></td>
  </tr>
  <tr>
    <td><p><strong>Google AdSense Channel (optional):</strong> Your default <a href="https://www.google.com/support/adsense/bin/topic.py?topic=152" target="_blank">Google AdSense Channel</a>. A channel can be used to track the   performance of a particular region in your forum. Keep field empty if you don't   want to use it.</p></td>
    <td><input name="ad_channel" id="ad_channel" value="<?php if ($ad_channel != "") { echo "$ad_channel"; } ?>" size="25" maxlength="20" /></td>
  </tr>
  <tr>
    <td>Don't have a Google AdSense Account? Get it now and start making money! Click the blue image to the right... </td>
    <td><div align="center">
      <script type="text/javascript"><!--
google_ad_client = "pub-4166677013602529";
google_ad_width = 180;
google_ad_height = 60;
google_ad_format = "180x60_as_rimg";
google_cpa_choice = "CAAQr_Wy0gEaCPfBz19CTlSHKL3D93M";
//--></script>
      <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
    </div></td>
  </tr>
</table>
<P align="center">

<P>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
  <?php 	
        BodyFooter(); 
?>