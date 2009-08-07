<?php
session_start();

include ("../functions.php");
include ("../f_secure.php");
include ("../body.php");
include ("../config.php");

/* if ($use_phpBB == "yes") {
include_once("../phpbb_include.php");   
} elseif ($use_vb == "yes") {
include_once("../vbulletin_include.php");
}//end use_vb */

$back = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];

if (isset($_SESSION['signedin']) && $_SESSION['signedin'] != "y" && isset($_POST['active']) && $_POST['active'] != "n")
	{
	// User not logged in, redirect to login page
	Header("Location: ../login/signin.php");
	}


$username = @$_SESSION['username_logged'];

if (!$username) {
BodyHeader("User Not Found!","","");
?>
<link href="../review.css" rel="stylesheet" type="text/css" />
<p align="center">&nbsp;</p>
<p align="center"><span class="category">The user was not found. Click to <a href="../login/register.php?item_id=<?php echo "$item_id"; ?>&amp;back=<?php echo "$back"; ?>">Register</a> or if you've already registered, <a href="../login/signin.php?item_id=<?php echo "$item_id"; ?>&amp;back=<?php echo "$back"; ?>">Login In</a>. </span></p>
<p align="center"><br />
  <?php BodyFooter();  
exit;  
}

$sqlaccess = "SELECT *
		FROM review_users
		WHERE username='" . mysql_real_escape_string($username) . "'
		";

	$resultaccess = mysql_query($sqlaccess)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row = mysql_fetch_array($resultaccess)) {
$name = stripslashes($row['name']);
$email = stripslashes($row['email']);
$username = stripslashes($row['username']);
$city= stripslashes($row['city']);
$state= stripslashes($row['state']);
$zip= stripslashes($row['zip']);
$age= stripslashes($row['age']);
$profession= stripslashes($row['profession']);
$aboutme= stripslashes($row['aboutme']);
$sig= stripslashes($row['sig']);
$skype= stripslashes($row['skype']);
} //while

	$numaccess = mysql_numrows($resultaccess);

		if ($numaccess == 0) {
BodyHeader("User Not Found!","","");
?>
</p>
<p align="center"><span class="category">The user was not found. Please push the back button in your browser to proceed.</span></p>
<p align="center"><br />
  <?php BodyFooter();  
exit;  
}

//bbcode
if ($use_bbcode == "yes") {
include("../bbcode.php");

  $sig = str_replace($bbcode, $htmlcode, $sig);
  $sig = nl2br($sig);//second pass

  $aboutme = str_replace($bbcode, $htmlcode, $aboutme);
  $aboutme = nl2br($aboutme);//second pass
}
BodyHeader("Profile","","");
echo "<span class=\"index2-Orange\">";
if ($use_vb == "yes") { echo $bbuserinfo['username']; } else { echo $_SESSION['name_logged']; } ?>
  's Control Panel</span> <br />
  <br />
</p><div id="borderbox">
  <div align="center"><b><span class="index2-Orange">About <?php echo ucfirst($name); ?></span></b> </div>

                        <?php 
//if there is an image, resize it and then display it.
$username = $_SESSION['username_logged'];
$filename = "upload/images/$username.jpg";
if (file_exists($filename)) {
$html="<img src=upload/images/resize.php?filename=$username.jpg>";
print $html;
} else { ?>
                        <div align="center"><img src="../images/user.gif" width="48" height="48" /></div>
                        <?php } ?>                      <br />
<b>Name:</b> <?php echo ucfirst($name); ?><br />
                          <b>Username:</b> <?php echo ucfirst($username); ?> <br />
                          <b>E-mail:</b> <a 
href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a><br />
                          <b>Location:</b> <?php echo "$city, $state $zip"; ?><br />
                          <b>Age</b>: <?php echo "$age"; ?><br />
                          <b>Profession:</b> <?php echo "$profession"; ?><br />
                          <b>Custom Signature:</b> <?php echo "$sig"; ?> <br />
                          <b>About me:</b> <?php echo "$aboutme"; ?>
                          <!--
