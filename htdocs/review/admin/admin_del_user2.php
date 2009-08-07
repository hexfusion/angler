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

$user = $_POST['user'];

		if (!$user) {
header("Location: http://$url$directory/admin/admin_del_user.php?".sid);
			exit;
		}
		
			$sql = "SELECT username FROM review_users WHERE username='$user'";
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {

			 while ($row = mysql_fetch_array($sql_result)) {
	$username = stripslashes($row["username"]);
}			

BodyHeader("$sitename: Delete User.");
?>
<center>
  <h1>You have selected 
    the following user to DELETE:</h1>
</center>
<FORM method="POST" action="admin_del_user3.php?<?php echo htmlspecialchars(SID); ?>">
  <input type="hidden" name="username" value="<?php echo "$username"; ?>">
  <table cellspacing=5 cellpadding=5>
    <tr> 
      <td valign=top><strong>Username:</strong></td>
      <td valign=top> <?php echo "$username"; ?> 
      </td>
    </tr>

    <tr> 
      <td align=center colspan=2>   
  <INPUT type="submit" value="Delete Username"> 
      </td>
    </tr>
  </table>
</FORM>
<div align=center>Back to <a href=admin_menu.php?<?php echo htmlspecialchars(SID); ?>>Menu</a><br /></div>
<?php
BodyFooter(); 
}
?>
