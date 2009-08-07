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

$username = $_POST['username'];

	if (!$username) {
header("Location: http://$url$directory/admin/admin_del_user.php?".sid);
			exit;
		}
	
	$sql = "DELETE FROM review_users WHERE username = '$username'";
        $sql_result = mysql_query($sql) 
            or die(sprintf("Couldn't execute query 1, %s: %s", db_errno(), db_error())); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't delete record!";

	} 
//remove from the review table
$sql2 = "UPDATE review
	SET
	username = '' 
	WHERE username = '$username'";

        $sql_result2 = mysql_query($sql2) 
            or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	if (!$sql_result2) {  
   
		echo "<P>Couldn't update record from review_items!";

	} 
else {
		BodyHeader("$sitename:  Delete A User"); 
        ?>
<h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have deleted 
  <?php echo stripslashes($username); ?> from the User Database</font></h1>
<P> 
<table align="center">
  <tr> 
    <td valign=top><strong>Username:</strong></td>
    <td valign=top> <?php echo stripslashes($username); ?> 
    </td>
  </tr>
  
</table>
<div align="center">
  <p><br />
    Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br />  </p>
</div>
    <?php 
				
        BodyFooter(); 
 }
 
?>

