<?php
//if a session does not yet exist for this user, start one
session_start();

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

$username_new = $_POST["username_new"];
$passtext_new = $_POST["passtext_new"];
$admin_username = $_SESSION['valid_user'];		




$sql = "DELETE FROM `review_admin`";

		//execute the query
$result = mysql_query($sql,$connection) or die(mysql_error());


//append salt to users entered password. 
$salted_pass = $passtext_new . $salt; 

//hash the result
$hashed_pass = md5( $salted_pass ); 

$sql = "INSERT INTO `review_admin` ( `username` , `passtext` )
VALUES (  '" . mysql_real_escape_string($username_new) . "', '$hashed_pass' );";
		//execute the query
$result = mysql_query($sql,$connection) or die(mysql_error());

	if (!$result) {  
   
		echo "<P>Couldn't edit record!";

	} else {
		BodyHeader("$sitename:  Change Username or Password","",""); 
        ?>
<h1><center><BR>
<font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have changed your username and/or password to: </font>
</center></h1>
<P> 
<table align="center">
  <tr> 
    <td valign=top><strong>Username:</strong></td>
    <td valign=top> <?php echo stripslashes($username_new); ?>  
    </td>
  </tr>
  <tr> 
    <td valign=top><strong>Password:</strong></td>
    <td valign=top><?php echo stripslashes($passtext_new); ?></td>
    </td>
  </tr>
</table>
<br />
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
  <?php 
        BodyFooter(); 
}
?>