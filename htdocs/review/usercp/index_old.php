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

if ($_SESSION['signedin'] != "y" && $_POST['active'] != "n")
	{
	// User not logged in, redirect to login page
	Header("Location: ../login/signin.php");
	}


$username = $_SESSION['username_logged'];

if (!$username) {
BodyHeader("User Not Found!");
?>
<link href="../review.css" rel="stylesheet" type="text/css">
<P align="center">
<P align="center"><span class="box1">The user was not found. Click to <a href=../login/register.php?item_id=$item_id&back=$back>Register</a> or if you've already registered, <a href=../login/signin.php?item_id=$item_id&back=$back>Login In</a>. </span>
<P align="center"><br>
  <?php BodyFooter();  
exit;  
}

$sqlaccess = "SELECT *
		FROM review_users
		WHERE username='$username'
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
BodyHeader("User Not Found!");
?>
<link href="../review.css" rel="stylesheet" type="text/css">


<P align="center">
<P align="center"><span class="box1">The user was not found. Please push the back button in your browser to proceed.</span>
<P align="center">
<P align="center"><br>
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
BodyHeader("Profile");
if ($use_vb == "yes") { echo $bbuserinfo['username']; } else { echo $_SESSION['name_logged']; } ?>
  's Control Panel <BR>
  <br>
<table width="80%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><p align="center"><B><FONT face=verdana,arial,helvetica color=#cc6600><br>
      About <?php echo ucfirst($name); ?></FONT></B> </p>
      <TABLE cellSpacing=2 cellPadding=2 width="100%" border=0>
        <TBODY>
          <TR>
            <TD class=small vAlign=top><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD width="150" vAlign=top class=small style="LINE-HEIGHT: 150%">&nbsp;
<?php 
//if there is an image, resize it and then display it.
$username = $_SESSION['username_logged'];
$filename = "upload/images/$username.jpg";
if (file_exists($filename)) {
$html="<img src=upload/images/resize.php?filename=$username.jpg>";
print $html;
} else { ?> <div align="center"><img src="../images/user.gif" width="48" height="48"></div> <?php } ?>
</TD>
                    <TD vAlign=top class=small style="LINE-HEIGHT: 150%"><p><B class="style3">Name:</B> <span class="medium"><?php echo ucfirst($name); ?></span><br>
                      <B class="style3">Username:</B> <span class="medium"><?php echo ucfirst($username); ?></span>                      <BR>
                          <B class="style3">E-mail:</B> <span class="medium"><A 
href="mailto:<?php echo $email; ?>"><?php echo $email; ?></A></span><BR>
                          <B class="style3">Location:</B> <span class="medium"><? echo "$city, $state $zip"; ?></span><br>
                          <span class="style3"><B>Age</B>:</span> <span class="medium"><? echo "$age"; ?></span><br>
                          <B class="style3">Profession:</B> <span class="medium"><? echo "$profession"; ?></span><br>
                          <B class="style3">Custom Signature:</B> <span class="medium"><? echo "$sig"; ?></span>                          <br>
                        <B class="style3">About me:</B> 
                      <span class="medium"><? echo "$aboutme"; ?></span> <!--
Skype 'Call me!' button
http://www.skype.com/go/skypebuttons
--><?php if ($skype != "") { ?>
<b class="style3">Skype:</b> <span class="medium">Your Skype username is set to <? echo "$skype"; ?></span> <br /> <?php } //end if skype ?>
                    <br />                   
                    </TD>
                  <TR>
                    <TD colspan="2" vAlign=top class=small style="PADDING-TOP: 5px"><hr width="85%">
                      <div align="center"><span class="style3"><img src="../images/favorites.gif" width="16" height="16"> My Favorites:</span> <br>
                        <br>
                      </div>
                      <table width="89%"  border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                          <td height="25" class="style3"><div align="left">Name</div></td>
                          <td height="25" class="style3"><div align="left">Description</div></td>
                          <td height="25"><div align="left"></div></td>
                          <td height="25"><div align="left"></div></td>
                          <BR>
                        </tr>
                        <tr>
                 <?php
				 $username = $_SESSION['username_logged'];

				 $sql = "SELECT * FROM review_favorites WHERE username='$username'";
				 
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
		
	
	?> <tr valign="top"><td>
                   <div align="left"></div>
                   <?php echo "$item_name"; ?></td>
	   <td>
	     <div align="left"></div>
	     <?php echo "$item_desc"; ?></td>
                          <td class="style3"><div align="center"><img src="../images/show_review.gif" width="16" height="16" border="0"> <a href="<?php echo "$directory"; ?>/index2.php?item_id=<?php echo "$item_id_fav"; ?>&<?=SID?>">Show Review</a></div></td>
                          <td class="style3"><div align="center"><img src="../images/delete.gif" width="16" height="16" border="0"> <a href="<?php echo "$directory"; ?>/favorites/delete.php?fav_id=<?php echo "$fav_id"; ?>&<?=SID?>">Remove</a></div><br></td>
                        </tr> <?
}
}
/*
//show names of table fields in result
$y=mysql_num_fields($sql_result2);
for ($x=0; $x<$y; $x++) {
   echo  mysql_field_name($sql_result2, $x).'<br>';
}
*/
//print_r(mysql_field_name($sql_result2, 0));
?>
                      </table>
                      <hr width="85%">
                      <div align="center"><span class="style3"><img src="../images/subscribe.gif" width="16" height="16"> My Subscriptions:</span> <br>
                          <br>
                      </div>
                      <table width="89%"  border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr valign="top">
                          <td height="25" class="style3"><div align="left">Name</div></td>
                          <td height="25" class="style3"><div align="left">Description</div></td>
                          <td height="25"><div align="left"></div></td>
                          <td height="25"><div align="left"></div></td>
                          <BR>
                        </tr>
                        <tr>
                          <?php
				 $username = $_SESSION['username_logged'];

				 $sql = "SELECT * FROM review_subscriptions WHERE username='$username'";
				 
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
                        <tr valign="top">
                          <td><div align="left"></div>
                              <?php echo "$item_name"; ?></td>
                          <td><div align="left"></div>
                              <?php echo "$item_desc"; ?></td>
                          <td class="style3"><div align="center"><img src="../images/show_review.gif" width="16" height="16" border="0"> <a href="<?php echo "$directory"; ?>/index2.php?item_id=<?php echo "$item_id_sub"; ?>&<?=SID?>">Show Review</a></div></td>
                          <td class="style3"><div align="center"><img src="../images/delete.gif" width="16" height="16" border="0"> <a href="<?php echo "$directory"; ?>/subscriptions/delete.php?sub_id=<?php echo "$sub_id"; ?>&<?=SID?>">Remove</a></div>
                              <br></td>
                        </tr>
                        <?
}
}
?>
                      </table>
                      <hr width="85%">
                      <p align="center"><br>                     
                        <?php //show navigation links on the bottom
include("user_cp_links.php"); ?>
                    </TD>
                  </TR>
                </TBODY>
              </TABLE></TD>
          </TR>
        </TBODY>
      </TABLE>
      <p>&nbsp;</p></td>
  </tr>
</table>
<?php 
BodyFooter();
exit;
?>