Skype 'Call me!' button
http://www.skype.com/go/skypebuttons
-->
                          <?php if ($skype != "") { ?>
                          <b>Skype:</b> Your Skype username is set to <?php echo "$skype"; ?> <br />
                          <?php } //end if skype ?><hr width="85%" />
                        <div align="center"><span><img src="../images/favorites.gif" width="16" height="16" /> My Favorites:</span> <br />
                          <br />
                        </div>
                        <table width="89%"  border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr valign="top">
                            <td height="25"><div align="left">Name</div></td>
                            <td height="25"><div align="left">Description</div></td>
                            <td height="25">&nbsp;</td>
                            <td height="25">&nbsp;</td>
                            <br />
                          </tr>
                          <tr>
                            <?php
				 $username = $_SESSION['username_logged'];

				 $sql = "SELECT * FROM review_favorites WHERE username='" . mysql_real_escape_string($username) . "'";
				 
				 $sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {

			 while ($row = mysql_fetch_array($sql_result)) {
	$item_id_fav = stripslashes($row["item_id_fav"]);
	$fav_id = stripslashes($row["fav_id"]);
	

			 $sql2 = "SELECT item_name, item_desc FROM review_items WHERE item_id='$item_id_fav'";
				 
				 $sql_result2 = mysql_query($sql2)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
					 while ($row2 = mysql_fetch_array($sql_result2)) {
	$item_name = stripslashes($row2["item_name"]);
	$item_desc = stripslashes($row2["item_desc"]);	}
		
	
	?>
                          </tr>
                          <tr valign="top">
                            <td><?php echo "$item_name"; ?></td>
                            <td><?php echo "$item_desc"; ?></td>
                            <td><div align="center"><img src="../images/show_review.gif" width="16" height="16" border="0" /> <a href="<?php echo "$directory"; ?>/index2.php?item_id=<?php echo "$item_id_fav"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Show Review</a></div></td>
                            <td><div align="center"><img src="../images/delete.gif" width="16" height="16" border="0" /> <a href="<?php echo "$directory"; ?>/favorites/delete.php?fav_id=<?php echo "$fav_id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Remove</a></div>
                              <br /></td>
                          </tr>
                          <?php
}
}
/*
//show names of table fields in result
$y=mysql_num_fields($sql_result2);
for ($x=0; $x<$y; $x++) {
   echo  mysql_field_name($sql_result2, $x).'<br />';
}

print_r(mysql_field_name($sql_result2, 0));*/
?>
                        </table>
                        <hr width="85%" />
                        <div align="center"><span><img src="../images/subscribe.gif" width="16" height="16" /> My Subscriptions:</span> <br />
                          <br />
                        </div>
                        <table width="89%"  border="0" align="center" cellpadding="0" cellspacing="0">
                          <tr valign="top">
                            <td height="25"><div align="left">Name</div></td>
                            <td height="25"><div align="left">Description</div></td>
                            <td height="25">&nbsp;</td>
                            <td height="25">&nbsp;</td>
                            <br />
                          </tr>
                          <tr>
                            <?php
				 $username = $_SESSION['username_logged'];

				 $sql = "SELECT * FROM review_subscriptions WHERE username='" . mysql_real_escape_string($username) . "'";
				 
				 $sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {

			 while ($row = mysql_fetch_array($sql_result)) {
	$item_id_sub = stripslashes($row["item_id_sub"]);
	$sub_id = stripslashes($row["sub_id"]);
	

			 $sql2 = "SELECT item_name, item_desc FROM review_items WHERE item_id='$item_id_sub'";
				 
				 $sql_result2 = mysql_query($sql2)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
					 while ($row2 = mysql_fetch_array($sql_result2)) {
	$item_name = stripslashes($row2["item_name"]);
	$item_desc = stripslashes($row2["item_desc"]);	}
		
	
	?>
                          </tr>
                          <tr valign="top">
                            <td><?php echo "$item_name"; ?></td>
                            <td><?php echo "$item_desc"; ?></td>
                            <td><div align="center"><img src="../images/show_review.gif" width="16" height="16" border="0" /> <a href="<?php echo "$directory"; ?>/index2.php?item_id=<?php echo "$item_id_sub"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Show Review</a></div></td>
                            <td><div align="center"><img src="../images/delete.gif" width="16" height="16" border="0" /> <a href="<?php echo "$directory"; ?>/subscriptions/delete.php?sub_id=<?php echo "$sub_id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Remove</a></div>
                              <br /></td>
                          </tr>
                          <?php
}
}
?>
                        </table>
                        <hr width="85%" />
                        <p align="center"><br />
                          <?php //show navigation links on the bottom
include("user_cp_links.php"); ?>
                      </p>
       
</div>
<?php 
BodyFooter();
exit;
?>
