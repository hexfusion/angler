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

$rec_id = $_POST['rec_id'];	

		if (!$rec_id) {
header("Location: http://$url$directory/admin_view_recommendations.php?".sid);
			exit;
		}
 
$sql = "select *
from review_recommend
where
rec_id = '$rec_id'
";
		
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {

			 while ($row = mysql_fetch_array($sql_result)) {

	$message = stripslashes($row["message"]);
	
}

BodyHeader("Delete Recommendation!");
?>
<center>
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected 
    the following Recommendation to DELETE:</font></h1>
</center>
<FORM action="admin_del_recommend3.php?<?php echo htmlspecialchars(SID); ?>" method="POST" name="delete" id="delete">
  <input type="hidden" name="rec_id" value="<?php echo $rec_id ?>">
    <input type="hidden" name="message" value="<?php echo $message ?>">
  <table cellspacing=5 cellpadding=5>
    <tr>
      <td valign=top><strong>Message:</strong></td>
      <td valign=top><?php echo "$message" ?> </td>
    </tr><tr>
    <td align=center colspan=2>
        <input type="submit" value="Delete Recommendation" />    </td>
  </tr>
  </table>
</FORM>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>
<?php
 BodyFooter(); 
}
?>
