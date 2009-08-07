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

$sel_record = $_POST['sel_record'];	

		if (!$sel_record) {
header("Location: http://$url$directory/admin_approve1.php?".sid);
			exit;
		}
	
			$sql = "SELECT * FROM review 
					WHERE id='$sel_record'
					";
		
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {

			 while ($row = mysql_fetch_array($sql_result)) {

	$summary = stripslashes($row["summary"]);
	$review = stripslashes($row["review"]);
	$source = stripslashes($row["source"]);
	$date_added = $row["date_added"];
}

BodyHeader("Approve Review!");
?>
<center><BR>
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected 
    the following review for Approval:</font></h1>
</center>

  <table align="center" cellpadding=5 cellspacing=5><FORM method="POST" action="admin_approve3.php?<?php echo htmlspecialchars(SID); ?>">
  <input type="hidden" name="sel_record" value="<?php echo "$sel_record"; ?>">
    <tr> 
      <td valign=top><strong>Summary:</strong></td>
      <td valign=top> <?php echo "$summary" ?> <INPUT type="hidden" name="summary" value="<?php echo $summary ?>"> 
      </td>
    </tr>
    <tr> 
      <td valign=top><strong>Review:</strong></td>
      <td valign=top><?php echo $review ?></td>
<input type=hidden name="review" value="<?php echo htmlspecialchars($review); ?>">
    </tr>
    <tr> 
      <td valign=top><strong>Source:</strong></td>
      <td valign=top><?php echo $source ?></td>
	  <input type=hidden name="source" value="<?php echo htmlspecialchars($source); ?>">
    </tr>
    <tr> 
      <td valign=top><strong>Date Added:</strong></td>
      <td valign=top><?php echo $date_added ?>
      <INPUT type="hidden" name="date_added" value="<?php echo "$date_added" ?>"></td></tr>
    <tr> 
      <td align=center colspan=2>  
   <INPUT type="submit" value="Approve Review"> 
      </td>
</FORM>    </tr>
<tr><td align=center><form action="admin_edit2.php?<?php echo htmlspecialchars(SID); ?>" method="post" name="edit">
<input type="hidden" name="sel_record" value="<?php echo $sel_record ?>">
<INPUT type="hidden" name="date_added" value="<?php echo "$date_added" ?>">
<input type=hidden name="source" value="<?php echo htmlspecialchars($source); ?>">
<input type=hidden name="review" value="<?php echo htmlspecialchars($review); ?>">
<INPUT type="hidden" name="summary" value="<?php echo $summary ?>"> 
<input name="Edit" type="submit" value="Edit"></form></td></tr>
</table>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php
 BodyFooter(); 
}
?>