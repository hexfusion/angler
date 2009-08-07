<?php
session_start();

include ('../functions.php');
include ('../f_secure.php');
include ('../body.php');
include ('../config.php');

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
} //while

	$numaccess = mysql_numrows($resultaccess);

		if ($numaccess == 0) {
BodyHeader("User Not Found!");
?>
<link href="../review.css" rel="stylesheet" type="text/css">


<P>The user was not found. Please push the back button in your browser to proceed.<br />
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
if ($use_vb == "yes") { echo $bbuserinfo['username']; } else { echo $_SESSION['name_logged']; } ?>'s Control Panel <BR>
<br />
<table width="80%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><p align="center"><B><FONT face=verdana,arial,helvetica color=#cc6600><br />
      About <?php echo ucfirst($name); ?></FONT></B> </p>
      <TABLE cellSpacing=2 cellPadding=2 width="100%" border=0>
        <TBODY>
          <TR>
            <TD class=small vAlign=top><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD width="150" vAlign=top class=small style="LINE-HEIGHT: 150%">&nbsp; <?php 
//if there is an image, resize it and then display it.
$filename = "upload/images/$username.jpg";
if (file_exists($filename)) {
$html="<img src=upload/images/resize.php?filename=$filename >";
print $html;
} 
?>
</TD>
                    <TD vAlign=top class=small style="LINE-HEIGHT: 150%"><p><B class="style3">Name:</B> <span class="medium"><?php echo ucfirst($name); ?></span><br />
                      <B class="style3">Username:</B> <span class="medium"><?php echo ucfirst($username); ?></span>                      <BR>
                          <B class="style3">E-mail:</B> <span class="medium"><A 
href="mailto:<?php echo $email; ?>"><span class="medium"><?php echo $email; ?></A></span><BR>
                          <B class="style3">Location:</B> <span class="medium"><?php echo "$city, $state $zip"; ?></span><br />
                          <span class="style3"><B>Age</B>:</span> <span class="medium"><?php echo "$age"; ?></span><br />
                          <B class="style3">Profession:</B> <span class="medium"><?php echo "$profession"; ?></span><br />
                          <B class="style3">Custom Signature:</B> <span class="medium"><?php echo "$sig"; ?></span>                          <br />
                        <B class="style3">About me:</B> 
                      <span class="medium"><?php echo "$aboutme"; ?></span>                    
                    </TD>
                  <TR>
                    <TD colspan="2" vAlign=top class=small style="PADDING-TOP: 5px"><p align="center">| <a href="profile_edit1.php?<?php echo htmlspecialchars(SID); ?>">Edit About You</a> | <a href="upload/upload.php?<?php echo htmlspecialchars(SID); ?>">Edit Photo</a> | <a href="index.php?<?php echo htmlspecialchars(SID); ?>">User CP</a> | <a href="../reviewer_reviews.php?source=<?php echo $_SESSION['name_logged']; ?>&<?php echo htmlspecialchars(SID); ?>">View myReviews</a> | <a href="../reviewer_about.php?source=<?php echo $_SESSION['name_logged']; ?>&<?php echo htmlspecialchars(SID); ?>">View myProfile</a> | </p></TD>
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
