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

$sel_record = clean($_GET['sel_record']);
$item_id=0;
		if (!$sel_record) {
header("Location: $url$directory/admin_edit_comments1.php?".sid);
			exit;
		}
		
			$sql = "SELECT * FROM review_comment WHERE id='$sel_record'";
		
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {

			 while ($row = mysql_fetch_array($sql_result)) {

	$id = stripslashes($row["id"]);
	$author = $row["author"];
	$time = $row["time"];
	$comment = $row["comment"];
	
}

			

BodyHeader("Edit Comment","","");
?>
<center><BR>
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected 
    the following comment for Editing:</font></h1>
</center>
<FORM method="POST" action="admin_edit_comment3.php?<?php echo htmlspecialchars(SID); ?>">
  <input type="hidden" name="sel_record" value="<?php echo $sel_record ?>">
  <table align="center" cellpadding=5 cellspacing=5>
    <tr> 
      <td valign=top><strong>Author:</strong></td>
      <td valign=top><input name="author" type="text" value="<?php echo htmlentities("author"); ?>">
	
      </td>
    </tr>
    <tr> 
      <td valign=top><strong>Time:</strong></td>
      <td valign=top><input name="time" type="text" value="<?php echo htmlentities("$time"); ?>">
    
      </td>
    </tr>
    <tr> 
      <td valign=top><strong>Comment:</strong></td>
      <td valign=top>
	  <?php echo "<textarea name=comment cols=35 rows=8>" . htmlentities($comment) . "</textarea>"; ?>
	  
	  </td>

    </tr>
    <tr>
		<td colspan=2>
		</td>
	</tr>	
	<tr> 
      <td align=center colspan=2> <INPUT type="submit" value="Edit Comment"> 
      </td>
    </tr>
  </table>

</FORM>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php
BodyFooter(); 
}
?>
