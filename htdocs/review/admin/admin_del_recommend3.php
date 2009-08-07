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

$rec_id = $_POST["rec_id"];
$message = $_POST["message"];

	if (!$message) {
header("Location: http://$url$directory/admin/admin_view_recommendations.php?".sid);
			exit;
		}
	
	
	$sql = "DELETE FROM review_recommend WHERE rec_id = \"$rec_id\" LIMIT 1";

        $sql_result = mysql_query($sql) 
            or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't delete record!";

	} else {
		BodyHeader("$sitename:  Delete A Recommendation"); 
        ?>
<h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Recommendation has been deleted</font></h1>
<P> 
<table align="center">
  <tr> 
    <td valign=top><strong>Message:</strong></td>
    <td valign=top> <?php echo stripslashes($message); ?>    </td>
  </tr>
  	
		
</table>
<p>&nbsp;</p>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
  <?php 
				
        BodyFooter(); 
 }
 
?>

