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

$time = makeStringSafe($_POST["time"]);
$author = makeStringSafe($_POST["author"]);
$comment = makeStringSafe($_POST["comment"]);
$sel_record = makeStringSafe($_POST["sel_record"]);

	if (!$sel_record) {
header("Location: http://$url$directory/admin/admin_menu.php?".sid);
			exit;
		}
	
	
	$sql = "UPDATE review_comment SET 
	author = '$author',
	comment = '$comment',
	time = '$time'
    WHERE id = '$sel_record'";
	
        $sql_result = mysql_query($sql) 
            or die( "Couldn't execute update query."); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't edit record!";

	} 
		BodyHeader("$sitename:  Edit A Comment","",""); 
        ?>

<h1>
  <center>
    <BR>
    <font face="Verdana, Arial, Helvetica, sans-serif" size="3">The comment has been edited.</font>
  </center>
</h1>
<P>
<table align="center">
  <tr>
    <td valign=top><strong>Author:</strong></td>
    <td valign=top><?php echo stripslashes("$author"); ?> </td>
  </tr>
  <tr>
    <td valign=top><strong>Time:</strong></td>
    <td valign=top><?php echo stripslashes($time); ?></td>
  </tr>
  <tr>
    <td valign=top><strong>Comment:</strong></td>
    <td valign=top><?php echo stripslashes("$comment"); ?></td>
  </tr>
  	<tr>
		<td colspan=2>
		</td>
	</tr>	

</table>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br />
</div>
<?php 
				
        BodyFooter(); 
 
?>
