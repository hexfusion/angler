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

$admin_username = $_SESSION['valid_user'];		

			$sql = "SELECT * FROM review_admin WHERE username='" . mysql_real_escape_string($admin_username) . "'";
		
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {

BodyHeader("Change Username or Password!");
?>
<center><BR>
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Please enter your new username and/or password<br />
      <font size="2">(Enter both a username and a password.  If you don't want to change one of them, enter your current username or password)</font>  :</font></h1>
</center>
<FORM method="POST" action="admin_change_p2.php?<?php echo htmlspecialchars(SID); ?>">
  <table cellspacing=5 cellpadding=5>
    <tr> 
      <td valign=top><strong>Username:</strong></td>
      <td valign=top><input name="username_new" type="text" id="username_new" maxlength="10"> 
      </td>
    </tr>
    <tr> 
      <td valign=top><strong>Password:</strong></td>
      <td valign=top><input name="passtext_new" type="text" id="passtext_new" maxlength="25">
	  </td>
     
    </tr>
    <tr> 
      <td align=center colspan=2> <INPUT name="change" type="submit" id="change" value="Change"> 
      </td>
    </tr>
  </table>

</FORM>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php
 BodyFooter(); 
}
?>